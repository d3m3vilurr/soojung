{include file="header.tpl"}

<form action="{$baseurl}/admin.php" method="post">
Password: <input type="password" name="password">
<input type="hidden" name="mode" value="login">
<input type="submit" value="Log In">
</form>

{include file="footer.tpl"}