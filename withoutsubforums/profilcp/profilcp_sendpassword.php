<?php
//-- mod : profilcp --------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_sendpassword.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilecp_sendpassword.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

//-- mod : profilcp --------------------------------------------------------------------------------
//-- add
if ( !empty($setmodules) ) return;
//-- fin mod : profilcp ----------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
    exit;
}

if ( isset($HTTP_POST_VARS['submit']) )
{
    // session id check
    if ( ($sid == '' || $sid != $userdata['session_id']) && !defined('NO_SID') )
    {
        message_die(GENERAL_ERROR, 'Invalid_session');
    }

    $username = ( !empty($HTTP_POST_VARS['username']) ) ? trim(strip_tags($HTTP_POST_VARS['username'])) : '';
    $email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(strip_tags(htmlspecialchars($HTTP_POST_VARS['email']))) : '';

    $sql = "SELECT user_id, username, user_email, user_active, user_lang, user_type
        FROM " . USERS_TABLE . "
        WHERE user_email = '" . str_replace("\'", "''", $email) . "'
            AND username = '" . str_replace("\'", "''", $username) . "'";
    if ( $result = $db->sql_query($sql) )
    {
        if ( $row = $db->sql_fetchrow($result) )
        {
            if ( !$row['user_active'] )
            {
                message_die(GENERAL_MESSAGE, $lang['No_send_account_inactive']);
            }

            if ($row['user_type'] == User_Type_LDAP)
            {
                message_die(GENERAL_ERROR, $lang['No_send_account_LDAP']);
            }

            $username = $row['username'];
            $user_id = $row['user_id'];

            $user_actkey = pcp_gen_rand_string(true);
            $key_len = 54 - strlen($server_url);
            $key_len = ( $str_len > 6 ) ? $key_len : 6;
            $user_actkey = substr($user_actkey, 0, $key_len);
            $user_password = pcp_gen_rand_string(false);

            $sql = "UPDATE " . USERS_TABLE . "
                SET user_newpasswd = '" . md5($user_password) . "', user_actkey = '$user_actkey'
                WHERE user_id = " . $row['user_id'];
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update new password information', '', __LINE__, __FILE__, $sql);
            }

//-- mod : profilcp --------------------------------------------------------------------------------
//-- delete
//          include($phpbb_root_path . 'includes/emailer.'.$phpEx);
//-- fin mod : profilcp ----------------------------------------------------------------------------
            $emailer = new emailer($board_config['smtp_delivery']);

            $email_headers = 'From: ' . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";

            $emailer->use_template('user_activate_passwd', $row['user_lang']);
            $emailer->email_address($row['user_email']);
            $emailer->set_subject($lang['New_password_activation']);
            $emailer->extra_headers($email_headers);

            $emailer->assign_vars(array(
                'SITENAME' => $board_config['sitename'],
                'USERNAME' => $username,
                'PASSWORD' => $user_password,
                'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',

                'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
            );
            $emailer->send();
            $emailer->reset();

            $template->assign_vars(array(
                'META' => '<meta http-equiv="refresh" content="15;url=' . append_sid("index.$phpEx") . '">')
            );

            $message = $lang['Password_updated'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

            message_die(GENERAL_MESSAGE, $message);
        }
        else
        {
            message_die(GENERAL_MESSAGE, $lang['No_email_match']);
        }
    }
    else
    {
        message_die(GENERAL_ERROR, 'Could not obtain user information for sendpassword', '', __LINE__, __FILE__, $sql);
    }
}
else
{
    $username = '';
    $email = '';
}

//
// Output basic page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
    'body' => 'profile_send_pass.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
    'USERNAME' => $username,
    'EMAIL' => $email,

    'L_SEND_PASSWORD' => $lang['Send_password'],
    'L_ITEMS_REQUIRED' => $lang['Items_required'],
    'L_EMAIL_ADDRESS' => $lang['Email_address'],
    'L_SUBMIT' => $lang['Submit'],
    'L_RESET' => $lang['Reset'],

    'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />',
    'S_PROFILE_ACTION' => append_sid("profile.$phpEx?mode=sendpassword"))
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>