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

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

include_once("classes/Template.class.php");

?>