<?php

class Import {

  /**
   * static method
   */
  function importSoojung($uploadFile, $version) {
    $fd = fopen($uploadFile['tmp_name'], "rb");
    $data = fread($fd, $uploadFile['size']);
    fclose($fd);

    while (($pos_s = strpos($data, "<file>", $pos_e)) !== FALSE) {
      $pos_e = strpos($data, "</file>", $pos_s) + strlen("</file>");
      $file = substr($data, $pos_s, $pos_e - $pos_s);

      if ($version == "0.3.2") {
	Import::createFileFromVer032($file);
      } else if ($version == "0.4") {
	Import::createFile($file);
      }
      /*
      if ($version == "0.2") {
        Import::createFileFromVer02(substr($data, $pos_s, $pos_e - $pos_s));
      }
      */
    }
    exit;
  }

  /**
   * static method
   */
  function importTatterTools($dbServer, $dbUser, $dbPass, $dbName, $prefix, $encoding) {
    if (strcasecmp($encoding, 'UTF-8') == 0 || strcasecmp($encoding, 'UTF8') == 0) {
      $needtoconvert = FALSE;
    } else {
      $needtoconvert = TRUE;
    }

    $link = mysql_connect($dbServer, $dbUser, $dbPass) or die("could not connect");
    mysql_select_db($dbName) or die("could not select database");

    # preprocessing: category1
    $query = "SELECT no, label FROM ${prefix}_ct1";
    $result = mysql_query($query) or die("query failed");
    $categories = array();
    while ($category = mysql_fetch_assoc($result)) {
      $categories[$category['no']] = $category['label'];
    }
    mysql_free_result($result);

    # preprocessing: category2
    $query = "SELECT no, pno, label FROM ${prefix}_ct2";
    $result = mysql_query($query) or die("query failed");
    $subcategories = array();
    while ($category = mysql_fetch_assoc($result)) {
      $subcategories[$category['no']] = $category['label'];
    }
    mysql_free_result($result);

    # processing: post
    $query = "SELECT no, title, body, regdate, category1, category2, is_public FROM ${prefix}";
    $result = mysql_query($query) or die("query failed");
    $entries = array();
    while ($line = mysql_fetch_assoc($result)) {
      $category = $categories[$line['category1']];
      if ($line['category2'] != 0) {
        $category .= '/'.$subcategories[$line['category2']];
      }
      if ($needtoconvert) {
        $title = iconv($encoding, "UTF-8", $line['title']);
        $body = iconv($encoding, "UTF-8", $line['body']);
        $category = iconv($encoding, "UTF-8", $category);
      } else {
        $title = $line['title'];
        $body = $line['body'];
      }
      $options = array();
      if ($line['is_public'] == 0) {
        $options[] = "SECRET";
      }
      $date = $line['regdate'];
      $entryid = Entry::createEntry($title, $body, $date, $category, $options);
      $entries[$line['no']] = $entryid;
    }
    mysql_free_result($result);

    # processing: comment
    $query = "SELECT pno, name, homepage, body, regdate FROM ${prefix}_reply";
    $result = mysql_query($query) or die("query failed");
    while ($line = mysql_fetch_assoc($result)) {
      if ($needtoconvert) {
        $name = iconv($encoding, "UTF-8", $line['name']);
        $body = iconv($encoding, "UTF-8", $line['body']);
      } else {
        $name = $line['name'];
        $body = $line['body'];
      }
      $email = '';
      $homepage = $line['homepage'];
      $date = $line['regdate'];
      $entryid = $entries[$line['pno']];
      Comment::writeComment($entryid, $name, $email, $homepage, $body, $date);
    }
    mysql_free_result($result);

    # processing: trackback
    $query = "SELECT pno, site, url, title, body, regdate FROM ${prefix}_trackback";
    $result = mysql_query($query) or die("query failed");
    while ($line = mysql_fetch_assoc($result)) {
      if ($needtoconvert) {
        $name = iconv($encoding, "UTF-8", $line['site']);
        $title = iconv($encoding, "UTF-8", $line['title']);
        $excerpt = iconv($encoding, "UTF-8", $line['body']);
      } else {
        $name = $line['site'];
        $title = $line['title'];
        $excerpt = $line['body'];
      }
      $url = $line['url'];
      $date = $line['regdate'];
      $entryid = $entries[$line['pno']];
      Trackback::writeTrackback($entryid, $url, $name, $title, $excerpt, $date);
    }
    mysql_free_result($result);

    mysql_close($link);
  }

  /**
   * static method
   */
  /* TODO : seperate comment and trackback of each blog */
  function importWordPress($dbServer, $dbUser, $dbPass, $dbName, $prefix, $encoding) {
    $link = mysql_connect($dbServer, $dbUser, $dbPass) or die("could not connect");
    mysql_select_db($dbName) or die("could not select database");

    $query = "select ID, post_date, post_content, post_title, post_category from " . $prefix . "posts";
    $result = mysql_query($query) or die("query failed");

    while ($line = mysql_fetch_assoc($result)) {
      $pid = $line['ID'];
      $cid_no = $line['post_category'];
      $title = $line['post_title'];
      $body = $line['post_content'];

      if (!empty($title) &&  !empty($body)) {

        /* get parents category */
        $category = "";
        if ( (int )$cid_no !=  0) {
          $pcat_query = "select cat_name form " . $prefix . "categories where post_id - " . $cid_no;
          $pcat_result = mysql_query ($pcat_query);
          $pcat_row = mysql_fetch_array ($pcat_result);
          $category .= $pcat_row['cat_name'] . "-";
        }

        /* get category */
        $cid_query = "select category_id from " . $prefix . "post2cat where post_id = " . $pid;
        $cid_result = mysql_query($cid_query) or die("category_id query failed");
        $c_count = mysql_num_rows($cid_result);
        $i=0;
        while ($cid_row = mysql_fetch_assoc($cid_result)) {
          $i++;
          $c_no = $cid_row['category_id'];
          $c_query = "select cat_name from " . $prefix . "categories where cat_ID = " . $c_no;
          $c_result = mysql_query($c_query) or die ("category query failed");

          $c_row = mysql_fetch_assoc($c_result);
          $category .= $c_row['cat_name'];
          if ( $i < $c_count ) {
            $category .=",";
          }
          mysql_free_result($c_result);
        }

        /* get body entry */
        $title = ereg_replace("\\\\\"", "\"", $title);
        $title = ereg_replace("\\\\'", "'", $title);

        $body = ereg_replace("\\\\\"", "\"", $body);
        $body = ereg_replace("\\\\'", "'", $body);
        $body = ereg_replace("\n", "<br />", $body);
        $date = strtotime($line['post_date']);

        if (strcasecmp($encoding, "UTF-8") != 0 && strcasecmp($encoding, "UTF8") != 0) {
          $title = iconv($encoding, "UTF-8", $title);
          $body = iconv($encoding, "UTF-8", $body);
          $category = iconv($encoding, "UTF-8", $category);
        }
        $body_id=Entry::createEntry($title, $body, $date, $category);


        /* add comments */
        $comment_query = "select comment_author, comment_author_email, comment_author_url, "
                          ."comment_date, comment_content "
                          ."from " . $prefix . "comments where  comment_post_ID = " . $pid;
        $comment_result = mysql_query($comment_query) or die ("comment query failed!");

        while ($comment_row = mysql_fetch_array($comment_result)) {
          $comment_author = $comment_row['comment_author'];
          $comment_email = $comment_row['comment_author_email'];
          $comment_url = $comment_row['comment_author_url'];
          $comment_date = strtotime($comment_row['comment_date']);
          $comment_content = $comment_row['comment_content'];

          $comment_author = ereg_replace("\\\\\"", "\"", $comment_author);
          $comment_author = ereg_replace("\\\\'", "'", $comment_author);
          $comment_content = ereg_replace("\\\\\"", "\"", $comment_content);
          $comment_content = ereg_replace("\\\\'", "'", $comment_content);
          $comment_content = ereg_replace("\n", "<br />", $comment_content);

          if (strcasecmp($encoding, "UTF-8") != 0 && strcasecmp($encoding, "UTF8") != 0) {
            $comment_author = iconv($encoding, "UTF-8", $comment_author);
            $comment_content = iconv($encoding, "UTF-8", $comment_content);
          }

          Comment::writeComment($body_id, $comment_author, $comment_email,
                                $comment_url, $comment_content, $comment_date);
        }
        mysql_free_result($comment_result);
        mysql_free_result($cid_result);
      }
    }
    mysql_free_result($result);
    mysql_close($link);
  }

  /**
   * static method
   */
  function importB2($dbServer, $dbUser, $dbPass, $dbName, $prefix, $encoding) {
    $link = mysql_connect($dbServer, $dbUser, $dbPass) or die("could not connect");
    mysql_select_db($dbName) or die("could not select database");

    // posts
    $query = "select post_date, post_content, post_title, post_category, ID from " . $prefix . "posts";
    $result = mysql_query($query) or die("query failed");

    while ($line = mysql_fetch_assoc($result)) {
      $c_no = $line['post_category'];
      $c_query = "select cat_name from " . $prefix . "categories where cat_ID = " . $c_no;
      $c_result = mysql_query($c_query);
      $c_line = mysql_fetch_array($c_result);

      $category = isset($c_line['cat_name']) ? $c_line['cat_name'] : "General"; //'General' is wp default category

      if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
        $title = $line['post_title'];
        $body = stripslashes($line['post_content']);
      } else {
        $title = iconv($encoding, "UTF-8", $line['post_title']);
        $body = iconv($encoding, "UTF-8", stripslashes($line['post_content']));
        $category = iconv($encoding, "UTF-8", $category);
      }
      $date = strtotime($line['post_date']);
      $options = array();

      $id = Entry::createEntry($title, $body, $date, $category, $options);

      // comments
      $comment_query = "select comment_date, comment_author, comment_author_email, comment_author_url, comment_content from " . $prefix . "comments" . " where comment_post_ID = " . $line['ID'];
      $comment_result = mysql_query($comment_query) or die("query failed");
      while ($line = mysql_fetch_assoc($comment_result)) {
        if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
          $name = $line['comment_author'];
          $email = $line['comment_author_email'];
          $homepage = $line['comment_author_url'];
          $body = $line['comment_content'];
        } else {
          $name = iconv($encoding, "UTF-8", $line['comment_author']);
          $email = iconv($encoding, "UTF-8", $line['comment_author_email']);
          $homepage = iconv($encoding, "UTF-8", $line['comment_author_url']);
          $body = iconv($encoding, "UTF-8", $line['comment_content']);
        }
        $date = strtotime($line['comment_date']);

        Comment::writeComment($id, $name, $email, $homepage, $body, $date);
      }
      mysql_free_result($comment_result);
    }

    mysql_free_result($result);
    mysql_close($link);
  }

  /**
   * private, static method
   */
  function createFile($xml) {
    $name = Import::getNameFromXml($xml);

    $dir = dirname($name);
    if (file_exists($dir) == FALSE) {
      mkdir($dir, 0777); //TODO: mkdirr
    }
    $fd = fopen($name, "wb");

    $data = Import::getDataFromXml($xml);
    fwrite($fd, Import::trans($data));
    fclose($fd);
  }

  function createFileFromVer032($xml) {
    $name = Import::getNameFromXml($xml);
    if (strpos($name, ".entry") != false) {
      $info = explode("_", $name);
      $dot = strpos($info[1], ".");
      $id = substr($info[1], 0, $dot);

      $data = explode("\r\n", Import::trans(Import::getDataFromXml($xml)), 6);
      $date = trim(strstr($data[0], ' '));
      $title = trim(strstr($data[1], ' '));
      $category = trim(strstr($data[2], ' '));
      $options = explode("|", trim(strstr($data[3], ' ')));
      $format = "html";
      $body = $data[5];
      Entry::editEntry($id, $title, $body, $date, $category, $options, $format);
    } else {
      Import::createFile($xml);
    }
  }

  /**
  function createFileFromVer02($xml) {
    $name = Import::getNameFromXml($xml);
    if (strpos($name, ".entry") != false) { // entry file
      $info = explode("_", $name);
      $category = $info[1];
      $dot = strpos($info[2], ".");
      $id = substr($info[2], 0, $dot);
      $data = explode("\r\n", Import::trans(Import::getDataFromXml($xml)), 3);
      $title = $data[0];
      $date = $data[1];
      $body = $data[2];
      $options = array();
      Entry::editEntry($id, $title, $body, $date, $category, $options);
    } else if (strpos($name, ".trackback") != false) { // trackback file
      $id = Soojung::filenameToEntryId($name);
      $f = basename($name);
      $dot = strpos($f, ".");

      //YmdHis to Y-m-d H:i:s
      $date = substr($f, 0, $dot);
      $y = substr($date, 0, 4);
      $m = substr($date, 4, 2);
      $d = substr($date, 6, 2);
      $h = substr($date, 8, 2);
      $i = substr($date, 10, 2);
      $s = substr($date, 12);
      $date = strtotime($y . "-" . $m . "-" . $d . " " . $h . ":" . $i . ":" . $s);

      $data = explode("\r\n", Import::getDataFromXml($xml), 4);
      $url = $data[0];
      $name = $data[1];
      $title = $data[2];
      $excerpt = $data[3];
      Trackback::writeTrackback($id, $url, $name, $title, $excerpt, $date);
    } else {
      Import::createFile($xml);
    }
  }
  **/

  function getNameFromXml($xml) {
    $name_pos = strpos($xml, "<name>") + strlen("<name>");
    $name_end = strpos($xml, "</name>");
    return substr($xml, $name_pos, $name_end - $name_pos);
  }

  function getDataFromXml($xml) {
    $data_pos = strpos($xml, "<data>") + strlen("<data>");
    $data_end = strpos($xml, "</data>");
    return substr($xml, $data_pos, $data_end - $data_pos);
  }

  /**
   * private, static method
   */
  function trans($string) {
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($string, $trans);
  }

}

?>
