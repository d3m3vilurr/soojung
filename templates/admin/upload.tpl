<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/admin/styles.css"/>
<script type="text/javascript" src="{$baseurl}/templates/admin/javascript.js"></script>
<title>Admin Upload</title>
</head>
<body>

<form enctype="multipart/form-data" action="{$baseurl}/upload.php" method="post">
Upload: <input type="file" name="file">
<input type="hidden" name="mode" value="upload">
<input type="submit" value="Upload">
</form>

<!-- file list -->
<table>
<tr><th>File name</th><th>Action</th></tr>
{foreach from=$files item=file}
<tr>
<td>
<a href="{$file.path|escape}" onclick="window.open('{$baseurl}/{$file.path|escape:'quotes'|escape}');return false;">{$file.name|escape}</a>
</td>
<td>
<strong>
<a href="#" onclick="return appendLink('{$baseurl}/{$file.path|escape:'quotes'|escape}');">append</a>
<a href="{$baseurl}/upload.php?mode=delete&file={$file.path|escape:'url'|escape}">delete</a>
</strong>
</td>
</tr>
{/foreach}
</table>

</body>
</html>
