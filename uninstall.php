<?php

if (isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
}

function deldir($dir)
{
  $handle = opendir($dir);
  while (false!==($FolderOrFile = readdir($handle)))
  {
     if($FolderOrFile != "." && $FolderOrFile != "..")
     { 
       if(is_dir("$dir/$FolderOrFile"))
       { deldir("$dir/$FolderOrFile"); }  // recursive
       else
       { unlink("$dir/$FolderOrFile"); }
     } 
  }
  closedir($handle);
  if(rmdir($dir))
  { $success = true; }
  return $success; 
}

unlink(".htaccess");
unlink("config.php");
deldir("contents");
deldir("templates/.cache");
deldir("templates/.configs");
deldir("templates/.compile");
?>