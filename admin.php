<?php
session_start();

include_once("soojung.php");

if ($_POST["mode"] == "login") {
  if (md5($_POST["password"]) == $admin_password) {
    $_SESSION['auth'] = TRUE;
  }
}

$smarty = new Smarty;
$smarty->compile_dir = "templates/.compile/";
$smarty->config_dir = "templates/.configs/";
$smarty->cache_dir = "templates/.cache/";
$smarty->template_dir = "templates/admin/";
$smarty->assign('baseurl', $blog_baseurl);

if (!isset($_SESSION["auth"])) {
  $smarty->display('login.tpl');
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
  write_config_file($_POST["blogname"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
		    $_POST["fancyurl"], $_POST["adminname"], $_POST["email"],
		    FALSE, $_POST["skin"]);
  echo "<meta http-equiv='refresh' content='0;URL=index.php?compile=t'>";
}

if ($_GET["mode"] == "delete" && isset($_GET["file"])) {
  if (strstr($_GET["file"], "..") != FALSE || strstr($_GET["file"], "contents/") == FALSE) {
    echo "what the fuck?";
  } else {
    unlink($_GET["file"]);
  }
} else if ($_GET["mode"] == "delete_entry" && isset($_GET["blogid"])) {
  entry_delete($_GET["blogid"]);
}

if ($_GET["mode"] == "config") {
  $smarty->assign("blog_name", $blog_name);
  $smarty->assign("blog_desc", $blog_desc);
  $smarty->assign("blog_entries_per_page", $blog_entries_per_page);
  $smarty->assign("blog_fancyurl", $blog_fancyurl);
  $smarty->assign("blog_skin", $blog_skin);
  $smarty->assign("admin_name", $admin_name);
  $smarty->assign("admin_email", $admin_email);
  
  $smarty->display('config.tpl');
} else {
  $entry_structs = array();
  $entries = get_entries(get_entry_count(), 1);
  foreach ($entries as $e) {
    $entry_struct = array();
    $entry_struct['entry'] = $e;
    $entry_struct['comments'] = get_comments($e['id']);
    $entry_struct['trackbacks'] = get_trackbacks($e['id']);
    $entry_structs[] = $entry_struct;
  }
  $smarty->assign('entry_structs', $entry_structs);
  $smarty->display('list.tpl');
}
?>