<?php

include_once("soojung.php");

if ($_POST["mode"] == "post") {
  $blogid = $_POST['id'];
  $body = $_POST["body"];

  if (empty($blogid) || empty($body) || empty($trackback_url) ){
    echo "input body, trackback_url";
    exit;
  } else if (!isset($_POST["id"])) {
    echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
    exit;
  }
  send_trackbackping($blogid, $trackback_url);
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  exit;
} else if ($_GET["file"]) {
  $entry = entry_open($_GET["file"]);
  $mode = "edit";
  $title = $entry["title"];
  $body = $entry["body"];
  $date = $entry["date"];
  $category = $entry["category"];
  $id = $entry["id"];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>post</title>
<link rel="stylesheet" type="text/css" href="styles.css"/>
</head>
<body>

<form action="sendping.php" method="post">
	Trackback URL: 
	<input type="text" name="trackback_url" value="http://"><br>
	<!--
	Body: <br>
	<textarea name="body" rows="20" cols="80"><?=br2nl($body)?></textarea>
	-->
	<br>
<?php
	if(isset($id)) {
	  echo '<input type="hidden" name="id" value="' . $id . '">';
	}
?>	
	<input type="hidden" name="mode" value="post">
	<input type="submit" value="Ping">
</form>
</body>
</html>
