<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_register.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilecp_register.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
    exit;
}

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

//if( isset($HTTP_GET_VARS['mode'] ) || isset($HTTP_POST_VARS['mode']) )
//{
//$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];

if( $board_config['disable_reg'] && $userdata['user_level'] != ADMIN )
{
      $template->assign_vars(array(
        "META" => '<meta http-equiv="refresh" content="10;url='.append_sid("index.$phpEx").'">')
      );
      $message = ( $board_config['disable_reg_msg'] ) ? $board_config['disable_reg_msg'] : $lang['disable_reg_msg'];
      message_die(GENERAL_ERROR, $message);
}
//}


if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['mode'][] = 'register';
    $module['sort'][] = 30;
    $module['url'][] = $file;
    $module['shortcut'][] = $lang['profilcp_register_shortcut'];
    $module['page_title'][] = $lang['profilcp_register_pagetitle'];
    $module['sub'][] = array();
    return;
}

// check access
if ( ($userdata['user_id'] != $view_userdata['user_id']) && ($userdata['user_level'] != ADMIN) ) return;

//
// new user
$create_user = !$userdata['session_logged_in'];
if ( $create_user )
{
    $view_userdata['username'] = '';
    $view_userdata['user_email'] = '';
}

//
// template file
$template->set_filenames(array(
    'body' => 'profilcp/register_body.tpl')
);
if ($submit)
{
    $error = false;

    $forum_rules_present = isset($HTTP_POST_VARS['forum_rules_present']);
    $forum_rules_agreed = isset($HTTP_POST_VARS['agree_rules']);

    // get formular var
    $username = isset($HTTP_POST_VARS['username']) ? trim(htmlspecialchars($HTTP_POST_VARS['username'])) : $view_userdata['username'];

    $cur_password = isset($HTTP_POST_VARS['cur_password']) ? trim($HTTP_POST_VARS['cur_password']) : '';
    $new_password = trim($HTTP_POST_VARS['new_password']);
    $password_confirm = trim($HTTP_POST_VARS['password_confirm']);

    $robot_confirm = trim($HTTP_POST_VARS['robot_confirm']);

    $user_email = trim(htmlspecialchars($HTTP_POST_VARS['user_email']));
    $user_email_confirm = trim(htmlspecialchars($HTTP_POST_VARS['user_email_confirm']));

    // username change allow ?
    $username_changed = false;
    $username_error = false;
    if ( $create_user || $board_config['allow_namechange'] || is_admin($userdata) )
    {
        // check username
        if ( empty($username) )
        {
            $username_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['profilcp_username_missing'];
        }
        if ( !$username_error && ( $create_user || ( strtolower($username) != strtolower($view_userdata['username']) ) ) )
        {
            $result = validate_username($username);
            if ( $result['error'] )
            {
                $username_error = true;
                $error = true;
                $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
            }
            else $username_changed = true;
        }
    }

    // email
    $email_changed = false;
    $email_error = false;

    // check email
    if ( ($user_email != $user_email_confirm) || empty($user_email) )
    {
        $email_error = true;
        $error = true;
        $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['profilcp_email_not_matching'];
    }
    if ( !$email_error && ( $create_user || ($user_email != $view_userdata['user_email']) ) )
    {
        $result = validate_email($user_email);
        if ( $result['error'] )
        {
            $user_email = $view_userdata['user_email'];
            $email_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
        }
        else $email_changed = true;
    }

    // password
    $password_changed = false;
    $password_error = false;

    // create : new and confirm password should be set
    if ( $create_user && ( empty($new_password) || empty($password_confirm) ) )
    {
        $password_error = true;
        $error = true;
        $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Fields_empty'];
    }

    // edit email and/or username
    if ( !is_admin($userdata) && !$create_user && ( $username_changed || $email_changed || !empty($cur_password) ) )
    {
        // check the current password
        if ( empty($cur_password) || ( md5($cur_password) != $view_userdata['user_password'] ) )
        {
            $password_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
        }
    }

    // edit : check if a pass has been set
    if ( !empty($new_password) || !empty($password_confirm) )
    {
        // check the current password
        if ( !$create_user && ( empty($cur_password) || ( md5($cur_password) != $view_userdata['user_password'] ) ) && !is_admin($userdata) )
        {
            $password_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
        }

        // check the new password
        if ( !$password_error && ( empty($new_password) || empty($password_confirm) || ($new_password != $password_confirm) ) )
        {
            $password_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
        }
        if ( !$password_error && (strlen($new_password) > 32) )
        {
            $password_error = true;
            $error = true;
            $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_long'];
        }
        if (!$password_error)
        {
            $password_changed = true;
            $new_password = md5($new_password);
        }
    }

    // check anti-robotic flood register
    if ( $board_config['robotic_register'] && $create_user && (empty($userdata['session_robot']) || empty($robot_confirm) || ($userdata['session_robot'] != $robot_confirm)) )
    {
        $robot_error = true;
        $error = true;
        $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Robot_flood_control'];
    }

    // check the agreement to the forum rules
    if ($create_user && $forum_rules_present && !$forum_rules_agreed)
    {
        $error = true;
        $error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Disagree_rules'];
    }

    // break if error
    if ( $error ) message_die(GENERAL_ERROR, $error_msg);

    // disabling the user (or creation)
    $active_changed = false;
    if ( ($create_user || $email_changed) && ($board_config['require_activation'] != USER_ACTIVATION_NONE) )
    {
        $active_changed = true;
        $user_active = is_admin($userdata);

        // get an activation key
        if ( !$user_active )
        {
            $user_actkey = pcp_gen_rand_string(true);
            $key_len = 54 - ( strlen($server_url) );
            $key_len = ( $key_len > 6 ) ? $key_len : 6;
            $user_actkey = substr($user_actkey, 0, $key_len);
            $user_active = 0; // Bug Fix
        }
    }
    else
    {
        //$active_changed = ( !$create_user && !$view_userdata['user_active'] ); // Original
        $active_changed = ( $create_user && !$view_userdata['user_active'] );    // Bug Fix
		$user_active = 'user_active';
		$user_actkey = 'user_actkey';
    }

    // prepare values
    $values = array();
    if ( $username_changed ) $values['username'] = $username;
    if ( $email_changed )    $values['user_email'] = $user_email;
    if ( $password_changed ) $values['user_password'] = $new_password;
    if ( $active_changed )   $values['user_active'] = $user_active;

    // default values
    if ( $create_user )
    {
        // get a new user_id
        $sql = "SELECT MAX(user_id) AS total FROM " . USERS_TABLE;
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
        }
        if ( !$row = $db->sql_fetchrow($result) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
        }
        $userrow['user_id'] = $row['total'] + 1;
        $user_id = $userrow['user_id'];

        // force some specific values that would have been updated for anonymous user
        $userrow['user_regdate'] = time();
        $userrow['user_timezone'] = $board_config['board_timezone'];
        $userrow['user_style'] = $board_config['default_style'];
        $userrow['user_lang'] = $board_config['default_lang'];
        $userrow['user_dateformat'] = $board_config['default_dateformat'];

        // add new values
        @reset($values);
        while ( list($key, $value) = each($values) ) $userrow[$key] = $value;

        // prepare the sql request
        @reset($userrow);
        while ( list($key, $value) = each($userrow) )
        {
            $sql_key .= ( empty($sql_key) ? '' : ', ') . $key;
            $sql_val .= ( empty($sql_val) ? '' : ', ') . "'" . str_replace("\'", "''", $value) . "'";
        }
		if ( $active_changed )   $values['user_actkey'] = $user_actkey;
		$sql_key .= ( empty($sql_key) ? '' : ', ') . 'user_actkey';
		$sql_val .= ( empty($sql_val) ? '' : ', ') . "'$user_actkey'";
        // perform the sql requests
        $sql = "INSERT INTO " . USERS_TABLE . "($sql_key) VALUES ($sql_val)";
        if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
        {
            message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
        }

        $sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
                VALUES ('', 'Personal User', 1, 0)";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
        }

        $group_id = $db->sql_nextid();

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//
            $sql = 'INSERT INTO ' . IM_PREFS_TABLE . ' (user_id, themes_id)
                VALUES (' . $user_id . ', ' . $board_config['default_style'] . ')';
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not insert data into im_prefs table', '', __LINE__, __FILE__, $sql);
            }
//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------

        $sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
                VALUES ($user_id, $group_id, 0)";
        if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
        {
            message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
        }

        if ( $board_config['require_activation'] == USER_ACTIVATION_SELF )
        {
            $message = $lang['Account_inactive'];
            $email_template = 'user_welcome_inactive';
        }
        else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
        {
            $message = $lang['Account_inactive_admin'];
            $email_template = 'admin_welcome_inactive';
        }
        else
        {
            $message = $lang['Account_added'];
            $email_template = 'user_welcome';
        }

//Auto Group - START
        $sql = "SELECT ug.user_id, g.group_id as g_id, g.group_name , u.user_posts, g.group_count FROM " . GROUPS_TABLE . " g, ".USERS_TABLE." u
            LEFT JOIN ". USER_GROUP_TABLE." ug ON g.group_id=ug.group_id AND ug.user_id=$user_id
            WHERE u.user_id=$user_id
                AND ug.user_id is NULL
                AND g.group_count=0
                AND g.group_single_user=0
                AND g.group_moderator<>$user_id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Error geting users post stat', '', __LINE__, __FILE__, $sql);
            }
            while ($group_data = $db->sql_fetchrow($result))
            {
                //user join a autogroup
                $sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
                    VALUES (".$group_data['g_id'].", $user_id, 0)";
                if ( !($db->sql_query($sql)) )
                {
                    message_die(GENERAL_ERROR, 'Error inserting user group, group count', '', __LINE__, __FILE__, $sql);
                }
            }
//Auto Group - END


        $emailer = new emailer($board_config['smtp_delivery']);

        $email_headers = "From: " . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";

        $emailer->use_template($email_template, stripslashes($user_lang));
        $emailer->email_address($user_email);
        $emailer->set_subject(sprintf($lang['Welcome_subject'], $board_config['sitename']));
        $emailer->extra_headers($email_headers);

        $emailer->assign_vars(array(
            'SITENAME' => $board_config['sitename'],
            'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
            'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
            'PASSWORD' => $password_confirm,
            'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

            'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
        );

        $emailer->send();
        $emailer->reset();

        if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
        {
            $emailer->use_template("admin_activate", $board_config['default_lang']);
            $emailer->email_address($board_config['board_email']);
            $emailer->set_subject($lang['New_account_subject']);
            $emailer->extra_headers($email_headers);

            $emailer->assign_vars(array(
                'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
                'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

                'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
            );

            $emailer->send();
            $emailer->reset();
        }
    }

    // update
    if ( !$create_user )
    {
        $userrow = array();

        // add new values
        @reset($values);
        while ( list($key, $value) = each($values) ) $userrow[$key] = $value;

        // prepare the sql request
        @reset($userrow);
        $sql_req = '';
        while ( list($key, $value) = each($userrow) )
        {
            $sql_req .= (empty($sql_req) ? '' : ', ') . $key . " = '" . str_replace("\'", "''", $value) . "'";
        }

        // perform the sql requests
        if ( $sql_req != '' )
        {
            $user_id = $view_userdata['user_id'];
            $sql = "UPDATE " . USERS_TABLE . " SET $sql_req WHERE user_id = $user_id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update user table', '', __LINE__, __FILE__, $sql);
            }
            if ( $username_changed )
            {
                $sql = "UPDATE " . GROUPS_TABLE . "
                        SET group_name = '" . str_replace("\'", "''", $username) . "'
                        WHERE group_name = '" . str_replace("\'", "''", $view_userdata['username'] ) . "'";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not rename users group', '', __LINE__, __FILE__, $sql);
                }
            }

            // send mails if requested
            if ( $active_changed )
            {
                //
                // The users account has been deactivated, send them an email with a new activation key
                //
                $emailer = new emailer($board_config['smtp_delivery']);

                $email_headers = "From: " . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";

                $emailer->use_template('user_activate', stripslashes($view_userdata['user_lang']));
                $emailer->email_address($user_email);
                $emailer->set_subject($lang['Reactivate']);
                $emailer->extra_headers($email_headers);

                $emailer->assign_vars(array(
                    'SITENAME' => $board_config['sitename'],
                    'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
                    'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',

                    'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
                );
                $emailer->send();
                $emailer->reset();
            }
        }
    }
    //
    // send update message
    if (!$user_active)
    {
        if ( $userdata['session_logged_in'] && !is_admin($userdata))
        {
            session_end($userdata['session_id'], $userdata['user_id']);
        }
        if ($create_user)
        {
            if ( $board_config['require_activation'] == USER_ACTIVATION_SELF )
            {
                $message = $lang['Account_inactive'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
            }
            else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
            {
                $message = $lang['Account_inactive_admin'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
            }
        }
        else
        {
            $message = $lang['Profile_updated_inactive'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
        }
    }
    else
    {
        if ($create_user)
        {
            $message = $lang['Account_added'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
        }
        else
        {
            $message = $lang['Profile_updated'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
        }
    }

    if ( $create_user || ( !$user_active && !is_admin($userdata) ) )
    {
        $template->assign_vars(array(
            "META" => '<meta http-equiv="refresh" content="5;url=' . append_sid("index.$phpEx") . '">')
        );
        message_die(GENERAL_MESSAGE, $message);
    }
}
else
{
    // constants
    $template->assign_vars(array(
        'L_REGISTRATION'                => $lang['profilcp_register_pagetitle'],
        'L_USERNAME'                    => $lang['Username'],

        'L_EMAIL_TITLE'                 => $lang['profilcp_email_title'],
        'L_EMAIL'                       => $lang['Email'],
        'L_EMAIL_CONFIRM'               => $lang['profilcp_email_confirm'],

        'L_IMAGE'                       => $lang['anti_robotic'],
        'L_IMAGE_EXPLAIN'               => $lang['anti_robotic_explain'],

        'L_PASSWORD_TITLE'              => $lang['Password'],

        'L_SUBMIT'                      => $lang['Submit'],
        'L_RESET'                       => $lang['Reset'],

        'L_CURRENT_PASSWORD'            => $lang['Current_password'],
        'L_CONFIRM_PASSWORD_EXPLAIN'    => $lang['profilcp_password_explain'],
        'L_NEW_PASSWORD'                => $lang['Password'], /* : $lang['New_password'], */
        'L_CONFIRM_PASSWORD'            => $lang['Confirm_password'],
        'L_PASSWORD_IF_CHANGED'         => $lang['password_if_changed'],
        'L_PASSWORD_CONFIRM_IF_CHANGED' => $lang['password_confirm_if_changed'],
        )
    );

    // get the current password ?
    if ( !$create_user )
    {
        $template->assign_block_vars('switch_get_cur_password', array() );
    }

    // name edition allowed ?
    if ( $create_user || $board_config['allow_namechange'] || is_admin($userdata) )
    {
        $template->assign_block_vars('switch_edit_name', array() );
    }
    else
    {
        $template->assign_block_vars('switch_no_edit_name', array() );
    }

    // anti-robotic on registration
    if ($board_config['robotic_register'] && $create_user)
    {
        $template->assign_block_vars('switch_anti_robotic', array());

        // get the anti-robotic string
        $userdata['session_robot'] = pcp_gen_rand_string(false);
        $sql = "UPDATE " . SESSIONS_TABLE . " SET session_robot = '" . $userdata['session_robot'] . "' WHERE session_id = '" . $userdata['session_id'] . "'";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update session robot', '', __LINE__, __FILE__, $sql);
        }

        // get the images
        for ($i=0; $i < strlen($userdata['session_robot']); $i++)
        {
            $template->assign_block_vars('switch_anti_robotic.robotic_pic', array(
                'PIC' => append_sid("profile_pic.$phpEx?l=$i"),
                )
            );
        }
    }

    // value
    $template->assign_vars(array(
        'USERNAME'          => $view_userdata['username'],
        'EMAIL'             => $view_userdata['user_email'],
        'EMAIL_CONFIRM'     => $view_userdata['user_email'],
        )
    );

    // forum rules
    /*
    if ( intval($board_config['forum_rules']) != 0 )
    {
        $topic_id = intval($board_config['forum_rules']);

        // check if topic exist
        $sql = "SELECT u.*, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
                FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
                WHERE p.topic_id = $topic_id
                    AND p.post_id=t.topic_first_post_id
                    AND pt.post_id = p.post_id
                    AND u.user_id = p.poster_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read forum rules post', '', __LINE__, __FILE__, $sql);
        }
        if ($row=$db->sql_fetchrow($result))
        {
            // prepare it for display
            $post_subject = ( $row['post_subject'] != '' ) ? $row['post_subject'] : '';
            $message = $row['post_text'];
            $bbcode_uid = $row['bbcode_uid'];
            if (!$board_config['allow_html'] && $row['enable_html'] ) $message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
            if ( $board_config['allow_bbcode'] && $bbcode_uid != '' ) $message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
            if ( $board_config['allow_smilies'] && $row['enable_smilies'] ) $message = smilies_pass($message);
            $message = str_replace("\n", "\n<br />\n", $message);
            $last_update = $lang['Last_updated'] . ": " . create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

            // send
            $template->assign_vars(array(
                'L_FORUM_RULES'     => $post_subject,
                'FORUM_RULES'       => $message,
                'LAST_UPDATE'       => $last_update,
                'L_AGREE_RULES'     => $lang['Agree_rules'],
                )
            );
            $template->assign_block_vars('switch_forum_rules', array());
            if ($create_user)
            {
                $template->assign_block_vars('switch_forum_rules.confirm_rules', array());
                $s_hidden_fields .= '<input type="hidden" name="forum_rules_present" value="1">';
            }
        }
    }
    */

    // forum rules
   if ( intval($board_config['forum_rules']) != 0 )
   {
      $topic_id = intval($board_config['forum_rules']);

      // check if rules exist
      $sql = "SELECT * FROM " . RULES_TABLE . "";
      if( !($result = $db->sql_query($sql)) )
      {
         message_die(GENERAL_ERROR, 'Could not obtain the rules', '', __LINE__, __FILE__, $sql);
      }

      if ($row=$db->sql_fetchrow($result))
      {
         // prepare it for display
         $rules = $row["rules"];
         $rules_date = create_date($lang['DATE_FORMAT'], $row['date'], $board_config['board_timezone']);

         // send
         $template->assign_vars(array(
            'L_FORUM_RULES'     => $lang['Forum_Rules'],
            'RULES_DATA'        => $rules,
            'RULES_DATE'        => $rules_date,
            'L_AGREE_RULES'      => $lang['Agree_rules'],
            )
         );

         $template->assign_block_vars('switch_forum_rules', array());
         if ($create_user)
         {
            $template->assign_block_vars('switch_forum_rules.confirm_rules', array());
            $s_hidden_fields .= '<input type="hidden" name="forum_rules_present" value="1">';
         }
      }
   }

    $template->assign_vars(array(
        'S_HIDDEN_FIELDS'       => $s_hidden_fields,
        'S_PROFILCP_ACTION'     => append_sid("profile.$phpEx"),
        )
    );

    // page
    $template->pparse('body');
}

?>