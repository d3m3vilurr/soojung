{include file="header.tpl"}
{include file="menu.tpl"}

<form action="{$baseurl}/admin.php" method="post">
Blog Name:
<input type="text" name="blogname" value="{$blog_name}">
<br />

Blog Description:
<input type="text" name="desc" value="{$blog_desc}">
<br />

Blog URL:
<input type="text" name="url" value="{$baseurl}">
<br />

Blog Skin:
<select name="skin">
{foreach from=$templates item=template}
<option value="{$template}" {if $blog_skin == $template}selected{/if}>{$template}
{/foreach}
</select>
<br />
<br />

Admin Name: <input type="text" name="adminname" value="{$admin_name}">
<br />

Admin Email: <input type="text" name="email" value="{$admin_email}">
<br />

Admin Password: <input type="password" name="password"> If you want change the password, input new password.
<br />
<br />

entries per page:
<input type="text" name="perpage" value="{$blog_entries_per_page}">
<br />

fancy URL:
<input type="checkbox" name="fancyurl"
{if $blog_fancyurl == true}
	checked="on">
{else}
	>
{/if}
<br />
 notify email:
<input type="checkbox" name="notify"
{if $blog_notify == true}
	checked="on">
{else}
	>
{/if}
<br />
<input type="hidden" name="mode" value="config_update">
<input type="submit" value="update">
</form>

{include file="footer.tpl"}