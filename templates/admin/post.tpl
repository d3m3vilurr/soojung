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
	<input type="text" name="category_input" value="{$category}"> or 

	{foreach name=categories from=$categories item=cate}
		{if $smarty.foreach.categories.first}
			<select name="category">
		{/if}
		<option value="{$cate->name}" {if $category == $cate->name}selected{/if}>{$cate->name}
	{foreachelse}
		<select name="category" disabled>
	{/foreach}
	</select>
	<br />

	Options:
	<input type="checkbox" name="SECRET" {if $secret}checked{/if}>SECRET
	<input type="checkbox" name="NO_COMMENT" {if $no_comment}checked{/if}>NO_COMMENT
	<input type="checkbox" name="NO_TRACKBACK" {if $no_trackback}checked{/if}>NO_TRACKBACK
	<input type="checkbox" name="STATIC" {if $static}checked{/if}>STATIC
	<input type="checkbox" name="NO_RSS" {if $no_rss}checked{/if}>NO_RSS
	<br />

	Format:
	<input type="radio" name="format" value="plain" {if $format == "plain" || $format == ""}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=plain')">plain
	<input type="radio" name="format" value="html" {if $format == "html"}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=html')">html
	<input type="radio" name="format" value="bbcode" {if $format == "bbcode"}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=bbcode')">bbcode
	<br />
	
	Body: <br />
	{if $format == "plain" || $format == "bbcode" || $format == ""}
		<textarea name="body" rows="15" cols="80">{$body}</textarea>
	{else}
		<script type="text/javascript">
			var oFCKeditor = new FCKeditor( 'body' ) ;
			oFCKeditor.BasePath = "libs/fckeditor/";	

			oFCKeditor.Value = '{$body|replace:"\r\n":"<br />"}';
			oFCKeditor.Width = "80%";
			oFCKeditor.Height = "300";
			oFCKeditor.Create() ;

		</script>
	{/if}
	<br />

	Upload:	<a href="#" onClick="window.open('{$baseurl}/upload.php','blah','width=500,height=300')">Upload Files</a>
	<br />

	{if $id != null}
	  <input type="hidden" name="id" value="{$id}">
	{/if}

	<input type="submit" name="mode" value="Preview">
	<input type="submit" name="mode" value="Post">
</form>

{include file="footer.tpl"}