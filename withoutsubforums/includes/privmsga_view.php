<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_view.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsga_view.php
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
$mod_post_icon = function_exists('get_icon_title');

//--------------------------
//
//  get parameters
//
//--------------------------
_hidden_init();

// vars
$folder_id          = _read_var('folder', 1, INBOX);
$to_folder          = _read_var('to_folder', 1);
$privmsg_recip_id   = _read_var(POST_POST_URL, 1);

// buttons
$move               = _button_var('move');
$refresh            = _button_var('refresh');

//---------------------------------
//
//  adjust the $pmmode suiting the button
//
//---------------------------------
if ( $move )
{
    $pmmode = 'move_pm';
}
if ( !in_array($pmmode, array('move_pm', 'savemail')) || empty($privmsg_recip_id) )
{
    $pmmode = 'view';
}

//---------------------------------
//
//  change the pm read status on first entrance
//
//---------------------------------
if ( !$refresh )
{
    // is this message unread for the actor
    $user_id = $userdata['user_id'];

    // read the privmsg_id
    $sql = "SELECT privmsg_id
                FROM " . PRIVMSGA_RECIPS_TABLE . "
                WHERE privmsg_recip_id = $privmsg_recip_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not get privmsg_id', '', __LINE__, __FILE__, $sql);
    }
    if ( !$row = $db->sql_fetchrow($result) )
    {
        message_die(GENERAL_ERROR, _lang('No_post_id'));
    }
    $privmsg_id = intval($row['privmsg_id']);

    // flag the message as readen for the actor
    $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                SET privmsg_read = " . READ_MAIL . ", privmsg_distrib = 0
            WHERE privmsg_status = " . STS_TRANSIT . "
                AND privmsg_user_id = $user_id AND privmsg_direct = 1
                AND privmsg_read IN ($s_unread)
                AND privmsg_id = $privmsg_id";
    if ( !$db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not update unread status for this message for you', '', __LINE__, __FILE__, $sql);
    }

    // something done
    if ( $db->sql_affectedrows() > 0 )
    {
        // get all read status for these messages
        $sql = "SELECT privmsg_direct, privmsg_read, count(privmsg_recip_id) AS count_recips
                    FROM " . PRIVMSGA_RECIPS_TABLE . "
                    WHERE privmsg_id = $privmsg_id
                        AND privmsg_status = " . STS_TRANSIT . "
                GROUP BY privmsg_direct, privmsg_read";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read remaining status for this message', '', __LINE__, __FILE__, $sql);
        }
        $reads = array();
        $original = 0;
        while ( $row = $db->sql_fetchrow($result) )
        {
            if ( $row['privmsg_direct'] == 0 )
            {
                $original = $row['privmsg_read'];
            }
            else
            {
                $reads[ $row['privmsg_read'] ] = $row['count_recips'];
            }
        }

        // synchronize the messages sender : get the cumulated read status
        $read_status = READ_MAIL;
        if ( intval($reads[NEW_MAIL]) > 0 )
        {
            $read_status = NEW_MAIL;
        }
        else if ( intval($reads[UNREAD_MAIL]) > 0 )
        {
            $read_status = UNREAD_MAIL;
        }

        // update
        if ( $read_status != $original )
        {
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

    // distribute pm
    distribute_pm($view_user_id);
}

// mark as not first entrance
_hide('refresh', true);

//---------------------------------
//
//  start the init process
//
//---------------------------------
// get privmsg_id and folder_id, check if it belongs to user
$sql = "SELECT privmsg_id, privmsg_folder_id, privmsg_status
            FROM " . PRIVMSGA_RECIPS_TABLE . "
            WHERE privmsg_recip_id = $privmsg_recip_id
                AND privmsg_user_id = $view_user_id
                AND privmsg_status <> " . STS_DELETED;
if ( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, 'Can\'t obtain message id', '', __LINE__, __FILE__, $sql);
}
if ( !$row = $db->sql_fetchrow($result) )
{
    message_die(GENERAL_ERROR, _lang('No_post_id'));
}
$privmsg_id = intval($row['privmsg_id']);
$folder_id = intval($row['privmsg_folder_id']);

// folders
if ( !isset($folders['data'][$folder_id]) )
{
    message_die(GENERAL_MESSAGE, _lang('No_such_folder'));
}
$folder_main = $folder_id;
if ( !empty($folders['main'][$folder_id]) )
{
    $folder_main = $folders['main'][$folder_id];
}

// get the message itself and the sender
$sql = "SELECT p.*, pr.*, u.*, u.username AS privmsg_from_username
            FROM " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pr, " . USERS_TABLE . " u
            WHERE p.privmsg_id = $privmsg_id
                AND pr.privmsg_id = p.privmsg_id
                AND pr.privmsg_direct = 0
                AND ( (pr.privmsg_user_id <> 0 AND u.user_id = pr.privmsg_user_id) OR (pr.privmsg_user_id = 0 AND u.user_id = " . ANONYMOUS . ") )";
if ( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, 'Can\'t read message data', '', __LINE__, __FILE__, $sql);
}
if ( !$privmsg = $db->sql_fetchrow($result) )
{
    message_die(GENERAL_ERROR, _lang('No_post_id'));
}

// get and fix some values
$privmsg_from_status    = intval($privmsg['privmsg_status']);
$privmsg_from_user_id   = intval($privmsg['privmsg_user_id']);
if ( empty($privmsg_from_user_id) )
{
    $privmsg['privmsg_user_id'] = 0;
    $privmsg['privmsg_from_username'] = $board_config['sitename'];
}

// check the sended
$sql_sender = ( $privmsg_from_user_id == $view_user_id ) ? '' : ' AND pr.privmsg_user_id = ' . $view_user_id;

// read the recipients
$sql = "SELECT pr.*, u.username AS privmsg_to_username
            FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . USERS_TABLE . " u
            WHERE pr.privmsg_id = $privmsg_id AND pr.privmsg_direct = 1
                AND u.user_id = pr.privmsg_user_id
                $sql_sender
            ORDER BY u.username, pr.privmsg_recip_id";
if ( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, 'Can\'t read message recipients', '', __LINE__, __FILE__, $sql);
}

$recips = array();
while ( $row = $db->sql_fetchrow($result) )
{
    $recips[] = $row;
}
if ( empty($recips) )
{
    message_die(GENERAL_ERROR, _lang('No_post_id'));
}

//---------------------------------
//
//  move
//
//---------------------------------
if ( $pmmode == 'move_pm' )
{
    // move pm to save folder
    move_pm($privmsg_recip_id, $view_user_id, $folder_id, $to_folder);

    // return to box display
    if ( !defined('IN_PCP') )
    {
        $return_path = append_sid("$main_pgm&folder=$to_folder");
        redirect($return_path);
        exit;
    }
    else
    {
        $pmmode = 'view';
        $folder_id = $to_folder;
        $folder_main = $folder_id;
        if ( !empty($folders['main'][$folder_id]) )
        {
            $folder_main = $folders['main'][$folder_id];
        }
    }
}

//---------------------------------
//
//  save to mail
//
//---------------------------------
if ( $pmmode == 'savemail' )
{
    // prepare the message
    if ( $cfg_save_to_mail )
    {
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

        // send the message
        $copy = true;
        $parsed_values = array();
        send_mail('save_to_mail', $privmsg, $userdata['user_id'], $recips, $post_subject, $private_message, $privmsg['privmsg_time'], $copy);

        // return message
        $return_msg = 'Message_saved_to_mail';
        $l_link = 'Click_return_message';
        $u_link = append_sid("$main_pgm&pmmode=view&" . POST_POST_URL . "=$privmsg_recip_id&" . POST_USERS_URL . "=$view_user_id");
        _message_return($return_msg, $l_link, $u_link);
    }
}

//-----------------------------
//
//  main entry
//
//-----------------------------
if ( $pmmode == 'view' )
{
    // display the page
    $page_title = _lang('Read_pm');
    if ( !defined('IN_PCP') )
    {
        include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
    }

    $template->set_filenames(array(
        'body' => 'privmsga_view_body.tpl')
    );
    privmsg_header($view_user_id, $folder_id, $privmsg_recip_id);

    // header
    $template->assign_vars(array(
        'BOX_NAME'      => _lang($folders['data'][$folder_id]['folder_name']),

        'L_MESSAGE'     => _lang('Message'),
        'L_SUBJECT'     => _lang('Subject'),
        'L_POSTED'      => _lang('Posted'),
        'L_FROM'        => _lang('From'),
        'L_TO'          => _lang('To'),
        'L_MOVE_MSG'    => _lang('Move_marked'),
        'L_CANCEL'      => _lang('Cancel'),
        )
    );

    // basic info
    $poster_id = $privmsg['privmsg_user_id'];
    $from_board = empty($privmsg['privmsg_user_id']);

    // from
    $u_user_from = $from_board ? append_sid("./index.$phpEx") : append_sid("./profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . '=' . $privmsg['privmsg_user_id']);
    $l_user_from = $from_board ? $board_config['sitename'] : $privmsg['privmsg_from_username'];
    $s_user_from = '<a href="' . $u_user_from . '" class="gen">' . $l_user_from . '</a>';

    // to
    for ( $i = 0; $i < count($recips); $i++ )
    {
        switch ( $recips[$i]['privmsg_read'] )
        {
            case NEW_MAIL:
                $img = 'icon_newest_reply';
                break;
            case UNREAD_MAIL:
                $img = 'icon_minipost_new';
                break;
            case READ_MAIL:
                $img = 'icon_minipost';
                break;
            default:
                $img = 'icon_minipost';
                break;
        }

        $u_user_to = append_sid("./profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . '=' . $recips[$i]['privmsg_user_id']);
        $l_user_to = $recips[$i]['privmsg_to_username'];
        $s_user_to .= ( empty($s_user_to) ? '' : ', ') . '<img src="' . _images($img) . '" border="0" align="middle" hspace="2" /><a href="' . $u_user_to . '" class="gen">' . $l_user_to . '</a>';
    }

    // message
    $post_date = create_date($userdata['user_dateformat'], $privmsg['privmsg_time'], $userdata['user_timezone']);
    $post_subject = $privmsg['privmsg_subject'];
    $private_message = $privmsg['privmsg_text'];
    $bbcode_uid = $privmsg['privmsg_bbcode_uid'];

    $user_sig = ( $privmsg['privmsg_attach_sig'] && !empty($privmsg['user_sig']) && $board_config['allow_sig'] ) ? $privmsg['user_sig'] : '';
    $user_sig_bbcode_uid = $privmsg['user_sig_bbcode_uid'];

    // HTML
    if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'] || !$privmsg['privmsg_enable_html'])
    {
        $private_message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $private_message);
        if ( !empty($user_sig) )
        {
            $user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
        }
    }

    // BBCode
    if ( $board_config['allow_bbcode'] && $userdata['user_allowbbcode'] && $privmsg['privmsg_enable_bbcode'])
    {
        if ( !empty($bbcode_uid) )
        {
            $private_message = $bbcode_parse->bbencode_second_pass($private_message, $bbcode_uid);
        }
        if ( !empty($user_sig) && !empty($user_sig_bbcode_uid) )
        {
            $user_sig = $bbcode_parse->bbencode_second_pass($user_sig, $user_sig_bbcode_uid);
        }
    }
    else
    {
        $private_message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $private_message);
        if ( !empty($user_sig) )
        {
            $user_sig = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
        }
    }

    // hyper links
    $private_message = $bbcode_parse->make_clickable($private_message);
    if ( !empty($user_sig) )
    {
        $user_sig = $bbcode_parse->make_clickable($user_sig);
    }

    // Parse smilies
    if ( $board_config['allow_smilies'] && $userdata['user_allowsmile'] && $privmsg['privmsg_enable_smilies'])
    {
        $private_message = $bbcode_parse->smilies_pass($private_message);
        if ( !empty($user_sig) )
        {
            $user_sig = $bbcode_parse->smilies_pass($user_sig);
        }
    }

    // Replace naughty words
    if (count($orig_word))
    {
        $post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
        $private_message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $private_message . '<'), 1, -1));
        if ( !empty($user_sig) )
        {
            $user_sig = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
        }
    }

    // <br /> replace \n
    $private_message = str_replace("\n", "\n<br />\n", $private_message);

    $private_message = $bbcode_parse->acronym_pass( $private_message );
    $private_message = $bbcode_parse->smart_pass( $private_message );

    if ( !empty($user_sig) )
    {
        $user_sig = '<br />_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
    }

    // commands
    $post_urls = array(
        'quote'         => append_sid("$main_pgm&pmmode=quote&amp;folder=$folder_id&amp;" . POST_POST_URL . "=$privmsg_recip_id"),
        'edit'          => append_sid("$main_pgm&pmmode=edit&amp;folder=$folder_id&amp;" . POST_POST_URL . "=$privmsg_recip_id"),
        'delete'        => append_sid("$main_pgm&pmmode=delete&amp;folder=$folder_id&amp;" . POST_POST_URL . "=$privmsg_recip_id"),
        'forward'       => append_sid("$main_pgm&pmmode=forward&amp;folder=$folder_id&amp;" . POST_POST_URL . "=$privmsg_recip_id"),
        'savemail'      => append_sid("$main_pgm&pmmode=savemail&amp;folder=$folder_id&amp;" . POST_POST_URL . "=$privmsg_recip_id"),
    );
    $post_icons = array(
        'quote_img'     => '<a href="' . $post_urls['quote'] . '"><img src="' . _images('pm_quotemsg') . '" alt="' . _lang('Post_quote_pm') . '" border="0" /></a>',
        'quote'         => '<a href="' . $post_urls['quote'] . '">' . _lang('Post_quote_pm') . '</a>',
        'edit_img'      => '<a href="' . $post_urls['edit'] . '"><img src="' . _images('pm_editmsg') . '" alt="' . _lang('Edit_pm') . '" border="0" /></a>',
        'edit'          => '<a href="' . $post_urls['edit'] . '">' . _lang('Edit_pm') . '</a>',
        'delete_img'    => '<a href="' . $post_urls['delete'] . '"><img src="' . _images('icon_delpost') . '" alt="' . _lang('Delete_message') . '" border="0" /></a>',
        'delete'        => '<a href="' . $post_urls['delete'] . '">' . _lang('Delete_message') . '</a>',
        'forward_img'   => '<a href="' . $post_urls['forward'] . '"><img src="' . _images('icon_forward') . '" alt="' . _lang('Forward_message') . '" border="0" /></a>',
        'forward'       => '<a href="' . $post_urls['forward'] . '">' . _lang('Forward_message') . '</a>',
        'savemail_img'  => '<a href="' . $post_urls['savemail'] . '"><img src="' . _images('icon_save_to_mail') . '" alt="' . _lang('Save_to_mail_message') . '" border="0" /></a>',
        'savemail'      => '<a href="' . $post_urls['savemail'] . '">' . _lang('Save_to_mail_message') . '</a>',
    );

    $editpm_img = '';
    $editpm     = '';
    if ( $folder_main == OUTBOX )
    {
        $editpm_img = $post_icons['edit_img'];
        $editpm     = $post_icons['edit'];
    }
    $quote_img      = $post_icons['quote_img'];
    $quote          = $post_icons['quote'];
    $delpm_img      = $post_icons['delete_img'];
    $delpm          = $post_icons['delete'];
    $forward_img    = $post_icons['forward_img'];
    $forward        = $post_icons['forward'];
    $savemail_img   = $post_icons['savemail_img'];
    $savemail       = $post_icons['savemail'];

    if ( empty($cfg_save_to_mail) )
    {
        $savemail_img = '';
        $savemail = '';
    }

    // get save sub-folder list
    $s_move_folder = '';
    if ( $folder_main != SAVEBOX )
    {
        $s_move_folder = get_folders_list($folder_id);
    }
    $s_move_folder .= get_folders_list(SAVEBOX);

    // standard process
    if ( !defined('IN_PCP') )
    {
        // user icons
        $profile_img    = '';
        $profile        = '';
        $pm_img         = '';
        $pm             = '';
        $email_img      = '';
        $email          = '';
        $www_img        = '';
        $www            = '';
        $icq_status_img = '';
        $icq_img        = '';
        $icq            = '';
        $aim_img        = '';
        $aim            = '';
        $msn_img        = '';
        $msn            = '';
        $yim_img        = '';
        $yim            = '';
        $search_img     = '';
        $search         = '';
        if ( !$from_board && ($poster_id != ANONYMOUS) )
        {
            $temp_url       = append_sid("./profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
            $profile_img    = '<a href="' . $temp_url . '"><img src="' . _images('icon_profile') . '" alt="' . _lang('Read_profile') . '" title="' . _lang('Read_profile') . '" border="0" /></a>';
            $profile        = '<a href="' . $temp_url . '">' . _lang('Read_profile') . '</a>';

            $temp_url       = append_sid("$main_pgm&pmmode=post&amp;" . POST_USERS_URL . "=$poster_id");
            $pm_img         = '<a href="' . $temp_url . '"><img src="' . _images('icon_pm') . '" alt="' . _lang('Send_private_message') . '" title="' . _lang('Send_private_message') . '" border="0" /></a>';
            $pm             = '<a href="' . $temp_url . '">' . _lang('Send_private_message') . '</a>';

            if ( !empty($privmsg['user_viewemail']) || $userdata['user_level'] == ADMIN )
            {
                $temp_url   = ( $board_config['board_email_form'] ) ? append_sid("./profile.$phpEx?mode=email&amp;" . POST_USERS_URL . "=$poster_id") : 'mailto:' . $privmsg['user_email'];
                $email_img  = '<a href="' . $temp_url . '"><img src="' . _images('icon_email') . '" alt="' . _lang('Send_email') . '" title="' . _lang('Send_email') . '" border="0" /></a>';
                $email      = '<a href="' . $temp_url . '">' . _lang('Send_email') . '</a>';
            }

            $temp_url       = $privmsg['user_website'];
            $www_img        = ( $privmsg['user_website'] ) ? '<a href="' . $temp_url . '" target="_userwww"><img src="' . _images('icon_www') . '" alt="' . _lang('Visit_website') . '" title="' . _lang('Visit_website') . '" border="0" /></a>' : '';
            $www            = ( $privmsg['user_website'] ) ? '<a href="' . $temp_url . '" target="_userwww">' . _lang('Visit_website') . '</a>' : '';

            $temp_url       = 'http://wwp.icq.com/scripts/search.dll?to=' . $privmsg['user_icq'] . '';
            $icq_img        = ( $privmsg['user_icq'] ) ? '<a href="' . $temp_url . '"><img src="' . _images('icon_icq') . '" alt="' . _lang('ICQ') . '" title="' . _lang('ICQ') . '" border="0" /></a>' : '';
            $icq_status_img = ( $privmsg['user_icq'] ) ? '<a href="http://wwp.icq.com/' . $privmsg['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $privmsg['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>' : '';
            $icq            = ( $privmsg['user_icq'] ) ? '<a href="' . $temp_url . '">' . _lang('ICQ') . '</a>' : '';

            $temp_url       = 'aim:goim?screenname=' . $privmsg['user_aim'] . '&amp;message=Hello+Are+you+there?';
            $aim_img        = ( $privmsg['user_aim'] ) ? '<a href="' . $temp_url . '"><img src="' . _images('icon_aim') . '" alt="' . _lang('AIM') . '" title="' . _lang('AIM') . '" border="0" /></a>' : '';
            $aim            = ( $privmsg['user_aim'] ) ? '<a href="' . $temp_url . '">' . _lang('AIM') . '</a>' : '';

            $temp_url       = append_sid("./profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
            $msn_img        = ( $privmsg['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="' . _images('icon_msnm') . '" alt="' . _lang('MSNM') . '" title="' . _lang('MSNM') . '" border="0" /></a>' : '';
            $msn            = ( $privmsg['user_msnm'] ) ? '<a href="' . $temp_url . '">' . _lang('MSNM') . '</a>' : '';

            $temp_url       = 'http://edit.yahoo.com/config/send_webmesg?.target=' . $privmsg['user_yim'] . '&amp;.src=pg';
            $yim_img        = ( $privmsg['user_yim'] ) ? '<a href="' . $temp_url . '"><img src="' . _images('icon_yim') . '" alt="' . _lang('YIM') . '" title="' . _lang('YIM') . '" border="0" /></a>' : '';
            $yim            = ( $privmsg['user_yim'] ) ? '<a href="' . $temp_url . '">' . _lang('YIM') . '</a>' : '';

            // search
            $temp_url       = append_sid("./search.$phpEx?search_author=" . urlencode($privmsg['username']) . "&amp;showresults=posts");
            $search_img     = '<a href="' . $temp_url . '"><img src="' . _images('icon_search') . '" alt="' . _lang('Search_user_posts') . '" title="' . _lang('Search_user_posts') . '" border="0" /></a>';
            $search         = '<a href="' . $temp_url . '">' . _lang('Search_user_posts') . '</a>';
        }

        //
        // Dump it to the templating engine
        //
        $template->assign_vars(array(
            'PROFILE_IMG'       => $profile_img,
            'PROFILE'           => $profile,
            'SEARCH_IMG'        => $search_img,
            'SEARCH'            => $search,
            'EMAIL_IMG'         => $email_img,
            'EMAIL'             => $email,
            'WWW_IMG'           => $www_img,
            'WWW'               => $www,
            'ICQ_STATUS_IMG'    => $icq_status_img,
            'ICQ_IMG'           => $icq_img,
            'ICQ'               => $icq,
            'AIM_IMG'           => $aim_img,
            'AIM'               => $aim,
            'MSN_IMG'           => $msn_img,
            'MSN'               => $msn,
            'YIM_IMG'           => $yim_img,
            'YIM'               => $yim,
            )
        );
    }

    // PCP process
    if ( defined('IN_PCP') )
    {
        // get user relational status
        $sql = "SELECT *
                    FROM " . BUDDYS_TABLE . "
                    WHERE user_id = " . intval($privmsg['user_id']) . "
                        AND buddy_id = " . intval($userdata['user_id']);
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read from user buddy info', '', __LINE__, __FILE__, $sql);
        }
        $buddys = $db->sql_fetchrow($result);

        // reverse list
        $sql = "SELECT *
                    FROM " . BUDDYS_TABLE . "
                    WHERE user_id = " . intval($userdata['user_id']) . "
                        AND buddy_id = " . intval($privmsg['user_id']);
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read my buddies info', '', __LINE__, __FILE__, $sql);
        }
        $my_buddys = $db->sql_fetchrow($result);

        // message enhancement
        $privmsg['user_friend']     = isset($buddys['buddy_ignore']) ? !$buddys['buddy_ignore'] : false;
        $privmsg['user_visible']    = $buddys['buddy_visible'];
        $privmsg['user_ignore']     = $buddys['buddy_ignore'];
        $privmsg['user_my_friend']  = isset($my_buddys['buddy_ignore']) ? !$my_buddys['buddy_ignore'] : false;
        $privmsg['user_my_visible'] = $my_buddys['buddy_visible'];
        $privmsg['user_my_ignore']  = $my_buddys['buddy_ignore'];
        $privmsg['user_online']     = ( $privmsg['user_session_time'] >= (time()-300) );
        $privmsg['user_pm']         = 1;

        // sig
        if (!$userdata['user_viewsig'] || !$privmsg['user_allow_sig'])
        {
            $privmsg['user_sig'] = '';
        }

        // get the from panels
        $privmsg['privmsgs_id'] = $privmsg_recip_id;

        $from_panel         = pcp_output_panel('PHPBB.privmsgs.left', $privmsg);
        $buttons_panel      = pcp_output_panel('PHPBB.privmsgs.buttons', $privmsg);
        $from_ignore_panel  = pcp_output_panel('PHPBB.privmsgs.left.ignore', $privmsg);
        $ignore_buttons     = pcp_output_panel('PHPBB.privmsgs.buttons.ignore', $privmsg);

        //
        // Dump it to the templating engine
        //
        $template->assign_vars(array(
            'AUTHOR_PANEL'  => !$privmsg['user_my_ignore'] ? $from_panel : $from_ignore_panel,
            'DEST_PANEL'    => !$privmsg['user_my_ignore'] ? $to_panel : $to_ignore_panel,
            'BUTTONS_PANEL' => !$privmsg['user_my_ignore'] ? $buttons_panel : $ignore_buttons,
            )
        );
    }

    // post icons mod installed
    $post_icon = '';
    if ( $mod_post_icon )
    {
        $topic_type = POST_NORMAL;
        $post_icon = get_icon_title($privmsg['privmsg_icon'], 1, $topic_type);
        $post_subject = $post_icon . '&nbsp;' . $post_subject;
    }

    //
    // Dump it to the templating engine
    //
    $template->assign_vars(array(
        'MESSAGE_FROM'      => $s_user_from,
        'MESSAGE_TO'        => $s_user_to,
        'POST_SUBJECT'      => $post_subject,
        'POST_DATE'         => $post_date,
        'MESSAGE'           => $private_message,
        'SIGNATURE'         => $signature,

        'QUOTE_PM_IMG'      => $quote_img,
        'QUOTE_PM'          => $quote,
        'EDIT_PM_IMG'       => $editpm_img,
        'EDIT_PM'           => $editpm,
        'DELETE_PM_IMG'     => $delpm_img,
        'DELETE_PM'         => $delpm,
        'FORWARD_PM_IMG'    => $forward_img,
        'FORWARD_PM'        => $forward,
        'SAVEMAIL_PM_IMG'   => $savemail_img,
        'SAVEMAIL_PM'       => $savemail,

        'S_MOVE_FOLDER'     => $s_move_folder,
        )
    );

    // system
    _hide(POST_POST_URL, $privmsg_recip_id);
    _hide('folder', $folder_id);
    _hide(POST_USERS_URL, $view_user_id);
    _hide('pmmode', $pmmode);
    _hide('start', $pm_start);

    $template->assign_vars(array(
        'S_ACTION'          => append_sid($main_pgm),
        'S_HIDDEN_FIELDS'   => _hidden_get(),
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