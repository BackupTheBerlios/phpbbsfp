<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_folders.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsga_folders.php
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
define('IN_FOLDERS', true);

//--------------------------
//
//  get parameters
//
//--------------------------
_hidden_init();

// vars
$folder_id      = _read_var('folder', 1, INBOX);
$rules_id       = _read_var('rules_id', 1, 0, 'rule');

// buttons
$confirm        = _button_var('confirm');
$cancel         = _button_var('cancel');
$refresh        = _button_var('refresh');

$add_folder     = _button_var('add_folder');
$submit_folder  = _button_var('submit_folder');
$delete_folder  = _button_var('delete_folder');
$cancel_folder  = _button_var('cancel_folder');
$return_folder  = _button_var('return_folder');

$add_rules      = _button_var('add_rules');
$submit_rules   = _button_var('submit_rules');
$delete_rules   = _button_var('delete_rules');
$cancel_rules   = _button_var('cancel_rules');


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

// adjust the pmmode
if ( $add_folder )
{
    $pmmode = 'fcreate';
}
if ( $delete_folder )
{
    _hide('return_folder', true);
    $pmmode = 'fdelete';
}
if ( $cancel_folder )
{
    $pmmode = 'flist';
}
if ( $add_rules )
{
    $pmmode = 'rcreate';
}
if ( $delete_rules )
{
    $pmmode = 'rdelete';
}
if ( $cancel_rules )
{
    $pmmode = 'rlist';
}
if ( !in_array($pmmode, array('flist', 'fcreate', 'fedit', 'fdelete', 'fmoveup', 'fmovedw', 'rlist', 'rcreate', 'redit', 'rdelete')) )
{
    $pmmode = '';
}

//---------------------------------
//
//  rules read
//
//---------------------------------
$rules = array();
if ( !empty($folder_id) )
{
    $sql = "SELECT *
                FROM " . PRIVMSGA_RULES_TABLE . "
                WHERE rules_user_id = $view_user_id
                    AND rules_folder_id = $folder_id
                ORDER BY rules_name";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not read rules', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $rules[ $row['rules_id'] ] = $row;
    }
}

// verify rules_id
if ( !isset($rules[$rules_id]) )
{
    $rules_id = 0;
}

//---------------------------------
//
//  folder management
//
//---------------------------------
if ( in_array($pmmode, array('fcreate', 'fedit', 'fdelete', 'fmoveup', 'fmovedw')) )
{
    $error = false;
    $error_msg = '';

    // get data from table
    $folder_name = isset($folders['data'][$folder_id]) ? $folders['data'][$folder_id]['folder_name'] : '';
    $folder_main = isset($folders['data'][$folder_id]) ? $folders['data'][$folder_id]['folder_main'] : $folder_main;
    if ( $pmmode == 'fcreate' )
    {
        $folder_name = '';
    }

    $folder_main = _read_var('folder_main', 1, $folder_main);
    $folder_name = unprepare_message(trim(str_replace("\'", "''", htmlspecialchars(_read_var('folder_name', 0, $folder_name)))));
}

// process
if ( in_array($pmmode, array('fmoveup', 'fmovedw')) )
{
    if ( $pmmode == 'fmoveup' )
    {
        $sql = "UPDATE " . PRIVMSGA_FOLDERS_TABLE . "
                    SET folder_order = folder_order - 15
                    WHERE folder_id = $folder_id AND folder_main <> 0";
    }
    else
    {
        $sql = "UPDATE " . PRIVMSGA_FOLDERS_TABLE . "
                    SET folder_order = folder_order + 15
                    WHERE folder_id = $folder_id AND folder_main <> 0";
    }
    if ( !$db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not update folder order', '', __LINE__, __FILE__, $sql);
    }
    renum_folders($view_user_id);
    $folders = get_user_folders($view_user_id);
    $pmmode = 'flist';
}

if ( $pmmode == 'fdelete' )
{
    if ( $cancel )
    {
        $pmmode = $return_folder ? 'fedit' : 'flist';
        $delete_folder = false;
        $cancel = false;
    }
    else if ( $confirm )
    {
        // check if no messages in it
        $sql = "SELECT *
                    FROM " . PRIVMSGA_RECIPS_TABLE . "
                    WHERE privmsg_folder_id = $folder_id
                        AND privmsg_status <> " . STS_DELETED;
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read pms in folder', '', __LINE__, __FILE__, $sql);
        }
        $messages_left = ( $db->sql_numrows($result) > 0 );
        $sql = "SELECT *
                    FROM " . PRIVMSGA_RULES_TABLE . "
                    WHERE rules_folder_id = $folder_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read folder rules', '', __LINE__, __FILE__, $sql);
        }
        $rules_left = ( $db->sql_numrows($result) > 0 );
        if ( $messages_left || $rules_left )
        {
            _error( 'Folder_not_empty' );
        }
        if ( $error )
        {
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=flist&folder=$folder_id");
            _message_return($error_msg, $l_link, $u_link);
        }
        if ( !$error )
        {
            $sql = "DELETE FROM " . PRIVMSGA_FOLDERS_TABLE . "
                        WHERE folder_id = $folder_id
                            AND folder_user_id = $view_user_id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete folder', '', __LINE__, __FILE__, $sql);
            }
        }

        // return message
        renum_folders($view_user_id);
        $return_msg = 'Folder_deleted';
        $l_link = 'Click_return_folders';
        $u_link = append_sid("$main_pgm&pmmode=flist&folder=$folder_main");
        _message_return($return_msg, $l_link, $u_link);
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Folders_management');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }

        // template name
        $template->set_filenames(array(
            'body' => 'confirm_body.tpl')
        );

        $template->assign_vars(array(
            'MESSAGE_TITLE' => _lang('Delete_folder'),
            'MESSAGE_TEXT'  => _lang('Confirm_delete_folder'),

            'L_YES'         => _lang('Yes'),
            'L_NO'          => _lang('Cancel'),
            )
        );

        // system
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

if ( in_array($pmmode, array('fcreate', 'fedit')) )
{
    if ( $cancel_folder )
    {
        $pmmode = 'flist';
        $cancel_folder = false;
    }
    else if ( $submit_folder )
    {
        // main folder missing
        if ( empty($folder_main) )
        {
            _error('Folder_not_attached');
        }

        // folder name missing
        if ( empty($folder_name) )
        {
            _error('Folder_name_missing');
        }

        // check if no messages in it
        if ( $pmmode == 'fedit' )
        {
            $sql = "SELECT *
                        FROM " . PRIVMSGA_RECIPS_TABLE . "
                        WHERE privmsg_folder_id = $folder_id
                            AND privmsg_status <> " . STS_DELETED;
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not read pms in folder', '', __LINE__, __FILE__, $sql);
            }
            if ( $db->sql_numrows($result) > 0 )
            {
                _error( 'Folder_not_empty' );
            }
        }
        if ( $error )
        {
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=flist&folder=$folder_id&pmmode=$pmmode");
            _message_return($error_msg, $l_link, $u_link);
        }
        if ( !$error )
        {
            // sql building
            $fields = array();
            $fields['folder_main'] = intval($folder_main);
            $fields['folder_name'] = "'" . htmlspecialchars($folder_name) . "'";
            $fields['folder_user_id'] = intval($view_user_id);
            $sql_fields = '';
            $sql_values = '';
            $sql_update = '';

            // process
            if ( ($pmmode == 'fcreate') || empty($folder_id) )
            {
                $lats_order = 0;
                $count = 0;
                for ( $i = 0; $i < count($folders['folder'][$folder_main]); $i++ )
                {
                    $id = $folders['sub'][$folder_main][$i];
                    if ( $folders['data'][$id]['folder_order'] > $last_order )
                    {
                        $last_order = $folders['data'][$id]['folder_order'];
                        $count++;
                    }
                }
                $fields['folder_order'] = $last_order + 10;
                _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
                $sql = "INSERT INTO " . PRIVMSGA_FOLDERS_TABLE . "
                            ($sql_fields)
                            VALUES ($sql_values)";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not create a new folder', '', __LINE__, __FILE__, $sql);
                }
                $folder_id = $db->sql_nextid();

                // return message
                renum_folders($view_user_id);
                $return_msg = 'Folder_created';
                $l_link = 'Click_return_folders';
                $u_link = append_sid("$main_pgm&pmmode=flist&folder=$folder_id");
                _message_return($return_msg, $l_link, $u_link);
            }
            else if ( $pmmode == 'fedit' )
            {
                _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
                $sql = "UPDATE " . PRIVMSGA_FOLDERS_TABLE . "
                            SET $sql_update
                            WHERE folder_id = $folder_id";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not update folder', '', __LINE__, __FILE__, $sql);
                }

                // return message
                renum_folders($view_user_id);
                $return_msg = 'Folder_edited';
                $l_link = 'Click_return_folders';
                $u_link = append_sid("$main_pgm&pmmode=flist&folder=$folder_id");
                _message_return($return_msg, $l_link, $u_link);
            }
        }
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Folders_management');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }

        // template name
        $template->set_filenames(array(
            'body' => 'privmsga_folders_edit_body.tpl')
        );

        // send header
        privmsg_header($view_user_id, $folder_id);

        // Header
        $template->assign_vars(array(
            'L_TITLE'           => _lang('Folders_management'),

            'L_NAME'            => _lang('Folder_name'),
            'L_NAME_EXPLAIN'    => _lang('Folder_name_explain'),
            'L_MAIN'            => _lang('Folder_main'),
            'L_MAIN_EXPLAIN'    => _lang('Folder_main_explain'),

            'L_SUBMIT'          => _lang('Submit'),
            'L_EDIT'            => _lang('Edit'),
            'L_DELETE'          => _lang('Delete'),
            'L_CANCEL'          => _lang('Cancel'),
            )
        );

        // get main folders
        $s_folders = '';
        @reset($folders['data']);
        while ( list($id, $data) = @each($folders['data']) )
        {
            if ( empty($folders['main'][$id]) )
            {
                $selected = ($id == $folder_main) ? ' selected="selected"' : '';
                $s_folders .= '<option value="' . $id . '"' . $selected . '>' . _lang($folders['data'][$id]['folder_name']) . '</option>';
            }
        }

        // data
        $template->assign_vars(array(
            'FOLDER_NAME'   => $folder_name,
            'S_FOLDERS'     => $s_folders,
            )
        );

        // system
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);
        _hide(POST_USERS_URL, $view_user_id);
        _hide('sid', $userdata['session_id']);

        $template->assign_vars(array(
            'S_ACTION'          => append_sid($main_pgm),
            'S_HIDDEN_FIELDS'   => _hidden_get(),
            )
        );

        // send to browser
        privmsg_footer();
        $template->pparse('body');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
        }
    }
}

//---------------------------------
//
//  rules management
//
//---------------------------------
if ( in_array($pmmode, array('rcreate', 'redit', 'rdelete')) )
{
    // init errors
    $error = false;
    $error_msg = '';

    // get data from table
    $rules_folder_id = isset($rules[$rules_id]) ? $rules[$rules_id]['rules_folder_id'] : $folder_id;
    $rules_name = isset($rules[$rules_id]) ? $rules[$rules_id]['rules_name'] : '';
    $rules_group_id = isset($rules[$rules_id]) ? intval($rules[$rules_id]['rules_group_id']) : 0;
    $rules_word = isset($rules[$rules_id]) ? $rules[$rules_id]['rules_word'] : '';

    // check if the group_id is an individual user one
    $rules_username = '';
    if ( isset($rules[$rules_id]) && !empty($rules_group_id) )
    {
        $sql = "SELECT *
                    FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
                    WHERE ug.group_id = $rules_group_id
                        AND g.group_id = ug.group_id
                        AND u.user_id = ug.user_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could read group', '', __LINE__, __FILE__, $sql);
        }
        if ( $row = $db->sql_fetchrow($result) )
        {
            if ( intval($row['group_single_user']) == 1 )
            {
                $rules_group_id = 0;
                $rules_username = $row['username'];
            }
        }
    }

    // get rules_type
    $rules_type = 1;
    if ( isset($rules[$rules_id]) )
    {
        if ( !empty($rules_group_id) )
        {
            $rules_type = 1;
        }
        else if ( !empty($rules_username) )
        {
            $rules_type = 2;
        }
        else if ( !empty($rules_word) )
        {
            $rules_type = 4;
        }
        else
        {
            $rules_type = 3;
        }
    }

    // get data from form
    $rules_folder_id    = _read_var('rules_folder_id', 1, $rules_folder_id);
    $rules_name         = unprepare_message(trim(str_replace("\'", "''", htmlspecialchars(_read_var('rules_name', 0, $rules_name)))));
    $rules_type         = _read_var('rules_type', 1, $rules_type);
    $rules_group_id     = _read_var('rules_group_id', 1, $rules_group_id);
    $rules_username     = unprepare_message(trim(str_replace("\'", "''", htmlspecialchars(_read_var('username', 0, $rules_username)))));
    $rules_sysuser      = _read_var('rules_sysuser', 1, $rules_sysuser);
    $rules_word         = unprepare_message(trim(str_replace("\'", "''", htmlspecialchars(_read_var('rules_word', 0, $rules_word)))));
}

if ( $pmmode == 'rdelete' )
{
    if ( $cancel )
    {
        $pmmode = 'redit';
        $cancel = false;
    }
    else if ( $confirm )
    {
        if ( $error )
        {
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=rlist&folder=$folder_id");
            _message_return($error_msg, $l_link, $u_link);
        }
        if ( !$error )
        {
            $sql = "DELETE FROM " . PRIVMSGA_RULES_TABLE . "
                        WHERE rules_id = $rules_id
                            AND rules_user_id = $view_user_id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete rule', '', __LINE__, __FILE__, $sql);
            }
        }

        // return message
        $return_msg = 'Rules_deleted';
        $l_link = 'Click_return_folders';
        $u_link = append_sid("$main_pgm&pmmode=rlist&folder=$folder_id");
        _message_return($return_msg, $l_link, $u_link);
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Rules_management');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }

        // template name
        $template->set_filenames(array(
            'body' => 'confirm_body.tpl')
        );

        $template->assign_vars(array(
            'MESSAGE_TITLE' => _lang('Delete_rule'),
            'MESSAGE_TEXT'  => _lang('Confirm_delete_rule'),

            'L_YES'         => _lang('Yes'),
            'L_NO'          => _lang('Cancel'),
            )
        );

        // system
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);
        _hide('rules_id', $rules_id);
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

if ( in_array($pmmode, array('rcreate', 'redit')) )
{
    if ( $cancel_rules )
    {
        $pmmode = 'rlist';
        $cancel_rules = false;
    }
    else if ( $submit_rules )
    {
        // do some checks
        if ( empty($rules_name) )
        {
            _error('Rules_name_missing');
        }
        if ( empty($rules_folder_id) )
        {
            _error('Rules_folder_missing');
        }
        if ( empty($folders['data'][$rules_folder_id]) )
        {
            _error('Rules_folder_not_exists');
        }
        if ( empty($folders['main'][$rules_folder_id]) )
        {
            _error('Rules_folder_main');
        }
        switch ( $rules_type )
        {
            case 1:
                if ($rules_group_id != FRIEND_LIST_GROUP)
                {
                    $sql = "SELECT *
                            FROM " . GROUPS_TABLE . "
                            WHERE group_id = $rules_group_id
                                AND group_single_user = 0";
                    if ( !$result = $db->sql_query($sql) )
                    {
                        message_die(GENERAL_ERROR, 'Could read group', '', __LINE__, __FILE__, $sql);
                    }
                    if ( $db->sql_numrows($result) <= 0 )
                    {
                        _error('Group_not_exist');
                    }
                }
                break;
            case 2:
                $sql = "SELECT g.group_id
                            FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
                            WHERE ug.user_id = u.user_id
                                AND g.group_id = ug.group_id
                                AND g.group_single_user = 1
                                AND u.username = '" . htmlspecialchars($rules_username) . "'";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could read individual usergroup', '', __LINE__, __FILE__, $sql);
                }
                if ( !$row = $db->sql_fetchrow($result) )
                {
                    _error('User_not_exist');
                }
                else
                {
                    $rules_group_id = intval($row['group_id']);
                }
                break;
            case 3:
                if ( !in_array($folders['main'][$rules_folder_id], array(INBOX, SAVEBOX)) )
                {
                    _error('Rules_sysuser_input');
                }
                break;
            case 4:
                if ( empty($rules_word) )
                {
                    _error('Rules_word_missing');
                }
                break;
            default:
                _error('Rules_type_unknown');
                break;
        }
        if ( $error )
        {
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=rlist&folder=$folder_id");
            _message_return($error_msg, $l_link, $u_link);
        }

        // update
        $fields = array();
        $fields['rules_user_id']    = $view_user_id;
        $fields['rules_folder_id']  = $rules_folder_id;
        $fields['rules_name']       = "'" . htmlspecialchars($rules_name) . "'";
        $fields['rules_group_id']   = $rules_group_id;
        $fields['rules_word']       = "'" . htmlspecialchars($rules_word) . "'";
        $sql_fields = '';
        $sql_values = '';
        $sql_update = '';
        _sql_statements($fields, $sql_fields, $sql_values, $sql_update);
        if ( empty($rules_id) || ($pmmode == 'rcreate') )
        {
            $sql = "INSERT INTO " . PRIVMSGA_RULES_TABLE . "
                        ($sql_fields)
                        VALUES($sql_values)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could create a new rule', '', __LINE__, __FILE__, $sql);
            }

            // return message
            $return_msg = 'Rules_created';
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=rlist&folder=$rules_folder_id");
            _message_return($return_msg, $l_link, $u_link);
        }
        else
        {
            $sql = "UPDATE " . PRIVMSGA_RULES_TABLE . "
                        SET $sql_update
                        WHERE rules_id = $rules_id
                            AND rules_user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could update the rule', '', __LINE__, __FILE__, $sql);
            }

            // return message
            $return_msg = 'Rules_edited';
            $l_link = 'Click_return_folders';
            $u_link = append_sid("$main_pgm&pmmode=rlist&folder=$rules_folder_id");
            _message_return($return_msg, $l_link, $u_link);
        }
    }
    else
    {
        // set the page title and include the page header
        $page_title = _lang('Rules_management');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_header.' . $phpEx);
        }

        // template name
        $template->set_filenames(array(
            'body' => 'privmsga_rules_edit_body.tpl')
        );

        // send header
        privmsg_header($view_user_id, $folder_id);

        // Header
        $template->assign_vars(array(
            'L_TITLE'               => _lang('Rules_management'),

            'L_NAME'                => _lang('Rules_name'),
            'L_NAME_EXPLAIN'        => _lang('Rules_name_explain'),
            'L_RFOLDER'             => _lang('Folder'),
            'L_RFOLDER_EXPLAIN'     => _lang('Rules_folder_explain'),
            'L_GROUP'               => _lang('Group'),
            'L_GROUP_EXPLAIN'       => _lang('Rules_group_explain'),
            'L_USERNAME'            => _lang('Username'),
            'L_USERNAME_EXPLAIN'    => _lang('Rules_user_explain'),
            'L_WORD'                => _lang('Rules_word'),
            'L_WORD_EXPLAIN'        => _lang('Rules_word_explain'),

            'L_RULE_TYPE'           => _lang('Rules_type'),
            'L_RULE_TYPE_EXPLAIN'   => _lang('Rules_type_explain'),
            'L_RULE_TYPE_GROUP'     => _lang('Rules_type_group'),
            'L_RULE_TYPE_USER'      => _lang('Rules_type_user'),
            'L_RULE_TYPE_SYSUSER'   => _lang('Rules_type_sysuser'),
            'L_SYSUSER_EXPLAIN'     => _lang('Rules_sysuser_explain'),
            'L_RULE_TYPE_WORD'      => _lang('Rules_type_word'),

            'L_SUBMIT'              => _lang('Submit'),
            'L_EDIT'                => _lang('Edit'),
            'L_DELETE'              => _lang('Delete'),
            'L_CANCEL'              => _lang('Cancel'),

            'L_FIND_USERNAME'       => _lang('Find_username'),
            'U_SEARCH_USER'         => append_sid("./search.$phpEx?mode=searchuser"),
            )
        );

        // folders list
        $s_folders = get_folders_list(0, $rules_folder_id);

        // groups list
        $s_groups = get_groups_list($view_userdata, $rules_group_id);
        $s_groups = '<option value="">' . _lang('Select_group') . '</option><option value="">' . str_repeat('-', strlen(_lang('Select_group'))+5) . '</option>' . $s_groups;

        // data
        $template->assign_vars(array(
            'RULES_NAME'            => $rules_name,
            'S_FOLDERS'             => $s_folders,
            'RULES_TYPE_GROUP'      => ($rules_type == 1) ? 'checked="checked"' : '',
            'RULES_TYPE_USER'       => ($rules_type == 2) ? 'checked="checked"' : '',
            'RULES_TYPE_SYSUSER'    => ($rules_type == 3) ? 'checked="checked"' : '',
            'RULES_TYPE_WORD'       => ($rules_type == 4) ? 'checked="checked"' : '',
            'GROUP_DISPLAY'         => ($rules_type == 1) ? '' : 'none',
            'USER_DISPLAY'          => ($rules_type == 2) ? '' : 'none',
            'SYSUSER_DISPLAY'       => ($rules_type == 3) ? '' : 'none',
            'WORD_DISPLAY'          => ($rules_type == 4) ? '' : 'none',
            'S_GROUPS'              => $s_groups,
            'RULES_USERNAME'        => $rules_username,
            'RULES_WORD'            => $rules_word,
            )
        );

        // system
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);
        _hide(POST_USERS_URL, $view_user_id);
        _hide('sid', $userdata['session_id']);

        $template->assign_vars(array(
            'S_ACTION'          => append_sid($main_pgm),
            'S_HIDDEN_FIELDS'   => _hidden_get(),
            )
        );

        // send to browser
        privmsg_footer();
        $template->pparse('body');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
        }
    }
}

//---------------------------------
//
//  main list
//
//---------------------------------
if ( in_array($pmmode, array('rlist', 'flist')) )
{
    if ( $cancel )
    {
        $pmmode = '';
        $pm_start = 0;
        $cancel = false;
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
            'body' => 'privmsga_folders_body.tpl')
        );

        // send header
        privmsg_header($view_user_id, $folder_id);

        // Header
        $template->assign_vars(array(
            'L_TITLE'       => _lang('Rules_management'),
            'L_EMPTY'       => _lang('No_rules'),

            'L_ADD_RULES'   => _lang('Add_new_rule'),
            'L_EDIT'        => _lang('Edit'),
            'L_COPY'        => _lang('Copy'),
            'L_CANCEL'      => _lang('Cancel'),
            )
        );

        // read data
        $color = false;
        @reset($rules);
        while ( list($rid, $data) = @each($rules) )
        {
            $color = !$color;
            $template->assign_block_vars('rules_row', array(
                'COLOR'     => $color ? 'row1' : 'row2',
                'L_NAME'    => $data['rules_name'],
                'U_NAME'    => append_sid("$main_pgm&pmmode=redit&folder=$folder_id&rule=$rid&" . POST_USERS_URL . "=$view_user_id"),
                'U_COPY'    => append_sid("$main_pgm&pmmode=rcreate&folder=$folder_id&rule=$rid&" . POST_USERS_URL . "=$view_user_id"),
                'U_DELETE'  => append_sid("$main_pgm&pmmode=rdelete&folder=$folder_id&rule=$rid&" . POST_USERS_URL . "=$view_user_id"),
                )
            );
        }
        if ( empty($rules) )
        {
            $template->assign_block_vars('rules_empty', array());
        }
        $template->assign_vars(array(
            'SPAN_ALL' => empty($rules) ? 1 : 2,
            )
        );

        // system
        _hide('pmmode', $pmmode);
        _hide('folder', $folder_id);
        _hide(POST_USERS_URL, $view_user_id);
        _hide('sid', $userdata['session_id']);

        $template->assign_vars(array(
            'S_ACTION'          => append_sid($main_pgm),
            'S_HIDDEN_FIELDS'   => _hidden_get(),
            )
        );

        // send to browser
        privmsg_footer();
        $template->pparse('body');
        if ( !defined('IN_PCP') )
        {
            include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
        }
    }
}

?>