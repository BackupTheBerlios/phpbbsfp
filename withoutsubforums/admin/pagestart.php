<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: pagestart.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : pagestart.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
    die("Hacking attempt");
}

define('IN_ADMIN', true);
// Include files
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

// Jnr. Admin
include_once($phpbb_root_path . 'includes/functions_jr_admin.' . $phpEx);
find_lang_file_nivisec('lang_jr_admin');

if (!$userdata['session_logged_in'])
{
    redirect(append_sid("login.$phpEx?redirect=admin/", true));
}
// Begin Junior Admin Bugfix -- By ToonArmy
/*elseif (($HTTP_GET_VARS['pane'] != 'left' || $HTTP_GET_VARS['pane'] != 'right') && basename($HTTP_SERVER_VARS['PHP_SELF']) == 'index.' . $phpEx)
{
	if (!jr_admin_secure(basename($HTTP_SERVER_VARS['PHP_SELF']))) {
        $template->assign_vars(array(
            'META' => "<meta http-equiv=\"refresh\" content=\"3;url=" . $phpbb_root_path . "index.$phpEx\" />")
        );
    	message_die(GENERAL_ERROR, $lang['Not_admin']);
	}
}*/
// End Junior Admin Bugfix 
else
{
	if ( isset($HTTP_GET_VARS['file']) )
	{
		$file = $HTTP_GET_VARS['file'] . '.' . $phpEx;
	}
	elseif ( isset($HTTP_POST_VARS['file']) )
	{
		$file = $HTTP_POST_VARS['file'] . '.' . $phpEx;
	}
	else
	{
		$file = basename(isset($HTTP_SERVER_VARS['REQUEST_URI']) ? $HTTP_SERVER_VARS['REQUEST_URI'] : $HTTP_SERVER_VARS['PHP_SELF']);
	}

	if ( !jr_admin_secure($file) )
	{
	    message_die(GENERAL_ERROR, $lang['Error_Module_ID'], '', __LINE__, __FILE__);
	}
}

// Original
// else if ($userdata['user_level'] != ADMIN)
// {
//     message_die(GENERAL_MESSAGE, $lang['Not_admin']);
// }

if ($HTTP_GET_VARS['sid'] != $userdata['session_id'])
{
    $url = str_replace(preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name'])), '', $HTTP_SERVER_VARS['REQUEST_URI']);
    $url = str_replace(preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path'])), '', $url);
    $url = str_replace('//', '/', $url);
    $url = preg_replace('/sid=([^&]*)(&?)/i', '', $url);
    $url = preg_replace('/\?$/', '', $url);
    $url .= ((strpos($url, '?')) ? '&' : '?') . 'sid=' . $userdata['session_id'];

    redirect("index.$phpEx?sid=" . $userdata['session_id']);
}

if (empty($no_page_header))
{
    // Not including the pageheader can be neccesarry if META tags are
    // needed in the calling script.
    include('./page_header_admin.'.$phpEx);
}

?>
