<?
include_once("settings.php");

class UserTemplate extends Template {
  function UserTemplate($template, $cache_id)
  {
    global $blog_skin;
    global $today_count, $total_count;

    $this->Template();
    
    $this->template_dir = "templates/" . $blog_skin . "/";
    $this->compile_dir = "templates/.compile/";
    $this->config_dir = "templates/" . $blog_skin . "/";
    $this->cache_dir = "templates/.cache/";

    $this->assign('skin', $blog_skin);

    $this->compile_check = false;
    $this->caching=true;
    $this->force_compile = false;

    if (!$this->is_cached($template, $cache_id)) {
      $this->assign('static_entries', Entry::getStaticEntries());

      $this->assign('categories', Category::getCategoryList());
      $this->assign('archvies', Archive::getArchiveList());

      $this->assign('recent_entries', Entry::getRecentEntries(10));
      $this->assign('recent_comments', Comment::getRecentComments(10));
      $this->assign('recent_trackbacks', Trackback::getRecentTrackbacks(10));
      $this->assign('recent_referers', Soojung::getRecentReferers(10));

      $this->assign('bookmarks', Bookmark::getBookmarkList());

      $this->assign('today_count', $today_count);
      $this->assign('total_count', $total_count);
    }
    $year = 0;
    $month = 0;
    $day = 0;
    if(isset($_GET["archive"])) {
      $year = substr($_GET["archive"], 0, 4);
      $month = substr($_GET["archive"], 4, 2);
      $day = substr($_GET["archive"], 6);
    }
    $calendar = new Calendar($year, $month, $day);
    $this->assign('calendar', $calendar);
  }

  function clearCache() {
    $this->clear_compiled_tpl();
    $this->clear_all_cache();
  }
}
?>
