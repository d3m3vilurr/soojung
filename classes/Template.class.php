<?
include_once("settings.php");

class Template extends Smarty {
  function Template()
  {
    global $blog_name;
    global $blog_desc;
    global $blog_baseurl;
    global $soojung_version;

    $this->Smarty();
    
    $this->force_compile = false;

    $this->assign('title', htmlspecialchars($blog_name));
    $this->assign('description', htmlspecialchars($blog_desc));
    $this->assign('baseurl', $blog_baseurl);
    $this->assign('soojung_version', $soojung_version);
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
