{include file="header.tpl"}

<h2>{$entry->date|date_format:"%B %d, %Y"}</h2>
<div class="post">
	<h3 class="storytitle">{$entry->title|escape}</h3>
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
			<a class="plink" href="{$entry->getHref()|escape}#comment">Comments ({$entry->getCommentCount()})</a>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<a class="plink" href="{$entry->getHref()|escape}#trackback">Trackbacks ({$entry->getTrackbackCount()})</a>
		{/if}
	</div>

{if $entry->isSetOption("NO_TRACKBACK") == false}
<h2 id="trackbacks">trackbacks</h2>
<a name="trackback"></a>
<p>
	The URI to TrackBack this entry is: {$baseurl}/trackback.php?blogid={$entry->entryId}
</p>
<ol id="trackbacklist">
{foreach from=$trackbacks item=trackback}
	<li>
		<a name="TB{$trackback->date}"></a>
		<p>
			Trackback from <a href="{$trackback->url|escape}">{$trackback->url|escape}</a>
		</p>
		<p>{$trackback->title|escape}:</p>
		<p>
			{$trackback->getExcerpt()|strip_tags|escape}
		</p>
	</li>
{/foreach}
</ol>
{/if}

{if $entry->isSetOption("NO_COMMENT") == false}
<h2 id="comments">Comments</h2>
<a name="comment"></a>

<ol id="commentlist">
{foreach from=$comments item=comment}
	<li>
		<a name="CO{$comment->date}"></a>
			
		<p>
			{$comment->getBody()}
		</p>
		<p>
			<cite>Comment by
			{if $comment->homepage != ""}
				<a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
			{elseif $comment->email != ""}
				<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
			{else}
				{$comment->name|escape}
			{/if}
			&#8212; {$comment->date|date_format:"%m/%d/%Y @ %I:%M %p"}
			</cite>
		</p>
	</li>
{/foreach}
</ol>

<h2>Leave a Comment</h2>

<form id="commentform" action="" method="post">
	<p>
		<input type="hidden" name="blogid" value="{$entry->entryId}" />
		<input id="author" class="text" type="text" name="name" value="{$w_name|escape}" />
		<label for="author">Name</label>
	</p>
	<p>
		<input id="email" class="text" type="text" name="email" value="{$w_email|escape}" />
		<label for="email">Email</label>
	</p>
	<p>
		<input id="url" class="text" type="text" name="url" value="{$w_url|default:"http://"|escape}" />
		<label for="email">URL</label>
	</p>
	<p>
		<label>Your Comment</label>
		<br />
		<textarea id="comment" name="body" rows="5" cols="40"></textarea>
	</p>
	<p>
		<input type="submit" value="Say it!" />
	</p>
</form>
{/if}

</div>

</div>

{include file="footer.tpl"}
