<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$title}</title>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/styles.css" />
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/skin.css" />
<script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
<script type="text/javascript" src="{$baseurl}/templates/{$skin}/javascript.js"></script>

</head>
<body onload="on_load();">
<div id="bg">
	<div id="leftbox" class="blackbox">
		<div id="main">
			<div id="header">
				<div style="text-align:right;" class="silver8">
					<a href="{$baseurl}/admin.php" class="silver7">admin</a>
				</div>
				<div class="blogtitle" style="font-size: 18pt;">
					<a href="{$baseurl}" title="{$description}">{$title}</a> &nbsp; <a href="{$baseurl}/rss2.php" onclick="seturltarget(this,'_blank');"><img src="{$baseurl}/templates/{$skin}/imgs/xml.gif" alt=""/></a>
				</div>
				<div class="blogdesc">{$description}</div>
			</div>

