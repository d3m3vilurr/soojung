<?php
session_cache_limiter('private'); 
session_start();

include("config.php");
include("settings.php");

if ($_POST["mode"] == "login") {
  if (md5($_POST["password"]) == $admin_password)
    $_SESSION['auth'] = TRUE;
  $_POST["mode"] = $_POST["original_mode"];
#  echo "mode = ". $_POST["mode"];
} 

if (!isset($_SESSION["auth"])) {
  $hidden_attr = array();
  $param["name"] = "original_mode";
  $param["value"] = $_POST["mode"];
  $hidden_attr[] = $param;
  
  $list = array("title", "format", "date", "category", "category_input", "body", "SECRET", "NO_COMMENT", "NO_TRACKBACK", "STATIC", "NO_RSS");
  foreach ($list as $key) {
    if (array_key_exists($key, $_POST)) {
      $param["name"] = $key;
      $param["value"] = htmlspecialchars($_POST[$key]);
      $hidden_attr[] = $param;
    }
  }
  
  $template = new AdminTemplate;
  $template->assign('original_dst', $_SERVER["PHP_SELF"]);
  $template->assign('hidden_attr', $hidden_attr);
  $template->display('login.tpl');
  exit;
}

if ($_POST["mode"] == "upload") {
  echo "upload:";
  print_r($_FILES);
  die();
} else if ($_POST["mode"] == "Post") {
  $title =  $_POST["title"];
  $format = $_POST["format"];
  $formatter = Soojung::getFormatter($format);
  $body = $formatter->onPost($_POST["body"]);
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

  if (empty($title) || empty($body) || empty($date) || empty($format) || empty($category) ) {
    echo "<font color=\"red\">WARNING: Input title, body, date, category, format</font>";
  } else {
    if (isset($_POST["id"])) {
      Entry::editEntry($_POST["id"], $title, $body, $date, $category, $options, $format);
    } else {
      $date = time() + 10;
      echo "hahaha<br />";
      Entry::createEntry($title, $body, $date, $category, $options, $format);
      echo "hahaha<br />";
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
  $body = $entry->getRawBody();
  $date = $entry->date;
  $category = $entry->category->name;
  $options = $entry->options;
  $format = $entry->format;
  $id = $entry->entryId;
} else if ($_POST["mode"] == "Preview") {
  $mode = "preview";
  $title = $_POST["title"];
  $format = $_POST["format"];
  $formatter = Soojung::getFormatter($format);
  $body = $formatter->onPost($_POST["body"]);
  $body = nl2br($body);
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
