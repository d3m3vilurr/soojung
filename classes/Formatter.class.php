<?php

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

class Formatter {

  function toHtml($str) {
    return htmlspecialchars($str);
  }

  function toRSS($str) {
    return $this->toHtml($str);
  }

  function onPost($str) {
    return $str;
  }

}

class PlainFormatter extends Formatter {

  function toHtml($str) {
    $trans = array("\\\r\n"=>"", "\\\r"=>"", "\\\n"=>"");
    $text = "";
    $spos = 0;
    while (($pos = @strpos($str, "<pre", $spos)) !== FALSE) {
      $text .= nl2br(strtr(trim(substr($str, $spos, $pos-$spos)), $trans));
      $spos = strpos($str, "</pre>", $spos) + 6;
      $text .= substr($str, $pos, $spos-$pos);
    }
    $text .= nl2br(strtr(trim(substr($str, $spos)), $trans));
    return $text;
  }

  function onPost($str) {
    return balanceTags($str);
  }

}

class HtmlFormatter extends Formatter {

  function toHtml($str) {
    return $str;
  }

  function onPost($str) {
    return balanceTags($str);
  }

}

class BBcodeFormatter extends Formatter {

  function __listing($mode, $str) {
    $item = explode("[*]", $str);
    $rstr = trim($item[0]);
    for($i=1;$i<count($item);$i++) {
      $rstr .= "<li>".trim($item[$i])."</li>";
    }
    switch($mode) {
    case "A": return "<ol class=\"list-ualpha\">$rstr</ol>";
    case "a": return "<ol class=\"list-lalpha\">$rstr</ol>";
    case "1": return "<ol class=\"list-decimal\">$rstr</ol>";
    case "I": return "<ol class=\"list-uroman\">$rstr</ol>";
    case "i": return "<ol class=\"list-lroman\">$rstr</ol>";
    default: return "<ul>$rstr</ul>";
    }
  }

  function __escape($str) {
    return strtr($str, array("@"=>"\0@", "://"=>"\0://", "["=>"[\0"));
  }

  function toHtml($str) {
    static
      $rule1 = array(
        '#\[i](.+)\[/i]#iU',
        '#\[b](.+)\[/b]#iU',
        '#\[u](.+)\[/u]#iU',
        '#\[\+\+](.+)\[/\+\+]#iU',
        '#\[--](.+)\[/--]#iU',
        '#\[color=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/color]#i',
        '#\[size=((?:&quot;)?)([\d.]+)\1](.+?)\[/size]#i',
        '#\[quote](.+)\[/quote]#isU',
        '#\[quote=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/quote]#is',
        '#\[list(?:=([Aa1Ii]))?](.+)\[/list]#iseU',
        '#\[url](.+)\[/url]#ieU',
        '#\[url=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/url]#ie',
        '#\[img](.+)\[/img]#ieU',
        '#\[img=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/img]#ie',
        '#\[email](.+)\[/email]#ieU'),
      $repl1 = array(
        '<i>\1</i>',
        '<b>\1</b>',
        '<span style="text-decoration:underline;">\1</span>',
        '<ins>\1</ins>',
        '<del>\1</del>',
        '<span style="color:\2;">\3</span>',
        '<span style="font-size:\2pt;">\3</span>',
        '<blockquote><div>\1</div></blockquote>',
        '<blockquote><p class="quotetitle">\2</p><div>\3</div></blockquote>',
        'BBcodeFormatter::__listing("\1","\2")',
        'BBcodeFormatter::__escape("<a href=\\"\1\\">\1</a>")',
        'BBcodeFormatter::__escape("<a href=\\"\2\\">\3</a>")',
        'BBcodeFormatter::__escape("<img src=\\"\1\\" width=\\"\\" height=\\"\\" alt=\\"\1\\\" class=\\"bbcode\\" />")',
        'BBcodeFormatter::__escape("<img src=\\"\2\\" width=\\"\\" height=\\"\\" alt=\\"\3\\\" class=\\"bbcode\\" />")',
        'BBcodeFormatter::__escape("<a href=\\"mailto:\1\\">\1</a>")'),
      $rule2 = array(
        '#http://(?:[-0-9a-z_.@:~\\#%=+?/]|&amp;)+#i',
        '#[-0-9a-z_.]+@[-0-9a-z_.]+#i'),
      $repl2 = array(
        '<a href="\0">\0</a>',
        '<a href="mailto:\0">\0</a>'),
      $_smiley = array(
        ":D" => "icon_biggrin.gif",
        ":)" => "icon_smile.gif",
        ":(" => "icon_sad.gif",
        ":shock:" => "icon_eek.gif",
        "8)" => "icon_cool.gif",
        ":lol:" => "icon_lol.gif",
        ":x" => "icon_mad.gif",
        ":p" => "icon_razz.gif",
        ":cry:" => "icon_cry.gif",
        ":evil:" => "icon_evil.gif",
        ":twisted:" => "icon_twisted.gif",
        ":roll:" => "icon_rolleyes.gif",
        ";)" => "icon_wink.gif",
        ":!:" => "icon_exclaim.gif",
        ":idea:" => "icon_idea.gif",
        ":arrow:" => "icon_arrow.gif",
        ":|" => "icon_neutral.gif",
        ":mrgreen:" => "icon_mrgreen.gif",
        ":oops:" => "icon_redface.gif",
        ":o" => "icon_surprised.gif",
        ":?:" => "icon_question.gif",
        ":?" => "icon_confused.gif"),
      $smiley = null;

    if(is_null($smiley)) {
      $smiley = array();
      foreach($_smiley as $k => $v) {
        $smiley[htmlspecialchars($k)] = '<img src="./libs/bbcode/smiles/'.$v.'" width="15" height="15" alt="'.htmlspecialchars($k).'" />';
      }
    }

    $option = array("smiley" => true);
    if(preg_match("/^#pragma(.*?)(?:(?:\r\n?|\n)+|$)/i", $str, $m)) {
      $str = str_replace($m[0], "", $str);
      $options = explode(" ", strtolower(trim($m[1])));
      foreach($options as $v) {
        if($v == "nosmiley") $option["smiley"] = false;
      }
    }

    $str = explode("\0", preg_replace('#\[code](.*?)\[/code](?:\r\n|\r|\n)?#is', "\0\\1\0", $str));
    $rstr = "";
    for($i=0;$i<count($str);$i++) {
      if($i % 2 == 0) {
        $temp = htmlspecialchars($str[$i]);
        if($option["smiley"]) {
          $temp = strtr($temp, $smiley);
        }
        $temp = preg_replace('#\[literal](.*)\[/literal]#ieU', 'BBcodeFormatter::__escape("\1")', $temp);
        $temp = preg_replace($rule2, $repl2, preg_replace($rule1, $repl1, $temp));
        $rstr .= nl2br(str_replace("\0", "", $temp));
      } else {
        $rstr .= "<pre>".htmlspecialchars(trim($str[$i], "\r\n"))."</pre>";
      }
    }
    return "<div class=\"format_bbcode\">$rstr</div>";
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
