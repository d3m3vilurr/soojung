<?php

class Soojung {

  /**
   * static method
   */
  function queryFilenameMatch($query, $path="contents/") {
    $list = array();
    if (is_dir($path) === false) {
      return $list;
    }

    $dh = opendir($path);
    if ($dh == false) {
      return $list;
    }

    while (($file = readdir($dh)) !== false) {
      if (ereg($query, $file)) {
	$list[] = $path . $file;
      }
    }
    return $list;
  }

  /**
   * static method
   */
  function entryIdToFilename($entryId) {
    $f = Soojung::queryFilenameMatch("_" . $entryId . "[.]entry$");
    return $f[0];
  }

  /**
   * static method
   */
  function filenameToEntryId($filename) {
    if (strpos($filename, ".entry") != false) {
      $i = strrpos($filename, "_") + 1;
      $j = strrpos($filename, ".");
      return substr($filename, $i, $j-$i);
    } else { //comment, trackback
      $dirs = explode("/", $filename);
      return $dirs[1];
    }
  }

  /**
   * static method
   */
  function notifyToAdmin($title, $entryId, $msg) {
    global $notify, $admin_email;
    if ($notify != true) {
      return;
    }

    $entry = Entry::getEntry($entryId);
    $message = "<html><head></head><body>";
    $message .= $msg;
    $message .= "<br /><a href=\"" . $entry->getHref() . "\">check out</a>";
    $message .= "</body></html>";

    $title = "soojung: " . $title;
    mail($admin_email, $title, $message, "Content-Type: text/html; charset=\"utf-8\"");
  }

  /**
   * static method
   */
  function createNewEntryId() {
    clearstatcache();
    $fd = fopen("contents/.info", "r");
    $i = trim(fread($fd, filesize("contents/.info")));
    fclose($fd);
    $fd = fopen("contents/.info", "w");
    fwrite($fd, $i + 1);
    fclose($fd);
    return $i;
  }

  /**
   * static method
   */
  function getTemplates() {
    $list = array();
    $filenames = Soojung::queryFilenameMatch(".+", "templates/");
    foreach($filenames as $filename) {
      $filename = basename($filename);
      if ($filename[0] != '.') {
	$list[] = $filename;
      }
    }
    return $list;
  }

  /**
   * static method
   */
  function writeConfigFile($blogname, $blogdesc, $blogurl, $perpage, $blogfancyurl, $blognotify,
			     $adminname, $adminemail, $adminpassword, $skin = "simple") {
    $fd = fopen("config.php", "w");
    fwrite($fd, "<?php\n");
    fwrite($fd, '$blog_name="' . $blogname . "\";\n");
    fwrite($fd, '$blog_desc="' . $blogdesc . "\";\n");
    fwrite($fd, '$blog_baseurl="' . trim_slash($blogurl) . "\";\n");
    fwrite($fd, '$blog_entries_per_page=' . $perpage . ";\n");
    if ($blogfancyurl == "on") {
      fwrite($fd, '$blog_fancyurl=true;' . "\n");
    } else {
      fwrite($fd, '$blog_fancyurl=false;' . "\n");
    }
    if ($blognotify == "on") {
      fwrite($fd, '$notify=true;' . "\n");
    } else {
      fwrite($fd, '$notify=false;' . "\n");
    }
    fwrite($fd, '$blog_skin="' . $skin . "\";\n");
    fwrite($fd, '$admin_name="' . $adminname . "\";\n");
    fwrite($fd, '$admin_email="' . $adminemail . "\";\n");
    if ($adminpassword === FALSE) {
      global $admin_password;
      fwrite($fd, '$admin_password="' . $admin_password . "\";\n");
    } else {
      fwrite($fd, '$admin_password="' . $adminpassword . "\";\n");
    }
    fwrite($fd, "?>");
    fclose($fd);
  }


  function addReferer() {
    if (isset($_SERVER['HTTP_REFERER'])) {
      global $blog_baseurl;
      $referer = $_SERVER['HTTP_REFERER'];

      if(strstr($referer, $blog_baseurl) != FALSE) { //local
	return;
      }

      if ($fd = @fopen("contents/.referer", "r")) {
	$data = fread($fd, filesize("contents/.referer"));
	fclose($fd);
	$data = $referer . "\r\n" . $data;
      } else {
	$data = $referer;
      }

      if ($fd = @fopen("contents/.referer", "w")) {
        fwrite($fd, $data);
        fclose($fd);
      }
    }
  }

  function getRecentReferers($n) {
    if ($fd = @fopen("contents/.referer", "r")) {
      $data = fread($fd, filesize("contents/.referer"));
      $array = split("\r\n", $data);
      return array_slice($array, 0, $n);
    }
  }
}

?>
