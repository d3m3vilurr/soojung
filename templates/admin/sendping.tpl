{include file="header.tpl"}
{include file="menu.tpl"}

<div id="sendping">
<h2>{$entry->title}</h2>
{$entry->getBody()}
<p class="posted">{$entry->date|date_format:"%B %d, %Y"}</p>

<div class="trackbacks">

<div class="trackback_url">TrackBack URL of this entry: {$baseurl}/trackback.php?blogid={$entry->entryId}</div>
<a name="trackback"></a>
</div>

<div class="trackback_entry">
     <form action="sendping.php" method="post">
     Trackback URL to send trackback ping: <br /> 
     <input type="text" name="trackback_url" value="http://" /><br /><br />

     Remote Blog's encoding :
     <select name="encoding">
         <option value="UTF-8" selected="selected">UTF-8</option>
         <option value="CP949">cp949 (euc-kr)</option>
     </select> 
     or 
     <input type="text" name="encoding_input" value="" /> 
     <br />
     <input type="hidden" name="mode" value="post" />
     <input type="hidden" name="blogid" value={$entry->entryId} />
     <input type="submit" value="Send Ping"><br />
</form>

</div>
</div>
{include file="footer.tpl"}
