{include file="header.tpl" subtitle=$entry->title}

<div class="entry thisentry">
<h2>
	<span class="box_title">{$entry->title}</span> <span class="hide">|</span>
	<span class="box_header">{$entry->category->name|escape} | {$entry->date|date_format:"%Y/%m/%d %p %I:%M"}</span>
</h2>
<div class="box_body">
{$entry->getBody()}
</div>

<div id="trackbacks">
	<p class="trackback_url"><b>TrackBack URL:</b> {$baseurl}/trackback.php?blogid={$entry->entryId}</p>
	<form action="sendping.php" method="post" class="postform">
		<h3>Send trackback ping</h3>
		<div class="box_body">
			<input type="hidden" name="mode" value="post" />
			<input type="hidden" name="blogid" value="{$entry->entryId}" />
			Trackback URL to send trackback ping:
			<input type="text" name="trackback_url" value="http://" /><br />
			Remote Blog's encoding:
			<input type="text" name="encoding" value="UTF-8" /><br />
			<input type="submit" value="Send Ping" />
		</div>
	</form>
</div>

</div>

{include file="footer.tpl"}
