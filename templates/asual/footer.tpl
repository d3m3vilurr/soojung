			<div id="column">

<!--
{$calendar->getCalendar()}
-->
<div>
	<strong>Menu:</strong>
	<ul>
		<li><a href="{$baseurl}/index.php">main</a></li>
		<li><a href="{$baseurl}/admin.php">admin</a></li>
		{foreach from=$static_entries item=static}
		<li><a href="{$static->getHref()|escape}">{$static->title|escape}</a></li>
		{/foreach}
	</ul>
</div>
<div>
	<strong>Categories:</strong>
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
</div>
<div>
	<strong>Syndication:</strong>
	<p><a href="{$baseurl}/rss2.php">RSS2</a></p>
</div>
<div>
	<strong>Archives:</strong>
	<ul>
		{foreach from=$archvies item=archive}
		<li>
			<a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"}</a>
		</li>
		{foreachelse}
		<li></li>
		{/foreach}
	</ul>
</div>
<div>
	<strong>Recently:</strong>
	<ul>
		{foreach from=$recent_entries item=entry}
		<li>
			<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
		</li>
		{foreachelse}
		<li></li>
		{/foreach}
	</ul>
</div>
{if count($recent_referers) != 0}
<div>
	<strong>Recent Referers:</strong>
	<ul>
		{foreach from=$recent_referers item=referer}
		<li>
			<a href="{$referer}">{$referer}</a>
		</li>
		{/foreach}
	</ul>
</div>
{/if}

{if count($bookmarks) != 0}
<div>
	<strong>Bookmarks:</strong>
	<ul>
		{foreach from=$bookmarks item=bookmark}
		<li>
			<a href="{$bookmark->url|escape}">{$bookmark->desc|escape}</a>
		</li>
		{foreachelse}
		<li></li>
		{/foreach}
	</ul>
</div>
{/if}
<div>
	<p>Today : {$today_count}</p>
	<p>Total : {$total_count}</p>
</div>
			</div>
		</div>
		<div id="cfoot"></div>
	</div>

	<div id="footer">
		<p id="copyright">{$license_link} Design based on Asual <a href="http://blojsom.sourceforge.net">blojsom</a> theme.</p>
		<p id="info">Powered by <a href="http://soojung.kldp.net">soojung {$soojung_version}</a>. Valid <a href="http://validator.w3.org/check?uri=referer">XHTML</a></p>
	</div>

</div>
</body>
</html>
