<?php
session_start();

include("settings.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

/* for posting new bookmark */
if ($_POST["mode"] == "delete") {
  $del_list = $_POST["delchk"];
  foreach($del_list as $number => $chk) {
    Bookmark::deleteBookmark($number);    
  }
} else if ($_GET["mode"] == "delete") {
  $number = $_GET["number"];
  Bookmark::deleteBookmark($number);
} else if ($_GET["mode"] == "move") {
  $number = $_GET["number"];
  $offset = intval($_GET["offset"]);
  Bookmark::moveBookmark($number, $offset);
  /* XXX without refresh moving, file doesnt save correctly */
  echo "<meta http-equiv='refresh' content='0;URL=bookmark.php'>";
  exit;
}

$template = new AdminTemplate;

$bookmarks = Bookmark::getBookmarkList();
if (empty($bookmarks)) {
  $bookmarks = array();
}

$template->assign('bookmarks', $bookmarks);
$template->display('bookmark.tpl');
# vim: ts=8 sw=2 sts=2 noet
?>
