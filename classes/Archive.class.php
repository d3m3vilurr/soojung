<?php

class Archive {
  var $year;
  var $month;

  function Archive($year, $month) {
    $this->year = intval($year);
    $this->month = intval($month);
  }

  function getDate() {
    return mktime(0, 0, 0, $this->month, 1, $this->year);
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      return sprintf('%s/%04d/%02d', $blog_baseurl, $this->year, $this->month);
    } else {
      return sprintf('%s/index.php?archive=%04d%02d', $blog_baseurl, $this->year, $this->month);
    }
  }

  function getEntries() {
    $filenames = Soojung::queryFilenameMatch(sprintf("^%04d%02d[^.]+[.]entry$", $this->year, $this->month));
    rsort($filenames);
    $entries = array();
    foreach($filenames as $filename) {
      $entries[] = new Entry($filename);
    }
    return $entries;
  }

  /**
   * static method
   */
  function getArchive($name) {
    $year = substr($name, 0, 4);
    $month = substr($name, 4, 2);
    return new Archive($year, $month);
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

?>

<?
# vim: ts=8 sw=2 sts=2 noet
?>