{if $entry->isSetOption("NO_COMMENT") == false}
<div class="comments">
{$entry->getCommentCount()} Comments<br />
<a name="comment"></a>
{foreach from=$comments item=comment}
<div class="comment">
    <a name="{$comment->date}"></a>
    On {$comment->date|date_format:"%B %d, %Y %I:%M %p"},
    {if $comment->homepage != ""}
    <a href="{$comment->homepage|escape}">{$comment->name|escape}</a>
    {elseif $comment->email != ""}
    <a href="mailto:{$comment->email|escape}">{$comment->name|escape}</a>
    {else}
    {$comment->name|escape}
    {/if}
    said: <br />
    {$comment->getBody()}
</div>
{/foreach}
</div>
<form action="entry.php?blogid={$entry->entryId}" method="post">
Post a comment<br />
Name:<br />
<input type="text" name="name" value="{$w_name|escape}"><br />
Email Adress:<br />
<input type="text" name="email" value="{$w_email|escape}"><br />
URL:<br />
<input type="text" name="url" value="{$w_url|default:"http://"|escape}"><br />
Comments:<br />
<textarea name="body" rows="5" cols="40"></textarea><br />
<input type="hidden" name="blogid" value="{$entry->entryId}">
<input type="submit" value="Post">
</form>
{/if}

