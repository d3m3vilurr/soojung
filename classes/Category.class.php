<?php

class Category {
  var $name;

  function Category($name) {
    $this->name = $name;
  }


  function getHref() {
    global $blog_baseurl, $blog_fancyurl;

    if ($blog_fancyurl) {
      return $blog_baseurl . '/' . $this->name;
    } else {
      return $blog_baseurl . '/index.php?category=' . $this->name;
    }
  }

  function getRssHref() {
    global $blog_baseurl;

    return $blog_baseurl . "/rss2.php?category=" . $this->name;
  }

  function getEntries() {
    $entries = array();
    $all = Entry::getAllEntries();
    foreach($all as $entry) {
      if ($entry->category->name == $this->name) {
	$entries[] = $entry;
      }
    }
    return $entries;
  }

  function getEntryCount() {
    $entries = $this->getEntries();
    //print_r($entries);
    return count($entries);
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