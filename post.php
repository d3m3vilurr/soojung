<?php
session_start();

include_once("soojung.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

if ($_POST["mode"] == "Post") {
  $title =  $_POST["title"];
  $body = balanceTags($_POST["body"]);
  $date = strtotime($_POST["date"]);
  $category = trim($_POST["category"]);

  if (empty($title) || empty($body) || empty($date) || empty($category)) {
    echo "<font color=\"red\">WARNING: Input title, body, date, category</font>";
  } else {
    if (isset($_POST["id"])) {
      entry_edit($_POST["id"], $title, $body, $date, $category);
    } else {
      entry_new($title, $body, $date, $category);
    }
    echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
    exit;
  }
} else if ($_GET["blogid"]) { //edit
  $entry = get_entry($_GET["blogid"]);
  $mode = "edit";
  $title = $entry["title"];
  $body = $entry["body"];
  $date = $entry["date"];
  $category = $entry["category"];
  $id = $entry["id"];
} else if ($_POST["mode"] == "Preview") {
  $mode = "preview";
  $title =  $_POST["title"];
  $body = balanceTags($_POST["body"]);
  $date = strtotime($_POST["date"]);
  $category = trim($_POST["category"]);
}

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty;
$smarty->compile_dir = "templates/.admin_compile/";
$smarty->config_dir = "templates/.admin_configs/";
$smarty->cache_dir = "templates/.admin_cache/";
$smarty->template_dir = "templates/admin/";
$smarty->assign('baseurl', $blog_baseurl);

$smarty->assign("title", $title);
$smarty->assign("body", br2nl($body));
$smarty->assign("date", date('Y-m-d H:i:s', isset($date) ? $date : time()));
$smarty->assign("category", $category);
$smarty->assign("id", $id);
$smarty->assign("mode", $mode);

$smarty->display('post.tpl');
?>
