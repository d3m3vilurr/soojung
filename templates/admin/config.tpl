{include file="header.tpl"}
{include file="menu.tpl"}

<div id="config">

<form action="{$baseurl}/admin.php" method="post">

<div class="row">
<span class="label">Blog Name:</span>
<span class="formw"><input type="text" name="blogname" value="{$blog_name}"></span>
</div>

<div class="row">
<span class="label">Blog Description:</span>
<span class="formw"><input type="text" name="desc" value="{$blog_desc}"></span>
</div>

<div class="row">
<span class="label">Blog URL:</span>
<span class="formw"><input type="text" name="url" value="{$baseurl}"></span>
</div>

<div class="row">
<span class="label">Blog Skin:</span>
<span class="formw">
<select name="skin">
{foreach from=$templates item=template}
<option value="{$template}" {if $blog_skin == $template}selected{/if}>{$template}
{/foreach}
</select>
</span>
</div>

<div class="row">
<span class="label">Entries License:</span>
<span class="formw">
<select name="license">
<option value="none" {if $license == "none"}selected{/if}>None (All rights reserved)</option>
<option value="by" {if $license == "by"}selected{/if}>Attribution License</option>
<option value="by-nd" {if $license == "by-nd"}selected{/if}>Attribution-NoDerivs License</option>
<option value="by-nc-nd" {if $license == "by-nc-nd"}selected{/if}>Attribution-NonCommercial-NoDerivs License</option>
<option value="by-nc" {if $license == "by-nc"}selected{/if}>Attribution-NonCommercial License</option>
<option value="by-nc-sa" {if $license == "by-nc-sa"}selected{/if}>Attribution-NonCommercial-ShareAlike License</option>
<option value="by-sa" {if $license == "by-sa"}selected{/if}>Attribution-ShareAlike License</option>
</select>
</span>
</div>

<div class="row">
<span class="label">Admin Name:</span>
<span class="formw"><input type="text" name="adminname" value="{$admin_name}"></span>
</div>

<div class="row">
<span class="label">Admin Email:</span>
<span class="formw"><input type="text" name="email" value="{$admin_email}"></span>
</div>

<div class="row">
<span class="label">Admin Password:</span>
<span class="formw"><input type="password" name="password"> <i>If you want change the password, input new password.</i></span>
</div>

<div class="row">
<span class="label">entries per page:</span>
<span class="formw"><input type="text" name="perpage" value="{$blog_entries_per_page}"></span>
</div>

<div class="row">
<span class="label">fancy URL:</span>
<span class="formw">
<input type="checkbox" name="fancyurl"
{if $blog_fancyurl == true}
	checked="on">
{else}
	>
{/if}
</span>
</div>

<div class="row">
<span class="label">notify email:</span>
<span class="formw">
<input type="checkbox" name="notify"
{if $blog_notify == true}
	checked="on">
{else}
	>
{/if}
</span>
</div>

<input type="hidden" name="mode" value="config_update">

<div class="row">
<span class="label"><input type="submit" value="update"></span>
</div>

</form>

</div>
{include file="footer.tpl"}
