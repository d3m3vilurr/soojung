<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$title}</title>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/styles.css"/>
<script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
</head>

<body onload="return correct_bbcode_image();">
<div id="header">
<!-- banner -->
    <div class="logo">
        <a href="{$baseurl}">
            <img width="150" height="55" alt="Go to Blog Home" src="{$baseurl}/templates/{$skin}/imgs/logo_sm.gif" />
        </a>
    </div>
    <div class="topmenu">
        <span class="blogtitle">{$title}</span> - {$description}
        <div class='menu'>
            <a href="{$baseurl}/rss2.php">RSS</a> |
            <a href="#none" onclick="return fold_sidebar('div_category');">Category</a> |
            <a href="#none" onclick="return fold_sidebar('div_archive');">Archive</a> |
            <a href="#none" onclick="return fold_sidebar('div_calendar');">Calendar</a> |
            <a href="#none" onclick="return fold_sidebar('div_static');">Satic Post</a> |
            <a href="#none" onclick="return fold_sidebar('div_entry');">Post</a> |
            <a href="#none" onclick="return fold_sidebar('div_recent_comment');">Comment</a> |
            <a href="#none" onclick="return fold_sidebar('div_recent_trackback');">Trackback</a> |
            <a href="#none" onclick="return fold_sidebar('div_bookmark');">Bookmark</a>
        </div>
    </div>
    <div class="search">
        <form action="{$baseurl}/index.php" method="get">
            <div>
            <input type="text" name="search" title="input search text" size="41" />
            <input type="submit" value="Search" />
            </div>
        </form>
    </div>
    <div class="setting">
    	<a href="{$baseurl}/admin.php" class="silver7">admin</a>
    </div>
</div>
