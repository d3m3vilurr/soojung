{include file="header.tpl"}

<div class="entry">
<h2>{$entry->title|escape}</h2>
{$entry->getBody()}
<p class="posted">{$entry->date|date_format:"%B %d, %Y %I:%M %p"}</p>
</div>

{if $entry->isSetOption("NO_TRACKBACK") == false}
<div class="trackbacks">
<div class="trackback_url">TrackBack URL: {$baseurl}/trackback.php?blogid={$entry->entryId}</div>
<a name="trackback"></a>
{foreach from=$trackbacks item=trackback}
	<div class="trackback">
	<a name="TB{$trackback->date}"></a>
	<a href="{$trackback->url|escape}">{$trackback->url|escape}</a><br />
	{$trackback->title|escape}<br />
	{$trackback->getExcerpt()|strip_tags|escape}
	</div>
{/foreach}
</div>
{/if}

{if $entry->isSetOption("NO_COMMENT") == false}
<div class="comments">
{$entry.comment_count} Comments<br />
<a name="comment"></a>
{foreach from=$comments item=comment}
	<div class="comment">
	<a name="CO{$comment->date}"></a>
	On {$comment->date|date_format:"%B %d, %Y %I:%M %p"}, 
	{if $comment->homepage != ""}
		<a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
	{elseif $comment->email != ""}
		<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
	{else}
		{$comment->name}
	{/if}
	said: <br />
	{$comment->getBody()}
	</div>
{/foreach}
</div>

<form action="" method="post">
Post a comment<br />
Name:<br />
<input type="text" name="name" value="{$w_name|escape}"><br />
Email Address:<br />
<input type="text" name="email" value="{$w_email|escape}"><br />
URL:<br />
<input type="text" name="url" value="{$w_url|default:"http://"|escape}"><br />
Comments:<br />
<textarea name="body" rows="5" cols="40"></textarea><br />
<input type="hidden" name="blogid" value="{$entry->entryId}">
<input type="submit" value="Post">
</form>
{/if}

{include file="footer.tpl"}
