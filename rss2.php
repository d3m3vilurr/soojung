<?php
include_once("soojung.php");
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title><?=$blog_name?></title>
<link><?=$blog_baseurl?></link>
<description><?=$blog_desc?></description>
<copyright></copyright>
<pubDate></pubDate>
<generator>soojung</generator>

<?
if (isset($_GET['category'])) {
  $entries = get_category_entries($_GET['category']);
} else {
  $entries = get_entries(10, 1);
}


foreach ($entries as $e) {
  echo "<item>\n";
  echo "<title>" . $e["title"] . "</title>\n";
  echo "<link>" . $e["link"] . "</link>\n";
  echo "<comments></comments>\n";
  echo "<pubDate>" . date('r', $e['date']) . "</pubDate>\n";
  echo "<category>" . $e['category'] . "</category>\n";
  echo "<guid></guid>\n";
  echo "<description></description>\n";
  echo "<content:encoded><![CDATA[" . $e['body'] . "]]></content:encoded>\n";
  echo "</item>\n";
}
?>

</channel>
</rss>

