<?
include_once("settings.php");

class Template extends Smarty {
  function Template()
  {
    global $blog_skin, $blog_name, $blog_baseurl;
    global $today_count, $total_count;

    //add_referer();
    //get_count();

    $this->Smarty();
    
    $this->template_dir = "templates/" . $blog_skin . "/";
    $this->compile_dir = "templates/.compile/";
    $this->config_dir = "templates/.configs/";
    $this->cache_dir = "templates/.cache/";
    $this->force_compile = true;

    $this->assign('title', $blog_name);
    $this->assign('baseurl', $blog_baseurl);
    $this->assign('skin', $blog_skin);

    //$this->assign('categories', get_category_list());
    $this->assign('archvies', Archive::getArchiveList());

    $this->assign('recent_entries', Entry::getRecentEntries(10));
    $this->assign('recent_comments', Comment::getRecentComments(10));
    $this->assign('recent_trackbacks', Trackback::getRecentTrackbacks(10));
    //$this->assign('recent_referers', get_recent_referers(10));

    //$this->assign('bookmarks', get_bookmark_list());

    //$this->assign('today_count', $today_count);
    //$this->assign('total_count', $total_count);
  }
}
?>
