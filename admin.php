<?php
session_start();

include_once("soojung.php");

if ($_POST["mode"] == "config_update" && isset($_SESSION["auth"])) {
  if (empty($_POST["blogname"]) || empty($_POST["desc"]) ||
      empty($_POST["url"]) || empty($_POST["adminname"]) ||
      empty($_POST["email"]) || empty($_POST["perpage"]) ||
      empty($_POST["skin"])) {
    echo "input";
    exit();
  }
  write_config_file($_POST["blogname"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
		    $_POST["fancyurl"], $_POST["adminname"], $_POST["email"],
		    FALSE, $_POST["skin"]);
  echo "<meta http-equiv='refresh' content='0;URL=index.php?compile=t'>";
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="styles.css"/>
<title>admin</title>
</head>
<body>

<?php
if ($_POST["mode"] == "login") {
  if (md5($_POST["password"]) == $admin_password) {
    $_SESSION['auth'] = TRUE;
  }
}

if (!isset($_SESSION["auth"])) {
  echo "<form action=\"admin.php\" method=\"post\">";
  echo "password: <input type=\"password\" name=\"password\">";
  echo "<input type=\"hidden\" name=\"mode\" value=\"login\">";
  echo "<input type=\"submit\" value=\"login\">";
  echo "</form>";
  echo "</body></html>";
  exit();
}

if ($_GET["mode"] == "delete" && isset($_GET["file"])) {
  if (strstr($_GET["file"], "..") != FALSE || strstr($_GET["file"], "contents/") == FALSE) {
    echo "what the fuck?";
  } else {
    unlink($_GET["file"]);
  }
}
?>

<a href="index.php">home</a>
<a href="post.php">post</a>

<?php
if ($_GET["mode"] == "config") {
  echo "<a href=\"admin.php\">admin</a><br />";
  echo "<form action=\"admin.php\" method=\"post\">";
  echo "Blog Name: <input type=\"text\" name=\"blogname\" value=\"$blog_name\"><br />";
  echo "Blog Description: <input type=\"text\" name=\"desc\" value=\"$blog_desc\"><br />";
  echo "Blog URL: <input type=\"text\" name=\"url\" value=\"$blog_baseurl\"><br />";
  echo "Blog entries per page: <input type=\"text\" name=\"perpage\" value=\"$blog_entries_per_page\"><br />";
  echo "Blog Fancy URL: <input type=\"checkbox\" name=\"fancyurl\"";
  if ($blog_fancyurl) {
    echo " checked=\"on\"><br />";
  } else {
    echo "><br />";
  }
  echo "Blog Skin: <input type=\"text\" name=\"skin\" value=\"$blog_skin\"><br />";
  echo "Admin Name: <input type=\"text\" name=\"adminname\" value=\"$admin_name\"><br />";
  echo "Admin Email: <input type=\"text\" name=\"email\" value=\"$admin_email\"><br />";
  echo "<input type=\"hidden\" name=\"mode\" value=\"config_update\">";
  echo "<input type=\"submit\" value=\"update\">";
  echo "</form>";
} else {
  echo "<a href=\"admin.php?mode=config\">config</a><br />";
  $entries = get_entries(get_entry_count(), 1);
  foreach($entries as $e) {
    echo "<a href=\"post.php?file=" . blogid_to_filename($e['id']) . "\">edit</a> ";
    echo "<a href=\"admin.php?mode=delete&file=" . blogid_to_filename($e['id']) . "\">delete</a> ";
    echo $e['title'];
    echo "&nbsp;<a href=sendping.php?id=". $e['id'] .">send trackback ping</a>";
    echo "<br />\n";
    $comments = get_comments($e['id']);
    foreach($comments as $c) {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"admin.php?mode=delete&file=" . $c["filename"] . "\">delete</a> ";
      echo $c['body'];
      echo "<br />\n";
    }
    $trackbacks = get_trackbacks($e['id']);
    foreach($trackbacks as $t) {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"admin.php?mode=delete&file=" . $t["filename"] . "\">delete</a> ";
      echo $t['title'];
      echo "<br />\n";
    }
  }
}
?>

</body>
</html>
