{include file="header.tpl"}
<div id="entries">
    <div class="bar">
        <span class="classes">
        <b>
	    {if $view == "index"}
		    All Entries
            {php}$entry_count = Entry::getEntryCount();{/php}
    	{elseif $view == "archive"}
	    	Archive
            {php}$entry_count = 0;{/php}
    	{elseif $view == "category"}
	    	Category
            {php}
                $category = new Category($_GET['category']);
                $entry_count = $category->getEntryCount();
            {/php}
    	{elseif $view == "search"}
	    	Search
            {php}$entry_count = 0;{/php}
    	{/if}
        </b>
        </span>
        {foreach from=$entries item=entry}
            {if $view == "archive" || $view == "search"} 
                {php}$entry_count++;{/php}
            {/if}
            {php}$count++;{/php}
        {/foreach}
        <span class="result">
            Results <b>
            {php}
                $temp = empty($_GET['page'])?0:$_GET['page']-1;
                echo ($temp)*$count+1;
            {/php}
            </b> - <b>
            {php}
                echo ($temp)*$count+$count;
            {/php}
            </b> of about <b>
            {php}
                echo $entry_count;
            {/php}
            </b> for <b>
            {$keyword}
            </b>.  (<b>
            {php}
                printf("%.2f",rand(1,10000)/10000);
                $i = empty($_GET['page'])?0:$_GET['page']-1;
            {/php}
            </b> seconds)
        </span>
    </div>

    {foreach from=$entries item=entry}
    <div class="entry">
        <div class="entrytitle">
        <a class="w" href="{$entry->getHref()|escape}" >{$entry->title|escape}</a>
        </div>
        <div class="entrybody">{$entry->getBody()|strip_tags|substring:300}</div>
        <div class="entryinfo">
        	{$entry->getHref()} - 
			{$entry->getBody()|count_characters:true}k -  
			{$entry->date|date_format:"%B %d, %Y"}
            {if $entry->isSetOption("NO_COMMENT") == false} - <a class="fl" href="{$entry->getHref()|escape}#comment">Comments ({$entry->getCommentCount()})</a>{/if}
            {if $entry->isSetOption("NO_TRACKBACK") == false} - <a class="fl" href="{$entry->getHref()|escape}#trackback">TrackBacks ({$entry->getTrackbackCount()})</a>{/if}
        </div>
    </div>
    {/foreach}
    {if $view == "index"}
    <div class='nav'>
    <table style="margin:0 auto; text-align=center;">
        <tr>
            <td>
                Result Page:             
            </td>
            <td>
                {if $prev_page_link != ""}
              	<a href="{$prev_page_link|escape}" class='q'>
                {/if}
                <span class='i'><img src="{$baseurl}/templates/{$skin}/imgs/nav_previous.gif" alt="" /></span><br />
                {if $prev_page_link != ""}
                <span class='b'>Previous</span>
              	</a>                
                {else}
                &nbsp;
                {/if}
            </td>
            <td>
                {if $prev_page_link != ""}
              	<a href="{$prev_page_link|escape}">
                <img src="{$baseurl}/templates/{$skin}/imgs/nav_page.gif" alt="" /><br />
                {php}
                echo $i;
                {/php}
                </a>
                {/if}
            </td>
            <td>
                <img src="{$baseurl}/templates/{$skin}/imgs/nav_current.gif" alt="current" /><br /><span class='i'>
                {php}
                echo $i+1;
                {/php}
                </span>
            </td>
            <td>
                {if $next_page_link != ""}
              	<a href="{$next_page_link|escape}">
                <img src="{$baseurl}/templates/{$skin}/imgs/nav_page.gif" alt="next" /><br />
                {php}
                echo $i+2;
                {/php}
                </a>
                {/if}
            </td>
            <td>
                {if $next_page_link != ""}
              	<a href="{$next_page_link|escape}">
                {/if}
                <img src="{$baseurl}/templates/{$skin}/imgs/nav_next.gif" alt="next_page" /><br />
                {if $next_page_link != ""}
                <span class='b'>Next</span>
                </a>
                {else}
                &nbsp;
                {/if}
            </td>
        </tr>
    </table>
    </div>
    {/if}
</div>
{include file="footer.tpl"}
