<div id="menu">
<ul>
<li id="Other:">
	Menu:
	<ul>
		<li><a href="{$baseurl}/index.php">main</a></li>
		<li><a href="{$baseurl}/admin.php">admin</a></li>
		{foreach from=$static_entries item=static}
		<li><a href="{$static->getHref()|escape}">{$static->title|escape}</a></li>
		{/foreach}
	</ul>
</li>
<li id="categories">
	Categories:
	<ul>
		{foreach from=$categories item=category}
		<li>
			<a href="{$category->getHref()|escape}">{$category->name|escape}</a> 
			({$category->getEntryCount()})
		</li>
		{foreachelse}
		<li></li>
		{/foreach}
	</ul>
</li>
<li id="search">
	Search:
	<form id="searchform" action="{$baseurl}/index.php" method="get">
	<div>
		<input type="text" name="search" title="input search text" size="15" /><br />
		<input type="submit" value="search" />
	</div>
	</form>
</li>
<li id="archives">
	Archives:
	<ul>
	{foreach from=$archvies item=archive}
	<li>
		<a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"}</a>
	</li>
	{foreachelse}
	<li></li>
	{/foreach}
	</ul>
</li>
{if count($recent_referers) != 0}
<li>
	Recent Referers:
	<ul>
	{foreach from=$recent_referers item=referer}
	<li>
		<a href="{$referer}">{$referer}</a>
	</li>
	{/foreach}
	</ul>
</li>
{/if}
{if count($bookmarks) != 0}
<li>
	Bookmarks:
	<ul>
	{foreach from=$bookmarks item=bookmark}
	<li>
		<a href="{$bookmark->url|escape}">{$bookmark->desc|escape}</a>
	</li>
	{foreachelse}
	<li></li>
	{/foreach}
	</ul>
</li>
{/if}
<li id="meta">
	Meta:
	<ul>
	<li><a href="{$baseurl}/rss2.php">RSS2</a></li>
	<li><a href="http://validator.w3.org/check?uri=referer">Valid XHTML</a></li>
	</ul>
</li>
<li>
	<p>Today : {$today_count}, Total : {$total_count}</p>
</li>
</ul>
</div>

</div>

<p class="credit">{$license_link}<br />Powered by <a href="http://soojung.kldp.net">soojung {$soojung_version}</a>. Design based on Rubric <a href="http://www.wordpress.org">WordPress</a> CSS style by Handley Wickham.</p>
</body>
</html>
