<?php

//deprecate source file

@include_once("config.php");
include("libs/util.php");

setcookie("soojungcountercookie", "on", 0);

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

?>
