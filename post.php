<?php
session_start();

include("settings.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

if ($_POST["mode"] == "upload") {
  echo "upload:";
  print_r($_FILES);
  die();
} else if ($_POST["mode"] == "Post") {
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

  if (empty($title) || empty($body) || empty($date) || empty($format) || empty($category) ) {
    echo "<font color=\"red\">WARNING: Input title, body, date, category, format</font>";
  } else {
    if (isset($_POST["id"])) {
      Entry::editEntry($_POST["id"], $title, $body, $date, $category, $options, $format);
    } else {
      $date = time() + 10;
      Entry::createEntry($title, $body, $date, $category, $options, $format);
    }
    $temp = new UserTemplate("index.tpl", 1);
    $temp->clearCache();
    echo "post success<br>";
    echo "<a href=\"admin.php\">admin</a> ";
    echo "<a href=\"index.php\">index</a><br />";
    exit;
  }
} else if ($_GET["blogid"]) { //edit
  $entry = Entry::getEntry($_GET["blogid"]);
  $mode = "edit";
  $title = $entry->title;
  $body = $entry->getBody(false);
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
  $format =  $_POST["format"];
  if (isset($_POST["id"]))
    $id = $_POST["id"];
}

$template = new AdminTemplate;

$template->assign("title", $title);
$template->assign("body", $body);
$template->assign("date", date('Y-m-d H:i:s', isset($date) ? $date : time()+10));
$template->assign("category", $category);

if (isset($options)) {
  $options = array_flip($options);
  $template->assign("secret", array_key_exists("SECRET", $options));
  $template->assign("no_comment", array_key_exists("NO_COMMENT", $options));
  $template->assign("no_trackback", array_key_exists("NO_TRACKBACK", $options));
  $template->assign("static", array_key_exists("STATIC", $options));
  $template->assign("no_rss", array_key_exists("NO_RSS", $options));
}

$template->assign("id", $id);
$template->assign("mode", $mode);

$template->assign("categories", Category::getCategoryList());

if (isset($_GET["format"])) {
  $template->assign("format", $_GET["format"]);
} else {
  $template->assign("format", $format);
}
$template->display('post.tpl');

# vim: ts=8 sw=2 sts=2 noet
?>
