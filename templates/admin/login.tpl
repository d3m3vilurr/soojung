{include file="header.tpl"}
{if $original_dst eq ""}
<form name="loginForm" action="{$baseurl}/admin.php" method="post">
{else}
<form name="loginForm" action="{$original_dst}" method="post">
{/if}
Password: <input type="password" name="password">
<input type="hidden" name="mode" value="login">
<input type="submit" value="Log In">
{foreach from=$hidden_attr item=attr}
	 <input type="hidden" name="{$attr.name}" value="{$attr.value}" />
{/foreach}
</form>

<script type="text/javascript">
<!--
document.loginForm.password.focus();
//-->
</script>


{include file="footer.tpl"}