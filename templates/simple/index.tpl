{include file="header.tpl"}

{if $view == "index"}
	{foreach from=$entries item=entry}
		<div class="entry">
		<h2><a href="{$entry->getHref()|escape}">{$entry->title|escape}</a></h2>
		{$entry->getBody()}
		<p class="posted">
			<a class="plink" href="{$entry->getHref()|escape}">{$entry->date|date_format:"%B %d, %Y"}</a>
			{if $entry->isSetOption("NO_COMMENT") == false}| <a class="plink" href="{$entry->getHref()|escape}#comment">Comments ({$entry->getCommentCount()})</a>{/if}
			{if $entry->isSetOption("NO_TRACKBACK") == false}| <a class="plink" href="{$entry->getHref()|escape}#trackback">TrackBacks ({$entry->getTrackbackCount()})</a>{/if}

		</p>
		</div>
	{/foreach}
	{if $prev_page_link != ""}
		<a href="{$prev_page_link|escape}">prev</a>
	{/if}
	{if $next_page_link != ""}
		<a href="{$next_page_link|escape}">next</a>
	{/if}
{elseif $view == "archive"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
		{if $keyword|strlen != 8}
		{$entry->date|date_format:"%B %d, %Y"}
		{else}
		{$entry->date|date_format:"%H:%M"}
		{/if}
		<br />
	{/foreach}
{elseif $view == "category"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
		{$entry->date|date_format:"%B %d, %Y"}
		<br />
	{/foreach}
{elseif $view == "search"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
		{$entry->date|date_format:"%B %d, %Y"}
		<br />
	{/foreach}
{/if}

{include file="footer.tpl"}
