<?
include_once("settings.php");

class UserTemplate extends Template {
  function UserTemplate()
  {
    global $blog_skin;
    global $today_count, $total_count;

    $this->Template();
    
    $this->template_dir = "templates/" . $blog_skin . "/";
    $this->compile_dir = "templates/.compile/";
    $this->config_dir = "templates/" . $blog_skin . "/";
    $this->cache_dir = "templates/.cache/";

    $this->assign('skin', $blog_skin);

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
}
?>
