<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_smart_tags.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_smart_tags.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		CodeMonkeyX
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['General']['Smarts'] = "$file";
    return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
    $mode = ($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
}
else
{
    //
    // These could be entered via a form button
    //
    if( isset($HTTP_POST_VARS['add']) )
    {
        $mode = "add";
    }
    else if( isset($HTTP_POST_VARS['save']) )
    {
        $mode = "save";
    }
    else
    {
        $mode = "";
    }
}

if( $mode != "" )
{
    if( $mode == "edit" || $mode == "add" )
    {
        $smart_id = ( isset($HTTP_GET_VARS['id']) ) ? $HTTP_GET_VARS['id'] : 0;

        $template->set_filenames(array(
            "body" => "smart_tag_edit_body.tpl")
        );

        $s_hidden_fields = '';

        if( $mode == "edit" )
        {
            if( $smart_id )
            {
                $sql = 'SELECT *
                    FROM ' . SMART_TABLE . "
                    WHERE smart_id = $smart_id";

                if(!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not query smart tag table", "Error", __LINE__, __FILE__, $sql);
                }

                $smart_info = $db->sql_fetchrow($result);
                $s_hidden_fields .= '<input type="hidden" name="id" value="' . $smart_id . '" />';
            }
            else
            {
                message_die(GENERAL_MESSAGE, $lang['No_smart_selected']);
            }
        }

        $template->assign_vars(array(
            'SMART' => $smart_info['smart'],
            'URL' => $smart_info['url'],

            'L_SMART_TITLE' => $lang['Smart_title'],
            'L_SMART_TEXT' => $lang['Smart_explain'],
            'L_SMART_EDIT' => $lang['Edit_smart'],
            'L_SMART' => $lang['Smart'],
            'L_URL' => $lang['Smart_URL'],
            'L_SUBMIT' => $lang['Submit'],

            'S_SMART_ACTION' => append_sid("admin_smart_tags.$phpEx"),
            'S_HIDDEN_FIELDS' => $s_hidden_fields)
        );

        $template->pparse("body");

        include('./page_footer_admin.'.$phpEx);
    }
    else if( $mode == "save" )
    {
        $smart_id = ( isset($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : 0;
        $smart = ( isset($HTTP_POST_VARS['smart']) ) ? trim($HTTP_POST_VARS['smart']) : "";
        $url = ( isset($HTTP_POST_VARS['url']) ) ? trim($HTTP_POST_VARS['url']) : "";

        if($smart == "" || $url == "")
        {
            message_die(GENERAL_MESSAGE, $lang['Must_enter_smart']);
        }

        if( $smart_id )
        {
            $sql = "UPDATE " . SMART_TABLE . "
                SET smart = '" . str_replace("\'", "''", htmlspecialchars($smart) ) . "', url = '" . str_replace("\'", "''", htmlspecialchars($url)) . "'
                WHERE smart_id = $smart_id";
            $message = $lang['Smart_updated'];
        }
        else
        {
            $sql = 'SELECT smart FROM ' . SMART_TABLE . " WHERE smart = '" . str_replace("\'", "''", htmlspecialchars($smart) ) . "'";

            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not insert data into words table", $lang['Error'], __LINE__, __FILE__, $sql);
            }

            if( $db->sql_fetchrow( $result ) )
            {
                $message = 'Smart tag already in Database.';
                $message .= "<br /><br />" . sprintf($lang['Click_return_smartadmin'], "<a href=\"" . append_sid("admin_smart_tags.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

                $db->sql_freeresult( $result );

                message_die(GENERAL_MESSAGE, $message );
            }

            $db->sql_freeresult( $result );

            $sql = "INSERT INTO " . SMART_TABLE . " (smart, url)
                VALUES ('" . str_replace("\'", "''", htmlspecialchars($smart)) . "', '" . str_replace("\'", "''", htmlspecialchars($url)) . "')";

            $message = $lang['Smart_added'];
        }

        if(!$result = $db->sql_query($sql))
        {
            message_die(GENERAL_ERROR, "Could not insert data into words table", $lang['Error'], __LINE__, __FILE__, $sql);
        }

		$cache->destroy('smart_tags');

        $message .= "<br /><br />" . sprintf($lang['Click_return_smartadmin'], "<a href=\"" . append_sid("admin_smart_tags.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
    }
    else if( $mode == "delete" )
    {
        if( isset($HTTP_POST_VARS['id']) ||  isset($HTTP_GET_VARS['id']) )
        {
            $smart_id = ( isset($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
        }
        else
        {
            $smart_id = 0;
        }

        if( $smart_id )
        {
            $sql = "DELETE FROM " . SMART_TABLE . "
                WHERE smart_id = $smart_id";

            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not remove data from words table", $lang['Error'], __LINE__, __FILE__, $sql);
            }

			$cache->destroy('smart_tags');

            $message = $lang['Smart_removed'] . "<br /><br />" . sprintf($lang['Click_return_smartadmin'], "<a href=\"" . append_sid("admin_smart_tags.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);
        }
        else
        {
            message_die(GENERAL_MESSAGE, $lang['No_smart_selected']);
        }
    }
}
else
{
    $template->set_filenames(array(
        "body" => "smart_tag_list_body.tpl")
    );

    $sql = "SELECT *
        FROM " . SMART_TABLE . "
        ORDER BY smart";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query words table", $lang['Error'], __LINE__, __FILE__, $sql);
    }

    $word_rows = $db->sql_fetchrowset($result);
    $word_count = count($word_rows);

    $template->assign_vars(array(
        'L_SMART_TITLE' => $lang['Smart_title'],
        'L_SMART_TEXT' => $lang['Smart_explain'],
        'L_SMART' => $lang['Smart'],
        'L_URL' => $lang['Smart_URL'],
        'L_EDIT' => $lang['Edit'],
        'L_DELETE' => $lang['Delete'],
        'L_ADD_SMART' => $lang['Add_new_smart'],
        'L_ACTION' => $lang['Action'],

        'S_SMART_ACTION' => append_sid("admin_smart_tags.$phpEx"),
        'S_HIDDEN_FIELDS' => '')
    );

    for($i = 0; $i < $word_count; $i++)
    {
        $smart = $word_rows[$i]['smart'];
        $url = $word_rows[$i]['url'];
        $smart_id = $word_rows[$i]['smart_id'];

        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        $template->assign_block_vars('smart', array(
            'ROW_COLOR' => "#" . $row_color,
            'ROW_CLASS' => $row_class,
            'SMART' => $smart,
            'URL' => $url,

            'U_SMART_EDIT' => append_sid("admin_smart_tags.$phpEx?mode=edit&amp;id=$smart_id"),
            'U_SMART_DELETE' => append_sid("admin_smart_tags.$phpEx?mode=delete&amp;id=$smart_id"))
        );
    }
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
