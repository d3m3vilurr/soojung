{include file="header.tpl"}

<div id="entries">

{if $view == "index"}
	{foreach from=$entries item=entry}

	<div class="entry">
		<h3><a href="{$entry->getHref()|escape}">{$entry->title|escape}</a></h3>
		<div class="post">
			{$entry->getBody()}
		</div>
		<p>
			<span class="blue">Posted on {$entry->date|date_format:"%B %d, %Y %p %I:%M"}</span>
		{if $entry->isSetOption("NO_COMMENT") == false}
			<span><a href="{$entry->getHref()|escape}#comment"><span></span>Comments [{$entry->getCommentCount()}]</a></span>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<span><a href="{$entry->getHref()|escape}#trackback"><span></span>Trackbacks [{$entry->getTrackbackCount()}]</a></span>
		{/if}
		</p>
	</div>
	<div class="clear"></div>
	{/foreach}

	<div>
	{if $prev_page_link != ""}
		<a href="{$prev_page_link|escape}">prev</a>
	{/if}
	{if $next_page_link != ""}
		<a href="{$next_page_link|escape}">next</a>
	{/if}
	</div>

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
<div class="clear"></div>
	{/foreach}
{elseif $view == "search"}
	{foreach from=$entries item=entry}
	<p>
		<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
		on {$entry->date|date_format:"%B %d, %Y"}
	</p>
<div class="clear"></div>
	{/foreach}
{/if}

</div>

{include file="footer.tpl"}
