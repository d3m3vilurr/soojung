{include file="header.tpl"}
<div id="entries">
	<div class="entry">
		<h3>{$entry->date|date_format:"%B %d, %Y"}</h3>
		<h4>{$entry->title}</h4>
		<div class="post">
			{$entry->getBody()}
		</div>
		<div class="info">
			Posted at {$entry->date|date_format:"%p %I:%M"}
		</div>
		<div class="links">
		{if $entry->isSetOption("NO_COMMENT") == false}
			<span class="commentslink"><a href="{$entry->getHref()}#comment"><span></span>Comments ({$entry->getCommentCount()})</a></span>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<span class="trackbackslink"><a href="{$entry->getHref()}#trackback"><span></span>Trackbacks ({$entry->getTrackbackCount()})</a></span>
		{/if}
		</div>
	</div>
	<div class="clear"></div>
	<div id="trackbacks">
		<a name="trackback"></a>
		<h3>TrackBack URL of this entry:</h3>
		<p>{$baseurl}/trackback.php?blogid={$entry->entryId}</p>
	</div>
	
	<div class="trackback_entry">
		<form action="sendping.php" method="post">
			<p>
				<input type="hidden" name="mode" value="post" />
				<input type="hidden" name="blogid" value={$entry->entryId} />
			</p>
			<p>Trackback URL to send trackback ping:</p> 
			<p><input type="text" name="trackback_url" value="http://" class="text" /></p>
			<p>Remote Blog's encoding :
				<select name="encoding">
					<option value="UTF-8" selected="selected">UTF-8</option>
					<option value="CP949">cp949 (euc-kr)</option>
				</select>
			</p>
			<p><input type="submit" value="Send Ping" class="button" /></p>
		</form>
	</div>
</div>

{include file="footer.tpl"}
