<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_list.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsga_list.php
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
//--------------------------
//
//  get parameters
//
//--------------------------
_hidden_init();

// vars
$folder_id      = _read_var('folder', 1, INBOX);
$to_folder      = _read_var('to_folder', 1);
$msg_days       = _read_var('msgdays', 1);
$mark_ids       = _read_var('mark_ids', 21, array());

// buttons
$delete         = _button_var('delete');
$move           = _button_var('move');
$savemails      = _button_var('savemails');
$confirm        = _button_var('confirm');
$cancel         = _button_var('cancel');
$refresh        = _button_var('refresh');

// compatibility
if ( empty($folder_id) )
{
    $folder_id = INBOX;
}

// folder
if ( !isset($folders['data'][$folder_id]) )
{
    message_die(GENERAL_MESSAGE, _lang('No_such_folder'));
}
$folder_main = $folder_id;
if ( !empty($folders['main'][$folder_id]) )
{
    $folder_main = $folders['main'][$folder_id];
}

// destination sub-folder
if ( empty($to_folder) )
{
    $to_folder = $folder_main;
}
$to_folder_main = $to_folder;
if ( !empty($folders['main'][$to_folder]) )
{
    $to_folder_main = $folders['main'][$to_folder];
}

// mode
if ( !in_array( $pmmode, array('delete_pms', 'move_pms', 'savemails') ) )
{
    $pmmode = '';
}

// select days
if ( !in_array($msg_days, $previous_days) )
{
    $msg_days = 0;
}

//---------------------------------
//
//  distribute the pm to the good folders
//
//---------------------------------
if ( !$refresh )
{
    distribute_pm($view_user_id);
}

//---------------------------------
//
//  build the folder basic sql request
//
//---------------------------------
// filter the user
$sql_user = "pr.privmsg_user_id = $view_user_id";

// filter the recips to list
switch ($folder_main)
{
    case INBOX:
        // inbox : we select the recipient record (privmsg_direct=1)
        $sql_recip = "pr.privmsg_direct = 1 AND pr.privmsg_status = " . STS_TRANSIT;
        break;

    case OUTBOX:
        // outbox : we read the sender (privmsg_direct=0) and filter on new/unread
        // this will require to update the status read/unread/new on the sender recip while reading the item
        $sql_recip = "pr.privmsg_direct = 0 AND pr.privmsg_read IN ($s_unread) AND pr.privmsg_status = " . STS_TRANSIT;
        break;

    case SENTBOX:
        // sentbox : we read the sender (privmsg_direct=0) and filter on read
        // this will require to update the read status read/unread/new on the sender recip while reading the item
        $sql_recip = "pr.privmsg_direct = 0 AND pr.privmsg_read = " . READ_MAIL . " AND pr.privmsg_status = " . STS_TRANSIT;
        break;

    case SAVEBOX:
        // savebox : we don't really care the direction of the mail :
        // only one record with the user_id is marked as saved, the other are marked as deleted
        $sql_recip = "pr.privmsg_status = " . STS_SAVED;
        break;

    default:
        message_die(GENERAL_ERROR, _lang('No_such_folder'));
        exit;
}

// add the days filter
$sql_days = '';
if ( $msg_days > 0 )
{
    $time = time();
    $floor = mktime( 0, 0, 0, intval(date('m', $time)), intval(date('d', $time)) - $msg_days, intval(date('Y', $time)) );
    $sql_days = "p.privmsg_time >= $floor";
}

// add the message table if selection on days
$sql_from = '';
if ( !empty($sql_days) )
{
    $sql_from = ", " . PRIVMSGA_TABLE . " p ";
    $sql_days = "AND $sql_days AND p.privmsg_id=pr.privmsg_id";
}

// folder
$sql_folder = "pr.privmsg_folder_id = $folder_id";

// buid the request
$sql_where = "pr.privmsg_user_id = $view_user_id AND $sql_recip";

// count how many records (messages)
$sql = "SELECT pr.*
            FROM " . PRIVMSGA_RECIPS_TABLE . " pr $sql_from
            WHERE $sql_where
                AND $sql_folder $sql_days";
if ( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, 'Can\'t read pms in selected sub-folder', '', __LINE__, __FILE__, $sql);
}
$count_in_sub_folder = $db->sql_numrows($result);

//---------------------------------
//
//  check the validity of the selected id
//
//---------------------------------
$s_mark_ids = '';

// read all the pms in the selection
if ( !empty($mark_ids) )
{
    $s_mark_ids = implode(', ', $mark_ids);
    $sql = "SELECT pr.*
                FROM " . PRIVMSGA_RECIPS_TABLE . " pr $sql_from
                WHERE $sql_where
                    AND $sql_folder $sql_days
                    AND pr.privmsg_recip_id IN ($s_mark_ids)";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read pms selected', '', __LINE__, __FILE__, $sql);
    }
    $mark_ids = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $mark_ids[] = $row['privmsg_recip_id'];
    }
}

//---------------------------------
//
//  adjust the $pmmode suiting the button
//
//---------------------------------
if ( empty($mark_ids) )
{
    $move = false;
    $delete = false;
    if ( in_array($pmmode, array('move_pms', 'delete_pms', 'savemails')) )
    {
        $pmmode = '';
    }
}
if ( $move )
{
    $pmmode = 'move_pms';
    $move = false;
}
if ( $delete )
{
    $pmmode = 'delete_pms';
    $delete = false;
}
if ( $savemails )
{
    $pmmode = 'savemails';
}

//---------------------------------
//
//  save marked
//
//---------------------------------
if ( $pmmode == 'move_pms' )
{
    // move pm to save folder
    move_pm($mark_ids, $view_user_id, $folder_id, $to_folder);

    // return to box display
    if ( !defined('IN_PCP') )
    {
        $return_path = append_sid("$main_pgm&folder=$to_folder&sid=" . $userdata['session_id']);
        redirect($return_path);
        exit;
    }
    else
    {
        $folder_id = $to_folder;
        $folder_main = $folder_id;
        if ( !empty($folders['main'][$folder_id]) )
        {
            $folder_main = $folders['main'][$folder_id];
        }
        $pmmode = '';

        // rebuild the request
        switch ($folder_main)
        {
            case INBOX:
                $sql_recip = "pr.privmsg_direct = 1 AND pr.privmsg_status = " . STS_TRANSIT;
                break;
            case OUTBOX:
                $sql_recip = "pr.privmsg_direct = 0 AND pr.privmsg_read IN ($s_unread) AND pr.privmsg_status = " . STS_TRANSIT;
                break;
            case SENTBOX:
                $sql_recip = "pr.privmsg_direct = 0 AND pr.privmsg_read = " . READ_MAIL . " AND pr.privmsg_status = " . STS_TRANSIT;
                break;
            case SAVEBOX:
                $sql_recip = "pr.privmsg_status = " . STS_SAVED;
                break;
            default:
                message_die(GENERAL_ERROR, _lang('No_such_folder'));
        }
        $sql_folder = "pr.privmsg_folder_id = $folder_id";
        $sql_where = "pr.privmsg_user_id = $view_user_id AND $sql_recip";
        $mark_ids = array();
    }
}

//---------------------------------
//
//  delete marked
//
//---------------------------------
if ( $pmmode == 'delete_pms' )
{
    if ( $cancel )
    {
        $pmmode = '';
    }
    else if ( $confirm )
    {
        // delete marked privmsg
        delete_pm($mark_ids, $view_user_id);

        // send update message
        $l_link = 'Click_return_' . strtolower($folders['data'][$folder_main]['folder_name']);
        $u_link = append_sid("$main_pgm&folder=$folder_id");
        _message_return('Message_deleted', $l_link, $u_link);
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Private_Messaging');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }

        // template name
        $template->set_filenames(array(
            'body' => 'confirm_body.tpl')
        );

        $template->assign_vars(array(
            'MESSAGE_TITLE' => _lang('Information'),
            'MESSAGE_TEXT' => ( count($mark_ids) == 1 ) ? _lang('Confirm_delete_pm') : _lang('Confirm_delete_pms'),

            'L_YES' => _lang('Yes'),
            'L_NO' => _lang('Cancel'),
            )
        );

        // system
        for ( $i = 0; $i < count($mark_ids); $i++ )
        {
            _hide('mark_ids[]', $mark_ids[$i]);
        }
        _hide('start', $pm_start);
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);
        _hide(POST_USERS_URL, $view_user_id);
        _hide('sid', $userdata['session_id']);

        $template->assign_vars(array(
            'S_CONFIRM_ACTION'  => append_sid($main_pgm),
            'S_HIDDEN_FIELDS'   => _hidden_get(),
            )
        );

        // send to browser
        privmsg_footer();
        $template->pparse('body');
        include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
    }
}

//---------------------------------
//
//  save to mail marked
//
//---------------------------------
if ( $pmmode == 'savemails' )
{
    if ( $cfg_save_to_mail && !empty($mark_ids) )
    {
        $s_mark_ids = implode(', ', $mark_ids);
        // read the messages and process them
        $sql = "SELECT p.*, pa.*, pa.privmsg_user_id AS privmsg_from_user_id, u.*
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " u
                    WHERE p.privmsg_id = pr.privmsg_id
                        AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
                        AND pr.privmsg_recip_id IN ($s_mark_ids)
                        AND ( (pa.privmsg_user_id <> 0 AND u.user_id = pa.privmsg_user_id)
                            OR (pa.privmsg_user_id = 0 AND u.user_id = " . ANONYMOUS . ") )";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read selected pms', '', __LINE__, __FILE__, $sql);
        }
        while ( $privmsg = $db->sql_fetchrow($result) )
        {
            $privmsg_id = $privmsg['privmsg_id'];
            $post_subject = $privmsg['privmsg_subject'];
            $private_message = $privmsg['privmsg_text'];
            $bbcode_uid = $privmsg['privmsg_bbcode_uid'];

            // HTML
            $private_message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $private_message);

            // BBCode
            $private_message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $private_message);
            $private_message = preg_replace('/\[url=([^\]]*)\]/si', "\n\\1 : ", $private_message);
            $private_message = str_replace('[/url]', ' ', $private_message);
            $private_message = str_replace('[url]', "\n", $private_message);
            $private_message = str_replace('[/img]', ' ', $private_message);
            $private_message = str_replace('[img]', "\n", $private_message);

            // Replace naughty words
            if (count($orig_word))
            {
                $post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
                $private_message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $private_message . '<'), 1, -1));
            }

            // <br /> replace \n
            $private_message = str_replace('<br />', "\n", $private_message);

            // check the sended
            $sql_sender = ( $privmsg['privmsg_from_user_id'] == $view_user_id ) ? '' : ' AND pr.privmsg_user_id = ' . $view_user_id;

            // read
            $sql = "SELECT u.user_id AS privmsg_to_user_id, u.username AS privmsg_to_username
                        FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . USERS_TABLE . " u
                        WHERE pr.privmsg_id = $privmsg_id AND pr.privmsg_direct = 1
                            AND u.user_id = pr.privmsg_user_id
                            $sql_sender
                        ORDER BY u.username, pr.privmsg_recip_id";
            if ( !$result2 = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Can\'t read message recipients', '', __LINE__, __FILE__, $sql);
            }
            $recips = array();
            while ( $row = $db->sql_fetchrow($result2) )
            {
                $recips[] = $row;
            }
            $user_id = $userdata['user_id'];
            $copy = true;
            send_mail('save_to_mail', $privmsg, $user_id, $recips, $post_subject, $private_message, $privmsg['privmsg_time'], $copy);
        }

        // return message
        $return_msg = 'Message_saved_to_mail';
        $l_link = 'Click_return_message';
        $u_link = append_sid("$main_pgm&folder=$folder_id&" . POST_USERS_URL . "=$view_user_id");
        _message_return($return_msg, $l_link, $u_link);
    }

    // return to box display
    $pmmode = '';
    $mark_ids = array();
}

//---------------------------------
//
//  main list
//
//---------------------------------
if ( $pmmode == '' )
{
    // some inits
    $s_privmsg_ids  = '';
    $privmsg_rowset = array();
    $recips         = array();

    // read the pms of the page
    $sql_days_w = empty($sql_days) ? " AND p.privmsg_id = pr.privmsg_id" : $sql_days;
    $sql = "SELECT p.*,
                    pa.*,
                    pr.privmsg_recip_id AS selected_pm_id, pr.privmsg_status AS selected_status, pr.privmsg_read AS selected_read,
                    ua.username AS privmsg_from_username
                FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " ua
                WHERE $sql_where
                    AND $sql_folder $sql_days_w
                    AND pr.privmsg_status <> " . STS_DELETED . "
                    AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
                    AND ( (pa.privmsg_user_id = 0 AND ua.user_id = " . ANONYMOUS . ") OR (pa.privmsg_user_id <> 0 AND ua.user_id = pa.privmsg_user_id) )
                ORDER BY p.privmsg_time DESC, p.privmsg_id
                LIMIT $pm_start, " . intval($board_config['topics_per_page']);
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read pms in selected sub-folder', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $privmsg_rowset[] = $row;
        $s_privmsg_ids .= ( empty($s_privmsg_ids) ? '' : ', ' ) . $row['privmsg_id'];
    }

    // read the recipients
    if ( !empty($privmsg_rowset) )
    {
        $sql = "SELECT pr.privmsg_id, pr.privmsg_recip_id, pr.privmsg_user_id, ur.username AS privmsg_to_username
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " ur
                    WHERE pr.privmsg_id IN ($s_privmsg_ids) AND pr.privmsg_direct = 1
                        AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
                        AND ur.user_id = pr.privmsg_user_id
                        AND (pr.privmsg_user_id = $view_user_id OR pa.privmsg_user_id = $view_user_id)
                    ORDER BY pr.privmsg_id, ur.username, pr.privmsg_recip_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read recipients', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            if ( count($recips[ $row['privmsg_id'] ]) <= $cfg_max_userlist )
            {
                $recips['data'][ $row['privmsg_id'] ][] = $row;
            }
            else
            {
                $recips['over'][ $row['privmsg_id'] ] = true;
            }
        }
    }

    //---------------------------------
    //
    //  change the pm read status on first entrance
    //
    //---------------------------------
    if ( !$refresh )
    {
        // update the status of the new pms awaiting in inbox
        if ( ($folder_main == INBOX) && ($userdata['user_id'] == $view_user_id) )
        {
            // get all the new mails from the inbox folders
            $sql = "SELECT privmsg_id, privmsg_recip_id
                        FROM " . PRIVMSGA_RECIPS_TABLE . "
                        WHERE privmsg_status = " . STS_TRANSIT . "
                            AND privmsg_user_id = $view_user_id AND privmsg_direct = 1
                            AND privmsg_read = " . NEW_MAIL;
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update private message new/read status (2) for user', '', __LINE__, __FILE__, $sql);
            }
            $ws_privmsg_ids = '';
            while ( $row = $db->sql_fetchrow($result) )
            {
                $ws_privmsg_ids .= ( empty($ws_privmsg_ids) ? '' : ', ' ) . $row['privmsg_id'];
            }

            // flag the message as readen for this messages
            if ( !empty($ws_privmsg_ids) )
            {
                $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                            SET privmsg_read = " . UNREAD_MAIL . ", privmsg_distrib = 0
                            WHERE privmsg_status = " . STS_TRANSIT . "
                                AND privmsg_user_id = $view_user_id AND privmsg_direct = 1
                                AND privmsg_read = " . NEW_MAIL;
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not update private message new/read status (2) for user', '', __LINE__, __FILE__, $sql);
                }

                // get all read status for these messages
                $sql = "SELECT privmsg_id, privmsg_direct, privmsg_read, count(privmsg_recip_id) AS count_recips
                            FROM " . PRIVMSGA_RECIPS_TABLE . "
                            WHERE privmsg_id IN ($ws_privmsg_ids)
                                AND privmsg_status = " . STS_TRANSIT . "
                        GROUP BY privmsg_id, privmsg_direct, privmsg_read";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not read remaining status for these messages', '', __LINE__, __FILE__, $sql);
                }
                $reads = array();
                $originals = array();
                while ( $row = $db->sql_fetchrow($result) )
                {
                    if ( $row['privmsg_direct'] == 0 )
                    {
                        $originals[ $row['privmsg_id'] ] = $row['privmsg_read'];
                    }
                    else
                    {
                        $reads[ $row['privmsg_id'] ][ $row['privmsg_read'] ] = $row['count_recips'];
                    }
                }

                // synchronize the messages senders record
                @reset($reads);
                while ( list($privmsg_id, $counts) = @each($reads) )
                {
                    // get the cumulated read status
                    $read_status = READ_MAIL;
                    if ( intval($counts[NEW_MAIL]) > 0 )
                    {
                        $read_status = NEW_MAIL;
                    }
                    else if ( intval($counts[UNREAD_MAIL]) > 0 )
                    {
                        $read_status = UNREAD_MAIL;
                    }

                    // update
                    if ( $read_status != $originals[$privmsg_id] )
                    {
                        // update sender if read status changed
                        $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                                    SET privmsg_read = $read_status, privmsg_distrib = 0
                                    WHERE privmsg_id = $privmsg_id
                                        AND privmsg_direct = 0";
                        if ( !$db->sql_query($sql) )
                        {
                            message_die(GENERAL_ERROR, 'Could not update unread status for the sender of this message', '', __LINE__, __FILE__, $sql);
                        }
                    }
                }
            }
            resync_pm($view_user_id);
        }
    }

    // set the page title and include the page header
    $page_title = _lang('Private_Messaging');
    if ( !defined('IN_PCP') )
    {
        include($phpbb_root_path . 'includes/page_header.' . $phpEx);
    }

    // template name
    $template->set_filenames(array(
        'body' => 'privmsga_body.tpl')
    );

    // send header
    privmsg_header($view_user_id, $folder_id);

    // send list
    privmsg_list($privmsg_rowset, $recips, $folder_id, true, $mark_ids);

    // pagination
    $page_list      = generate_pagination("profile.$phpEx?mode=privmsg", $count_in_sub_folder, $board_config['topics_per_page'], $pm_start);
	$page_number    = sprintf(_lang('Page_of'), ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $count_in_sub_folder / $board_config['topics_per_page'] ));
    //$page_list      = str_replace('&amp;start', '&start', $page_list);
    $page_list      = preg_replace("/\&start\=(\d*)/", "javascript:document.post.start.value='\\1'; document.post.submit();", $page_list);

    // add marked on other pages
    for ( $i = 0; $i < count($mark_ids); $i++ )
    {
        if ( empty($marked_on_this_page) || !in_array($mark_ids[$i], $marked_on_this_page) )
        {
            _hide('mark_ids[]', $mark_ids[$i]);
        }
    }
    _hide('start', $pm_start);
    _hide('pmmode', $pmmode);
    _hide('folder', $folder_id);
    _hide(POST_USERS_URL, $view_user_id);

    // mark as not first entrance
    _hide('refresh', true);

    // system
    $template->assign_vars(array(
        'S_ACTION'          => append_sid($main_pgm),
        'S_HIDDEN_FIELDS'   => _hidden_get(),

        'L_GOTO_PAGE'       => ($count_in_sub_folder <  $board_config['topics_per_page']) ? '' : _lang('Goto_page'),
        'PAGINATION'        => ($count_in_sub_folder <  $board_config['topics_per_page']) ? '' : $page_list,
        'PAGE_NUMBER'       => $page_number,
        )
    );
}

// send to browser
privmsg_footer();
$template->pparse('body');
if ( !defined('IN_PCP') )
{
    include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}

?>