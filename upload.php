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