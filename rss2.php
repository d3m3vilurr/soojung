<?php
include_once("settings.php");

$encoding = strtolower(trim($_GET["encoding"]));
if(!$encoding) $encoding = strtolower(trim($_GET["charset"]));
if($encoding == "cp949" || $encoding == "euc-kr" || $encoding == "euckr") {
  $encoding = "euc-kr";
  function convenc($str) { return iconv("UTF-8", "CP949", $str); }
} else {
  $encoding = "utf-8";
  function convenc($str) { return $str; }
}

header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"$encoding\"?>\n";
?>
<rss version="2.0">
<channel>
<title><?=convenc($blog_name)?></title>
<link><?=$blog_baseurl?></link>
<description><?=convenc($blog_desc)?></description>
<copyright></copyright>
<pubDate><?=date('r')?></pubDate>
<generator>soojung <?=$soojung_version?></generator>

<?
if (isset($_GET['category'])) {
  $category = new Category($_GET['category']);
  $entries = $category->getEntries();
} else {
  $entries = Entry::getEntries(10, 1);
}


foreach ($entries as $e) {
  if ($e->isSetOption("NO_RSS") || $e->isSetOption("SECRET")) {
    continue;
  }

  $post_text = $e->getBody();

  echo "<item>\n";
  echo "<title>" . convenc($e->title) . "</title>\n";
  echo "<link>" . $e->getHref() . "</link>\n";
  echo "<pubDate>" . date('r', $e->date) . "</pubDate>\n";
  echo "<category>" . convenc($e->category->name) . "</category>\n";
  echo "<description>" . convenc(htmlspecialchars(nl2br($post_text))) . "</description>\n";
  echo "</item>\n";
}
?>

</channel>
</rss>

