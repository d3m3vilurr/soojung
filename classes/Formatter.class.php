<?php

class Formatter {

  function plainToHtml($str) {
    $pos = strpos($str, "<pre");
    if ($pos === false) {
      return nl2br($str);
    }
 
    $text = "";
    while (($pos = @strpos($str, "<pre")) !== FALSE) {
      $text .= nl2br(trim(substr($str, 0, $pos)));
 
      $str = substr($str, $pos);
      $endpos = strpos($str, "</pre>") + strlen("</pre>");
 
      $text .= substr($str, 0, $endpos);
      $str = substr($str, $endpos);
    }
    $text .= nl2br(trim($str));
    return $text;
  }

  function __bbcode_listing($mode, $str) {
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

  function __bbcode_escape($str) {
    return strtr($str, array("@"=>"\0@", "://"=>"\0://", "["=>"[\0"));
  }

  function bbcodeToHtml($str) {
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
		'Formatter::__bbcode_listing("\1","\2")',
		'Formatter::__bbcode_escape("<a href=\\"\1\\">\1</a>")',
		'Formatter::__bbcode_escape("<a href=\\"\2\\">\3</a>")',
		'Formatter::__bbcode_escape("<img src=\\"\1\\" width=\\"\\" height=\\"\\" alt=\\"\1\\\" />")',
		'Formatter::__bbcode_escape("<img src=\\"\2\\" width=\\"\\" height=\\"\\" alt=\\"\3\\\" />")',
		'Formatter::__bbcode_escape("<a href=\\"mailto:\1\\">\1</a>")'),
      $rule2 = array(
		'#http://(?:[-0-9a-z_.@:~\\#%=+?/]|&amp;)+#i',
		'#[-0-9a-z_.]+@[-0-9a-z_.]+#i'),
      $repl2 = array(
		'<a href="\0">\0</a>',
		'<a href="mailto:\0">\0</a>'),
      $smiley = array(
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
		":?" => "icon_confused.gif");

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
          foreach($smiley as $k => $v) {
            $temp = str_replace(htmlspecialchars($k), '<img src="./libs/bbcode/smiles/'.$v.'" width="15" height="15" alt="'.htmlspecialchars($k).'" />', $temp);
          }
        }
        $temp = preg_replace('#\[literal](.*)\[/literal]#ieU', 'Formatter::__bbcode_escape("\1")', $temp);
        $temp = preg_replace($rule2, $repl2, preg_replace($rule1, $repl1, $temp));
        $rstr .= nl2br(str_replace("\0", "", $temp));
      } else {
        $rstr .= "<pre>".htmlspecialchars(trim($str[$i], "\r\n"))."</pre>";
      }
    }
    return "<div class=\"format_bbcode\">$rstr</div>";
  }
}

?>
