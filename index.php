<?php
include_once("soojung.php");
include("Template.class.php");

$template = new Template;

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
if ($page > 1) {
  $template->assign('prev_page_link', "index.php?page=" . ($page - 1));
}
if (get_entry_count() > (($page) * $blog_entries_per_page)) {
  $template->assign('next_page_link', "index.php?page=" . ($page + 1));
}

if (isset($_GET["archive"])) {
  $template->assign('view', 'archive');
  $template->assign('entries', get_archive_entries($_GET["archive"]));
} else if (isset($_GET["category"])) {
  $template->assign('view', 'category');
  $template->assign('entries', get_category_entries($_GET["category"]));
} else if (isset($_GET["search"])) {
  $template->assign('view', 'search');
  $template->assign('entries', entry_search($_GET["search"]));
} else {
  $template->assign('view', 'index');
  $template->assign('entries', get_entries($blog_entries_per_page, $page));
}

$template->display('index.tpl');

?>
