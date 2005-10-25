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
    $this->date = trim(fgets($fd, 1024));
    $this->name = trim(fgets($fd, 1024));
    $this->email = trim(fgets($fd, 1024));
    $this->homepage = trim(fgets($fd, 1024));
    fclose($fd);
  }

  function getHref() {
    $id = Soojung::filenameToEntryId($this->filename);
    $e = Entry::getEntry($id);
    return $e->getHref() . "#CO" . $this->date;
  }

  function getBody() {
    $fd = fopen($this->filename, "r");
    //ignore date, name, email, homepage
    fgets($fd, 1024);
    fgets($fd, 1024);
    fgets($fd, 1024);
    fgets($fd, 1024);
    $body = fread($fd, filesize($this->filename));
    fclose($fd);
    return $body;
  }

  /**
   * static method
   * if $text is spam comment return true
   */ 
  function isSpam($text) {
    if ($text == "") {
      return false;
    }
    global $spam_words;
    $text = br2nl($text);

    $words = split("(\r\n|\n)", $spam_words);
    foreach($words as $word) {
      $word = trim($word);
      if($word != '')
	$spam_word[] = "(?:" . $word. ")";
    }
    if($spam_word != '') {
      $p = "@(" . implode("|", $spam_word) . ")@i";
      if(preg_match($p, $text))
	return true;
    }
    return false;
  }

  /**
   * static method
   */
  function writeComment($entryId, $name, $email, $homepage, $body, $date) {
    $e = Entry::getEntry($entryId);
    if ($e->isSetOption("NO_COMMENT")) {
      return;
    }
    
    if (Comment::isSpam($body) || Comment::isSpam($name) || Comment::isSpam($homepage)) {
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

# vim: ts=8 sw=2 sts=2 noet
?>
