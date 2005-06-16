<div id="menu">
    <div class="category">
	    <ul id="div_category" class="hide">
    	    {foreach from=$categories item=category}
	    	<li>
    			<a href="{$category->getHref()|escape}">{$category->name|substring:25|escape} ({$category->getEntryCount()})</a>
    		</li>
	    	{foreachelse}
    		<li></li>
		    {/foreach}
	    </ul>
    </div>
    
    <div class="archive">
        <ul id="div_archive" class="hide">
            {foreach from=$archvies item=archive}
            <li>
                <a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"|substring:25} ({$archive->getEntryCount()})</a>
            </li>
            {foreachelse}
            <li></li>
            {/foreach}
        </ul>
    </div>
    
    <div class="calendar">
        <div id="div_calendar" class="hide">
            {$calendar->getCalendar()}    
        </div>
    </div>
    
    <div class="static">
        <ul id="div_static" class="hide">
            {foreach from=$static_entries item=static}
            <li>
                <a href="{$static->getHref()|escape}">{$static->title|escape}</a>
            </li>
            {foreachelse}
            <li></li>
            {/foreach}
        </ul>
    </div>
    
    <div class="recent_entry">
        <ul id="div_entry" class="hide">
            {foreach from=$recent_entries item=entry}
            <li>
                <a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
            </li>
            {foreachelse}
            <li></li>
            {/foreach}
        </ul>
    </div>
    
    <div class="recent_comment">
        <ul id="div_recent_comment" class="hide">
            {foreach from=$recent_comments item=comment}
            <li>
                <a href="{$comment->getHref()|escape}">{$comment->getBody()|strip_tags:false|substring:35|escape}</a>
            </li>
            {/foreach}
        </ul>
    </div>

    <div class="recent_trackback">
        <ul id="div_recent_trackback" class="hide">
            {foreach from=$recent_trackbacks item=trackback}
            <li>
                <a href="{$trackback->getHref()|escape}">{$trackback->getExcerpt()|strip_tags|substring:27|escape}</a>
            </li>
            {/foreach}
        </ul>
    </div>

    <div class="bookmark">
        <ul id="div_bookmark" class="hide">
            {foreach from=$bookmarks item=bookmark}
            <li>
                <a href="{$bookmark->url|escape}" {if #targetBlank# == 1}onclick="seturltarget(this,'_blank');"{/if}>{$bookmark->desc|substring:35|escape}</a>
            </li>
            {/foreach}
        </ul>
    </div>
</div> 

<div class="footer">
    <div class="footerbox">
        <div>
            <form action="{$baseurl}/index.php" method="get">
                <div>
                    <input type="text" name="search" size="31" value="{$keyword}" />
                    <input type="submit" value="Search" />
                </div>
            </form>
        </div>
        <div>
        	<a href="#none">Search within results</a> | 
			<a href="http://soojung.kldp.net">Language Tools</a> |
			<a href="http://soojung.kldp.net">Search Tips</a> |
			<a href="http://kldp.net/forum/forum.php?forum_id=1821">Dissatisfied? Help us improve</a>
        </div>
    </div>
    <p>
        {$license_link}<br />
        Powered by <a href="http://soojung.kldp.net" {if #targetBlank# == 1}onclick="seturltarget(this,'_blank');"{/if}>soojung {$soojung_version}</a>
    </p>
</div>

</body>
</html>
