<?
if ($_POST["mode"] == "convert") {

  include("soojung.php");

  $db_server = $_POST["db_server"];
  $db_user = $_POST["db_user"];
  $db_pass = $_POST["db_pass"];
  $db_name = $_POST["db_name"];

  $link = mysql_connect($db_server, $db_user, $db_pass) or die("could not connect");
  mysql_select_db($db_name) or die("could not select database");

  $query = "SELECT title, body, regdate, category1 FROM t3_tts";
  $result = mysql_query($query) or die("query failed");

  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $c_no = $line['category1'];
    $c_query = "SELECT label FROM t3_tts_ct1 WHERE no = " . $c_no;
    $c_result = mysql_query($c_query);
    $c_line = mysql_fetch_array($c_result);

    $title = iconv("cp949", "UTF-8", $line['title']);
    $body = iconv("cp949", "UTF-8", $line['body']);
    $date = $line['regdate'];
    $category = iconv("cp949", "UTF-8", $c_line['label']);

    entry_new($title, $body, $date, $category);
    mysql_free_result($c_result);
  }

  mysql_free_result($result);
  mysql_close($link);
  echo "done";
  exit();
}
?>

<html>
<head>
</head>
<body>
<form action="tt_convert.php" method="post">
Input tettertools database info <br />
DB server: <input type="text" name="db_server"> <br />
DB username: <input type="text" name="db_user"> <br />
DB password: <input type="text" name="db_pass"> <br />
DB name: <input type="text" name="db_name"> <br />
<input type="hidden" name="mode" value="convert">
<input type="submit" value="convert!">
</form>
</body>
</html>