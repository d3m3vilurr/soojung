{include file="header.tpl" title=$entry.title}

<div class="entry">
<h2>{$entry.title}</h2>
{$entry.body}
<p class="posted">{$entry.date|date_format:"%B %d, %Y %I:%M %p"}</p>
</div>

<div class="trackbacks">

<div class="trackback_url">TrackBack URL: {$baseurl}/trackback.php?blogid={$entry.id}</div>
<a name="trackback"></a>
{foreach from=$trackbacks item=trackback}
	<div class="trackback">
	<a name="TB{$trackback.date}"></a>
	<a href="{$trackback.url}">{$trackback.url}</a><br />
	{$trackback.title}<br />
	{$trackback.excerpt|strip_tags}
	</div>
{/foreach}
</div>

<div class="comments">
{$entry.comment_count} Comments<br />
<a name="comment"></a>
{foreach from=$comments item=comment}
	<div class="comment">
	<a name="CO{$comment.date}"></a>
	On {$comment.date|date_format:"%B %d, %Y %I:%M %p"}, 
	{if $comment.url != ""}
		<a href="{$comment.url}">{$comment.name}</a>
	{elseif $comment.email != ""}
		<a href=mailto:"{$comment.email}">{$comment.name}</a>
	{else}
		{$comment.name}
	{/if}
	said: <br />
	{$comment.body}
	</div>
{/foreach}
</div>

<form action="" method="post">
Post a comment<br />
Name:<br />
<input type="text" name="name" value="{$w_name}"><br />
Email Adress:<br />
<input type="text" name="email" value="{$w_email}"><br />
URL:<br />
<input type="text" name="url" value="{$w_url|default:"http://"}"><br />
Comments:<br />
<textarea name="body" rows="10" cols="40"></textarea><br />
<input type="hidden" name="blogid" value="{$entry.id}">
<input type="submit" value="Post">
</form>

{include file="footer.tpl"}
