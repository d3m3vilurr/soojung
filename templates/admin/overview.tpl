{include file="header.tpl"}
{include file="menu.tpl"}

<div id="overview">

<div class="content_entry">
<h2>Recent Posts</h2>
<table>
{foreach from=$recent_entries item=entry}
<tr>
<div class="entry_item">
<td>
<div class="entry_title">
<a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a>
</div>
</td>
<td>
<a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete?');">X</a>
</td>
<td>
<a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">Ping</a>
</td>
</div>
</tr>
{/foreach}
</table>
</div>

<div class="content_comment">
<h2>Recent Comments</h2>
<table>
	{foreach from=$recent_comments item=comment}
	<tr>
	<div class="comment_item">
		<td>
			{$comment->name}
		</td>
		<td>
			<div class="entry_title">
			<a href="{$comment->getHref()}">{$comment->getBody()|strip_tags:false|truncate:40}</a>
			</div>
		</td>
		<td>
			<a href="{$baseurl}/admin.php?mode=delete&file={$comment->filename}" onclick="return confirm('Are you sure want to delete?');">X</a>
		</td>
	</div>
	</tr>
	{/foreach}
</table>
</div>

<div class="content_trackback">
<h2>Recent Trackback</h2>
<table>
	{foreach from=$recent_trackbacks item=trackback}
	<tr>
	<div class="trackback_item">
		<td>
			<div class="entry_title">
			<a href="{$trackback->getHref()}">{$trackback->url}</a>
			</div>
		</td>
		<td>
			<a href="{$baseurl}/admin.php?mode=delete&file={$trackback->filename}" onclick="return confirm('Are you sure want to delete?');">X</a>
		</td>
	</div>
	</tr>
	{/foreach}
</table>
</div>

<div class="content_info">
<h2>Blog Stat</h2>
<table>
	<tr>
		<td>Entry Count:</td>
		<td><b>100</b></td>
	</tr>
	<tr>
		<td>Comment Count:</td>
		<td><b>123</b></td>
	</tr>
	<tr>
		<td>Trackback Count:</td>
		<td><b>10</b></td>
	</tr>
	<tr>
		<td>Clear cache</td>
		<td></td>
	</tr>
</table>
</div>

</div>

{include file="footer.tpl"}