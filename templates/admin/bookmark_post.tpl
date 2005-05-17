{include file="header.tpl"}
{include file="menu.tpl"}

<div id="bookmark">

<form action="{$baseurl}/bookmark_post.php" method="post">
	Name :
	<input type="text" name="name" value="{$name}" />
	<br />

	Url : 
	<input type="text" name="url" value="{$url}" /> 
	<br />
	
	Description :
	<input type="text" name="desc" value="{$desc}" />
	<br />

	Image URL :
	<input type="text" name="image" value="{$image}" />
	<br />

	<input type="hidden" name="number" value="{$number}" />
	<input type="submit" name="mode" value="post" />
</form>

</div>
{include file="footer.tpl"}
