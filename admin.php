<?php
session_start();

include("config.php");
include_once("settings.php");

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
		    $password, $_POST["skin"], $_POST["license"]);
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

if ($_POST["mode"] == "login") {
  if (md5($_POST["password"]) == $admin_password) {
    $_SESSION['auth'] = TRUE;
    header("Location: admin.php");
  }
}

$template = new AdminTemplate;

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
  $template->assign("templates", Soojung::getTemplates());
  $template->display('config.tpl');
} else if ($_GET["mode"] == "data") {
  $template->display('data.tpl');
} else if ($_GET["mode"] == "list") {
  $entry_structs = array();
  $template->assign('entries', Entry::getAllEntries(false));
  $template->display('list.tpl');
} else {
  $template->assign('recent_entries', Entry::getRecentEntries(5));
  $template->assign('recent_comments', Comment::getRecentComments(5));
  $template->assign('recent_trackbacks', Trackback::getRecentTrackbacks(5));
  $template->assign('entry_count', Entry::getEntryCount());
  $template->display('overview.tpl');
}

# vim: ts=8 sw=2 sts=2 noet
?>
