<?php
include("Template.class.php");

if ($_POST["mode"] == "post") {
  $blogid = $_POST['blogid'];
  $trackback_url = $_POST['trackback_url'];
  $encoding = $_POST['encoding'];
  if (empty($blogid) || empty($trackback_url) ){
    echo "input body, trackback_url";
    exit;
  } 
  echo "encoding : $encoding<br>\n";
  $result = send_trackbackping($blogid, $trackback_url, $encoding);
  
/* show result of trackback ping */
  if ($result['error'] == 0) {
    echo "<html><head>\n";
    echo "<meta http-equiv='refresh' content='3;URL=admin.php'>";
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
    
$entry = get_entry($blogid);
$title = $entry["title"];
$body = $entry["body"];
$date = $entry["date"];
$category = $entry["category"];
?>


<?php
$smarty = new Template;

$smarty->assign('entry', $entry);
$smarty->assign('trackbacks', get_trackbacks($entry['id']));
$smarty->assign('comments', get_comments($entry['id']));

$smarty->display('sendping.tpl');
?>

