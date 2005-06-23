{include file="header.tpl"}
<div id="bar">
    <span class="result">
        Results <strong>1</strong> - <strong>1</strong> of about<strong>
        {php}
            $entry = Entry::getEntry($_GET['blogid']);
            $category = new Category($entry->category->name);
            echo $category->getEntryCount();
        {/php}
        </strong>for<strong>
        {$entry->category->name|escape}
        </strong>.(<strong>
        {php}
            printf("%.2f",rand(1,10000)/10000);
            $i = 1;
        {/php}
        </strong>seconds)
    </span>
    <h3>{$entry->title|escape}</h3>
</div>
<div class="entry">
    <div class="entrybody">
        {$entry->getBody()}
        <p class="posted">
            posted at {$entry->date|date_format:"%y/%m/%d %H:%M"} - <a class="fl" href="{$entry->category->getHref()|escape}">{$entry->category->name|escape}</a>
            {if $entry->isSetOption("NO_COMMENT") == false}
            - <a class="fl" href="#none" onclick="return fold_sidebar('div_CO_{$entry->entryId}');">Comments ({$entry->getCommentCount()})</a>
            {/if}
            {if $entry->isSetOption("NO_TRACKBACK") == false}
            - <a class="fl" href="#none" onclick="return fold_sidebar('div_TB_{$entry->entryId}');">TrackBacks ({$entry->getTrackbackCount()})</a>
            {/if}
        </p>
    </div>
    
    {if $entry->isSetOption("NO_TRACKBACK") == false}
    <div id="div_TB_{$entry->entryId}">
        <div class="trackbacks">
            <div class="trackback_url">TrackBack URL: {$baseurl}/trackback.php?blogid={$entry->entryId}</div>
            {if $entry->getTrackbackCount() != 0}
            <div id="trackback">
                {foreach from=$trackbacks item=trackback}
                <div class="trackback">
                    <div id="TB{$trackback->date}">
                        <div class="trackback_name">
                            Tracked from <a href="{$trackback->url|escape}" class="fl">{$trackback->name|escape}</a> at {$trackback->date|date_format:"%y/%m/%d %H:%M"}<br />
        					<a href="{$trackback->url|escape}" class="f1">{$trackback->title|escape}</a>
                            
                        </div>
                        <div class="trackback_body">
                            {$trackback->getExcerpt()|strip_tags} ...<a href="{$trackback->url}" class="fl">more</a>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
            {/if}
        </div>
    </div>
    {/if}
    
    {if $entry->isSetOption("NO_COMMENT") == false}
    <div id="div_CO_{$entry->entryId}">
        <div class="comments">
            {if $entry->getCommentCount() != 0}
            <div id="comment">
                {foreach from=$comments item=comment}
                <div class="comment">
                    <div class="comment_user">
                        <div id="CO{$comment->date}">
                            Commented by
                            {if $comment->homepage != ""}
                            <a href="{$comment->homepage|escape}" class="fl">{$comment->name|escape}</a>
                        	{elseif $comment->email != ""}
	    	                <a href="mailto:{$comment->email|escape}" class="fl">{$comment->name|escape}</a>
                    	    {else}
            	    	    {$comment->name|escape}
                	        {/if}
                             at {$comment->date|date_format:"%y/%m/%d %H:%M"} 
                        </div>
                    </div>
                    <div class="comment_body">
                    	{$comment->getBody()}
                    </div>
            	</div>
                {/foreach}
            </div>
            {/if}
            <div class='comment_post'>
                <form action="{$baseurl}/entry.php" method="post">
                    <div class="comment_name">
                        Name <input class="comment_text" type="text" name="name" value="{$w_name|escape}" style="width: 100px;" /> &nbsp;
                        Email <input class="comment_text" type="text" name="email" value="{$w_email|escape}" style="width: 100px;" /> &nbsp;
                        Homepage <input class="comment_text" type="text" name="url" value="{$w_url|default:"http://"|escape}" style="width: 200px;" />
                    </div>
                    <div>
                        <textarea class="comment_text" name="body" rows="3" cols=""></textarea>
                    </div>
                    <div>
                        <input type="hidden" name="blogid" value="{$entry->entryId}" />
                        <input type="submit" value="save" class="submit" />
                    </div>
                </form>            
            </div>
        </div>
    </div>
    {/if}
</div>
{include file="footer.tpl"}
