<!-- $Id: xs_header.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<html>
<head>
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="{XS_PATH}style.css" type="text/css">
<style>
<!--
body { background-color: #E5E5E5; background-image: url('{XS_PATH}images/top_bg2.gif'); background-position: top left; background-repeat: repeat-x; margin-top: 5px; margin-bottom: 5px; margin-left: 2px; margin-right: 2px; }
-->
</style>
</head>
<body>
<table width="100%" height="100%" cellspacing="0" cellpadding="2" class="bodyline">
<tr>
	<td align="left" valign="top" style="padding: 5px;">
<table width="100%" cellspacing="0" cellpadding="3" class="navbar">
<tr>
	<td align="left" nowrap="nowrap">
		<!-- BEGIN nav_left -->
		{nav_left.ITEM}
		<!-- END nav_left -->
	</td>
	<td align="right" nowrap="nowrap">
		<!-- BEGIN nav_right -->
		{nav_right.ITEM}
		<!-- END nav_right -->
	</td>
</tr>
</table>
<br />
