{include file="header.tpl"}

{if $view == "index"}

{foreach from=$entries item=entry}
<div class="entry">
<h2>
	<a href="{$entry->getHref()|escape}" class="box_title">{$entry->title|escape}</a> <span class="hide">|</span>
	<span class="box_header">{$entry->date|date_format:"%Y/%m/%d %p %I:%M"}</span>
</h2>
<div class="box_body">
{$entry->getbody()}
</div>
<p class="entry_footer">
	{$entry->category->name|escape} |
{if $entry->isSetOption("NO_COMMENT") == false}
	<a href="{$entry->getHref()|escape}#comments">Comments ({$entry->getCommentCount()})</a> |
{else}
	<span class="disabled">No Comment</span> |
{/if}
{if $entry->isSetOption("NO_TRACKBACK") == false}
	<a href="{$entry->getHref()|escape}#trackbacks">TrackBacks ({$entry->getTrackbackCount()})</a>
{else}
	<span class="disabled">No Trackback</span>
{/if}
</p>
</div>

{/foreach}

{if $prev_page_link != "" or $next_page_link != ""}
<p class="navibar"><span>
	{if $prev_page_link != ""}<a href="{$prev_page_link|escape}">&lt; prev</a>{else}&lt; prev{/if} |
	{if $next_page_link != ""}<a href="{$next_page_link|escape}">next &gt;</a>{else}next &gt;{/if}
</span></p>
{/if}

{elseif $view == "archive"}

<div class="entrylist">
	<h2>Archive</h2>
	<div class="box_body">
{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a> {$entry->date|date_format:"%B %d, %Y"}<br />
{/foreach}
	</div>
</div>

{elseif $view == "category"}

<div class="entrylist">
	<h2>Category</h2>
	<div class="box_body">
{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a> {$entry->date|date_format:"%B %d, %Y"}<br />
{/foreach}
	</div>
</div>

{elseif $view == "search"}

<div class="entrylist">
	<h2>Search Results</h2>
	<div class="box_body">
{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a> {$entry->date|date_format:"%B %d, %Y"}<br />
{/foreach}
	</div>
</div>

{/if}

{include file="footer.tpl"}
