{include file="header.tpl"}
{include file="menu.tpl"}

{if $mode == "preview"}
<h1>preview</h1>
<h2>{$title}</h2>
{$body}
<br />
{$category}, {$date}
<hr />
{/if}
<form action="{$baseurl}/post.php" method="post">
	Title:
	<input type="text" name="title" value="{$title}">
	<br />

	Date:
	<input type="text" name="date" value="{$date}">
	<br />

	Category:
	<input type="text" name="category" value="{$category}">
	<br />

	Options:
	<input type="checkbox" name="SECRET" {if $secret}checked{/if}>SECRET
	<input type="checkbox" name="NO_COMMENT" {if $no_comment}checked{/if}>NO_COMMENT
	<input type="checkbox" name="NO_TRACKBACK" {if $no_trackback}checked{/if}>NO_TRACKBACK
	<input type="checkbox" name="STATIC" {if $static}checked{/if}>STATIC
	<input type="checkbox" name="NO_RSS" {if $no_rss}checked{/if}>NO_RSS
	<br />

	Format:
	<input type="radio" name="format" value="plain" {if $format == "plain"}checked{/if}>plain
	<input type="radio" name="format" value="html" {if $format == "html"}checked{/if}>html
	<input type="radio" name="format" value="wiki" {if $format == "wiki"}checked{/if}>wiki
	<input type="radio" name="format" value="bbcode" {if $format == "bbcode"}checked{/if}>bbcode
	<br />

	Body: <br/>
	<textarea name="body" rows="20" cols="80">{$body}</textarea>
	<br />

	{if $id != null}
	  <input type="hidden" name="id" value="{$id}">
	{/if}

	<input type="submit" name="mode" value="Preview">
	<input type="submit" name="mode" value="Post">
</form>

{include file="footer.tpl"}