{include file="header.tpl"}
{include file="menu.tpl"}

<div id="overview">
    <table width="100%">
      <tr>
	
	<td width="50%">
	  <h2>Recent Posts</h2>
	  <table>
	    {foreach from=$recent_entries item=entry}
	    <tr class="row">
	      <td>
		<div class="entry_title">
		  <a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a>
		</div>
	      </td>
	      <td width="1%">
		<a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete this entry?\nTitle: {$entry->title}');">X</a>
	      </td>
	      <td width="1%">
		<a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">Ping</a>
	      </td>
	    </tr>
	    {/foreach}
	  </table>
	</td>
	
	<td width="50%">
	  <h2>Recent Comments</h2>
	  <table>
	    {foreach from=$recent_comments item=comment}
	    <tr class="row">
	      <td>
		{$comment->name}
	      </td>
	      <td>
		<div class="entry_title">
		  <a href="{$comment->getHref()}">{$comment->getBody()|strip_tags:false|truncate:40}</a>
		</div>
	      </td>
	      <td width="1%">
		<a href="{$baseurl}/admin.php?mode=delete&file={$comment->filename}" onclick="return confirm('Are you sure want to delete this comment?\nAuthor: {$comment->name}');">X</a>
	      </td>
	    </tr>
	    {/foreach}
	  </table>
	</td>
      </tr>
      
      <tr>
	<td width="50%">
	  <h2>Recent Trackback</h2>
	  <table>
	    {foreach from=$recent_trackbacks item=trackback}
	    <tr class="row">
	      <td>
		<div class="entry_title">
		  <a href="{$trackback->getHref()}">{$trackback->url}</a>
		</div>
	      </td>
	      <td width="1%">
		<a href="{$baseurl}/admin.php?mode=delete&file={$trackback->filename}" onclick="return confirm('Are you sure want to delete this trackback?\nURL: {$trackback->url}');">X</a>
	      </td>
	    </tr>
	    {/foreach}
	  </table>
	</td>
	
	<td width="50%">
	  <h2>Blog Stat</h2>
	  <table>
	    <tr>
	      <td>Entry Count:</td>
	      <td><b>{$entry_count}</b></td>
	    </tr>
	    <tr>
	      <td><a href="{$baseurl}/admin.php?mode=clear_cache">Clear cache</a></td>
	      <td></td>
	    </tr>
	    <tr>
	      <td><a href="{$baseurl}/admin.php?mode=clear_referer">Clear referer</a></td>
	      <td></td>
	    </tr>
	  </table>
	</td>
      </tr>
    </table>
</div>

{include file="footer.tpl"}