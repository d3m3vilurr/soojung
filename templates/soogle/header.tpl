<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>{$title}</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$baseurl}/rss2.php" />
    <link rel="stylesheet" type="text/css" href="{$baseurl}/templates/{$skin}/styles.css"/>
    <script type="text/javascript" src="{$baseurl}/libs/common.js"></script>
</head>

<body onload="return correct_bbcode_image();">
<div id="header">
<!-- banner -->
    <div id="header-logo">
        <a href="{$baseurl}">
            <img width="150" height="55" alt="Go to Blog Home" src="{$baseurl}/templates/{$skin}/imgs/logo_sm.gif" />
        </a>
    </div>

    <div id="header-menu">
        <span><strong>{$title}</strong> - {$description}</span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="{$baseurl}/rss2.php">RSS</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#none" onclick="return fold_sidebar('div_category');">Category</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#none" onclick="return fold_sidebar('div_archive');">Archive</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#none" onclick="return fold_sidebar('div_entry');">Post</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#none" onclick="return fold_sidebar('div_bookmark');">Bookmark</a>
    </div>

    <!-- search -->
    <form action="{$baseurl}/index.php" method="get">
        <div id="header-search">
            <input type="text" name="search" title="input search text" size="41" />
            <input type="submit" value="Search" />
            <a href="{$baseurl}/admin.php" class="tiny">admin</a>
        </div>
    </form>
</div>
