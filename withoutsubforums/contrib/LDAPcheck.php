<?php

/***************************************************************************
    LDAPcheck.php
 *                            -------------------
 *   begin                : Monday, April 27, 2004
 *   copyright            : (C)2004 Adam Larsen
 *   email                : Adam@ACSoft.net
 *   package              : LDAP Auth MOD
 *   version              : 1.1.8
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/*
 *   DEFAULT VALUES for you site (OPTIONAL)
 */
$ldapServer = '';
$ldapBase = '';
$ldapUser = '';
$ldappass = '';
$ldapSearchValue = '';

$ldapSearchField = 'user';
$ldap_gid = 'member';
$justthese = array($ldapSearchField,'uid','samaccountname','displayname','physicaldeliveryofficename','mail','member','memberof') ;

$SearchSelect = 2;  // 1 = User Name, 2 = User DN




















?>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"  />
<!-- link rel="stylesheet" href="templates/subSilver/subSilver.css" type="text/css" -->
<style type="text/css">
<!--

/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/


 /* General page style. The scroll bar colours only visible in IE5.5+ */
body {
	background-color: #E5E5E5;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
}

/* General font families for common tags */
font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
p, td		{ font-size : 11; color : #000000; }
a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900; }
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}
h1,h2		{ font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; font-size : 22px; font-weight : bold; text-decoration : none; line-height : 120%; color : #000000;}


/* This is the border line & background colour round the entire page */
.bodyline	{ background-color: #FFFFFF; border: 1px #98AAB1 solid; }

/* This is the outline round the main forum tables */
.forumline	{ background-color: #FFFFFF; border: 2px #006699 solid; }


/* Main table cell colours and backgrounds */
td.row1	{ background-color: #EFEFEF; }
td.row2	{ background-color: #DEE3E7; }
td.row3	{ background-color: #D1D7DC; }


/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
		background-color: #FFFFFF;
		background-image: url(templates/subSilver/images/cellpic2.jpg);
		background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th	{
	color: #FFA34F; font-size: 11px; font-weight : bold;
	background-color: #006699; height: 25px;
	background-image: url(templates/subSilver/images/cellpic3.gif);
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-image: url(templates/subSilver/images/cellpic1.gif);
			background-color:#D1D7DC; border: #FFFFFF; border-style: solid; height: 28px;
}


/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: #FFFFFF; border-style: solid; height: 28px; }
td.row3Right,td.spaceRow {
	background-color: #D1D7DC; border: #FFFFFF; border-style: solid; }

th.thHead,td.catHead { font-size: 12px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop	 { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }


/* The largest text used in the index page title and toptic title etc. */
.maintitle	{
			font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
			text-decoration: none; line-height : 120%; color : #000000;
}


/* General text */
.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #000000; }
a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #DD6900; text-decoration: underline; }


/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : 11px; color : #000000 }
a.mainmenu		{ text-decoration: none; color : #006699;  }
a.mainmenu:hover{ text-decoration: underline; color : #DD6900; }


/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #006699}
a.cattitle		{ text-decoration: none; color : #006699; }
a.cattitle:hover{ text-decoration: underline; }


/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: 12px; color : #006699; }
a.forumlink 	{ text-decoration: none; color : #006699; }
a.forumlink:hover{ text-decoration: underline; color : #DD6900; }


/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: 11px; color : #000000;}
a.nav			{ text-decoration: none; color : #006699; }
a.nav:hover		{ text-decoration: underline; }



/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : 11px; color : #000000;}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : 10px; color : #000000; }


/* The content of the posts (body of text) */
.postbody { font-size : 12px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : #006699 }
a.postlink:visited { text-decoration: none; color : #5493B4; }
a.postlink:hover { text-decoration: underline; color : #DD6900}


/* Quote & Code blocks */
.code {
	font-family: Courier, 'Courier New', sans-serif; font-size: 11px; color: #006600;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #444444; line-height: 125%;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}


/* Copyright and bottom info */
.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
a.copyright		{ color: #444444; text-decoration: none;}
a.copyright:hover { color: #000000; text-decoration: underline;}


/* Form elements */
input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color : #FFFFFF;
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color : #EFEFEF;
	color : #000000;
	font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif;
}

/* The main submit button option */
input.mainoption {
	background-color : #FAFAFA;
	font-weight : bold;
}

/* None-bold submit button */
input.liteoption {
	background-color : #FAFAFA;
	font-weight : normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: #DEE3E7; border-style: none; }


/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("templates/subSilver/formIE.css");
-->
</style>
</HEAD>
<body bgcolor="#E5E5E5" text="#000000" link="#006699" vlink="#5493B4">

<?php

if (isset($_POST['ServerInfo']))
	$ldapServer = $_POST['ServerInfo'];
if (isset($_POST['BaseDN']))
	$ldapBase = $_POST['BaseDN'];
if (isset($_POST['User']))
	$ldapUser = $_POST['User'];
	$ldapUser = str_replace("\\,", "\,",$ldapUser);
if (isset($_POST['UserPass']))
	$ldappass = $_POST['UserPass'];
if (isset($_POST['SearchValue']))
	$ldapSearchValue = $_POST['SearchValue'];
if (isset($_POST['SearchField']))
	$ldapSearchField = $_POST['SearchField'];
if (isset($_POST['SearchSelect']))
	$SearchSelect = $_POST['SearchSelect'];
if (isset($_POST['ldap_gid']))
	$ldap_gid = $_POST['ldap_gid'];

	echo '<FORM action="LDAPcheck.php" method="POST">';
	echo '<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">';
	echo '<tr><th class="thHead" colspan="2">Authentication Settings</th></tr>';

	echo '<TR><TD class="row1"><B>LDAP Host Name</B> </TD><TD class="row2"><input size=40 type="text" name="ServerInfo" value="'.$ldapServer.'"></TD></TR>';
	echo '<TR><TD class="row1"><B>Base DN</B><br><span class="gensmall">Base DN, which will be used as start point for LDAP directory</span> </TD><TD class="row2"><input size=60 type="text" name="BaseDN" value="'.$ldapBase.'"></TD></TR>';
	echo '<TR><TD class="row1"><B>Proxy DN</B> (OPTIONAL)<br><span class="gensmall">Used if your LDAP server does not allow anonymous access (I.E. Active Directory).  This must be the FULL distinguished name of a user that has read access to your LDAP server</span></TD><TD class="row2"><input size=60 type="text" name="User" value="'.$ldapUser.'"></TD></TR>';
	echo '<TR><TD class="row1"><B>Proxy DN Password</B> (OPTIONAL)<br><span class="gensmall">This password is stored in CLEAR text, in your Database!!!  Make sure this user only has read access to LDAP and nothing else (make it a guest).  And make sure users can not get to your DB</span> </TD><TD class="row2"><input size=40 type="password" name="UserPass" value="'.$ldappass.'"></TD></TR>';
	echo '<tr><th class="thHead" colspan="2">Check User logon</th></tr>';
	echo '<TR><TD class="row1"><B>LDAP User Name</B> </TD><TD class="row2"><input size=40 type="text" name="SearchValue" value="'.$ldapSearchValue.'"></TD></TR>';
	echo '<TR><TD class="row1"><B>LDAP User ID Field</B><br/><span class="gensmall">LDAP User ID Field, what LDAP property/field do you want to use as your forum user names. (default = \'uid\', Active Directory = \'samaccountname\')</span> </TD><TD class="row2"><input size=40 type="text" name="SearchField" value="'.$ldapSearchField.'"><BR>lower CASE only</TD></TR>';
	echo '<tr>
      		<td class="row1"><B>LDAP Group Membership Field</B><br/><span class="gensmall">What LDAP property/field do you want to use to determen that groups a user is a member of.  This will be a user property. (default = \'member\', Active Directory = \'memberof\')</span></td>
      		<td class="row2"><input class="post" type="text" maxlength="255" size="60" name="ldap_gid" value="' . $ldap_gid . '" /></td>
   	</tr>';
	echo '<TR><TD class="row1"><B>Search Type</B>
	<br><span class="gensmall">
	Use <I>User Name</I> to run the search they way it\'t run in the forum<BR>
	Use <I>User DN</I> to see what basic Fields are avalable in your LDAP server<BR>
	note: NOT ALL fields are shown in <I>User DN</I> check you vender documention to all all fields avalable to you.
	</span>
	</TD><TD class="row2"><SELECT name="SearchSelect"><OPTION ';
		if ($SearchSelect == 1) echo 'selected';
	echo ' value="1">User Name</OPTION><OPTION ';
		if ($SearchSelect == 2) echo 'selected';
	echo ' value="2">User DN</OPTION>
		</SELECT></TD></TR>';
	echo '<TR><TD colspan=2 class="catBottom"><P ALIGN=\'center\'><input type="submit" name="submit" value="Submit" class="mainoption" /></P></TD></TR>';
	echo '</TABLE></FORM>';


	echo '<HR><CENTER><B>LDAP Auth Values Check</B><BR> PM this section to TehTarget at <A HREF="http://www.phpbb.com">www.phpbb.com</A> if you have questions.</CENTER><HR>';

	echo 'Base DN...<B>'.$ldapBase.'</B><BR> ';
	echo 'User Name...<B>'.$ldapUser.'</B><BR> ';
	echo 'Search Value...<B>'.$ldapSearchValue.'</B><BR> ';
	echo 'Search Field...<B>'.$ldapSearchField.'</B><BR> ';
	echo 'Search Mode...<B>'.$SearchSelect.'</B><BR> ';

/*
 * Setup
 */

define('LDAP_AUTH_OK', 1);
define('LDAP_INVALID_USERNAME', 2);
define('LDAP_INVALID_PASSWORD', 4);
define('ALLOW_BASIC_AUTH', true);
include('includes/functions_ldap_groups.php');
global $board_config;
$board_config["ldap_proxy_dn"] = $ldapUser;
$board_config["ldap_proxy_dn_pass"] = $ldappass;
$board_config['ldap_dn']  = $ldapBase;
$board_config['ldap_uid'] = $ldapSearchField;
$board_config['ldap_host'] = $ldapServer;
$board_config['ldap_port'] = '389';
$board_config['ldap_gid'] = $ldap_gid;

echo '<pre>';
/*
 * try to connect to the server
 */
echo 'Connecting to LDAP Server... ';
$ldapConn = ldap_connect($ldapServer);
$aldapConn = ldap_connect($ldapServer);
if (!$ldapConn || !$aldapConn)
{
  echo 'Check that the server you entered is correct and that you can connect to it';
  die('<B>Cannot connect to LDAP directory</B><BR> ');
}
else
	echo '<B>OK</B><BR> ';

/*
 * bind
 */
 echo 'anonymous bind to LDAP Server...';
$aldapBind = ldap_bind($aldapConn);
if ($aldapBind == false)
{
	echo('<B>	Cannot anonymous Bind to LDAP server</B><BR> ');
}
else
	echo '<B>OK</B><BR> ';

echo 'Proxy bind to LDAP Server...';
If ($ldapUser == '' || $ldappass == '')
{
	echo 'no user name or password giving, bypassing proxy bind<BR> ';
	$ldapBind = false;
}
else
{

	$ldapBind = ldap_bind($ldapConn,$ldapUser,$ldappass);
	if ($ldapBind == false)
	{
		echo '<B>	Cannot Proxy Bind to LDAP server</B><BR> ';
		echo '		If you can get data via anonymouse binding then you don\'t need Proxy.<BR>  <BR> ';
		echo '		If you do need proxy binding then check the following;<BR> ';
		echo '		Check that your User Name is the Full distinguished name for you LDAP Server<BR> ';
		echo '		and that your password is correct<BR> ';
	}
	else
		echo '<B>OK</B><BR> ';
}
if ($ldapBind == false && $aldapBind == false)
	die ('<CENTER><H2>CRITICAL ERROR: Could not BIND to server</H2></CENTER>');

/*
 * set the ldap options
 */
ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($aldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);

/*
 *  Check that we have the correct Search Field and the other fields we need are there
 */
echo 'Checking User info...';
if ($ldapSearchValue == '')
{
	echo '<B>Can\'t check LDAP values with out a User Name</B><BR> ';
	die ('	Please enter a Full Distinguished Name<BR> ');
}
else
	echo '<B>OK</B><BR> ';
echo 'Checking Proxy LDAP values...';
if ($ldapBind == false)
{
	echo '<B>Didn\'t Bind - Bypassing</B><BR> ';
}
else
{
		$pldapSearch = ldap_read($ldapConn, $ldapUser, 'objectclass=user',$justthese);
		$pldapResults = ldap_get_entries($ldapConn, $pldapSearch);
		$rtnOK = true;
		$rtnstr = '	Open User...';
		if ($pldapSearch == false)
		{
			$rtnstr = $rtnstr.'<B>ERROR</B><BR> ';
			$rtnOK = false;
		}
		else
		{
			if (!$pldapResults['count'] == 1)
			{
					$rtnstr = $rtnstr.'<B>ERROR</B><BR> ';
					$rtnOK = false;
			}
			else
			{
				$rtnstr = $rtnstr.'<B>OK</B><BR> ';
				$rtnstr = $rtnstr."	User Defind - Search Field...";
				if (isset($pldapResults[0][$ldapSearchField]))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0][$ldapSearchField][0].'<BR> ';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR> ";

				$rtnstr = $rtnstr."	UID - Search Field...";
				if (isset($pldapResults[0]['uid']))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0]['uid'][0].'<BR> ';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR> ";

				$rtnstr = $rtnstr."	SamAccountname - Search Field...";
				if (isset($pldapResults[0]['samaccountname']))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0]['samaccountname'][0].'<BR> ';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR> ";

				$rtnstr = $rtnstr."	DisplayName Field...";
				if (isset($pldapResults[0]['displayname']))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0]['displayname'][0].'<BR> ';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR> ";

				$rtnstr = $rtnstr."	physical delivery office name Field...";
				if (isset($pldapResults[0]['physicaldeliveryofficename']))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0]['physicaldeliveryofficename'][0].'<BR> ';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR> ";

				$rtnstr = $rtnstr."	Mail Field...";
				if (isset($pldapResults[0]['mail']))
					$rtnstr = $rtnstr.' <B>OK</B> : '.$pldapResults[0]['mail'][0].'<BR>';
				else
					$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";
			}

		}

		if (!$rtnOK)
			echo '<B>ERROR</B><BR>'.$rtnstr;
		else
			echo '<B>OK</B><BR>'.$rtnstr;
}
$rtnstr = '';

echo ' <BR>';
echo 'Checking anonymous LDAP values...';
if ($aldapBind == false)
{
	echo '<B>Didn\'t Bind - Bypassing</B><BR>';
}
else
{
		$aldapSearch = ldap_read($aldapConn, $ldapUser, 'objectclass=user', $justthese);
		//$aldapSearch = $pldapSearch;
		if ($aldapSearch != true)
		{
			$rtnOK = false;
			$rtnstr = '	Open User...';
			$rtnstr = $rtnstr.'<B>ERROR</B><BR>';
		}
		else
		{
			$aldapResults = ldap_get_entries($aldapConn, $aldapSearch);
			$rtnOK = true;
			$rtnstr = '	Open User...';
			if ($aldapSearch == false)
			{
				$rtnstr = $rtnstr.'<B>ERROR</B><BR>';
				$rtnOK = false;
			}
			else
			{
				if (!$aldapResults['count'] == 1)
				{
						$rtnstr = $rtnstr.'<B>ERROR</B><BR>';
						$rtnOK = false;
				}
				else
				{
					$rtnstr = $rtnstr.'<B>OK</B><BR>';
					$rtnstr = $rtnstr."	User Defind - Search Field...";
					if (isset($aldapResults[0][$ldapSearchField]))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0][$ldapSearchField][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";

					$rtnstr = $rtnstr."	UID - Search Field...";
					if (isset($aldapResults[0]['uid']))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0]['uid'][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";

					$rtnstr = $rtnstr."	SamAccountname - Search Field...";
					if (isset($aldapResults[0]['samaccountname']))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0]['samaccountname'][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";

					$rtnstr = $rtnstr."	DisplayName Field...";
					if (isset($aldapResults[0]['displayname']))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0]['displayname'][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";

					$rtnstr = $rtnstr."	physical delivery office name Field...";
					if (isset($aldapResults[0]['physicaldeliveryofficename']))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0]['physicaldeliveryofficename'][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";

					$rtnstr = $rtnstr."	Mail Field...";
					if (isset($aldapResults[0]['mail']))
						$rtnstr = $rtnstr.' <B>OK</B> : '.$aldapResults[0]['mail'][0].'<BR>';
					else
						$rtnstr = $rtnstr."<B>NOT FOUND</B><BR>";
				}
			}
		}

		if (!$rtnOK)
			echo $rtnstr;
			//echo '<B>ERROR</B><BR>'.$rtnstr;
		else
			echo '<B>OK</B><BR>'.$rtnstr;
}

	echo ' <BR><B>NOTE:</B> Some values maybe good, but say <B>NOT FOUND </B>if the user you entered does not have them set.  <BR>See if they are set in the next section.<BR>';

/*
 * search the LDAP server
 */
//$ldapSearch = ldap_search($ldapConn, $ldapBase, $ldapSearch . '=' . $ldapSearchValue, $justthese);
if ($SearchSelect == 2)
	$ldapSearch = ldap_search($ldapConn, $ldapBase, 'distinguishedname='.$ldapUser);
else
	$ldapSearch = ldap_search($ldapConn, $ldapBase, $ldapSearchField . '=' . $ldapSearchValue);
If ($ldapSearch == false)
{
	die('Search Faild');
}

echo "".$ldapSearch."";
$ldapResults = ldap_get_entries($ldapConn, $ldapSearch);
echo $ldapResults["count"]." entries returned";


echo '<HR><CENTER><B>ALL Data Returned</B><BR>Data from LDAP search, if empty then search failed. <BR> And your forum is not going to work.</CENTER><HR>';

for ($item = 0; $item < $ldapResults['count']; $item++)
{
  echo $ldapResults[$item]['dn']."\n";
  for ($attribute = 0; $attribute < $ldapResults[$item]['count']; $attribute++)
  {
   	$data = $ldapResults[$item][$attribute];
   	//echo $data."  ".$ldapResults[$item][$data][0]."";
  	echo "    --------------$data--------------------\n";
	for ($detail = 0; $detail < $ldapResults[$item][$data]['count']; $detail++)
  	{
   		if ($data == 'objectsid' | $data == 'objectguid')
   		{
   			$entry = ldap_first_entry($ldapConn, $ldapSearch);
   			$value = ldap_get_values_len($ldapConn, $entry, $data);
   			echo ("      ".$value[0])."  Len = ".strlen($value[0])."\n";
   		}
		else
		{
			$called = $ldapResults[$item][$data][$detail];
   			echo "      ".$called."\n";
		}
  	}
  }
  echo "=====================================================================================\n";
if ($SearchSelect != 2)
{
  echo "     LDAP Group this user is apart of\n";
  echo "=====================================================================================\n";
$ldapGroup = new ldapGroups();
$ldapGroup->ldap_members_set($ldapSearchValue);

	// Go thought the list of groups that they are members of in LDAP
	foreach($ldapGroup->ldapMembers as $value)
	{
		echo "	". $value."\n";
	}
}
}
echo '</pre>';
// ----------------------------------------------------
// ldap_connect_ex()
//
// Connects to LDAP on specifing port, if it was configured
// using Authentication Settings in Control Panel
// ----------------------------------------------------
function ldap_connect_ex() {
	global $board_config;

	if ($board_config['ldap_port'] != '') {
		$connection  = ldap_connect($board_config['ldap_host'], $board_config['ldap_port']);
		if (!$connection && $board_config['ldap_host2'] != '')
		{
			//Unable to connect the host 1, try host 2
			$connection  = ldap_connect($board_config['ldap_host2'], $board_config['ldap_port2']);
		}
	}
	else {
		$connection  = ldap_connect($board_config['ldap_host']);
		if (!$connection && $board_config['ldap_host2'] != '')
		{
			//Unable to connect the host 1, try host 2
			$connection  = ldap_connect($board_config['ldap_host2']);
		}
	}
	return $connection;
}

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	echo '<HR>ERROR: ' . $msg_text . '<BR> line: ' . $err_line . '<BR> File: ' . $err_file . '<HR>';
}
class ldapraw {
	var $rawData;
	var $conn;
	var $srchRslt;

	function ldap_get_values_raw()
	{
		// will use ldap_get_values_len() instead and build the array
		// note: it's similar with the array returned by
		// ldap_get_entries() except it has no "count" elements
		$i=0;
		$entry = ldap_first_entry($this->conn, $this->srchRslt);
		do {
		     $attributes = ldap_get_attributes($this->conn, $entry);
		     for($j=0; $j<$attributes['count']; $j++) {
		       $values = ldap_get_values_len($this->conn, $entry,$attributes[$j]);
		       $this->rawData[$i][$attributes[$j]] = $values;
		     }
		     $i++;
		   }
		while ($entry = ldap_next_entry($this->conn, $entry));
		//we're done
		return ($this->rawData);
	}
}

?>

</BODY>