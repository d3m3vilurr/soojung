<html>
<head></head>
<body>

<form enctype="multipart/form-data" action="{$baseurl}/upload.php" method="post">
Upload: <input type="file" name="file">
<input type="hidden" name="mode" value="upload">
<input type="submit" value="Upload">
</form>

<!-- file list -->
{foreach from=$files item=file}
	{$baseurl}/{$file}
	<a href="{$baseurl}/upload.php?mode=delete&file={$file}">delete</a><br />
{/foreach}

</body>
</html>