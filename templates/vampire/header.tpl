<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>{$title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/vampire.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/vampire-fixed.css" title="fixed" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/vampire-elastic.css" title="elastic" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
	<script type="text/javascript" src="{$baseurl}/templates/{$skin}/vampire.js"></script>
	<script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
</head>
<body onload="return correct_bbcode_image();">

<div id="main">
	<div id="header">
		<h1>
			<a href="{$baseurl}">{$title}</a>
			<span>{$description}</span>
		</h1>
		<div id="controls">
			<form id="searchform" action="{$baseurl}/index.php" method="get">
				<p>
					<label id="searchlabel" for="searchfield">Search:</label><input id="searchfield" type="text" name="search" title="input search text" />
					<input id="searchimage" type="image" src="{$baseurl}/templates/{$skin}/images/vampire-search.jpg" value="submit" alt="Search" />
				</p>
			</form>
			<div id="switcher">
				<a id="switchlink" href="#" onclick="switchLayout(); return false;">Switch to Elastic Layout</a>
			</div>
		</div>
	</div>

	<div id="contentBodyLeft">
		<div id="contentBodyRight">
			<div id="contentBodyCenter">
				<div id="contentHeadLeft"><div id="contentHeadRight"><div id="contentHeadCenter"></div></div></div>
				<div id="content">
