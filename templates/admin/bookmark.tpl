{include file="header.tpl"}
{include file="menu.tpl"}

<div id="bookmark">
<a href="bookmark_post.php">add new bookmark</a>
<form method="post">
<table>
	<tr>
	<td>name</td> 
	<td>url</td>
	<td>image</td>
	<td>description</td>
	<td colspan=2>action</td>
	<td>move</td>
	<td>X</td>
	</tr>
{foreach from=$bookmarks item=bookmark name=bookmark}
	<tr>
	<td>{$bookmark->name}</td>
	<td>{$bookmark->url}</td>
	<td><img src="{$bookmark->image}" /></td>
	<td>{$bookmark->desc}</td>	
	<td><a href="{$baseurl}/bookmark_post.php?number={$bookmark->number}">Edit</a></td>
	<td><a href="{$baseurl}/bookmark.php?mode=delete&amp;number={$bookmark->number}">Delete</a></td>
	<td>
	{if $smarty.foreach.bookmark.first}
		<span style="color:white;">&uArr;</span>
	{else}
		<a href="{$baseurl}/bookmark.php?mode=move&amp;offset=-1&amp;number={$bookmark->number}">&uArr;</a>
	{/if}
	{if $smarty.foreach.bookmark.last}
		<span style="color:white;">&dArr;</span>
	{else}
		<a href="{$baseurl}/bookmark.php?mode=move&amp;offset=1&amp;number={$bookmark->number}">&dArr;</a>
	{/if}
	</td>
	<td><input type="checkbox" name="delchk[{$bookmark->number}]" /></td>
	</tr>
{/foreach}
</table>
<input type="hidden" name="mode" value="delete" />
<input type="submit" value="delete selected" />	
</form>
</div>
{include file="footer.tpl"}
