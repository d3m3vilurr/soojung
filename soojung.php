<?php
@include_once("config.php");

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

setcookie("soojungcountercookie", "on", 0);


if (get_magic_quotes_gpc()) {
  function stripslashes_deep($value) {
    $value = is_array($value) ?
      array_map('stripslashes_deep', $value) :
      stripslashes($value);
    return $value;
  }
  $_POST = array_map('stripslashes_deep', $_POST);
  $_GET = array_map('stripslashes_deep', $_GET);
  $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function br2nl( $data ) {
   return preg_replace( '!<br.*>!iU', "", $data );
}

function query_filename_match($query, $dir="contents/") {
  $list = array();
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
	if (ereg($query, $file)) {
	  $list[] = $dir . $file;
	}
      }
    }
  }
  return $list;
}

function create_new_id() {
  $f = fopen("contents/.info", "r");
  $i = fread($f, filesize("contents/.info"));
  fclose($f);
  $f = fopen("contents/.info", "w");
  fwrite($f, $i + 1);
  fclose($f);
  return $i;
}

function blogid_to_filename($blogid) {
  $f = query_filename_match("_" . $blogid . "[.]entry$");
  return $f[0];

}

#FIXME: XXX
function pre_nl2br($string) {
  $s = $string;
  $pos = strpos($s, "<pre");
  if ($pos === false) {
    return nl2br($s);
  }
  $a = substr($s, 0, $pos);
  print $a;
  $a = nl2br($a);
  $end = strpos($s, "</pre>");
  $a = $a . substr($s, $pos, $end);
  $a = $a . nl2br(substr($s, $end, -1));
  return $a;
}

function notify_to_admin($title, $blogid) {
  global $admin_email;
  $entry = get_entry($blogid);
  $body = "<html><head></head><body><a href=" . $entry['link'] . "\">check out</a></body></html>";
  mail($admin_email, $title, $body);
}

function entry_open($filename) {
  global $blog_baseurl, $blog_fancyurl;
  $fd = fopen($filename, "r");
  $data = fread($fd, filesize($filename));
  fclose($fd);
  $data = split("\r\n", $data, 3);
  $entry = array();
  $entry["title"] = $data[0];
  $entry["date"] = $data[1];

  $entry["body"] = pre_nl2br($data[2]);

  $t = split("_", $filename, 3);
  $category = $t[1];
  $id =  substr($t[2], 0, -6); // remove '.entry'

  $entry["category"] = $category;
  $entry["id"] = $id;

  if ($blog_fancyurl == true) {
    $entry["link"] = $entry['category'] . date("/Y/m/d/", $entry['date']) .  $id . ".html";
  } else {
    $entry["link"] = $blog_baseurl . "/entry.php?blogid=" . $id;
  }

  $entry["comment_count"] = get_comment_count($id);
  $entry["trackback_count"] = get_trackback_count($id);

  return $entry;
}

function _entry_write($title, $body, $date, $category, $id) {
  $filename = date('YmdHis', $date) . '_' . $category . '_' . $id . '.entry';
  $fd = fopen('contents/' . $filename, "w");
  fwrite($fd, $title);
  fwrite($fd, "\r\n");
  fwrite($fd, $date);
  fwrite($fd, "\r\n");
  fwrite($fd, $body);
  fclose($fd);
}

function entry_new($title, $body, $date, $category) {
  _entry_write($title, $body, $date, $category, create_new_id());
}

function entry_edit($blogid, $title, $body, $date, $category) {
  unlink(blogid_to_filename($blogid));
  _entry_write($title, $body, $date, $category, $blogid);
}

function get_entry_count() {
  return count(query_filename_match("[.]entry$"));
}

function get_entry($blogid) {
  return entry_open(blogid_to_filename($blogid));
}

function get_entries($count, $page) {
  $entries = array();
  $filenames = query_filename_match("[.]entry$");
  rsort($filenames);
  $index = ($page - 1) * $count;
  for ($i = $index; $i < count($filenames) && $i < ($index + $count); $i++) {
    $entries[] = entry_open($filenames[$i]);
  }
  return $entries;
}

function get_recent_entries() {
  return get_entries(10, 1);
}

function entry_search($query) {
  $filenames = query_filename_match("[.]entry$");
  rsort($filenames);
  $founds = array();
  foreach($filenames as $f) {
    $fd = fopen($f, "r");
    $data = fread($fd, filesize($f));
    fclose($fd);
    if (strpos($data, $query) !== FALSE) {
      $founds[] = entry_open($f);
    }
  }
  return $founds;
}

function comment_open($filename) {
  $fd = fopen($filename, "r");
  $data = fread($fd, filesize($filename));
  fclose($fd);
  $data = split("\r\n", $data, 5);
  $comment = array();
  $comment["date"] = $data[0];
  $comment["name"] = $data[1];
  $comment["email"] = $data[2];
  $comment["url"] = $data[3];
  $comment["body"] = $data[4];
  
  $paths = split("/", $filename);
  $id = $paths[1];

  $entry = get_entry($id);
  $comment["link"] = $entry['link'] . "#" . $comment["date"];
  $comment["filename"] = $filename;
  return $comment;
}

function comment_write($blogid, $name, $email, $url, $body, $date) {
  $dirname = "contents/" . $blogid;
  @mkdir($dirname, 0777);
  $filename = date('YmdHis', $date) . '.comment';
  $fd = fopen($dirname . '/' . $filename, "w");
  fwrite($fd, $date);
  fwrite($fd, "\r\n");
  fwrite($fd, $name);
  fwrite($fd, "\r\n");
  fwrite($fd, $email);
  fwrite($fd, "\r\n");
  fwrite($fd, $url);
  fwrite($fd, "\r\n");
  fwrite($fd, $body);
  fclose($fd);

  notify_to_admin("new comment", $blogid);
}

function get_comment_count($blogid) {
  $r = query_filename_match("[.]comment$", "contents/" . $blogid);
  return count($r);
}

function get_comments($blogid) {
  $comments = array();
  $filenames = query_filename_match("[.]comment$", "contents/" . $blogid . "/");
  sort($filenames);
  foreach($filenames as $filename) {
    $comments[] = comment_open($filename);
  }
  return $comments;
}

function get_recent_comments() {
  $comment_filenames = array();
  $dirs = query_filename_match("^[0-9]+$", "contents/");
  foreach ($dirs as $dir) {
    $files = query_filename_match("[.]comment$", $dir . "/");
    foreach ($files as $file) {
      $comment_filenames[] = $file;
    }
  }
  rsort($comment_filenames);
  $comment_filenames = array_slice($comment_filenames, 0, 10);
  $comments = array();
  foreach ($comment_filenames as $f) {
    $comments[] = comment_open($f);
  }
  return $comments;
}

function trackback_open($filename) {
  $fd = fopen($filename, "r");
  $data = fread($fd, filesize($filename));
  fclose($fd);
  $data = split("\r\n", $data, 4);
  $trackback = array();
  $trackback["url"] = $data[0];
  $trackback["name"] = $data[1];
  $trackback["title"] = $data[2];
  $trackback["excerpt"] = $data[3];

  $paths = split("/", $filename);
  $entry = get_entry($paths[1]);

  $t = split("[.]", $paths[2], 2);
  $trackback["date"] = $t[0];

  $trackback["link"] = $entry['link'] . "#" . $trackback["date"];
  $trackback["filename"] = $filename;
  return $trackback;
}

function trackback_write($blogid, $url, $name, $title, $excerpt) {
  $dirname = "contents/" . $blogid;
  @mkdir($dirname, 0777);
  $filename = date('YmdHis', time()) . '.trackback';
  $fd = fopen($dirname . '/' . $filename, "w");
  fwrite($fd, $url);
  fwrite($fd, "\r\n");
  fwrite($fd, $name);
  fwrite($fd, "\r\n");
  fwrite($fd, $title);
  fwrite($fd, "\r\n");
  fwrite($fd, $excerpt);
  fwrite($fd, "\r\n");
  fclose($fd);

  notify_to_admin("new trackback", $blogid);
}

function get_trackbacks($blogid) {
  $trackbacks = array();
  $filenames = query_filename_match("[.]trackback$", "contents/" . $blogid . "/");
  sort($filenames);
  foreach($filenames as $filename) {
    $trackbacks[] = trackback_open($filename);
  }
  return $trackbacks;
}

function get_trackback_count($blogid) {
  $r = query_filename_match("[.]trackback$", "contents/" . $blogid);
  return count($r);
}

function get_recent_trackbacks() {
  $filenames = array();
  $dirs = query_filename_match("^[0-9]+$", "contents/");
  foreach ($dirs as $dir) {
    $files = query_filename_match("[.]trackback$", $dir . "/");
    foreach ($files as $file) {
      $filenames[] = $file;
    }
  }
  rsort($filenames);
  $filenames = array_slice($filenames, 0, 10);
  $trackbacks = array();
  foreach ($filenames as $f) {
    $trackbacks[] = trackback_open($f);
  }
  return $trackbacks;
}

function send_trackbackping($blogid, $trackback_url) {
  global $blog_name;
  
  $tb_url = parse_url($trackback_url);
  if (isset ($tb_url['port']))
    $tb_port = $tb_url['port'];
  else
    $tb_port = 80;

  //  $permlink = rawurlencode(get_entry_link($blogid, false));
  $entry = get_entry($blogid);
  $permlink = $entry['link'];
  if ( isset($entry['title']))
    $tb_title = rawurlencode($entry['title']);
  else
    $tb_title = rawurlencode('title');

  $tb_excerpt = $entry['body'];
  if (strlen ($tb_excerpt) > 255)
    $tb_excerpt = substr($tb_excerpt,0, 252) . "...";
  $tb_excerpt = rawurlencode($tb_excerpt);

  if (isset($blog_name))
    $tb_blogname = rawurlencode($blog_name);
  else
    $tb_blogname = rawurlencode('soojung blog');

  $query_string = "title=$tb_title&url=$permlink&excerpt=$tb_excerpt&blog_name=$tb_blogname";

  $http_request  = 'POST '.$trackback_url." HTTP/1.0\r\n";
  $http_request .= 'Content-Type: application/x-www-form-urlencoded'."\r\n";
  $http_request .= 'Content-Length: '.strlen($query_string)."\r\n\r\n";
  $http_request .= $query_string;

  $response = array();

  if (!($fp = fsockopen($tb_url['host'], $tb_port))) {
    // Cannot open trackback url
    $response['error'] = 1;
    $response['message'] = "Cannot connect to host \"".$tb_url['host']."\"";
    echo "merong<br>\n";
    return $response;
  } 

  if (!fputs($fp, $http_request)) {
    echo "cannot send trackback ping<br>\n";

  }
  $line = "";
  while (!feof ($fp)) {
    $line .= fgets ($fp, 1024);
  }

  if (ereg("<error>[^<0-9]*([0-9]*)[^<0-9]*</error>", $line, $regs)) {
    $response['error'] = $regs[1];
    if ($response == 0 && ereg("<message>([<]*)</message>", $line, $regs)) {
      $response['message'] = $regs[1];
    }
      
  }

  fclose ($fp);
  return $response;
}

function get_archive_list() {
  global $blog_baseurl, $blog_fancyurl;

  $archives = array();
  $files = array();
  $filenames = query_filename_match("[.]entry$");
  foreach($filenames as $filename) {
    $t = substr($filename, 9);
    $t = substr($t, 0, 6);
    $files[] = $t;
  }
  rsort($files);
  $files = array_unique($files);

  foreach($files as $file) {
    $archive = array();

    $year = substr($file, 0, 4);
    $month = substr($file, 4);
    if ($blog_fancyurl) {
      $link = $blog_baseurl . '/' . $year . '/' . $month;
    } else {
      $link = $blog_baseurl . '/index.php?archive=' . $file;
    }

    $archive["name"] = $file;
    $archive["link"] = $link;
    $archives[] = $archive;
  }
  return $archives;
}

function get_archive_entries($date) {
  $filenames = query_filename_match($date . "[^.]+[.]entry$");
  rsort($filenames);
  $entries = array();
  foreach($filenames as $filename) {
    $entries[]  = entry_open($filename);
  }
  return $entries;
}

function get_category_list() {
  global $blog_baseurl, $blog_fancyurl;

  $categories = array();
  $files = array();
  $filenames = query_filename_match("[.]entry$");
  foreach($filenames as $filename) {
    $t = split("_", $filename, 3);
    $files[] = $t[1];
  }
  sort($files);
  $files = array_unique($files);

  foreach($files as $file) {
    $category = array();
    if ($blog_fancyurl) {
      $link = $blog_baseurl . '/' . $file;
    } else {
      $link = $blog_baseurl . '/index.php?category=' . $file;
    }
    $category["name"] = $file;
    $category["link"] = $link;
    $categories[] = $category;
  }
  return $categories;
}

function get_category_entries($category) {
  $filenames = query_filename_match("[^_]+_" . $category . "_[^.]+[.]entry$");
  rsort($filenames);
  $entries = array();
  foreach($filenames as $filename) {
    $entries[]  = entry_open($filename);
  }
  return $entries;
}

function write_config_file($blogname, $blogdesc, $blogurl, $perpage, $blogfancyurl,
			   $adminname, $adminemail, $adminpassword, $skin = "default") {
  $f = fopen("config.php", "w");
  fwrite($f, "<?php\n");
  fwrite($f, '$blog_name="' . $blogname . "\";\n");
  fwrite($f, '$blog_desc="' . $blogdesc . "\";\n");
  fwrite($f, '$blog_baseurl="' . $blogurl . "\";\n");
  fwrite($f, '$blog_entries_per_page=' . $perpage . ";\n");
  if ($blogfancyurl == "on") {
    fwrite($f, '$blog_fancyurl=true;' . "\n");
  } else {
    fwrite($f, '$blog_fancyurl=false;' . "\n");
  }
  fwrite($f, '$blog_skin="' . $skin . "\";\n");
  fwrite($f, '$admin_name="' . $adminname . "\";\n");
  fwrite($f, '$admin_email="' . $adminemail . "\";\n");
  if ($adminpassword === FALSE) {
    global $admin_password;
    fwrite($f, '$admin_password="' . $admin_password . "\";\n");
  } else {
    fwrite($f, '$admin_password="' . $adminpassword . "\";\n");
  }
  fwrite($f, "?>");
  fclose($f);
}

function get_count() {
  global $soojungcountercookie;
  global $today_count;
  global $total_count;
  $today_count = 0;
  $total_count = 0;
  $last_date = date("Y-m-d");
  $today = date("Y-m-d");
  $modified = false;

  if ($fp = @fopen ("contents/.count", "r")) {
    $last_date = trim(fgets($fp,256));
    $today_count = trim(fgets($fp,256));
    $total_count = trim(fgets($fp,256));
    fclose($fp);
  }

  if ($soojungcountercookie != "on") {
    $today_count += 1;
    $total_count += 1;
    $modified = true;
  }
  if ($today != $last_date) {
    $modified = true;
    $today_count = 0;
  }

  if ($modified) {
    $fp = fopen ("contents/.count", "w");
    fwrite($fp, $today);
    fwrite($fp, "\n");
    fwrite($fp, $today_count);
    fwrite($fp, "\n");
    fwrite($fp, $total_count);
    fwrite($fp, "\n");
    fclose($fp);
  }
}
?>
