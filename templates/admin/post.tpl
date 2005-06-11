{include file="header.tpl"}
{include file="menu.tpl"}

<div id="post">

{if $mode == "preview"}
<h1>preview</h1>
<h2>{$title}</h2>
{$preview}
<br />
{$category}, {$date}
<hr />
{/if}

<form action="{$baseurl}/post.php" method="post" name="postForm">
<div class="row">
<span class="label">Title:</span>
<span class="formw"><input type="text" name="title" value="{$title|escape}" /></span>
</div>

<div class="row">
<span class="label">Date:</span>
<span class="formw"><input type="text" name="date" value="{$date}" />
</div>

<div class="row">
<span class="label">Category:</span>
<span class="formw">
<input type="text" name="category_input" value="{$category|escape}" /> or 
{foreach name=categories from=$categories item=cate}
{if $smarty.foreach.categories.first}
<select name="category">
{/if}
	<option value="{$cate->name}" {if $category == $cate->name}selected{/if}>{$cate->name}
{foreachelse}
<select name="category" disabled>
{/foreach}
</select>
</span>
</div>

<div class="row">
<span class="label">Options:</span>
<span class="formw">
<input type="checkbox" name="SECRET" {if $secret}checked{/if} />SECRET
<input type="checkbox" name="NO_COMMENT" {if $no_comment}checked{/if} />NO_COMMENT
<input type="checkbox" name="NO_TRACKBACK" {if $no_trackback}checked{/if} />NO_TRACKBACK
<input type="checkbox" name="STATIC" {if $static}checked{/if} />STATIC
<input type="checkbox" name="NO_RSS" {if $no_rss}checked{/if} />NO_RSS
</span>
</div>

<div class="row">
<span class="label">Format:</span>
<span class="formw">
<input type="radio" name="format" value="plain" {if $format == "plain" || $format == ""}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=plain')" />plain
<input type="radio" name="format" value="html" {if $format == "html"}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=html')" />html
<input type="radio" name="format" value="bbcode" {if $format == "bbcode"}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=bbcode')" />bbcode
<input type="radio" name="format" value="moniwiki" {if $format == "moniwiki"}checked{/if} onClick="go('{$baseurl}/post.php?blogid={$id}&format=moniwiki')" />moniwiki
</span>
</div>

<div class="row">
<span class="label">Body:</span>
<span class="formw">
<textarea id="body" name="body" rows="10" cols="65">{$body|escape}</textarea>
{if $format == "html"}
<script type="text/javascript">
	var oFCKeditor = new FCKeditor('body');
	oFCKeditor.BasePath = "libs/fckeditor/";
	oFCKeditor.Width = "80%";
	oFCKeditor.Height = "300";
	oFCKeditor.ReplaceTextarea();
</script>
{/if}
</span>
</div>

<div class="row">
<span class="label">Upload:</span>
<span class="formw">
<a href="#" onClick="window.open('{$baseurl}/upload.php','blah','width=500,height=300,scrollbars=yes')">Upload Files</a>
</span>
</div>

{if $id != null}
<input type="hidden" name="id" value="{$id}" />
{/if}

<div class="row">
<span class="formw">
<input type="submit" name="mode" value="Preview" />
<input type="submit" name="mode" value="Post" />
</span>
</div>

</form>
</div>
{include file="footer.tpl"}
