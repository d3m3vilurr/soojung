{include file="header.tpl"}

<form name="loginForm" action="{$baseurl}/admin.php" method="post">
Password: <input type="password" name="password">
<input type="hidden" name="mode" value="login">
<input type="submit" value="Log In">
</form>

<script type="text/javascript">
<!--
document.loginForm.password.focus();
//-->
</script>


{include file="footer.tpl"}