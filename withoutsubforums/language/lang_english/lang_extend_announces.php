<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_announces.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_extend_announces.php
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
	$lang['Lang_extend_announces'] = 'Announces Suite';
}

$lang['Board_announcement']						= 'Board Announcements';
$lang['announcement_duration']					= 'Announcement duration';
$lang['announcement_duration_explain']			= 'This is the number of days an announcement remains. Use -1 in order to set it permanent';
$lang['Announce_settings']						= 'Announcements';
$lang['announcement_date_display']				= 'Display announcement dates';
$lang['announcement_display']					= 'Display board announcements on index';
$lang['announcement_display_forum']				= 'Display board announcements on forums';
$lang['announcement_split']						= 'Split announcement type in the board announcement box';
$lang['announcement_forum']						= 'Display the forum name under the announcement title in the board announcement box';
$lang['announcement_prune_strategy']			= 'Announcement prune strategy';
$lang['announcement_prune_strategy_explain']	= 'This is what will be the type of the announcement topic after being pruned';

$lang['Global_announce']						= 'Global announce';
$lang['Sorry_auth_global_announce']				= 'Sorry, but only %s can post global announcements in this forum.';
$lang['Post_Global_Announcement']				= 'Global Announcement';
$lang['Topic_Global_Announcement']				= '<b>Global Announcement:</b>';

$lang['Announces_from_to']						= '(from %s to %s)';

?>