{include file="header.tpl"}

<h2>{$entry->date|date_format:"%B %d, %Y"}</h2>
<div class="post">
	<h3 class="storytitle">{$entry->title}</h3>
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
			<a class="plink" href="{$entry->getHref()}#comment">Comments ({$entry->getCommentCount()})</a>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<a class="plink" href="{$entry->getHref()}#trackback">Trackbacks ({$entry->getTrackbackCount()})</a>
		{/if}
	</div>

<h2 id="trackbacks">trackbacks</h2>
<p>
	The URI to TrackBack this entry is: {$baseurl}/trackback.php?blogid={$entry->entryId}
</p>

<form id="trackback-entry" action="sendping.php" method="post">
	<p>
		<input type="hidden" name="mode" value="post" />
		<input type="hidden" name="blogid" value="{$entry->entryId}" />
	</p>
	<p>Trackback URL to send trackback ping:</p> 
	<p><input type="text" name="trackback_url" value="http://" size="40" /></p>
	<p>Remote Blog's encoding:</p>
	<p><input type="text" name="encoding" value="UTF-8" /></p>
	<p><input type="submit" value="Send Ping" /></p>
</form>

</div>
</div>

{include file="footer.tpl"}
