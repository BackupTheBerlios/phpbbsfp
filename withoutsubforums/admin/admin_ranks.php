<?php
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_ranks.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_ranks.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['Users']['Ranks'] = "$file";
    return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
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
        //
        // They want to add a new rank, show the form.
        //
        $rank_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;

        $s_hidden_fields = "";

        if( $mode == "edit" )
        {
            if( empty($rank_id) )
            {
                message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
            }

            $sql = "SELECT * FROM " . RANKS_TABLE . "
                WHERE rank_id = $rank_id";
            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Couldn't obtain rank data", "", __LINE__, __FILE__, $sql);
            }

            $rank_info = $db->sql_fetchrow($result);
            $s_hidden_fields .= '<input type="hidden" name="id" value="' . $rank_id . '" />';

        }
        else
        {
            $rank_info['rank_special'] = 0;
        }

        $s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

        $rank_is_special = ( $rank_info['rank_special'] ) ? "checked=\"checked\"" : "";
        $rank_is_not_special = ( !$rank_info['rank_special'] ) ? "checked=\"checked\"" : "";

        //Ranks in template and drop-down list mod
        //$rep = 'templates/' . $template_name . '/images/ranks/';
        $dir = opendir($phpbb_root_path . $images['rank']);

        $l = 0;
        while($file = readdir($dir))
        {
            if (strpos($file, '.gif'))
            {
                $file1[$l] = $file;
                $l++;
            }
        }
        closedir($dir);

        $ranks_list = "<option value=\"" . $rank_info['rank_image'] . "\" selected>" . $rank_info['rank_image'] . "</option>";

        for($k=0; $k<=$l;$k++)
        {
            if ($file1[$k] != "")
            {
                $ranks_list .= "<option value=\"" . $file1[$k] . "\">" . $file1[$k] . "</option>";
            }
        }
        //end - Ranks in template and drop-down list mod

        $template->set_filenames(array(
            "body" => "ranks_edit_body.tpl")
        );

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
        $rank_title     = explode( '|', $rank_info['rank_title']);
        $rank_default   = (isset($rank_title[0]) ) ? $rank_title[0] : '';
        $rank_male      = (isset($rank_title[1]) ) ? $rank_title[1] : '';
        $rank_female    = (isset($rank_title[2]) ) ? $rank_title[2] : '';
//-- fin mod : profile cp --------------------------------------------------------------------------

        $template->assign_vars(array(
//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//          "RANK" => $rank_info['rank_title'],
//-- add
            'L_RANK_DEFAULT'    => $lang['No_gender_specify'],
            'L_RANK_MALE'       => $lang['Male'],
            'L_RANK_FEMALE'     => $lang['Female'],
            'RANK_DEFAULT'      => $rank_default,
            'RANK_MALE'         => ($rank_male != '') ? $rank_male : $rank_default,
            'RANK_FEMALE'       => ($rank_female != '') ? $rank_female : $rank_default,
//-- fin mod : profile cp --------------------------------------------------------------------------

            "SPECIAL_RANK" => $rank_is_special,
            "NOT_SPECIAL_RANK" => $rank_is_not_special,
            "MINIMUM" => ( $rank_is_special ) ? "" : $rank_info['rank_min'],
            "IMAGE" => ( $rank_info['rank_image'] != "" ) ? $rank_info['rank_image'] : "",

            "IMAGE_DISPLAY" => ( $rank_info['rank_image'] != "" ) ? '<img src="../' . $images['rank'] . $rank_info['rank_image'] . '" />' : "",
            "RANK_LIST" => $ranks_list,

            "L_RANKS_TITLE" => $lang['Ranks_title'],
            "L_RANKS_TEXT" => $lang['Ranks_explain'],
            "L_RANK_TITLE" => $lang['Rank_title'],
            "L_RANK_SPECIAL" => $lang['Rank_special'],
            "L_RANK_MINIMUM" => $lang['Rank_minimum'],
            "L_RANK_IMAGE" => $lang['Rank_image'],
            "L_RANK_IMAGE_EXPLAIN" => $lang['Rank_image_explain'],
            "L_SUBMIT" => $lang['Submit'],
            "L_RESET" => $lang['Reset'],
            "L_YES" => $lang['Yes'],
            "L_NO" => $lang['No'],

            "S_RANK_ACTION" => append_sid("admin_ranks.$phpEx"),
            "S_HIDDEN_FIELDS" => $s_hidden_fields)
        );

    }
    else if( $mode == "save" )
    {
        //
        // Ok, they sent us our info, let's update it.
        //

        $rank_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//      $rank_title = ( isset($HTTP_POST_VARS['title']) ) ? trim($HTTP_POST_VARS['title']) : "";
//-- add
        $rank_default   = ( isset($HTTP_POST_VARS['title_default']) ) ? trim($HTTP_POST_VARS['title_default']) : '';
        $rank_male      = ( isset($HTTP_POST_VARS['title_male']) ) ? trim($HTTP_POST_VARS['title_male']) : '';
        $rank_female    = ( isset($HTTP_POST_VARS['title_female']) ) ? trim($HTTP_POST_VARS['title_female']) : '';

        if ($rank_default == '') $rank_default = $rank_male;
        if ($rank_default == '') $rank_default = $rank_female;
        if ($rank_male == $rank_default) $rank_male = '';
        if ($rank_female == $rank_default) $rank_female = '';
        $rank_title = (($rank_default != '') || ($rank_male != '') || ($rank_female != '')) ? $rank_default . ( ( ($rank_male != '') || ($rank_female != '') ) ? '|' : '' ) . $rank_male . ( ($rank_female != '') ? '|' : '' ) . $rank_female : '';
//-- fin mod : profile cp --------------------------------------------------------------------------

        $special_rank = ( $HTTP_POST_VARS['special_rank'] == 1 ) ? TRUE : 0;
        $min_posts = ( isset($HTTP_POST_VARS['min_posts']) ) ? intval($HTTP_POST_VARS['min_posts']) : -1;
        $rank_image = ( (isset($HTTP_POST_VARS['rank_image'])) ) ? trim($HTTP_POST_VARS['rank_image']) : "";

        if( $rank_title == "" )
        {
            message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
        }

        if( $special_rank == 1 )
        {
            $max_posts = -1;
            $min_posts = -1;
        }

        //
        // The rank image has to be a jpg, gif or png
        //
        if($rank_image != "")
        {
            if ( !preg_match("/(\.gif|\.png|\.jpg)$/is", $rank_image))
            {
                $rank_image = "";
            }
        }

        if ($rank_id)
        {
            if (!$special_rank)
            {
                $sql = "UPDATE " . USERS_TABLE . "
                    SET user_rank = 0
                    WHERE user_rank = $rank_id";

                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
                }
            }
            $sql = "UPDATE " . RANKS_TABLE . "
                SET rank_title = '" . str_replace("\'", "''", $rank_title) . "', rank_special = $special_rank, rank_min = $min_posts, rank_image = '" . str_replace("\'", "''", $rank_image) . "'
                WHERE rank_id = $rank_id";

            $message = $lang['Rank_updated'];
        }
        else
        {
            $sql = "INSERT INTO " . RANKS_TABLE . " (rank_title, rank_special, rank_min, rank_image)
                VALUES ('" . str_replace("\'", "''", $rank_title) . "', $special_rank, $min_posts, '" . str_replace("\'", "''", $rank_image) . "')";

            $message = $lang['Rank_added'];
        }

        if( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Couldn't update/insert into ranks table", "", __LINE__, __FILE__, $sql);
        }

		$cache->destroy('pcp_ranks');
		$cache->destroy('ranks'); 

        $message .= "<br /><br />" . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_ranks.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);

    }
    else if( $mode == "delete" )
    {
        //
        // Ok, they want to delete their rank
        //

        if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
        {
            $rank_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
        }
        else
        {
            $rank_id = 0;
        }

        if( $rank_id )
        {
            $sql = "DELETE FROM " . RANKS_TABLE . "
                WHERE rank_id = $rank_id";

            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't delete rank data", "", __LINE__, __FILE__, $sql);
            }

            $sql = "UPDATE " . USERS_TABLE . "
                SET user_rank = 0
                WHERE user_rank = $rank_id";

            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
            }

            $message = $lang['Rank_removed'] . "<br /><br />" . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_ranks.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

        }
        else
        {
            message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
        }
    }
    else
    {
        //
        // They didn't feel like giving us any information. Oh, too bad, we'll just display the
        // list then...
        //
        $template->set_filenames(array(
            "body" => "ranks_list_body.tpl")
        );

        $sql = "SELECT * FROM " . RANKS_TABLE . "
            ORDER BY rank_min, rank_title";
        if( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
        }

        $rank_rows = $db->sql_fetchrowset($result);
        $rank_count = count($rank_rows);

        $template->assign_vars(array(
            "L_RANKS_TITLE" => $lang['Ranks_title'],
            "L_RANKS_TEXT" => $lang['Ranks_explain'],
            "L_RANK" => $lang['Rank_title'],
            "L_RANK_MINIMUM" => $lang['Rank_minimum'],
            "L_SPECIAL_RANK" => $lang['Special_rank'],
            "L_EDIT" => $lang['Edit'],
            "L_DELETE" => $lang['Delete'],
            "L_ADD_RANK" => $lang['Add_new_rank'],
            "L_ACTION" => $lang['Action'],

            "S_RANKS_ACTION" => append_sid("admin_ranks.$phpEx"))
        );

        for( $i = 0; $i < $rank_count; $i++)
        {
            $rank = $rank_rows[$i]['rank_title'];
            $special_rank = $rank_rows[$i]['rank_special'];
            $rank_id = $rank_rows[$i]['rank_id'];
            $rank_min = $rank_rows[$i]['rank_min'];

            if($special_rank)
            {
                $rank_min = $rank_max = "-";
            }

            $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
            $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

            $template->assign_block_vars("ranks", array(
                "ROW_COLOR" => "#" . $row_color,
                "ROW_CLASS" => $row_class,
                "RANK" => $rank,
                "RANK_MIN" => $rank_min,

                "SPECIAL_RANK" => ( $special_rank == 1 ) ? $lang['Yes'] : $lang['No'],

                "U_RANK_EDIT" => append_sid("admin_ranks.$phpEx?mode=edit&amp;id=$rank_id"),
                "U_RANK_DELETE" => append_sid("admin_ranks.$phpEx?mode=delete&amp;id=$rank_id"))
            );
        }
    }
}
else
{
    //
    // Show the default page
    //
    $template->set_filenames(array(
        "body" => "ranks_list_body.tpl")
    );

    $sql = "SELECT * FROM " . RANKS_TABLE . "
        ORDER BY rank_min ASC, rank_special ASC";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
    }
    $rank_count = $db->sql_numrows($result);

    $rank_rows = $db->sql_fetchrowset($result);

    $template->assign_vars(array(
        "L_RANKS_TITLE" => $lang['Ranks_title'],
        "L_RANKS_TEXT" => $lang['Ranks_explain'],
        "L_RANK" => $lang['Rank_title'],
        "L_RANK_MINIMUM" => $lang['Rank_minimum'],
        "L_SPECIAL_RANK" => $lang['Rank_special'],
        "L_EDIT" => $lang['Edit'],
        "L_DELETE" => $lang['Delete'],
        "L_ADD_RANK" => $lang['Add_new_rank'],
        "L_ACTION" => $lang['Action'],

        "S_RANKS_ACTION" => append_sid("admin_ranks.$phpEx"))
    );

    for($i = 0; $i < $rank_count; $i++)
    {
        $rank = $rank_rows[$i]['rank_title'];
        $special_rank = $rank_rows[$i]['rank_special'];
        $rank_id = $rank_rows[$i]['rank_id'];
        $rank_min = $rank_rows[$i]['rank_min'];

        if( $special_rank == 1 )
        {
            $rank_min = $rank_max = "-";
        }

        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        $rank_is_special = ( $special_rank ) ? $lang['Yes'] : $lang['No'];

        $template->assign_block_vars("ranks", array(
            "ROW_COLOR" => "#" . $row_color,
            "ROW_CLASS" => $row_class,
            "RANK" => $rank,
            "SPECIAL_RANK" => $rank_is_special,
            "RANK_MIN" => $rank_min,

            "U_RANK_EDIT" => append_sid("admin_ranks.$phpEx?mode=edit&amp;id=$rank_id"),
            "U_RANK_DELETE" => append_sid("admin_ranks.$phpEx?mode=delete&amp;id=$rank_id"))
        );
    }
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
