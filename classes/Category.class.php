<?php

class Category {
  var $name;

  function Category($name) {
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

  function getEntries() {
    $entries = array();
    $query = "^[0-9].+_" . str_replace("+", "\\+", $this->getHashID()) . "_.+[.]entry$";
    $filenames = Soojung::queryFilenameMatch($query);
    usort($filenames, "cmp_base_filename");
    foreach($filenames as $filename) {
      $entries[] = new Entry($filename);
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
