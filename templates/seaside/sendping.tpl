{include file="header.tpl"}

<div id="contents">
	<div class="entry">
		<h2>{$entry->title} <div class="category">{$entry->category->name}</div> <div class="date">{$entry->date|date_format:"%B %d, %Y %I:%M %p"}</div></h2>
		<p class="body">{$entry->getBody()}</p>
	</div>

<div class="trackbacks">

<div class="trackback_url">TrackBack URL of this entry: {$baseurl}/trackback.php?blogid={$entry->entryId}</div>
<a name="trackback"></a>
</div>

<div class="trackback_entry">
     <form action="sendping.php" method="post">
     Trackback URL to send trackback ping: <br /> 
     <input type="text" name="trackback_url" value="http://"><br /><br />
     
     Remote Blog's encoding : <input type="text" name="encoding" value="UTF-8"><br />
     <input type="hidden" name="mode" value="post">
     <input type="hidden" name="blogid" value={$entry->entryId}>
     <input type="submit" value="Send Ping"><br />
</form>

</div>
</div>

{include file="footer.tpl"}
