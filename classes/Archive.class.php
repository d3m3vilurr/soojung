<?php

class Archive {
  var $year;
  var $month;

  function Archive($year, $month, $day = 0) {
    $this->year = intval($year);
    $this->month = intval($month);
    $this->day = intval($day);
  }

  function getDate() {
    return mktime(0, 0, 0, $this->month, $this->day ? $this->day : 1, $this->year);
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($this->day) {
      if ($blog_fancyurl) {
	return sprintf('%s/%04d/%02d/%02d', $blog_baseurl, $this->year, $this->month, $this->day);
      } else {
	return sprintf('%s/index.php?archive=%04d%02d%02d', $blog_baseurl, $this->year, $this->month, $this->day);
      }
    } else {
      if ($blog_fancyurl) {
	return sprintf('%s/%04d/%02d', $blog_baseurl, $this->year, $this->month);
      } else {
	return sprintf('%s/index.php?archive=%04d%02d', $blog_baseurl, $this->year, $this->month);
      }
    }
  }

  function getEntries() {
    if ($this->day) {
      $pattern = sprintf("^%04d%02d%02d[^.]+[.]entry$", $this->year, $this->month, $this->day);
    } else {
      $pattern = sprintf("^%04d%02d[^.]+[.]entry$", $this->year, $this->month);
    }
    $filenames = Soojung::queryFilenameMatch($pattern);
    rsort($filenames);
    $entries = array();
    foreach($filenames as $filename) {
      $entries[] = new Entry($filename);
    }
    return $entries;
  }

  function getEntryCount() {
    if ($this->day) {
      $pattern = sprintf("^%04d%02d%02d[^.]+[.]entry$", $this->year, $this->month, $this->day);
    } else {
      $pattern = sprintf("^%04d%02d[^.]+[.]entry$", $this->year, $this->month);
    }
    return Soojung::queryNumFilenameMatch($pattern);
  }

  /**
   * static method
   */
  function getArchive($name) {
    $year = intval(substr($name, 0, 4));
    $month = intval(substr($name, 4, 2));
    $day = intval(substr($name, 6, 2));
    return new Archive($year, $month, $day);
  }

  /**
   * static method
   */
  function getArchiveList() {
    $archives = array();
    $files = array();
    $filenames = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    foreach($filenames as $filename) {
      $t = substr($filename, 9);
      $t = substr($t, 0, 6);
      $files[] = $t;
    }
    rsort($files);
    $files = array_unique($files);

    foreach($files as $file) {
      $archive = array();
      $year = substr($file, 0, 4);
      $month = substr($file, 4);
      $archive = new Archive($year, $month);
      $archives[] = $archive;
    }
    return $archives;
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
