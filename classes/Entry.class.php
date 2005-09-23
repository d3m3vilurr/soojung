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
    $fd = fopen($filename, "r");
    
    //read header
    $this->date = trim(strstr(fgets($fd, 1024), ' '));
    $this->title = trim(strstr(fgets($fd, 1024), ' '));
    $this->category = new Category(trim(strstr(fgets($fd, 1024), ' ')));
    $this->options = explode("|", trim(strstr(fgets($fd, 1024), ' ')));
    $this->format = trim(strstr(fgets($fd, 1024), ' '));
    fclose($fd);

    $this->entryId = Soojung::filenameToEntryId($filename);
  }

  function isSetOption($option) {
    if (empty($this->options)) {
      return false;
    }
    return in_array($option, $this->options);
  }

  function getHref($toShow=false) {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      $_category ="";
      if ($toShow) {
	$_category = $this->category->name;
      } else {
	$_category = preg_replace('/%2F/i','/',urlencode($this->category->name));
      }
      return $blog_baseurl . "/" . $_category . date("/Y/m/d/", $this->date) . $this->entryId . ".html";
    } else {
      return $blog_baseurl . '/entry.php?blogid=' . $this->entryId;
    }
  }

  function getRawBody() {
    $fd = fopen($this->filename, "r");
    fgets($fd, 1024); // date
    fgets($fd, 1024); // title
    fgets($fd, 1024); // category
    fgets($fd, 1024); // options
    fgets($fd, 1024); // format
    fgets($fd, 1024);
    $body = fread($fd, filesize($this->filename));
    fclose($fd);
    
    return $body;
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
  function getStaticEntries($count = -1, $page = 1) {
    $entries = array();
    $query = "^[0-9].+S_.+[.]entry$";
    $filenames = Soojung::queryFilenameMatch($query);
    usort($filenames, "cmp_base_filename");

    if ($count == -1) { //all
      foreach($filenames as $filename) {
	$entries[] = new Entry($filename);
      }
    } else {
      $index = ($page - 1) * $count;
      for ($i = $index; $i < count($filenames) && $i < ($index + $count); $i++) {
	$entries[] = new Entry($filenames[$i]);
      }
    }

    return $entries;
  }

  function getStaticEntryCount() {
    return Soojung::queryNumFilenameMatch("^[0-9].+S_.+[.]entry$");
  }

  /**
   * static method
   */
  function search($keyword) {
    $founds = array();
    if ($keyword == "") {
      return $founds;
    }
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
