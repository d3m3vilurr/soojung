<?php
include_once("settings.php");

$counter = new Counter();
$counter->update();

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}

$cache_id = implode("_", $_GET) . $page;

$template = new UserTemplate('index.tpl', $cache_id);
if (isset($_GET["compile"])) {
  $template->force_compile = true;
}

if (!$template->is_cached('index.tpl', $cache_id)) {
  if ($page > 1) {
    if ($blog_fancyurl == true) {
      $template->assign('prev_page_link', $blog_baseurl . "/page/" . ($page - 1));
    } else {
      $template->assign('prev_page_link', "index.php?page=" . ($page - 1));
    }
  }

  if (Entry::getEntryCount() > (($page) * $blog_entries_per_page)) {
    if ($blog_fancyurl == true) {
      $template->assign('next_page_link', $blog_baseurl . "/page/" . ($page + 1));
    } else {
      $template->assign('next_page_link', "index.php?page=" . ($page + 1));
    }
  }

  if (isset($_GET["archive"])) {
    $template->assign('view', 'archive');
    $template->assign('keyword', $_GET["archive"]);
    $archive = Archive::getArchive($_GET["archive"]);
    $entries = $archive->getEntries();
    $template->assign('entries', $entries);
  } else if (isset($_GET["category"])) {
    $template->assign('view', 'category');
    $template->assign('keyword', $_GET["category"]);
    $category = new Category($_GET["category"]);
    $template->assign('entries', $category->getEntries());
  } else if (isset($_GET["search"])) {
    $template->assign('view', 'search');
    $template->assign('keyword', $_GET["search"]);
    $template->assign('entries', Entry::search($_GET["search"]));
  } else {
    $template->assign('view', 'index');
    $template->assign('keyword', "all");
    $template->assign('entries', Entry::getEntries($blog_entries_per_page, $page));
  }
}
$template->display('index.tpl', $cache_id);

# vim: ts=8 sw=2 sts=2 noet
?>
