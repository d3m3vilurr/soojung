<?php

function br2nl( $data ) {
   return preg_replace( '!<br.*>!iU', "", $data );
}

function pre_nl2br($string) {
  $s = $string;
  $pos = strpos($s, "<pre");
  if ($pos === false) {
    return nl2br($s);
  }

  $text = "";
  while (($pos = @strpos($s, "<pre")) !== FALSE) {
    $text .= nl2br(trim(substr($s, 0, $pos)));

    $s = substr($s, $pos);
    $endpos = strpos($s, "</pre>") + strlen("</pre>");

    $text .= substr($s, 0, $endpos);
    $s = substr($s, $endpos);
  }
  $text .= nl2br(trim($s));
  return $text;
}

function cmp_base_filename($a, $b) {
  $filename1 = basename($a);
  $filename2 = basename($b);
  return ($filename1 < $filename2) ? 1 : -1;
}

function rmdirr($dirname)
{
  if (file_exists($dirname) == false) {
    return 0;
  }

  if (is_file($dirname)) {
    return unlink($dirname);
  }
  
  $dir = dir($dirname);
  while (false !== $entry = $dir->read()) {
    if ($entry == '.' || $entry == '..') {
      continue;
    }

    if (is_dir("$dirname/$entry")) {
      rmdirr("$dirname/$entry");
    } else {
      unlink("$dirname/$entry");
    }
  }

  $dir->close();
  return rmdir($dirname);
}

?>