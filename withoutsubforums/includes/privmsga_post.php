<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_post.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsgs_post.php
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
include_once($phpbb_root_path . 'includes/functions_bbcodes.' . $phpEx);

$mod_post_icon = function_exists('get_icon_title');

//-----------------------------
//
//  get parameters
//
//-----------------------------
_hidden_init();

// vars
$folder_id          = _read_var('folder', 1);
$privmsg_recip_id   = _read_var(POST_POST_URL, 1);
$group_id           = _read_var(POST_GROUPS_URL, 1);
$to_user_ids        = _read_var('tousers', 12);

// buttons
$submit             = _button_var('post');
$confirm            = _button_var('confirm');
$cancel             = _button_var('cancel');

$preview            = _button_var('preview');
$usersubmit         = _button_var('usersubmit');
$groupsubmit        = _button_var('groupsubmit');
$refresh            = _button_var('refresh');

$refresh = $refresh || $preview || $usersubmit || $groupsubmit;

// mode
if ( !in_array( $pmmode, array('post', 'reply', 'quote', 'forward', 'edit', 'delete') ) )
{
    $pmmode = 'post';
}
$review = in_array($pmmode, array('reply'));

// keep the folder only if edition
if ( !in_array($pmmode, array('edit', 'delete')) )
{
    $folder_id = OUTBOX;
}

// read the user's folders list
$folders = get_user_folders($view_user_id);
if ( !isset($folders['data'][$folder_id]) )
{
    message_die(GENERAL_MESSAGE, _lang('No_such_folder'));
}

// main folder
$folder_main = $folder_id;
if ( !empty($folders['main'][$folder_id]) )
{
    $folder_main = $folders['main'][$folder_id];
}

//-----------------------------
//
//  read the pm
//
//-----------------------------
// init privmsg data
$privmsg = array();
$privmsg['privmsg_enable_bbcode']   = $view_userdata['user_allowbbcode'];
$privmsg['privmsg_enable_html']     = $view_userdata['user_allowhtml'];
$privmsg['privmsg_enable_smilies']  = $view_userdata['user_allowsmile'];
$privmsg['privmsg_attach_sig']      = $view_userdata['user_attachsig'];

// read the pm
if ( !empty($privmsg_recip_id) )
{
    // get the privmsg_id and folder_id : it has to belong to the user
    $sql = "SELECT privmsg_id, privmsg_folder_id
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
    if ( in_array($pmmode, array('edit', 'delete')) )
    {
        $folder_id = intval($row['privmsg_folder_id']);
    }

    // read the message and sender
    $sql = "SELECT p.*,
                    pr.privmsg_status AS privmsg_from_status, pr.privmsg_user_id AS privmsg_from_user_id,
                    u.username AS privmsg_from_username
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
    $privmsg_status         = intval($privmsg['privmsg_from_status']);
    $privmsg_from_user_id   = intval($privmsg['privmsg_from_user_id']);
    if ( empty($privmsg_from_user_id) )
    {
        $privmsg['privmsg_from_user_id'] = 0;
        $privmsg['privmsg_from_username'] = $board_config['sitename'];
    }

    // read the recipients
    $sql = "SELECT pr.privmsg_id, pr.privmsg_recip_id, pr.privmsg_user_id,
                    u.user_id AS privmsg_to_user_id, u.username AS privmsg_to_username
                FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . USERS_TABLE . " u
                WHERE pr.privmsg_id = $privmsg_id AND pr.privmsg_direct = 1
                    AND u.user_id = pr.privmsg_user_id
                ORDER BY u.username, pr.privmsg_recip_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read message data', '', __LINE__, __FILE__, $sql);
    }
    $privmsg['privmsg_to_user_ids'] = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $privmsg['privmsg_to_user_ids'][] = $row['privmsg_to_user_id'];
    }
    if ( !empty($privmsg_recip_id) )
    {
        $privmsg['privmsg_recip_id'] = $privmsg_recip_id;
    }
}

if ( empty($folder_id) )
{
    $folder_id = OUTBOX;
}

//-----------------------------
//
//  delete
//
//-----------------------------
if ( $pmmode == 'delete' )
{
    if ( empty($privmsg_recip_id) )
    {
        message_die(GENERAL_ERROR, _lang('No_post_id'));
    }
    if ( $cancel )
    {
        $cancel = false;
        $pmmode = 'post';
        redirect(append_sid("$main_pgm&folder=$folder_id&" . POST_POST_URL . "=$privmsg_recip_id&" . POST_USERS_URL . "=$view_user_id"));
    }
    else if ( $confirm )
    {
        // perform some checks
        $error = false;
        $error_msg = '';

        if ( $error )
        {
            message_die(GENERAL_ERROR, $error_msg);
        }

        // perform the delete
        delete_pm($privmsg_recip_id, $view_user_id);
        redirect(append_sid("$main_pgm&folder=$folder_id&" . POST_USERS_URL . "=$view_user_id"));
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Private_Messaging');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }
        privmsg_header($view_user_id, $folder_id, $privmsg_recip_id);

        // template name
        $template->set_filenames(array(
            'body' => 'confirm_body.tpl')
        );

        $template->assign_vars(array(
            'MESSAGE_TITLE'     => _lang('Information'),
            'MESSAGE_TEXT'      => _lang('Confirm_delete_pm'),

            'L_YES'             => _lang('Yes'),
            'L_NO'              => _lang('No'),
            )
        );

        // system
        _hide(POST_POST_URL, $privmsg_recip_id);
        _hide(POST_USERS_URL, $view_user_id);
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);

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

//-----------------------------
//
//  get data from the table
//
//-----------------------------
$privmsg_to_user_ids = $privmsg['privmsg_to_user_ids'];

$post_icon  = intval($privmsg['privmsg_icon']);
$subject    = $privmsg['privmsg_subject'];
$message    = $privmsg['privmsg_text'];
$bbcode_on  = $privmsg['privmsg_enable_bbcode'] && $board_config['allow_bbcode'];
$html_on    = $privmsg['privmsg_enable_html'] && $board_config['allow_html'];
$smilies_on = $privmsg['privmsg_enable_smilies'] && $board_config['allow_smilies'];
$attach_sig = $privmsg['privmsg_attach_sig'] && $board_config['allow_sig'];

$user_sig = $privmsg['user_sig'];
$user_sig_bbcode_uid = $privmsg['user_sig_bbcode_uid'];

// clean message
$bbcode_uid = $privmsg['privmsg_bbcode_uid'];
$message = preg_replace("/\:(([a-z0-9]:)?)$bbcode_uid/si", '', $message);
$message = str_replace('<br />', "\n", $message);

// add special mentions
if ( $pmmode == 'reply' )
{
    $privmsg_to_user_ids = empty($privmsg['privmsg_from_user_id']) ? array() : array($privmsg['privmsg_from_user_id']);
    $subject = _lang('Short_reply') . $subject;
    $message = '';
}
if ( $pmmode == 'quote' )
{
    $privmsg_to_user_ids = ($privmsg['privmsg_from_userid'] == $view_user_id) ? array() : array($privmsg['privmsg_from_user_id']);
    $subject = _lang('Short_reply') . $subject;
    $message = '[quote="' . $privmsg['privmsg_from_username'] . '"]' . $message . '[/quote]';
}
if ( $pmmode == 'forward' )
{
    $privmsg_to_user_ids = array();
    $subject = _lang('Short_forward') . $subject;
    $message = '[quote="' . $privmsg['privmsg_from_username'] . '"]' . $message . '[/quote]';
}

//-----------------------------
//
//  get data from form
//
//-----------------------------
$s_to_users = '';

// users passed from the url
$to_users = array();
if ( !empty($to_user_ids) )
{
    $wto_user_ids = explode(',', $to_user_ids);
    $ato_user_ids = array();
    for ( $i = 0; $i < count($wto_user_ids); $i++ )
    {
        $w_user_id = intval($wto_user_ids[$i]);
        if ( !empty($w_user_id) )
        {
            if ( empty($ato_user_ids) || !in_array($w_user_id, $ato_user_ids) )
            {
                $ato_user_ids[] = $w_user_id;
            }
        }
    }
    if ( !empty($ato_user_ids) )
    {
        $s_ato_user_ids = implode(', ', $ato_user_ids);
        $sql = "SELECT username
                    FROM " . USERS_TABLE . "
                    WHERE user_id IN ($s_ato_user_ids)
                        AND user_id <> " . ANONYMOUS . "
                    ORDER BY username";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read users', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $to_users[] = $row['username'];
        }
    }
}

// users present in the form field
if ( isset($HTTP_POST_VARS['to_users']) || !empty($to_users) )
{
    if ( isset($HTTP_POST_VARS['to_users']) )
    {
        $to_users = explode(',', _read_var('to_users', 12) );
    }
    if ( (count($to_users) == 1) && empty($to_users[0]) )
    {
        $to_users = array();
    }
    for ( $i = 0; $i < count($to_users); $i++ )
    {
        if ( !empty($to_users[$i]) )
        {
            $s_to_users .= ( empty($s_to_users) ? '' : ', ') . "'" . trim(str_replace("'", "\'", $to_users[$i])) . "'";
        }
    }
}

// friend list has been asked
if ( defined('IN_PCP') && $groupsubmit && ( $group_id == FRIEND_LIST_GROUP ) )
{
    $sql = "SELECT u.username
                FROM " . BUDDYS_TABLE . " b, " . USERS_TABLE . " u
                WHERE b.user_id = $view_user_id
                    AND b.buddy_ignore = 0
                    AND u.user_id = b.buddy_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read friend list', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $s_to_users .=  ( empty($s_to_users) ? '' : ', ') . "'" . trim(str_replace("''", "\'", $row['username'])) . "'";
    }
    $groupsubmit = false;
}

// group has been asked
if ( $groupsubmit && ( $group_id > 0 ) )
{
    // is the user member of this group ?
    $member = ( $view_userdata['user_level'] == ADMIN );
    if ( !$member )
    {
        $sql = "SELECT *
                    FROM " . USER_GROUP_TABLE . "
                    WHERE user_id = $view_user_id
                        AND group_id = $group_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read user group', '', __LINE__, __FILE__, $sql);
        }
        $member = ( $db->sql_numrows($result) > 0 );
    }
    $sql_member = $member ? '' : 'AND g.group_type <> ' . GROUP_HIDDEN;

    // read the members of the group
    $sql = "SELECT u.username
                FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
                WHERE g.group_id = $group_id
                    AND g.group_single_user = 0
                    AND ug.group_id = g.group_id
                    AND u.user_id = ug.user_id
                    AND ug.user_id <> $view_user_id
                    $sql_member";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read group members', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $s_to_users .=  ( empty($s_to_users) ? '' : ', ') . "'" . trim(str_replace("''", "\'", $row['username'])) . "'";
    }
}

// search for users
if ( !empty($s_to_users) || $submit || $refresh )
{
    $privmsg_to_user_ids = array();
    if ( !empty($s_to_users) )
    {
        $sql = "SELECT DISTINCT user_id
                    FROM " . USERS_TABLE . "
                    WHERE username IN ($s_to_users)
                        AND user_id <> " . ANONYMOUS . "
                    GROUP BY user_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read users data', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $privmsg_to_user_ids[] = $row['user_id'];
        }
    }
}

// continue with the message
$post_icon  = _read_var('post_icon', 1, $post_icon);
$subject    = _read_var('subject', 12, $subject);
$message    = _read_var('message', 12, $message);

// check if Post icon mod installed and if icon exists
if ( !$mod_post_icon )
{
    $post_icon = 0;
}

if ( $submit || $refresh )
{
    $bbcode_on  = !_button_var('disable_bbcode') && $board_config['allow_bbcode'];
    $html_on    = !_button_var('disable_html') && $board_config['allow_html'];
    $smilies_on = !_button_var('disable_smilies') && $board_config['allow_smilies'];
    $attach_sig = _button_var('attach_sig') && $board_config['allow_sig'];
}

//-----------------------------
//
//  preview
//
//-----------------------------
if ( $preview )
{
    $bbcode_uid = ( $bbcode_on ) ? $bbcode_parse->make_bbcode_uid() : '';
    $preview_message = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
    $preview_subject = $subject;

    if ( !$html_on || !$view_userdata['user_allowhtml'])
    {
        $preview_message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $preview_message);
        if( !empty($user_sig) )
        {
            $user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
        }
    }
    if ( $attach_sig && !empty($user_sig) && !empty($user_sig_bbcode_uid) )
    {
        $user_sig = $bbcode_parse->bbencode_second_pass($user_sig, $user_sig_bbcode_uid);
    }

    if ( $bbcode_on && $view_userdata['user_allowbbcode'])
    {
        $preview_message = $bbcode_parse->bbencode_second_pass($preview_message, $bbcode_uid);
    }
    if ( !empty($orig_word) )
    {
        $preview_subject = ( !empty($subject) ) ? preg_replace($orig_word, $replacement_word, $preview_subject) : '';
        $preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
    }

    // icons
    if ( $mod_post_icon )
    {
        $preview_subject = get_icon_title($post_icon, 0, POST_NORMAL) . '&nbsp;' . $subject;
    }

    $preview_message = $bbcode_parse->make_clickable($preview_message);
    if ( !empty($user_sig) )
    {
        $user_sig = $bbcode_parse->make_clickable($user_sig);
    }

    if( $smilies_on && $view_userdata['user_allowsmile'])
    {
        $preview_message = $bbcode_parse->smilies_pass($preview_message);
        if( !empty($user_sig) )
        {
            $user_sig = $bbcode_parse->smilies_pass($user_sig);
        }
    }

    if( $attach_sig && !empty($user_sig) )
    {
        $preview_message = $preview_message . '<br /><br />_________________<br />' . $user_sig;
    }
    $preview_message = str_replace("\n", '<br />', $preview_message);

    $preview_message = $bbcode_parse->acronym_pass( $preview_message );
    $preview_message = $bbcode_parse->smart_pass( $preview_message );

}

//-----------------------------
//
//  process
//
//-----------------------------
// init error msg
$error = false;
$error_msg = '';

// test the action
if ( $cancel )
{
}
else if ( $submit )
{
    // some checks
    if ( empty($privmsg_to_user_ids) )
    {
        _error('No_to_user');
    }
    if ( empty($subject) )
    {
        _error('Empty_subject');
    }
    if ( empty($message) )
    {
        _error('Empty_message');
    }

    // send the message
    if ( !$error )
    {
        $w_id = ( $pmmode == 'edit' ) ? $privmsg_id : 0;
        $error_msg = send_pm( $w_id, $view_userdata, $privmsg_to_user_ids, trim(strip_tags(_read_var('subject'))), trim(_read_var('message')), intval($post_icon), $html_on, $bbcode_on, $smilies_on, $attach_sig );
        if ( !empty($error_msg) )
        {
            $error = true;
        }
    }

    // send end message
    if ( !$error )
    {
        $u_link = append_sid("$main_pgm&folder=" . INBOX);
        $l_link = _lang('Click_return_inbox');
        _message_return('Message_sent', $l_link, $u_link);
    }
}

// display the page
$page_title = _lang('Read_pm');
if ( !defined('IN_PCP') )
{
    include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
}

switch ( $pmmode )
{
    case 'post':
        $post_a = _lang('Send_a_new_message');
        break;
    case 'reply':
    case 'quote':
    case 'forward':
        $post_a = _lang('Send_a_reply');
        break;
    case 'edit':
        $post_a = _lang('Edit_message');
        break;
    default:
        $post_a = _lang('Send_a_reply');
        break;
}

$template->set_filenames(array(
    'body' => 'posting_body.tpl')
);
$template->assign_block_vars('switch_privmsga', array());
privmsg_header($view_user_id, $folder_id, $privmsg_recip_id);

// preview asked
if ( $preview )
{
    $template->set_filenames(array(
        'preview' => 'posting_preview.tpl')
    );

    $template->assign_vars(array(
        'TOPIC_TITLE' => $preview_subject,
        'POST_SUBJECT' => $preview_subject,
        'POSTER_NAME' => $preview_username,
        'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
        'MESSAGE' => $preview_message,

        'L_POST_SUBJECT' => _lang('Post_subject'),
        'L_PREVIEW' => _lang('Preview'),
        'L_POSTED' => _lang('Posted'),
        'L_POST' => _lang('Post'),
        )
    );
    $template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
}

// send error box
if ( $error )
{
    $template->set_filenames(array(
        'reg_header' => 'error_body.tpl')
    );
    $template->assign_vars(array(
        'ERROR_MESSAGE' => $error_msg,
        )
    );
    $template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

//
// Send smilies to template
//
generate_smilies('inline', PAGE_PRIVMSGS);

// get the recipients users names
if ( empty($privmsg_to_user_ids) )
{
    $to_users = '';
}
else
{
    $s_privmsg_to_user_ids = implode(', ', $privmsg_to_user_ids);
    $sql = "SELECT username
                FROM " . USERS_TABLE . "
                WHERE user_id IN ($s_privmsg_to_user_ids)
                    ORDER BY username";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read users data', '', __LINE__, __FILE__, $sql);
    }
    $to_users = '';
    while ( $row = $db->sql_fetchrow($result) )
    {
        $to_users .= ( empty($to_users) ? '' : ', ' ) . $row['username'];
    }
}

// HTML toggle selection
if ( $board_config['allow_html'] )
{
    $html_status = _lang('HTML_is_ON');
    $template->assign_block_vars('switch_html_checkbox', array());
}
else
{
    $html_status = _lang('HTML_is_OFF');
}
// BBCode toggle selection
if ( $board_config['allow_bbcode'] )
{
    $bbcode_status = _lang('BBCode_is_ON');
    $template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
    $bbcode_status = _lang('BBCode_is_OFF');
}
// Smilies toggle selection
if ( $board_config['allow_smilies'] )
{
    $smilies_status = _lang('Smilies_are_ON');
    $template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
    $smilies_status = _lang('Smilies_are_OFF');
}
// Signature toggle selection
if ( $board_config['allow_sig'] )
{
    $template->assign_block_vars('switch_signature_checkbox', array());
}

// header
$template->assign_vars(array(
    'SUBJECT'               => $subject,
    'TO_USERS'              => $to_users,
    'MESSAGE'               => $message,
    'HTML_STATUS'           => $html_status,
    'SMILIES_STATUS'        => $smilies_status,
    'BBCODE_STATUS'         => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'),
    'FORUM_NAME'            => _lang('Private_Message'),

    'L_USERNAMES'           => _lang('Recipients'),
    'L_ADD_GROUP'           => _lang('Select_group'),
    'S_GROUPS_LIST'         => get_groups_list($view_userdata),

    'BOX_NAME'              => $l_box_name,

    'L_SUBJECT'             => _lang('Subject'),
    'L_MESSAGE_BODY'        => _lang('Message_body'),
    'L_OPTIONS'             => _lang('Options'),
    'L_SPELLCHECK'          => _lang('Spellcheck'),
    'L_PREVIEW'             => _lang('Preview'),
    'L_SUBMIT'              => _lang('Submit'),
    'L_CANCEL'              => _lang('Cancel'),
    'L_POST_A'              => $post_a,
    'L_FIND_USERNAME'       => _lang('Find_username'),
    'L_FIND'                => _lang('Find'),
    'L_DISABLE_HTML'        => _lang('Disable_HTML_pm'),
    'L_DISABLE_BBCODE'      => _lang('Disable_BBCode_pm'),
    'L_DISABLE_SMILIES'     => _lang('Disable_Smilies_pm'),
    'L_ATTACH_SIGNATURE'    => _lang('Attach_signature'),

    'S_HTML_CHECKED'        => ( !$html_on ) ? ' checked="checked"' : '',
    'S_BBCODE_CHECKED'      => ( !$bbcode_on ) ? ' checked="checked"' : '',
    'S_SMILIES_CHECKED'     => ( !$smilies_on ) ? ' checked="checked"' : '',
    'S_SIGNATURE_CHECKED'   => ( $attach_sig ) ? ' checked="checked"' : '',
    'S_NAMES_SELECT'        => $user_names_select,

    'U_SEARCH_USER'         => append_sid("./search.$phpEx?mode=searchuser&multi=1"),
    'U_VIEW_FORUM'          => '',
    'U_REVIEW_TOPIC'        => $review ? append_sid("$main_pgm&pmmode=review&" . POST_POST_URL . "=$privmsg_recip_id&" . POST_USERS_URL . "=$view_user_id") : '',
    )
);

// add bbcodes
bbcodes_posting();

// post icon mod installed
if ( $mod_post_icon )
{
    // get the number of icon per row from config
    $icon_per_row = isset($board_config['icon_per_row']) ? intval($board_config['icon_per_row']) : 10;
    if ($icon_per_row <= 1)
    {
        $icon_per_row = 10;
    }

    // get the list of icon available to the user
    $icones_sort = array();
    for ($i = 0; $i < count($icones); $i++)
    {
        switch ($icones[$i]['auth'])
        {
            case AUTH_ADMIN:
                if ( $userdata['user_level'] == ADMIN )
                {
                    $icones_sort[] = $i;
                }
                break;
            case AUTH_MOD:
                if ( $is_auth['auth_mod'] )
                {
                    $icones_sort[] = $i;
                }
                break;
            case AUTH_REG:
                if ( $userdata['session_logged_in'] )
                {
                    $icones_sort[] = $i;
                }
                break;
            default:
                $icones_sort[] = $i;
                break;
        }
    }

    // check if the icon exists
    $found = false;
    for ($i=0; ( ($i < count($icones_sort)) && !$found );$i++)
    {
        $found = ($icones[ $icones_sort[$i] ]['ind'] == $post_icon);
    }
    if ( !$found )
    {
        $post_icon = 0;
    }

    // send to template
    $template->assign_block_vars('switch_icon_checkbox', array());
    $template->assign_vars(array(
        'L_ICON_TITLE' => _lang('post_icon_title'),
        )
    );

    // display the icons
    $nb_row = intval( (count($icones_sort)-1) / $icon_per_row )+1;
    $offset = 0;
    for ($i=0; $i < $nb_row; $i++)
    {
        $template->assign_block_vars('switch_icon_checkbox.row',array());
        for ($j=0; ( ($j < $icon_per_row) && ($offset < count($icones_sort)) ); $j++)
        {
            $icon_id  = $icones_sort[$offset];

            // send to cell or cell_none
            $template->assign_block_vars('switch_icon_checkbox.row.cell', array(
                'ICON_ID'       => $icones[$icon_id]['ind'],
                'ICON_CHECKED'  => ($post_icon == $icones[$icon_id]['ind']) ? ' checked="checked"' : '',
                'ICON_IMG'      => get_icon_title($icones[$icon_id]['ind'], 2),
                )
            );
            $offset++;
        }
    }
}

// system
_hide(POST_USERS_URL, $view_user_id);
_hide('pmmode', $pmmode);
_hide('folder', $folder_id);
_hide('start', $pm_start);
if ( !empty($privmsg_id) )
{
    _hide(POST_POST_URL, $privmsg_recip_id);
}
$template->assign_vars(array(
    'S_POST_ACTION'         => append_sid($main_pgm),
    'S_HIDDEN_FORM_FIELDS'  => _hidden_get(),
    )
);

// send to browser
privmsg_footer();

// privmsg review
if ( $review )
{
    include_once($phpbb_root_path . 'includes/privmsga_review.' . $phpEx);
    privmsg_review($view_user_id, $privmsg_recip_id, true);
    $template->assign_block_vars('switch_inline_mode', array());
    $template->assign_var_from_handle('TOPIC_REVIEW_BOX', 'reviewbody');
}

$template->pparse('body');
if ( !defined('IN_PCP') )
{
    include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
}

?>