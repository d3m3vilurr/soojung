{include file="header.tpl"}
{include file="menu.tpl"}

<div id="bookmark">

<form action="{$baseurl}/bookmark.php" method="post">
	Url : 
	<input type="text" name="url" value="http://"> 
	<br />
	
	Description : 
	<input type="text" name="desc" value="">
	<br />

	<input type="submit" name="mode" value="post">
</form>

{foreach from=$bookmarks item=bookmark name=bookmark}
<strong>
	<a href="{$baseurl}/bookmark.php?mode=delete&amp;url={$bookmark.url|escape:'url'|escape}">delete</a>
{if $smarty.foreach.bookmark.first}
	<span style="color:white;">&uArr;</span>
{else}
	<a href="{$baseurl}/bookmark.php?mode=move&amp;offset=-1&amp;url={$bookmark.url|escape:'url'|escape}">&uArr;</a>
{/if}
{if $smarty.foreach.bookmark.last}
	<span style="color:white;">&dArr;</span>
{else}
	<a href="{$baseurl}/bookmark.php?mode=move&amp;offset=1&amp;url={$bookmark.url|escape:'url'|escape}">&dArr;</a>
{/if}
</strong>
<a href="{$bookmark.url|escape}">{$bookmark.desc|escape}</a><br />
{/foreach}


</div>
{include file="footer.tpl"}
