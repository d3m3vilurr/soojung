<?php
include_once("soojung.php");

if (isset($_POST["blogid"])) {
  $blogid = $_POST["blogid"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $url = $_POST["url"];
  $body = $_POST["body"];
  
  if (empty($blogid) || empty($name) || empty($email) || empty($url) || empty($body)) {
    echo "input name, email, url, comment";
    exit();
  }
  if ($url == "http://") {
    $url = "";
  }
  $t = time();
  comment_write($blogid, $name, $email, $url, $body, $t);

  // Remembering 30 days
  setcookie('w_id',    $blogid, time()+2592000);
  setcookie('w_name',  $name,   time()+2592000);
  setcookie('w_email', $email,  time()+2592000);
  setcookie('w_url',   $url,    time()+2592000);

  $entry = get_entry($blogid);
  header("Location: " . $entry['link'] . "#" . $t);
  exit;
} else if (isset($_GET["blogid"]) == false) {
  echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
  exit;
} else {
  $blogid = $_GET["blogid"];
  $entry = get_entry($blogid);
}
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

foreach (array('w_id','w_name','w_email','w_url') as $key) {
  if (isset($HTTP_COOKIE_VARS[$key])) {
    $smarty->assign("$key", $HTTP_COOKIE_VARS[$key]);
  }
}

$smarty->display('entry.tpl');
?>
