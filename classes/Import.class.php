<?php

class Import {

  /**
   * static method
   */
  function importSoojung($uploadFile) {
    $fd = fopen($uploadFile['tmp_name'], "rb");
    $data = fread($fd, $uploadFile['size']);
    fclose($fd);

    while (($pos_s = strpos($data, "<file>", $pos_e)) !== FALSE) {
      $pos_e = strpos($data, "</file>", $pos_s) + strlen("</file>");
      Import::createFile(substr($data, $pos_s, $pos_e - $pos_s));
    }
  }

  /**
   * static method
   */
  function importTatterTools($dbServer, $dbUser, $dbPass, $dbName, $encoding) {
    $link = mysql_connect($dbServer, $dbUser, $dbPass) or die("could not connect");
    mysql_select_db($dbName) or die("could not select database");

    $query = "SELECT title, body, regdate, category1 FROM t3_tts";
    $result = mysql_query($query) or die("query failed");

    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $c_no = $line['category1'];
      $c_query = "SELECT label FROM t3_tts_ct1 WHERE no = " . $c_no;
      $c_result = mysql_query($c_query);
      $c_line = mysql_fetch_array($c_result);

      if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
	$title = $line['title'];
	$body = $line['body'];
	$category = $c_line['label'];
      } else {
	$title = iconv($encoding, "UTF-8", $line['title']);
	$body = iconv($encoding, "UTF-8", $line['body']);
	$category = iconv($encoding, "UTF-8", $c_line['label']);
      }
      $date = $line['regdate'];

      Entry::createEntry($title, $body, $date, $category);
      mysql_free_result($c_result);
    }

    mysql_free_result($result);
    mysql_close($link);
  }

  /**
   * static method
   */
  function importWordPress($dbServer, $dbUser, $dbPass, $dbName, $prefix, $encoding) {
    $link = mysql_connect($dbServer, $dbUser, $dbPass) or die("could not connect");
    mysql_select_db($dbName) or die("could not select database");

    $query = "select post_date, post_content, post_title, post_category from " . $prefix . "posts";
    $result = mysql_query($query) or die("query failed");

    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $c_no = $line['post_category'];
      $c_query = "select cat_name from " . $prefix . "categories where cat_ID = " . $c_no;
      $c_result = mysql_query($c_query);
      $c_line = mysql_fetch_array($c_result);

      $category = isset($c_line['cat_name']) ? $c_line['cat_name'] : "General"; //'General' is wp default category

      if (strcasecmp($encoding, "UTF-8") == 0 || strcasecmp($encoding, "UTF8") == 0) {
	$title = $line['post_title'];
	$body = $line['post_content'];
      } else {
	$title = iconv($encoding, "UTF-8", $line['post_title']);
	$body = iconv($encoding, "UTF-8", $line['post_content']);
	$category = iconv($encoding, "UTF-8", $category);
      }
      $date = strtotime($line['post_date']);

      Entry::createEntry($title, $body, $date, $category);
      mysql_free_result($c_result);
    }
    mysql_free_result($result);
    mysql_close($link);
  }

  /**
   * private, static method
   */
  function createFile($xml) {
    $name_pos = strpos($xml, "<name>") + strlen("<name>");
    $name_end = strpos($xml, "</name>");
    $name = substr($xml, $name_pos, $name_end - $name_pos);

    $dir = dirname($name);
    if (file_exists($dir) == FALSE) {
      mkdir($dir, 0777); //TODO: mkdirr
    }
    $fd = fopen($name, "wb");

    $data_pos = strpos($xml, "<data>") + strlen("<data>");
    $data_end = strpos($xml, "</data>");
    $data = substr($xml, $data_pos, $data_end - $data_pos);

    fwrite($fd, Import::trans($data));
    fclose($fd);
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