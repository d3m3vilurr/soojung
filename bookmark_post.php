<?php
session_start();

include("settings.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

if ($_POST["mode"] == "post") {
  $number = $_POST["number"];
  $name = $_POST["name"];
  $image = $_POST["image"];
  $url = $_POST["url"];
  $desc = $_POST["desc"];
  
  if (strstr ($url, "://") == FALSE)
    $url = 'http://' . $url;

  /* check each entry property */
  if (empty($url) || $url == 'http://')  {
    echo "<font color=\"red\">WARNING: Input url correctly</font>";
  } else {
    if (Bookmark::bookmarkWrite ($number, $name, $url, $desc, $image) == false) {
      /* for each error code */
      echo "<font color=\"red\">WARNING: bookmark already exist</font>";    
    }
  } 
  /* if it succeed */
  echo "<meta http-equiv='refresh' content='0;URL=bookmark.php'>";
  exit;
} else if ($_GET["number"]) { // edit
  $bookmark = Bookmark::getBookmark($_GET["number"]);
  $number = $bookmark->number;
  $name = $bookmark->name;
  $image = $bookmark->image;
  $desc = $bookmark->desc;
  $url = $bookmark->url;
}

$template = new AdminTemplate;
$template->assign("number", $number);
$template->assign("name", $name);
$template->assign("image", $image);
$template->assign("url", $url);
$template->assign("desc", $desc);
$template->display('bookmark_post.tpl');

# vim: ts=8 sw=2 sts=2 noet
?>
