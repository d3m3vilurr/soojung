{include file="header.tpl"}

{if $view == "index"}
	{foreach from=$entries item=entry}
		<div class="entry">
		<h2><a href="{$entry->getHref()}">{$entry->title}</a></h2>
		{$entry->getBody()}
		<p class="posted">
			<a class="plink" href="{$entry->getHref()}">{$entry->date|date_format:"%B %d, %Y"}</a>
			{if $entry->isSetOption("NO_COMMENT") == false}| <a class="plink" href="{$entry->getHref()}#comment">Comments ({$entry->getCommentCount()})</a>{/if}
			{if $entry->isSetOption("NO_TRACKBACK") == false}| <a class="plink" href="{$entry->getHref()}#trackback">TrackBacks ({$entry->getTrackbackCount()})</a>{/if}

		</p>
		</div>
	{/foreach}
	{if $prev_page_link != ""}
		<a href="{$prev_page_link}">prev</a>
	{/if}
	{if $next_page_link != ""}
		<a href="{$next_page_link}">next</a>
	{/if}
{elseif $view == "archive"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()}">{$entry->title}</a>
		{$entry->date|date_format:"%B %d, %Y"}
		<br />
	{/foreach}
{elseif $view == "category"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()}">{$entry->title}</a>
		{$entry->date|date_format:"%B %d, %Y"}
		<br />
	{/foreach}
{elseif $view == "search"}
	{foreach from=$entries item=entry}
		<a href="{$entry->getHref()}">{$entry->title}</a>
		{$entry->date|date_format:"%B %d, %Y"}
		<br />
	{/foreach}
{/if}

{include file="footer.tpl"}
