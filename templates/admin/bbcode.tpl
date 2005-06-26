<div class='raw'>
<div id="bbcode">
	<input type="button" class="button" accesskey="b" id="addbbcode0" name="addbbcode0" value="B" style="font-weight:bold; width: 30px" onclick="bbstyle(0);" />
	<input type="button" class="button" accesskey="i" id="addbbcode2" name="addbbcode2" value="i" style="font-style:italic; width: 30px" onclick="bbstyle(2);" />
	<input type="button" class="button" accesskey="u" id="addbbcode4" name="addbbcode4" value="u" style="text-decoration: underline; width: 30px" onclick="bbstyle(4);" />
	<input type="button" class="button" accesskey="q" id="addbbcode6" name="addbbcode6" value="Quote" style="width: 50px" onclick="bbstyle(6);" />
	<input type="button" class="button" accesskey="c" id="addbbcode8" name="addbbcode8" value="Code" style="width: 40px" onclick="bbstyle(8);" />
	<input type="button" class="button" accesskey="l" id="addbbcode10" name="addbbcode10" value="List" style="width: 40px" onclick="bbstyle(10);" />
	<input type="button" class="button" accesskey="o" id="addbbcode12" name="addbbcode12" value="List=" style="width: 40px" onclick="bbstyle(12);" />
	<input type="button" class="button" accesskey="p" id="addbbcode14" name="addbbcode14" value="Img" style="width: 40px"  onclick="bbstyle(14);" />
	<input type="button" class="button" accesskey="w" id="addbbcode16" name="addbbcode16" value="URL" style="text-decoration: underline; width: 40px" onclick="bbstyle(16);" />
	<span onclick="bbstyle(-1);">Close Tags</span>

	<select id="addbbcode18" name="addbbcode18" onchange="bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
	<option style="color:black;" value="black">Default</option>
	<option style="color:darkred;" value="darkred">Dark Red</option>
	<option style="color:red;" value="red">Red</option>
	<option style="color:orange;" value="orange">Orange</option>
	<option style="color:brown;" value="brown">Brown</option>
	<option style="color:yellow;" value="yellow">Yellow</option>
	<option style="color:green;" value="green">Green</option>
	<option style="color:olive;" value="olive">Olive</option>
	<option style="color:cyan;" value="cyan">Cyan</option>
	<option style="color:blue;" value="blue">Blue</option>
	<option style="color:darkblue;" value="darkblue">Dark Blue</option>
	<option style="color:indigo;" value="indigo">Indigo</option>
	<option style="color:violet;" value="violet">Violet</option>
	<option style="color:white;" value="white">White</option>
	<option style="color:black;" value="black">Black</option>
	</select>
	
	<select id="addbbcode20" name="addbbcode20" onchange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')">
	<option value="7">Tiny</option>
	<option value="9">Small</option>
	<option value="12" selected="selected">Normal</option>
	<option value="18">Large</option>
	<option value="24">Huge</option>
	</select>
</div>
<div id="smiles">
	<span onclick="emoticon(':D');"><img src="{$baseurl}/libs/bbcode/smiles/icon_biggrin.gif" alt="Very Happy" title=":D" /></span>
	<span onclick="emoticon(':)');"><img src="{$baseurl}/libs/bbcode/smiles/icon_smile.gif" alt="Smile" title=":)" /></span>
	<span onclick="emoticon(':(');"><img src="{$baseurl}/libs/bbcode/smiles/icon_sad.gif" alt="Sad" title=":(" /></span>
	<span onclick="emoticon(':o');"><img src="{$baseurl}/libs/bbcode/smiles/icon_surprised.gif" alt="Surprised" title=":o" /></span>
	<span onclick="emoticon(':shock:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_eek.gif" alt="Shocked" title=":shock:" /></span>
	<span onclick="emoticon(':?');"><img src="{$baseurl}/libs/bbcode/smiles/icon_confused.gif" alt="Confused" title=":?" /></span>
	<span onclick="emoticon('8)');"><img src="{$baseurl}/libs/bbcode/smiles/icon_cool.gif" alt="Cool" title="8)" /></span>
	<span onclick="emoticon(':lol:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_lol.gif" alt="Laughing" title=":lol:" /></span>
	<span onclick="emoticon(':x');"><img src="{$baseurl}/libs/bbcode/smiles/icon_mad.gif" alt="Mad" title=":x" /></span>
	<span onclick="emoticon(':p');"><img src="{$baseurl}/libs/bbcode/smiles/icon_razz.gif" alt="Razz" title=":p" /></span>
	<span onclick="emoticon(':oops:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_redface.gif" alt="Embarassed" title=":oops:" /></span>
	<span onclick="emoticon(':cry:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_cry.gif" alt="Crying or Very sad" title=":cry:" /></span>
	<span onclick="emoticon(':evil:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_evil.gif" alt="Evil or Very Mad" title=":evil:" /></span>
	<span onclick="emoticon(':twisted:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_twisted.gif" alt="Twisted Evil" title=":twisted:" /></span>
	<span onclick="emoticon(':roll:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_rolleyes.gif" alt="Rolling Eyes" title=":roll:" /></span>
	<span onclick="emoticon(':wink:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_wink.gif" alt="Wink" title=":wink:" /></span>
    <span onclick="emoticon(':|');"><img src="{$baseurl}/libs/bbcode/smiles/icon_neutral.gif" alt="Neutral" title=":|" /></span>
    <span onclick="emoticon(':mrgreen:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_mrgreen.gif" alt="Mr. Green" title=":mrgreen:" /></span>
	<span onclick="emoticon(':!:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_exclaim.gif" alt="Exclamation" title=":!:" /></span>
	<span onclick="emoticon(':?:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_question.gif" alt="Question" title=":?:" /></span>
	<span onclick="emoticon(':idea:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_idea.gif" alt="Idea" title=":idia:" /></span>
	<span onclick="emoticon(':arrow:');"><img src="{$baseurl}/libs/bbcode/smiles/icon_arrow.gif" alt="Arrow" title=":arrow:" /></span>
</div>
</div>
