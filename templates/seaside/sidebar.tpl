<h2>Menu</h2>
<ul>
	<li><a href="{$baseurl}/index.php">main</a></li>
	<li><a href="{$baseurl}/admin.php">admin</a></li>
	{foreach from=$static_entries item=static}
	<li><a href="{$static->getHref()|escape}">{$static->title|escape}</a></li>
	{/foreach}
	<li><a href="{$baseurl}/rss2.php" class="rss">RSS (2.0)</a></li>
</ul>

<h2>Search</h2>
<form action="{$baseurl}/index.php" method="get">
<p>
<input type="text" name="search" id="search" title="input search text" size="16" onfocus="this.className='on'; return true" onblur="this.className='off'; return true" /> <input type="submit" value="Go" />
</p>
</form>

<h2>Calendar</h2>
{$calendar->getCalendar()}

<h2>Categories</h2>
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

<h2>Archives</h2>
<ul>
	{foreach from=$archvies item=archive}
	<li>
		<a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"}</a>
	</li>
	{foreachelse}
	<li></li>
	{/foreach}
</ul>

<h2>Bookmarks</h2>
<ul>
	{foreach from=$bookmarks item=bookmark}
	<li>
		<a href="{$bookmark->url|escape}">{$bookmark->name|escape}</a>
	</li>
	{foreachelse}
	<li></li>
	{/foreach}
</ul>
