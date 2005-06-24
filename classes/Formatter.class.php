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
	'#\[s](.+)\[/s]#iU',
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
	'<span style="text-decoration:line-through;">\1</span>',
        '<ins>\1</ins>',
        '<del>\1</del>',
        '<span style="color:\2;">\3</span>',
        '<span style="font-size:\2pt;">\3</span>',
        '<blockquote><div>\1</div></blockquote>',
        '<blockquote><p class="quotetitle">\2</p><div>\3</div></blockquote>',
        'BBcodeFormatter::__listing("\1","\2")',
        'BBcodeFormatter::__escape("<a href=\\"\1\\">\1</a>")',
        'BBcodeFormatter::__escape("<a href=\\"\2\\">\3</a>")',
        'BBcodeFormatter::__escape("<img src=\\"\1\\" alt=\\"\1\\\" />")',
        'BBcodeFormatter::__escape("<img src=\\"\2\\" alt=\\"\3\\\" />")',
        'BBcodeFormatter::__escape("<a href=\\"mailto:\1\\">\1</a>")'),
      $rule2 = array(
        '#(?<![\/~"\'])http://(?:[-0-9a-z_.@:~\\#%=+?/]|&amp;)+(?!(?:</a>|"|\'>))#i',
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
        ":P" => "icon_razz.gif",
        ":cry:" => "icon_cry.gif",
        ":evil:" => "icon_evil.gif",
        ":twisted:" => "icon_twisted.gif",
        ":roll:" => "icon_rolleyes.gif",
        ";)" => "icon_wink.gif",
        ":wink:" => "icon_wink.gif",
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
    global $blog_baseurl;
    if(is_null($smiley)) {
      $smiley = array();
      foreach($_smiley as $k => $v) {
        $smiley[htmlspecialchars($k)] = '<img src="'.$blog_baseurl.'/libs/bbcode/smiles/'.$v.'" width="15" height="15" alt="'.htmlspecialchars($k).'" />';
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

class MoniwikiFormatter extends Formatter {
  function toHtml($str) {
    $str=rtrim($str); # delete last empty line
    $lines=explode("\n",$str);

    # have no contents
    if (!$lines) return;

    $text='';
    $pre_line='';
    $in_p='';
    $in_div=0;
    $in_li=0;
    $in_pre=0;
    $in_table=0;
    $li_open=0;
    $li_empty=0;
    $indent_list[0]=0;
    $indent_type[0]="";

	$punct="<\'}\]\|;\.\!"; # , is omitted for the WikiPedia
    $url="wiki|http|https|ftp|nntp|news|irc|telnet|mailto|file|attachment";
    $urlrule="((?:$url):([^\s$punct]|(\.?[^\s$punct]))+)";
    $baserule=array("/<([^\s<>])/","/`([^`' ]+)'/",
					"/(?<!`)`([^`]*)`/",
                    "/'''([^']*)'''/","/(?<!')'''(.*)'''(?!')/",
                    "/''([^']*)''/","/(?<!')''(.*)''(?!')/",
                    "/\^([^ \^]+)\^(?=\s|$)/","/\^\^([^\^]+)\^\^(?!^)/",
                    "/(?<!,),,([^ ,]+),,(?!,)/",
                    "/(?<!_)__([^_]+)__(?!_)/",
					//"/^(-{4,})/e",
                    "/(?<!-)--[^\s]([^-]+)[^\s]--(?!-)/",
				    "/\[\[BR\]\]/",
				    "/\[\[HTML\(&lt;(.*)\)\]\]/",
                    );
    $baserepl=array("&lt;\\1","&#96;\\1'",
					"<tt class='wiki'>\\1</tt>",
                    "<b>\\1</b>","<b>\\1</b>",
                    "<i>\\1</i>","<i>\\1</i>",
                    "<sup>\\1</sup>","<sup>\\1</sup>",
                    "<sub>\\1</sub>",
                    "<u>\\1</u>",
                    //"<div class='separator'><hr class='wiki' /></div>",
					//"<u>\\1</u>",
                    "<del>\\1</del>",
				    "<br />",
				    "<\\1",
                    );

    # NoSmoke's MultiLineCell hack
    $extrarule=array("/{{\|/","/\|}}/");
    #$extrarepl=array("</div><table class='closure'><tr class='closure'><td class='closure'><div>","</div></td></tr></table><div>");
    $extrarepl=array("<blockquote><p>","</p></blockquote>");
    $wordrule="({{{([^}]+)}}})|".
              "\[\[([A-Za-z0-9]+(\(((?<!\]\]).)*\))?)\]\]|"; # macro
	$wordrule.="\[\*[^\]]*\s[^\]]+\]|";
    $wordrule.=
    # single bracketed rule [http://blah.blah.com Blah Blah]
    "(\[\^?($url):[^\s\]]+(\s[^\]]+)?\])|".
    # WikiName rule: WikiName ILoveYou (imported from the rule of NoSmoke)
    # protect WikiName rule !WikiName
    "(?<![a-z])\!?(?:((\.{1,2})?\/)?[A-Z]([A-Z]+[0-9a-z]|[0-9a-z]+[A-Z])[0-9a-zA-Z]*)+\b|".
    # single bracketed name [Hello World]
    "(?<!\[)\!?\[([^\[:,<\s'][^\[:,>]{1,255})\](?!\])|".
    # bracketed with double quotes ["Hello World"]
    "(?<!\[)\!?\[\\\"([^\\\"]+)\\\"\](?!\])|".
    # "(?<!\[)\[\\\"([^\[:,]+)\\\"\](?!\])|".
    "($urlrule)|".
    # single linkage rule ?hello ?abacus
    #"(\?[A-Z]*[a-z0-9]+)";
    "(\?[A-Za-z0-9]+)";

    foreach ($lines as $line) {
      # empty line
	  $line = trim($line);
      if (strlen($line) == 0) {
        if ($in_pre) { $pre_line.="\n";continue;}
        if ($in_li) { $text.="<br />\n";$li_empty=1; continue;}
        if ($in_table) {
          $text.="</table>"."<br />\n";$in_table=0; continue;
        } else {
          #if ($in_p) { $text.="</div><br />\n"; $in_p='';}
          //if ($in_p) { $text.=$this->_div(0,&$in_div)."<br />\n"; $in_p='';}
          //else if ($in_p=='') { $text.="<br />\n";}
		  $text.="<br /><br />\n";
          continue;
        }
      }

      $p_close='';
      if (preg_match('/^-{4,}/',$line)) {
        if ($in_p) { $p_close=$this->_div(0,&$in_div); $in_p='';}
      } else if ($in_p == '') {
        $p_close=$this->_div(1,&$in_div);
        $in_p= $line;
      }

      if ($in_pre) {
         if (strpos($line,"}}}")===false) {
           $pre_line.=$line."\n";
           continue;
         } else {
           #$p=strrpos($line,"}}}");
           $p= strlen($line) - strpos(strrev($line),'}}}') - 1;
           if ($p>2 and $line[$p-3]=='\\') {
             $pre_line.=substr($line,0,$p-3).substr($line,$p-2)."\n";
             continue;
           }
           $pre_line.=substr($line,0,$p-2);
           $line=substr($line,$p+1);
           $in_pre=-1;
         }
      } else if (!(strpos($line,"{{{")===false) and 
                 preg_match("/{{{[^{}]*$/",$line)) {
         $p= strlen($line) - strpos(strrev($line),'{{{') - 3;

         $in_pre=1;
         $np=0;

         if ($line[$p+3] == ":") {
            # new formatting rule for a quote block (pre block + wikilinks)
            $line[$p+3]=" ";
            $np=1;
            if ($line[$p+4]=='#' or $line[$p+4]=='.') {
              $pre_style=strtok(substr($line,$p+4),' ');
              $np++;
              if ($pre_style) $np+=strlen($pre_style);
            } else
              $pre_style='';
            $in_quote=1;
         }

         $pre_line=substr($line,$p+$np+3);
         if (trim($pre_line))
           $pre_line.="\n";
         $line=substr($line,0,$p);
      }
      $line=preg_replace($baserule,$baserepl,$line);
      if ($in_pre != -1 && preg_match("/^(\s*)/",$line,$match)) {
         $open="";
         $close="";
         $indtype="dd";
         $indlen=strlen($match[0]);
         if ($indlen > 0) {
           $line=substr($line,$indlen);
           if ($line[0]=='*') {
             $limatch[1]='*';
             $line=preg_replace("/^(\*\s?)/","<li>",$line);
             if ($indent_list[$in_li] == $indlen && $indent_type[$in_li]!='dd') $line="</li>\n".$line;
             $numtype="";
             $indtype="ul";
           } elseif (preg_match("/^((\d+|[aAiI])\.)(#\d+)?\s/",$line,$limatch)){
             $line=preg_replace("/^((\d+|[aAiI])\.(#\d+)?)/","<li>",$line);
             if ($indent_list[$in_li] == $indlen) $line="</li>\n".$line;
             //$numtype=$limatch[2][0];
			 switch($limatch[2][0]) {
				case "A": $numtype = "class=\"list-ualpha\""; break;
			    case "a": $numtype = "class=\"list-lalpha\""; break;
				case "1": $numtype = "class=\"list-decimal\""; break;
				case "I": $numtype = "class=\"list-uroman\""; break;
				case "i": $numtype = "class=\"list-lroman\""; break;
				default: $numtype = "";
			 }
             if ($limatch[3])
               $numtype.=substr($limatch[3],1);
             $indtype="ol";
           } elseif (preg_match("/^([^:]+)::\s/",$line,$limatch)) {
             $line=preg_replace("/^[^:]+::\s/",
                     "<dt class='wiki'>".$limatch[1]."</dt><dd>",$line);
             if ($indent_list[$in_li] == $indlen) $line="</dd>\n".$line;
             $numtype="";
             $indtype="dl";
           }
         }
         if ($indent_list[$in_li] < $indlen) {
            $in_li++;
            $indent_list[$in_li]=$indlen; # add list depth
            $indent_type[$in_li]=$indtype; # add list type
            $open.=$this->_list(1,$indtype,$numtype);
         } else if ($indent_list[$in_li] > $indlen) {
            while($in_li >= 0 && $indent_list[$in_li] > $indlen) {
               if ($indent_type[$in_li]!='dd' && $li_open == $in_li)
                 $close.="</li>\n";
               $close.=$this->_list(0,$indent_type[$in_li],"",$indent_type[$in_li-1]);
               unset($indent_list[$in_li]);
               unset($indent_type[$in_li]);
               $in_li--;
            }
            $li_empty=0;
         }
         if ($indent_list[$in_li] <= $indlen || $limatch) $li_open=$in_li;
         else $li_open=0;
      }
      if (!$in_pre && $line[0]=='|' && !$in_table && preg_match("/^((\|\|)+)(&lt;[^>]+>)?.*\|\|$/",$line,$match)) {
		 $open.=$this->_table(1,$match[3]);
         $line=preg_replace('/^((\|\|)+)(&lt;[^>]+>)?/','\\1',$line);
         $in_table=1;
      } elseif ($in_table && $line[0]!='|' && !preg_match("/^\|\|.*\|\|$/",$line)){
         $close=$this->_table(0).$close;
         $in_table=0;
      }
      if ($in_table) {
         $line=preg_replace('/^((?:\|\|)+(&lt;[^>]+>)?)((\s?)(.*))\|\|$/e',"'<tr class=\"wiki\"><td class=\"wiki\" '.\$this->_table_span('\\1','\\4').'>\\3</td></tr>'",$line);
         $line=preg_replace('/((\|\|)+(&lt;[^>]+>)?)(\s?)/e',"'</td><td class=\"wiki\" '.\$this->_table_span('\\1','\\4').'>\\4'",$line);
         $line=str_replace('\"','"',$line); # revert \\" to \"
      }

      # InterWiki, WikiName, {{{ }}}, !WikiName, ?single, ["extended wiki name"]
      # urls, [single bracket name], [urls text], [[macro]]
      $line=preg_replace("/(".$wordrule.")/e","\$this->link_repl('\\1')",$line);

      # Headings
      $line=preg_replace("/(?<!=)(={1,5})\s+(.*)\s+\\1\s?$/e",
                         "\$this->head_repl('\\1','\\2')",$line);
      $line=preg_replace($extrarule,$extrarepl,$line);
      $line=$close.$p_close.$open.$line;
      $open="";$close="";

      if ($in_pre==-1) {
         $in_pre=0;
         if ($in_quote) {
            # htmlfy '<'
            $pre=str_replace("<","&lt;",$pre_line);
            $pre=preg_replace($baserule,$baserepl,$pre);
            $pre=preg_replace("/(".$wordrule.")/e","\$this->link_repl('\\1')",$pre);
            $attr='class="quote"';
            if ($pre_style) {
              $tag=$pre_style[0];
              $style=substr($pre_style,1);
              switch($tag) {
              case '#':
                $attr="id='$style'";
                break;
              case '.':
                $attr="class='$style'";
                break;
              }
            }
            $line="<pre $attr>\n".$pre."</pre>\n".$line;
            $in_quote=0;
         } else {
            # htmlfy '<'
            $pre=str_replace("<","&lt;",$pre_line);
            $line="<pre class='wiki'>\n".$pre."</pre>\n".$line;
         }
      }
      $text.=$line."\n";
    } # end rendering loop

    # close all tags
    $close="";
    # close pre,table
    if ($in_pre) $close.="</pre>\n";
    if ($in_table) $close.="</table>\n";
    # close indent
    while($in_li >= 0 && $indent_list[$in_li] > 0) {
      if ($indent_type[$in_li]!='dd' && $li_open == $in_li)
        $close.="</li>\n";
      $close.=$this->_list(0,$indent_type[$in_li],"",$indent_type[$in_li-1]);
      unset($indent_list[$in_li]);
      unset($indent_type[$in_li]);
      $in_li--;
    }
    # close div
    #if ($in_p) $close.="</div>\n"; # </para>
    if ($in_p) $close.=$this->_div(0,&$in_div); # </para>

    # activate <del></del> tag
    #$text=preg_replace("/(&lt;)(\/?del>)/i","<\\2",$text);
    $text.=$close;
  
    return $text;
  }

  function _list($on,$list_type,$numtype="",$closetype="") {
    if ($list_type=="dd") {
      if ($on)
         #$list_type="dl><dd";
         $list_type="div class='indent'";
      else
         #$list_type="dd></dl";
         $list_type="div";
      $numtype='';
    } else if ($list_type=="dl") {
      if ($on)
         $list_type="dl";
      else
         $list_type="dd></dl";
      $numtype='';
    } if (!$on and $closetype and $closetype !='dd')
      $list_type=$list_type.'></li';

    if ($on) {
      if ($numtype) {
        //$start=substr($numtype,1);
        if ($start)
          return "<$list_type $numtype start='$start'>";
        return "<$list_type $numtype>";
      }
      return "$close$open<$list_type>\n";
    } else {
      return "</$list_type>\n$close$open";
    }
  }

  function _table($on,$attr='') {
    if ($attr) {
      $attr=substr($attr,4,-1);
    }
    if ($on)
      return "<table class='wiki' cellpadding='3' cellspacing='2' $attr>\n";
    return "</table>\n";
  }

  function _table_span($str,$align='') {
    $tok=strtok($str,'&');
    $len=strlen($tok)/2;
    $extra=strtok('');
    $attr=array();
    if ($extra) {
      $para=substr($extra,3,-1);
      # rowspan
      if (preg_match("/^\|(\d+)$/",$para,$match))
        $attr[]="rowspan='$match[1]'";
      else if ($para[0]=='#')
        $attr[]="bgcolor='$para'";
      else
        $attr[]=$para;
    }
    if ($align) $attr[]="align='center'";
    if ($len > 1)
      $attr[]="colspan='$len'"; #$attr[]="align='center' colspan='$len'";
    return implode(' ',$attr);
  }

  function _div($on,$in_div) {
    $tag=array("</div>\n","<div>\n");
    if ($on) $in_div++;
    else {
      if (!$in_div) return '';
      $in_div--;
    }
    return $purple.$tag[$on];
  }

  function head_repl($depth,$head) {
    $dep=strlen($depth);
    $head=str_replace('\"','"',$head); # revert \\" to \"
	$head_num=1;
	$head_dep=0;

    if (!$depth_top) {
      $depth_top=$dep; $depth=1;
    } else {
      $depth=$dep - $depth_top + 1;
      if ($depth <= 0) $depth=1;
    }

    $num="".$head_num;
    $odepth=$head_dep;

    if ($head[0] == '#') {
      # reset TOC numberings
      if ($toc_prefix) $toc_prefix++;
      else $toc_prefix=1;
      $head[0]=' ';
      $dum=explode(".",$num);
      $i=sizeof($dum);
      for ($j=0;$j<$i;$j++) $dum[$j]=1;
      $dum[$i-1]=0;
      $num=join($dum,".");
    }
    $open="";
    $close="";

    if ($odepth && ($depth > $odepth)) {
      $num.=".1";
    } else if ($odepth) {
      $dum=explode(".",$num);
      $i=sizeof($dum)-1;
      while ($depth < $odepth && $i > 0) {
         unset($dum[$i]);
         $i--;
         $odepth--;
      }
      $dum[$i]++;
      $num=join($dum,".");
    }

    $head_dep=$depth; # save old
    $head_num=$num;

    $prefix=$toc_prefix;

    return "$close$open<h$dep>$head</h$dep>";
  }

  function link_repl($url,$attr='') {
    global $blog_baseurl;

    $url=str_replace('\"','"',$url);
    if ($url[0]=="[") {
      $url=substr($url,1,-1);
      $force=1;
    }
    switch ($url[0]) {
    case '{':
      $url=substr($url,3,-3);
      if ($url[0]=='#' and ($p=strpos($url,' '))) {
	$col=strtok($url,' '); $url=strtok('');
	if (!preg_match('/^#[0-9a-f]{6}$/',$col)) $col=substr($col,1);
	return "<font color='$col'>$url</font>";
      }
      return "<tt class='wiki'>$url</tt>";
      break;
    case '#': # Anchor syntax in the MoinMoin 1.1
      $anchor=strtok($url,' ');
      //return ($word=strtok('')) ? $this->link_to($anchor,$word):
      //           "<a name='".($temp=substr($anchor,1))."' id='$temp'></a>";
	  return ($word=strtok('')) ? $temp=substr($anchor,1):
                 "<a name='".($temp=substr($anchor,1))."' id='$temp'></a>";
      break;
    case '!':
      $url=substr($url,1);
      return $url;
      break;
    default:
      break;
    }

    if (strpos($url,":")) {
      if ($url[0]=='a') # attachment:
        //return $this->macro_repl('Attachment',substr($url,11));
        if (preg_match("/.*\.(png|gif|jpeg|jpg)$/i",$url)) {
	  $url = preg_replace("/attachment([a-zA-Z0-9:\/]*)\:/", "", $url);
	  return "<img src='$blog_baseurl"."/contents/upload/"."$url' alt='$url' />";
	} else {
	  $url = preg_replace("/attachment([a-zA-Z0-9:\/]*)\:/", "", $url);
	  return "<a href='$blog_baseurl"."/contents/upload/"."$url'>$url</a>";
	}

      if ($url[0] == '^') {
        $attr.=' target="_blank" ';
        $url=substr($url,1);
      }

      if (preg_match("/^mailto:/",$url)) {
        $url=str_replace("@","_at_",$url);
        $link=str_replace('&','&amp;',$url);
        $name=substr($url,7);
        return "<a href='$link'>$name</a>";
      }

      if ($force or strpos($url," ")) { # have a space ?
        list($url,$text)=explode(" ",$url,2);
        $link=str_replace('&','&amp;',$url);
        if (!$text) $text=$url;
        else {
          if (preg_match("/^(http|ftp).*\.(png|gif|jpeg|jpg)$/i",$text)) {
            $text=str_replace('&','&amp;',$text);
            return "<a href='$link' $attr title='$url'><img alt='$url' src='$text' /></a>";
          }
        }
        $icon=strtok($url,':');
        return "<a class='externalLink' $attr href='$link'>$text</a>";
      } # have no space
      $link=str_replace('&','&amp;',$url);
      if (preg_match("/^(http|https|ftp)/",$url)) {
        if (preg_match("/(^.*\.(png|gif|jpeg|jpg))(([\?&]([a-z]+=[0-9a-z]+))*)$/i",$url,$match)) {
          $url=$match[1];
          $attrs=explode('&',substr($match[3],1));
          foreach ($attrs as $arg) {
            $name=strtok($arg,'=');
            $val=strtok(' ');
            if ($name and $val) $attr.=$name.'="'.$val.'" ';
            if ($name == 'align') $attr.='class="img'.ucfirst($val).'" ';
          }
          return "<img alt='$link' $attr src='$url' />";
        }
      }
      return "<a class='externalLink' $attr href='$link'>$url</a>";
    } else {
      if ($url[0]=="?") $url=substr($url,1);
      //return $this->word_repl($url,'',$attr);
	  return $url;
    }
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
