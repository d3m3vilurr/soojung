{include file="header.tpl"}

<form action="{$baseurl}/admin.php" method="post">
password: <input type="password" name="password">
<input type="hidden" name="mode" value="login">
<input type="submit" value="login">
</form>

{include file="footer.tpl"}