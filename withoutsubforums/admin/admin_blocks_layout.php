<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_blocks_layout.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_blocks_layout.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2004		Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['Blocks']['Layout'] = $file;
    return;
}

define('IN_PHPBB', 1);

//
// Load default header
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
    $mode = ($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
    $mode = htmlspecialchars($mode);
}
else
{
    //
    // These could be entered via a form button
    //
    if( isset($HTTP_POST_VARS['add']) )
    {
        $mode = 'add';
    }
    else if( isset($HTTP_POST_VARS['save']) )
    {
        $mode = 'save';
    }
    else
    {
        $mode = '';
    }
}

if(isset($HTTP_POST_VARS['cancel']))
{
    $mode = '';
}

/****** This is not needed right? ****

$sql = "SELECT *
        FROM " . MODULES_TABLE;
if (!($result = $db->sql_query($sql)))
{
    message_die(CRITICAL_ERROR, "Could not query module information", "", __LINE__, __FILE__, $sql);
}

**************************************/

$sql = "SELECT config_value FROM " . BLOCKS_CONFIG_TABLE . " WHERE config_name = 'default_layout'";
if( !($result = $db->sql_query($sql)) )
{
    message_die(CRITICAL_ERROR, "Could not query portal config table", "", __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);
$default_layout = $row['config_value'];

if ( $mode != '' )
{
    if ( $mode == "edit" || $mode == "add" )
    {
        $l_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;

        $template->set_filenames(array(
            "body" => "layout_edit_body.tpl")
        );

        $s_hidden_fields = '';

        if( $mode == "edit" )
        {
            if( $l_id )
            {
                $sql = "SELECT *
                    FROM " . BLOCKS_LAYOUT_TABLE . "
                    WHERE lid = $l_id";
                if(!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not query layout table", "Error", __LINE__, __FILE__, $sql);
                }

                $l_info = $db->sql_fetchrow($result);
                $s_hidden_fields .= '<input type="hidden" name="id" value="' . $l_id . '" />';

                $view_array = array(
                    '0' => $lang['B_All'],
                    '1' => $lang['B_Guests'],
                    '2' => $lang['B_Reg'],
                    '3' => $lang['B_Mod'],
                    '4' => $lang['B_Admin']
                );

                $view ='';
                for ($i = 0; $i <= 4; $i++)
                {
                    $view .= '<option value="' . $i .'" ';
                    if($l_info['view']==$i)
                    {
                        $view .= 'selected';
                    }
                    $view .= '>' . $view_array[$i];
                }

                $sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
                if( !($result = $db->sql_query($sql)) )
                {
                    message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
                }
                $group_array = explode(",", $l_info['groups']);
                $group = '';
                while ($row = $db->sql_fetchrow($result))
                {
                    $checked = (in_array($row['group_id'],$group_array)) ? 'checked' : '';
                    $group .= '<input type="checkbox" name="group' . strval($row['group_id']) . '" ' . $checked . '>' . $row['group_name'] . '&nbsp;&nbsp;';
                }
                if(empty($group))
                {
                    $group = '&nbsp;&nbsp;None';
                }
            }
            else
            {
                message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
            }

        }

        // Populate the layout selection list from $core_layout array (see constants.php) and '/modules' directory.

        for ($layout_loop = 0, $core_layout_count = count($core_layout); $layout_loop < $core_layout_count; $layout_loop++)
        {
            $name_select .= '<option value="' . $core_layout[$layout_loop] .'" ';
            if ($l_info['name'] == $core_layout[$layout_loop])
            {
                $name_select .= 'selected';
            }
            $name_select .= '>' . $core_layout[$layout_loop];
        }

        $modules_dir = $phpbb_root_dir . '../modules/';
        $modules = opendir($modules_dir);

        while (($file = readdir($modules)) !== false)
        {
            if ($file[0] != '.')
            {
                if (is_dir($modules_dir . $file))
                {
                    $name_select .= '<option value="' . $file .'" ';
                    if ( $l_info['name'] == $file )
                    {
                        $name_select .= 'selected';
                    }
                    $name_select .= '>' . $file;
                }
            }
        }

        $view_array = array(
            '0' => $lang['B_All'],
            '1' => $lang['B_Guests'],
            '2' => $lang['B_Reg'],
            '3' => $lang['B_Mod'],
            '4' => $lang['B_Admin']
        );

        $view ='';
        for ($i = 0; $i <= 4; $i++)
        {
            $view .= '<option value="' . $i .'">' . $view_array[$i];
        }

        $sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
        }
        $group = '';
        while ($row = $db->sql_fetchrow($result))
        {
            $group .= '<input type="checkbox" name="group' . strval($row['group_id']) . '">' . $row['group_name'] . '&nbsp;&nbsp;';
        }
        if (empty($group))
        {
            $group = '&nbsp;&nbsp;None';
        }

        $template->assign_vars(array(
            "L_LAYOUT_TITLE" => $lang['Layout_Title'],
            "L_LAYOUT_TEXT" => $lang['Layout_Explain'],
            "L_LAYOUT_NAME" => $lang['Layout_Name'],
            "L_LAYOUT_VIEW" => $lang['Layout_View'],
            "L_LAYOUT_GROUPS" => $lang['B_Groups'],
            "L_YES" => $lang['Yes'],
            "L_NO" => $lang['No'],
            "NAME" => $l_info['name'],
            "NAME_SELECT" => $name_select,
            "VIEW" => $view,
            "GROUPS" => $group,
            "L_EDIT_LAYOUT" => $lang['Layout_Edit'],
            "L_SUBMIT" => $lang['Submit'],
            "S_LAYOUT_ACTION" => append_sid("admin_blocks_layout.$phpEx"),
            "S_HIDDEN_FIELDS" => $s_hidden_fields)
        );

        $template->pparse("body");

        include('./page_footer_admin.'.$phpEx);
    }
    else if( $mode == "save" )
    {
        $l_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
        $l_name = ( isset($HTTP_POST_VARS['name']) ) ? trim($HTTP_POST_VARS['name']) : "";
        $l_view = ( isset($HTTP_POST_VARS['view']) ) ? intval($HTTP_POST_VARS['view']) : 0;

        $sql = "SELECT MAX(group_id) max_group_id FROM " . GROUPS_TABLE . " WHERE group_single_user = 0";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
        }
        $row = $db->sql_fetchrow($result);
        $l_group = '';
        $not_first = FALSE;
        for ($i = 1; $i <= $row['max_group_id']; $i++)
        {
            if (isset($HTTP_POST_VARS['group' . strval($i)]))
            {
                if ($not_first)
                {
                    $l_group .= ',' . strval($i);
                }
				else
                {
                    $l_group .= strval($i);
                    $not_first = TRUE;
                }
            }
        }
        if ($l_name == "")
        {
            message_die(GENERAL_MESSAGE, $lang['Must_enter_layout']);
        }

        if ( $l_id )
        {
            $sql = "UPDATE " . BLOCKS_LAYOUT_TABLE . "
                SET name = '" . str_replace("\'", "''", $l_name) . "',
                view = '" . $l_view . "',
                groups = '" . $l_group . "'
                WHERE lid = $l_id";
            if (!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not insert data into layout table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
            $message = $lang['Layout_updated'];
        }
        else
        {
            $sql = "INSERT INTO " . BLOCKS_LAYOUT_TABLE . " (name, view, groups)
                VALUES ('" . str_replace("\'", "''", $l_name) . "', '" . $l_view . "', '" . $l_group . "')";
            if (!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not insert data into layout table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
            $message = $lang['Layout_added'];
        }

        $message .= "<br /><br />" . sprintf($lang['Click_return_layoutadmin'], "<a href=\"" . append_sid("admin_blocks_layout.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
        message_die(GENERAL_MESSAGE, $message);
    }
    else if ( $mode == "delete" )
    {
        if ( isset($HTTP_POST_VARS['id']) ||  isset($HTTP_GET_VARS['id']) )
        {
            $l_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
        }
        else
        {
            $l_id = 0;
        }

        if (!isset($HTTP_POST_VARS['confirm']))
        {
            $hidden_fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="id" value="'.$l_id.'" />';

            //
            // Set template files
            //
            $template->set_filenames(array(
                "confirm" => "confirm_body.tpl")
            );

            $template->assign_vars(array(
                "MESSAGE_TITLE" => $lang['Confirm'],
                "MESSAGE_TEXT" => $lang['Confirm_delete_item'],

                "L_YES" => $lang['Yes'],
                "L_NO" => $lang['No'],

                "S_CONFIRM_ACTION" => append_sid("admin_blocks_layout.$phpEx"),
                "S_HIDDEN_FIELDS" => $hidden_fields)
            );

            $template->pparse("confirm");
            exit();
        }
		else
        {
            if ( $l_id )
            {
                $sql = "DELETE FROM " . BLOCKS_LAYOUT_TABLE . "
                    WHERE lid = $l_id";

                if (!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not remove data from layout table", $lang['Error'], __LINE__, __FILE__, $sql);
                }

                $sql = "DELETE FROM " . BLOCKS_POSITION_TABLE . "
                    WHERE layout = $l_id";

                if (!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not remove data from blocks position table", $lang['Error'], __LINE__, __FILE__, $sql);
                }

                $sql = "DELETE FROM " . BLOCKS_TABLE . "
                    WHERE layout = $l_id";

                if (!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not remove data from blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
                }

                $message = $lang['Layout_removed'] . "<br /><br />" . sprintf($lang['Click_return_layoutadmin'], "<a href=\"" . append_sid("admin_blocks_layout.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

                message_die(GENERAL_MESSAGE, $message);
            }
            else
            {
                message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
            }
        }
    }
}
else
{
    if ( isset($HTTP_GET_VARS['d']))
    {
        $d = intval($HTTP_GET_VARS['d']);

        $sql = "UPDATE " . BLOCKS_CONFIG_TABLE . " SET config_value = '" . $d . "' WHERE config_name = 'default_layout'";

        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Could not update config table", $lang['Error'], __LINE__, __FILE__, $sql);
        }

		$cache->destroy('portal_config');

        $default_layout = $d;
    }

    $template->set_filenames(array(
        "body" => "layout_list_body.tpl")
    );

    $sql = "SELECT *
        FROM " . BLOCKS_LAYOUT_TABLE . "
        ORDER BY lid";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query layout table", $lang['Error'], __LINE__, __FILE__, $sql);
    }

    $l_rows = $db->sql_fetchrowset($result);
    $l_count = count($l_rows);

    $template->assign_vars(array(
        "L_LAYOUT_TITLE" => $lang['Layout_Title'],
        "L_LAYOUT_TEXT" => $lang['Layout_Explain'],
        "L_LAYOUT_NAME" => $lang['Layout_Name'],
        "L_LAYOUT_PAGE" => $lang['Layout_Page'],
        "L_LAYOUT_VIEW" => $lang['Layout_View'],
        "L_LAYOUT_GROUPS" => $lang['B_Groups'],
        "L_EDIT" => $lang['Edit'],
        "L_LAYOUT_ADD" => $lang['Layout_Add'],
        "L_ACTION" => $lang['Action'],

        "S_LAYOUT_ACTION" => append_sid("admin_blocks_layout.$phpEx"),
        "S_HIDDEN_FIELDS" => '')
    );

    for ($i = 0; $i < $l_count; $i++)
    {
        $l_name = $l_rows[$i]['name'];
        $l_id = $l_rows[$i]['lid'];

        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        switch ($l_rows[$i]['view'])
        {
            case '0':
                $l_view = $lang['B_All'];
                break;
            case '1':
                $l_view = $lang['B_Guests'];
                break;
            case '2':
                $l_view = $lang['B_Reg'];
                break;
            case '3':
                $l_view = $lang['B_Mod'];
                break;
            case '4':
                $l_view = $lang['B_Admin'];
                break;
        }

        if (!empty($l_rows[$i]['groups']))
        {
            $sql = "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id in (" . $l_rows[$i]['groups'] . ")";
            if( !($result = $db->sql_query($sql)) )
            {
                message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
            }
            $groups = '';
            $not_first = FALSE;
            while ($row = $db->sql_fetchrow($result))
            {
                if($not_first)
                {
                    $groups .= '&nbsp;&nbsp;' . '[ ' . $row['group_name'] . ' ]';
                }
				else
                {
                    $not_first = TRUE;
                    $groups .= '[ ' . $row['group_name'] . ' ]';
                }
            }
        }
		else
        {
            $groups = $lang['B_All'];
        }

        $template->assign_block_vars("layout", array(
            "ROW_COLOR" => "#" . $row_color,
            "ROW_CLASS" => $row_class,
            "NAME" => $l_name,
            "PAGE" => strval($l_id),
            "VIEW" => $l_view,
            "GROUPS" => $groups,
            "L_DEFAULT" => ($l_id != $default_layout) ? '<a href ="' . append_sid("admin_blocks_layout.$phpEx?d=$l_id") . '">' . $lang['Layout_make_default'] . '</a>' : $lang['Layout_default'],
            "U_LAYOUT_EDIT" => append_sid("admin_blocks_layout.$phpEx?mode=edit&amp;id=$l_id"),
            "L_LAYOUT_DELETE" => ($l_id!=$default_layout) ? '<a href ="' . append_sid("admin_blocks_layout.$phpEx?mode=delete&amp;id=$l_id") . '">' . $lang['Delete'] . '</a>' : ''
            )
        );
    }
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
