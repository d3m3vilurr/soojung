<?php
include_once("soojung.php");
include("Template.class.php");

if (isset($_POST["blogid"])) {
  $blogid = $_POST["blogid"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $url = $_POST["url"];
  $body = $_POST["body"];
  
  if (empty($blogid) || empty($name) || empty($body)) {
    echo "input name and comment";
    exit();
  }
  if ($url == "http://") {
    $url = "";
  }
  $t = time();
  comment_write($blogid, $name, $email, $url, nl2br($body), $t);

  // Remembering 30 days
  setcookie('w_id',    $blogid, $t+2592000);
  setcookie('w_name',  $name,   $t+2592000);
  setcookie('w_email', $email,  $t+2592000);
  setcookie('w_url',   $url,    $t+2592000);

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
$template = new Template;

$template->assign('entry', $entry);
$template->assign('trackbacks', get_trackbacks($entry['id']));
$template->assign('comments', get_comments($entry['id']));

foreach (array('w_id','w_name','w_email','w_url') as $key) {
  if (isset($HTTP_COOKIE_VARS[$key])) {
    $template->assign("$key", $HTTP_COOKIE_VARS[$key]);
  }
}

$template->display('entry.tpl');
?>
