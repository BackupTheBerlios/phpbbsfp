<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_privmsga.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilecp_privmsga.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

// start
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

if ( !empty($board_config['privmsg_disable']) )
{
	return;
}

if( !empty($setmodules) )
{
	$mode = $HTTP_GET_VARS['mode'];
	if ( $mode == 'privmsg' )
	{
		$pmmode = $HTTP_GET_VARS['pmmode'];
		define('IN_PCP', true);
		define('IN_PRIVMSG', true);
		switch ( $pmmode )
		{
			case 'newpm':
				include($phpbb_root_path . './includes/privmsga_popup.' . $phpEx);
				exit;
				break;
			case 'review':
				include_once($phpbb_root_path . './includes/privmsga_review.' . $phpEx);
				$view_user_id = intval($HTTP_GET_VARS[POST_USERS_URL]);
				$privmsg_recip_id = intval($HTTP_GET_VARS[POST_POST_URL]);
				privmsg_review($view_user_id, $privmsg_recip_id, false);
				break;
		}
	}
	pcp_set_menu('privmsg', 80, __FILE__, 'Private_Messaging', 'Private_Messaging' );
	return;
}

define('IN_PCP', true);
define('IN_PRIVMSG', true);

// get the standard program
include($phpbb_root_path . './privmsga.' . $phpEx);

?>