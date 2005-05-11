<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>{$title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/asual.css" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
	<script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
</head>
<body onload="return correct_bbcode_image();">

<div id="main">
	<div id="header">
		<h1>
			<a href="{$baseurl}" title="{$description}">{$title}</a>
			<span>{$description}</span>
		</h1>
		<div id="searchbox">
			<form action="{$baseurl}/index.php" method="get">
			<p id="searchlabel"><label for="searchfield">Search:</label></p>
			<p id="searchfield"><input type="text" name="search" title="input search text" /></p>
			<p id="searchimg"><input type="image" src="{$baseurl}/templates/{$skin}/images/asual-search.jpg" value="submit" alt="Search" /></p>
			</form>
		</div>
	</div>

	<div id="content">
		<div id="chead"></div>
		<div id="cbody">
