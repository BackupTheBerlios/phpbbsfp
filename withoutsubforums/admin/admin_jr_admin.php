<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_jr_admin.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_jnr_admin.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/****************************************************************************
/** Module Setup
/***************************************************************************/

if (!empty($setmodules))
{
    $filename = basename(__FILE__);
    $module['Users']['Jr_Admin'] = $filename;
    return;
}

define('IN_PHPBB', true);
define('MOD_VERSION', '2.0.5');
define('MOD_CODE', 1);
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path."includes/functions_jr_admin.$phpEx");
include_once("pagestart.$phpEx");
find_lang_file_nivisec('lang_jr_admin');

/* Debugging for this file */
$debug = false;

/****************************************************************************
/** Constants and Main Vars.
/***************************************************************************/
$status_message = '';

define('UPDATE_MODULE_PREFIX', 'update_module_');
$update_find_pattern = "/^.+_".UPDATE_MODULE_PREFIX."/";

/****************************************************************************
/** Functions
/***************************************************************************/
function jr_admin_user_exist($user_id)
{
	global $db, $lang;

	$sql = 'SELECT start_date
		FROM ' . JR_ADMIN_TABLE . "
		WHERE user_id = $user_id";

	if ( !($result = $db->sql_query) )
	{
		message_die(GENERAL_ERROR, $lang['Error_Module_Table'], '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$db->sql_freeresult($result);

    return isset($row['start_date']);
}

function jr_admin_make_rank_list($user_id, $user_rank)
{
	global $lang, $db;

	/****************
	** Due to a damn bug in some browsers (mozilla firebird for sure)
	** this needs to be disabled for drop down!  return only the name
	** for now.
	****************/
	/*
	//Get a list of ranks and make a nice select box
	$rowset = sql_query_nivisec(
	'SELECT * FROM ' . RANKS_TABLE . " WHERE rank_special = 1
	ORDER BY rank_title ASC",
	$lang['Error_Users_Table'],
	false
	);
	
	$rank_list = '<select name="user_rank_list_"'.$user_id.'" class="post" size="1">';
	$selected = (0 == $user_rank) ? 'selected="selected"' : '';
	$rank_list .= '<option value="0" '.$selected.'>'.$lang['No_assigned_rank'].'</option>\n';
	for($i = 0; $i < count($rowset); $i++)
	{
	$selected = ($rowset[$i]['rank_id'] == $user_rank) ? ' selected="selected"' : '';
	$rank_list .= '<option value="'.$rowset[$i]['rank_id'].'"'.$selected.'>'.$rowset[$i]['rank_title'].'</option>\n';
	}
	$rank_list .= '</selected>';
	*/

	if (empty($user_rank))
	{
		return '';
	}

	$sql = 'SELECT rank_title
		FROM ' . RANKS_TABLE . "
		WHERE rank_id = $user_rank";

	if ( !($result = $db->sql_query) )
	{
		message_die(GENERAL_ERROR, $lang['Error_Users_Table'], '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$db->sql_freeresult($result);

	return $row['rank_title'];
}

function jr_admin_make_bookmark_heading($letters_list)
{
    global $lang;

    $seperator = ' | ';
    $start = '[ ';
    $end = ' ]';

    $list = '';

    $search_list = explode(',', $lang['ASCII_Search_Codes']);

    //Go through each char group
    foreach($search_list as $ord_value)
    {
        //Trim spaces
        $ord_value = trim($ord_value);
        $first_link = false;

        //Check & first
        if (preg_match("/^.+\&.+$/", $ord_value))
        {
            $make_link = false;
            $items = explode('&', $ord_value);
            for($i = $items[0]; $i <= $items[1]; $i++)
            {
                if (isset($letters_list[$i]))
                {
                    $make_link = true;
                    $first_link = (!$first_link) ? $i : $first_link;
                }
            }
            if ($make_link)
            {
                $list .= '<a href="#'.strtoupper(chr($first_link)).'" class="nav">'.strtoupper(chr($items[0])).' - '.strtoupper(chr($items[1])).'</a>';
            }
            else
            {
                $list .= strtoupper(chr($items[0])).' - '.strtoupper(chr($items[1]));
            }
            $list .= $seperator;
        }
        //Check for - now
        elseif (preg_match("/^.+\-.+$/", $ord_value))
        {
            $items = explode('-', $ord_value);
            for($i = $items[0]; $i <= $items[1]; $i++)
            {
                if (isset($letters_list[$i]))
                {
                    $list .= '<a href="#'.strtoupper(chr($i)).'" class="nav">'.strtoupper(chr($i)).'</a>';
                }
                else
                {
                    $list .= strtoupper(chr($i));
                }
                $list .= $seperator;
            }
        }
        else
        {
            if (isset($letters_list[$ord_value]))
            {
                $list .= '<a href="#'.strtoupper(chr($ord_value)).'" class="nav">'.strtoupper(chr($ord_value)).'</a>';
            }
            else
            {
                $list .= strtoupper(chr($ord_value));
            }
            $list .= $seperator;
        }
    }

    //Replace the last seperator with the ending item
    $list = preg_replace('/'.addcslashes($seperator, '|').'$/', $end, $list);

    return ($start . $list);
}
/*******************************************************************************************
/** Get parameters.  'var_name' => 'default'
/******************************************************************************************/
$params = array('mode' => '', 'user_id' => '', 'order' => 'ASC', 'sort_item' => 'username');
if ($debug)
{
    //Dump out the get and post vars if in debug mode
    echo '<pre><span  class="gensmall"><font color="blue">DEBUG - POST VARS -<br />';
    print_r($HTTP_POST_VARS);
    echo '</font><br />';
    echo '<font color="red">DEBUG - GET VARS -<br />';
    print_r($HTTP_GET_VARS);
    echo '</font><br /></pre></span>';
}

foreach($params as $var => $default)
{
    $$var = $default;
    if( isset($HTTP_POST_VARS[$var]) || isset($HTTP_GET_VARS[$var]) )
    {
        $$var = ( isset($HTTP_POST_VARS[$var]) ) ? $HTTP_POST_VARS[$var] : $HTTP_GET_VARS[$var];
    }
}

//*******************************************************************************************
/** Check for edit user
/******************************************************************************************/
if (count($HTTP_POST_VARS))
{
    foreach ($HTTP_POST_VARS as $key => $val)
    {
        if (preg_match("/^edit_user_/", $key))
        {
            $user_id = str_replace('edit_user_', '', $key);
        }
    }
}
$page_title = $lang['Jr_Admin'];
$page_desc = $lang['Permissions_Page_Desc'];


if (!empty($user_id) && !isset($HTTP_POST_VARS['update_user']))
{
    $sql = 'SELECT user_id, user_level  FROM ' . USERS_TABLE . "
        WHERE user_id = $user_id
        ORDER BY username ASC";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, $lang['Error_User_Table'], '', __LINE__, __FILE__, $sql);
    }

    $row = $db->sql_fetchrow($result);

    if ($debug)
    {
        //Dump out the get and post vars if in debug mode
        echo '<pre><span  class="gensmall"><font color="green">DEBUG - User Info -<br />';
        print_r($row);
        echo '</font><br /></pre></span>';
    }

    $jr_admin_row = jr_admin_get_user_info($user_id);
    $module_list = jr_admin_get_module_list();
    $user_module_list = explode(EXPLODE_SEPERATOR_CHAR, $jr_admin_row['user_jr_admin']);
    if ($debug)
    {
        //Dump out the get and post vars if in debug mode
        echo '<pre><span  class="gensmall"><font color="purple">DEBUG - Modules -<br />';
        print_r($module_list);
        echo '</font><br />';
        echo '<font color="maroon">DEBUG - User Modules -<br />';
        print_r($user_module_list);
        echo '</font><br /></pre></span>';
    }

    jr_admin_include_all_lang_files();

    $i = 0;
    foreach($module_list as $cat => $info_array)
    {
        $template->assign_block_vars('catrow', array(
        'CAT' => (isset($lang[$cat])) ? $lang[$cat] : preg_replace("/_/", ' ', $cat),
        'NUM' => $i,
        ));
        foreach($info_array as $module_name => $file_array)
        {
            $file_hash = $file_array['file_hash'];
            $checked = (in_array($file_hash, $user_module_list)) ? 'checked="checked"' : '';
            $template->assign_block_vars('catrow.modulerow', array(
            'ROW' => ($i % 2) ? 'row1' : 'row2',
            'NAME' => (isset($lang[$module_name])) ? $lang[$module_name] : preg_replace("/_/", ' ', $module_name),
            'FILENAME' => $file_array['filename'],
            'FILE_HASH' => $file_hash,
            'CHECKED' => $checked
            ));
        }
        $i++;
    }

    $template->assign_vars(array(
    	'USER_ID' => $user_id,
    	'USERNAME' => $row['username'],
    	'DISABLED' => $disabled,
    	'DISABLED_TEXT' => $disabled_text,
    	'START_DATE' => create_date($board_config['default_dateformat'], $jr_admin_row['start_date'], $board_config['board_timezone']),
    	'UPDATE_DATE' => create_date($board_config['default_dateformat'], $jr_admin_row['update_date'], $board_config['board_timezone']),
    	'NOTES' => $jr_admin_row['admin_notes'],
    	'NOTES_VIEW_CHECKED' => ($jr_admin_row['notes_view']) ? 'checked="checked"' : '',
    	'ADMIN_TEXT' => ($row['user_level'] == ADMIN) ? $lang['Admin_Note'] : ''
    	)
    );

    $template->set_filenames(array('body' => 'jr_admin_user_permissions.tpl'));
}
else
{
    //Update info like module list and color groups
    if (isset($HTTP_POST_VARS['update_user']) && !empty($user_id))
    {
        $user_update_list = '';
        foreach ($HTTP_POST_VARS as $key => $val)
        {
            if (preg_match($update_find_pattern, $key))
            {
                $user_update_list .= (!empty($user_update_list)) ? EXPLODE_SEPERATOR_CHAR : '';
                $user_update_list .= preg_replace($update_find_pattern, '', $key);
            }
        }

        if (!jr_admin_user_exist($user_id))
        {
            //If the user_id doesn't exist in the table, we need to add it
            //before we can update!
			$sql =  'INSERT INTO ' . JR_ADMIN_TABLE . "
				(user_id, start_date)
				VALUES ($user_id, " . time() . ')';

			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, $lang['Error_Module_Table'], '', __LINE__, __FILE__, $sql);
			}
        }

        $notes_view = (isset($HTTP_POST_VARS['notes_view'])) ? 1 : 0;
        $admin_notes = $HTTP_POST_VARS['admin_notes'];

        //Do the information update
        $sql = 'UPDATE ' . JR_ADMIN_TABLE . "
            SET user_jr_admin = '$user_update_list',
            update_date = " . time() . ",
            admin_notes = '$admin_notes',
            notes_view = $notes_view
            WHERE user_id = $user_id";
        if (!$db->sql_query($sql))
        {
            message_die(GENERAL_ERROR, $lang['Error_User_Table'], '', __LINE__, __FILE__, $sql);
        }
        $status_message .= $lang['Updated_Permissions'];
    }

    //No user_id was found or we are done updating, take them to the info page
    $current_letter = ''; //for alpha links
    $assigned_current_letter_link = false; //for alpha links
    $letter_list = array(); //hold letters

    $sql = 'SELECT username, user_id, user_rank, user_active
        FROM ' . USERS_TABLE . '
        WHERE user_id <> ' . ANONYMOUS . "
        ORDER BY $sort_item $order";
    if (!$result = $db->sql_query($sql))
    {
        message_die(GENERAL_ERROR, $lang['Error_User_Table'], '', __LINE__, __FILE__, $sql);
    }
    while ($row = $db->sql_fetchrow($result))
    {
        $test_letter = strtoupper(substr($row['username'], 0, 1));
        if ($test_letter != $current_letter)
        {
            //If we have a new letter, get it here.
            $current_letter = $test_letter;
            $assigned_current_letter_link = false;
            $letter_list[ord($current_letter)] = true;
        }

        $jr_admin_row = jr_admin_get_user_info($row['user_id']);
        $module_count = (!empty($jr_admin_row['user_jr_admin'])) ? count(explode(EXPLODE_SEPERATOR_CHAR, $jr_admin_row['user_jr_admin'])) : 0;
        $block_text = 'userrow';

        $template->assign_block_vars($block_text, array(
        'NAME' => $row['username'],
        'ID' => $row['user_id'],
        'ACTIVE' =>($row['user_active']) ? 'checked="checked"' : '',
        'ROW_CLASS' => ($i++ % 2) ? 'row1' : 'row2',
        'RANK_LIST' => jr_admin_make_rank_list($row['user_id'], $row['user_rank']),
        'BOOKMARK' => (!$assigned_current_letter_link) ? '<a name="'.$current_letter.'">' : '',
        'BOOKMARK_END' => (!$assigned_current_letter_link) ? '</a>' : '',
        'MODULE_COUNT' => ($module_count != 0) ? sprintf($lang['Modules_Owned'], $module_count) : ''
        ));

        //We 'know' we assigned it if it wasn't already now
        $assigned_current_letter_link = true;

    }

    //Make sort image choice and sorting links
    $base_order = ($order == 'ASC') ? 'order=DESC' : 'order=ASC';
    $base_filename = append_sid(basename(__FILE__) . '?' . $base_order);
    $desc_img = '<img src="'.$images['DESC_Image'].'" border="0">';
    $asc_img = '<img src="'.$images['ASC_Image'].'" border="0">';
    $template->assign_vars(array(
    'IMG_USERNAME' => ($sort_item == 'username') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
    'IMG_RANK' => ($sort_item == 'user_rank') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
    'IMG_ACTIVE' => ($sort_item == 'user_active') ? ($order == 'ASC') ? $asc_img : $desc_img : '',
    'S_USERNAME' => $base_filename . '&sort_item=username',
    'S_RANK' => $base_filename . '&sort_item=user_rank',
    'S_ACTIVE' => $base_filename . '&sort_item=user_active'
    ));

    if ($sort_item == 'username')
    {
        $template->assign_var('LETTER_HEADING', jr_admin_make_bookmark_heading($letter_list));
    }

    $template->set_filenames(array('body' => 'jr_admin_user_list.tpl'));
}
//Common Variables
$template->assign_vars(array(
	'S_ACTION' => append_sid(basename(__FILE__)),
	'S_USER_PERM' => append_sid('admin_ug_auth.'.$phpEx),
	'S_PROFILE' => append_sid($phpbb_root_path.'profile.'.$phpEx),
	'S_MANAGEMENT' => append_sid('admin_users.'.$phpEx),
	'S_USER_POST_URL' => POST_USERS_URL,
	'L_NONE' => $lang['None'],
	'L_ALLOW' => $lang['Allow_Access'],
	'L_VERSION' => $lang['Version'],
	'L_PAGE_NAME' => $page_title,
	'L_PAGE_DESC' => $page_desc,
	'MOD_NUMBER' => MOD,
	'VERSION' => MOD_VERSION,
	'L_USERS_W_ACCESS' => $lang['Users_with_Access'],
	'L_USERS_WOUT_ACCESS' => $lang['Users_without_Access'],
	'L_MODULE_COUNT' => $lang['Module_Count'],
	'L_EDIT' => $lang['Edit'],
	'L_UPDATE' => $lang['Update'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_EXAMPLE' => $lang['Example'],
	'L_MODULE_INFO' => $lang['Module_Info'],
	'L_CHECK_ALL_IN_CAT' => $lang['Cat_Check_All'],
	'L_CHECK_ALL' => $lang['Check_All'],
	'L_OPTIONS' => $lang['Options'],
	'L_EDIT_PERMISSIONS' => $lang['Edit_Permissions'],
	'L_VIEW_PROFILE' => $lang['View_Profile'],
	'L_EDIT_USER_DETAILS' => $lang['Edit_User_Details'],
	'L_NOTES' => $lang['Notes'],
	'L_ALLOW_VIEW' => $lang['Allow_View'],
	'L_START_DATE' => $lang['Start_Date'],
	'L_UPDATE_DATE' => $lang['Update_Date'],
	'L_USERNAME' => $lang['Username'],
'L_EDIT_LIST' => $lang['Edit_Modules'],
'L_USER_STATS' => $lang['User_Stats'],
'L_USER_INFO' => $lang['User_Info'],
'L_ACTIVE' => $lang['User_Active'],
'L_RANK' => $lang['Rank'],
'L_ADMIN_NOTES' => $lang['Admin_Notes']
));

if ($status_message != '')
{
    $template->assign_block_vars('statusrow', array());
    $template->assign_vars(array(
    'L_STATUS' => $lang['Status'],
    'I_STATUS_MESSAGE' => $status_message)
    );
}

$template->pparse('body');
include('page_footer_admin.'.$phpEx);

?>
