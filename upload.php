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

$path = "contents/upload/";

$list = array();
$dh = opendir($path);
if ($dh != false) {
  while (($file = readdir($dh)) !== false) {
    if ($file == "." || $file == "..") {
      continue;
    }
    $list[] = array(filemtime($path . $file), $file);
  }
}
closedir($dh);

rsort($list);
$files = array();
foreach($list as $f) {
  $files[] = array("path" = > $path . $f[1], "name" => $f[1]);
}

$template->assign("files", $files);

$template->display("upload.tpl");

# vim: ts=8 sw=2 sts=2 noet
?>
