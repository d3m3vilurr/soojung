<cfsetting enablecfoutputonly="Yes">
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
 * File Name: fckeditor.cfm
 * 	ColdFusion integration. 
 * 	Note this module is created for use with Coldfusion 4.52 and above.
 * 	For a cfc version for coldfusion mx check the fckeditor.cfc.
 * 
 * 	Syntax: 
 * 
 * 	<cfmodule name="path/to/cfc/fckeditor" 
 * 		instanceName="myEditor"
 * 		toolbarSet="..."
 * 		width="..."
 * 		height="..:"
 * 		value="..."
 * 		config="..." 
 * 	>
 * 
 * Version:  2.0 Beta 2
 * Modified: 2004-05-27 12:24:11
 * 
 * File Authors:
 * 		Hendrik Kramer (hk@lwd.de)
--->
<!--- ::
	 * 	Attribute validation
	:: --->
<cfparam name="attributes.instanceName" type="string">
<cfparam name="attributes.width" 		type="string" default="100%">
<cfparam name="attributes.height" 		type="string" default="200">
<cfparam name="attributes.toolbarSet" 	type="string" default="Default">
<cfparam name="attributes.value" 		type="string" default="">
<cfparam name="attributes.basePath" 	type="string" default="/fckeditor/">
<cfparam name="attributes.checkBrowser" type="boolean" default="true">
<cfparam name="attributes.config" 		type="struct" default="#structNew()#">

<!--- ::
	 * check browser compatibility via HTTP_USER_AGENT, if checkBrowser is true
	:: --->
<cfif attributes.checkBrowser>

	<cfscript>
	sAgent = lCase( cgi.HTTP_USER_AGENT );
	isCompatibleBrowser = false;

	// check for Internet Explorer ( >= 5.5 )
	if( find( "msie", sAgent ) and not find( "mac", sAgent ) and not find( "opera", sAgent ) )
	{
		// try to extract IE version
		stResult = reFind( "msie ([5-9]\.[0-9])", sAgent, 1, true );
		if( arrayLen( stResult.pos ) eq 2 )
		{
			// get IE Version
			sBrowserVersion = mid( sAgent, stResult.pos[2], stResult.len[2] );
			if( sBrowserVersion GTE 5.5 )
				isCompatibleBrowser = true;
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
			if( sBrowserVersion GTE 20030210 )
				isCompatibleBrowser = true;
		}
	}
	</cfscript>

<cfelse>
	<!--- :: If we should not check browser compatibility, assume true :: --->
	<cfset isCompatibleBrowser = true>
</cfif>


<cfif isCompatibleBrowser>

	<!--- ::
		 * show html editor area for compatible browser
		:: --->

	<cfscript>
		// try to fix the basePath, if ending slash is missing
		if( len( attributes.basePath) and right( attributes.basePath, 1 ) is not "/" )
			attributes.basePath = attributes.basePath & "/";

		// construct the url
		sURL = attributes.basePath & "editor/fckeditor.html?InstanceName=" & attributes.instanceName;

		// append toolbarset name to the url
		if( len( attributes.toolbarSet ) )
			sURL = sURL & "&Toolbar=" & attributes.toolbarSet;

		// create configuration string: Key1=Value1&Key2=Value2&... (Key/Value:HTML encoded)
		sConfig = "";

		for( key in attributes.config )
		{
			if( len( sConfig ) )
				sConfig = sConfig & '&';
			sConfig = sConfig & HTMLEditFormat( key ) & '=' & HTMLEditFormat( attributes.config[key] );
		}
	</cfscript>

	<cfoutput>
	<div>
	<input type="hidden" id="#attributes.instanceName#" name="#attributes.instanceName#" value="#HTMLEditFormat(attributes.value)#" />
	<input type="hidden" id="#attributes.instanceName#___Config" value="#sConfig#" />
	<iframe id="#attributes.instanceName#___Frame" src="#sURL#" width="#attributes.width#" height="#attributes.height#" frameborder="no" scrolling="no"></iframe>
	</div>
	</cfoutput>

<cfelse>

	<!--- ::
		 * show	plain textarea for non compatible browser
		:: --->

	<cfscript>
		// append unit "px" for numeric width and/or height values
		if( isNumeric( attributes.width ) )
			attributes.width = attributes.width & "px";
		if( isNumeric( attributes.height ) )
			attributes.height = attributes.height & "px";
	</cfscript>

	<cfoutput>
	<div>
	<textarea name="#attributes.instanceName#" rows="4" cols="40" style="WIDTH: #width#; HEIGHT: #height#" wrap="virtual">#HTMLEditFormat(attributes.value)#</textarea>
	</div>
	</cfoutput>	

</cfif>

<cfsetting enablecfoutputonly="No">
