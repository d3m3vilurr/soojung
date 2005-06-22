{include file="header.tpl"}

			<h2><a href="{$baseurl}">Blog</a></h2>
			<div id="nav"></div>
			<div id="clear"></div>
			<div id="entries">
<div class="entry">
	<h3>{$entry->title|escape}</h3>
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
{if $entry->isSetOption("NO_TRACKBACK") == false}
<div id="trackbacks">
	<a name="trackback"></a>
	<h3>Trackbacks on this entry:</h3>
	<p>
		TrackBack URL: {$baseurl}/trackback.php?blogid={$entry->entryId}
	</p>
{foreach from=$trackbacks item=trackback}
	<div class="trackback">
		<a name="TB{$trackback->date}"></a>
		<p class="blue">
			Trackback from <a href="{$trackback->url|escape}">{$trackback->url|escape}</a>
		</p>
		<p>{$trackback->title|escape}:</p>
		<p>
			{$trackback->getExcerpt()|strip_tags|escape}
		</p>
	</div>
{/foreach}
</div>
{/if}

{if $entry->isSetOption("NO_COMMENT") == false}
<div id="comments">
	<a name="comment"></a>
	<h3>Comments on this entry:</h3>
{foreach from=$comments item=comment}
	<div class="comment">
		<a name="CO{$comment->date}"></a>
		<p class="blue">
			Left on {$comment->date|date_format:"%B %d, %Y %I:%M %p"} by
			{if $comment->homepage != ""}
				<a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
			{elseif $comment->email != ""}
				<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
			{else}
				{$comment->name|escape}
			{/if}
		</p>
		<p>
			{$comment->getBody()}
		</p>
	</div>
{/foreach}
	<form id="commentform" action="" method="post">
		<p>
			<input type="hidden" name="blogid" value="{$entry->entryId}" />
		</p>
		<p class="label">Author (<span class="red">*</span>):</p>
		<p class="input"><input type="text" name="name" value="{$w_name|escape}" /></p>
		<p class="label">E-mail:</p>
		<p class="input"><input type="text" name="email" value="{$w_email|escape}" /></p>
		<p class="label">URL:</p>
		<p class="input"><input type="text" name="url" value="{$w_url|default:"http://"|escape}" /></p>
		<p class="label">Comment (<span class="red">*</span>):</p>
		<p class="input"><textarea name="body" rows="5" cols="40"></textarea></p>
		<p class="label">&nbsp;</p>
		<p class="input"><input type="submit" value="Post" /></p>
	</form>
</div>
{/if}
			</div>

{include file="footer.tpl"}
