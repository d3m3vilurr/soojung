{include file="header.tpl"}

<div id="contents">
{if $view == "category"}
<h1 class="legend">Category: <strong>{$keyword|escape}</strong></h1>
{elseif $view == "archive"}
<h1 class="legend">Archive: <strong>{$keyword|escape}</strong></h1>
{elseif $view == "search"}
<h1 class="legend">Search result of <strong>{$keyword|escape}</strong></h1>
{/if}
{foreach from=$entries item=entry}
	<div class="entry">
		<h2><a href="{$entry->getHref()|escape}">{$entry->title|escape}</a> <span class="category">{$entry->category->name|escape}</span> <span class="date">{$entry->date|date_format:"%B %d, %Y"}</span></h2>
		<div class="body">{$entry->getBody()}</div>
		<p class="info">{if $entry->isSetOption("NO_COMMENT") == false}<a class="plink" href="{$entry->getHref()|escape}#comment">Comments ({$entry->getCommentCount()})</a>{/if}
        {if $entry->isSetOption("NO_TRACKBACK") == false and $entry->isSetOption("NO_COMMENT") == false}|{/if}
        {if $entry->isSetOption("NO_TRACKBACK") == false}<a class="plink" href="{$entry->getHref()|escape}#trackback">TrackBacks ({$entry->getTrackbackCount()})</a>{/if}</p>
	</div>
{/foreach}
	<div id="page">
{if $prev_page_link != ""}
		<a href="{$prev_page_link|escape}">prev</a>
{/if}
{if $prev_page_link && $next_page_link}
		|
{/if}
{if $next_page_link != ""}
		<a href="{$next_page_link|escape}">next</a>
{/if}
	</div>
</div>

{include file="footer.tpl"}
