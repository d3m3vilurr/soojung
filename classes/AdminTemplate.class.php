<?
include_once("settings.php");

class AdminTemplate extends Template {
  function AdminTemplate()
  {
    $this->Template();
    $this->compile_dir = "templates/.admin_compile/";
    $this->config_dir = "templates/.admin_configs/";
    $this->cache_dir = "templates/.admin_cache/";
    $this->template_dir = "templates/admin/";
  }
}