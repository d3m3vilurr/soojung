<?php

include_once("soojung.php");

if ($_POST["mode"] == "post") {
  $blogid = $_POST['blogid'];
  $trackback_url = $_POST['trackback_url'];

  if (empty($blogid) || empty($trackback_url) ){
    echo "input body, trackback_url";
    exit;
  } 
  $result = send_trackbackping($blogid, $trackback_url);
  
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
$smarty = new Smarty;
$smarty->template_dir = "templates/" . $blog_skin . "/";
$smarty->compile_dir = "templates/.compile/";
$smarty->config_dir = "templates/.configs/";
$smarty->cache_dir = "templates/.cache/";

$smarty->assign('title', $blog_name);
$smarty->assign('baseurl', $blog_baseurl);

$smarty->assign('entry', $entry);
$smarty->assign('trackbacks', get_trackbacks($entry['id']));
$smarty->assign('comments', get_comments($entry['id']));

$smarty->assign('categories', get_category_list());
$smarty->assign('archvies', get_archive_list());
$smarty->assign('recent_entries', get_recent_entries());
$smarty->assign('recent_comments', get_recent_comments());
$smarty->assign('recent_trackbacks', get_recent_trackbacks());

get_count();

$smarty->assign('today_count', $today_count);
$smarty->assign('total_count', $total_count);

$smarty->display('sendping.tpl');
?>

