</div>

<div id="sidebar">
<h3><a href="#none" onclick="return fold_sidebar('div_menu');">Menu</a></h3>
<ul id="div_menu">
<li><a href="{$baseurl}/index.php">main</a></li>
<li><a href="{$baseurl}/admin.php">admin</a></li>
{foreach from=$static_entries item=static}
	<li><a href="{$static->getHref()}">{$static->title}</a></li>
{/foreach}
</ul>

<h3><a href="#none" onclick="return fold_sidebar('div_category');">Categories</a></h3>
<ul id="div_category">
{foreach from=$categories item=category}
	<li>
		<a href="{$category->getHref()}">{$category->name}</a> 
		({$category->getEntryCount()})
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>

<h3><a href="#none" onclick="return fold_sidebar('div_archive');">Archives</a></h3>
<ul id="div_archive" class="div_hide">
{foreach from=$archvies item=archive}
	<li>
		<a href="{$archive->getHref()}">{$archive->getName()}</a>
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>

<h3><a href="#none" onclick="return fold_sidebar('div_recent_entry');">Recent Entries</a></h3>
<ul id="div_recent_entry" class="div_hide">
{foreach from=$recent_entries item=entry}
	<li>
		<a href="{$entry->getHref()}">{$entry->title}</a>
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>

{if count($recent_comments) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_recent_comment');">Recent Comments</a></h3>
<ul id="div_recent_comment" class="div_hide">
{foreach from=$recent_comments item=comment}
	<li>
		<a href="{$comment->getHref()}">{$comment->getBody()|strip_tags:false}</a>
	</li>
{/foreach}
</ul>
{/if}

{if count($recent_trackbacks) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_recent_trackback');">Recent TrackBacks</a></h3>
<ul id="div_recent_trackback" class="div_hide">
{foreach from=$recent_trackbacks item=trackback}
	<li>
		<a href="{$trackback->getHref()}">{$trackback->url}</a>
	</li>
{/foreach}
</ul>
{/if}

{if count($recent_referers) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_recent_referer');">Recent Referers</a></h3>
<ul id="div_recent_referer" class="div_hide">
{foreach from=$recent_referers item=referer}
	<li>
		<a href="{$referer}">{$referer}</a>
	</li>
{/foreach}
</ul>
{/if}

{if count($bookmarks) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_bookmark');">Bookmarks</a></h3>
<ul id="div_bookmark" class="div_hide">
{foreach from=$bookmarks item=bookmark}
	<li>
		<a href="{$bookmark.url}">{$bookmark.desc}</a>
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

<h3>Search</h3>
<form action="index.php" method="get">
<p>
<input type="text" name="search" title="input search text"/>
<input type="submit" value="go" />
</p>
</form>

<p>
Today : {$today_count}<br />
Total : {$total_count}<br />
</p>

<a href="{$baseurl}/rss2.php">
<img src="{$baseurl}/templates/simple/imgs/rss20_logo.gif" alt="rss 2.0 feed" style="border:0px;"/>
</a>

<br />

<a href="http://validator.w3.org/check?uri=referer">
<img src="{$baseurl}/templates/simple/imgs/xhtml11.png" alt="Valid XHTML 1.0!" style="border:0px;"/>
</a>

<br />

<a class="nodecoration" title="Explanation of Level A Conformance" href="http://www.w3.org/WAI/WCAG1A-Conformance">
<img src="{$baseurl}/templates/simple/imgs/wai_a.png" alt="Level A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0" style="border:0px;"/>
</a>

</div>

<div id="footer">
<p>
Copyleft 2004 soojung devel team, all rights reserved.<br />
Powered by <a href="http://soojung.kldp.net">soojung</a>
</p>

</div>
</body>
</html>
