<?php

class Bookmark {
  

  /**
   * Bookmark file name:
   * [number].bookmark
   *
   * Bookmark file structure:
   * Name: [name]\r\n
   * URL: [url]\r\n
   * ImageLink: [url]\r\n
   * Desc: [desc]\r\n
   */
  
  /** 
   * add personal bookmark file 
   */
  function Bookmark($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    $this->number = Soojung::filenameToEntryId($filename);
    $this->name = trim(strstr(fgets($fd, 1024), ' '));
    $this->url = htmlspecialchars(trim(strstr(fgets($fd, 1024), ' ')), ENT_QUOTES, "UTF-8");
    $this->image = htmlspecialchars(trim(strstr(fgets($fd, 1024), ' ')), ENT_QUOTES, "UTF-8");
    $this->desc = trim(strstr(fgets($fd, 1024), ' '));
    fclose($fd);
  }

  function getBookmark($number) {
    if (empty($number)) return false;
    $filename = 'contents/.bookmark/' . $number . '.bookmark';
    return new Bookmark($filename);
  }

  /*
  function addBookmark($name, $url, $desc, $image) {
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
  */

  function bookmarkWrite($number, $name, $url, $desc, $image) {
    if (empty($number) || !isset($number) || $number == 0) {
      /* get last number + 1 */
      $filenames = Soojung::queryFilenameMatch("^[0-9]+[.]bookmark$", "contents/.bookmark/");
      sort($filenames);
      $last_index = count($filenames) - 1;

      if (empty($filenames[$last_index]) || !isset($filenames[$last_index]) || $filenames[$last_index] == "") {
	$number = 1;
      } else {
	$number = Soojung::filenameToEntryId($filenames[$last_index]) + 1;
      }
    }
    $filename = "$number.bookmark";
    @mkdir('contents/.bookmark');
    $fd = fopen('contents/.bookmark/'. $filename, "w");

    fwrite($fd, "Name: ". $name. "\r\n");
    fwrite($fd, "URL: ". $url. "\r\n");
    fwrite($fd, "ImageLink: ". $image. "\r\n");
    fwrite($fd, "Desc: ". $desc. "\r\n");
    fclose($fd);

    return true;
  }

  /* rename it */
  function moveBookmark($number, $offset) {
#    return false;
    $bookmarks = Bookmark::getBookmarkList();
    $index = -1;
    $bookmark = Bookmark::getBookmark($number);
    foreach ($bookmarks as $key => $b) {
      if ($b->number == $number) {
	$index = $key;
	break;
      }
    }

    if ($index == -1)
      {
	return false;
      }
    $index_new = $index + $offset;
    /* check $index_new is in range */
    if ($index_new < 0)
      $index_new = 0;
    else if ($index_new >= count($bookmarks))
      $index_new = count($bookmarks) -1;


    $b = $bookmarks[$index];
    $b_new = $bookmarks[$index_new];

    Bookmark::bookmarkWrite($b_new->number, $b->name, $b->url, $b->desc, $b->image);
    Bookmark::bookmarkWrite($b->number, $b_new->name, $b_new->url, $b_new->desc, $b_new->image);

  }

  /** 
   * delete personal bookmark, key is url (not description) 
   */
  function deleteBookmark($number) {
    $filename = "contents/.bookmark/$number.bookmark";
    unlink($filename);
  }


  /** 
   * read $bookmarks from file
   */
  function getBookmarkList() {
    if (is_file("contents/.bookmark")) {
      $bookmarks = array();
      if (!file_exists("contents/.bookmark")) {
	return $bookmarks;
      }
      rename("contents/.bookmark", "contents/.bookmark_old");
      $fd = fopen("contents/.bookmark_old", "r");

      $number = 0;
      while (!feof($fd)) {
	$number++;
	$line = trim(fgets($fd, 1024));
	$b = explode(" ", $line, 2);
	if (!empty($b[0])) {
	  Bookmark::bookmarkWrite($number, $b[1], $b[0], $b[1], null);
	  $bookmark = array("url"=>$b[0], "desc" => $b[1]);
	}
      }
      fclose($fd);
      unlink("contents/.bookmark_old");
    }
     
    $entries = array();
    $filenames = Soojung::queryFilenameMatch("^[0-9]+[.]bookmark$", "contents/.bookmark/");
    natsort($filenames);

    for ($i = 0; $i < count($filenames); $i++) {
      $bookmark = new Bookmark($filenames[$i]);
      $bookmarks[] = $bookmark;
    }

    return $bookmarks;
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
