<?
include_once("settings.php");

class Template extends Smarty {
  function Template()
  {
    global $blog_name;
    global $blog_baseurl;
    global $soojung_version;

    $this->Smarty();
    
    $this->force_compile = false;

    $this->assign('title', $blog_name);
    $this->assign('baseurl', $blog_baseurl);
    $this->assign('soojung_version', $soojung_version);
  }
}
?>
