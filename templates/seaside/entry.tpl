{include file="header.tpl"}

<div id="contents">
	<div class="entry">
		<h2>{$entry->title|escape} <span class="category">{$entry->category->name|escape}</span> <span class="date">{$entry->date|date_format:"%B %d, %Y %I:%M %p"}</span></h2>
		<div class="body">{$entry->getBody()}</div>
	</div>

{if $entry->isSetOption("NO_TRACKBACK") == false}
<div class="trackbacks" id="trackback">
<p class="trackback_url">TrackBack URL: {$baseurl}/trackback.php?blogid={$entry->entryId}</p>
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
{/if}

{if $entry->isSetOption("NO_COMMENT") == false}
<ul id="comment">
{foreach from=$comments item=comment}
	<li id="CO{$comment->date}"> 
		<p class="cmtinfo">
		{if $comment->homepage != ""}
			<a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
		{elseif $comment->email != ""}
			<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
		{else}
			{$comment->name|escape}
		{/if}|
		{$comment->date|date_format:"%B %d, %Y %I:%M %p"}
		</p>
		<p class="cmtbody">{$comment->getBody()}</p>
	</li>
{foreachelse}
    <li style="display:none;">&nbsp;</li>
{/foreach}
</ul>

<form action="" method="post">
<input type="hidden" name="blogid" value="{$entry->entryId}" />
<h3>Post a comment</h3>
<table id="cmtform">
<tr>
	<th>Name:</th>
	<td><input type="text" name="name" value="{$w_name|escape}" class="text" /></td>
</tr>
<tr>
	<th>Email Address:</th>
	<td><input type="text" name="email" value="{$w_email|escape}" class="text" /></td>
</tr>
<tr>
	<th>URL:</th>
	<td><input type="text" name="url" value="{$w_url|default:"http://"|escape}" class="text" /></td>
</tr>
<tr>
	<th>Comments:</th>
	<td><textarea name="body" rows="5" cols="40"></textarea><br /><input type="submit" value="Post" /></td>
</tr>
</table>
</form>
{/if}
</div>

{include file="footer.tpl"}
