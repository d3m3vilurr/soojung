<?php
session_start();

include("settings.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

/* for posting new bookmark */

if ($_POST["mode"] == "post") {
  $url = $_POST["url"];
  $desc = $_POST["desc"];

  if (strstr ($url, "://") == FALSE)
    $url = 'http://' . $url;

  if (empty($url) || $url == 'http://')  {
    echo "<font color=\"red\">WARNING: Input url correctly</font>";
  } else {
    if (Bookmark::addBookmark ($url, $desc) == false) {
      echo "<font color=\"red\">WARNING: bookmark already exist</font>";    
    }
  }
} else if ($_GET["mode"] == "delete") {
  $url = $_GET["url"];
  Bookmark::deleteBookmark($url);
} else if ($_GET["mode"] == "move") {
  $url = $_GET["url"];
  $offset = intval($_GET["offset"]);
  Bookmark::moveBookmark($url, $offset);
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
