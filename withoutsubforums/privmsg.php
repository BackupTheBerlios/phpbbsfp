<?php
//-- mod : sub-template ----------------------------------------------------------------------------
//-- mod : profile cp ------------------------------------------------------------------------------
//-- mod : avanced privmsg -------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsg.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : privmsg.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
define('IN_CASHMOD', true);

//-- mod : sub-template ----------------------------------------------------------------------------
//-- add
define('IN_PRIVMSG', true);
//-- fin mod : sub-template ------------------------------------------------------------------------

$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//-- mod : avanced privmsg -------------------------------------------------------------------------
//-- add
$mode = htmlspecialchars($HTTP_GET_VARS['mode']);
if ($mode == 'read')
{
    $mode = 'view';
}
$user_id = intval($HTTP_GET_VARS[POST_USERS_URL]);
$privmsg_id = intval($HTTP_GET_VARS[POST_POST_URL]);

$main_pgm = defined('ADMIN_FOUNDER') ? "./profile.$phpEx?mode=privmsg" : "./privmsga.$phpEx?";

/* If the line above is causing problems, this line below might be the fix. Unconfirmed!
$main_pgm = defined('ADMIN_FOUNDER') ? "./profile.$phpEx?mode=privmsg" : "privmsga.$phpEx";
*/

if ( empty($user_id) )
{
	redirect(append_sid("$main_pgm&pmmode=$mode&" . POST_POST_URL . "=$privmsg_id"));
}
else
{
    redirect(append_sid("$main_pgm&pmmode=$mode&tousers=$user_id&" . POST_POST_URL . "=$privmsg_id"));
}

//-- fin mod : avanced privmsg ---------------------------------------------------------------------

//
// Is PM disabled?
//
if ( !empty($board_config['privmsg_disable']) )
{
    message_die(GENERAL_MESSAGE, 'PM_disabled');
}

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
$get_vars = array('folder', 'sid', 'mode', 'start', 'msgdays', POST_POST_URL, POST_USERS_URL);
$s_call = '';
$newpm = false;
for ($i = 0; $i < count($get_vars); $i++)
{
    $key = $get_vars[$i];
    $val = $HTTP_GET_VARS[$get_vars[$i]];
    switch ($get_vars[$i])
    {
        case POST_USERS_URL: $key = 'b'; break;
        case 'mode':
            $newpm = ($val == 'newpm');
            if (!$newpm) $key = 'privmsg_mode';
            break;
        case 'folder': $key = 'sub'; break;
        default: $key = $get_vars[$i];
    }
    if (isset($HTTP_GET_VARS[$get_vars[$i]])) $s_call .= '&' . $key . '=' . $HTTP_GET_VARS[$get_vars[$i]];
}
if ( !$newpm )
{
    redirect(append_sid("profile.$phpEx?mode=privmsg" . $s_call, true));
}
else
{
    redirect(append_sid("profile.$phpEx?mode=privmsg_popup", true));
}
exit;
//-- fin mod : profile cp --------------------------------------------------------------------------

?>