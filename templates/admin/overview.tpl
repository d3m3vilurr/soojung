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
<a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a>
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
<a href="{$comment->getHref()}">{$comment->getBody()|strip_tags:false|truncate:40}</a>
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
<a href="{$trackback->getHref()}">{$trackback->url}</a>
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
Entry Count: <b>100</b><br />
Comment Count: <b>123</b><br />
Trackback Count: <b>10</b><br />
Clear cache
</div>

</div>

{include file="footer.tpl"}