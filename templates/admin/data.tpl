{include file="header.tpl"}
{include file="menu.tpl"}

<h2>Export</h2>
<h3>export to soojung data file</h3>
<a href="{$baseurl}/admin.php?mode=export">export</a>

<h2>Import</h2>
<h3>import from soojung data file</h3>
<form enctype="multipart/form-data" action="{$baseurl}/admin.php" method="post">
File: <input type="file" name="file" />
<br />
Version: 
<select name="version">
<!--<option value="0.2">0.2.*</option>-->
<option value="0.3.2">0.3.2</option>
</select>
<br />
<input type="hidden" name="mode" value="import" />
<input type="submit" value="import!" />
</form>
<br />

<h3>import from wordpress</h3>
<form action="{$baseurl}/admin.php" method="post">
Input wordpress database info<br />
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" /><br />
Encoding: <input type="text" name="encoding" value="utf-8" /><br />
<input type="hidden" name="mode" value="import_wp" />
<input type="submit" value="import!" />
</form>

<h3>import from tattertools</h3>
<form action="{$baseurl}/admin.php" method="post">
Input tettertools database info<br />
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" value="t3_tts" /><br />
Encoding: <input type="text" name="encoding" value="cp949" /><br />
<input type="hidden" name="mode" value="import_tt" />
<input type="submit" value="import!" />
</form>

<h3>import from B2</h3>
<form action="{$baseurl}/admin.php" method="post">
Input B2 database info<br />
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" value="b2" /><br />
Encoding: <input type="text" name="encoding" value="utf-8" /><br />
<input type="hidden" name="mode" value="import_b2" />
<input type="submit" value="import!" />
</form>

{include file="footer.tpl"}
