<?
include_once("settings.php");

class Template extends Smarty {
  function Template()
  {
    global $blog_skin, $blog_name, $blog_baseurl;
    global $today_count, $total_count;
    global $soojung_version;

    $this->Smarty();
    
    $this->template_dir = "templates/" . $blog_skin . "/";
    $this->compile_dir = "templates/.compile/";
    $this->config_dir = "templates/" . $blog_skin . "/";
    $this->cache_dir = "templates/.cache/";
    $this->force_compile = true;

    $this->assign('title', $blog_name);
    $this->assign('baseurl', $blog_baseurl);
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

    $this->assign('soojung_version', $soojung_version);
  }
}
?>
