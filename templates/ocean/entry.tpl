{include file="header.tpl"}
<div id="entries">
	<div class="entry">
		{$entry->date|date_format:"%p %I:%M"}
		<h3>{$entry->date|date_format:"%B %d, %Y"}</h3>
		<h4>{$entry->title|escape}</h4>
		<div class="post">
			{$entry->getBody()}
		</div>
		<div class="info">
			Posted at {$entry->date|date_format:"%p %I:%M"}
		</div>
		<div class="links">
		{if $entry->isSetOption("NO_COMMENT") == false}
			<span class="commentslink"><a href="{$entry->getHref()|escape}#comment"><span></span>Comments ({$entry->getCommentCount()})</a></span>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<span class="trackbackslink"><a href="{$entry->getHref()|escape}#trackback"><span></span>Trackbacks ({$entry->getTrackbackCount()})</a></span>
		{/if}
		</div>
	</div>
	<div class="clear"></div>
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
			<p>Trackback from <a href="{$trackback->url|escape}">{$trackback->url|escape}</a></p>
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
			<div class="info">
				Left by <span class="author">
				{if $comment->homepage != ""}
					<a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
				{elseif $comment->email != ""}
					<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
				{else}
					{$comment->name|escape}
				{/if}
				</span> at {$comment->date|date_format:"%B %d, %Y %I:%M %p"}
			</div>
			<p class="post">
				{$comment->getBody()}
			</p>
		</div>
	{/foreach}
		<h3>Your comment:</h3>
		<form id="commentform" action="" method="post">
			<div>
				<input type="hidden" name="blogid" value="{$entry->entryId}" />
			</div>
			<div class="label">Author (<span class="red">*</span>):</div>
			<div class="input"><input id="author" type="text" name="name" value="{$w_name|escape}" class="fixed" /></div>
			<div class="label">E-mail:</div>
			<div class="input"><input id="authorEmail" type="text" name="email" value="{$w_email|escape}" class="fixed" /></div>
			<div class="label">URL:</div>
			<div class="input"><input id="authorURL" type="text" name="url" value="{$w_url|default:"http://"|escape}" class="fixed" /></div>
			<div class="label">Comment (<span class="red">*</span>):</div>
			<div class="input"><textarea id="commentText" name="body" rows="7" cols="55" class="fixed"></textarea></div>
			<div class="label">&nbsp;</div>
			<div class="input"><input type="submit" value="Post" class="button" /></div>
		</form>
	</div>
	{/if}

</div>

{include file="footer.tpl"}
