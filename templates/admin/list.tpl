{include file="header.tpl"}
{include file="menu.tpl"}

<div id="list">

<div class="submenu">
<a href="{$baseurl}/admin.php?mode=list">show all</a> -
<a href="{$baseurl}/admin.php?mode=list&amp;flag=static">show only static</a> -
show only 
<select name="category" onchange="return changeCategory(this)">
{foreach name=categories from=$categories item=cate}
	<option value="{$cate->name|urlencode}" {if $flag|urldecode == $cate->name}selected{/if}>{$cate->name}</option>
{/foreach}
</select>
category
</div>

<p class="navbar">
{if $prev_page_link != ""}
	<a href="{$prev_page_link|escape}">&laquo; prev</a>
{/if}
{if $prev_page_link != "" && $next_page_link != ""}
	&nbsp;&nbsp;
{/if}
{if $next_page_link != ""}
	<a href="{$next_page_link|escape}">next &raquo;</a>
{/if}
</p>

<table class="entries">
    <tr>
        <th class="subject">Subject</th>
        <th class="date">Date</td>	
        <th class="comments">Comment</th>
        <th class="trackbacks">Trackback</th>
        <th class="trackback_ping">Ping</th>
        <th class="delete">Delete</th>
    </tr>
    
    {foreach from=$entries item=entry}
    <tr class="entry">
        <td><div class="subject"><a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a></div></td>
        <td class="date">{$entry->date|date_format:"%y/%m/%d %H:%M"}</td>
        <td class="comments">
            {if $entry->isSetOption("NO_COMMENT") == false && $entry->getCommentCount() != 0}
            <a href="#none" onclick="return fold_sidebar('CO_{$entry->entryId}');">({$entry->getCommentCount()})</a>
            {else}
            (0)
            {/if}
        </td>
        <td class="trackbacks">
            {if $entry->isSetOption("NO_TRACKBACK") == false && $entry->getTrackbackCount() != 0}
            <a href="#none" onclick="return fold_sidebar('TB_{$entry->entryId}');">({$entry->getTrackbackCount()})</a>
            {else}
            (0)
            {/if}
        </td>
        <td class="trackback_ping"><a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">Ping</a></td>
        <td class="delete"><a href="{$baseurl}/admin.php?mode=delete_entry&amp;blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete this entry?\nTitle: {$entry->title}');">X</a></td>
    </tr>
    
    {if $entry->isSetOption("NO_COMMENT") == false && $entry->getCommentCount() != 0}
    <tr><td colspan="6" id="CO_{$entry->entryId}" class="div_hide">
        {foreach from=$entry->getComments() item=comment}
        <table class="comment"><tr>
            <td><div class="subject">{$comment->getBody()|strip_tags|substring:50}</div></td>
            <td class="date">{$comment->date|date_format:"%y/%m/%d %H:%M"}</td>
            <td class="comments">&nbsp;</td>
            <td class="trackbacks">&nbsp;</td>
            <td class="trackback_ping">&nbsp;</td>
            <td class="delete"><a href="{$baseurl}/admin.php?mode=delete&amp;file={$comment->filename}" onclick="return confirm('Are you sure want to delete this comment?\nAuthor: {$comment->name}');">X</a></td>
        </tr></table>
        {/foreach}
    </td></tr>
    {/if}

    {if $entry->isSetOption("NO_TRACKBACK") == false && $entry->getTrackbackCount() != 0}
    <tr><td colspan="6" id="TB_{$entry->entryId}" class="div_hide">
        {foreach from=$entry->getTrackbacks() item=trackback}
        <table class="trackback"><tr>
            <td><div class="subject">{$trackback->getExcerpt()|strip_tags|substring:50}</div></td>
            <td class="date">{$trackback->date|date_format:"%y/%m/%d %H:%M"}</td>
            <td class="comments">&nbsp;</td>
            <td class="trackbacks">&nbsp;</td>
    	    <td class="trackback_ping">&nbsp;</td>
            <td class="delete"><a href="{$baseurl}/admin.php?mode=delete&amp;file={$trackback->filename}" onclick="return confirm('Are you sure want to delete this trackback?\nURL: {$trackback->url}');">X</a></td>
        </tr></table>
        {/foreach}
    </td></tr>
    {/if}
    {/foreach}
</table>

</div>

{include file="footer.tpl"}
