{include file="header.tpl"}

{if $view == "index"}
	{foreach from=$entries item=entry}

<h2>{$entry->date|date_format:"%B %d, %Y"}</h2>
<div class="post">
	<h3 class="storytitle"><a href="{$entry->getHref()}">{$entry->title}</a></h3>
	<div class="meta">
		Posted at {$entry->date|date_format:"%I:%M %p"}
	</div>
	<div class="storycontent">
		<p>
			{$entry->getBody()}
		</p>
	</div>
	<div class="feedback">
		{if $entry->isSetOption("NO_COMMENT") == false}
			<a href="{$entry->getHref()}#comment">Comments ({$entry->getCommentCount()})</a>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<a href="{$entry->getHref()}#trackback">Trackbacks ({$entry->getTrackbackCount()})</a>
		{/if}
	</div>
</div>

	{/foreach}

<div id="goto">
	{if $prev_page_link != ""}
		<a href="{$prev_page_link}">prev</a>
	{/if}
	{if $next_page_link != ""}
		<a href="{$next_page_link}">next</a>
	{/if}
</div>

{elseif $view == "archive"}
<div class="post">
	{foreach from=$entries item=entry}
	<p>
		<a href="{$entry->getHref()}">{$entry->title}</a>
		on {$entry->date|date_format:"%B %d, %Y"}
	</p>
	{/foreach}
</div>
{elseif $view == "category"}
<div class="post">
	{foreach from=$entries item=entry}
	<p>
		<a href="{$entry->getHref()}">{$entry->title}</a>
		on {$entry->date|date_format:"%B %d, %Y"}
	</p>
	{/foreach}
</div>
{elseif $view == "search"}
<div class="post>
	{foreach from=$entries item=entry}
	<p>
		<a href="{$entry->getHref()}">{$entry->title}</a>
		on {$entry->date|date_format:"%B %d, %Y"}
	</p>
	{/foreach}
</div>
{/if}

</div>

{include file="footer.tpl"}
