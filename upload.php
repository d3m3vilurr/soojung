<?php
session_start();

include("settings.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

if ($_POST["mode"] == "upload") {
  $dest = "contents/upload/" . basename($_FILES['file']['name']);
  move_uploaded_file($_FILES['file']['tmp_name'], $dest);
}

if ($_GET["mode"] == "delete" && !empty($_GET["file"])) {
  unlink($_GET["file"]);
}

$template = new AdminTemplate;

$list = array();

$dh = opendir("contents/upload");
if ($dh != false) {
  while (($file = readdir($dh)) !== false) {
    if ($file == "." || $file == "..") {
      continue;
    }
    $list[] = "contents/upload/" . $file;
  }
}
$template->assign("files", $list);

$template->display("upload.tpl");
?>
<?
# vim: ts=8 sw=2 sts=2 noet
?>