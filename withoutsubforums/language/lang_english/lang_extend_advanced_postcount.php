<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: lang_extend_advanced_postcount.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_extend_advanced_postcount.php [English]
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2004 Andrey Babak <minerva@club60.org>
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// admin part
if ($lang_extend_admin)
{
	$lang['Advanced_Postcount'] = 'Advanced Postcount';
	$lang['resync_post_count'] = 'Resync now';
	$lang['no_post_count'] = 'No post count in forum';
	$lang['no_post_count_explain'] = 'Forum IDs of forums you do not want users post to increase. Separate multiple forum IDs with commas.';
}
?>