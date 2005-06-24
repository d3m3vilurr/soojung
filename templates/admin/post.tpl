{include file="header.tpl"}
{include file="menu.tpl"}

<script type="text/javascript" src="{$baseurl}/libs/bbcode.js"></script>
<script type="text/javascript">
{literal}
function changeFormat(format) {
    tempForm = document.postTempForm;
    tempForm.format.value = format;
    tempForm.title.value = document.postForm.title.value;
    tempForm.date.value = document.postForm.date.value;
    tempForm.category.value = document.postForm.category_input.value != ""
        ? document.postForm.category_input.value : document.postForm.category.value;
    tempForm.SECRET.value = document.postForm.SECRET.checked;
    tempForm.NO_COMMENT.value = document.postForm.NO_COMMENT.checked;
    tempForm.NO_TRACKBACK.value = document.postForm.NO_TRACKBACK.checked;
    tempForm.STATIC.value = document.postForm.STATIC.checked;
    tempForm.NO_RSS.value = document.postForm.NO_RSS.checked;
    tempForm.body.value = document.postForm.body.value;
    tempForm.id.value = (document.postForm.id.value == undefined || document.postForm.id.value == "")
        ? null : document.postForm.id.value;
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
<span class="formw"><input type="text" name="title" value="{$title|escape}" /></span>
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
{elseif $format == "bbcode"}

<div id="bbcode">
	<input type="button" class="button" accesskey="b" id="addbbcode0" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" />
	<input type="button" class="button" accesskey="i" id="addbbcode2" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" />
	<input type="button" class="button" accesskey="u" id="addbbcode4" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" />
	<input type="button" class="button" accesskey="q" id="addbbcode6" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" />
	<input type="button" class="button" accesskey="c" id="addbbcode8" name="addbbcode8" value="Code" style="width: 40px" onClick="bbstyle(8)" />
	<input type="button" class="button" accesskey="l" id="addbbcode10" name="addbbcode10" value="List" style="width: 40px" onClick="bbstyle(10)" />
	<input type="button" class="button" accesskey="o" id="addbbcode12" name="addbbcode12" value="List=" style="width: 40px" onClick="bbstyle(12)" />
	<input type="button" class="button" accesskey="p" id="addbbcode14" name="addbbcode14" value="Img" style="width: 40px"  onClick="bbstyle(14)" />
	<input type="button" class="button" accesskey="w" id="addbbcode16" name="addbbcode16" value="URL" style="text-decoration: underline; width: 40px" onClick="bbstyle(16)" />
	<a href="javascript:bbstyle(-1)">Close Tags</a>

	<select id="addbbcode18" name="addbbcode18" onChange="bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
	<option style="color:black; background-color: #FAFAFA" value="black">Default</option>
	<option style="color:darkred; background-color: #FAFAFA" value="darkred">Dark Red</option>
	<option style="color:red; background-color: #FAFAFA" value="red">Red</option>
	<option style="color:orange; background-color: #FAFAFA" value="orange">Orange</option>
	<option style="color:brown; background-color: #FAFAFA" value="brown">Brown</option>
	<option style="color:yellow; background-color: #FAFAFA" value="yellow">Yellow</option>
	<option style="color:green; background-color: #FAFAFA" value="green">Green</option>
	<option style="color:olive; background-color: #FAFAFA" value="olive">Olive</option>
	<option style="color:cyan; background-color: #FAFAFA" value="cyan">Cyan</option>
	<option style="color:blue; background-color: #FAFAFA" value="blue">Blue</option>
	<option style="color:darkblue; background-color: #FAFAFA" value="darkblue">Dark Blue</option>
	<option style="color:indigo; background-color: #FAFAFA" value="indigo">Indigo</option>
	<option style="color:violet; background-color: #FAFAFA" value="violet">Violet</option>
	<option style="color:white; background-color: #FAFAFA" value="white">White</option>
	<option style="color:black; background-color: #FAFAFA" value="black">Black</option>
	</select>
	
	<select id="addbbcode20" name="addbbcode20" onChange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')">
	<option value="7">Tiny</option>
	<option value="9">Small</option>
	<option value="12" selected="selected">Normal</option>
	<option value="18">Large</option>
	<option value="24">Huge</option>
	</select>
</div>
<div id="smiles">
	<a href="javascript:emoticon(':D')"><img src="{$baseurl}/libs/bbcode/smiles/icon_biggrin.gif" alt="Very Happy" title="Very Happy" /></a>
	<a href="javascript:emoticon(':)')"><img src="{$baseurl}/libs/bbcode/smiles/icon_smile.gif" alt="Smile" title="Smile" /></a>
	<a href="javascript:emoticon(':(')"><img src="{$baseurl}/libs/bbcode/smiles/icon_sad.gif" alt="Sad" title="Sad" /></a>
	<a href="javascript:emoticon(':o')"><img src="{$baseurl}/libs/bbcode/smiles/icon_surprised.gif" alt="Surprised" title="Surprised" /></a>
	<a href="javascript:emoticon(':shock:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_eek.gif" alt="Shocked" title="Shocked" /></a>
	<a href="javascript:emoticon(':?')"><img src="{$baseurl}/libs/bbcode/smiles/icon_confused.gif" alt="Confused" title="Confused" /></a>
	<a href="javascript:emoticon('8)')"><img src="{$baseurl}/libs/bbcode/smiles/icon_cool.gif" alt="Cool" title="Cool" /></a>
	<a href="javascript:emoticon(':lol:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_lol.gif" alt="Laughing" title="Laughing" /></a>
	<a href="javascript:emoticon(':x')"><img src="{$baseurl}/libs/bbcode/smiles/icon_mad.gif" alt="Mad" title="Mad" /></a>
	<a href="javascript:emoticon(':P')"><img src="{$baseurl}/libs/bbcode/smiles/icon_razz.gif" alt="Razz" title="Razz" /></a>
	<a href="javascript:emoticon(':oops:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_redface.gif" alt="Embarassed" title="Embarassed" /></a>
	<a href="javascript:emoticon(':cry:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_cry.gif" alt="Crying or Very sad" title="Crying or Very sad" /></a>
	<a href="javascript:emoticon(':evil:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_evil.gif" alt="Evil or Very Mad" title="Evil or Very Mad" /></a>
	<a href="javascript:emoticon(':twisted:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_twisted.gif" alt="Twisted Evil" title="Twisted Evil" /></a>
	<a href="javascript:emoticon(':roll:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_rolleyes.gif" alt="Rolling Eyes" title="Rolling Eyes" /></a>
	<a href="javascript:emoticon(':wink:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_wink.gif" alt="Wink" title="Wink" /></a>
	<a href="javascript:emoticon(':!:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_exclaim.gif" alt="Exclamation" title="Exclamation" /></a>
	<a href="javascript:emoticon(':?:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_question.gif" alt="Question" title="Question" /></a>
	<a href="javascript:emoticon(':idea:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_idea.gif" alt="Idea" title="Idea" /></a>
	<a href="javascript:emoticon(':arrow:')"><img src="{$baseurl}/libs/bbcode/smiles/icon_arrow.gif" alt="Arrow" title="Arrow" /></a>
</div>

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
