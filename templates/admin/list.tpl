{include file="header.tpl"}
{include file="menu.tpl"}

{foreach from=$entries item=entry}
	<a href="{$baseurl}/post.php?blogid={$entry->entryId}">edit</a>
	<a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete?');">delete</a>
	{$entry->title}
	<a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">send trackback ping</a>
	<br />
	
	{foreach from=$entry_struct.comments item=comment}
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/admin.php?mode=delete&file={$comment.filename}" onclick="return confirm('Are you sure want to delete?');">delete</a>
		{$comment.body|strip_tags} - {$comment.name}
		<br />
	{/foreach}

	{foreach from=$entry_struct.trackbacks item=trackback}
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/admin.php?mode=delete&file={$trackback.filename}" onclick="return confirm('Are you sure want to delete?');">delete</a>
		{$trackback.title}
		<br />
	{/foreach}

{/foreach}

{include file="footer.tpl"}
