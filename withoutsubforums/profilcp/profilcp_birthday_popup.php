<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_birthday_popup.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilcp_birthday_popup.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !empty($setmodules) || !isset($mode) || $mode != 'birthday_popup' ) 
{
	return;
}

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}


//
// set the page title and include the page header
//
$page_title = $lang['Birthday'];
$gen_simple_header = TRUE;
include ($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// Set tpl
//
$template->set_filenames(array(
    'body' => 'profilcp/birthday_popup.tpl',
    )
);
//
// Assign Vars
//
$template->assign_vars(array(
    'L_MESSAGE' => sprintf($lang['birthday_msg'], $userdata['username'], $board_config['sitename']),
    'L_CLOSE_WINDOW' => $lang['Close_window'],
    )
);

//
// Ouput
//
$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>