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

{foreach from=$bookmarks item=bookmark}
	<a href="{$baseurl}/bookmark.php?mode=delete&amp;url={$bookmark.url}">delete</a> 
	<a href="{$bookmark.url}">{$bookmark.desc}</a><br />
{/foreach}


</div>
{include file="footer.tpl"}
