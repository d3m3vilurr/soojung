<?php

class Category {
  var $name;

  function Category($name) {
    $this->name = $name;
  }

  function getHashID() {
    $hash = md5($this->name);
    return sprintf("%08x",
      hexdec(substr($hash, 0, 8)) + hexdec(substr($hash, 8, 8)) +
      hexdec(substr($hash, 16, 8)) + hexdec(substr($hash, 24, 8)));
  }

  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      return $blog_baseurl . '/' . $this->name;
    } else {
      return $blog_baseurl . '/index.php?category=' . urlencode($this->name);
    }
  }

  function getRssHref() {
    global $blog_baseurl;

    return $blog_baseurl . "/rss2.php?category=" . $this->name;
  }

  function getEntries() {
    $entries = array();
    $query = "^[0-9].+_" . $this->getHashID() . "_.+[.]entry$";
    $filenames = Soojung::queryFilenameMatch($query);
    usort($filenames, "cmp_base_filename");
    foreach($filenames as $filename) {
      $entries[] = new Entry($filename);
    }
    return $entries;
  }

  function getEntryCount() {
    $query = "^[0-9].+_" . $this->getHashID() . "_.+[.]entry$";
    return Soojung::queryNumFilenameMatch($query);
  }

  /**
   * static method
   */
  function getCategoryList() {
    $categories = array();
    $entries = Entry::getAllEntries();
    foreach ($entries as $entry) {
      $categories[$entry->category->name] = $entry->category;
    }
    return $categories;
  }

}

?>
