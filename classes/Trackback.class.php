<?php

class Trackback {
  
  var $date;
  var $url;
  var $name;
  var $title;
  var $excerpt;
  var $filename;
  var $href;

  /**
   * Trackback file name:
   * contents/[entryId]/[date].trackback
   *
   * Comment file structure:
   * [date]\r\n
   * [url]\r\n
   * [name]\r\n
   * [title]\r\n
   * [excerpt]
   */
  function Trackback($filename) {
    $this->filename = $filename;
    $fd = fopen($filename, "r");
    $this->date = fgets($fd);
    $this->url = fgets($fd);
    $this->name = fgets($fd);
    $this->title = fgets($fd);
    fclose($fd);
    //TODO: set href
  }

  function getExcerpt($filename) {
    $fd = fopen($this->filename, "r");
    //ignore date, url, name, title
    fgets($fd);
    fgets($fd);
    fgets($fd);
    fgets($fd);
    $excerpt = fread($fd, filesize($this->filename));
    fclose($fd);
    return $excerpt;
  }

  /**
   * need to check entryId is not null or anything. this is caused by tattertools.
   * static method
   */
  function writeTrackback($entryId, $url, $name, $title, $excerpt) {
    $dirname = "contents/" . $entryId;
    @mkdir($dirname, 0777);

    $filename = date('YmdHis', time()) . '.trackback';
    $fd = fopen($dirname . '/' . $filename, "w");
    fwrite($fd, $date);
    fwrite($fd, "\r\n");
    fwrite($fd, $url);
    fwrite($fd, "\r\n");
    fwrite($fd, $name);
    fwrite($fd, "\r\n");
    fwrite($fd, $title);
    fwrite($fd, "\r\n");
    fwrite($fd, $excerpt);
    fclose($fd);

    $msg = "trackback from " . $url . "<br />";
    Soojung::notifyToAdmin("new trackback", $entryId, $msg);
  }

  /**
   * static method
   */
  function getRecentTrackbacks($count=10) {
    $filenames = array();
    $dirs = Soojung::queryFilenameMatch("^[0-9]+$", "contents/");
    foreach ($dirs as $dir) {
      $files = Soojung::queryFilenameMatch("[.]trackback$", $dir . "/");
      foreach ($files as $file) {
	$filenames[] = $file;
      }
    }
    usort($filenames, "cmp_base_filename");

    $filenames = array_slice($filenames, 0, $count);
    $trackbacks = array();
    foreach ($filenames as $f) {
      $trackbacks[] = new Trackback($f);
    }
    return $trackbacks;
  }

  /**
   * static method
   */
  function sendTrackbackPing($entryId, $trackbackUrl, $encoding="UTF-8") {
    global $blog_name;
  
    $tb_url = parse_url($trackbackUrl);
    if (isset ($tb_url['port']))
      $tb_port = $tb_url['port'];
    else
      $tb_port = 80;

    //  $permlink = rawurlencode(get_entry_link($blogid, false));
    $entry = Entry::getEntry($entryId);
    $permlink = $entry->getHref();
    //if ( isset($entry['title']))
    if ($entry->getTitle() != null) {
      $tb_title = rawurlencode(iconv("UTF-8", $encoding, $entry->getTitle()));
    } else {
      $tb_title = rawurlencode('title');
    }

    $tb_excerpt = iconv("UTF-8", $encoding, $entry->getBody());
    if (strlen ($tb_excerpt) > 255)
      $tb_excerpt = substr($tb_excerpt,0, 252) . "...";
    $tb_excerpt = rawurlencode($tb_excerpt);

    if (isset($blog_name)) {
      $tb_blogname = rawurlencode(iconv("UTF-8", $encoding, $blog_name));
    } else {
      $tb_blogname = rawurlencode('soojung blog');
    }

    $query_string = "title=$tb_title&url=$permlink&excerpt=$tb_excerpt&blog_name=$tb_blogname";
    $query_string = iconv( "UTF-8", $encoding, $query_string);
    echo "query_string : $query_string<br />"; //debug code?

    $http_request  = 'POST '.$trackback_url." HTTP/1.0\r\n";
    $http_request .= 'Content-Type: application/x-www-form-urlencoded'."\r\n";
    $http_request .= 'Content-Length: '.strlen($query_string)."\r\n\r\n";
    $http_request .= $query_string;

    $response = array();
    if (!($fp = fsockopen($tb_url['host'], $tb_port))) {
      // Cannot open trackback url
      $response['error'] = 1;
      $response['message'] = "Cannot connect to host \"".$tb_url['host']."\"";
      echo "merong<br>\n";
      return $response;
    } 

    if (!fputs($fp, $http_request)) {
      echo "cannot send trackback ping<br />\n";
    }

    $line = "";
    while (!feof ($fp)) {
      $line .= fgets ($fp, 1024);
    }

    if (ereg("<error>[^<0-9]*([0-9]*)[^<0-9]*</error>", $line, $regs)) {
      $response['error'] = $regs[1];
      if ($response == 0 && ereg("<message>([<]*)</message>", $line, $regs)) {
	$response['message'] = $regs[1];
      }
    }

    fclose ($fp);
    return $response;
  }

}

?>