<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_messages.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_messages.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

include_once($phpbb_root_path . './includes/bbcode.' . $phpEx);
include_once($phpbb_root_path . './includes/functions_post.' . $phpEx);

include_once($phpbb_root_path . './includes/functions_sys.' . $phpEx);
include_once($phpbb_root_path . './includes/functions_privmsga.' . $phpEx);
include_once($phpbb_root_path . './includes/emailer.' . $phpEx);

if (defined('IN_CASHMOD'))
{
	include($phpbb_root_path . 'includes/functions_cash.'.$phpEx);
}

//-------------------------------
//
//  send a mail
//
//-------------------------------
function send_mail($type, $from_userdata, &$to_user_ids, &$recips, $subject, $message, $time=0, $copy=true, $parsed_values=array())
{
    global $db, $board_config, $lang, $phpbb_root_path, $phpEx, $userdata;

    // fix some parameters
    $subject = trim($subject);
    $message = trim($message);

    // check we have a message and a subject
    if ( empty($subject) )
    {
        return 'Empty_subject';
    }
    if ( empty($message) )
    {
        return 'Empty_message';
    }

    // recipient is not an array, so make one
    if ( !is_array($to_user_ids) && !empty($to_user_ids) )
    {
        $to_user_ids = array(intval($to_user_ids));
    }

    // check if recipients
    if ( empty($to_user_ids) )
    {
        return 'No_to_user';
    }
    $s_to_user_ids = implode(', ', $to_user_ids);

    // censor words
    $orig_word = array();
    $replacement_word = array();
    obtain_word_list($orig_word, $replacement_word);

    // process some cleaning
    $subject = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, unprepare_message($subject)) : unprepare_message($subject);
    $message = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, unprepare_message($message)) : unprepare_message($message);

    // clean any bbcode_uid
    $subject = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $subject);
    $message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);

    // clean HTML
    $subject = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $subject);
    $message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);

    // from_user_id can be 0 for sys message (sent by the board)
    if ( empty($from_userdata) )
    {
        $from_userdata['user_id'] = 0;
        $from_userdata['user_level'] = ADMIN;
        $from_userdata['username'] = $board_config['sitename'];
    }
    $from_user_id = intval($from_userdata['user_id']);

    // get the recipients
    $sql_where = "user_email <> '' AND user_email IS NOT NULL";

    // this will require enhancement for the pcp ignore/friend list
    if ( !$copy )
    {
        $sql_where .= " AND user_id <> " . intval($from_userdata['user_id']);
    }
    if ( $userdata['user_level'] != ADMIN )
    {
        $sql_where .= " AND (user_viewemail = 1 OR user_id = " . intval($userdata['user_id']) . ")";
    }

    //
    // Make sure user wánts the mail
    //
    $notify_sql = '';
    $sql_notify = '';
    if ( $type == 'privmsg_notify' )
    {
        $sql_notify = ', user_notify_pm';
        $notify_sql = 'AND user_notify_pm != 0';
    }

    // read the mail recipients
    $sql = "SELECT user_id, user_email, user_lang, username" . $sql_notify . "
                FROM " . USERS_TABLE . "
                WHERE user_id IN ($s_to_user_ids)
                $notify_sql
                AND user_id NOT IN (0, " . ANONYMOUS . ")
                AND $sql_where";

    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read recipient mail list', '', __LINE__, __FILE__, $sql);
    }
    $count = 0;
    $bcc_list_ary = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $count++;
        $bcc_list_ary[$row['user_lang']][] = array('user_id' => $row['user_id'], 'mail' => $row['user_email'], 'username' => $row['username']);
    }

    if ( $count > 0 )
    {
        // read the message recipients
        $msg_to = '';
        if ( !empty($recips) )
        {
            for ( $i = 0; $i < count($recips); $i++ )
            {
                $username = isset($recips[$i]['privmsg_to_username']) ? $recips[$i]['privmsg_to_username'] : $recips[$i]['username'];
                if ( !empty($username) )
                {
                    $msg_to .= ( empty($msg_to) ? '' : ', ' ) . $username;
                }
            }
        }

        //
        // Let's do some checking to make sure that mass mail functions
        // are working in win32 versions of php.
        //
        if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
        {
            $ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

            // We are running on windows, force delivery to use our smtp functions
            // since php's are broken by default
            $board_config['smtp_delivery'] = 1;
            $board_config['smtp_host'] = @$ini_val('SMTP');
        }

        // init the mailer
        $emailer = new emailer($board_config['smtp_delivery']);

        // init server vars
        $server_name = trim($board_config['server_name']);
        $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
        $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

        // sender script
        $script_path = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
        $script_path = !empty($script_name) ? $server_protocol . $server_name . $server_port . $script_name . '/' : $server_protocol . $server_name . $server_port;

        // start the emailer data
        $emailer->from($board_config['board_email']);
        $emailer->replyto($board_config['board_email']);

        // choose template
        switch ($type)
        {
            case 'privmsg_notify':
                $tpl = 'privmsg_notify';
                $mail_subject = _lang('Notification_subject');
                break;
            case 'save_to_mail':
                $tpl = 'admin_send_email';
                $mail_subject = _lang('Save_to_mail_subject') . $subject;
                break;
            default:
                $tpl = 'admin_send_email';
                $mail_subject = $subject;
                break;
        }

        // send message (coming partially from privmsgs.php) : one per lang
        @reset($bcc_list_ary);
        while (list($user_lang, $bcc_list) = each($bcc_list_ary))
        {
            if ( $count == 1)
            {
                $emailer->email_address($bcc_list[0]['mail']);
            }
            else
            {
                // affect users mail
                for ( $i = 0; $i < count($bcc_list); $i++ )
                {
                    $emailer->bcc($bcc_list[$i]['mail']);
                }
            }

            // remove {USERNAME} from the template if more than one recipient
            if ( $count > 0 )
            {
                $emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);
            }

            // build message
            $msg = _lang('Subject') . ': ' . $subject;
            $msg .= "\n" . _lang('From') . ': ' . $from_userdata['username'];
            if ( !empty($msg_to) )
            {
                $msg .= "\n" . _lang('To') . ': ' . $msg_to;
            }
            if ( !empty($time) )
            {
                $dformat = $board_config['default_dateformat'];
                $dtz = $board_config['board_timezone'];
                if ( count($to_user_ids) == 1 )
                {
                    $dformat = $userdata['user_dateformat'];
                    $dtz = $userdata['user_timezone'];
                }
                $post_date = create_date($dformat, $time, $dtz);
                $msg .= "\n" . _lang('Date') . ': ' . $post_date;
            }
            $msg .= "\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n" . $message;

            // generic values
            $parsed_values['SITENAME'] = $board_config['sitename'];
            $parsed_values['EMAIL_SIG'] = !empty($board_config['board_email_sig']) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '';
            $parsed_values['MESSAGE'] = $msg;
            $parsed_values['FROM'] = $userdata['username'];

            $emailer->use_template($tpl, $user_lang);
            $emailer->set_subject($mail_subject);
            $emailer->assign_vars($parsed_values);

            // send
            $emailer->send();
            $emailer->reset();
			}
    }
}

//-------------------------------
//
//  send a pm
//
//-------------------------------
function send_pm($privmsg_id, $from_userdata, &$to_user_ids, $subject, $message, $icon, $html_on='?', $bbcode_on='?', $smiley_on='?', $attach_sig='?')
{
    global $userdata, $user_ip;
    global $lang, $board_config, $db, $phpbb_root_path, $phpEx, $bbcode_parse;
    global $folders;
    global $s_unread;

    // get some constants
    $time = time();
    $sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';
    $q = "'";

    // lists of impacted users
    $recips = array();

    // fix some parameters
    $privmsg_id     = intval(trim($privmsg_id));
    $subject        = trim($subject);
    $message        = trim($message);
    $icon           = intval($icon);
    $privmsg_ip     = $user_ip;

    // recipient is not an array, so make one
    if ( !is_array($to_user_ids) && !empty($to_user_ids) )
    {
        $to_user_ids = array(intval($to_user_ids));
    }

    // check if recipients
    if ( empty($to_user_ids) )
    {
        return 'No_to_user';
    }
    $s_to_user_ids = implode(', ', $to_user_ids);

    // deleted recip
    $s_new_delete = '';
    $s_unread_delete = '';
    $s_new_add = '';
    $s_unread_add = '';
    $s_read_add = '';

    // check we have a message and a subject
    if ( empty($subject) )
    {
        return 'Empty_subject';
    }
    if ( empty($message) )
    {
        return 'Empty_message';
    }

    // from_user_id can be 0 for sys message (sent by the board)
    if ( empty($from_userdata) )
    {
        $from_userdata['user_id']           = 0;
        $from_userdata['username']          = $board_config['sitename'];
        $from_userdata['user_allowhtml']    = $board_config['allow_html'];
        $from_userdata['user_allowbbcode']  = $board_config['allow_bbcode'];
        $from_userdata['user_allowsmile']   = $board_config['allow_smilies'];
        $from_userdata['user_attachsig']    = $board_config['allow_sig'];
    }
    $from_user_id = intval($from_userdata['user_id']);

    // init message row
    $bbcode_uid     = '';
    $html_on        = !$board_config['allow_html'] ? false : ($html_on == '?') ? intval($from_userdata['user_allowhtml']) : intval($html_on);
    $bbcode_on      = !$board_config['allow_bbcode'] ? false : ($bbcode_on == '?') ? intval($from_userdata['user_allowbbcode']) : intval($bbcode_on);
    $smiley_on      = !$board_config['allow_smilies'] ? false : ($smiley_on == '?') ? intval($from_userdata['user_allowsmile']) : intval($smiley_on);
    $attach_sig     = !$board_config['allow_sig'] ? false : ($attach_sig == '?') ? intval($from_userdata['user_attachsig']) : intval($attach_sig);

    $create = true;
    if ( !empty($privmsg_id) )
    {
        $create = false;
    }
    //------------------------------
    // edit a message : read the pm and take care of recipients that are no more recipients
    //------------------------------
    if ( !$create )
    {
        //-------------------------------
        // read the pm and check if ok to edit by the user (it has to belong to him)
        //-------------------------------
        $sql = "SELECT p.*, pr.*
                    FROM " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pr
                    WHERE p.privmsg_id = $privmsg_id
                        AND pr.privmsg_id = p.privmsg_id
                        AND pr.privmsg_user_id = $from_user_id
                        AND pr.privmsg_direct = 0
                        AND pr.privmsg_status = " . STS_TRANSIT;
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read message to duplicate', '', __LINE__, __FILE__, $sql);
        }
        if ( !$privmsg = $db->sql_fetchrow($result) )
        {
            return 'No_such_post';
        }

        // get some values from the original message
        $privmsg_ip = $privmsg['privmsg_ip'];

        //-------------------------------
        // manage recipients that are no more
        //-------------------------------
        // get users that are no more recipients and haven't read their pms
        $sql = "SELECT privmsg_user_id
                    FROM " . PRIVMSGA_RECIPS_TABLE . "
                    WHERE privmsg_user_id NOT IN ($s_to_user_ids)
                        AND privmsg_direct = 1
                        AND privmsg_id = $privmsg_id
                        AND privmsg_status = " . STS_TRANSIT . "
                        AND privmsg_read IN ($s_unread)";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read users no more recipients having not yet readen the message', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            if ( $row['privmsg_read'] == NEW_MAIL )
            {
                $s_new_delete .= ( empty($s_new_delete) ? '' : ', ' ) . $row['privmsg_user_ids'];
            }
            else
            {
                $s_unread_delete .= ( empty($s_unread_delete) ? '' : ', ' ) . $row['privmsg_user_ids'];
            }
        }

        // delete recipients for users who have deleted the message or not yet read and are no more recipients
        $sql = "DELETE $sql_priority
                    FROM " . PRIVMSGA_RECIPS_TABLE . "
                    WHERE privmsg_user_id NOT IN ($s_to_user_ids)
                        AND privmsg_direct = 1
                        AND privmsg_id = $privmsg_id
                        AND ( privmsg_read IN ($s_unread) OR privmsg_status = " . STS_DELETED . " )";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not delete users no more recipients having deleted or not yet readen the message', '', __LINE__, __FILE__, $sql);
        }

        // verify recipients that are no more but have read the pm
        $sql = "SELECT *
                    FROM " . PRIVMSGA_RECIPS_TABLE . "
                    WHERE privmsg_user_id NOT IN ($s_to_user_ids)
                        AND privmsg_direct = 1
                        AND privmsg_id = $privmsg_id
                        AND privmsg_read = " . READ_MAIL . "
                        AND privmsg_status <> " . STS_DELETED . "
                    LIMIT 0, 1";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not check if copy required', '', __LINE__, __FILE__, $sql);
        }

        // if some, duplicate the message and attach them to it
        if ( $db->sql_numrows($result) > 0 )
        {
            // message
            $fields = array();
            $fields['privmsg_subject']          = $q . str_replace("\'", "''", str_replace('\"', '"', addslashes(stripslashes($privmsg['privmsg_subject'])))) . $q;
            $fields['privmsg_text']             = $q . str_replace("\'", "''", str_replace('\"', '"', addslashes(stripslashes($privmsg['privmsg_text'])))) . $q;
            $fields['privmsg_bbcode_uid']       = $q . $privmsg['privmsg_bbcode_uid'] . $q;
            $fields['privmsg_time']             = intval($privmsg['privmsg_time']);
            $fields['privmsg_enable_bbcode']    = intval($privmsg['privmsg_enable_bbcode']);
            $fields['privmsg_enable_html']      = intval($privmsg['privmsg_enable_html']);
            $fields['privmsg_enable_smilies']   = intval($privmsg['privmsg_enable_smilies']);
            $fields['privmsg_attach_sig']       = intval($privmsg['privmsg_attach_sig']);
            $fields['privmsg_icon']             = intval($privmsg['privmsg_icon']);

            // generate a copy of the pm for recipients that are no more but have readen the pm, and mark it as deleted for the author
            _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
            $sql = "INSERT $sql_priority
                        INTO " . PRIVMSGA_TABLE . "
                        ($sql_fields)
                        VALUES($sql_values)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not generate a copy of original pm', '', __LINE__, __FILE__, $sql);
            }

            // get the copy id
            $privmsg_copy_id = $db->sql_nextid();

            // author
            $fields_recip = array();
            $fields_recip['privmsg_id']         = $privmsg_copy_id;
            $fields_recip['privmsg_direct']     = 0;
            $fields_recip['privmsg_user_id']    = intval($privmsg['privmsg_user_id']);
            $fields_recip['privmsg_ip']         = $q . $privmsg['privmsg_ip'] .$q;
            $fields_recip['privmsg_folder_id']  = intval($privmsg['privmsg_folder_id']);
            $fields_recip['privmsg_status']     = STS_DELETED;
            $fields_recip['privmsg_read']       = READ_PM;
            $fields_recip['privmsg_distrib']    = 1;

            // generate the author info
            _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update);
            $sql = "INSERT $sql_priority
                        INTO " . PRIVMSGA_RECIPS_TABLE . "
                        ($sql_fields)
                        VALUES($sql_values)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not generate a copy of original pm author', '', __LINE__, __FILE__, $sql);
            }

            // attach to the copy recipients that are no more but have readed the pm
            $sql = "UPDATE $sql_priority " . PRIVMSGA_RECIPS_TABLE . "
                        SET privmsg_id = $privmsg_copy_id, privmsg_distrib = 1
                        WHERE privmsg_user_id NOT IN ($s_to_user_ids)
                            AND privmsg_direct = 1
                            AND privmsg_id = $privmsg_id
                            AND privmsg_read = " . READ_MAIL;
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not attach recips to the copied pm', '', __LINE__, __FILE__, $sql);
            }
        }

        //-------------------------------
        // get the existing recips list
        //-------------------------------
        $sql = "SELECT pr.privmsg_user_id, pr.privmsg_read
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr
                    WHERE pr.privmsg_id = $privmsg_id
                        AND pr.privmsg_direct = 1";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read recipients', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $recips[ $row['privmsg_user_id'] ] = $row['privmsg_read'];
        }
    }

    //----------------------------
    // create or update the message
    //----------------------------
    // get a bbcode uid
    $bbcode_uid = $bbcode_on ? $bbcode_parse->make_bbcode_uid() : '';

    // prepare the message and add bbcode uid to the bbcodes
    $message = prepare_message($message, $html_on, $bbcode_on, $smiley_on, $bbcode_uid);

    // message
    $fields = array();
    $fields['privmsg_subject']          = $q . str_replace("\'", "''", str_replace('\"', '"', addslashes(stripslashes($subject)))) . $q;
    $fields['privmsg_text']             = $q . str_replace("\'", "''", str_replace('\"', '"', addslashes(stripslashes($message)))) . $q;
    $fields['privmsg_bbcode_uid']       = $q . $bbcode_uid . $q;
    $fields['privmsg_time']             = $time;
    $fields['privmsg_enable_bbcode']    = $bbcode_on;
    $fields['privmsg_enable_html']      = $html_on;
    $fields['privmsg_enable_smilies']   = $smiley_on;
    $fields['privmsg_attach_sig']       = $attach_sig;
    $fields['privmsg_icon']             = $icon;

    // process
    if ( $create )
    {
        // message
        _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
        $sql = "INSERT $sql_priority
                    INTO " . PRIVMSGA_TABLE . "
                    ($sql_fields)
                    VALUES($sql_values)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not create pm', '', __LINE__, __FILE__, $sql);
        }

        // store the new privmsg_id
        $privmsg_id = $db->sql_nextid();

        // author
        $fields_recip = array();
        $fields_recip['privmsg_id'] = $privmsg_id;
        $fields_recip['privmsg_ip'] = $q . $privmsg_ip .$q;
        $fields_recip['privmsg_status'] = STS_TRANSIT;
        $fields_recip['privmsg_read'] = NEW_MAIL;
        $fields_recip['privmsg_distrib'] = 0;
        $fields_recip['privmsg_folder_id'] = OUTBOX;
        $fields_recip['privmsg_direct'] = 0;
        $fields_recip['privmsg_user_id'] = $from_user_id;
        _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update);
        $sql = "INSERT $sql_priority
                    INTO " . PRIVMSGA_RECIPS_TABLE . "
                    ($sql_fields)
                    VALUES($sql_values)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not create pm author', '', __LINE__, __FILE__, $sql);
        }

        // recipients
        $fields_recip['privmsg_direct'] = 1;
        $fields_recip['privmsg_folder_id'] = INBOX;
        _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update, 'privmsg_user_id');
        for ( $i = 0; $i < count($to_user_ids); $i++ )
        {
            $privmsg_to_user_id = intval($to_user_ids[$i]);
            if ( !empty($privmsg_to_user_id) )
            {
                $sql = "INSERT $sql_priority
                            INTO " . PRIVMSGA_RECIPS_TABLE . "
                            ($sql_fields, privmsg_user_id)
                            VALUES($sql_values, $privmsg_to_user_id)";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not create pm recipient', '', __LINE__, __FILE__, $sql);
                }
                $s_new_add .= ( empty($s_new_add) ? '' : ', ') . $privmsg_to_user_id;
            }
        }
    }
    else
    {
        // message
        _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
        $sql = "UPDATE $sql_priority " . PRIVMSGA_TABLE . "
                    SET $sql_update
                    WHERE privmsg_id = $privmsg_id";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update pm', '', __LINE__, __FILE__, $sql);
        }

        // author
        $fields_recip = array();
        $fields_recip['privmsg_id'] = $privmsg_id;
        $fields_recip['privmsg_ip'] = $q . $privmsg_ip .$q;
        $fields_recip['privmsg_status'] = STS_TRANSIT;
        $fields_recip['privmsg_read'] = NEW_MAIL;
        $fields_recip['privmsg_distrib'] = 0;
        $fields_recip['privmsg_folder_id'] = OUTBOX;
        $fields_recip['privmsg_direct'] = 0;
        $fields_recip['privmsg_user_id'] = $from_user_id;
        _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update);
        $sql = "UPDATE $sql_priority " . PRIVMSGA_RECIPS_TABLE . "
                    SET $sql_update
                    WHERE privmsg_id = $privmsg_id
                        AND privmsg_direct = 0";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update pm', '', __LINE__, __FILE__, $sql);
        }

        // recipients
        $fields_recip['privmsg_direct'] = 1;
        $fields_recip['privmsg_folder_id'] = INBOX;
        _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update, 'privmsg_user_id');
        for ( $i = 0; $i < count($to_user_ids); $i++ )
        {
            $privmsg_to_user_id = intval($to_user_ids[$i]);
            if ( !empty($privmsg_to_user_id) )
            {
                if ( !isset($recips[$privmsg_to_user_id]) )
                {
                    // create a new recip
                    $sql = "INSERT $sql_priority
                                INTO " . PRIVMSGA_RECIPS_TABLE . "
                                ($sql_fields, privmsg_user_id)
                                VALUES($sql_values, $privmsg_to_user_id)";
                    if ( !$db->sql_query($sql) )
                    {
                        message_die(GENERAL_ERROR, 'Could not create pm recipient', '', __LINE__, __FILE__, $sql);
                    }
                    $s_new_add .= ( empty($s_new_add) ? '' : ', ') . $privmsg_to_user_id;
                }
                else
                {
                    // update an existing recip
                    $sql = "UPDATE $sql_priority " . PRIVMSGA_RECIPS_TABLE . "
                                SET $sql_update
                                WHERE privmsg_id = $privmsg_id
                                    AND privmsg_user_id = $privmsg_to_user_id
                                    AND privmsg_direct = 1";
                    if ( !$db->sql_query($sql) )
                    {
                        message_die(GENERAL_ERROR, 'Could not update pm recipient', '', __LINE__, __FILE__, $sql);
                    }
                    switch ($recips[$privmsg_to_user_id])
                    {
                        case READ_MAIL:
                            $s_read_add .= ( empty($s_read_add) ? '' : ', ' ) . $privmsg_to_user_id;
                            break;
                        case UNREAD_MAIL:
                            $s_unread_add .= ( empty($s_unread_add) ? '' : ', ' ) . $privmsg_to_user_id;
                            break;
                        case NEW_MAIL:
                            $s_new_add .= ( empty($s_new_add) ? '' : ', ' ) . $privmsg_to_user_id;
                            break;
                    }

                }
            }
        }
    }

    //----------------------------
    // adjust the impacted users box
    //----------------------------
    if ( !empty($s_new_delete) )
    {
        $sql = "UPDATE " . USERS_TABLE . "
                    SET user_new_privmsg = user_new_privmsg-1
                    WHERE user_id IN ($s_new_delete)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update users counter - deleted new private messages', '', __LINE__, __FILE__, $sql);
        }
    }
    if ( !empty($s_unread_delete) || !empty($s_unread_add) )
    {
        $semicol = ( empty($s_unread_delete) || empty($s_unread_add) ) ? '' : ',';
        $sql = "UPDATE " . USERS_TABLE . "
                    SET user_unread_privmsg = user_unread_privmsg-1
                    WHERE user_id IN ($s_unread_delete $semicol $s_unread_add)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update users counter - deleted unread private messages', '', __LINE__, __FILE__, $sql);
        }
    }
    if ( !empty($s_read_add) || !empty($s_new_add) )
    {
        $semicol = ( empty($s_read_add) || empty($s_new_add) ) ? '' : ',';
        $sql = "UPDATE " . USERS_TABLE . "
                    SET user_new_privmsg = user_new_privmsg+1,
                        user_last_privmsg = $time
                    WHERE user_id IN ($s_read_add $semicol $s_new_add)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update users counter - deleted new private messages', '', __LINE__, __FILE__, $sql);
        }
    }

    // notifications
    $date = $privmsg['privmsg_time'];
    $copy = false;

    // server values
    $server_name = trim($board_config['server_name']);
    $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
    $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

    // sender script
    $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
    $script_name = !empty($script_name) ? $script_name . '/privmsga.' . $phpEx : 'privmsga.' . $phpEx;

    // specific data
    $parsed_values = array(
        'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=' . INBOX,
    );
    $recips = array();
    send_mail('privmsg_notify', $from_userdata, $to_user_ids, $recips, $subject, $message, $time, $copy, $parsed_values);

    if (defined('IN_CASHMOD'))
	{
    	$pmer = new cash_user($userdata['user_id'],$userdata);
    	$pmer->give_pm_amount();
	}

    return '';
}

?>