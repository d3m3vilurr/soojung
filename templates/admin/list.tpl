{include file="header.tpl"}
{include file="menu.tpl"}

<div id="list">

<p class="navbar">
{if $prev_page_link != ""}
	<a href="{$prev_page_link|escape}">&laquo; prev</a>
{/if}
{if $prev_page_link != "" && $next_page_link != ""}
	&nbsp;&nbsp;
{/if}
{if $next_page_link != ""}
	<a href="{$next_page_link|escape}">next &raquo;</a>
{/if}
</p>

<span class="subject"><b>Subject</b></span>
<span class="trackback_ping"><b>Trackback</b></span>
<span class="delete"><b>Delete</b></span>

{foreach from=$entries item=entry}
<div class="entry">
	<span class="subject"><a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a></span>
	<span class="trackback_ping"><a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">Ping</a></span>
	<span class="delete"><a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete this entry?\nTitle: {$entry->title}');">X</a></span>
</div>

	{foreach from=$entry->getComments() item=comment}
	<div class="comment">
		<span class="subject">{$comment->getBody()|strip_tags|substring:70}</span>
		<span class="trackback_ping">&nbsp;</span>
		<span class="delete"><a href="{$baseurl}/admin.php?mode=delete&file={$comment->filename}" onclick="return confirm('Are you sure want to delete this comment?\nAuthor: {$comment->name}');">X</a></span>
	</div>
	{/foreach}

	{foreach from=$entry->getTrackbacks() item=trackback}
	<div class="trackback">
		<span class="subject">{$trackback->url|strip_tags|substring:70}</span>
		<span class="trackback_ping">&nbsp;</span>
		<span class="delete"><a href="{$baseurl}/admin.php?mode=delete&file={$trackback->filename}" onclick="return confirm('Are you sure want to delete this trackback?\nURL: {$trackback->url}');">X</a></span>
	</div>
	{/foreach}

{/foreach}

</div>

{include file="footer.tpl"}
