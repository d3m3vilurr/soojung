<cfcomponent output="no" displayname="FCKEditor" hint="Create an instance of the FCKEditor.">
<!---
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fckeditor.cfc
 * 	ColdFusion MX integration. 
 * 	Note this CFC is created for use only with Coldfusion MX and above.
 * 	For older version, check the fckeditor.cfm.
 * 
 * 	Syntax: 
 * 
 * 	<cfscript>
 * 			fckEditor = createObject("component", "fckEditorV2/fckeditor");
 * 			fckEditor.instanceName="myEditor";
 * 			fckEditor.basePath="/fckEditorV2/";
 * 			fckEditor.value="This is my <strong>initial</strong> html text.";
 * 			fckEditor.width="100%";
 * 			fckEditor.height="200";
 * 	 	// ... additional parameters ...
 * 			fckEditor.create(); // create instance now.
 * 	</cfscript>
 * 
 * 	See your macromedia coldfusion mx documentation for more info.
 * 
 * 	*** Note: 
 * 	Do not use path names with a "." (dot) in the name. This is a coldfusion 
 * 	limitation with the cfc invocation.
 * 
 * Version:  2.0 Beta 2
 * Modified: 2004-05-27 12:39:32
 * 
 * File Authors:
 * 		Hendrik Kramer (hk@lwd.de)
--->
<cffunction 
	name="create" 
	access="public" 
	output="Yes" 
	returntype="void" 
	hint="Initialize the FCKEditor instance."
>

	<cfparam name="this.instanceName" type="string" />
	<cfparam name="this.width" type="string" default="100%" />
	<cfparam name="this.height" type="string" default="200" />
	<cfparam name="this.toolbarSet" type="string" default="Default" />
	<cfparam name="this.value" type="string" default="" />
	<cfparam name="this.basePath" type="string" default="/fckeditor/" />
	<cfparam name="this.checkBrowser" type="boolean" default="true" />
	<cfparam name="this.config" type="struct" default="#structNew()#" />

	<cfscript>
		// display the html editor or a plain textarea?
		if( isCompatibleBrowser() )
			showHTMLEditor();
		else
			showTextArea();
	</cfscript>

</cffunction>

<cffunction
	name="isCompatibleBrowser"
	access="private"
	output="no"
	returnType="boolean"
	hint="Check browser compatibility via HTTP_USER_AGENT, if checkBrowser is true"
>

	<cfscript>
		var sAgent = lCase( cgi.HTTP_USER_AGENT );
		var stResult = "";
		var sBrowserVersion = "";

		// do not check if argument "checkBrowser" is false
		if( not this.checkBrowser )
			return true;

		// check for Internet Explorer ( >= 5.5 )
		if( find( "msie", sAgent ) and not find( "mac", sAgent ) and not find( "opera", sAgent ) )
		{
			// try to extract IE version
			stResult = reFind( "msie ([5-9]\.[0-9])", sAgent, 1, true );
			if( arrayLen( stResult.pos ) eq 2 )
			{
				// get IE Version
				sBrowserVersion = mid( sAgent, stResult.pos[2], stResult.len[2] );
				return ( sBrowserVersion GTE 5.5 );
			}
		}
		// check for Gecko ( >= 20030210+ )
		else if( find( "gecko", sAgent ) )
		{
			// try to extract Gecko version date
			stResult = reFind( "gecko/(200[3-9][0-1][0-9][0-3][0-9])", sAgent, 1, true );
			if( arrayLen( stResult.pos ) eq 2 )
			{
				// get Gecko build (i18n date)
				sBrowserVersion = mid( sAgent, stResult.pos[2], stResult.len[2] );
				return ( sBrowserVersion GTE 20030210 );
			}
		}

		return false;
	</cfscript>
</cffunction>

<cffunction
	name="showTextArea"
	access="private"
	output="yes"
	returnType="void"
	hint="Create a textarea field for non-compatible browsers."
>

	<cfscript>
		var width = this.width;
		var height = this.height;
		
		// append unit "px" for numeric width and/or height values
		if( isNumeric( width ) )
			width = width & "px";
		if( isNumeric( height ) )
			height = height & "px";
	</cfscript>

	<cfoutput>
	<div>
	<textarea name="#this.instanceName#" rows="4" cols="40" style="WIDTH: #width#; HEIGHT: #height#" wrap="virtual">#HTMLEditFormat(this.value)#</textarea>
	</div>
	</cfoutput>

</cffunction>

<cffunction
	name="showHTMLEditor"
	access="private"
	output="yes"
	returnType="void"
	hint="Create the html editor instance for compatible browsers."
>
	
	<cfscript>
		var sConfig = "";
		var sURL = "";
		var basePath = this.basePath;
		
		// try to fix the basePath, if ending slash is missing
		if( len( basePath) and right( basePath, 1 ) is not "/" )
			basePath = basePath & "/";

		// construct the url
		sURL = basePath & "editor/fckeditor.html?InstanceName=" & this.instanceName;

		// append toolbarset name to the url
		if( len( this.toolbarSet ) )
			sURL = sURL & "&Toolbar=" & this.toolbarSet;

		// create configuration string: Key1=Value1&Key2=Value2&... (Key/Value:HTML encoded)
		for( key in this.config )
		{
			if( len( sConfig ) )
				sConfig = sConfig & '&';
			sConfig = sConfig & HTMLEditFormat(key) & '=' & HTMLEditFormat(this.config[key]);
		}
	</cfscript>

	<cfoutput>
	<div>
	<input type="hidden" id="#this.instanceName#" name="#this.instanceName#" value="#HTMLEditFormat(this.value)#" />
	<input type="hidden" id="#this.instanceName#___Config" value="#sConfig#" />
	<iframe id="#this.instanceName#___Frame" src="#sURL#" width="#this.width#" height="#this.height#" frameborder="no" scrolling="no"></iframe>
	</div>
	</cfoutput>

</cffunction>

</cfcomponent>