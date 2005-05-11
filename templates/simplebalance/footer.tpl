</div>

<hr class="hide" />

<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+ sidebar +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

<div id="sidebar">

<!-- custom message start -->
<p>
	<i>your message goes here.</i>
</p>
<!-- custom message done -->

{if #showStaticEntries# != -1 && count($static_entries) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_static');">Static Entries</a></h3>
<ul id="div_static"{if #showStaticEntries# == 0} class="hide"{/if}>
{foreach from=$static_entries item=static}
	<li><a href="{$static->getHref()|escape}">{$static->title|escape}</a></li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

{if #showCalendar# != -1}
<h3><a href="#none" onclick="return fold_sidebar('div_calendar');">Calendar</a></h3>
<div id="div_calendar"{if #showCalendar# == 0} class="hide"{/if}>
	{$calendar->getCalendar()}
</div>
{/if}

{if #showCategories# != -1}
<h3><a href="#none" onclick="return fold_sidebar('div_category');">Categories</a></h3>
<ul id="div_category"{if #showCategories# == 0} class="hide"{/if}>
{foreach from=$categories item=category}
	<li><a href="{$category->getHref()|escape}">{$category->name|escape}</a> ({$category->getEntryCount()})</li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

{if #showArchives# != -1}
<h3><a href="#none" onclick="return fold_sidebar('div_archive');">Archives</a></h3>
<ul id="div_archive"{if #showArchives# == 0} class="hide"{/if}>
{foreach from=$archvies item=archive}
	<li><a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"}</a></li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

{if #showRecentEntries# != -1}
<h3><a href="#none" onclick="return fold_sidebar('div_entry');">Recent Entries</a></h3>
<ul id="div_entry"{if #showRecentEntries# == 0} class="hide"{/if}>
{foreach from=$recent_entries item=entry}
	<li><a href="{$entry->getHref()|escape}">{$entry->title|escape}</a></li>
{foreachelse}
	<li></li>
{/foreach}
</ul>
{/if}

{if #showRecentComments# != -1 && count($recent_comments) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_comment');">Recent Comments</a></h3>
<ul id="div_comment"{if #showRecentComments# == 0} class="hide"{/if}>
{foreach from=$recent_comments item=comment}
	<li><a href="{$comment->getHref()|escape}">{$comment->getBody()|strip_tags:false|escape}</a></li>
{/foreach}
</ul>
{/if}

{if #showRecentTrackbacks# != -1 && count($recent_trackbacks) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_trackback');">Recent TrackBacks</a></h3>
<ul id="div_trackback"{if #showRecentTrackbacks# == 0} class="hide"{/if}>
{foreach from=$recent_trackbacks item=trackback}
	<li><a href="{$trackback->getHref()|escape}">{$trackback->url|escape}</a></li>
{/foreach}
</ul>
{/if}

{if #showRecentReferers# != -1 && count($recent_referers) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_referer');">Recent Referers</a></h3>
<ul id="div_referer"{if #showRecentReferers# == 0} class="hide"{/if}>
{foreach from=$recent_referers item=referer}
	<li><a href="{$referer}">{$referer}</a></li>
{/foreach}
</ul>
{/if}

{if #showBookmarks# != -1 && count($bookmarks) != 0}
<h3><a href="#none" onclick="return fold_sidebar('div_bookmark');">Bookmarks</a></h3>
<ul id="div_bookmark"{if #showBookmarks# == 0} class="hide"{/if}>
{foreach from=$bookmarks item=bookmark}
	<li><a href="{$bookmark->url|escape}">{$bookmark->name|escape}</a></li>
{/foreach}
</ul>
{/if}

{if #showSearchForm# != -1}
<h3><a href="#none" onclick="return fold_sidebar('div_search');">Search</a></h3>
<form action="{$baseurl}/index.php" method="get" id="div_search"{if #showSearchForm# == 0} class="hide"{/if}>
	<p>
	<input type="text" name="search" title="input search text" />
	<input type="submit" value="go" />
	</p>
</form>
{/if}

<hr />

<p class="addition">
<a href="{$baseurl}/rss2.php"><img src="{$baseurl}/templates/{$skin}/img/rss20_logo.gif" alt="rss 2.0 feed" /></a>
<a href="{$baseurl}/rss2.php?charset=cp949">(euc-kr)</a><br />
<a href="http://validator.w3.org/check?uri=referer"><img src="{$baseurl}/templates/{$skin}/img/xhtml11.png" alt="Valid XHTML 1.0!" /></a><br />
<a href="http://www.w3.org/WAI/WCAG1A-Conformance" title="Explanation of Level A Conformance"><img src="{$baseurl}/templates/{$skin}/img/wai_a.png" alt="Level A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0" /></a>
</p>

</div>

<hr class="hide" />

<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+ footer ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

<div id="footer">
<p>
	{$license_link}<br />
	Powered by <a href="http://soojung.kldp.net/">soojung {$soojung_version}</a>. "The Simple Balance" template designed by Tokigun.
</p>
</div>

</body>
</html>
