<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: board_menu_manager.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : board_menu_manager.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       OXPUS
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_menu.'.$phpEx);

//
// Start session management
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
// End session management
//

if ( !$userdata['session_logged_in'] )
{
    redirect(append_sid("login.".$phpEx."?redirect=board_menu_manager.".$phpEx, true));
    exit;
}

// Set variables
$params = array(
    'submit' => '',
    'move' => 'move',
    'move_default' => 'move_default',
    'set_links' => 'set_links',
    'sort_links' => 'sort_links',
    'manage_links' => 'manage_links',
    'config_links' => 'config_links',
    'cancel' => 'cancel',
    'reset' => 'reset',
    'default_sort' => 'default_sort'
);

$join_link = '';
foreach ( $params as $var => $param )
{
	if ( isset($HTTP_POST_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? htmlspecialchars($HTTP_POST_VARS[$param]) : '';
	}
	elseif ( isset($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_GET_VARS[$param]) ) ? htmlspecialchars($HTTP_GET_VARS[$param]) : '';
	}
	else
	{
		$$var = '';
	}
}

$userlang = ( $userdata['user_lang'] ) ? $userdata['user_lang'] : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $userlang . '/lang_menu.' . $phpEx );

if ( $cancel || $submit == 'cancel' )
{
    $cancel = '';
    $submit = '';
    $HTTP_POST_VARS = '';
}
if ( $reset || $submit == 'reset' )
{
    $cancel = '';
    $submit = '';
    $HTTP_POST_VARS = '';
    $reset = '';
    $set_links = TRUE;
    $update_set_links = '';
}

// Set default sorting
if ( $HTTP_POST_VARS['sort_default'] )
{
    $sql = "SELECT ub.board_link, bl.bl_dsort FROM " . USER_BOARD_LINKS_TABLE . " ub, " . BOARD_LINKS_TABLE . " bl
        WHERE ub.board_link = bl.bl_id
        AND ub.user_id = " . $userdata['user_id'];
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not get default sorting for users board menu', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $board_link = $row['board_link'];
        $board_sort = $row['bl_dsort'];

        $sql_updates = "UPDATE " . USER_BOARD_LINKS_TABLE . "
                SET board_sort = $board_sort
                WHERE board_link = $board_link
                AND user_id = " . $userdata['user_id'];
        if ( !$result_updates = $db->sql_query($sql_updates) )
        {
            message_die(GENERAL_ERROR, 'Could not set default sorting for users board menu', '', __LINE__, __FILE__, $sql_updates);
        }
    }

    $sort_links = TRUE;
}

// The moves. Yeah! Lets move around...
if ( $move )
{
    $bl_id = ( isset($HTTP_POST_VARS['bl_id']) ) ? intval($HTTP_POST_VARS['bl_id']) : intval($HTTP_GET_VARS['bl_id']);

    if ( $bl_id )
    {
        if ( $move == 1 || $move == -1 )
        {
            $bl_move = ( $move == -1 ) ? -15 : 15;

            $sql = 'UPDATE ' . USER_BOARD_LINKS_TABLE . " SET board_sort = board_sort + $bl_move
                WHERE user_id = " . $userdata['user_id'] . "
                AND board_link = $bl_id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not save board menu sorting', '', __LINE__, __FILE__, $sql);
            }

			$db->sql_freeresult($result);

            reorder_menu_links('board');
        }
        else if ( $move == 9 || $move == -9 )
        {
            $sql = 'SELECT max(board_sort) as max_sort FROM ' . USER_BOARD_LINKS_TABLE . '
                WHERE user_id = ' . $userdata['user_id'];
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get board menu sorting', '', __LINE__, __FILE__, $sql);
            }

            while ( $row = $db->sql_fetchrow($result) )
            {
                $max_sort = $row['max_sort'];
            }

            $db->sql_freeresult($result);

            $bl_move = ( $move == -9 ) ? 0 : $max_sort + 10;

            $sql = 'UPDATE ' . USER_BOARD_LINKS_TABLE . " SET board_sort = $bl_move
                WHERE user_id = " . $userdata['user_id'] . "
                AND board_link = $bl_id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not save board menu sorting', '', __LINE__, __FILE__, $sql);
            }

			$db->sql_freeresult($result);

            reorder_menu_links('board');
        }
    }

    $sort_links = TRUE;
}

if ( $move_default )
{
    $bl_id = ( isset($HTTP_POST_VARS['bl_id']) ) ? intval($HTTP_POST_VARS['bl_id']) : intval($HTTP_GET_VARS['bl_id']);

    if ( $bl_id )
    {
        if ( $move_default == 15 || $move_default == -15 )
        {
            $bl_move = $move_default;
        }
        else if ( $move_default == -9 )
        {
            $sql = 'SELECT bl_dsort FROM ' . BOARD_LINKS_TABLE . '
                WHERE bl_id = ' . $bl_id;
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get board menu default sorting', '', __LINE__, __FILE__, $sql);
            }

            while ( $row = $db->sql_fetchrow($result) )
            {
                $bl_move = 0 - $row['bl_dsort'];
            }

			$db->sql_freeresult($result);
        }
        else
        {
            $sql = 'SELECT max(bl_dsort) as last_sort FROM ' . BOARD_LINKS_TABLE;
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get board menu default sorting', '', __LINE__, __FILE__, $sql);
            }

            while ( $row = $db->sql_fetchrow($result) )
            {
                $bl_move = $row['last_sort'] + 10;
            }

			$db->sql_freeresult($result);
        }

        $sql = 'UPDATE ' . BOARD_LINKS_TABLE . " SET bl_dsort = bl_dsort + $bl_move
            WHERE bl_id = $bl_id";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not save board menu default sorting', '', __LINE__, __FILE__, $sql);
        }

		$db->sql_freeresult($result);

        $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . '
            ORDER BY bl_dsort ASC';
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not save board menu default sorting', '', __LINE__, __FILE__, $sql);
        }

        $i = 10;
        while ( $row = $db->sql_fetchrow($result) )
        {
            $sql2 = 'UPDATE ' . BOARD_LINKS_TABLE . " SET bl_dsort = $i
                 WHERE bl_id = " . $row['bl_id'];
            if ( !($result2 = $db->sql_query($sql2)) )
            {
                message_die(GENERAL_ERROR, 'Could not save board menu default sorting', '', __LINE__, __FILE__, $sql);
            }

			$db->sql_freeresult($result2);
            $i += 10;
        }
		$cache->destroy('_menu_data');
		$cache->destroy('default_menu');
    }

    $default_sort = TRUE;
}

// The submits
if ( $HTTP_POST_VARS['config'] == 1 )
{
    $bl_seperator = intval($HTTP_POST_VARS['bl_seperator']);
    $bl_seperator_content = $HTTP_POST_VARS['bl_seperator_content'];
    $bl_break = intval($HTTP_POST_VARS['bl_break']);

    $bl_seperator = ( $bl_seperator == '' ) ? 0 : $bl_seperator;
    $bl_seperator_content = ( $bl_seperator_content == '' ) ? 'SPACE' : $bl_seperator_content;
    $bl_break = ( $bl_break == '' ) ? 5 : $bl_break;

    $bl_seperator = ( $bl_seperator_content == 'SPACE' ) ? 0 : $bl_seperator;
	if ( ($sql = set_config('bl_seperator', $bl_seperator)) )
    {
        message_die(GENERAL_ERROR, 'Could not save board menu configuration', '', __LINE__, __FILE__, $sql);
    }
	if ( ($sql = set_config('bl_seperator_content', $bl_seperator_content)) )
    {
        message_die(GENERAL_ERROR, 'Could not save board menu configuration', '', __LINE__, __FILE__, $sql);
    }

    if ( ($sql = set_config('bl_break', $bl_break)) )
    {
        message_die(GENERAL_ERROR, 'Could not save board menu configuration', '', __LINE__, __FILE__, $sql);
    }

    $board_config['bl_seperator'] = $bl_seperator;
    $board_config['bl_seperator_content'] = str_replace('SPACE', '&nbsp;&nbsp;&nbsp;', $bl_seperator_content);
    $board_config['bl_break'] = $bl_break;
}
else if ( $HTTP_POST_VARS['update_set_links'] == 1 )
{
    $user = $userdata['user_id'];

    $sort_links = FALSE;

    // Update choosed board menu links
    $bl_id = $HTTP_POST_VARS['bl_id'];

    if ( $bl_id )
    {
        $sql = 'DELETE FROM ' . USER_BOARD_LINKS_TABLE . ' WHERE user_id = ' . $user;
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not update board menu link', '', __LINE__, __FILE__, $sql);
        }

		$db->sql_freeresult($result);

        for ($i = 0, $max = count($bl_id); $i < $max; $i++)
        {
            $j = $i*10;
            $board_link = $bl_id[$i];

            if ( $board_link != '' )
            {
                $sql = 'INSERT INTO ' . USER_BOARD_LINKS_TABLE . "
                    (user_id, board_link, board_sort)
                    VALUES ($user, $board_link, $j)";
                if ( !($result = $db->sql_query($sql)) )
                {
                    message_die(GENERAL_ERROR, 'Could not save board menu link', '', __LINE__, __FILE__, $sql);
                }

				$db->sql_freeresult($result);
            }
        }

        reorder_menu_links('board', $user);

		$cache->destroy('default_menu');

        $sort_links = TRUE;
    }
}
else if ( $HTTP_POST_VARS['update_links'] == 1 && $userdata['user_level'] == ADMIN )
{
    // Get link settings and old auth
    $bl_id = $HTTP_POST_VARS['bl_id'];
    $bl_img = $HTTP_POST_VARS['bl_img'];
    $bl_name = $HTTP_POST_VARS['bl_name'];
    $bl_parameter = htmlspecialchars($HTTP_POST_VARS['bl_parameter']);
    $bl_link = htmlspecialchars($HTTP_POST_VARS['bl_link']);
    $bl_level = $HTTP_POST_VARS['bl_level'];

    $bl_img = ( $bl_img == '---' ) ? '' : $bl_img;

    $sql = 'SELECT bl_level FROM ' . BOARD_LINKS_TABLE . "
        WHERE bl_id = $bl_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not get menu link data', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $bl_level_check = $row['bl_level'];
    }

	$db->sql_freeresult($result);

    // Check auth changes
    $sql_user_level = '';

	if ( ( $bl_level_check == USER || $bl_level_check == ANONYMOUS ) && $bl_level == MOD )
	{
		$sql_user_level = 'WHERE user_level = ' . USER;
	}
	else if ( ( $bl_level_check == USER || $bl_level_check == ANONYMOUS || $bl_level_check == MOD ) && $bl_level == ADMIN )
	{
		$sql_user_level = 'WHERE user_level IN (' . USER . ', ' . MOD . ')';
	}

    if ( $sql_user_level != '' )
    {
        // Read userdata for needed updates
        $sql = 'SELECT user_id FROM ' . USERS_TABLE . "
            $sql_user_level
            ORDER BY user_id";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not get user data for deleting none accessable links', '', __LINE__, __FILE__, $sql);
        }

        // Delete not needed menu links
        while ( $row = $db->sql_fetchrow($result) )
        {
            $user = $row['user_id'];

            $sql2 = 'DELETE FROM ' . USER_BOARD_LINKS_TABLE . "
                 WHERE user_id = $user
                 AND board_link = $bl_id";
            if ( !($result2 = $db->sql_query($sql2)) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user board menu link', '', __LINE__, __FILE__, $sql2);
            }

			$db->sql_freeresult($result2);

            reorder_menu_links('board', $user);
        }

        $db->sql_freeresult($result);
    }

    $sql = 'UPDATE ' . BOARD_LINKS_TABLE . "
        SET bl_img = '$bl_img', bl_name = '$bl_name', bl_parameter = '$bl_parameter', bl_link = '$bl_link', bl_level = $bl_level
        WHERE bl_id = $bl_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not update board menu link', '', __LINE__, __FILE__, $sql);
    }

	$db->sql_freeresult($result);

	$cache->destroy('_menu_data');
	$cache->destroy('default_menu');

    $manage_links = TRUE;
}
else if ( $HTTP_POST_VARS['save_links'] == 1 && $userdata['user_level'] == ADMIN )
{
    // Save new link
    $bl_img = $HTTP_POST_VARS['bl_img'];
    $bl_name = $HTTP_POST_VARS['bl_name'];
    $bl_parameter = htmlspecialchars($HTTP_POST_VARS['bl_parameter']);
    $bl_link = htmlspecialchars($HTTP_POST_VARS['bl_link']);
    $bl_level = $HTTP_POST_VARS['bl_level'];

    $bl_img = ( $bl_img == '---' ) ? '' : $bl_img;

    $sql = 'INSERT INTO ' . BOARD_LINKS_TABLE . ' (bl_img , bl_name , bl_parameter , bl_link , bl_level)
        VALUES ' . "('$bl_img', '$bl_name', '$bl_parameter', '$bl_link', $bl_level)";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not save new board menu link', '', __LINE__, __FILE__, $sql);
    }

	$db->sql_freeresult($result);

	$cache->destroy('_menu_data');
	$cache->destroy('default_menu');

    $manage_links = TRUE;
}
else if ( isset($HTTP_POST_VARS['delete_link']) || isset($HTTP_GET_VARS['delete_link']) && $userdata['user_level'] == ADMIN )
{
    // Delete board menu link
    $bl_id = $HTTP_GET_VARS['bl_id'];

    $sql = 'SELECT user_id FROM ' . USER_BOARD_LINKS_TABLE . "
        WHERE board_link = $bl_id
        ORDER BY user_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not get link from user board menu', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $user = $row['user_id'];

        $sql_deletes = 'DELETE FROM ' . USER_BOARD_LINKS_TABLE . "
                WHERE board_link = $bl_id
                AND user_id = $user";
        if ( !$result_deletes = $db->sql_query($sql_deletes) )
        {
            message_die(GENERAL_ERROR, 'Could not delete link from user board menu', '', __LINE__, __FILE__, $sql_deletes);
        }

		$db->sql_freeresult($result_deletes);

        reorder_menu_links('board', $user);
    }

    $db->sql_freeresult($result);

    $sql = 'DELETE FROM ' . BOARD_LINKS_TABLE . "
        WHERE bl_id = $bl_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not delete board menu link', '', __LINE__, __FILE__, $sql);
    }

	$db->sql_freeresult($result);

	$cache->destroy('_menu_data');
	$cache->destroy('default_menu');

    $manage_links = TRUE;
}
else if ( isset($HTTP_POST_VARS['edit_link']) || isset($HTTP_GET_VARS['edit_link']) && $userdata['user_level'] == ADMIN )
{
    $bl_id = $HTTP_GET_VARS['bl_id'];

    $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . "
        WHERE bl_id = $bl_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu link', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $blimg = $row['bl_img'];
        $blname = $row['bl_name'];
        $bl_parameter = $row['bl_parameter'];
        $bl_link = $row['bl_link'];
        $bllevel = $row['bl_level'];
    }

    $bl_names = get_menu_language_names();
    $bl_names = str_replace('value="'.$blname.'">', 'value="'.$blname.'" SELECTED>', $bl_names);

    $bl_images = get_menu_images();
    $bl_images = str_replace('value="'.$blimg.'">', 'value="'.$blimg.'" SELECTED>', $bl_images);

    $bl_level = get_bl_access();
    $bl_level = str_replace('value="'.$bllevel.'">', 'value="'.$bllevel.'" SELECTED>', $bl_level);

    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        'body' => 'board_menu_links_edit.tpl')
    );

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_manage_links'],

        'L_BL_IMG' => $lang['Bl_img'],
        'L_BL_NAME' => $lang['Bl_name'],
        'L_BL_PARAMETER' => $lang['Bl_parameter'],
        'L_BL_PARAMETER_EXPLAIN' => $lang['Bl_parameter_explain'],
        'L_BL_LINK' => $lang['Bl_link'],
        'L_BL_LINK_EXPLAIN' => $lang['Bl_link_explain'],
        'L_BL_LEVEL' => $lang['Bl_level'],

        'BLIMG' => $bl_images,
        'BLNAME' => $bl_names,
        'BLPARAMETER' => '<input type="text" name="bl_parameter" size="50" maxlength="50" value="'.$bl_parameter.'" />',
        'BLLINK' => '<input type="text" name="bl_link" size="50" maxlength="128" value="'.$bl_link.'" />',
        'BLLEVEL' => $bl_level,
        'U_CLOSE_WINDOW' => '<input type="hidden" name="update_links" value="1"><input type="hidden" name="bl_id" value="'.$bl_id.'"><input type="submit" name="submit" value="'.$lang['Submit'].'" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="manage_links" value="'.$lang['Previous'].'" class="liteoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

$submit = '';

// Various Modes
if ( $set_links )
{
    // Prepare page
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $bl_links = array();
    $pl_links = array();

    $template->set_filenames(array(
        'body' => 'board_menu_links_set.tpl')
    );

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_set_links'],

        'L_BL_LINK' => $lang['Bl_link'],
        'L_PL_LINK' => $lang['Bl_plink'],
        'L_BL_SET' => $lang['Bl_set'],

        'L_MARK_ALL' => $lang['Mark_all'],
        'L_UNMARK_ALL' => $lang['Unmark_all'],

        'U_CLOSE_WINDOW' => '<input type="hidden" name="update_set_links" value="1"><input type="submit" name="submit" value="'.$lang['Submit'].'" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="'.$lang['Board_menu_manager'].'" class="liteoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="reset" value="'.$lang['Reset'].'" class="liteoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    // Prepare board menu links
    $sql = 'SELECT bl.* FROM ' . USER_BOARD_LINKS_TABLE . ' ub, ' . BOARD_LINKS_TABLE . ' bl
        WHERE ub.user_id = ' . $userdata['user_id'] . '
        AND ub.board_link = bl.bl_id
        ORDER BY ub.board_sort';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
    }
    $user_links_count = $db->sql_numrows($result);

    $bl_links = array();
    $board_menu_links_user = array();
    $board_menu_links_check = array();

    while( $row = $db->sql_fetchrow($result) )
    {
        $bl_links[] = $row['bl_id'];

        $board_menu_links = ( $row['bl_img'] != '' ) ? '<img src="'.get_bl_theme().$row['bl_img'].'" border="0" />&nbsp;' : '';
        if (substr($row['bl_link'],0,10) != 'javascript')
        {
		    $board_menu_links .= '<a href="'.append_sid($row['bl_link'].'.'.$phpEx.(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : ''));
        }
        else
        {
		    $board_menu_links .= '<a href="'.$row['bl_link'].(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : '');
        }
        $board_menu_links .= '" class="mainmenu" title="'.$lang[$row['bl_name']].'">'.$lang[$row['bl_name']].'</a>';

        $saved_link = $row['bl_id'];

        $board_menu_links_user[] = $board_menu_links;
        $board_menu_links_check[] = '<input type="checkbox" name="bl_id[]" value="'.$saved_link.'" checked="checked" />';
    }

	$db->sql_freeresult($result);

    $sql_access = get_bllink_access();

    $sql_where = '';
    if ( $user_links_count != 0 )
    {
        $sql_where = ( $sql_access != '' ) ? ' AND bl_id NOT IN ('.implode(',', $bl_links).')' : 'WHERE bl_id NOT IN ('.implode(',', $bl_links).')';
    }

    $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . "
        $sql_access
        $sql_where
        ORDER BY bl_dsort";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $board_menu_links = ( $row['bl_img'] != '' ) ? '<img src="'.get_bl_theme().$row['bl_img'].'" border="0" />&nbsp;' : '';
        if (substr($row['bl_link'],0,10) != 'javascript')
        {
		    $board_menu_links .= '<a href="'.append_sid($row['bl_link'].'.'.$phpEx.(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : ''));
        }
        else
        {
		    $board_menu_links .= '<a href="'.$row['bl_link'].(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : '');
        }
        $board_menu_links .= '" class="mainmenu" title="'.$lang[$row['bl_name']].'">'.$lang[$row['bl_name']].'</a>';

        $saved_link = $row['bl_id'];

        $template->assign_block_vars('board_links_row', array(
            'BL_MENU_LINKS' => $board_menu_links,
            'BL_CHECK' => '<input type="checkbox" name="bl_id[]" value="'.$saved_link.'" />')
        );
    }

	$db->sql_freeresult($result);

    for ( $i = 0, $max = count($board_menu_links_user); $i < $max; $i++ )
    {
        $template->assign_block_vars('board_links_row', array(
            'BL_MENU_LINKS' => $board_menu_links_user[$i],
            'BL_CHECK' => $board_menu_links_check[$i])
        );
    }

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else if ( $sort_links )
{
    // Prepare page
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $bl_links = array();
    $pl_links = array();

    $template->set_filenames(array(
        'body' => 'board_menu_links_sort.tpl')
    );

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_sort_links'],

        'L_BL_LINK' => $lang['Bl_link'],
        'L_PL_LINK' => $lang['Bl_plink'],

        'U_CLOSE_WINDOW' => '<input type="submit" name="cancel" value="'.$lang['Board_menu_manager'].'" class="mainoption" />',
        'U_SORT_DEFAULT' => '<input type="submit" name="sort_default" value="'.$lang['Board_manager_default_sort_links'].'" class="liteoption" />',
        'U_SORT_PDEFAULT' => '<input type="submit" name="sort_pdefault" value="'.$lang['Board_manager_default_sort_links'].'" class="liteoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    // Prepare board menu links
    $sql = 'SELECT bl.* FROM ' . USER_BOARD_LINKS_TABLE . ' ug, ' . BOARD_LINKS_TABLE . ' bl
        WHERE ug.user_id = ' . $userdata['user_id'] . '
        AND ug.board_link = bl.bl_id
        ORDER BY ug.board_sort';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
    }

    while( $row = $db->sql_fetchrow($result) )
    {

        $board_menu_links = ( $row['bl_img'] != '' ) ? '<img src="'.get_bl_theme().$row['bl_img'].'" border="0" />&nbsp;' : '';
        if (substr($row['bl_link'],0,10) != 'javascript')
        {
		    $board_menu_links .= '<a href="'.append_sid($row['bl_link'].'.'.$phpEx.(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : ''));
        }
        else
        {
		    $board_menu_links .= '<a href="'.$row['bl_link'].(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : '');
        }
        $board_menu_links .= '" class="mainmenu" title="'.$lang[$row['bl_name']].'">'.$lang[$row['bl_name']].'</a>';

        $bl_id = $row['bl_id'];
        $template->assign_block_vars('sort_links_row', array(
            'BL_LINK' => $board_menu_links,
            'BL_UP' => '<a href="'.append_sid("board_menu_manager.$phpEx?move=-1&amp;bl_id=$bl_id").'">'.$lang['Bl_moveup'].'</a>',
            'BL_DOWN' => '<a href="'.append_sid("board_menu_manager.$phpEx?move=1&amp;bl_id=$bl_id").'">'.$lang['Bl_movedown'].'</a>',
            'BL_FIRST' => '<a href="'.append_sid("board_menu_manager.$phpEx?move=-9&amp;bl_id=$bl_id").'">'.$lang['Bl_movefirst'].'</a>',
            'BL_LAST' => '<a href="'.append_sid("board_menu_manager.$phpEx?move=9&amp;bl_id=$bl_id").'">'.$lang['Bl_movelast'].'</a>')
        );
    }

    $db->sql_freeresult($result);

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else if ( $default_sort && $userdata['user_level'] == ADMIN )
{
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        'body' => 'board_menu_links_sort.tpl')
    );

    // Set default sorting for board menu links
    $sql = 'SELECT bl_dsort FROM ' . BOARD_LINKS_TABLE . '
        ORDER BY bl_dsort DESC
        LIMIT 1';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu for default sorting', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {
        $sort_check = $row['bl_dsort'];
    }

	$db->sql_freeresult($result);

    if ( $sort_check == '' )
    {
        $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . '
            ORDER BY bl_id';
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not save board menu default sorting', '', __LINE__, __FILE__, $sql);
        }

        $i = 10;
        while ( $row = $db->sql_fetchrow($result) )
        {
            $sql2 = 'UPDATE ' . BOARD_LINKS_TABLE . " SET bl_dsort = $i
                 WHERE bl_id = " . $row['bl_id'];
            if ( !($result2 = $db->sql_query($sql2)) )
            {
                message_die(GENERAL_ERROR, 'Could not save board menu default sorting', '', __LINE__, __FILE__, $sql);
            }

			$db->sql_freeresult($result2);

            $i += 10;
        }

		$db->sql_freeresult($result);

		$cache->destroy('_menu_data');
		$cache->destroy('default_menu');
    }

    $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . '
        ORDER BY bl_dsort';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read board menu for default sorting', '', __LINE__, __FILE__, $sql);
    }

    while ( $row = $db->sql_fetchrow($result) )
    {

        $board_menu_links = ( $row['bl_img'] != '' ) ? '<img src="'.get_bl_theme().$row['bl_img'].'" border="0" />&nbsp;' : '';
        if (substr($row['bl_link'],0,10) != 'javascript')
        {
		    $board_menu_links .= '<a href="'.append_sid($row['bl_link'].'.'.$phpEx.(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : ''));
        }
        else
        {
		    $board_menu_links .= '<a href="'.$row['bl_link'].(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : '');
        }
        $board_menu_links .= '" class="mainmenu" title="'.$lang[$row['bl_name']].'">'.$lang[$row['bl_name']].'</a>';

        $bl_id = $row['bl_id'];
        $template->assign_block_vars('sort_links_row', array(
            'BL_LINK' => $board_menu_links,
            'BL_UP' => '<a href="'.append_sid("board_menu_manager.$phpEx?move_default=-15&amp;bl_id=$bl_id").'">'.$lang['Bl_moveup'].'</a>',
            'BL_DOWN' => '<a href="'.append_sid("board_menu_manager.$phpEx?move_default=15&amp;bl_id=$bl_id").'">'.$lang['Bl_movedown'].'</a>',
            'BL_FIRST' => '<a href="'.append_sid("board_menu_manager.$phpEx?move_default=-9&amp;bl_id=$bl_id").'">'.$lang['Bl_movefirst'].'</a>',
            'BL_LAST' => '<a href="'.append_sid("board_menu_manager.$phpEx?move_default=9&amp;bl_id=$bl_id").'">'.$lang['Bl_movelast'].'</a>')
        );
    }

	$db->sql_freeresult($result);

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_default_sort_links'],

        'L_BL_LINK' => $lang['Bl_link'],
        'L_PL_LINK' => $lang['Bl_plink'],

        'U_CLOSE_WINDOW' => '<input type="submit" name="cancel" value="'.$lang['Board_menu_manager'].'" class="mainoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else if ( $manage_links && $userdata['user_level'] == ADMIN )
{
    // Load Page Header
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        'body' => 'board_menu_links_admin.tpl'));

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_manage_links'],

        'L_BL_IMG' => $lang['Bl_img'],
        'L_BL_NAME' => $lang['Bl_name'],
        'L_BL_PARAMETER' => $lang['Bl_parameter'],
        'L_BL_PARAMETER_EXPLAIN' => $lang['Bl_parameter_explain'],
        'L_BL_LINK' => $lang['Bl_link'],
        'L_BL_LINK_EXPLAIN' => $lang['Bl_link_explain'],
        'L_BL_LEVEL' => $lang['Bl_level'],

        'BLIMG' => get_menu_images(),
        'BLNAME' => get_menu_language_names(),
        'BLPARAMETER' => '<input type="text" name="bl_parameter" size="50" maxlength="50"/>',
        'BLLINK' => '<input type="text" name="bl_link" size="50" maxlength="128"/>',
        'BLLEVEL' => get_bl_access(),
        'U_CLOSE_WINDOW' => '<input type="hidden" name="save_links" value="1"><input type="submit" name="submit" value="'.$lang['Submit'].'" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="'.$lang['Board_menu_manager'].'" class="liteoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    // Get saved Board Menu Links
    $sql = 'SELECT * FROM ' . BOARD_LINKS_TABLE . '
        ORDER BY bl_dsort, bl_id';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read saves board menu links', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {

        $board_menu_links = ( $row['bl_img'] != '' ) ? '<img src="'.get_bl_theme().$row['bl_img'].'" border="0" />&nbsp;' : '';
        if (substr($row['bl_link'],0,10) != 'javascript')
        {
		    $board_menu_links .= '<a href="'.append_sid($row['bl_link'].'.'.$phpEx.(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : ''));
        }
        else
        {
		    $board_menu_links .= '<a href="'.$row['bl_link'].(( $row['bl_parameter'] != '') ? '?'.$row['bl_parameter'] : '');
        }
        $board_menu_links .= '" class="mainmenu" title="'.$lang[$row['bl_name']].'">'.$lang[$row['bl_name']].'</a>';

        switch ($row['bl_level'])
        {
            case ANONYMOUS:
                $bl_access_level = $lang['Bl_guest'];
                break;
            case USER:
                $bl_access_level = $lang['Bl_user'];
                break;
            case MOD:
                $bl_access_level = $lang['Bl_mod'];
                break;
            case ADMIN:
                $bl_access_level = $lang['Bl_admin'];
                break;
        }

        $bl_id = $row['bl_id'];

        $template->assign_block_vars('menulinkrow', array(
            'BL_MENU_LINK' => $board_menu_links,
            'BL_LEVEL' => $bl_access_level,
            'BL_EDIT' => '<a href="'.append_sid("board_menu_manager.$phpEx?edit_link=1&amp;bl_id=$bl_id").'" class="nav">'.$lang['Update'].'</a>',
            'BL_DELETE' => '<a href="'.append_sid("board_menu_manager.$phpEx?delete_link=1&amp;bl_id=$bl_id").'" class="nav">'.$lang['Delete'].'</a>')
        );
    }

	$db->sql_freeresult($result);

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else if ( $config_links && $userdata['user_level'] == ADMIN )
{
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        'body' => 'board_menu_config.tpl'));

    $sql = 'SELECT * FROM ' . CONFIG_TABLE . "
        WHERE config_name IN ('bl_seperator', 'bl_seperator_content', 'bl_break')";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not get board menu configuration', '', __LINE__, __FILE__, $sql);
    }

    $bl = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $bl[$row['config_name']] = $row['config_value'];
    }

	$db->sql_freeresult($result);

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_config_links'],

        'L_BL_SEPERATOR' => $lang['Bl_seperator'],
        'L_BL_SEPERATOR_CONTENT' => $lang['Bl_seperator_content'],
        'L_BL_BREAK' => $lang['Bl_break'],

        'BL_SEPERATOR' => ( $bl['bl_seperator'] == 1 ) ? 'checked="checked"' : '',
        'BL_SEPERATOR_CONTENT' => $bl['bl_seperator_content'],
        'BL_BREAK' => $bl['bl_break'],
        'U_CLOSE_WINDOW' => '<input type="hidden" name="config" value="1"><input type="submit" name="submit" value="'.$lang['Bl_config_save'].'" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="'.$lang['Board_menu_manager'].'" class="liteoption" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    $template->pparse('body');

    include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

//
// Welcome Screen and Board Manager Menu
//
if ( !$submit )
{
    // Load Board Header with current user board menu
    $page_title = $lang['Board_menu_manager'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        'body' => 'board_menu_welcome.tpl'));

    $template->assign_vars(array(
        'L_PAGE_TITLE' => $page_title,
        'L_WELCOME' => $lang['Board_manager_welcome'],
        'L_MANAGER_EXPLAIN' => $lang['Board_manager_explain'],
        'L_USER_OPTIONS' => $lang['Board_manager_user_options'],
        'L_ADMINISTRATOR_OPTIONS' => $lang['Board_manager_administrator_options'],

        'U_SET_BOARD_LINKS' => '<input type="submit" name="set_links" style="width: 450px" value="'.$lang['Board_manager_set_links'].'" class="mainoption" />',
        'U_SORT_BOARD_LINKS' => '<input type="submit" name="sort_links" style="width: 450px" value="'.$lang['Board_manager_sort_links'].'" class="mainoption" />',
        'U_CLOSE_WINDOW' => '<input type="button" name="close" style="width: 100px" value="'.$lang['Board_manager_close'].'" class="liteoption" onClick="javascript:close_manager()" />',

        'S_ACTION' => append_sid("board_menu_manager.$phpEx"))
    );

    if ( $userdata['user_level'] == ADMIN )
    {
        $template->assign_block_vars('admin_options', array(
            'U_MANAGE_BOARD_LINKS' => '<input type="submit" name="manage_links" style="width: 450px" value="'.$lang['Board_manager_manage_links'].'" class="mainoption" />',
            'U_DEFAULT_SORT_LINKS' => '<input type="submit" name="default_sort" style="width: 450px" value="'.$lang['Board_manager_default_sort_links'].'" class="mainoption" />',
            'U_CONFIG_BOARD_LINKS' => '<input type="submit" name="config_links" style="width: 450px" value="'.$lang['Board_manager_config_links'].'" class="mainoption" />')
        );
    }

    $sql = 'SELECT * FROM ' . USER_BOARD_LINKS_TABLE . '
        WHERE user_id = ' . $userdata['user_id'];
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not check for existing board menu links', '', __LINE__, __FILE__, $sql);
    }
    $count_board_menu_links = $db->sql_numrows($result);

	$db->sql_freeresult($result);

    if ( $count_board_menu_links != 0 )
    {
        $template->assign_block_vars('switch_sorting_on', array());
    }

    $template->pparse('body');
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>