<?php

include_once("soojung.php");

if (isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
}

if ($_POST["mode"] == "post") {
  $title =  $_POST["title"];
  $body = $_POST["body"];
  $date = strtotime($_POST["date"]);
  $category = $_POST["category"];

  if (empty($title) || empty($body) || empty($date) || empty($category)) {
    echo "input title, body, date, category";
  } else {
    if (isset($_POST["id"])) {
      entry_edit($_POST["id"], $title, $body, $date, $category);
    } else {
      entry_new($title, $body, $date, $category);
    }
    echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  }
} else if ($_GET["blogid"]) {
  $entry = get_entry($_GET["blogid"]);
  $mode = "edit";
  $title = $entry["title"];
  $body = $entry["body"];
  $date = $entry["date"];
  $category = $entry["category"];
  $id = $entry["id"];
}

$smarty = new Smarty;
$smarty->compile_dir = "templates/.admin_compile/";
$smarty->config_dir = "templates/.admin_configs/";
$smarty->cache_dir = "templates/.admin_cache/";
$smarty->template_dir = "templates/admin/";
$smarty->assign('baseurl', $blog_baseurl);

$smarty->assign("title", $title);
$smarty->assign("body", br2nl($body));
$smarty->assign("date", date('Y-m-d H:i:s', $date));
$smarty->assign("category", $category);
$smarty->assign("id", $id);

$smarty->display('post.tpl');
?>
