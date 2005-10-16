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
  if ($filename1[0] == '.') {
    $filename1 = substr($filename1, 1);
  }
  $filename2 = basename($b);
  if ($filename2[0] == '.') {
    $filename2 = substr($filename2, 1);
  }
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

function trim_slash($str) {
  return trim($str, "/");
}

/** convert any encoding string to utf8 string
 * @param string input string of unkown encoding
 * @return utf8 converted string or FALSE on failure.
 */

function convert_to_utf8 ($string) {
  $result = "";
  if (($result = iconv("UTF-8", "UTF-8", $string)) != FALSE) {
    return $result;
  } else if (($result = iconv("CP949", "UTF-8", $string)) != FALSE) {
    return $result;
  }
  return FALSE;
}

/** The function which matchs with regex query. This function using 
 *  ereg to match query string.
 */

function detect_encoding ($string) {
  if (iconv("UTF-8", "UTF-8", $string) != FALSE) {
    return "UTF-8";
  } else if (iconv("CP949", "UTF-8", $string) != FALSE) {
    return "CP949";
  }
  return FALSE;
}

function isutf8($str){
  $i=0;
  $len = strlen($str);
  for ($i=0;$i< $len;$i++) {
    $sbit = ord(substr($str,$i,1));
    if ($sbit < 128) {
    } else if($sbit > 191 && $sbit < 224) {
      $i++;
    } else if($sbit > 223 && $sbit < 240) {
      $i+=2;
    } else if($sbit > 239 && $sbit < 248) {
      $i+=3;
    } else {
      return 0;
    }
  }
  return 1;
}

function substring($str, $len){
  if(strlen($str)>$len) {
    if(!isutf8($str)) {
      $str = substr($str,0,$len) . "...";
    } else {
      $str = cut_utf8($str,$len) . "...";
    }
  }
  return $str;
}

function cut_utf8($str, $len) {
  if(strlen($str) <= $len) {
    return $str;
  }

  $str = substr($str, 0, $len);
  for($i=$len-1;$i>=0;$i--) {
    if(ord($str{$i}) >= 0xF0) {
      $next = 4;
      break;
    } elseif(ord($str{$i}) >= 0xE0) {
      $next = 3;
      break;
    } elseif(ord($str{$i}) >= 0xC0) {
      $next = 2;
      break;
    }
  }

  if(strlen($str) < $i+$next) {
    return substr($str, 0, $i);
  } else {
    return $str;
  }
}

function getFirstLine($str) {
  $array = split("\r\n", $str);
  return trim($array[0]);
}

function locked_filewrite($filename, $data) {
   ignore_user_abort(1);
   $lockfile = $filename . '.lock';

   // if a lockfile already exists, but it is more than 5 seconds old,
   // we assume the last process failed to delete it
   // this would be very rare, but possible and must be accounted for
   if (file_exists($lockfile)) {
       if (time() - filemtime($lockfile) > 5) unlink($lockfile);
   }

   $lock_ex = @fopen($lockfile, 'x');
   for ($i=0; ($lock_ex === false) && ($i < 20); $i++) {
       clearstatcache();
       usleep(rand(9, 999));
       $lock_ex = @fopen($lockfile, 'x');
   }

   $success = false;
   if ($lock_ex !== false) {
       $fp = @fopen($filename, 'w');
       if (@fwrite($fp, $data)) $success = true;
       @fclose($fp);
       fclose($lock_ex);
       unlink($lockfile);
   }

   ignore_user_abort(0);
   return $success;
}

?>
