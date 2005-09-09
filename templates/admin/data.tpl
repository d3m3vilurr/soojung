{include file="header.tpl"}
{include file="menu.tpl"}
<div id="data">

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
<option value="0.4">0.4</option>
<option value="0.3.2">0.3.2</option>
<!--<option value="0.2">0.2.*</option>-->
</select>
<br />
<input type="hidden" name="mode" value="import" />
<input type="submit" value="import!" />
</form>

<h3>import from other blogs</h3>
<p>select your blog:
<select onchange="return switchDiv(this);">
<option selected="selected"></option>
<option value="import_wp">Wordpress</option>
<option value="import_tt">Tattertools</option>
<option value="import_b2">B2</option>
<option value="import_zb">Zeroboard (or ZOG)</option>
<option value="import_moniwiki">Moniwiki</option>
</select>
</p>

<form action="{$baseurl}/admin.php" method="post" id="import_wp" class="hide">
<h4>Wordpress</h4>
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" /><br />
Encoding: <input type="text" name="encoding" value="utf-8" /><br />
<input type="hidden" name="mode" value="import_wp" />
<input type="submit" value="import!" />
</form>

<form action="{$baseurl}/admin.php" method="post" id="import_tt" class="hide">
<h4>Tattertools</h4>
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" value="t3_tts" /><br />
Encoding: <input type="text" name="encoding" value="cp949" /><br />
<input type="hidden" name="mode" value="import_tt" />
<input type="submit" value="import!" />
</form>

<form action="{$baseurl}/admin.php" method="post" id="import_b2" class="hide">
<h4>B2</h4>
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" value="b2" /><br />
Encoding: <input type="text" name="encoding" value="utf-8" /><br />
<input type="hidden" name="mode" value="import_b2" />
<input type="submit" value="import!" />
</form>

<form action="{$baseurl}/admin.php" method="post" id="import_zb" class="hide">
<h4>Zeroboard (or ZOG)</h4>
DB server: <input type="text" name="db_server" /><br />
DB username: <input type="text" name="db_user" /><br />
DB password: <input type="password" name="db_pass" /><br />
DB name: <input type="text" name="db_name" /><br />
Table prefix: <input type="text" name="prefix" value="zetyx_board_" /><br />
Encoding: <input type="text" name="encoding" value="cp949" /><br />
Board ID: <input type="text" name="boardid" /><br />
<input type="hidden" name="mode" value="import_zb" />
<input type="submit" value="import!" />
</form>

</div>

<form action="{$baseurl}/admin.php" method="post" id="import_moniwiki" class="hide">
<h4>Moniwiki</h4>
Moniwiki Path: <input type="text" name="wiki_path" /><br />
Encoding: <input type="text" name="encoding" /><br />
<input type="hidden" name="mode" value="import_moniwiki" />
<input type="submit" value="import!" />
</form>

{include file="footer.tpl"}
