<?php
session_start();

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

/* for posting new bookmark */

if ($_POST["mode"] == "post") {
  $url = $_POST["url"];
  $desc = $_POST["desc"];

  if (strstr ($url, "://") == FALSE)
    $url = 'http://' . $url;

  if (empty($url) || $url == 'http://')  {
    echo "<font color=\"red\">WARNING: Input url correctly</font>";
  } else {
    if (add_bookmark ($url, $desc) == false) {
      echo "<font color=\"red\">WARNING: bookmark already exist</font>";    
    }
  }
} else if ($_GET["mode"] == "delete") {
  $url = $_GET["url"];
  delete_bookmark($url);
}

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = true;
$smarty->compile_dir = "templates/.admin_compile/";
$smarty->config_dir = "templates/.admin_configs/";
$smarty->cache_dir = "templates/.admin_cache/";
$smarty->template_dir = "templates/admin/";
$smarty->assign('baseurl', $blog_baseurl);

$bookmarks = get_bookmark_list();
if (empty($bookmarks)) {
  $bookmarks = array();
}
$smarty->assign('bookmarks', $bookmarks);
$smarty->display('bookmark.tpl');
?>