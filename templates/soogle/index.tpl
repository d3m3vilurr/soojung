{include file="header.tpl"}

<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#e5ecf9>
	<tr>
		<td bgcolor=#e5ecf9 nowrap>
			<font size=+1>&nbsp;<b>
			{if $view == "index"}
				All Entries
			{elseif $view == "archive"}
				Archive
			{elseif $view == "category"}
				Category
			{elseif $view == "search"}
				Search
			{/if}
			</b></font>&nbsp;
		</td>
		<td bgcolor=#e5ecf9 align=right nowrap>
			<font size=-1 color=#000000>Results <b>1</b> - <b>10</b> of about <b>{$count}</b> for <b>{$keyword}</b>.  (<b>0.18</b> seconds)&nbsp;</font>
		</td>
	</tr>
</table>

{foreach from=$entries item=entry}
	<p class=g>
		<a href="{$entry.link}">{$entry.title}</a><br />
	<font size=-1>
		{$entry.body|strip_tags|truncate:200}<br />
		<font color=#008000>
			{$entry.link} - 
			{$entry.body|count_characters:true}k -  
			{$entry.date|date_format:"%B %d, %Y"} -
		</font>
		<a class=fl href="{$entry.link}#comment">Comment</a> - 
		<a class=fl href="{$entry.link}#trackback">TrackBacks</a>
	</font>
	</p>
{/foreach}

{if $prev_page_link != ""}
	<a href="{$prev_page_link}">prev</a>
{/if}
{if $next_page_link != ""}
	<a href="{$next_page_link}">next</a>
{/if}

{include file="footer.tpl"}
