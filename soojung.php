<?php
@include_once("config.php");
include("libs/util.php");

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

/** The function which matchs with regex query. This function using 
 *  ereg to match query string.
 */

function detect_encoding ($string) {
  if (iconv("UTF-8", "UTF-8", $string) != FALSE) {
    return "UTF-8";
  } else if (iconv("CP949", "UTF-8", $string) != FALSE) {
    return "CP949";
  }
  return FALSE;
}

/** convert any encoding string to utf8 string
 * @param string input string of unkown encoding
 * @return utf8 converted string or FALSE on failure.
 */

function convert_to_utf8 ($string) {
  $result = "";
  if (($result = iconv("UTF-8", "UTF-8", $string)) != FALSE) {
    return $result;
  } else if (($result = iconv("CP949", "UTF-8", $string)) != FALSE) {
    return $result;
  }
  return FALSE;
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
  $fd = fopen("contents/.info", "r");
  $i = trim(fread($fd, filesize("contents/.info")));
  fclose($fd);
  $fd = fopen("contents/.info", "w");
  fwrite($fd, $i + 1);
  fclose($fd);
  return $i;
}

function blogid_to_filename($blogid) {
  $f = query_filename_match("_" . $blogid . "[.]entry$");
  return $f[0];

}

function notify_to_admin($title, $blogid, $msg) {
  global $notify, $admin_email;
  if ($notify != true) {
    return;
  }
  $entry = get_entry($blogid);
  $message = "<html><head></head><body>";
  $message .= $msg;
  $message .= "<br /><a href=" . $entry['link'] . "\">check out</a>";
  $message .= "</body></html>";
  mail($admin_email, $title, $message, "Content-Type: text/html; charset=\"utf-8\"");
}

function entry_open($filename) {
  global $blog_baseurl, $blog_fancyurl;
  $fd = fopen($filename, "r");
  $data = fread($fd, filesize($filename));
  fclose($fd);
  $data = split("\r\n", $data, 3);
  $entry = array();
  $entry["title"] = htmlspecialchars($data[0], ENT_QUOTES, "UTF-8");
  $entry["date"] = $data[1];

  $entry["body"] = pre_nl2br($data[2]);

  $t = split("_", $filename, 3);
  $category = $t[1];
  $id =  substr($t[2], 0, -6); // remove '.entry'

  $entry["category"] = $category;
  $entry["id"] = $id;

  if ($blog_fancyurl == true) {
    $entry["link"] = $blog_baseurl . "/" . $entry['category'] . date("/Y/m/d/", $entry['date']) .  $id . ".html";
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

function entry_new($title, $body, $date, $category="Default") {
  $id = create_new_id();
  if (!isset($category)) {
    $category = "Default";
  }
  _entry_write($title, $body, $date, $category, $id);
  return $id;
}

function entry_edit($blogid, $title, $body, $date, $category) {
  if (file_exists(blogid_to_filename($blogid)) !== TRUE)
    return FALSE;
  unlink(blogid_to_filename($blogid));
  _entry_write($title, $body, $date, $category, $blogid);
  return TRUE;
}

function entry_delete($blogid) {
  unlink(blogid_to_filename($blogid));
  rmdirr("contents/" . $blogid);
}

function get_entry_count() {
  return count(query_filename_match("[.]entry$"));
}

function get_entry_count_by_category($category) {
  $filenames = query_filename_match("[^_]+_" . $category . "_[^.]+[.]entry$");
  return count($filenames);
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

function get_recent_entries($n) {
  return get_entries($n, 1);
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

  $msg =  $name . " said:<br />";
  $msg .= $body;
  notify_to_admin("new comment", $blogid, $msg);
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

function get_recent_comments($n) {
  $comment_filenames = array();
  $dirs = query_filename_match("^[0-9]+$", "contents/");
  foreach ($dirs as $dir) {
    $files = query_filename_match("[.]comment$", $dir . "/");
    foreach ($files as $file) {
      $comment_filenames[] = $file;
    }
  }
  usort($comment_filenames, "cmp_base_filename");

  $comment_filenames = array_slice($comment_filenames, 0, $n);
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

  $msg = "trackback from " . $url . "<br />";
  notify_to_admin("new trackback", $blogid, $msg);
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

function get_recent_trackbacks($n) {
  $filenames = array();
  $dirs = query_filename_match("^[0-9]+$", "contents/");
  foreach ($dirs as $dir) {
    $files = query_filename_match("[.]trackback$", $dir . "/");
    foreach ($files as $file) {
      $filenames[] = $file;
    }
  }
  usort($filenames, "cmp_base_filename");
  $filenames = array_slice($filenames, 0, $n);
  $trackbacks = array();
  foreach ($filenames as $f) {
    $trackbacks[] = trackback_open($f);
  }
  return $trackbacks;
}

function send_trackbackping($blogid, $trackback_url, $encoding='UTF-8') {
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
    $tb_title = rawurlencode(iconv("UTF-8", $encoding, $entry['title']));
  else
    $tb_title = rawurlencode('title');

  $tb_excerpt = iconv("UTF-8", $encoding, $entry['body']);
  if (strlen ($tb_excerpt) > 255)
    $tb_excerpt = substr($tb_excerpt,0, 252) . "...";
  $tb_excerpt = rawurlencode($tb_excerpt);


  
  if (isset($blog_name))
    $tb_blogname = rawurlencode(iconv("UTF-8", $encoding, $blog_name));
  else
    $tb_blogname = rawurlencode('soojung blog');

  $query_string = "title=$tb_title&url=$permlink&excerpt=$tb_excerpt&blog_name=$tb_blogname";
  $query_string = iconv( "UTF-8", $encoding, $query_string);
  echo "query_string : $query_string<br>";
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

/** add personal bookmark file 
 *
 */

function add_bookmark($url, $desc) {
  if (empty($desc))
    $desc = $url;

  $bookmark = array("url" => $url, "desc" => $desc);
  $bookmarks = get_bookmark_list();
  foreach ($bookmarks as $b) {
    if ($b['url'] == $bookmark['url'])
      return false;
  }

  $bookmarks[] = $bookmark;
  write_bookmark($bookmarks);
  return true;
}

/** delete personal bookmark, key is url (not description) 
 *
 */

function delete_bookmark($url) {
  $bookmakrs = get_bookmark_list();
  $new_bookmakrs = array();
  foreach($bookmakrs as $b) {
    if ($b['url'] !== $url)
      $new_bookmarks[] = $b;
  }
  write_bookmark($new_bookmarks);
}

/** writing $bookmarks to file
 *
 */

function write_bookmark($bookmarks) {
  $fd = fopen("contents/.bookmark", "w");
  if (!empty($bookmarks)) {
    foreach ($bookmarks as $b) {
      fwrite ($fd, $b['url'] . " " . $b['desc'] . "\r\n");
      //    fprintf ($fd, "%s %s\r\n", $b['url'], $b['desc']);
    }
  }
  fclose($fd);

}

/** read $bookmarks from file
 *
 */


function get_bookmark_list() {
  $bookmarks = array();
  if (!file_exists("contents/.bookmark")) {
    return $bookmarks;
  }
  $fd = fopen("contents/.bookmark", "r");
  while (!feof($fd)) {
    $line = trim(fgets($fd, 1024));
    $b = explode(" ", $line, 2);
    if (!empty($b[0]))
      $bookmarks[] = array("url"=>$b[0], "desc" => $b[1]);
  }
  return $bookmarks;
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
    $category["rss"] = $blog_baseurl . "/rss2.php?category=" . $file;
    $category["count"] = get_entry_count_by_category($file);
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

function write_config_file($blogname, $blogdesc, $blogurl, $perpage, $blogfancyurl, $blognotify,
			   $adminname, $adminemail, $adminpassword, $skin = "default") {
  $fd = fopen("config.php", "w");
  fwrite($fd, "<?php\n");
  fwrite($fd, '$blog_name="' . $blogname . "\";\n");
  fwrite($fd, '$blog_desc="' . $blogdesc . "\";\n");
  fwrite($fd, '$blog_baseurl="' . $blogurl . "\";\n");
  fwrite($fd, '$blog_entries_per_page=' . $perpage . ";\n");
  if ($blogfancyurl == "on") {
    fwrite($fd, '$blog_fancyurl=true;' . "\n");
  } else {
    fwrite($fd, '$blog_fancyurl=false;' . "\n");
  }
  if ($blognotify == "on") {
    fwrite($fd, '$notify=true;' . "\n");
  } else {
    fwrite($fd, '$notify=false;' . "\n");
  }
  fwrite($fd, '$blog_skin="' . $skin . "\";\n");
  fwrite($fd, '$admin_name="' . $adminname . "\";\n");
  fwrite($fd, '$admin_email="' . $adminemail . "\";\n");
  if ($adminpassword === FALSE) {
    global $admin_password;
    fwrite($fd, '$admin_password="' . $admin_password . "\";\n");
  } else {
    fwrite($fd, '$admin_password="' . $adminpassword . "\";\n");
  }
  fwrite($fd, "?>");
  fclose($fd);
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

  if ($fd = @fopen ("contents/.count", "r")) {
    $last_date = trim(fgets($fd,256));
    $today_count = trim(fgets($fd,256));
    $total_count = trim(fgets($fd,256));
    fclose($fd);
  }

  if ($soojungcountercookie != "on" && !stristr($_SERVER['HTTP_USER_AGENT'], "googlebot")) {
    $today_count += 1;
    $total_count += 1;
    $modified = true;
  }
  if ($today != $last_date) {
    $modified = true;
    $today_count = 0;
  }

  if ($modified) {
    $fd = fopen ("contents/.count", "w");
    fwrite($fd, $today);
    fwrite($fd, "\n");
    fwrite($fd, $today_count);
    fwrite($fd, "\n");
    fwrite($fd, $total_count);
    fwrite($fd, "\n");
    fclose($fd);
  }
}

function add_referer() {
  if (isset($_SERVER['HTTP_REFERER'])) {
    global $blog_baseurl;
    $referer = $_SERVER['HTTP_REFERER'];

    if(strstr($referer, $blog_baseurl) != FALSE) { //local
      return;
    }

    if ($fd = @fopen("contents/.referer", "r")) {
      $data = fread($fd, filesize("contents/.referer"));
      fclose($fd);
      $data = $referer . "\r\n" . $data;
    } else {
      $data = $referer;
    }

    //TODO: 최근 10개만 저장하기
    $fd = fopen("contents/.referer", "w");
    fwrite($fd, $data);
    fclose($fd);
  }
}

function get_recent_referers($n) {
  if ($fd = @fopen("contents/.referer", "r")) {
    $data = fread($fd, filesize("contents/.referer"));
    $array = split("\r\n", $data);
    return array_slice($array, 0, $n);
  }
}

function file_to_xml($filename) {
  $fd = fopen($filename, "rb");
  $data = fread($fd, filesize($filename));
  $data = htmlspecialchars($data);
  fclose($fd);

  $xml = "\t<file>\n";
  $xml .= "\t\t<name>" . $filename . "</name>\n";
  $xml .= "\t\t<data>" . $data . "</data>\n";
  $xml .= "\t</file>\n";
  return $xml;
}

function to_xml($path) {
  if ($dh = opendir($path)) {
    while (($file = readdir($dh)) !== false) {
      if ($file == ".." || $file == ".") {
	continue;
      }
      $filename = $path . '/' . $file;
      if (is_dir($filename)) {
	$xml .= to_xml($filename);
      } else {
	$xml .= file_to_xml($filename);
      }
    }
    closedir($dh);
  }
  return $xml;
}

function export() {
  $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  $xml .= "<soojung>\n";
  $xml .= to_xml("contents");
  $xml .= "</soojung>";
  return $xml;
}

function trans($str) {
  $trans = get_html_translation_table(HTML_ENTITIES);
  $trans = array_flip($trans);
  return strtr($str, $trans);
}

function import($uploadfile) {
  $fd = fopen($uploadfile['tmp_name'], "rb");
  $data = fread($fd, $uploadfile['size']);
  fclose($fd);

  while (($pos_s = strpos($data, "<file>", $pos_e)) !== FALSE) {
    $pos_e = strpos($data, "</file>", $pos_s) + strlen("</file>");
    create_file(substr($data, $pos_s, $pos_e - $pos_s));
  }
}

function create_file($xml) {
  $name_pos = strpos($xml, "<name>") + strlen("<name>");
  $name_end = strpos($xml, "</name>");
  $name = substr($xml, $name_pos, $name_end - $name_pos);

  $dir = dirname($name);
  if (file_exists($dir) == FALSE) {
    mkdir($dir, 0777); //TODO: mkdirr
  }
  $fd = fopen($name, "wb");

  $data_pos = strpos($xml, "<data>") + strlen("<data>");
  $data_end = strpos($xml, "</data>");
  $data = substr($xml, $data_pos, $data_end - $data_pos);

  fwrite($fd, trans($data));
  fclose($fd);
}

function import_tt($db_server, $db_user, $db_pass, $db_name, $encoding) {
  $link = mysql_connect($db_server, $db_user, $db_pass) or die("could not connect");
  mysql_select_db($db_name) or die("could not select database");

  $query = "SELECT title, body, regdate, category1 FROM t3_tts";
  $result = mysql_query($query) or die("query failed");

  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $c_no = $line['category1'];
    $c_query = "SELECT label FROM t3_tts_ct1 WHERE no = " . $c_no;
    $c_result = mysql_query($c_query);
    $c_line = mysql_fetch_array($c_result);

    if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
      $title = $line['title'];
      $body = $line['body'];
      $category = $c_line['label'];
    } else {
      $title = iconv($encoding, "UTF-8", $line['title']);
      $body = iconv($encoding, "UTF-8", $line['body']);
      $category = iconv($encoding, "UTF-8", $c_line['label']);
    }
    $date = $line['regdate'];

    entry_new($title, $body, $date, $category);
    mysql_free_result($c_result);
  }

  mysql_free_result($result);
  mysql_close($link);
}

function import_wp($db_server, $db_user, $db_pass, $db_name, $prefix, $encoding) {
  $link = mysql_connect($db_server, $db_user, $db_pass) or die("could not connect");
  mysql_select_db($db_name) or die("could not select database");

  $query = "select post_date, post_content, post_title, post_category from " . $prefix . "posts";
  $result = mysql_query($query) or die("query failed");

  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $c_no = $line['post_category'];
    $c_query = "select cat_name from " . $prefix . "categories where cat_ID = " . $c_no;
    $c_result = mysql_query($c_query);
    $c_line = mysql_fetch_array($c_result);

    $category = isset($c_line['cat_name']) ? $c_line['cat_name'] : "General"; //'General' is wp default category

    if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
      $title = $line['post_title'];
      $body = $line['post_content'];
    } else {
      $title = iconv($encoding, "UTF-8", $line['post_title']);
      $body = iconv($encoding, "UTF-8", $line['post_content']);
      $category = iconv($encoding, "UTF-8", $category);
    }
    $date = strtotime($line['post_date']);

    entry_new($title, $body, $date, $category);
    mysql_free_result($c_result);
  }
  mysql_free_result($result);
  mysql_close($link);
}

/** Balances Tags of string using a modified stack.
 * @param text      Text to be balanced
 * @return          Returns balanced text
 * @notes           
 * import from Wordpress
 * add balance quot
 */

function balanceTags($text, $is_comment = 0) {
  /*	
  if (get_settings('use_balanceTags') == 0) {
    return $text;
  }
  */
  $tagstack = array(); 
  $stacksize = 0; 
  $tagqueue = ''; 
  $newtext = '';

# WP bug fix for comments - in case you REALLY meant to type '< !--'
  $text = str_replace('< !--', '<    !--', $text);
# WP bug fix for LOVE <3 (and other situations with '<' before a number)
  $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

  // close &quot; which isn't closed in MARKUP
  $text = ereg_replace("<([^=]*)=\"([^>\"]*)>", "<\\1=\"\\2\">", $text);
  // match MARKUP tag, $regex[1] is tagname.
  echo "<pre>";
  while (preg_match("/<(\/?\w*)\s*([^>]*)>/",$text,$regex)) {
    print_r ($regex);
    echo "<br>";
    $newtext = $newtext . $tagqueue;

    $i = strpos($text,$regex[0]);
    $l = strlen($tagqueue) + strlen($regex[0]);

    // clear the shifter
    $tagqueue = '';
    // Pop or Push
    if ($regex[1][0] == "/") { // End Tag
      $tag = strtolower(substr($regex[1],1));
      // if too many closing tags
      if($stacksize <= 0) { 
	$tag = '';
	//or close to be safe $tag = '/' . $tag;
      }
      // if stacktop value = tag close value then pop
      else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
	$tag = '</' . $tag . '>'; // Close Tag
	// Pop
	array_pop ($tagstack);
	$stacksize--;
      } else { // closing tag not at top, search for it
	for ($j=$stacksize-1;$j>=0;$j--) {
	  if ($tagstack[$j] == $tag) {
	    // add tag to tagqueue
	    for ($k=$stacksize-1;$k>=$j;$k--){
	      $tagqueue .= '</' . array_pop ($tagstack) . '>';
	      $stacksize--;
	    }
	    break;
	  }
	}
	$tag = '';
      }
    } else { // Begin Tag
      $tag = strtolower($regex[1]);

      // Tag Cleaning

      // Push if not img or br or hr
      if($tag != 'br' && $tag != 'img' && $tag != 'hr' ) {
	$stacksize = array_push ($tagstack, $tag);
      }

      // Attributes
      // $attributes = $regex[2];
      $attributes = $regex[2];
      if($attributes) {
	$attributes = ' '.$attributes;
      }
      $tag = '<'.$tag.$attributes.'>';
    }
    $newtext .= substr($text,0,$i) . $tag;
    $text = substr($text,$i+$l);
  }  

  // Clear Tag Queue
  $newtext = $newtext . $tagqueue;

  // Add Remaining text
  $newtext .= $text;

  // Empty Stack
  while($x = array_pop($tagstack)) {
    $newtext = $newtext . '</' . $x . '>'; // Add remaining tags to close      
  }

  // WP fix for the bug with HTML comments
  $newtext = str_replace("< !--","<!--",$newtext);
  $newtext = str_replace("<    !--","< !--",$newtext);

  echo "</pre>";
  return $newtext;
}
?>
