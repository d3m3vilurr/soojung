{include file="header.tpl"}
{include file="menu.tpl"}

<form action="{$baseurl}/post.php" method="post">
	Title: <br />
	<input type="text" name="title" value="{$title}">
	<br />

	Body: <br/>
	<textarea name="body" rows="20" cols="80">{$body}</textarea>
	<br />

	Date: <br />
	<input type="text" name="date" value="{$date}">
	<br />

	Category: <br />
	<input type="text" name="category" value="{$category}">
	<br />

	{if $id != null}
	  <input type="hidden" name="id" value="{$id}">
	{/if}

	<input type="hidden" name="mode" value="post">
	<input type="submit" value="Post">
</form>

{include file="footer.tpl"}