{include file="header.tpl"}

<div class="entry">
<h2>{$entry->title|escape}</h2>
{$entry->getBody()}
<p class="posted">{$entry->date|date_format:"%B %d, %Y %I:%M %p"}</p>
</div>

{if $entry->isSetOption("NO_TRACKBACK") == false}
<div class="trackbacks">
<div class="trackback_url">TrackBack URL: {$baseurl}/trackback.php?blogid={$entry->entryId}</div>
<div id="trackback">
{foreach from=$trackbacks item=trackback}
	<div class="trackback">
	<div id="TB{$trackback->date}">
	<a href="{$trackback->url|escape}">{$trackback->url|escape}</a><br />
	{$trackback->title|escape}<br />
	{$trackback->getExcerpt()|strip_tags|escape}
    </div>
	</div>
{/foreach}
</div>
</div>
{/if}

{include file="comments.tpl"}

{include file="footer.tpl"}
