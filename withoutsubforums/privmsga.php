<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : privmsga.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( defined('IN_PCP') )
{
    // check context
    if ( !defined('IN_PHPBB') )
    {
        die('Hacking attempt');
        exit;
    }

    // save called pgm
    $main_pgm = "./profile.$phpEx?mode=privmsg";
}
else
{
    define('IN_PHPBB', true);
    define('IN_CASHMOD', true);
    $phpbb_root_path = './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.'.$phpEx);

    // save called pgm
    $main_pgm = "./privmsga.$phpEx?";
}

define('IN_PRIVMSG', true);
include_once($phpbb_root_path . 'includes/functions_messages.' . $phpEx);
include($phpbb_root_path . 'attach/posting_attachments.'.$phpEx);
include($phpbb_root_path . 'attach/pm_attachments.'.$phpEx);

@include($phpbb_root_path . 'includes/def_icons.' . $phpEx);

//
// Start session management
//
if ( !defined('IN_PCP') )
{
    $userdata = session_pagestart($user_ip, PAGE_PRIVMSGS);
    init_userprefs($userdata);
}
//
// End session management
//

// preferences
$cfg_max_userlist   = isset($board_config['max_userlist']) ? intval($board_config['max_userlist']) : 2;
$cfg_max_inbox      = isset($board_config['max_inbox_privmsgs']) ? intval($board_config['max_inbox_privmsgs']) : 0;
$cfg_max_outbox     = isset($board_config['max_outbox_privmsgs']) ? intval($board_config['max_outbox_privmsgs']) : 0;
$cfg_max_sentbox    = isset($board_config['max_sentbox_privmsgs']) ? intval($board_config['max_sentbox_privmsgs']) : 0;
$cfg_max_savebox    = isset($board_config['max_savebox_privmsgs']) ? intval($board_config['max_savebox_privmsgs']) : 0;
$cfg_save_to_mail   = isset($board_config['apm_save_to_mail']) ?  intval($board_config['apm_save_to_mail']) : 0;

//
// Is PM disabled?
//
if ( !empty($board_config['privmsg_disable']) )
{
    message_die(GENERAL_MESSAGE, 'PM_disabled');
}

// only logged peoples
if ( !$userdata['session_logged_in'] )
{
    redirect(append_sid("login.$phpEx?redirect=$main_pgm", true));
}

// censor word
$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

//--------------------------
//
//  get parameters
//
//--------------------------
_hidden_init();

// vars
$view_user_id       = _read_var(POST_USERS_URL, 1, $userdata['user_id']);
$pmmode             = _read_var('pmmode');
$pm_start           = _read_var('start', 1);
$msg_days           = _read_var('msgdays', 1);
$privmsg_recip_id   = _read_var(POST_POST_URL, 1);
if ( _button_var('return_main') )
{
    $pmmode = '';
    $pm_start = 0;
}

// user
if ( $view_user_id != ANONYMOUS )
{
    $sql = "SELECT * FROM " . USERS_TABLE . " WHERE user_id = $view_user_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read user data', '', __LINE__, __FILE__, $sql);
    }
}
if ( ($view_user_id == ANONYMOUS) || (!$view_userdata = $db->sql_fetchrow($result)) )
{
    message_die(GENERAL_MESSAGE, 'No_such_user');
}

// verify the user is authorized
check_user($view_userdata, $main_pgm);

// read user folders
$folders = get_user_folders($view_user_id);

//--------------------------
//
//  get actions
//
//--------------------------

if ( in_array($pmmode, array('move_pm', 'savemail', 'view')) )
{
    include($phpbb_root_path . './includes/privmsga_view.' . $phpEx);
}
if ( in_array($pmmode, array('post', 'reply', 'quote', 'forward', 'edit', 'delete')) )
{
    include($phpbb_root_path . './includes/privmsga_post.' . $phpEx);
}
if ( in_array($pmmode, array('flist', 'fcreate', 'fedit', 'fdelete', 'fmoveup', 'fmovedw', 'rlist', 'rcreate', 'redit', 'rdelete')) )
{
    include($phpbb_root_path . './includes/privmsga_folders.' . $phpEx);
}
if ( in_array($pmmode, array('search')) )
{
    include($phpbb_root_path . './includes/privmsga_search.' . $phpEx);
}
if ( in_array($pmmode, array('move_pms', 'delete_pms', 'savemails', '')) )
{
    include($phpbb_root_path . './includes/privmsga_list.' . $phpEx);
}
if ( in_array($pmmode, array('newpm')) )
{
    include($phpbb_root_path . './includes/privmsga_popup.' . $phpEx);
}
if ( in_array($pmmode, array('review')) )
{
    include_once($phpbb_root_path . './includes/privmsga_review.' . $phpEx);
    $view_user_id = intval($HTTP_GET_VARS[POST_USERS_URL]);
    $privmsg_recip_id = intval($HTTP_GET_VARS[POST_POST_URL]);
    privmsg_review($view_user_id, $privmsg_recip_id, false);
}

?>