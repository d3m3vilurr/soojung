</div>

</td>
</tr>
<tr id="footer">
	<td colspan="2">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="left">
				<a href="{$baseurl}/post.php"><img src="{$baseurl}/templates/{$skin}/imgs/ico_edit.gif" alt='edit' /></a>
				<a href="{$baseurl}/admin.php"><img src="{$baseurl}/templates/{$skin}/imgs/ico_admin.gif" alt='admin' /></a>
				<a href="#none" onclick="return fold_sidebar('sidebar');"><img src="{$baseurl}/templates/{$skin}/imgs/ico_menu.gif" alt='menu' /></a>
			</td>
			<td align="right">
				<a href="http://soojung.kldp.net">수정</a>을 씁니다.
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</td><td valign="top" style='width:250px;'>
<div id="sidebar">
<h3><a href="#none" onclick="return fold_sidebar('div_calendar');">Calendar</a></h3>
<div id="div_calendar">
	{$calendar->getCalendar()}
</div>

<h3><a href="#none" onclick="return fold_sidebar('div_category');">Categories</a></h3>
<ul id="div_category">
{foreach from=$categories item=category}
	<li>
		<a href="{$category->getHref()|escape}">{$category->name|escape}</a> 
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
		<a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"}</a>
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>

<h3><a href="#none" onclick="return fold_sidebar('div_recent_entry');">Recent Entries</a></h3>
<ul id="div_recent_entry" class="div_hide">
{foreach from=$recent_entries item=entry}
	<li>
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
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
		<a href="{$comment->getHref()|escape}">{$comment->getBody()|strip_tags:false|escape}</a>
	</li>
{/foreach}
</ul>
{/if}

{if count($recent_trackbacks) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_recent_trackback');">Recent TrackBacks</a></h3>
<ul id="div_recent_trackback" class="div_hide">
{foreach from=$recent_trackbacks item=trackback}
	<li>
		<a href="{$trackback->getHref()|escape}">{$trackback->url|escape}</a>
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
		<a href="{$bookmark->url|escape}">{$bookmark->desc|escape}</a>
	</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

<h3>Search</h3>
<form action="{$baseurl}/index.php" method="get">
<p>
<input type="text" name="search" title="input search text" size="16"/>
<input type="submit" value="Search" />
</p>
</form>

<p>
Today : {$today_count}<br />
Total : {$total_count}<br />
</p>

<a href="{$baseurl}/rss2.php">
<img src="{$baseurl}/templates/{$skin}/imgs/rss20_logo.gif" alt="rss 2.0 feed" style="border:0px;"/>
</a>

<br />

<a href="http://validator.w3.org/check?uri=referer">
<img src="{$baseurl}/templates/{$skin}/imgs/xhtml11.png" alt="Valid XHTML 1.0!" style="border:0px;"/>
</a>

<br />

<a class="nodecoration" title="Explanation of Level A Conformance" href="http://www.w3.org/WAI/WCAG1A-Conformance">
<img src="{$baseurl}/templates/{$skin}/imgs/wai_a.png" alt="Level A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0" style="border:0px;"/>
</a>
<br />

<p>
{$license_link}
</p>
</div>
</td></tr>
</table>

</body>
</html>
