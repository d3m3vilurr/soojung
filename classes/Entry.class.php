<?php

class Entry {

  var $entryId;
  var $title;
  var $date;
  var $category;
  var $filename;
  var $href;

  /**
   * Entry file name:
   * contenets/[date]_[entryId].entry
   *
   * Entry file structure:
   * [date]\r\n
   * [title]\r\n
   * [category]\r\n
   * [body]
   */
  function Entry($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    $this->date = fgets($fd);
    $this->title = fgets($fd);
    $this->category = fgets($fd);
    fclose($fd);
    //TODO: entryId, href
  }

  function getCommentCount() {
    $r = Soojung::queryFilenameMatch("[.]comments$", "contents/" . $this->entryId);
    return count($r);
  }

  function getComments() {
    $comments = array();
    $filenames = Soojung::queryFilenameMatch("[.]comment$", "contents/" . $this->entryId . "/");
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
    $filenames = query_filename_match("[.]trackback$", "contents/" . $entryId . "/");
    sort($filenames);
    foreach($filenames as $filename) {
      $trackbacks[] = new Trackback($filename);
    }
    return $trackbacks;
  }

  /**
   * static method
   */
  function entryWrite($title, $body, $date, $cateogry, $entryId) {
    $filename = date('YmdHis', $date) . '_' . $id . '.entry';
    $fd = fopen('contents/' . $filename, "w");
    fwrite($fd, $date);
    fwrite($fd, "\r\n");
    fwrite($fd, $title);
    fwrite($fd, "\r\n");
    fwrite($fd, $category);
    fwrite($fd, "\r\n");
    fwrite($fd, $body);
    fclose($fd);
  }

  /**
   * static method
   */
  function createEntry($title, $body, $date, $category="Default") {
    $id = Soojung::createNewEntryId();
    Entry::entryWrite($title, $body, $date, $category, $id);
    return $id;
  }

  /**
   * static method
   */
  function editEntry($entryId, $title, $body, $date, $category) {
    if (file_exists(Soojung::entryIdToFilename($entryId)) !== TRUE)
      return FALSE;
    unlink(Soojung::entryIdToFilename($entryId));
    Entry::entryWrite($title, $body, $date, $category, $entryId);
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
  function getEntryCount($entryId) {
    $r = Soojung::queryFilenameMatch("[.]entry$");
    return count($r);
  }

  /**
   * static method
   */
  function getEntryCountByCategory($category) {
    //TODO: impl
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