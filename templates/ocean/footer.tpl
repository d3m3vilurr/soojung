<div id="column">
	<!--
	{$calendar->getCalendar()}
	-->
	<div id="links">
		<div>
			<h5>Menu:</h5>
			<ul>
				<li><a href="{$baseurl}/index.php" class="rounded"><span>main</span></a></li>
				<li><a href="{$baseurl}/admin.php" class="rounded"><span>admin</span></a></li>
				{foreach from=$static_entries item=static}
				<li><a href="{$static->getHref()|escape}">{$static->title|escape}</a></li>
				{/foreach}
			</ul>
		</div>
		<div>
			<h5>Categories:</h5>
			<ul>
				{foreach from=$categories item=category}
				<li>
					<a href="{$category->getHref()|escape}" class="rounded"><span>{$category->name|escape}</span></a> 
					({$category->getEntryCount()})
				</li>
				{foreachelse}
				<li></li>
				{/foreach}
			</ul>
		</div>
		<div>
			<h5>Archives:</h5>
			<ul>
				{foreach from=$archvies item=archive}
				<li>
					<a href="{$archive->getHref()|escape}" class="rounded"><span>{$archive->getDate()|date_format:"%B %Y"}</span></a>
				</li>
				{foreachelse}
				<li></li>
				{/foreach}
			</ul>
		</div>
		<div>
			<h5>Recently:</h5>
			<ul>
				{foreach from=$recent_entries item=entry}
				<li>
					<a href="{$entry->getHref()|escape}" class="rounded"><span>{$entry->title|escape}</span></a>
				</li>
				{foreachelse}
				<li></li>
				{/foreach}
			</ul>
		</div>
		{if count($recent_referers) != 0}
		<div>
			<h5>Recent Referers:</h5>
			<ul>
				{foreach from=$recent_referers item=referer}
				<li>
					<a href="{$referer}" class="rounded"><span>{$referer}</span></a>
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
					<a href="{$bookmark->url|escape}" class="rounded"><span>{$bookmark->name|escape}</span></a>
				</li>
				{foreachelse}
				<li></li>
				{/foreach}
			</ul>
		</div>
		{/if}
		<div>
			<h5>Syndication:</h5>
			<p><a href="{$baseurl}/rss2.php" class="rounded"><span>RSS2</span></a></p>
		</div>
		<div>
			<h5>&nbsp;</h5>
			<p>Today : {$today_count}</p>
			<p>Total : {$total_count}</p>
		</div>
	</div>
</div>
<!-- end #column -->
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		</div>
	</div>
	<div id="contentFootLeft"><div id="contentFootRight"><div id="contentFootCenter"></div></div></div>

	<div id="footer">
		<p id="copyright">{$license_link} Design based on Ocean <a href="http://blojsom.sourceforge.net">blojsom</a> theme by <a href="http://www.asual.com" title="Click here to visit the website of Asual Interactive Solutions">Asual</a>.</p>
		<p id="info">Powered by <a href="http://soojung.kldp.net">soojung {$soojung_version}</a>. Valid <a href="http://validator.w3.org/check?uri=referer">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a></p>
	</div>

</div>
</body>
</html>
