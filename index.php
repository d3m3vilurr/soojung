<?php
include_once("soojung.php");

$smarty = new Smarty;
$smarty->template_dir = "templates/" . $blog_skin . "/";
$smarty->compile_dir = "templates/.compile/";
$smarty->config_dir = "templates/.configs/";
$smarty->cache_dir = "templates/.cache/";

//if (isset($_GET["compile"])) {
  $smarty->force_compile = true;
//}

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
if ($page > 1) {
  $smarty->assign('prev_page_link', "index.php?page=" . ($page - 1));
}
if (get_entry_count() > (($page) * $blog_entries_per_page)) {
  $smarty->assign('next_page_link', "index.php?page=" . ($page + 1));
}

$smarty->assign('title', $blog_name);
$smarty->assign('baseurl', $blog_baseurl);

if (isset($_GET["archive"])) {
  $smarty->assign('view', 'archive');
  $smarty->assign('entries', get_archive_entries($_GET["archive"]));
} else if (isset($_GET["category"])) {
  $smarty->assign('view', 'category');
  $smarty->assign('entries', get_category_entries($_GET["category"]));
} else if (isset($_GET["search"])) {
  $smarty->assign('view', 'search');
  $smarty->assign('entries', entry_search($_GET["search"]));
} else {
  $smarty->assign('view', 'index');
  $smarty->assign('entries', get_entries($blog_entries_per_page, $page));
}

$smarty->assign('categories', get_category_list());
$smarty->assign('archvies', get_archive_list());
$smarty->assign('recent_entries', get_recent_entries(10));
$smarty->assign('recent_comments', get_recent_comments());
$smarty->assign('recent_trackbacks', get_recent_trackbacks());

get_count();

$smarty->assign('today_count', $today_count);
$smarty->assign('total_count', $total_count);

$smarty->display('index.tpl');

?>
