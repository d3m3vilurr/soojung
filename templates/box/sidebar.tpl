	<div id="sidebar" class="blackbox">
		<div style="text-align: center;">
			<i>your message goes here.</i>
		</div>
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_category');"> &nbsp;CATEGORY</a></div>
		<ul id="div_category">
			{foreach from=$categories item=category}
			<li>
				<a href="{$category->getHref()|escape}">{$category->name|substring:25|escape}</a>
				({$category->getEntryCount()})
			</li>
			{foreachelse}
			<li></li>
			{/foreach}
		</ul>

		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_calendar');"> &nbsp;CALENDAR</a></div>
		<div id="div_calendar" style="text-align:center;">
			{$calendar->getCalendar()}
		</div>
<!--
		{if count($static_entries) != 0}
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_static');"> &nbsp;static POST</a></div>
		<ul id="div_static">
			{foreach from=$static_entries item=static}
			<li>
				<a href="{$static->getHref()|escape}">{$static->title|escape}</a>
			</li>
			{foreachelse}
			<li></li>
			{/foreach}
		</ul>
		{/if}
-->
<!--
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_entry');"> &nbsp;recent POST</a></div>
		<ul id="div_entry" class="hide">
			{foreach from=$recent_entries item=entry}
			<li>
				<a href="{$entry->getHref()|escape}">{$entry->title|escape}</a>
			</li>
			{foreachelse}
			<li></li>
			{/foreach}
		</ul>
-->
		{if count($recent_comments) != 0}
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_recent_comment');"> &nbsp;recent COMMENTs</a></div>
		<ul id="div_recent_comment" class="hide">
			{foreach from=$recent_comments item=comment}
			<li>
				<a href="{$comment->getHref()|escape}">{$comment->getBody()|strip_tags:false|substring:35|escape}</a>
			</li>
			{/foreach}
		</ul>
		{/if}
				
		{if count($recent_trackbacks) != 0}
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_recent_trackback');"> &nbsp;recent TRACKBACKs</a></div>
		<ul id="div_recent_trackback" class="hide">
			{foreach from=$recent_trackbacks item=trackback}
			<li>
				<a href="{$trackback->getHref()|escape}">{$trackback->url|substring:27|escape}</a>
			</li>
			{/foreach}
		</ul>
		{/if}

		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_archive');"> &nbsp;ARCHIVEs</a></div>
		<ul id="div_archive">
			{foreach from=$archvies item=archive}
			<li>
				<a href="{$archive->getHref()|escape}">{$archive->getDate()|date_format:"%B %Y"|substring:25}</a> ({$archive->getEntryCount()})
			</li>
			{foreachelse}
			<li></li>
			{/foreach}
		</ul>

		{if count($recent_referers) != 0}
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_recent_referer');"> &nbsp;recent REFERERs</a></div>
		<ul id="div_recent_referer" class="hide">
			{foreach from=$recent_referers item=referer}
			<li>
				<a href="{$referer|escape}" onclick="seturltarget(this,'_blank');">{$referer|substring:25|escape}</a>
			</li>
			{/foreach}
		</ul>
		{/if}

		{if count($bookmarks) != 0}
		<div class="menutitle"><a href="#none" onclick="return fold_sidebar('div_bookmark');"> &nbsp;BOOKMARKs</a></div>
		<ul id="div_bookmark" class="hide">
			{foreach from=$bookmarks item=bookmark}
			<li>
				<a href="{$bookmark->url|escape}" onclick="seturltarget(this,'_blank');">{$bookmark->desc|substring:35|escape}</a>
			</li>
			{/foreach}
		</ul>
		{/if}

		<div class="menutitle" style="color: #777777;"> &nbsp;SEARCH</div>
		<form action="index.php" method="get">
			<p style="margin-left:10px;">
				<input type="text" name="search" title="input search text" size="16"/>
				<input type="submit" value="Search" />
			</p>
		</form>
		<br />
		<p style="margin-left: 10px;">
			Today : {$today_count}<br />
			Total : {$total_count}<br />
			<br />
			<a href="{$baseurl}/rss2.php" onclick="seturltarget(this,'_blank');"><img src="{$baseurl}/templates/{$skin}/imgs/rss20_logo.gif" alt="rss 2.0 feed" style="border:0px;"/></a><br />
			
			<a href="http://validator.w3.org/check?uri=referer" onclick="seturltarget(this,'_blank');"><img src="{$baseurl}/templates/{$skin}/imgs/xhtml11.png" alt="Valid XHTML 1.0!" style="border:0px;"/></a><br />

			<a class="nodecoration" title="Explanation of Level A Conformance" href="http://www.w3.org/WAI/WCAG1A-Conformance" onclick="seturltarget(this,'_blank');"><img src="{$baseurl}/templates/{$skin}/imgs/wai_a.png" alt="Level A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0" style="border:0px;"/></a>
		</p>
	</div>
</div>

