<?php

class Bookmark {
  
  /** 
   * add personal bookmark file 
   */
  function addBookmark($url, $desc) {
    if (empty($desc)) {
      $desc = $url;
    }

    $bookmark = array("url" => $url, "desc" => $desc);
    $bookmarks = Bookmark::getBookmarkList();
    foreach ($bookmarks as $b) {
      if ($b['url'] == $bookmark['url'])
	return false;
    }

    $bookmarks[] = $bookmark;
    Bookmark::writeBookmark($bookmarks);
    return true;
  }

  /** 
   * delete personal bookmark, key is url (not description) 
   */
  function deleteBookmark($url) {
    $bookmakrs = Bookmark::getBookmarkList();
    $new_bookmakrs = array();
    foreach($bookmakrs as $b) {
      if ($b['url'] !== $url) {
	$new_bookmarks[] = $b;
      }
    }
    Bookmark::writeBookmark($new_bookmarks);
  }

  /** 
   * writing $bookmarks to file
   */
  function writeBookmark($bookmarks) {
    $fd = fopen("contents/.bookmark", "w");
    if (!empty($bookmarks)) {
      foreach ($bookmarks as $b) {
	fwrite ($fd, $b['url'] . " " . $b['desc'] . "\r\n");
      }
    }
    fclose($fd);
  }

  /** 
   * read $bookmarks from file
   */
  function getBookmarkList() {
    $bookmarks = array();
    if (!file_exists("contents/.bookmark")) {
      return $bookmarks;
    }
    $fd = fopen("contents/.bookmark", "r");
    while (!feof($fd)) {
      $line = trim(fgets($fd, 1024));
      $b = explode(" ", $line, 2);
      if (!empty($b[0])) {
	$bookmarks[] = array("url"=>$b[0], "desc" => $b[1]);
      }
    }
    return $bookmarks;
  }
}

?>