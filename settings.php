<?php

if (file_exists("config.php")) {
  include_once("config.php");
}

include_once("libs/util.php");

include_once("classes/Entry.class.php");
include_once("classes/Soojung.class.php");
include_once("classes/Comment.class.php");
include_once("classes/Trackback.class.php");
include_once("classes/Archive.class.php");
include_once("classes/Category.class.php");
include_once("classes/Export.class.php");
include_once("classes/Import.class.php");
include_once("classes/Bookmark.class.php");

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

include_once("classes/Template.class.php");

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
    $fp = popen("echo \"$str\" | /usr/local/bin/iconv -c -f $in -t $out","r");
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

?>