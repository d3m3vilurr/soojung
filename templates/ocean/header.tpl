<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>{$title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/ocean.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/ocean-fixed.css" title="fixed" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/ocean-elastic.css" title="elastic" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
	<script type="text/javascript" src="{$baseurl}/templates/{$skin}/ocean.js"></script>
	<script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
</head>
<body onload="return correct_bbcode_image();">

<div id="main">
	<div id="header">
		<div id="name">
			<h1><a href="{$baseurl}" title="{$description}">{$title}</a></h1>
			<h2>{$description}</h2>
		</div>
		<div id="controls">
			<form id="searchform" action="{$baseurl}/index.php" method="get">
				<p>
					<label id="searchlabel">Search:</label><input id="searchfield" type="text" name="search" title="input search text" />
					<input id="searchimage" type="image" src="{$baseurl}/templates/{$skin}/images/ocean-search.jpg" value="submit" alt="Search" />
				</p>
			</form>
			<div id="switcher">
				<script type="text/javascript">
					writeSwitcher();
				</script>
				<noscript>
					<p>&nbsp;</p>
				</noscript>
			</div>
		</div>
	</div>

	<div id="contentHeadLeft"><div id="contentHeadRight"><div id="contentHeadCenter"></div></div></div>
	<div id="contentBodyLeft">
		<div id="contentBodyRight">
			<div id="contentBodyCenter">

				<div id="content">
