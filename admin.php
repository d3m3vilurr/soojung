<?php
session_start();

include("config.php");
include_once("settings.php");

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
  if (empty($_POST["blogname"]) || empty($_POST["desc"]) ||
      empty($_POST["url"]) || empty($_POST["adminname"]) ||
      empty($_POST["email"]) || empty($_POST["perpage"]) ||
      empty($_POST["skin"])) {
    echo "input";
    exit();
  }
  Soojung::writeConfigFile($_POST["blogname"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
		    $_POST["fancyurl"], $_POST["notify"], $_POST["adminname"], $_POST["email"],
		    FALSE, $_POST["skin"]);
  echo "<meta http-equiv='refresh' content='0;URL=index.php?compile=t'>";
  exit;
}

if ($_GET["mode"] == "delete" && isset($_GET["file"])) {
  if (strstr($_GET["file"], "..") != FALSE || strstr($_GET["file"], "contents/") == FALSE) {
    echo "what the fuck?";
  } else {
    unlink($_GET["file"]);
  }
} else if ($_GET["mode"] == "delete_entry" && isset($_GET["blogid"])) {
  Entry::deleteEntry($_GET["blogid"]);
} else if ($_GET["mode"] == "export") {
  header("Content-Type: application/octet");
  header("Content-Disposition: filename=soojung.dat");
  echo Export::export();
  flush();
  exit();
} else if ($_POST["mode"] == "import") {
  if (isset($_FILES['file']['name'])) {
    Import::importSoojung($_FILES['file'], $_POST["version"]);
  }
  header("Location: admin.php");
} else if ($_POST["mode"] == "import_tt") {
  Import::importTatterTools($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"], $_POST["prefix"], $_POST["encoding"]);
  header("Location: admin.php");
} else if ($_POST["mode"] == "import_wp") {
  Import::importWordPress($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"], $_POST["prefix"], $_POST["encoding"]);
  header("Location: admin.php");
}

if ($_GET["mode"] == "config") {
  $template->assign("blog_name", $blog_name);
  $template->assign("blog_desc", $blog_desc);
  $template->assign("blog_entries_per_page", $blog_entries_per_page);
  $template->assign("blog_fancyurl", $blog_fancyurl);
  $template->assign("blog_notify", $notify);
  $template->assign("blog_skin", $blog_skin);
  $template->assign("admin_name", $admin_name);
  $template->assign("admin_email", $admin_email);
  $template->assign("templates", Soojung::getTemplates());

  $template->display('config.tpl');
} else if ($_GET["mode"] == "data") {
  $template->display('data.tpl');
} else {
  $entry_structs = array();
  $template->assign('entries', Entry::getAllEntries(false));
  $template->display('list.tpl');
}
?>
