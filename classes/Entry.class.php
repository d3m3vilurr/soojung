<?php

class Entry {

  var $entryId;
  var $title;
  var $date;
  var $category;
  var $filename;
  var $href;

  var $options;

  /**
   * Entry file name:
   * contenets/[date]_[entryId].entry
   *
   * Entry file structure:
   * Date: [date]\r\n
   * Title: [title]\r\n
   * Category: [category]\r\n
   * Options: [option]\r\n
   * \r\n
   * [body]
   */
  function Entry($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    
    //read header
    $this->date = trim(strstr(fgets($fd), ' '));
    $this->title = htmlspecialchars(trim(strstr(fgets($fd), ' ')), ENT_QUOTES, "UTF-8");
    $this->category = new Category(trim(strstr(fgets($fd), ' ')));
    $this->options = explode("|", trim(strstr(fgets($fd), ' ')));
    fclose($fd);

    $this->entryId = Soojung::filenameToEntryId($filename);
  }

  function isSetOption($option) {
    return in_array($option, $this->options);
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      return $blog_baseurl . "/" . $this->category->name . date("/Y/m/d/", $this->date) . $this->entryId . ".html";
    } else {
      return $blog_baseurl . '/entry.php?blogid=' . $this->entryId;
    }
  }

  function getBody() {
    $fd = fopen($this->filename, "r");
    fgets($fd); // date
    fgets($fd); // title
    fgets($fd); // category
    fgets($fd); // options
    fgets($fd);
    $body = fread($fd, filesize($this->filename));
    fclose($fd);
    return $body;
  }

  function getCommentCount() {
    $r = Soojung::queryFilenameMatch("[.]comment$", "contents/" . $this->entryId . "/");
    return count($r);
  }

  function getComments() {
    $comments = array();
    $filenames = Soojung::queryFilenameMatch("[.]comment$", "contents/" . $this->entryId . "/");
    print_r($r);
    sort($filenames);
    foreach($filenames as $filename) {
      $comments[] = new Comment($filename);
    }
    return $comments;
  }

  function getTrackbackCount() {
    $r = Soojung::queryFilenameMatch("[.]trackback$", "contents/" . $this->entryId);
    return count($r);
  }

  function getTrackbacks() {
    $trackbacks = array();
    $filenames = Soojung::queryFilenameMatch("[.]trackback$", "contents/" . $entryId . "/");
    sort($filenames);
    foreach($filenames as $filename) {
      $trackbacks[] = new Trackback($filename);
    }
    return $trackbacks;
  }

  /**
   * static method
   */
  function entryWrite($title, $body, $date, $category, $entryId, $options) {
    $filename = "";
    if (in_array("SECRET", $options)) {
      $filename .= ".";
    }
    $filename .= date('YmdHis', $date) . '_' . $entryId . '.entry';
    $fd = fopen('contents/' . $filename, "w");
    fwrite($fd, "Date: " . $date . "\r\n");
    fwrite($fd, "Title: " . $title . "\r\n");
    fwrite($fd, "Category: " . $category . "\r\n");
    fwrite($fd, "Options: " . implode("|", $options) . "\r\n");
    fwrite($fd, "\r\n");
    fwrite($fd, $body);
    fclose($fd);
  }

  /**
   * static method
   */
  function createEntry($title, $body, $date, $category, $options) {
    $id = Soojung::createNewEntryId();
    Entry::entryWrite($title, $body, $date, $category, $id, $options);
    return $id;
  }

  /**
   * static method
   */
  function editEntry($entryId, $title, $body, $date, $category, $options) {
    //if (file_exists(Soojung::entryIdToFilename($entryId)) !== TRUE)
    //return FALSE;
    @unlink(Soojung::entryIdToFilename($entryId));
    Entry::entryWrite($title, $body, $date, $category, $entryId, $options);
    return TRUE;
  }

  /**
   * static method
   */
  function deleteEntry($entryId) {
    unlink(Soojung::entryIdToFilename($entryId));
    rmdirr("contents/" . $entryId);
  }

  /**
   * static method
   */
  function getEntryCount() {
    $r = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    return count($r);
  }

  /**
   * static method
   */
  function getEntry($entryId) {
    $filename = Soojung::entryIdToFilename($entryId);
    return new Entry($filename);
  }

  /**
   * static method
   */
  function getEntries($count, $page) {
    $entries = array();
    $filenames = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    rsort($filenames);
    $index = ($page - 1) * $count;

    for ($i = $index; $i < count($filenames) && $i < ($index + $count); $i++) {
      $entry = new Entry($filenames[$i]);
      $entries[] = $entry;
    }

    return $entries;
  }

  /**
   * static method
   */
  function getAllEntries($hide=true) {
    $entries = array();
    $query = "^[0-9].+[.]entry$";
    if ($hide == false) {
      $query = "[.]entry$";
    }
    $filenames = Soojung::queryFilenameMatch($query);
    usort($filenames, "cmp_base_filename");
    foreach($filenames as $filename) {
      $entries[] = new Entry($filename);
    }
    return $entries;
  }

  /**
   * static method
   */
  function getRecentEntries($count=10) {
    return Entry::getEntries($count, 1);
  }

  /**
   * static method
   */
  function getStaticEntries() {
    $entries = array();
    $all = Entry::getAllEntries();
    foreach($all as $e) {
      if ($e->isSetOption("STATIC")) {
	$entries[] = $e;
      }
    }
    return $entries;
  }

  /**
   * static method
   */
  function search($keyword) {
    $filenames = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    rsort($filenames);
    $founds = array();
    foreach($filenames as $f) {
      $fd = fopen($f, "r");
      $data = fread($fd, filesize($f));
      fclose($fd);
      if (strpos($data, $query) !== FALSE) {
	$founds[] = new Entry($f);
      }
    }
    return $founds;
  }
}

?>
