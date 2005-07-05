<?php

class Category {
  var $name;

  function Category($name) {
    if(!isutf8($name)) {
      $name = iconv('UHC//IGNORE', 'UTF-8', $name);
    }
    $this->name = $name;
  }

  function getHashID() {
    return str_replace("/", "-", base64_encode($this->name));
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

  function getEntries($count = -1, $page = 1) {
    $entries = array();
    $query = "^[0-9].+_" . str_replace("+", "\\+", $this->getHashID()) . "_.+[.]entry$";
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

  function getEntryCount() {
    $query = "^[0-9].+_" . str_replace("+", "\\+", $this->getHashID()) . "_.+[.]entry$";
    return Soojung::queryNumFilenameMatch($query);
  }

  /**
   * static method
   */
  function getCategoryList() {
    $categories = $categoryentries = array();
    $filenames = Soojung::queryFilenameMatch("^[0-9].+[.]entry$");
    foreach($filenames as $filename) {
      $filenamepart = explode("_", $filename);
      if(!isset($categoryentries[$filenamepart[1]])) {
        $categoryentries[$filenamepart[1]] = true;
        $entry = new Entry($filename);
        $categories[$entry->category->name] = $entry->category;
      }
    }
    ksort($categories);
    return $categories;
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
