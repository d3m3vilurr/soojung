{include file="header.tpl"}

			<h2><a href="{$baseurl}">Blog</a></h2>
			<div id="nav"></div>
			<div id="clear"></div>
			<div id="entries">

{if $view == "index"}
	{foreach from=$entries item=entry}

<div class="entry">
	<h3><a href="{$entry->getHref()|escape}">{$entry->title|escape}</a></h3>
	<div class="entrybody">
	{$entry->getBody()}
	</div>
	<p>
		<span class="blue">Posted on {$entry->date|date_format:"%B %d, %Y %I:%M"}</span>
		{if $entry->isSetOption("NO_COMMENT") == false}
			<span>Comments [<a class="plink" href="{$entry->getHref()|escape}#comment">{$entry->getCommentCount()}</a>]</span>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<span>Trackbacks [<a class="plink" href="{$entry->getHref()|escape}#trackback">{$entry->getTrackbackCount()}</a>]</span>
		{/if}
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
<p>
	<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
	on {$entry->date|date_format:"%B %d, %Y"}
</p>
	{/foreach}
{elseif $view == "category"}
	{foreach from=$entries item=entry}
<p>
	<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
	on {$entry->date|date_format:"%B %d, %Y"}
</p>
	{/foreach}
{elseif $view == "search"}
	{foreach from=$entries item=entry}
<p>
	<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
	on {$entry->date|date_format:"%B %d, %Y"}
</p>
	{/foreach}
{/if}
			</div>

{include file="footer.tpl"}
