{include file="header.tpl"}

<div class="entry">
<h2>{$entry.title}</h2>
{$entry.body}
<p class="posted">{$entry.date|date_format:"%B %d, %Y"}</p>
</div>

<div class="trackbacks">

<div class="trackback_url">TrackBack URL of this entry: {$baseurl}/trackback.php?blogid={$entry.id}</div>
<a name="trackback"></a>
</div>

<div class="trackback_entry">
     <form action="sendping.php" method="post">
     Trackback URL to send trackback ping: <br /> 
     <input type="text" name="trackback_url" value="http://"><br>
     <input type="hidden" name="mode" value="post">
     <input type="hidden" name="blogid" value={$entry.id}>
     <input type="submit" value="Send Ping"><br />
</form>

</div>
{include file="footer.tpl"}
