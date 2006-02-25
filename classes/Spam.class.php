<?php

class Spam {

  function check($text, $spam_word) {
    if ($text == "") {
      return false;
    }
    $text = br2nl($text);

    if($spam_word != '') {
      $p = "@(" . implode("|", $spam_word) . ")@i";
      if(preg_match($p, $text))
	return true;
    }
    return false;
  }

  function getPattern() {
    global $spam_words;

    $words = split("(\r\n|\n)", $spam_words);
    foreach($words as $word) {
      $word = trim($word);
      if($word != '')
	$spam_word[] = "(?:" . $word . ")";
    }
    return $spam_word;
  }

  function getBodyPattern() {
    $spam_word = Spam::getPattern();
    $spam_word[] = "(^http://)";
    return $spam_word;
  }

  function isSpam($text) {
    return Spam::check($text, Spam::getPattern());
  }

  function isSpamBody($text) {
    return Spam::check($text, Spam::getBodyPattern());
  }

  function validateUrl($url) {
    $u = parse_url($url);
    if ($u == false) {
      return true;
    }
    if ($u['path'] == '/') { //check url's path
      return true;
    }
    return false;
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
