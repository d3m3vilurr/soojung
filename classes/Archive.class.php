<?php

class Archive {
  var $year;
  var $month;

  function Archive($year, $month) {
    $this->year = $year;
    $this->month = $month;
  }

  function getName() {
    return $this->year . $this->month;
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      return $blog_baseurl . '/' . $this->year . '/' . $this->month;
    } else {
      return $blog_baseurl . '/index.php?archive=' . $this->year . $this->month;
    }
  }

  function getArchiveEntries() {
    $filenames = Soojung::queryFilenameMatch($this->year . $this->month . "[^.]+[.]entry$");
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
  function getArchiveList() {
    $archives = array();
    $files = array();
    $filenames = Soojung::queryFilenameMatch("[.]entry$");
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