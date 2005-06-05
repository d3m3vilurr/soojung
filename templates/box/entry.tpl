{include file="header.tpl"}
<div id="entries">
	<div class="entry">
		<div class="entrytitle" style="color: #777777;">{$entry->title|escape}</div>
		<div class="entrybody">{$entry->getBody()}</div>
		<div class="entrydate">
			posted at {$entry->date|date_format:"%y/%m/%d %H:%M"}
			| <a class="plink" href="{$entry->category->getHref()|escape}">{$entry->category->name|escape}</a>
			{if $entry->isSetOption("NO_COMMENT") == false}| <a class="plink" href="#none" onclick="return fold_sidebar('div_CO_{$entry->entryId}');">Comments ({$entry->getCommentCount()})</a>{/if}
            {if $entry->isSetOption("NO_TRACKBACK") == false}| <a class="plink" href="#none" onclick="return fold_sidebar('div_TB_{$entry->entryId}');">TrackBacks ({$entry->getTrackbackCount()})</a>{/if}
		</div>
        
		{if $entry->isSetOption("NO_TRACKBACK") == false}
		<div id="div_TB_{$entry->entryId}" style="text-align:left;">
			<div class="graybox">
				<div class="gray8" style="text-align:right; padding: 5px;">
					&nbsp;Track this back : <span class="trackbackurl" onclick="copy_clip(this.innerHTML);">{$baseurl}/trackback.php?blogid={$entry->entryId}</span>
				</div>
				{foreach from=$trackbacks item=trackback}
				<div id="TB{$trackback->date}" class="blue8" style="width: 100%; margin: 5px 5px 0 0; color: #999999;">
					<img src="{$baseurl}/templates/{$skin}/imgs/comment.gif" alt="" /> Tracked from <a href="{$trackback->url|escape}" {if #targetBlank# == 1}onclick="seturltarget(this,'_blank');"{/if}><b>{$trackback->name|escape}</b></a>
					at {$trackback->date|date_format:"%y/%m/%d %H:%M"}
				</div>
				<div class="blue8" style="margin: 0 0 5px 15px;  color: #555555;">
					<a href="{$trackback->url|escape}" {if #targetBlank# == 1}onclick="seturltarget(this,'_blank');"{/if}>{$trackback->title|escape}</a><br />
					{$trackback->getExcerpt()|strip_tags}
				</div>
				{/foreach}
			</div>
		</div>
		{/if}
        
		{if $entry->isSetOption("NO_COMMENT") == false}
		<div id="div_CO_{$entry->entryId}" style="text-align: left;">
			<div class="graybox">
		    	{foreach from=$comments item=comment}
				<div id="CO{$comment->date}" class="blue8" style="margin: 5px 5px 0 0; color: #999999;">
					<img src="{$baseurl}/templates/{$skin}/imgs/comment.gif" alt="" /> Commented by<b>
					{if $comment->homepage != ""}
					<a href="{$comment->homepage|escape}" {if #targetBlank# == 1}onclick="seturltarget(this,'_blank');"{/if}>{$comment->name|escape}</a>
					{elseif $comment->email != ""}
					<a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
					{else}
					{$comment->name|escape}
					{/if}
                    </b>
					at {$comment->date|date_format:"%y/%m/%d %H:%M"}
				</div>
				<div class="blue8" style="margin: 0 0 5px 15px; color:#555555;">{$comment->getBody()}</div>
				{/foreach}
				<br />
				<form action="{$baseurl}/entry.php" method="post">
					<div class="blue8" style="text-align:right; padding:0 0 3px 0;">
						Name <input class="comment_text" type="text" name="name" value="{$w_name|escape}" style="width: 50px;" /> &nbsp;
						Email <input class="comment_text" type="text" name="email" value="{$w_email|escape}" style="width: 50px;" /> &nbsp;
						Homepage <input class="comment_text" type="text" name="url" value="{$w_url|default:"http://"|escape}" style="width: 200px;" />
					</div>
					<div style="text-align:right;">
						<textarea class="comment_text" name="body" rows="3" cols="" style="width: 90%; overflow-y: auto; overflow-x: hidden;"></textarea>
					</div>
					<div style="text-align:right; padding: 3px 0 0 0;" class="gray7">
						<input type="hidden" name="blogid" value="{$entry->entryId}" />
						<input type="submit" value="save" style="color:#999999; background-color:#F7F7F7; border: 1px #CCCCCC solid; width: 50px; height: 17px; overflow: auto; font-size: 8pt;" />
					</div>
				</form>
			</div>
		</div>
		{/if}

	</div>
</div>

{include file="sidebar.tpl"}
{include file="footer.tpl"}
