<?php

include_once("soojung.php");

if (isset($_SESSION["auth"])) {
  echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
}

if ($_POST["mode"] == "post") {
  $title =  $_POST["title"];
  $body = $_POST["body"];
  $date = strtotime($_POST["date"]);
  $category = $_POST["category"];

  if (empty($title) || empty($body) || empty($date) || empty($category)) {
    echo "input title, body, date, category";
  } else {
    if (isset($_POST["id"])) {
      entry_edit($_POST["id"], $title, $body, $date, $category);
    } else {
      entry_new($title, $body, $date, $category);
    }
    echo "<meta http-equiv='refresh' content='0;URL=admin.php'>";
  }
} else if ($_GET["blogid"]) {
  $entry = get_entry($_GET["blogid"]);
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

<form action="post.php" method="post">
	Title: <br>
	<input type="text" name="title" value="<?=$title?>">
	<br>
	Body: <br>
	<textarea name="body" rows="20" cols="80"><?=br2nl($body)?></textarea>
	<br>
	Date: <br>
<?php
if ($mode != "edit") {
?>
	<input type="text" name="date" value="<?=date('Y-m-d H:i:s',time())?>">
<?php
} else {
?>
	<input type="text" name="date" value="<?=date('Y-m-d H:i:s',$date)?>">
<?php
	   }
?>
	<br>
	Category: <br>
	<input type="text" name="category" value="<?=$category?>">
	<br>
<?php
	if(isset($id)) {
	  echo '<input type="hidden" name="id" value="' . $id . '">';
	}
?>	
	<input type="hidden" name="mode" value="post">
	<input type="submit" value="Post">
</form>
</body>
</html>
