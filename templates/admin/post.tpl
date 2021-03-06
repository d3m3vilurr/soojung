{include file="header.tpl"}
{include file="menu.tpl"}

<script type="text/javascript" src="{$baseurl}/libs/bbcode.js"></script>
<script type="text/javascript">
{literal}
function savePostValue() {
    tempForm = document.postTempForm;
    tempForm.title.value = document.postForm.title.value;
    tempForm.date.value = document.postForm.date.value;
    tempForm.SECRET.value = document.postForm.SECRET.checked;
    tempForm.NO_COMMENT.value = document.postForm.NO_COMMENT.checked;
    tempForm.NO_TRACKBACK.value = document.postForm.NO_TRACKBACK.checked;
    tempForm.STATIC.value = document.postForm.STATIC.checked;
    tempForm.NO_RSS.value = document.postForm.NO_RSS.checked;
    tempForm.body.value = document.postForm.body.value;
    tempForm.id.value = (document.postForm.id.value == undefined || document.postForm.id.value == "")
        ? null : document.postForm.id.value;
}
function changePostCategory(category) {
    savePostValue();
    tempForm = document.postTempForm;
    for(i=0; i<document.postForm.format.length; i++) {
        if(!document.postForm.format[i].checked) continue;
        tempForm.format.value = document.postForm.format[i].value;
    }
    tempForm.category.value = category.value;
    tempForm.submit();
}
function changeFormat(format) {
    savePostValue();
    tempForm = document.postTempForm;
    tempForm.format.value = format;
    tempForm.category.value = document.postForm.category_input.value != ""
        ? document.postForm.category_input.value : document.postForm.category.value;
    tempForm.submit();
}
{/literal}
</script>

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
<span class="formw"><input type="text" name="title" value="{$title|escape}" style="width:70%;"/></span>
</div>

<div class="row">
<span class="label">Date:</span>
<span class="formw"><input type="text" name="date" value="{$date}" /></span>
</div>

<div class="row">
<span class="label">Category:</span>
<span class="formw">
<input type="text" name="category_input" value="{$category|escape}" /> or 
{foreach name=categories from=$categories item=cate}
{if $smarty.foreach.categories.first}
<select name="category" onchange="changePostCategory(this);">
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
<input type="radio" name="format" value="plain" {if $format == "plain" || $format == ""}checked{/if} onClick="changeFormat('plain');" />plain
<input type="radio" name="format" value="html" {if $format == "html"}checked{/if} onClick="changeFormat('html')" />html
<input type="radio" name="format" value="bbcode" {if $format == "bbcode"}checked{/if} onClick="changeFormat('bbcode');" />bbcode
<input type="radio" name="format" value="moniwiki" {if $format == "moniwiki"}checked{/if} onClick="changeFormat('moniwiki');" />moniwiki
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

{if $format == "bbcode"}
    {include file="bbcode.tpl"}
{/if}

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

<form action="{$baseurl}/post.php" method="post" name="postTempForm">
<input type="hidden" name="title"/>
<input type="hidden" name="date"/>
<input type="hidden" name="format"/>
<input type="hidden" name="category"/>
<input type="hidden" name="body"/>
<input type="hidden" name="id"/>
<input type="hidden" name="SECRET"/>
<input type="hidden" name="NO_COMMENT"/>
<input type="hidden" name="NO_TRACKBACK"/>
<input type="hidden" name="STATIC"/>
<input type="hidden" name="NO_RSS"/>
</form>
{include file="footer.tpl"}
