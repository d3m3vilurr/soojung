{include file="header.tpl" subtitle=$entry->title}

<div class="entry thisentry">
<h2>
	<span class="box_title">{$entry->title|escape}</span> <span class="hide">|</span>
	<span class="box_header">{$entry->category->name|escape} | {$entry->date|date_format:"%Y/%m/%d %p %I:%M"}</span>
</h2>

<div class="box_body">
{$entry->getBody()}
</div>

{if $entry->isSetOption("NO_TRACKBACK") == false}
<div id="trackbacks">
	<p class="trackback_url"><b>TrackBack URL:</b> {$baseurl}/trackback.php?blogid={$entry->entryId}</p>
{foreach from=$trackbacks item=trackback}
	<div class="trackback" id="TB{$trackback->date}">
		<h3>
			<a href="{$trackback->url|escape}" class="box_title">Trackback: {$trackback->url|escape}</a> <span class="hide">| trackbacked on</span>
			<span class="box_header">{$trackback->date|date_format:"%Y/%m/%d %p %I:%M"}</span>
		</h3>
		<div class="box_body">
		{$trackback->getExcerpt()|strip_tags|escape}
		</div>
	</div>
{/foreach}
</div>
{/if}

{if $entry->isSetOption("NO_COMMENT") == false}
<div id="comments">
{foreach from=$comments item=comment}
	<div class="comment" id="CO{$comment->date}">
		<h3>
		{if $comment->homepage != ""}
			<span class="box_title">Comment by <a href="{$comment->homepage|escape}">{$comment->name|escape}</a></span>
		{elseif $comment->email != ""}
			<span class="box_title">Comment by <a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a></span>
		{else}
			<span class="box_title">Comment by {$comment->name|escape}</span>
		{/if}
			<span class="hide">| posted on</span>
			<span class="box_header">{$comment->date|date_format:"%Y/%m/%d %p %I:%M"}</span>
		</h3>
		<div class="box_body">
		{$comment->getBody()}
		</div>
	</div>
{/foreach}
</div>

<form action="" method="post" class="postform">
	<h3>Post a comment</h3>
	<div class="postform_leftside"><p>
		<input type="hidden" name="blogid" value="{$entry->entryId}" />
		Name<br /><input type="text" name="name" value="{$w_name|escape}" tabindex="1" /><br />
		Email Address<br /><input type="text" name="email" value="{$w_email|escape}" tabindex="2" /><br />
		URL<br /><input type="text" name="url" value="{$w_url|default:"http://"|escape}" tabindex="3" /><br /><br />
		<input type="submit" class="button" value="Post" tabindex="5" accesskey="s" />
	</p></div>
	<div class="postform_rightside"><p>
		<textarea name="body" rows="10" cols="40" tabindex="4"></textarea>
	</p></div>
</form>
{/if}

</div>

{include file="footer.tpl"}
