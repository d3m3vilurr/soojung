<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="{$baseurl}/templates/soogle/styles.css"/>
</head>
<body topmargin=2 marginheight=2>
<table border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
			<a href="{$baseurl}"><img src="{$baseurl}/templates/soogle/logo_sm.gif" width=150 height=55 alt="Go to Blog Home" border=0 vspace=12></a>
		</td>
		<td>
			&nbsp;&nbsp;
		</td>
		<td valign=top>
			<table cellpadding=0 cellspacing=0 border=0>
				<tr>
					<td colspan=2 height=14 valign=bottom>
						<table border=0 cellpadding=4 cellspacing=0>
							<tr>
								<td class=q>
									<font size=-1>
										<a class=q href="{$baseurl}">Main</a>&nbsp;&nbsp;&nbsp;
										{foreach from=$categories item=category}
										<a href="{$category.link}">{$category.name|capitalize}</a>&nbsp;&nbsp;&nbsp;
										{/foreach}
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td nowrap>
						<form action="index.php" method="get">
							<input type=text name=search size=41 maxlength=2048>
							<font size=-1><input type=submit value="Search"></font>
						</form>
					</td>
					<td nowrap>
						<font size=-2>&nbsp;&nbsp;<a href={$baseurl}/admin.php>Admin</a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
					</td>
				</tr>
			</table>
			<table cellpadding=0 cellspacing=0 border=0>
				<tr><td></td></tr>
				<tr><td height=7><img width=1 height=1 alt=""></td></tr>
			</table>
		</td>
	</tr>
</table>

<table style="clear: left" width=100% border=0 cellpadding=0 cellspacing=0>
	<tr><td bgcolor=#3366cc><img width=1 height=1 alt=""></td></tr>
</table>
