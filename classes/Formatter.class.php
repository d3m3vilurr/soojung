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

  function bbcodeToHtml($str) {
    static $rule1 = array(
      '#\[i](.+)\[/i]#iU',
      '#\[b](.+)\[/b]#iU',
      '#\[u](.+)\[/u]#iU',
      '#\[color=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/color]#i',
      '#\[size=((?:&quot;)?)([\d.]+)\1](.+?)\[/size]#i',
      '#\[quote](.+)\[/quote]#isU',
      '#\[quote=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/quote]#is',
      '#\[list(?:=([Aa1Ii]))?](.+)\[/list]#iseU',
      '#\[url](.+)\[/url]#ieU',
      '#\[url=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/url]#ie',
      '#\[img](.+)\[/img]#ieU',
      '#\[img=((?:&quot;)?)([^\[\]]+)\1](.+?)\[/img]#ie',
      '#\[email](.+)\[/email]#ieU'
    ), $repl1 = array(
      '<i>\1</i>',
      '<b>\1</b>',
      '<span style="text-decoration:underline;">\1</span>',
      '<span style="color:\2;">\3</span>',
      '<span style="font-size:\2pt;">\3</span>',
      '<blockquote>\1</blockquote>',
      '<blockquote><p class="quotetitle">\2</p>\3</blockquote>',
      '__bbcode_listing("\1","\2")',
      '__bbcode_escape(\'<a href="\1">\1</a>\')',
      '__bbcode_escape(\'<a href="\2">\3</a>\')',
      '__bbcode_escape(\'<img src="\1" width="" height="" alt="\1\" />\')',
      '__bbcode_escape(\'<img src="\2" width="" height="" alt="\3\" />\')',
      '__bbcode_escape(\'<a href="mailto:\1">\1</a>\')'
    ), $rule2 = array(
      '#http://(?:[-0-9a-z_.@:~\\#%=+?/]|&amp;)+#i',
      '#[-0-9a-z_.]+@[-0-9a-z_.]+#i'
    ), $repl2 = array(
      '<a href="\0">\0</a>',
      '<a href="mailto:\0">\0</a>'
    );
 
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
      return str_replace("@", "\0@", str_replace("://", "\0://", $str));
    }
 
    $str = explode("\0", preg_replace('#\[code](.*?)\[/code](?:\r\n|\r|\n)?#is', "\0\\1\0", $str));
    $rstr = "";
    for($i=0;$i<count($str);$i++) {
      if($i % 2 == 0) {
        $temp = htmlspecialchars($str[$i]);
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
