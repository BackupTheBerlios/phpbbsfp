<?php
//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_pcp_addons.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_extend_pcp_addons.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
    $lang['Lang_extend_pcp_addons'] = 'Add-ons for Profile Control Panel';
}

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
$lang['Holidays'] = 'On Holiday';
$lang['No_holidays_specify'] = 'Unknown';
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

//-- mod : add-on Country Flags for PCP ------------------------------------------------------------
//-- add
$lang['Country_Flag'] = 'Country';
$lang['Select_Country'] = 'Select Country';
//-- end mod : add-on Country Flags for PCP --------------------------------------------------------
?>