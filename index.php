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
  if ($blog_fancyurl == true) {
    $template->assign('prev_page_link', $blog_baseurl . "/page/" . ($page - 1));
  } else {
    $template->assign('prev_page_link', "index.php?page=" . ($page - 1));
  }
}
if (get_entry_count() > (($page) * $blog_entries_per_page)) {
  if ($blog_fancyurl == true) {
     $template->assign('next_page_link', $blog_baseurl . "/page/" . ($page + 1));
  } else {
    $template->assign('next_page_link', "index.php?page=" . ($page + 1));
  }
}

if (isset($_GET["archive"])) {
  $template->assign('view', 'archive');
  $template->assign('keyword', $_GET["archive"]);
  $entries = get_archive_entries($_GET["archive"]);
  $template->assign('count', count($entries));
  $template->assign('entries', $entries);
} else if (isset($_GET["category"])) {
  $template->assign('view', 'category');
  $template->assign('keyword', $_GET["category"]);
  $entries = get_category_entries($_GET["category"]);
  $template->assign('count', count($entries));
  $template->assign('entries', $entries);
} else if (isset($_GET["search"])) {
  $template->assign('view', 'search');
  $template->assign('keyword', $_GET["search"]);
  $entries = entry_search($_GET["search"]);
  $template->assign('count', count($entries));
  $template->assign('entries', $entries);
} else {
  $template->assign('view', 'index');
  $template->assign('keyword', "all");
  $template->assign('count', get_entry_count());
  $template->assign('entries', get_entries($blog_entries_per_page, $page));
}

$template->display('index.tpl');

?>
