<?php
session_start();

include("libs/util.php");

if (!isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

unlink(".htaccess");
unlink("config.php");
rmdirr("contents");
rmdirr("templates/.cache");
rmdirr("templates/.configs");
rmdirr("templates/.compile");
rmdirr("templates/.admin_cache");
rmdirr("templates/.admin_configs");
rmdirr("templates/.admin_compile");

# vim: ts=8 sw=2 sts=2 noet
?>
