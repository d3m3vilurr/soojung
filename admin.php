<?php
session_start();

include("config.php");
include_once("settings.php");

function category_in_array($categoryName, $categories) {
  foreach ($categories as $c) {
    if ($c->name == $categoryName) {
      return true;
    }
  }
  return false;
}

function config_update_mode() {
  if (empty($_POST["blogname"]) || empty($_POST["desc"]) ||
      empty($_POST["url"]) || empty($_POST["adminname"]) ||
      empty($_POST["email"]) || empty($_POST["perpage"]) ||
      empty($_POST["skin"])) {
    echo "input";
    exit();
  }
  if (!empty($_POST["password"])) {
    $password = md5($_POST["password"]);
  } else {
    $password = FALSE;
  }
  Soojung::writeConfigFile($_POST["blogname"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
		    $_POST["fancyurl"], $_POST["notify"], $_POST["adminname"], $_POST["email"],
		    $password, $_POST["skin"], $_POST["license"],
		    $_POST["word"]);
  echo "<meta http-equiv='refresh' content='0;URL=index.php?compile=t'>";
  exit;
}

function delete_mode() {
  if ($_GET["mode"] == "delete" && isset($_GET["file"])) {
    if (strstr($_GET["file"], "..") != FALSE || strstr($_GET["file"], "contents/") == FALSE) {
      echo "what the fuck?";
    } else {
      unlink($_GET["file"]);
      $temp = new Usertemplate("index.tpl", 1);
      $temp->clearCache();
    }
  } else if ($_GET["mode"] == "delete_entry" && isset($_GET["blogid"])) {
    Entry::deleteEntry($_GET["blogid"]);
    $temp = new Usertemplate("index.tpl", 1);
    $temp->clearCache();
  }
}

function export_mode() {
  global $blog_name;
  $filename = $blog_name . '-' . date("Ymd", time()) . '.dat';
  header("Content-Type: application/octet");
  header("Content-Disposition: filename=" . $filename);
  echo Export::export();
  flush();
  exit();
}

function import_mode() {
  if ($_POST["mode"] == "import") {
    if (isset($_FILES['file']['name'])) {
      Import::importSoojung($_FILES['file'], $_POST["version"]);
    }
  } else if ($_POST["mode"] == "import_tt") {
    Import::importTatterTools($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"], $_POST["prefix"], $_POST["encoding"]);
  } else if ($_POST["mode"] == "import_wp") {
    Import::importWordPress($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"], $_POST["prefix"], $_POST["encoding"]);
  } else if ($_POST["mode"] == "import_zb") {
    Import::importZeroboard($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"], $_POST["prefix"], $_POST["encoding"], $_POST["boardid"]);
  }
  $temp = new Usertemplate("index.tpl", 1);
  $temp->clearCache();
  header("Location: admin.php");
}

function clear_cache_mode() {
  $temp = new Usertemplate("index.tpl", 1);
  $temp->clearCache();
}

function clear_referer_mode() {
  @unlink("contents/.referer");
}

function logout_mode() {
  unset($_SESSION['auth']);
  setcookie(session_name(), '', 0, '/');
  header("Location: index.php");
  exit();
}

if (array_key_exists("mode", $_POST) and $_POST["mode"] == "login") {
  if (md5($_POST["password"]) == $admin_password) {
    $_SESSION['auth'] = TRUE;
    header("Location: admin.php");
  }
}

$template = new AdminTemplate;

sajax_init();
sajax_handle_client_request();
$template->assign("sajax_functions", sajax_get_javascript());

if (!isset($_SESSION["auth"])) {
  $template->display('login.tpl');
  exit();
}

if ($_POST["mode"] == "config_update") {
  config_update_mode();
} else if (strpos($_GET["mode"], "delete") === 0) {
  delete_mode();
} else if ($_GET["mode"] == "export") {
  export_mode();
} else if (strpos($_POST["mode"], "import") === 0) {
  import_mode();
} else if ($_GET["mode"] == "clear_cache") {
  clear_cache_mode();
} else if ($_GET["mode"] == "clear_referer") {
  clear_referer_mode();
} else if ($_GET["mode"] == "logout") {
  logout_mode();
}

if ($_GET["mode"] == "config") {
  $template->assign("blog_name", $blog_name);
  $template->assign("blog_desc", $blog_desc);
  $template->assign("blog_entries_per_page", $blog_entries_per_page);
  $template->assign("blog_fancyurl", $blog_fancyurl);
  $template->assign("blog_notify", $notify);
  $template->assign("blog_skin", $blog_skin);
  $template->assign("license", $entries_license);
  $template->assign("admin_name", $admin_name);
  $template->assign("admin_email", $admin_email);
  $template->assign("spam_words", $spam_words);
  $template->assign("templates", Soojung::getTemplates());
  $template->assign("config_writable", is_writable("config.php"));
  $template->display('config.tpl');
} else if ($_GET["mode"] == "data") {
  $template->display('data.tpl');
} else if ($_GET["mode"] == "list") {
  if(isset($_GET["page"])) {
    $page = $_GET["page"];
  } else {
    $page = 1;
  }

  $count = 0;
  $categories = Category::getCategoryList();

  if ($_GET["flag"] == "static") {
    $count = Entry::getStaticEntryCount();
  } else if (category_in_array($_GET["flag"], $categories)) {
    $cate = new Category($_GET["flag"]);
    $count = $cate->getEntryCount();
  } else {
    $count = Entry::getEntryCount(false);
  }

  if ($page > 1) {
    $prev_link = "admin.php?mode=list&page=" . ($page - 1);
    if (isset($_GET["flag"])) {
      $prev_link .= "&flag=" . urlencode($_GET["flag"]);
    }
    $template->assign('prev_page_link', $prev_link);
  }
  if ($count > $page * 10) {
    $next_link = "admin.php?mode=list&page=" . ($page + 1);
    if (isset($_GET["flag"])) {
      $next_link .= "&flag=" . urlencode($_GET["flag"]);
    }
    $template->assign('next_page_link', $next_link);
  }


  if ($_GET["flag"] == "static") {
    $template->assign('entries', Entry::getStaticEntries(10, $page));
  } else if (category_in_array($_GET["flag"], $categories)) {
    $cate = new Category($_GET["flag"]);
    $template->assign('entries', $cate->getEntries(10, $page));
  } else {
    $template->assign('entries', Entry::getEntries(10, $page, false));
  }

  $template->assign("categories", $categories);
  $template->assign("flag", $_GET["flag"]);
  $template->display('list.tpl');
} else {
  $template->assign('recent_entries', Entry::getRecentEntries(5, false));
  $template->assign('recent_comments', Comment::getRecentComments(5));
  $template->assign('recent_trackbacks', Trackback::getRecentTrackbacks(5));
  $template->assign('entry_count', Entry::getEntryCount());
  $template->display('overview.tpl');
}

# vim: ts=8 sw=2 sts=2 noet
?>
