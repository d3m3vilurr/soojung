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
  if (empty($_POST["category_input"]) == false) {
    $category = trim($_POST["category_input"]);
  } else {
    $category = $_POST["category"];
  }
  $options = array();
  if (isset($_POST["SECRET"])) {
    $options[] = "SECRET";
  }
  if (isset($_POST["NO_COMMENT"])) {
    $options[] = "NO_COMMENT";
  }
  if (isset($_POST["NO_TRACKBACK"])) {
    $options[] = "NO_TRACKBACK";
  }
  if (isset($_POST["STATIC"])) {
    $options[] = "STATIC";
  }
  if (isset($_POST["NO_RSS"])) {
    $options[] = "NO_RSS";
  }
  $format = $_POST["format"];

  if (empty($title) || empty($body) || empty($date) || empty($format) ) {
    echo "<font color=\"red\">WARNING: Input title, body, date, category, format</font>";
  } else {
    if (isset($_POST["id"])) {
      Entry::editEntry($_POST["id"], $title, $body, $date, $category, $options, $format);
    } else {
      $date = time() + 10;
      Entry::createEntry($title, $body, $date, $category, $options, $format);
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
  $body = $entry->getBody(false);
  $body = addslashes($body);
  $date = $entry->date;
  $category = $entry->category->name;
  $options = $entry->options;
  $format = $entry->format;
  $id = $entry->entryId;
} else if ($_POST["mode"] == "Preview") {
  $mode = "preview";
  $title =  $_POST["title"];
  $body = balanceTags($_POST["body"]);
  $date = strtotime($_POST["date"]);
  $category = trim($_POST["category"]);
  $format =  $_POST["format"];
  $options = array();
  if (isset($_POST["SECRET"])) {
    $options[] = "SECRET";
  }
  if (isset($_POST["NO_COMMENT"])) {
    $options[] = "NO_COMMENT";
  }
  if (isset($_POST["NO_TRACKBACK"])) {
    $options[] = "NO_TRACKBACK";
  }
  if (isset($_POST["STATIC"])) {
    $options[] = "STATIC";
  }
  if (isset($_POST["NO_RSS"])) {
    $options[] = "NO_RSS";
  }
  if (isset($_POST["id"]))
    $id = $_POST["id"];
}

$smarty = new Smarty;
$smarty->compile_dir = "templates/.admin_compile/";
$smarty->config_dir = "templates/.admin_configs/";
$smarty->cache_dir = "templates/.admin_cache/";
$smarty->template_dir = "templates/admin/";
$smarty->assign('baseurl', $blog_baseurl);

$smarty->assign("title", $title);
$smarty->assign("body", $body);
$smarty->assign("date", date('Y-m-d H:i:s', isset($date) ? $date : time()+10));
$smarty->assign("category", $category);

if (isset($options)) {
  $options = array_flip($options);
  $smarty->assign("secret", array_key_exists("SECRET", $options));
  $smarty->assign("no_comment", array_key_exists("NO_COMMENT", $options));
  $smarty->assign("no_trackback", array_key_exists("NO_TRACKBACK", $options));
  $smarty->assign("static", array_key_exists("STATIC", $options));
  $smarty->assign("no_rss", array_key_exists("NO_RSS", $options));
}

$smarty->assign("id", $id);
$smarty->assign("mode", $mode);

$smarty->assign("categories", Category::getCategoryList());

if (isset($_GET["format"])) {
  $smarty->assign("format", $_GET["format"]);
} else {
  $smarty->assign("format", $format);
}
$smarty->display('post.tpl');
?>