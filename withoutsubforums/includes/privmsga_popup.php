<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_popup.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsga_popup.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') || !defined('IN_PRIVMSG') )
{
    die('Hacking attempt');
}

// system func
include_once($phpbb_root_path . './includes/functions_sys.' . $phpEx);

// send popup
$page_title = _lang('Private_Messaging');
$gen_simple_header = true;
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->set_filenames(array(
    'body' => 'privmsga_popup.tpl')
);

$message = _lang('You_no_new_pm');
if ( $userdata['user_new_privmsg'] == 1 )
{
    $message = _lang('You_new_pm');
}
else if ( $userdata['user_new_privmsg'] > 1 )
{
    $message = _lang('You_new_pms');
}
$message .= '<br /><br />' . sprintf(_lang('Click_view_privmsg'), '<a href="' . append_sid("$main_pgm&folder=inbox") . '" onclick="jump_to_inbox();return false;" target="_new">', '</a>');

// There might be a bug in the line above and ths is the fix. - Wimpy hasn't tested either yet.
//$message .= '<br /><br />' . sprintf(_lang('Click_view_privmsg'), '<a href="' . append_sid("$main_pgm&folder=inbox") . '" onclick="jump_to_inbox();return false;" >', '</a>');

$template->assign_vars(array(
    'L_CLOSE_WINDOW'    => _lang('Close_window'),
    'L_MESSAGE'         => $message,
    )
);

$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>