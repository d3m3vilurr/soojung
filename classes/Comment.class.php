<?php

class Comment {

  var $date;
  var $name;
  var $email;
  var $homepage;
  var $filename;

  /**
   * Comment file name:
   * contents/[entryId]/[date].comment
   *
   * Comment file structure:
   * [date]\r\n
   * [name]\r\n
   * [email]\r\n
   * [homepage]\r\n
   * [body]
   */
  function Comment($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    $this->date = fgets($fd);
    $this->name = fgets($fd);
    $this->email = fgets($fd);
    $this->homepage = fgets($fd);
    fclose($fd);
  }

  function getHref() {
    $id = Soojung::filenameToEntryId($this->filename);
    $e = Entry::getEntry($id);
    return $e->getHref() . "#" . $this->date;
  }

  function getBody() {
    $fd = fopen($this->filename, "r");
    //ignore date, name, email, homepage
    fgets($fd);
    fgets($fd);
    fgets($fd);
    fgets($fd);
    $body = fread($fd, filesize($this->filename));
    fclose($fd);
    return $body;
  }

  /**
   * static method
   */
  function writeCommenet($entryId, $name, $email, $homepage, $body, $date) {
    $e = Entry::getEntry($entryId);
    if ($e->isSetOption("NO_COMMENT")) {
      return;
    }

    $dirname = "contents/" . $entryId;
    @mkdir($dirname, 0777);

    $filename = date('YmdHis', $date) . '.comment';
    $fd = fopen($dirname . '/' . $filename, "w");
    fwrite($fd, $date);
    fwrite($fd, "\r\n");
    fwrite($fd, $name);
    fwrite($fd, "\r\n");
    fwrite($fd, $email);
    fwrite($fd, "\r\n");
    fwrite($fd, $homepage);
    fwrite($fd, "\r\n");
    fwrite($fd, $body);
    fclose($fd);

    $msg =  $name . " said:<br />";
    $msg .= $body;
    Soojung::notifyToAdmin("new comment", $entryId, $msg);
  }

  /**
   * static method
   */
  function getRecentComments($count=10) {
    $comment_filenames = array();
    $dirs = Soojung::queryFilenameMatch("^[0-9]+$", "contents/");
    foreach ($dirs as $dir) {
      $files = Soojung::queryFilenameMatch("[.]comment$", $dir . "/");
      foreach ($files as $file) {
	$comment_filenames[] = $file;
      }
    }
    usort($comment_filenames, "cmp_base_filename");

    $comment_filenames = array_slice($comment_filenames, 0, $count);
    $comments = array();
    foreach ($comment_filenames as $f) {
      $comments[] = new Comment($f);
    }
    return $comments;
  }

}

?>