<?
include_once("soojung.php");

define('SMARTY_DIR', 'libs/smarty/');
require(SMARTY_DIR . 'Smarty.class.php');

class Template extends Smarty {
  function Template()
  {
    global $blog_skin, $blog_name, $blog_baseurl;
    global $today_count, $total_count;

    add_referer();
    get_count();

    $this->Smarty();
    
    $this->template_dir = "templates/" . $blog_skin . "/";
    $this->compile_dir = "templates/.compile/";
    $this->config_dir = "templates/.configs/";
    $this->cache_dir = "templates/.cache/";
    $this->force_compile = true;

    $this->assign('title', $blog_name);
    $this->assign('baseurl', $blog_baseurl);

    $this->assign('categories', get_category_list());
    $this->assign('archvies', get_archive_list());

    $this->assign('recent_entries', get_recent_entries(10));
    $this->assign('recent_comments', get_recent_comments(10));
    $this->assign('recent_trackbacks', get_recent_trackbacks(10));
    $this->assign('recent_referers', get_recent_referers(10));

    $this->assign('today_count', $today_count);
    $this->assign('total_count', $total_count);
  }
}
?>