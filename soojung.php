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
