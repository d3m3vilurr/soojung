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
  function notifyToAdmin($title, $entryId, $msg) {
    global $notify, $admin_email;
    if ($notify != true) {
      return;
    }

    $entry = Entry::getEntry($entryId);
    $message = "<html><head></head><body>";
    $message .= $msg;
    $message .= "<br /><a href=" . $entry->getHref() . "\">check out</a>";
    $message .= "</body></html>";

    $title = "soojung: " . $title;
    mail($admin_email, $title, $message, "Content-Type: text/html; charset=\"utf-8\"");
  }

  /**
   * static method
   */
  function createNewEntryId() {
    $fd = fopen("contents/.info", "r");
    $i = trim(fread($fd, filesize("contents/.info")));
    fclose($fd);
    $fd = fopen("contents/.info", "w");
    fwrite($fd, $i + 1);
    fclose($fd);
    return $i;
  }

  
}

?>