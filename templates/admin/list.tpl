{include file="header.tpl"}
{include file="menu.tpl"}

{foreach from=$entry_structs item=entry_struct}
	<a href="{$baseurl}/post.php?blogid={$entry_struct.entry.id}">edit</a>
	<a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry_struct.entry.id}">delete</a>
	{$entry_struct.entry.title}
	<a href="{$baseurl}/sendping.php?blogid={$entry_struct.entry.id}">send trackback ping</a>
	<br />
	
	{foreach from=$entry_struct.comments item=comment}
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/admin.php?mode=delete&file={$comment.filename}">delete</a>
		{$comment.body|strip_tags} - {$comment.name}
		<br />
	{/foreach}

	{foreach from=$entry_struct.trackbacks item=trackback}
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/admin.php?mode=delete&file={$trackback.filename}">delete</a>
		{$trackback.title}
		<br />
	{/foreach}

{/foreach}

{include file="footer.tpl"}