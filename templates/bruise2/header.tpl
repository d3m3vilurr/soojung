<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/styles.css" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
<script type="text/javascript">/*<![CDATA[*/{literal}
function fold_sidebar(objid) {
document.getElementById(objid).className =
(document.getElementById(objid).className ? '' : 'div_hide');
return false;
}
{/literal}/*]]>*/</script>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td valign="top">
<table border="0" align="center" cellpadding="0" cellspacing="0" width="640">
<div id="header">
<tr>
	<td width="320" align="left">■ <a href="{$baseurl}" title="{$description}"><span class='blogTitle'><b>{$title}</b></span></a></td>
	<td width="320" align="right">
	| <a href="{$baseurl}">main</a> |
	<a href="{$baseurl}/admin.php">admin</a> |
	</td>
</tr>
</div>
<tr>
	<td colspan="2">
<div id="content">
