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

/** Balances Tags of string using a modified stack.
 * @param text      Text to be balanced
 * @return          Returns balanced text
 * @notes           
 * import from Wordpress
 * add balance quot
 */
function balanceTags($text, $is_comment = 0) {
  /*	
  if (get_settings('use_balanceTags') == 0) {
    return $text;
  }
  */
  $tagstack = array(); 
  $stacksize = 0; 
  $tagqueue = ''; 
  $newtext = '';

# WP bug fix for comments - in case you REALLY meant to type '< !--'
  $text = str_replace('< !--', '<    !--', $text);
# WP bug fix for LOVE <3 (and other situations with '<' before a number)
  $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

  // close &quot; which isn't closed in MARKUP
  $text = ereg_replace("<([^=]*)=\"([^>\"]*)>", "<\\1=\"\\2\">", $text);
  // match MARKUP tag, $regex[1] is tagname.

  while (preg_match("/<(\/?\w*)\s*([^>]*)>/",$text,$regex)) {
    $newtext = $newtext . $tagqueue;

    $i = strpos($text,$regex[0]);
    $l = strlen($tagqueue) + strlen($regex[0]);

    // clear the shifter
    $tagqueue = '';
    // Pop or Push
    if ($regex[1][0] == "/") { // End Tag
      $tag = strtolower(substr($regex[1],1));
      // if too many closing tags
      if($stacksize <= 0) { 
	$tag = '';
	//or close to be safe $tag = '/' . $tag;
      }
      // if stacktop value = tag close value then pop
      else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
	$tag = '</' . $tag . '>'; // Close Tag
	// Pop
	array_pop ($tagstack);
	$stacksize--;
      } else { // closing tag not at top, search for it
	for ($j=$stacksize-1;$j>=0;$j--) {
	  if ($tagstack[$j] == $tag) {
	    // add tag to tagqueue
	    for ($k=$stacksize-1;$k>=$j;$k--){
	      $tagqueue .= '</' . array_pop ($tagstack) . '>';
	      $stacksize--;
	    }
	    break;
	  }
	}
	$tag = '';
      }
    } else { // Begin Tag
      $tag = strtolower($regex[1]);

      // Tag Cleaning

      // Push if not img or br or hr
      if($tag != 'br' && $tag != 'img' && $tag != 'hr' ) {
	$stacksize = array_push ($tagstack, $tag);
      }

      // Attributes
      // $attributes = $regex[2];
      $attributes = $regex[2];
      if($attributes) {
	$attributes = ' '.$attributes;
      }
      $tag = '<'.$tag.$attributes.'>';
    }
    $newtext .= substr($text,0,$i) . $tag;
    $text = substr($text,$i+$l);
  }  

  // Clear Tag Queue
  $newtext = $newtext . $tagqueue;

  // Add Remaining text
  $newtext .= $text;

  // Empty Stack
  while($x = array_pop($tagstack)) {
    $newtext = $newtext . '</' . $x . '>'; // Add remaining tags to close      
  }

  // WP fix for the bug with HTML comments
  $newtext = str_replace("< !--","<!--",$newtext);
  $newtext = str_replace("<    !--","< !--",$newtext);

  return $newtext;
}

function trim_slash($str) {
  return trim($str, "/");
}

?>