<?php

$soojung_version = '0.4.4';
setlocale(LC_TIME, "C");

if (file_exists("config.php")) {
  include_once("config.php");
}

include_once("libs/util.php");

include_once("classes/Formatter.class.php");
include_once("classes/Entry.class.php");
include_once("classes/Soojung.class.php");
include_once("classes/Comment.class.php");
include_once("classes/Trackback.class.php");
include_once("classes/Archive.class.php");
include_once("classes/Category.class.php");
include_once("classes/Export.class.php");
include_once("classes/Import.class.php");
include_once("classes/Bookmark.class.php");
include_once("classes/Calendar.class.php");

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

include_once("classes/Template.class.php");
include_once("classes/UserTemplate.class.php");
include_once("classes/AdminTemplate.class.php");

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

if (function_exists("iconv") == 0) {
  function iconv($in, $out, $str) {
    if($in == "UTF-8") {
      if(!isutf8($str)) {
	return FALSE;
      }
    }
    $return = "";
    $fp = popen("echo ".escapeshellarg($str)." | /usr/local/bin/iconv -c -f $in -t $out","r");
    while(!feof($fp)) {
      $return .= fgets($fp,1024);
    }
    pclose($fp);
    if($in == "CP949") {
      if(!isutf8($return)) {
	return FALSE;
      }
    }
    return $return;
  }
}

setcookie("soojungcountercookie", "on", 0);

//FIXME: move to Soojung.class.php
function get_count() {
  global $soojungcountercookie;
  global $today_count;
  global $total_count;
  $today_count = 0;
  $total_count = 0;
  $last_date = date("Y-m-d");
  $today = date("Y-m-d");
  $modified = false;

  if ($fd = @fopen("contents/.count", "r")) {
  	flock($fd, LOCK_SH);
    $last_date = trim(fgets($fd,256));
    $today_count = trim(fgets($fd,256));
    $total_count = trim(fgets($fd,256));
    flock($fd, LOCK_UN);
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
    if ($fd = @fopen("contents/.count", "w")) {
      flock($fd, LOCK_EX);
      fwrite($fd, $today);
      fwrite($fd, "\n");
      fwrite($fd, $today_count);
      fwrite($fd, "\n");
      fwrite($fd, $total_count);
      fwrite($fd, "\n");
      flock($fd, LOCK_UN);
      fclose($fd);
    }
  }
}

get_count();
Soojung::addReferer();
?>
