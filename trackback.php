<?
include_once("soojung.php");

if (isset($_POST["url"]) || isset($_GET["blogid"])) {
  $id = $_GET["blogid"];
  $url = $_POST["url"];
  $title = $_POST["title"];
  $excerpt = $_POST["excerpt"];
  $name = $_POST["blog_name"];

  if (empty($url)) {
    echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
    echo "<response>\n";
    echo "<error>1</error>\n";
    echo "<message>url is required</message>\n";
    echo "</response>\n";
  } else {
    trackback_write($id, $url, $name, $title, $excerpt);
    echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
    echo "<response>\n";
    echo "<error>0</error>\n";
    echo "</response>\n";
  }
}
?>