<?php
session_start();

include("settings.php");

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
      Entry::editEntry($_POST["id"], $title, $body, $date, $category);
    } else {
      Entry::createEntry($title, $body, $date, $category);
    }
    echo "post success<br>";
    echo "<a href=\"admin.php\">admin</a> ";
    echo "<a href=\"\">index</a><br>";
    exit;
  }
} else if ($_GET["blogid"]) { //edit
  $entry = Entry::getEntry($_GET["blogid"]);
  $mode = "edit";
  $title = $entry->title;
  $body = $entry->getBody();
  $date = $entry->date;
  $category = $entry->category->name;
  $id = $entry->entryId;
} else if ($_POST["mode"] == "Preview") {
  $mode = "preview";
  $title =  $_POST["title"];
  $body = balanceTags($_POST["body"]);
  $date = strtotime($_POST["date"]);
  $category = trim($_POST["category"]);
}

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
