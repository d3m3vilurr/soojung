<?php
include_once("soojung.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="styles.css" />
<title>install</title>
</head>
<body>

<?php
if (!is_writable(".")) {
  echo "<font color=\"red\">WARNING: This directory is not writeable</font><br />";
}
if (!is_writable("templates")) {
  echo "<font color=\"red\">WARNING: Templates directory is not writeable</font><br />";
}
?>

<?php
if (isset($_POST["name"])) {
  if (empty($_POST["name"]) || empty($_POST["desc"])
      || empty($_POST["url"]) || empty($_POST["password"])
      || empty($_POST["admin"]) || empty($_POST["email"])
      || empty($_POST["perpage"])) {
    echo "input name, desc, url, email, password";
    die;
  }

  mkdir("contents", 0777);

  mkdir("templates/.cache", 0777);
  mkdir("templates/.configs", 0777);
  mkdir("templates/.compile", 0777);

  mkdir("templates/.admin_cache", 0777);
  mkdir("templates/.admin_configs", 0777);
  mkdir("templates/.admin_compile", 0777);

  $f = fopen("contents/.info","w");
  fwrite($f, "1");
  fclose($f);

  write_config_file($_POST["name"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
		    $_POST["fancyurl"], $_POST["admin"], $_POST["email"],
		    md5($_POST["password"]));

  $f = fopen(".htaccess", "w");
  fwrite($f, "RewriteEngine On\n");
  fwrite($f, "RewriteRule ^(.+)/([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)[.]html$ ");
  fwrite($f, $_POST["url"] . "/entry.php?blogid=$5\n");
  fwrite($f, "RewriteRule ^([0-9]+)/([0-9]+) ");
  fwrite($f, $_POST["url"] . "/index.php?archive=$1$2\n");
  fwrite($f, "RewriteRule ^([^/.]+)$ ");
  fwrite($f, $_POST["url"] . "/index.php?category=$1\n");
  fwrite($f, "RewriteRule ^page/([0-9]+)$ ");
  fwrite($f, $_POST["url"] . "/index.php?page=$1\n");
  fwrite($f, $data);
  fclose($f);

  echo "install success. delete install.php file";
  exit();
}
?>

<form action="install.php" method="post">
<table>

<tr>
<td>Blog Name:</td>
<td><input type="text" name="name" size="30" value="<?=$_POST['name']?>"></td>
</tr>

<tr>
<td>Blog Description:</td>
<td><input type="text" name="desc" size="50" value="<?=$_POST['desc']?>"></td>
</tr>

<tr>
<td>Blog URL:</td>
<td><input type="text" name="url" size="50" value="<?=($_POST['url'] ? $_POST['url'] : 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']))?>"></td>
</tr>

<tr>
<td>entries per page</td>
<td><input type="text" name="perpage" size="2" value="<?=($_POST['perpage'] ? $_POST['perpage'] : 5)?>"></td>
</tr>

<tr>
<td>fancy url:</td>
<td><input type="checkbox" name="fancyurl"></td>
</tr>

<tr>
<td>Admin name:</td>
<td><input type="text" name="admin" value="<?=$_POST['admin']?>"></td>
</tr>

<tr>
<td>Admin email:</td>
<td><input type="text" name="email" value="<?=$_POST['email']?>"></td>
</tr>

<tr>
<td>Admin password:</td>
<td><input type="password" name="password" value="<?=$_POST['password']?>"></td>
</tr>


</table>
<input type="submit" value="Install">
</form>

</body>
</html>
