<?php

class Entry {

  var $entryId;
  var $title;
  var $date;
  var $category;
  var $filename;
  var $href;

  var $options;
  var $format;

  /**
   * Entry file name:
   * contenets/[date]_[entryId].entry
   *
   * Entry file structure:
   * Date: [date]\r\n
   * Title: [title]\r\n
   * Category: [category]\r\n
   * Options: [option]\r\n
   * Format: [format]\r\n
   * \r\n
   * [body]
   */
  function Entry($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    
    //read header
    $this->date = trim(strstr(fgets($fd), ' '));
    $this->title = trim(strstr(fgets($fd), ' '));
    $this->category = new Category(trim(strstr(fgets($fd), ' ')));
    $this->options = explode("|", trim(strstr(fgets($fd), ' ')));
    $this->format = trim(strstr(fgets($fd), ' '));
    fclose($fd);

    $this->entryId = Soojung::filenameToEntryId($filename);
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      //
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
    fgets($fd); // format
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
  function entryWrite($title, $body, $date, $category, $entryId, $options, $format) {
    $filename = date('YmdHis', $date) . '_' . $entryId . '.entry';
    $fd = fopen('contents/' . $filename, "w");
    fwrite($fd, "Date: " . $date . "\r\n");
    fwrite($fd, "Title: " . $title . "\r\n");
    fwrite($fd, "Category: " . $category . "\r\n");
    fwrite($fd, "Options: " . implode("|", $options) . "\r\n");
    fwrite($fd, "Format: " . $format . "\r\n");
    fwrite($fd, "\r\n");
    fwrite($fd, $body);
    fclose($fd);
  }

  /**
   * static method
   */
  function createEntry($title, $body, $date, $category, $options, $format) {
    $id = Soojung::createNewEntryId();
    Entry::entryWrite($title, $body, $date, $category, $id, $options, $format);
    print $category;
    print "<BR>";
    return $id;
  }

  /**
   * static method
   */
  function editEntry($entryId, $title, $body, $date, $category, $options, $format) {
    if (file_exists(Soojung::entryIdToFilename($entryId)) !== TRUE)
      return FALSE;
    unlink(Soojung::entryIdToFilename($entryId));
    Entry::entryWrite($title, $body, $date, $category, $entryId, $options, $format);
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
    $r = Soojung::queryFilenameMatch("[.]entry$");
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
    $filenames = Soojung::queryFilenameMatch("[.]entry$");
    rsort($filenames);
    $index = ($page - 1) * $count;
    for ($i = $index; $i < count($filenames) && $i < ($index + $count); $i++) {
      $entries[] = new Entry($filenames[$i]);
    }
    return $entries;
  }

  /**
   * static method
   */
  function getAllEntries() {
    $entries = array();
    $filenames = Soojung::queryFilenameMatch("[.]entry$");
    rsort($filenames);
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
  function getSearch($keyword) {
    $filenames = Soojung::queryFilenameMatch("[.]entry$");
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