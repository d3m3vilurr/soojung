{include file="header.tpl"}

			<h2><a href="{$baseurl}">Blog</a></h2>
			<div id="nav"></div>
			<div id="clear"></div>
			<div id="entries">
<div class="entry">
	<h3>{$entry->title}</h3>
	<div class="entrybody">
		{$entry->getBody()}
	</div>
	<p>
		<span class="blue">{$entry->date|date_format:"%B %d, %Y"}</span>
		{if $entry->isSetOption("NO_COMMENT") == false}
			<span>Comments [<a class="plink" href="{$entry->getHref()}#comment">{$entry->getCommentCount()}</a>]</span>
		{/if}
		{if $entry->isSetOption("NO_TRACKBACK") == false}
			<span>Trackbacks [<a class="plink" href="{$entry->getHref()}#trackback">{$entry->getTrackbackCount()}</a>]</span>
		{/if}
	</p>
</div>

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
		<p><input type="text" name="trackback_url" value="http://" /></p>
		<p>Remote Blog's encoding : 
			<select name="encoding">
				<option value="UTF-8" selected="selected">UTF-8</option>
				<option value="CP949">cp949 (euc-kr)</option>
			</select>
		</p>
		<p><input type="submit" value="Send Ping" /></p>
	</form>
</div>
			</div>

{include file="footer.tpl"}
