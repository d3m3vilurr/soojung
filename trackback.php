<?php

include_once("settings.php");

header("Content-type: text/xml");

if ($_GET["__mode"] == "rss" && isset($_GET["blogid"])) {
  $blogid = $_GET["blogid"];
  $entry = Entry::getEntry($blogid);

  $excerpt = $entry->getBody();
  if (strlen ($excerpt) > 255)
    $excerpt = substr($excerpt,0, 252) . "...";
  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  echo "<response>\n";
  echo "<error>0</error>\n";
  echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">'."\n";
  echo "<channel>\n";
  echo "<title>".$blog_name."</title>\n";
  echo "<link>".$blog_baseurl."/trackback.php?blogid=".$_GET["blogid"]."</link>\n";
  echo "<description>".$blog_desc."</description>\n";
  echo "<item>\n";
  echo "<title>".$entry->title."</title>\n";
  echo "<link>".$entry->getHref()."</link>\n";
  echo "<description>".$excerpt."</description>\n";
  echo "</item>\n";
  echo "</channel>\n";
  echo "</rss></response>\n";
  exit;
}

if (isset($_REQUEST["url"]) || isset($_REQUEST["blogid"])) {
  $id = $_REQUEST["blogid"];
  $url = $_REQUEST["url"];
  $title = $_REQUEST["title"];
  $excerpt = $_REQUEST["excerpt"];
  $name = $_REQUEST["blog_name"];


#  $encoding_title = detect_encoding ($title);
#  $encoding_excerpt = detect_encoding ($excerpt);
#  $encoding_name = detect_encoding ($name)
#  fwrite($fd, $encoding . "\n");
#  if ($encoding != "UTF-8") {
#    $title = iconv($encoding, "UTF-8", $_POST["title"]);
#    $excerpt = iconv($encoding, "UTF-8", $_POST["excerpt"]);
#    $name = iconv($encoding, "UTF-8", $_POST["blog_name"]);
#  }

  $title = convert_to_utf8($title);
  $excerpt = convert_to_utf8($excerpt);
  $name = convert_to_utf8($name);
  
  if (empty($url)) {
    echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
    echo "<response>\n";
    echo "<error>1</error>\n";
    echo "<message>url is required</message>\n";
    echo "</response>\n";
  } else {
    Trackback::writeTrackback($id, $url, $name, $title, $excerpt);
    $temp = new Usertemplate("index.tpl", 1);
    $temp->clearCache();
    echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
    echo "<response>\n";
    echo "<error>0</error>\n";
    echo "</response>\n";
  }
} else {
  echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
  echo "<response>\n";
  echo "<error>1</error>\n";
  echo "<message>blogid is required. trackback.php?blogid=N form.</message>\n";
  echo "</response>\n";
}

# vim: ts=8 sw=2 sts=2 noet
?>
