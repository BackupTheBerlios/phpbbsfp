<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_split_topic_type.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME  : lang_extend_split_topic_type.php
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
	$lang['Lang_extend_split_topic_type'] = 'Split Topic Type';
}

$lang['Split_settings']			= 'Split topics per type';
$lang['split_global_announce']	= 'Split global announcement';
$lang['split_announce']			= 'Split announcement';
$lang['split_sticky']			= 'Split sticky';
$lang['split_topic_split']		= 'Seperate topic types in different boxes';

?>