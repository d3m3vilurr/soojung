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
  
  function moveBookmark($url, $offset) {
    $bookmarks = Bookmark::getBookmarkList();
    $index = null;
    foreach ($bookmarks as $key => $b) {
      if ($b['url'] == $url) {
	$index = $key;
	break;
      }
    }
    
    if (is_null($index)) return false;
    $item = array_splice($bookmarks, $index, 1);
    $index += $offset;
    if ($index < 0) {
      $index = 0;
    }
    if ($index > count($bookmarks)) {
      $index = count($bookmarks);
    }
    array_splice($bookmarks, $index, 0, $item);
    Bookmark::writeBookmark($bookmarks);
    return true;
  }

  /** 
   * delete personal bookmark, key is url (not description) 
   */
  function deleteBookmark($url) {
    $bookmarks = Bookmark::getBookmarkList();
    $new_bookmarks = array();
    foreach($bookmarks as $b) {
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

# vim: ts=8 sw=2 sts=2 noet
?>
