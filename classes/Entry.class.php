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
    if (empty($filename)) {
      return;
    }

    $this->filename = $filename;
    $this->db = new Database($filename);
    
    $this->date = $this->db->field["date"];
    $this->title = $this->db->field["title"];
    $this->category = new Category($this->db->field["category"]);
    $this->options = explode("|", $this->db->field["options"]);
    $this->format = $this->db->field["format"];

    $this->entryId = Soojung::filenameToEntryId($filename);
  }

  function isSetOption($option) {
    if (empty($this->options)) {
      return false;
    }
    return in_array($option, $this->options);
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      $_category = preg_replace('/%2F/i','/',urlencode($this->category->name));
      return $blog_baseurl . "/" . $_category . date("/Y/m/d/", $this->date) . $this->entryId . ".html";
    } else {
      return $blog_baseurl . '/entry.php?blogid=' . $this->entryId;
    }
  }

  function getRawBody() {
    $blocks = $this->db->getBlock();
    return $blocks[0];
  }

  function getBody() {
    $body = $this->getRawBody();
    $formatter = Soojung::getFormatter($this->format);
    return $formatter->toHtml($body);
  }

  function getCommentCount() {
    return Soojung::queryNumFilenameMatch("[.]comment$", "contents/" . $this->entryId . "/");
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
    return Soojung::queryNumFilenameMatch("[.]trackback$", "contents/" . $this->entryId);
  }

  function getTrackbacks() {
    $trackbacks = array();
    $filenames = Soojung::queryFilenameMatch("[.]trackback$", "contents/" . $this->entryId . "/");
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
    $filename = "";
    if (in_array("SECRET", $options)) {
      $filename .= ".";
    }
    $foptions = "";
    if (in_array("STATIC", $options)) {
      $foptions .= "S";
    }
    $categoryclass = new Category($category);
    $filename .= date('YmdHis', $date) . $foptions . '_' . $categoryclass->getHashID() . '_' . $entryId . '.entry';
    $db = new Database();
    $db->field = array(
      "date" => $date, "title" => $title, "category" => $category,
      "options" => implode("|", $options), "format" => $format);
    $db->block = array($body);
    $db->write("contents/".$filename);
  }

  /**
   * static method
   */
  function createEntry($title, $body, $date, $category, $options, $format = "plain") {
    $id = Soojung::createNewEntryId();
    Entry::entryWrite($title, $body, $date, $category, $id, $options, $format);
    return $id;
  }

  /**
   * static method
   */
  function editEntry($entryId, $title, $body, $date, $category, $options, $format) {
    //if (file_exists(Soojung::entryIdToFilename($entryId)) !== TRUE)
    //return FALSE;
    @unlink(Soojung::entryIdToFilename($entryId));
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
  function getEntryCount($hide=true) {
    return Soojung::queryNumFilenameMatch(Entry::_getQuery($hide));
  }

  /**
   * static method
   */
  function getEntry($entryId) {
    $filename = Soojung::entryIdToFilename($entryId);
    return new Entry($filename);
  }

  /**
   * private static method
   */
  function _getQuery($hide) {
    if ($hide == false) {
      $query = "[.]entry$";
    } else {
      $query = "^[0-9].+[.]entry$";
    }
    return $query;
  }

  /**
   * static method
   */
  function getEntries($count, $page, $hide=true) {
    $entries = array();
    $query = Entry::_getQuery($hide);
    $filenames = Soojung::queryFilenameMatch($query);
    usort($filenames, "cmp_base_filename");
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
    $query = Entry::_getQuery($hide);
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
  function getRecentEntries($count=10, $hide=true) {
    return Entry::getEntries($count, 1, $hide);
  }

  /**
   * static method
   */
  function getStaticEntries() {
    $entries = array();
    $query = "^[0-9].+S_.+[.]entry$";
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
  function search($keyword) {
    $founds = array();
    $filenames = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    rsort($filenames);
    foreach($filenames as $f) {
      $fd = fopen($f, "r");
      $data = fread($fd, filesize($f));
      fclose($fd);
      if (strpos($data, $keyword) !== FALSE) {
	$founds[] = new Entry($f);
      }
    }
    return $founds;
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
