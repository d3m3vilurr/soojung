{config_load file="config.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>

<!--
"the simple balance" template (revision 2) for soojung 0.3.x
designed by Kang Seonghoon <tokigun@gmail.com>
soojung (c) copyleft 2004 soojung devel team, all rights reserved.
-->

<head>
<title>{$title|escape}{if $subtitle != ""} | {$subtitle|escape}{/if}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="generator" content="soojung 0.3.2" />
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/css/{#cssFile#}" />
{if #useMozillaExtension# == 1}
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/css/mozilla.css" />
{/if}
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/css/msie.css" />
<![endif]-->
<script type="text/javascript">/*<![CDATA[*/{literal}
function fold_sidebar(objid) {
    document.getElementById(objid).className =
        (document.getElementById(objid).className ? '' : 'hide');
    return false;
}
{/literal}/*]]>*/</script>
</head>

<body>

<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+ header ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

<div id="header">
	<h1><a href="{$baseurl|escape}">{$title|escape}</a></h1>
	<p class="float">today: {$today_count} | total: {$total_count} | <a href="{$baseurl}/admin.php">admin</a></p>
</div>

<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+ contents ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

<div id="contents">
