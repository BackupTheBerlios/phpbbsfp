<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_privmsga.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_privmsga.php
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
include_once($phpbb_root_path . './includes/functions_sys.' . $phpEx);

// constants
$s_unread = implode(', ', array(NEW_MAIL, UNREAD_MAIL));
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array('All_Posts', '1_Day', '7_Days', '2_Weeks', '1_Month', '3_Months', '6_Months', '1_Year');

// nav separator
$from_to_separator = ' &raquo; ';
if ( !isset($nav_separator) )
{
    $nav_separator = '&nbsp;->&nbsp;';
}

// default display name class
$userclass = 'name';

//-------------------------------------------------
//
//  check_user() : check if the user is allowed to acces another user display
//
//-------------------------------------------------
function check_user($view_userdata, $redirect = '')
{
    global $userdata, $lang, $board_config, $phpbb_root_path, $phpEx;
    global $pmmode;

    // Is PM disabled?
    if ( !empty($board_config['privmsg_disable']) )
    {
        message_die(GENERAL_MESSAGE, 'PM_disabled');
    }

    // is the actor logged ?
    if ( !$userdata['session_logged_in'] )
    {
        $redirect = str_replace('?', '&', $redirect);
        redirect(append_sid("./login.$phpEx?redirect=$redirect", true));
    }

    // is the actor the viewed user ?
    if ( $view_userdata['user_id'] != $userdata['user_id'] )
    {
        switch ( $pmmode )
        {
            default:
                message_die(GENERAL_ERROR, 'Not_Authorised');
                break;
        }
    }

    // actor
    if ( !$userdata['user_allow_pm'] )
    {
        message_die(GENERAL_MESSAGE, 'Cannot_send_privmsg');
    }

    // viewed
    if ( !$view_userdata['user_allow_pm'] )
    {
        message_die(GENERAL_MESSAGE, 'Cannot_send_privmsg');
    }
}

//-------------------------------------------------
//
//  get_days_list() : return options of a drop down menu with "since x days" list
//
//-------------------------------------------------
function get_days_list($msg_days)
{
    global $lang;
    global $previous_days, $previous_days_text;

    $select_msg_days = '';
    for($i = 0; $i < count($previous_days); $i++)
    {
        $selected = ( $msg_days == $previous_days[$i] ) ? ' selected="selected"' : '';
        $select_msg_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . _lang( $previous_days_text[$i] ) . '</option>';
    }

    return $select_msg_days;
}

//-------------------------------------------------
//
//  get_groups_list() : return options of a drop down menu with groups available to this user
//
//-------------------------------------------------
function get_groups_list($view_userdata, $group_id=0)
{
    global $db, $lang;

    $res = '';

    $user_id = $view_userdata['user_id'];
    if ( empty($user_id) || ($user_id == ANONYMOUS) )
    {
        $res = '<option value="">' . _lang('None') . '</option>';
        return $res;
    }

    // get the groups hidden the user belongs to
    $sql_hidden_groups = '';
    if ( $view_userdata['user_level'] != ADMIN )
    {
        $s_hidden_groups = '';
        $sql = "SELECT * FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
                    WHERE ug.group_id = g.group_id
                        AND g.group_single_user = 0
                        AND g.group_type = " . GROUP_HIDDEN . "
                        AND ug.user_id = $user_id
                        AND ug.user_pending = 0";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read user\'s groups', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $s_hidden_groups .= ( empty($s_hidden_groups) ? '' : ', ' ) . $row['group_id'];
        }
        if ( empty($s_hidden_groups) )
        {
            $sql_hidden_groups = " AND group_type <> " . GROUP_HIDDEN;
        }
        else
        {
            $sql_hidden_groups = " AND (group_type <> " . GROUP_HIDDEN . " OR group_id IN ($s_hidden_groups))";
        }
    }

    // get groups
    $sql = "SELECT * FROM " . GROUPS_TABLE . "
                WHERE group_single_user = 0 $sql_hidden_groups
                ORDER by group_name";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read users groups', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $selected = ($group_id == $row['group_id']) ? ' selected="selected"' : '';
        $res .= '<option value="' . intval($row['group_id']) . '"' . $selected . '>' . $row['group_name'] . '</option>';
    }

    // add friend list
    if ( defined('IN_PCP') )
    {
        $selected = ($group_id == FRIEND_LIST_GROUP) ? ' selected="selected"' : '';
        $res = '<option value="' . FRIEND_LIST_GROUP . '"' . $selected . '>[ ' . _lang('Friend_list') . ' ]</option>' . $res;
    }

    if ( empty($res) )
    {
        $res = '<select value="">' . _lang('None') . '</option>';
    }

    return $res;
}

//-------------------------------------------------
//
//  get_user_folders() : return the $folders array of a user
//
//-------------------------------------------------
function get_user_folders($view_user_id)
{
    global $db;

    $res = array();

    // get common folders
    $sql = "SELECT * FROM " . PRIVMSGA_FOLDERS_TABLE . "
                WHERE (folder_user_id = 0 OR folder_user_id = $view_user_id)
                    ORDER BY folder_main, folder_order, folder_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Can\'t read pm folders', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $res['main'][ $row['folder_id'] ] = $row['folder_main'];
        $res['data'][ $row['folder_id'] ] = $row;
        if ( !empty($row['folder_main']) )
        {
            $res['sub'][ $row['folder_main'] ][] = $row['folder_id'];
        }
    }

    return $res;
}

//-------------------------------------------------
//
//  get_user_folders() : return a drop down list of options with folders of the user
//
//-------------------------------------------------
function get_folders_list($folder_id, $selected_id = 0)
{
    global $lang;
    global $folders;

    // go to main
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_id = $folders['main'][$folder_id];
    }

    // get all mains if required
    $mains = array();
    if ( empty($selected_id) )
    {
        $mains[] = $folder_id;
    }
    else
    {
        @reset($folders['data']);
        while ( list($id, $data) = @each($folders['data']) )
        {
            if ( empty($data['folder_main']) )
            {
                $mains[] = $id;
            }
        }
    }

    // build the tree
    $res = '';

    for ( $i = 0; $i < count($mains); $i++ )
    {
        // add the main level
        $selected = ( $mains[$i] == $selected_id ) ? ' selected="selected"' : '';
        $res .= '<option value="' . $mains[$i] . '"' . $selected . '>' . _lang( $folders['data'][ $mains[$i] ]['folder_name'] ) . '</option>';

        // add sublevels
        for ( $j = 0; $j < count($folders['sub'][ $mains[$i] ]); $j++ )
        {
            $id = $folders['sub'][ $mains[$i] ][$j];
            $selected = ( $id == $selected_id ) ? ' selected="selected"' : '';
            $res .= '<option value="' . $id . '"' . $selected . '>&nbsp;&nbsp;&nbsp;&raquo;&nbsp;' . _lang( $folders['data'][$id]['folder_name'] ) . '</option>';
        }
    }

    return $res;
}

//-------------------------------------------------
//
//  renum_folders() : renum the folders 10 to 10 by box
//
//-------------------------------------------------
function renum_folders($view_user_id)
{
    global $db;

    // renum
    $sav_folder_main = 0;
    $sql = "SELECT *
                FROM " . PRIVMSGA_FOLDERS_TABLE . "
                WHERE folder_user_id = $view_user_id
                ORDER BY folder_main, folder_order, folder_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read folder', '', __LINE__, __FILE__, $sql);
    }
    $count = 0;
    while ( $row = $db->sql_fetchrow($result) )
    {
        if ( $sav_folder_main != $row['folder_main'] )
        {
            $sav_folder_main = $row['folder_main'];
            $count = 0;
        }
        $count += 10;
        $sql = "UPDATE " . PRIVMSGA_FOLDERS_TABLE . "
                    SET folder_order = $count
                    WHERE folder_id = " . $row['folder_id'];
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update folder order', '', __LINE__, __FILE__, $sql);
        }
    }
}

//-------------------------------------------------
//
//  display_percent() : display the percentage of usage for a box
//
//-------------------------------------------------
function display_percent($view_user_id, $folder_id, $count)
{
    global $template, $lang, $images, $board_config;
    global $cfg_max_inbox, $cfg_max_outbox, $cfg_max_sentbox, $cfg_max_savebox;
    global $folders;

    // no folder granted
    if ( empty($folders['data'][$folder_id]) )
    {
        $template->assign_block_vars('folders_box.switch_size_notice_no', array());
        return;
    }

    // go to main folder
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_id = $folders['main'][$folder_id];
    }

    // get the cfg_key
    switch ( $folder_id )
    {
        case INBOX:
            $cfg_max_box = $cfg_max_inbox;
            break;
        case OUTBOX:
            $cfg_max_box = $cfg_max_outbox;
            break;
        case SENTBOX:
            $cfg_max_box = $cfg_max_sentbox;
            break;
        case SAVEBOX:
            $cfg_max_box = $cfg_max_savebox;
            break;
    }

    // if no max defined, end of process
    if ( empty($cfg_max_box) )
    {
        $template->assign_block_vars('folders_box.switch_size_notice_no', array());
        return;
    }

    // compute the values
    $max = ( intval($cfg_max_box) > 0 ) ? intval($cfg_max_box) : 1;

    $box_percent = round( $count * 100 / $max );
    $box_width = min( round($board_config['privmsg_graphic_length'] * $count / $max), $board_config['privmsg_graphic_length'] );

    $template->assign_vars(array(
        'SPACER'        => isset($images['spacer']) ? $images['spacer'] : 'images/spacer.gif',
        'BOX_STATUS'    => sprintf(_lang($folders['data'][$folder_id]['folder_name'] . '_size'), $box_percent),
        'BOX_WIDTH'     => $box_width,
        'BOX_PERCENT'   => $box_percent,
        )
    );
    $template->assign_block_vars('folders_box.switch_size_notice', array());
    if ( $box_percent > 0 )
    {
        $template->assign_block_vars('folders_box.switch_size_notice.switch_not_empty', array());
    }
    else
    {
        $template->assign_block_vars('folders_box.switch_size_notice.switch_empty', array());
    }
}

//-------------------------------------------------
//
//  display_menu() : display the left box list
//
//-------------------------------------------------
function display_menu($view_user_id, $folder_id)
{
    global $db, $template, $lang, $images, $board_config;
    global $main_pgm;
    global $folders;
    global $s_unread;

    // folder management
    if ( defined('IN_FOLDERS') )
    {
        display_menu_folders($view_user_id, $folder_id);
        return;
    }

    // no folder granted
    if ( empty($folders['data'][$folder_id]) )
    {
        $folder_id = INBOX;
    }
    $folder_main = $folder_id;
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_main = $folders['main'][$folder_id];
    }

    // get main folders
    $mains = array();
    @reset($folders['data']);
    while ( list($id, $data) = @each($folders['data']) )
    {
        if ( empty($folders['main'][$id]) )
        {
            $mains[] = $id;
        }
    }

    // count new/unread messages
    $count_messages = array();

    //------------------------------
    //  count mails per box
    //------------------------------
    for ( $i = 0; $i < count($mains); $i++ )
    {
        $sql_where = '';
        switch ($mains[$i])
        {
            case INBOX:
                $sql_where = "privmsg_direct = 1 AND privmsg_status = " . STS_TRANSIT;
                break;
            case OUTBOX:
                $sql_where = "privmsg_direct = 0 AND privmsg_read IN ($s_unread) AND privmsg_status = " . STS_TRANSIT;
                break;
            case SENTBOX:
                $sql_where = "privmsg_direct = 0 AND privmsg_read = " . READ_MAIL . " AND privmsg_status = " . STS_TRANSIT;
                break;
            case SAVEBOX:
                $sql_where = "privmsg_status = " . STS_SAVED;
                break;
        }
        // base sql
        $sql_box = "SELECT privmsg_folder_id, count(privmsg_id) AS count_messages
                        FROM " . PRIVMSGA_RECIPS_TABLE . "
                        WHERE privmsg_user_id = $view_user_id
                            AND $sql_where";

        // count all in a box
        $sql = $sql_box . " GROUP BY privmsg_folder_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not count messages from box', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $count_messages['local'][ $row['privmsg_folder_id'] ] = $row['count_messages'];
        }

        // count unread
        $sql = $sql_box . " AND privmsg_read IN ($s_unread) GROUP BY privmsg_folder_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not count unread messages', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            $count_messages['local_unread'][ $row['privmsg_folder_id'] ] = $row['count_messages'];
        }
    }

    // sum the message to the main folder : total
    @reset($count_messages['local']);
    while ( list($id, $count) = @each($count_messages['local']) )
    {
        $main_id = $id;
        if ( !empty($folders['main'][$id]) )
        {
            $main_id = $folders['main'][$id];
        }
        $count_messages['main'][$main_id] += $count;
    }

    // sum the message to the main folder : unread
    @reset($count_messages['local_unread']);
    while ( list($id, $count) = @each($count_messages['local_unread']) )
    {
        $main_id = $id;
        if ( !empty($folders['main'][$id]) )
        {
            $main_id = $folders['main'][$id];
        }
        $count_messages['main_unread'][$main_id] += $count;
    }

    //------------------------------
    // process
    //------------------------------
    // build the tree
    $template->set_filenames(array(
        'privmsga_folders_box' => 'privmsga_folders_box.tpl')
    );

    $template->assign_vars(array(
        'L_FOLDERS' => _lang('Folders'),
        )
    );

    $template->assign_block_vars('folders_box', array(
        'U_SEARCH'          => append_sid("$main_pgm&pmmode=search&folder=$folder_id&" . POST_USERS_URL . "=$view_user_id"),
        'L_SEARCH'          => _lang('Search'),
        'IMG_SEARCH'        => _images('icon_search'),
        'U_EDIT'            => append_sid("$main_pgm&pmmode=flist&folder=$folder_id&" . POST_USERS_URL . "=$view_user_id"),
        'L_EDIT'            => _lang('Edit'),
        'IMG_EDIT'          => _images('icon_folder_edit'),
        )
    );
    for ( $i = 0; $i < count($mains); $i++ )
    {
        // add the main level
        $id = $mains[$i];
        $name = _lang($folders['data'][$id]['folder_name']);
        if ( $id == $folder_id )
        {
            $name = sprintf('<b>%s</b>', $name);
        }

        // counts
        $count = 0;
        $count_unread = 0;
        if ( ($id == $folder_id) || ($id == $folder_main) )
        {
            // we are on the select box : use local count
            if ( $count_messages['local'][$id] > 0 )
            {
                $count = $count_messages['local'][$id];
            }
            if ( $count_messages['local_unread'][$id] > 0 )
            {
                $count_unread = $count_messages['local_unread'][$id];
            }
        }
        else
        {
            // we are on a non-selected box : use sum count
            if ( $count_messages['main'][$id] > 0 )
            {
                $count = $count_messages['main'][$id];
            }
            if ( $count_messages['main_unread'][$id] > 0 )
            {
                $count_unread = $count_messages['main_unread'][$id];
            }
        }

        // icon
        $img = 'icon_minipost';
        if ( $count_unread > 0)
        {
            $img = 'icon_minipost_new';
        }

        // display main folder
        $template->assign_block_vars('folders_box.main', array(
            'COLOR'         => ( ($id == $folder_id) || ($id == $folder_main) ) ? 'row1' : 'row2',
            'IMG_FOLDER'    => _images($img),
            'U_FOLDER'      => append_sid("$main_pgm&folder=$id"),
            'L_FOLDER'      => $name,
            'COUNT'         => sprintf('(%d)', $count),
            'COUNT_UNREAD'  => sprintf('(%d)', $count_unread),
            )
        );
        $template->assign_block_vars('folders_box.main.no_manage', array());

        // add sublevels
        if ( $id == $folder_main )
        {
            $sub_exist = false;
            for ( $j = 0; $j < count($folders['sub'][ $mains[$i] ]); $j++ )
            {
                $sub_exist = true;
                $id = $folders['sub'][ $mains[$i] ][$j];
                $name = _lang($folders['data'][$id]['folder_name']);
                if ( $id == $folder_id )
                {
                    $name = sprintf('<b>%s</b>', $name);
                }

                // counts
                $count = 0;
                $count_unread = 0;

                // we are on the select box : use local count
                if ( $count_messages['local'][$id] > 0 )
                {
                    $count = $count_messages['local'][$id];
                }
                if ( $count_messages['local_unread'][$id] > 0 )
                {
                    $count_unread = $count_messages['local_unread'][$id];
                }

                // icon
                $img = 'icon_minipost';
                if ( $count_unread > 0 )
                {
                    $img = 'icon_minipost_new';
                }

                $template->assign_block_vars('folders_box.main.sub', array(
                    'IMG_FOLDER'    => _images($img),
                    'U_FOLDER'      => append_sid("$main_pgm&folder=$id"),
                    'L_FOLDER'      => $name,
                    'COUNT'         => sprintf('(%d)', $count),
                    'COUNT_UNREAD'  => sprintf('(%d)', $count_unread),
                    )
                );
                $template->assign_block_vars('folders_box.main.sub.no_manage', array());
            }
            if ( $sub_exist )
            {
                $template->assign_block_vars('folders_box.main.sub_exist', array());
            }
        }
    }
    $template->assign_block_vars('folders_box.switch_search', array());
    $template->assign_block_vars('folders_box.switch_no_manage', array());
    display_percent($view_user_id, $folder_main, $count_messages['main'][$folder_main]);
    $template->assign_var_from_handle('FOLDERS_BOX', 'privmsga_folders_box');
}

//-------------------------------------------------
//
//  display_menu_folders() : display the left box list with link to folder management
//
//-------------------------------------------------
function display_menu_folders($view_user_id, $folder_id)
{
    global $db, $template, $lang, $images, $board_config;
    global $main_pgm;
    global $folders;

    // no folder granted
    if ( empty($folders['data'][$folder_id]) )
    {
        $folder_id = INBOX;
    }
    $folder_main = $folder_id;
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_main = $folders['main'][$folder_id];
    }

    // get main folders
    $mains = array();
    @reset($folders['data']);
    while ( list($id, $data) = @each($folders['data']) )
    {
        if ( empty($folders['main'][$id]) )
        {
            $mains[] = $id;
        }
    }

    //------------------------------
    // process
    //------------------------------
    // build the tree
    $template->set_filenames(array(
        'privmsga_folders_box' => 'privmsga_folders_box.tpl')
    );

    $template->assign_vars(array(
        'L_FOLDERS'         => _lang('Folders'),
        'L_EDIT'            => _lang('Edit'),
        'L_DELETE'          => _lang('Delete'),
        'L_MOVEUP'          => _lang('Move_up'),
        'L_MOVEDOWN'        => _lang('Move_down'),
        'IMG_MOVEUP'        => _images('up_arrow'),
        'IMG_MOVEDOWN'      => _images('down_arrow'),
        'U_ADD_FOLDER'      => append_sid("$main_pgm&pmmode=fcreate&folder=$folder_id&" . POST_USERS_URL . "=$view_user_id"),
        'L_ADD_FOLDER'      => _lang('Add_new_subfolder'),
        'IMG_ADD_FOLDER'    => _images('icon_folder_new'),
        )
    );

    $template->assign_block_vars('folders_box', array());
    for ( $i = 0; $i < count($mains); $i++ )
    {
        // add the main level
        $id = $mains[$i];
        $name = _lang($folders['data'][$id]['folder_name']);
        if ( $id == $folder_id )
        {
            $name = sprintf('<b>%s</b>', $name);
        }

        // display main folder
        $template->assign_block_vars('folders_box.main', array(
            'COLOR'         => ( ($id == $folder_id) || ($id == $folder_main) ) ? 'row1' : 'row2',
            'U_FOLDER'      => append_sid("$main_pgm&pmmode=flist&folder=$id"),
            'L_FOLDER'      => $name,
            )
        );

        // add sublevels
        if ( $id == $folder_main )
        {
            $sub_exist = false;
            for ( $j = 0; $j < count($folders['sub'][ $mains[$i] ]); $j++ )
            {
                $sub_exist = true;
                $id = $folders['sub'][ $mains[$i] ][$j];
                $name = _lang($folders['data'][$id]['folder_name']);
                if ( $id == $folder_id )
                {
                    $name = sprintf('<b>%s</b>', $name);
                }

                $template->assign_block_vars('folders_box.main.sub', array(
                    'IMG_FOLDER'    => _images($img),
                    'U_FOLDER'      => append_sid("$main_pgm&pmmode=flist&folder=$id"),
                    'L_FOLDER'      => $name,
                    'U_EDIT'        => append_sid("$main_pgm&pmmode=fedit&folder=$id"),
                    'U_DELETE'      => append_sid("$main_pgm&pmmode=fdelete&folder=$id"),
                    'U_MOVEUP'      => append_sid("$main_pgm&pmmode=fmoveup&folder=$id"),
                    'U_MOVEDOWN'    => append_sid("$main_pgm&pmmode=fmovedw&folder=$id"),
                    )
                );
                $template->assign_block_vars('folders_box.main.sub.manage', array());
            }
            if ( $sub_exist )
            {
                $template->assign_block_vars('folders_box.main.sub_exist', array());
            }
        }
    }
    $template->assign_block_vars('folders_box.switch_manage', array());
    $template->assign_var_from_handle('FOLDERS_BOX', 'privmsga_folders_box');
}

//-------------------------------------------------
//
//  privmsg_header() : display header
//
//-------------------------------------------------
function privmsg_header($view_user_id, $folder_id, $privmsg_recip_id=0)
{
    global $template, $db, $userdata;
    global $lang, $images, $board_config, $phpEx, $phpbb_root_path;
    global $folders;
    global $main_pgm;
    global $nav_separator;

    // fix the folder id
    if ( !isset($folders['data'][$folder_id]) )
    {
        $folder_id = INBOX;
    }
    $folder_main = $folder_id;
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_main = $folders['main'][$folder_id];
    }

    $template->set_filenames(array(
        'privmsga_header' => 'privmsga_header.tpl')
    );

    // start the display
    if ( !defined('IN_PCP') )
    {
        make_jumpbox('viewforum.'.$phpEx);
    }

    // send the menu
    display_menu($view_user_id, $folder_id);

    // nav sentence
    $u_main = append_sid("$main_pgm&folder=$folder_main");
    $l_main = _lang( $folders['data'][$folder_main]['folder_name'] );
    $u_subf = append_sid("$main_pgm&folder=$folder_id");
    $l_subf = _lang( $folders['data'][$folder_id]['folder_name'] );
    if ( $folder_main == $folder_id )
    {
        $u_main = $u_subf;
        $l_main = $l_subf;
        $u_subf = '';
        $l_subf = '';
    }

    // generate some command buttons and the nav sentence
    $template->assign_vars(array(
        'U_POST_NEW_PM'         => append_sid("$main_pgm&pmmode=post"),
        'L_POST_NEW_PM'         => _lang('Post_new_pm'),
        'POST_IMG'              => _images('pm_postmsg'),

        'U_REPLY_PM'            => append_sid("$main_pgm&pmmode=reply&" . POST_POST_URL . "=$privmsg_recip_id"),
        'L_REPLY_PM'            => _lang('Post_reply_pm'),
        'REPLY_IMG'             => _images('pm_replymsg'),

        'U_FOLDER'              => $u_main,
        'L_FOLDER'              => $l_main,
        'U_SUBFOLDER'           => $u_subf,
        'L_SUBFOLDER'           => $l_subf,
        )
    );

    if ( !defined('IN_PCP') )
    {
        $template->assign_block_vars('switch_not_in_pcp', array());
        $template->assign_block_vars('switch_nav_sentence', array());
        $template->assign_block_vars('switch_not_in_pcp.switch_nav_sentence', array());
        if ( !empty($u_subf) )
        {
            $template->assign_block_vars('switch_nav_sentence.switch_subfolder', array());
            $template->assign_block_vars('switch_not_in_pcp.switch_nav_sentence.switch_subfolder', array());
        }
    }
    else
    {
        $template->assign_block_vars('switch_in_pcp', array());
    }

    // edit folder list
    if ( !defined('IN_POSTING') && !defined('IN_FOLDERS') )
    {
        $template->assign_block_vars('switch_new_post', array());
        if ( !defined('IN_PCP') )
        {
            $template->assign_block_vars('switch_not_in_pcp.switch_new_post', array());
        }
    }

    // reply button
    if ( !empty($privmsg_recip_id) && ($folder_main == INBOX) )
    {
        $template->assign_block_vars('switch_reply', array());
        if ( !defined('IN_PCP') )
        {
            $template->assign_block_vars('switch_not_in_pcp.switch_reply', array());
        }
    }

    // system
    _hide('sid', $userdata['session_id']);
    $template->assign_vars(array(
        'NAV_SEPARATOR' => $nav_separator,
        )
    );

    $template->assign_var_from_handle('EXTERNAL_HEADER', 'privmsga_header');
}

//-------------------------------------------------
//
//  privmsg_list() : display a list of pm
//
//-------------------------------------------------
function privmsg_list($privmsg_rowset, $recips, $folder_id, $select=false, $mark_ids=array(), $detailed=false)
{
    global $template, $userdata;
    global $lang, $images, $board_config, $phpEx, $phpbb_root_path;
    global $folders;
    global $main_pgm, $from_to_separator;
    global $all_marked, $marked_on_this_page;
    global $msg_days;
    global $nav_separator;
    global $icones;

    // is the post icon mod installed ?
    $mod_post_icon = function_exists('get_icon_title');

    // censor word
    $orig_word = array();
    $replacement_word = array();
    obtain_word_list($orig_word, $replacement_word);

    // get main folder
    $folder_main = $folder_id;
    if ( !empty($folders['main'][$folder_id]) )
    {
        $folder_main = $folders['main'][$folder_id];
    }

    // author/recip
    $from_to = '';
    switch ($folder_main)
    {
        case INBOX:
            $from_to = _lang('From');
            break;
        case OUTBOX:
            $from_to = _lang('To');
            break;
        case SENTBOX:
            $from_to = _lang('To');
            break;
        case SAVEBOX:
            $from_to = _lang('From') . $from_to_separator . _lang('To');
            break;
    }

    // get save sub-folder list
    $s_move_folder = '';
    if ( $folder_main != SAVEBOX )
    {
        $s_move_folder = get_folders_list($folder_id);
    }
    $s_move_folder .= get_folders_list(SAVEBOX);


    // template name
    $template->set_filenames(array(
        'privmsga_box' => 'privmsga_box.tpl')
    );

    $span = 4;
    if ( $mod_post_icon )
    {
        $span++;
    }
    if ( $select )
    {
        $span++;
    }

    // Header
    $template->assign_vars(array(
        'L_DISPLAY_MESSAGES'    => _lang('Display_messages'),
        'S_SELECT_MSG_DAYS'     => get_days_list($msg_days),
        'L_GO'                  => _lang('Go'),
        'L_CANCEL'              => _lang('Cancel'),

        'L_FLAG'                => _lang('Flag'),
        'L_SUBJECT'             => $select ? _lang('Subject') : _lang('Private_Messages'),
        'L_FROM_OR_TO'          => $from_to,
        'L_DATE'                => _lang('Date'),
        'L_MARK'                => _lang('Mark'),
        'L_NO_MESSAGES'         => _lang('No_messages_folder'),

        'L_DELETE_MARKED'       => _lang('Delete_marked'),
        'L_DELETE_ALL'          => _lang('Delete_all'),
        'L_MOVE_MARKED'         => _lang('Move_marked'),
        'L_SAVE_TO_MAIL'        => _lang('Save_to_mail_message'),
        'S_SELECT_MOVE'         => $s_move_folder,

        'SPAN_ALL'              => $span,
        'SPAN_SUBJECT'          => $mod_post_icon ? 2 : 1,
        )
    );

    // process the display
    $all_marked = !empty($privmsg_rowset);
    $marked_on_this_page = array();
    $color = false;
    for ( $i = 0; $i < count($privmsg_rowset); $i++ )
    {
        $color = !$color;
        $privmsg_id = $privmsg_rowset[$i]['privmsg_id'];
        $privmsg_recip_id = $privmsg_rowset[$i]['selected_pm_id'];

        // get flag
        $read_icon_flag         = _images('pm_readmsg');
        $read_icon_flag_alt     = _lang('Read_message');
        $unread_icon_flag       = _images('pm_unreadmsg');
        $unread_icon_flag_alt   = _lang('Unread_message');
        $new_icon_flag          = _images('pm_newmsg');
        $new_icon_flag_alt      = _lang('New_message');

        // choose the good icon
        switch ( $privmsg_rowset[$i]['selected_read'] )
        {
            case NEW_MAIL:
                $icon_flag      = $new_icon_flag;
                $icon_flag_alt  = $new_icon_flag_alt;
                break;
            case UNREAD_MAIL:
                $icon_flag      = $unread_icon_flag;
                $icon_flag_alt  = $unread_icon_flag_alt;
                break;
            case READ_MAIL:
                $icon_flag      = $read_icon_flag;
                $icon_flag_alt  = $read_icon_flag_alt;
                break;
        }

        // get the status of the "select all" checkbox
        $marked = ( !empty($mark_ids) && in_array($privmsg_recip_id, $mark_ids) );
        if ( !$marked )
        {
            $all_marked = false;
        }
        else
        {
            $marked_on_this_page[] = $privmsg_recip_id;
        }

        // user display is the sender
        $a_in = true;
        $a_out = false;
        $w_from_to = array();
        if ( $detailed )
        {
            $w_from_to = array($a_in, $a_out);
        }
        else
        {
            switch ($folder_main)
            {
                case INBOX:
                    $w_from_to = array($a_in);
                    break;
                case OUTBOX:
                    $w_from_to = array($a_out);
                    break;
                case SENTBOX:
                    $w_from_to = array($a_out);
                    break;
                case SAVEBOX:
                    $w_from_to = array($a_in, $a_out);
                    break;
                default:
                    message_die(GENERAL_ERROR, _lang('No_such_folder'), '', __LINE__, __FILE__);
                    break;
            }
        }
        $s_username = '';
        for ( $k = 0; $k < count($w_from_to); $k++ )
        {
            $from = $w_from_to[$k];
            if ( $from )
            {
                $temp_url = empty($privmsg_rowset[$i]['privmsg_user_id']) ? append_sid("./index.$phpEx") : append_sid("./profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . '=' . $privmsg_rowset[$i]['privmsg_user_id']);
                $temp_lib = empty($privmsg_rowset[$i]['privmsg_user_id']) ? $board_config['sitename'] : $privmsg_rowset[$i]['privmsg_from_username'];
                $s_username .= ( empty($s_username) ? '' : (($j == 0) ? $from_to_separator : ', ') ) . '<a href="' . $temp_url . '" class="' . $userclass . '">' . $temp_lib . '</a>';
            }
            else
            {
                for ( $j = 0; $j < count($recips['data'][$privmsg_id]); $j++ )
                {
                    $temp_url = empty($recips['data'][$privmsg_id][$j]['privmsg_user_id']) ? append_sid("./index.$phpEx") : append_sid("./profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . '=' . $recips['data'][$privmsg_id][$j]['privmsg_user_id']);
                    $temp_lib = empty($recips['data'][$privmsg_id][$j]['privmsg_user_id']) ? $board_config['sitename'] : $recips['data'][$privmsg_id][$j]['privmsg_to_username'];
                    $s_username .= ( empty($s_username) ? '' : (($j == 0) ? $from_to_separator : ', ') ) . '<a href="' . $temp_url . '" class="' . $userclass . '">' . $temp_lib . '</a>';
                }
            }
            // add '...' if required
            if ( $recips['over'][$privmsg_id] )
            {
                $s_username .= ( empty($s_username) ? '' : ', ' ) . '...';
            }
        }

        $subject = preg_replace($orig_word, $replacement_word, $privmsg_rowset[$i]['privmsg_subject']);

        // nav sentence
        if ( $detailed )
        {
            $w_folder_id = $privmsg_rowset[$i]['privmsg_folder_id'];
            $w_folder_main = $w_folder_id;
            if ( !empty($folders['main'][$w_folder_id]) )
            {
                $w_folder_main = $folders['main'][$w_folder_id];
            }
            $u_main = append_sid("$main_pgm&folder=$w_folder_main");
            $l_main = _lang( $folders['data'][$w_folder_main]['folder_name'] );
            $u_subf = append_sid("$main_pgm&folder=$w_folder_id");
            $l_subf = _lang( $folders['data'][$w_folder_id]['folder_name'] );
            if ( $w_folder_main == $w_folder_id )
            {
                $u_main = $u_subf;
                $l_main = $l_subf;
                $u_subf = '';
                $l_subf = '';
            }
        }

        // post icons mod installed
        $post_icon = '';
        if ( $mod_post_icon )
        {
            $topic_type = POST_NORMAL;
            $post_icon = get_icon_title($privmsg_rowset[$i]['privmsg_icon'], 1, $topic_type);
        }

        // display
        $template->assign_block_vars('pm_row', array(
            'COLOR'         => $color ? 'row1' : 'row2',
            'FOLDER_IMG'    => $icon_flag,
            'L_FOLDER_ALT'  => $icon_flag_alt,
            'ICON'          => $post_icon,
            'SUBJECT'       => $subject,
            'U_SUBJECT'     => append_sid("$main_pgm&pmmode=view&start=$pm_start&folder=$folder_id&" . POST_POST_URL . "=$privmsg_recip_id"),
            'S_USERNAME'    => $s_username,
            'DATE'          => create_date($userdata['user_dateformat'], $privmsg_rowset[$i]['privmsg_time'], $userdata['user_timezone']),
            'CHECKED'       => $marked ? 'checked="checked"' : '',
            'S_MARK_ID'     => $privmsg_recip_id,

            'U_FOLDER'      => $u_main,
            'L_FOLDER'      => $l_main,
            'U_SUBFOLDER'   => $u_subf,
            'L_SUBFOLDER'   => $l_subf,
            )
        );

        // post icon mod installed
        if ( $mod_post_icon )
        {
            $template->assign_block_vars('pm_row.switch_icon', array());
        }
        else
        {
            $template->assign_block_vars('pm_row.switch_icon_no', array());
        }

        // selection available
        if ( $select )
        {
            $template->assign_block_vars('pm_row.privmsg_select', array());
        }
        else
        {
            $template->assign_block_vars('pm_row.privmsg_no_select', array());
        }

        // folder nav link asked
        if ( $detailed )
        {
            $template->assign_block_vars('pm_row.detailed', array());
            if ( !empty($u_subf) )
            {
                $template->assign_block_vars('pm_row.detailed.sub', array());
            }
            else
            {
                $template->assign_block_vars('pm_row.detailed.no_sub', array());
            }
        }
        else
        {
            $template->assign_block_vars('pm_row.not_detailed', array());
        }
    }

    // general marked
    $template->assign_vars(array(
        'CHECKED' => $all_marked ? 'checked="checked"' : '',
        )
    );

    // nothing to display
    if ( count($privmsg_rowset) == 0 )
    {
        $template->assign_block_vars('pm_empty', array());
    }

    // post icon nod installed
    if ( $mod_post_icon )
    {
        $template->assign_block_vars('switch_icon', array());
    }
    else
    {
        $template->assign_block_vars('switch_icon_no', array());
    }

    // selection of pms available
    if ( $select )
    {
        $template->assign_block_vars('privmsg_select', array());

        // save button : appears always for save box when we're not in savebox
        if ( ($folder_main <> SAVEBOX) || !empty($folders['sub'][$folder_main]) )
        {
            $template->assign_block_vars('privmsg_select.switch_move', array());
        }

        // delete button
        $template->assign_block_vars('privmsg_select.switch_delete', array());

        // save to mail
        $template->assign_block_vars('privmsg_select.switch_savetomail', array());
    }
    else if ( $detailed )
    {
        $template->assign_block_vars('switch_cancel', array());
    }
    else
    {
        $template->assign_block_vars('privmsg_no_select', array());
    }

    $template->assign_var_from_handle('PRIVMSGA_BOX', 'privmsga_box');
}

function privmsg_footer()
{
    global $template;
    global $phpbb_root_path, $phpEx;

    $template->set_filenames(array(
        'privmsga_footer' => 'privmsga_footer.tpl')
    );

    $template->assign_var_from_handle('EXTERNAL_FOOTER', 'privmsga_footer');
}

//-------------------------------------------------
//
//  resync_pm() : resync boxes, prune deleted messages, update user's counts
//
//-------------------------------------------------
function resync_pm($user_ids)
{
    global $db, $board_config;
    global $folders;
    global $s_unread;

    // check parms
    if ( empty($user_ids) )
    {
        return 'No_user_specified';
    }
    if ( !is_array($user_ids) )
    {
        $user_ids = array(intval($user_ids));
    }
    $s_user_ids = implode(', ', $user_ids);

    //----------------------------
    // adjust the impacted users private messages counts
    //----------------------------
    $sql = "SELECT privmsg_user_id, privmsg_read, COUNT(privmsg_id) AS count_messages
                FROM " . PRIVMSGA_RECIPS_TABLE . "
                WHERE privmsg_user_id IN ($s_user_ids)
                    AND privmsg_direct = 1
                    AND privmsg_status = " . STS_TRANSIT . "
                    AND privmsg_read IN ($s_unread)
                GROUP BY privmsg_user_id, privmsg_read";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not get the count from the privmsgs table', '', __LINE__, __FILE__, $sql);
    }
    $user_counter = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $key = ($row['privmsg_read'] == NEW_MAIL) ? 'user_new_privmsg' : 'user_unread_privmsg';
        $user_counter[ $row['privmsg_user_id'] ][$key] = $row['count_messages'];
    }

    // get the last message
    $sql = "SELECT pr.privmsg_user_id, MAX(p.privmsg_time) AS last_privmsg
                FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p
                WHERE p.privmsg_id = pr.privmsg_id
                    AND pr.privmsg_user_id IN ($s_user_ids)
                    AND pr.privmsg_direct = 1
                    AND pr.privmsg_status <> " . STS_DELETED . "
                GROUP BY pr.privmsg_user_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not get the greater message', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $user_counter[ $row['privmsg_user_id'] ]['user_last_privmsg'] = $row['last_privmsg'];
    }

    // update users
    for ( $i = 0; $i < count($user_ids); $i++ )
    {
        if ( !empty($user_ids[$i]) )
        {
            $sql = "UPDATE " . USERS_TABLE . "
                        SET user_new_privmsg = " . intval($user_counter[ $user_ids[$i] ]['user_new_privmsg']) . ",
                            user_unread_privmsg = " . intval($user_counter[ $user_ids[$i] ]['user_unread_privmsg']) . ",
                            user_last_privmsg = " . intval($user_counter[ $user_ids[$i] ]['user_last_privmsg']) . "
                        WHERE user_id = " . intval($user_ids[$i]);
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update users counter', '', __LINE__, __FILE__, $sql);
            }
        }
    }
}

//-------------------------------------------------
//
//  delete_pm() : delete private message
//
//-------------------------------------------------
function delete_pm($privmsg_recip_ids, $view_user_id)
{
    global $db;

    // no data
    if ( empty($privmsg_recip_ids) )
    {
        return 'No_post_id';
    }

    // not an array : do one
    if ( !is_array($privmsg_recip_ids) )
    {
        $privmsg_recip_ids = array( intval($privmsg_recip_ids) );
    }
    $s_privmsg_recip_ids = implode(', ', $privmsg_recip_ids);

    // mark the recip as deleted
    $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                SET privmsg_status = " . STS_DELETED . ", privmsg_read = " . READ_MAIL . "
                WHERE privmsg_recip_id IN ($s_privmsg_recip_ids)";
    if ( !$db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not delete recipients', '', __LINE__, __FILE__, $sql);
    }

    // resync user's boxes
    resync_pm($view_user_id);
}

//-------------------------------------------------
//
//  move_pm() : move/save messages
//
//-------------------------------------------------
function move_pm($privmsg_recip_ids, $view_user_id, $from_folder, $to_folder)
{
    global $db;
    global $folders;

    // no data
    if ( empty($privmsg_recip_ids) )
    {
        return 'No_post_id';
    }

    // not an array : do one
    if ( !is_array($privmsg_recip_ids) )
    {
        $privmsg_recip_ids = array( intval($privmsg_recip_ids) );
    }
    $s_privmsg_recip_ids = implode(', ', $privmsg_recip_ids);

    // no change : exit
    if ( ($from_folder == $to_folder) && !empty($from_folder) && !empty($to_folder) )
    {
        return 'No_such_folder';
    }

    // get the main folders
    $from_main = $from_folder;
    if ( !empty($folders['main'][$from_folder]) )
    {
        $from_main = $folders['main'][$from_folder];
    }
    $to_main = $to_folder;
    if ( !empty($folders['main'][$to_folder]) )
    {
        $to_main = $folders['main'][$to_folder];
    }

    //------------------------
    // no duplication required : proceed
    //------------------------
    if ( $from_main == $to_main )
    {
        $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                    SET privmsg_folder_id = $to_folder
                    WHERE privmsg_recip_id IN ($s_privmsg_recip_ids)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update recipients', '', __LINE__, __FILE__, $sql);
        }

        return '';
    }

    //------------------------
    // from living one to save
    //------------------------
    if ( $to_main != SAVEBOX )
    {
        return 'No_such_folder';
    }

    // get the messages
    $sql = "SELECT p.*, pa.*, pr.privmsg_recip_id AS selected_pm_id
                FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pa
                WHERE p.privmsg_id = pr.privmsg_id
                    AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
                    AND pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
                    AND pr.privmsg_status <> " . STS_DELETED;
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read messages', '', __LINE__, __FILE__, $sql);
    }
    $privmsgs = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $privmsgs[] = $row;
    }

    // copy them
    $q = "'";
    for ( $i = 0; $i < count($privmsgs); $i++ )
    {
        $privmsg = &$privmsgs[$i];
        $privmsg_id = $privmsg['privmsg_id'];

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
        $fields_recip['privmsg_read']       = READ_MAIL;
        $fields_recip['privmsg_folder_id']  = SAVEBOX;
        $fields_recip['privmsg_distrib']    = 1;

        // generate the author info
        $fields_recip['privmsg_status'] = STS_DELETED;
        if ( $privmsg['privmsg_recip_id'] == $privmsg['selected_pm_id'] )
        {
            $fields_recip['privmsg_status'] = STS_SAVED;
        }

        _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update);
        $sql = "INSERT $sql_priority
                    INTO " . PRIVMSGA_RECIPS_TABLE . "
                    ($sql_fields)
                    VALUES($sql_values)";
        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not generate a copy of original pm author', '', __LINE__, __FILE__, $sql);
        }

        // recipients
        $sql = "SELECT pr.*
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr
                    WHERE pr.privmsg_id = $privmsg_id
                        AND pr.privmsg_direct = 1
                        AND pr.privmsg_status <> " . STS_DELETED;
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read original recipients', '', __LINE__, __FILE__, $sql);
        }
        $recips = array();
        while ( $row = $db->sql_fetchrow($result) )
        {
            $recips[] = $row;
        }

        // recipients
        $fields_recip = array();
        $fields_recip['privmsg_id']         = $privmsg_copy_id;
        $fields_recip['privmsg_direct']     = 1;
        $fields_recip['privmsg_user_id']    = 0;
        $fields_recip['privmsg_ip']         = '';
        $fields_recip['privmsg_read']       = READ_MAIL;
        $fields_recip['privmsg_folder_id']  = SAVEBOX;
        $fields_recip['privmsg_distrib']    = 1;

        for ( $j = 0; $j < count($recips); $j++ )
        {
            // generate the recipient info
            $fields_recip['privmsg_status'] = STS_DELETED;
            if ( $recips[$j]['privmsg_recip_id'] == $privmsg['selected_pm_id'] )
            {
                $fields_recip['privmsg_status'] = STS_SAVED;
            }
            $fields_recip['privmsg_user_id'] = intval($recips[$j]['privmsg_user_id']);
            $fields_recip['privmsg_ip'] = $q . $recips[$j]['privmsg_ip'] .$q;

            _sql_statements($fields_recip, $sql_fields, $sql_values, $sql_update);
            $sql = "INSERT $sql_priority
                        INTO " . PRIVMSGA_RECIPS_TABLE . "
                        ($sql_fields)
                        VALUES($sql_values)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not generate a copy of original pm recipients', '', __LINE__, __FILE__, $sql);
            }
        }
    }

    // update the original record as deleted
    $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                SET privmsg_status = " . STS_DELETED . "
                WHERE privmsg_recip_id IN ($s_privmsg_recip_ids)
                    AND privmsg_status <> " . STS_DELETED;
    if ( !$db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not mark as deleted original messages', '', __LINE__, __FILE__, $sql);
    }

    // resync user's counts
    resync_pm($view_user_id);
}


//-------------------------------------------------
//
//  adjustbox_pm() : mark as deleted on overflow and prune messages entirely marked
//
//-------------------------------------------------
function adjustbox_pm($user_id)
{
    global $db;
    global $folders;
    global $s_unread;
    global $cfg_max_inbox, $cfg_max_outbox, $cfg_max_sentbox, $cfg_max_savebox;

    //----------------------------
    // check if boxes are overflowed
    //----------------------------
    $counts = array();

    // count per direction and type
    $sql = "SELECT privmsg_direct, privmsg_status, privmsg_read, COUNT(privmsg_recip_id) AS count_recip
                FROM " . PRIVMSGA_RECIPS_TABLE . "
                WHERE privmsg_status <> " . STS_DELETED . "
                    AND privmsg_user_id = $user_id
                GROUP BY privmsg_direct, privmsg_status, privmsg_read";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could count received messages', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $box = INBOX;
        if ( $row['privmsg_status'] == STS_SAVED )
        {
            $box = SAVEBOX;
        }
        else if ( $row['privmsg_direct'] == 0 )
        {
            $box = OUTBOX;
            if ( $row['privmsg_read'] == READ_MAIL )
            {
                $box = SENTBOX;
            }
        }
        $counts[$box] += $row['count_recip'];
    }

    // process the recip to mark as deleted
    $s_deleted = '';
    @reset($counts);
    while ( list($box, $count_recip) = @each($counts) )
    {
        $overflow = 0;
        $sql_where = '';
        switch ( $box )
        {
            case INBOX:
                if ( !empty($cfg_max_inbox) )
                {
                    $overflow = $count_recip - $cfg_max_inbox;
                    $sql_where = "pr.privmsg_direct = 1 AND pr.privmsg_status = " . STS_TRANSIT;
                }
                break;
            case OUTBOX:
                if ( !empty($cfg_max_outbox) )
                {
                    $overflow = $count_recip - $cfg_max_outbox;
                    $sql_where = "pr.privmsg_direct = 0 AND pr.privmsg_read IN ($s_unread) AND pr.privmsg_status = " . STS_TRANSIT;
                }
                break;
            case SENTBOX:
                if ( !empty($cfg_max_sentbox) )
                {
                    $overflow = $count_recip - $cfg_max_sentbox;
                    $sql_where = "pr.privmsg_direct = 0 AND pr.privmsg_read = " . READ_MAIL . " AND pr.privmsg_status = " . STS_TRANSIT;
                }
                break;
            case SAVEBOX:
                if ( !empty($cfg_max_savebox) )
                {
                    $overflow = $count_recip - $cfg_max_savebox;
                    $sql_where = "pr.privmsg_status = " . STS_SAVED;
                }
                break;
        }
        if ( $overflow > 0 )
        {
            // get the recip ids of the $overflow older messages to delete
            $sql = "SELECT pr.privmsg_recip_id
                        FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p
                        WHERE p.privmsg_id = pr.privmsg_id
                            AND pr.privmsg_user_id = $user_id
                            AND $sql_where
                        ORDER BY p.privmsg_time
                        LIMIT 0, $overflow";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could count messages to mark as deleted', '', __LINE__, __FILE__, $sql);
            }
            while ( $row = $db->sql_fetchrow($result) )
            {
                $s_deleted .= ( empty($s_deleted) ? '' : ', ' ) . $row['privmsg_recip_id'];
            }
        }
    }

    // mark recip to delete
    if ( !empty($s_deleted) )
    {
        $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                    SET privmsg_status = " . STS_DELETED . "
                    WHERE privmsg_recip_id IN ($s_deleted)";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could mark messages as deleted', '', __LINE__, __FILE__, $sql);
        }
    }

    // check entirely marked as deleted messages
    $sql = "SELECT pr.privmsg_id
                FROM (" . PRIVMSGA_RECIPS_TABLE . " pr
                    LEFT JOIN " . PRIVMSGA_RECIPS_TABLE . " pa
                        ON pa.privmsg_id = pr.privmsg_id AND pa.privmsg_status <> " . STS_DELETED . ")
                WHERE pr.privmsg_user_id = $user_id AND pr.privmsg_direct = 0
                    AND pr.privmsg_status = " . STS_DELETED . "
                    AND pa.privmsg_id IS NULL";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read entirely marked as deleted messages', '', __LINE__, __FILE__, $sql);
    }
    $s_deleted = '';
    while ( $row = $db->sql_fetchrow($result) )
    {
        $s_deleted .= ( empty($s_deleted) ? '' : ', ' ) . $row['privmsg_id'];
    }
    if ( !empty($s_deleted) )
    {
        // recipients
        $sql = "DELETE FROM " . PRIVMSGA_RECIPS_TABLE . " WHERE privmsg_id IN ($s_deleted)";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not deleted messages recips', '', __LINE__, __FILE__, $sql);
        }

        // message
        $sql = "DELETE FROM " . PRIVMSGA_TABLE . " WHERE privmsg_id IN ($s_deleted)";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not deleted messages', '', __LINE__, __FILE__, $sql);
        }
    }

    // resync user's counts
    resync_pm($user_id);
}

//-------------------------------------------------
//
//  distribute_pm() : apply distribution rules to the messages
//
//-------------------------------------------------
function distribute_pm($user_id, $privmsg_recip_ids=array())
{
    global $db;
    global $folders;

    // fix the privmsg_recip_ids
    if ( !is_array($privmsg_recip_ids) && !empty($privmsg_recip_ids) )
    {
        $privmsg_recip_ids = array(intval($privmsg_recip_ids));
    }
    $s_recips_ids = empty($privmsg_recip_ids) ? '' : implode(', ', $privmsg_recip_ids);

    // first do some cleaning job
    adjustbox_pm($user_id);

    // then get the ids of the non-processed recips
    $s_privmsg_recip_ids = '';
    $s_privmsg_ids = '';
    $sql_where = "privmsg_distrib = 0";
    if ( !empty($s_recip_ids) )
    {
        $sql_where = "( privmsg_distrib = 0 OR privmsg_recip_id IN ($s_recip_ids) )";
    }
    $sql = "SELECT privmsg_id, privmsg_recip_id
                FROM " . PRIVMSGA_RECIPS_TABLE . "
                WHERE privmsg_user_id = $user_id
                    AND $sql_where";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not get un-distrib recips', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $s_privmsg_ids .= ( empty($s_privmsg_ids) ? '' : ', ' ) . $row['privmsg_id'];
        $s_privmsg_recip_ids .= ( empty($s_privmsg_recip_ids) ? '' : ', ' ) . $row['privmsg_recip_id'];
    }

    // nothing to process
    if ( empty($s_privmsg_recip_ids) )
    {
        return;
    }

    $folders_list[INBOX] = empty($folders['sub'][INBOX]) ? INBOX : INBOX . ', ' . implode(', ', $folders['sub'][INBOX]);
    $folders_list[OUTBOX] = empty($folders['sub'][OUTBOX]) ? OUTBOX : OUTBOX . ', ' . implode(', ', $folders['sub'][OUTBOX]);
    $folders_list[SENTBOX] = empty($folders['sub'][SENTBOX]) ? SENTBOX : SENTBOX . ', ' . implode(', ', $folders['sub'][SENTBOX]);
    $folders_list[SAVEBOX] = empty($folders['sub'][SAVEBOX]) ? SAVEBOX : SAVEBOX . ', ' . implode(', ', $folders['sub'][SAVEBOX]);

    // rules
    $error = false;
    $error_msg = '';

    // system rules
    $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                SET privmsg_folder_id = " . SENTBOX . "
                WHERE privmsg_recip_id IN ($s_privmsg_recip_ids)
                    AND privmsg_direct = 0
                    AND privmsg_folder_id IN (" . $folders_list[OUTBOX] . ")
                    AND privmsg_read = " . READ_MAIL . "
                    AND privmsg_status = " . STS_TRANSIT;
    if ( !$db->sql_query($sql) )
    {
        _error('Sent_box_feeding_failed');
    }

    // read custom rules
    $sql = "SELECT *
                FROM " . PRIVMSGA_RULES_TABLE . "
                WHERE rules_user_id = $user_id
                ORDER BY rules_name";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read messages rules', '', __LINE__, __FILE__, $sql);
    }
    $words = array();
    $users = array();
    $groups = array();
    $friends = array();
    $frules = array();

    while ( $row = $db->sql_fetchrow($result) )
    {
        $folder_id = $row['rules_folder_id'];

        // own code: init all sub arrays!
        if ( empty($groups[$folder_id]) )
        {
            $groups[$folder_id] = array();
        }

        if ( empty($users[$folder_id]) )
        {
            $users[$folder_id] = array();
        }

        if ( !empty($words[$folder_id]) )
        {
            $words[$folder_id] = array();
        }

        if ( empty($frules) || !in_array($folder_id, $frules) )
        {
            $frules[] = $folder_id;
        }
        if ( !empty($row['rules_word']) )
        {
            $word_list = explode(',', $row['rules_word']);
            for ( $i = 0; $i < count($word_list); $i++ )
            {
                $words[$folder_id][] = str_replace("\'", "''", trim($word_list[$i]));
            }
        }
        else if ( empty($row['rules_group_id']) )
        {
            $users[$folder_id][] = 0;
        }
        else if ( $row['rules_group_id'] == FRIEND_LIST_GROUP )
        {
            $friends[$folder_id] = true;
        }
        else
        {
            $groups[$folder_id][] = $row['rules_group_id'];
        }
    }

    // build sql requests for custom rules
    for ( $i = 0; $i < count($frules); $i++ )
    {
        // choose the good list of folders
        $folder_id = $frules[$i];
        $folder_main = $folders['main'][$folder_id];

        // resulting privmsg_id
        $s_privmsg_recip_id_res = '';

        if ( !empty($words[$folder_id]) )
        {
            // prepare the request
            $sql = "SELECT pr.privmsg_recip_id
                        FROM " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pr
                        WHERE p.privmsg_id = pr.privmsg_id
                            AND pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
                            AND pr.privmsg_folder_id IN (" . $folders_list[$folder_main] . ")";

            // words filter
            $words_subject = '';
            $words_text = '';
            for ( $k = 0; $k < count($words[ $frules[$i] ]); $k++ )
            {
                $words_subject .= ( empty($words_subject) ? '' : ' OR ') . "p.privmsg_subject LIKE '%" . $words[ $frules[$i] ][$k] . "%'";
                $words_text .= ( empty($words_text) ? '' : ' OR ') . "p.privmsg_text LIKE '%" . $words[ $frules[$i] ][$k] . "%'";
            }

            // process the request
            $sql .= " AND ($words_subject) AND ($words_text)";
            if ( !$result = $db->sql_query($sql) )
            {
                _error('Word_search_failed');
            }
            else
            {
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $s_privmsg_recip_id_res .= ( empty($s_privmsg_recip_id_res) ? '' : ', ' ) . $row['privmsg_recip_id'];
                }
            }
        }

        // groups/users
        if ( !empty($groups[$folder_id]) )
        {
            $s_groups = implode(', ', $groups[$folder_id]);
            $sql = "SELECT pr.privmsg_recip_id
                        FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USER_GROUP_TABLE . " ug
                        WHERE pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
                            AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct <> pr.privmsg_direct
                            AND pr.privmsg_folder_id IN (" . $folders_list[$folder_main] . ")
                            AND ug.user_id=pa.privmsg_user_id AND ug.group_id IN ($s_groups)";
            // process the request
            if ( !$result = $db->sql_query($sql) )
            {
                _error('Group_search_failed');
            }
            else
            {
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $s_privmsg_recip_id_res .= ( empty($s_privmsg_recip_id_res) ? '' : ', ' ) . $row['privmsg_recip_id'];
                }
            }
        }

        // friend list
        if ( $friends[$folder_id] && defined('IN_PCP') )
        {
            $sql = "SELECT pr.privmsg_recip_id
                        FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa, " . BUDDYS_TABLE . " b
                        WHERE pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
                            AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct <> pr.privmsg_direct
                            AND pr.privmsg_folder_id IN (" . $folders_list[$folder_main] . ")
                            AND b.user_id = $view_user_id AND b.buddy_id = pa.privmsg_user_id AND b.buddy_ignore = 0";
            // process the request
            if ( !$result = $db->sql_query($sql) )
            {
                _error('Friends_search_failed');
            }
            else
            {
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $s_privmsg_recip_id_res .= ( empty($s_privmsg_recip_id_res) ? '' : ', ' ) . $row['privmsg_recip_id'];
                }
            }
        }

        // system messages
        if ( !empty($users[$folder_id]) )
        {
            $sql = "SELECT pr.privmsg_recip_id
                        FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa
                        WHERE pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
                            AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct <> pr.privmsg_direct
                            AND pr.privmsg_folder_id IN (" . $folders_list[$folder_main] . ")
                            AND pa.privmsg_user_id = 0";
            // process the request
            if ( !$result = $db->sql_query($sql) )
            {
                _error('Sysuser_search_failed');
            }
            else
            {
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $s_privmsg_recip_id_res .= ( empty($s_privmsg_recip_id_res) ? '' : ', ' ) . $row['privmsg_recip_id'];
                }
            }
        }

        // move the recip
        $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                    SET privmsg_folder_id = $folder_id,
                        privmsg_distrib = 1
                    WHERE privmsg_recip_id IN ($s_privmsg_recip_id_res)";
        if ( !$db->sql_query($sql) )
        {
            _error('Distribution_failed');
        }
    }

    // add the final rule
    $sql = "UPDATE " . PRIVMSGA_RECIPS_TABLE . "
                SET privmsg_distrib = 1
                WHERE privmsg_recip_id IN ($s_privmsg_recip_ids)
                    AND privmsg_distrib = 0";
    if ( !$db->sql_query($sql) )
    {
        _error('Remove_undistrib_flag_failed');
    }

    if ( $error )
    {
        message_die(GENERAL_ERROR, $error_msg, _lang('Distribution_failed'));
    }

    // adjust the user's counts
    resync_pm($view_user_id);
}

?>