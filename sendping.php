<?php

include_once("settings.php");

if ($_POST["mode"] == "post") {
  $blogid = $_POST['blogid'];
  $trackback_url = $_POST['trackback_url'];
  $encoding = $_POST['encoding'];
  if (empty($blogid) || empty($trackback_url) ){
    echo "input body, trackback_url";
    exit;
  } 
  echo "encoding : $encoding<br>\n";
  $result = Trackback::sendTrackbackPing($blogid, $trackback_url, $encoding);
  
/* show result of trackback ping */
  if ($result['error'] == 0) {
    echo "<html><head>\n";
    //echo "<meta http-equiv='refresh' content='3;URL=admin.php'>";
    echo "</head><body>\n";
    echo "Trackback sended successfully<br />\n";
    echo "After 3sec, or click <a href='admin.php'>admin page</a> to return admin page <br />\n";
    echo "</body></html>\n";
  } else {
    echo "<html><body>\n";
    echo "Error : ".$result['message']."<br>\n";
    echo "click <a href='admin.php'>admin page</a> to return admin page <br />\n";
    echo "</body></html>\n";
  }
  exit;
}

$blogid = $_GET['blogid'];
if (!isset($blogid)) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
}

$entry = Entry::getEntry($blogid);
$title = $entry->title;
$body = $entry->getBody();
$date = $entry->date;
$category = $entry->category->name;
?>


<?php
$smarty = new UserTemplate("sendping.tpl", $blogid);

$smarty->assign('entry', $entry);
$smarty->assign('trackbacks', $entry->getTrackbacks());
$smarty->assign('comments', $entry->getComments());

$smarty->display('sendping.tpl');

# vim: ts=8 sw=2 sts=2 noet
?>
