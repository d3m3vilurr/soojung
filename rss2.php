<?php
include_once("settings.php");

header("Content-type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title><?=$blog_name?></title>
<link><?=$blog_baseurl?></link>
<description><?=$blog_desc?></description>
<copyright></copyright>
<pubDate></pubDate>
<generator>soojung <?=$soojung_version?></generator>

<?
if (isset($_GET['category'])) {
  $category = new Category($_GET['category']);
  $entries = $category->getEntries();
} else {
  $entries = Entry::getEntries(10, 1);
}


foreach ($entries as $e) {
  if ($e->isSetOption("NO_RSS")) {
    continue;
  }

  $post_text = preg_replace("/(([\x80-\xFE].)*)[\x80-\xFE]?$/","\\1",str_replace("\n", "\n", $e->getBody()));

  echo "<item>\n";
  echo "<title>" . $e->title . "</title>\n";
  echo "<link>" . $e->getHref() . "</link>\n";
  echo "<comments></comments>\n";
  echo "<pubDate>" . date('r', $e->date) . "</pubDate>\n";
  echo "<category>" . $e->category->name . "</category>\n";
  echo "<guid>" . $e->getHref() . "</guid>\n";
  echo "<description></description>\n";
  echo "<content:encoded><![CDATA[" . $post_text . "]]></content:encoded>\n";
  echo "</item>\n";
}
?>

</channel>
</rss>

