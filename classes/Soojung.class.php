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
    closedir($dh);
    return $list;
  }

  /**
   * static method
   */
  function queryNumFilenameMatch($query, $path="contents/") {
    $number = 0;
    if (is_dir($path) === false) {
      return $number;
    }

    $dh = opendir($path);
    if ($dh == false) {
      return $number;
    }

    while (($file = readdir($dh)) !== false) {
      if (ereg($query, $file)) {
	$number++;
      }
    }
    closedir($dh);
    return $number;
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
    } else if (strpos($filename, ".bookmark") != false) {
      return basename($filename, ".bookmark");
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
    flock($fd, LOCK_SH);
    $i = trim(fread($fd, filesize("contents/.info")));
    flock($fd, LOCK_UN);
    fclose($fd);
    
    $fd = fopen("contents/.info", "w");
    flock($fd, LOCK_EX);
    fwrite($fd, $i + 1);
    flock($fd, LOCK_UN);
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
      if ($filename == "admin" or $filename == "CVS") {
	continue;
      }
      if ($filename[0] != '.') {
	$list[] = $filename;
      }
    }
    return $list;
  }

  /**
   * static method
   */
  function getFormatter($format) {
    switch($format) {
    case "plain":
      return new PlainFormatter();
    case "html":
      return new HtmlFormatter();
    case "bbcode":
      return new BBcodeFormatter();
    case "moniwiki":
      return new MoniwikiFormatter();
    default:
      return new Formatter();
    }
  }

  /**
   * static method
   */
  function writeConfigFile($blogname, $blogdesc, $blogurl, $perpage, $blogfancyurl, $blognotify,
			     $adminname, $adminemail, $adminpassword, $skin = "simple", $license = "none",
			     $words="수신거부\n기적의 영문법") {
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
    fwrite($fd, '$entries_license="' . $license . "\";\n");
    fwrite($fd, '$spam_words="' . $words . "\";\n");
    fwrite($fd, "?>");
    fclose($fd);
  }

  function writeHtaccess($filename = ".htaccess") {
    $f = fopen($filename, "w");
    fwrite($f, "RewriteEngine On\n");
    fwrite($f, "RewriteRule ^(.+)/([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)[.]html$ ");
    fwrite($f, "entry.php?blogid=$5\n");
    fwrite($f, "RewriteRule ^([0-9]+)/([0-9]+) ");
    fwrite($f, "index.php?archive=$1$2\n");
    fwrite($f, "RewriteRule ^([0-9]+)/([0-9]+)/([0-9]+) ");
    fwrite($f, "index.php?archive=$1$2$3\n");
    fwrite($f, "RewriteRule ^page/([0-9]+)$ ");
    fwrite($f, "index.php?page=$1\n");
    fwrite($f, "RewriteRule ^([^/.]+)$ ");    
    fwrite($f, "index.php?category=$1\n");
    fwrite($f, "RewriteRule ^([^/.]+)/([^/.]+)$ ");
    fwrite($f, "index.php?category=$1/$2\n");
    fclose($f);
  }

  function deleteHtaccess($filename = ".htaccess") {
    if(file_exists($filename))
      unlink($filename);
  }

  // referer spam check
  function isSpam($text) {
    $words = array("search", "blogspot", "naked", "nude", "boobs", "viagra",
		   "poker", "password", "sex", "porn", "hentai");
    foreach ($words as $word) {
      if (strpos($text, $word) !== FALSE) {
	return true;
      }
    }
    return false;
  }

  function addReferer() {
    if (isset($_SERVER['HTTP_REFERER'])) {
      global $blog_baseurl;
      $referer = $_SERVER['HTTP_REFERER'];

      if(strstr($referer, $blog_baseurl) != FALSE) { //local
	return;
      }

      if (strpos($referer, "http://") !== 0) {
	return;
      }
      if (Soojung::isSpam($referer)) {
	return;
      }

      if ($fd = @fopen("contents/.referer", "r")) {
      	flock($fd, LOCK_SH);
        $data = @fread($fd, filesize("contents/.referer"));
        flock($fd, LOCK_UN);
        fclose($fd);
	$array = split("\r\n", $data);
	array_unshift($array, $referer);
	$array = array_unique($array);
	$array = array_slice($array, 0, 100);
      } else {
	$array = array();
        $array[] = $referer;
      }

      if ($fd = @fopen("contents/.referer", "w")) {
      	flock($fd, LOCK_EX);
	fwrite($fd, implode($array, "\r\n"));
        flock($fd, LOCK_UN);
        fclose($fd);
      }
    }
  }

  function getRecentReferers($n) {
    if ($fd = @fopen("contents/.referer", "r")) {
      $data = @fread($fd, filesize("contents/.referer"));
      $array = split("\r\n", $data);
      $array = array_slice($array, 0, $n);

      foreach($array as $key => $val) {
	$array[$key] = htmlspecialchars($val);
      }
      return $array;
    }
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
