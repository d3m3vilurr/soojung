<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/admin/styles.css"/>
<script type="text/javascript" src="http://localhost:8888/soojung/templates/admin/javascript.js"></script>
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
<tr><th>File name</th><th>Delete</th></tr>
{foreach from=$files item=file}
<tr>
<td>
<a href="javascript:appendLink('{$baseurl}/{$file}');">{$file}</a>
</td>
<td>
<a href="{$baseurl}/upload.php?mode=delete&file={$file}">delete</a>
</td>
</tr>
{/foreach}
</table>

</body>
</html>