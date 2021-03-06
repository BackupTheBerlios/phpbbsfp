<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_blocks.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_blocks.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team
//           : � 2004		Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['Blocks']['Placement'] = $file;
    return;
}

define('IN_PHPBB', 1);

//
// Load default header
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

function fix_weight_blocks($l_id)
{
    global $db, $cache;

    $sql = "SELECT DISTINCT bposition FROM " . BLOCKS_TABLE . " WHERE layout = '" . $l_id . "'";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
    }
    $rows = $db->sql_fetchrowset($result);
    $count = count($rows);

    for($i = 0; $i < $count; $i++)
    {
        $sql = "SELECT bid FROM ". BLOCKS_TABLE . " WHERE layout = '" . $l_id . "' AND bposition = '" . $rows[$i]['bposition'] . "' ORDER BY weight ASC";
        if( !$result1 = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Could not query blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
        }
        $weight = 0;
        while( $row = $db->sql_fetchrow($result1) )
        {
            $weight++;
            $sql = "UPDATE " . BLOCKS_TABLE . " SET weight = '" . $weight . "' WHERE bposition = '" . $rows[$i]['bposition'] . "' AND bid = '" . $row['bid'] . "'";
            if( !$result2 = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Could not update blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
        }
    }
}

function generate_smilies2($mode, $page_id)
{
    global $db, $cache, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path, $images;
    global $user_ip, $session_length, $starttime;
    global $userdata;

    $inline_columns = 4;
    $inline_rows = 5;
    $window_columns = 8;

    if ( $mode == 'window' )
    {
        $userdata = session_pagestart($user_ip, $page_id);
        init_userprefs($userdata);

        $gen_simple_header = TRUE;

        $page_title = $lang['Review_topic'] . " - Add Blocks";
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);

        $template->set_filenames(array(
            'smiliesbody' => 'posting_smilies.tpl')
        );
    }

	if ($cache->exists('smilies'))
	{
		$rowset = $cache->get('smilies');
		$num_smilies = count($rowset);
	}
	else
	{
	    $sql = "SELECT *
	        FROM " . SMILIES_TABLE . "
	        ORDER BY smilies_id";
	    if ($result = $db->sql_query($sql))
	    {

	        $num_smilies = 0;
	        $rowset = array();
			$rowset2 = array();

			while ($row = $db->sql_fetchrow($result))
			{
				if (!isset($rowset2[$row['smile_url']]))
				{
					$rowset[$row['smilies_id']]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
					$rowset[$row['smilies_id']]['emoticon'] = $row['emoticon'];
					$rowset[$row['smilies_id']]['smile_url'] = $row['smile_url'];
					$rowset2[$row['smile_url']] = TRUE;
					$num_smilies++;
				}
			}

			unset($rowset2);

			$cache->put('smilies', $rowset);
		}
	}


    if ( $num_smilies )
    {
        $smilies_count = ( $mode == 'inline' ) ? min(19, $num_smilies) : $num_smilies;
        $smilies_split_row = ( $mode == 'inline' ) ? $inline_columns - 1 : $window_columns - 1;

        $s_colspan = 0;
        $row = 0;
        $col = 0;

        while ( list($smile_url, $data) = @each($rowset) )
        {
            if ( !$col )
            {
                $template->assign_block_vars('smilies_row', array());
            }

            $template->assign_block_vars('smilies_row.smilies_col', array(
                'SMILEY_CODE' => $data['code'],
                'SMILEY_IMG' => '../' . $images['smiles'] . '/' . $data['smile_url'],
                'SMILEY_DESC' => $data['emoticon'])
            );

            $s_colspan = max($s_colspan, $col + 1);

            if ( $col == $smilies_split_row )
            {
                if ( $mode == 'inline' && $row == $inline_rows - 1 )
                {
                    break;
                }
                $col = 0;
                $row++;
            }
            else
            {
                $col++;
            }
        }

        if ( $mode == 'inline' && $num_smilies > $inline_rows * $inline_columns )
        {
            $template->assign_block_vars('switch_smilies_extra', array());

            $template->assign_vars(array(
                'L_MORE_SMILIES' => $lang['More_emoticons'],
//              'U_MORE_SMILIES' => append_sid("../posting.$phpEx?mode=smilies"))
                'U_MORE_SMILIES' => append_sid("admin_blocks.$phpEx?mode=smilies"))
            );
        }

        $template->assign_vars(array(
            'L_EMOTICONS' => $lang['Emoticons'],
            'L_CLOSE_WINDOW' => $lang['Close_window'],
            'S_SMILIES_COLSPAN' => $s_colspan)
        );
    }

    if ( $mode == 'window' )
    {
        $template->pparse('smiliesbody');

        include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
    }
}

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

if(isset($HTTP_POST_VARS['cancel']))
{
    $mode="blocks";
}

if ( $mode == 'smilies' )
{
    generate_smilies2('window', PAGE_POSTING);
    exit;
}

if( $mode != "" && $mode != "blocks" )
{
    if( $mode == "edit" || $mode == "add" )
    {
        $b_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
        if( isset($HTTP_POST_VARS['lid']) ||  isset($HTTP_GET_VARS['lid']) )
        {
            $l_id = ( isset($HTTP_POST_VARS['lid']) ) ? intval($HTTP_POST_VARS['lid']) : intval($HTTP_GET_VARS['lid']);
        }
        else
        {
            $l_id = 0;
        }

        $template->set_filenames(array(
            "body" => "blocks_edit_body.tpl")
        );

        // Generate smilies listing for page output
        generate_smilies2('inline', PAGE_POSTING);


        $s_hidden_fields .= '<input type="hidden" name="lid" value="' . $l_id . '" />';

        $message='';

        //$sql = "SELECT forum_wide FROM " . BLOCKS_LAYOUT_TABLE . " WHERE lid ='" . $l_id . "'";
        //if( !$result = $db->sql_query($sql) )
        //{
        //    message_die(GENERAL_ERROR, "Could not query layout table", $lang['Error'], __LINE__, __FILE__, $sql);
        //}
        //$l_row = $db->sql_fetchrow($result);

        //$temp_layout = ($l_row['forum_wide']) ? "'" . $l_id . "','0'" : $temp_layout = "'" . $l_id . "'";

        $temp_layout = "'" . $l_id . "','0'";


        if( $mode == "edit" )
        {
            if( $b_id )
            {
                $sql = "SELECT *
                    FROM " . BLOCKS_TABLE . "
                    WHERE bid = $b_id";
                if(!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not query blocks table", "Error", __LINE__, __FILE__, $sql);
                }

                $b_info = $db->sql_fetchrow($result);
                $s_hidden_fields .= '<input type="hidden" name="id" value="' . $b_id . '" />';

                $sql = "SELECT pkey, bposition FROM " . BLOCKS_POSITION_TABLE . " WHERE layout IN (" . $temp_layout . ") ORDER BY layout, bpid";
                if( !($result = $db->sql_query($sql)) )
                {
                    message_die(CRITICAL_ERROR, "Could not query blocks position information", "", __LINE__, __FILE__, $sql);
                }

                while ($row = $db->sql_fetchrow($result))
                {
                    $position .= '<option value="' . $row['bposition'] . '" ';
                    if($b_info['bposition']==$row['bposition'])
                    {
                        $position .= 'selected';
                    }
                    $position .= '>' . $row['pkey'];
                }

                $block_dir = $phpbb_root_path .'blocks';
                $blocks = opendir($block_dir);

                $blockfile='<option value="">-- None --';
                while ($file = readdir($blocks))
                {
                    $pos = strpos($file, "blocks_imp_");
                    if ($pos==0 && $pos!==false)
                    {
                        $pos = strpos($file, ".".$phpEx);
                        if ($pos!==false)
                        {
                            $temp = ereg_replace("\.".$phpEx,"",$file);
                            $temp1 = ereg_replace('blocks_imp_','',$temp);
                            $temp1 = ereg_replace('_',' ',$temp1);
                            $blockfile .= '<option value="' . $temp .'" ';
                            if($b_info['blockfile']==$temp)
                            {
                                $blockfile .= 'selected';
                            }
                            $blockfile .= '>' . $temp1;
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
                    $view .= '<option value="' . $i .'" ';
                    if($b_info['view']==$i)
                    {
                        $view .= 'selected';
                    }
                    $view .= '>' . $view_array[$i];
                }

                $message = $b_info['content'];
                if ( $b_info['block_bbcode_uid'] != '' )
                {
                    $message = preg_replace('/\:(([a-z0-9]:)?)' . $b_info['block_bbcode_uid'] . '/s', '', $message);
                }

                $message = str_replace('<', '&lt;', $message);
                $message = str_replace('>', '&gt;', $message);
                $message = str_replace('<br />', "\n", $message);

                $sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
                if( !($result = $db->sql_query($sql)) )
                {
                    message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
                }
                $group_array = explode(",", $b_info['groups']);
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
                message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
            }
        }
        else
        {
            $sql = "SELECT pkey, bposition FROM " . BLOCKS_POSITION_TABLE . " WHERE layout IN (". $temp_layout .") ORDER BY layout, bpid";
            if( !($result = $db->sql_query($sql)) )
            {
                message_die(CRITICAL_ERROR, "Could not query blocks position information", "", __LINE__, __FILE__, $sql);
            }
            $position='';
            while ($row = $db->sql_fetchrow($result))
            {
                $position .= '<option value="' . $row['bposition'] . '">' . $row['pkey'];
            }

            $block_dir = $phpbb_root_path .'blocks';
            $blocks = opendir($block_dir);

            $blockfile='<option value="">-- None --';
            while ($file = readdir($blocks))
            {
                $pos = strpos($file, "blocks_imp_");
                if ($pos==0 && $pos!==false)
                {
                    $pos = strpos($file, ".".$phpEx);
                    if ($pos!==false)
                    {
                        $temp = ereg_replace("\.".$phpEx,"",$file);
                        $temp1 = ereg_replace('blocks_imp_','',$temp);
                        $temp1 = ereg_replace('_',' ',$temp1);
                        $blockfile .= '<option value="' . $temp .'">' . $temp1;
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
            if(empty($group))
            {
                $group = '&nbsp;&nbsp;None';
            }
        }

        $template->assign_vars(array(
            "L_BLOCKS_TITLE" => $lang['Blocks_Title'],
            "L_BLOCKS_TEXT" => $lang['Blocks_Explain'],
            "L_B_TITLE" => $lang['B_Title'],
            "L_B_POSITION" => $lang['B_Position'],
            "L_B_ACTIVE" => $lang['B_Active'],
            "L_B_CONTENT" => $lang['B_Content'],
            "L_B_HTML" => $lang['B_HTML'],
            "L_B_BBCODE" => $lang['B_BBCode'],
            "L_B_TYPE" => $lang['B_Type'],
            "L_B_BLOCK_FILE" => $lang['B_Blockfile'],
            "L_B_VIEW_BY" => $lang['B_View'],
            "L_B_BORDER" => $lang['B_Border'],
            "L_B_TITLEBAR" => $lang['B_Titlebar'],
            "L_B_LOCAL" => $lang['B_Local'],
            "L_B_BACKGROUND" => $lang['B_Background'],
            "L_B_GROUP" => $lang['B_Groups'],
            "L_YES" => $lang['Yes'],
            "L_NO" => $lang['No'],
            "TITLE" => $b_info['title'],
            "POSITION" => $position,
            "ACTIVE" => ( $b_info['active'] ) ? "checked=\"checked\"" : "",
            "NOT_ACTIVE" => ( !$b_info['active'] ) ? "checked=\"checked\"" : "",
            "HTML" => ( !$b_info['type'] ) ? "checked=\"checked\"" : "",
            "BBCODE" => ( $b_info['type'] ) ? "checked=\"checked\"" : "",
            "CONTENT" => $message,
            "BLOCKFILE" => $blockfile,
            "VIEWBY" => $view,
            "BORDER" => ( $b_info['border'] ) ? "checked=\"checked\"" : "",
            "NO_BORDER" => ( !$b_info['border'] ) ? "checked=\"checked\"" : "",
            "TITLEBAR" => ( $b_info['titlebar'] ) ? "checked=\"checked\"" : "",
            "NO_TITLEBAR" => ( !$b_info['titlebar'] ) ? "checked=\"checked\"" : "",
            "LOCAL" => ( $b_info['local'] ) ? "checked=\"checked\"" : "",
            "NOT_LOCAL" => ( !$b_info['local'] ) ? "checked=\"checked\"" : "",
            "BACKGROUND" => ( $b_info['background'] ) ? "checked=\"checked\"" : "",
            "NO_BACKGROUND" => ( !$b_info['background'] ) ? "checked=\"checked\"" : "",
            "GROUP" => $group,

            'L_BBCODE_B_HELP' => $lang['bbcode_b_help'],
            'L_BBCODE_I_HELP' => $lang['bbcode_i_help'],
            'L_BBCODE_U_HELP' => $lang['bbcode_u_help'],
            'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'],
            'L_BBCODE_C_HELP' => $lang['bbcode_c_help'],
            'L_BBCODE_L_HELP' => $lang['bbcode_l_help'],
            'L_BBCODE_O_HELP' => $lang['bbcode_o_help'],
            'L_BBCODE_P_HELP' => $lang['bbcode_p_help'],
            'L_BBCODE_W_HELP' => $lang['bbcode_w_help'],
            'L_BBCODE_A_HELP' => $lang['bbcode_a_help'],
            'L_BBCODE_S_HELP' => $lang['bbcode_s_help'],
            'L_BBCODE_F_HELP' => $lang['bbcode_f_help'],
            'L_EMPTY_MESSAGE' => $lang['Empty_message'],

            // Start add - BBCodes & smilies enhancement MOD
            'L_BBCODE_URL' => $lang['bbcode_url'],
            'L_BBCODE_URL_TITLE' => $lang['bbcode_url_title'],
            'L_BBCODE_URL_EMPTY' => $lang['bbcode_url_empty'],
            'L_BBCODE_URL_TITLE_EMPTY' => $lang['bbcode_url_title_empty'],
            'L_BBCODE_URL_ERRORS' => $lang['bbcode_url_errors'],
            // End add - BBCodes & smilies enhancement MOD

            'L_FONT_COLOR' => $lang['Font_color'],
            'L_COLOR_DEFAULT' => $lang['color_default'],
            'L_COLOR_DARK_RED' => $lang['color_dark_red'],
            'L_COLOR_RED' => $lang['color_red'],
            'L_COLOR_ORANGE' => $lang['color_orange'],
            'L_COLOR_BROWN' => $lang['color_brown'],
            'L_COLOR_YELLOW' => $lang['color_yellow'],
            'L_COLOR_GREEN' => $lang['color_green'],
            'L_COLOR_OLIVE' => $lang['color_olive'],
            'L_COLOR_CYAN' => $lang['color_cyan'],
            'L_COLOR_BLUE' => $lang['color_blue'],
            'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
            'L_COLOR_INDIGO' => $lang['color_indigo'],
            'L_COLOR_VIOLET' => $lang['color_violet'],
            'L_COLOR_WHITE' => $lang['color_white'],
            'L_COLOR_BLACK' => $lang['color_black'],

            'L_FONT_SIZE' => $lang['Font_size'],
            'L_FONT_TINY' => $lang['font_tiny'],
            'L_FONT_SMALL' => $lang['font_small'],
            'L_FONT_NORMAL' => $lang['font_normal'],
            'L_FONT_LARGE' => $lang['font_large'],
            'L_FONT_HUGE' => $lang['font_huge'],

            'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
            'L_STYLES_TIP' => $lang['Styles_tip'],

            "L_EDIT_BLOCK" => $lang['Block_Edit'],
            "L_SUBMIT" => $lang['Submit'],
            "S_BLOCKS_ACTION" => append_sid("admin_blocks.$phpEx"),
            "S_HIDDEN_FIELDS" => $s_hidden_fields)
        );

        $template->pparse("body");

        include('./page_footer_admin.'.$phpEx);
    }
    else if( $mode == "save" )
    {
        $b_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
        $l_id = ( isset($HTTP_POST_VARS['lid']) ) ? intval($HTTP_POST_VARS['lid']) : 0;
        $b_title = ( isset($HTTP_POST_VARS['title']) ) ? trim($HTTP_POST_VARS['title']) : "";
        $b_bposition = ( isset($HTTP_POST_VARS['bposition']) ) ? trim($HTTP_POST_VARS['bposition']) : "";
        $b_active = ( isset($HTTP_POST_VARS['active']) ) ? intval($HTTP_POST_VARS['active']) : 0;
        $b_type = ( isset($HTTP_POST_VARS['type']) ) ? intval($HTTP_POST_VARS['type']) : 0;
        $b_content = ( isset($HTTP_POST_VARS['message']) ) ? trim($HTTP_POST_VARS['message']) : "";
        $b_blockfile = ( isset($HTTP_POST_VARS['blockfile']) ) ? trim($HTTP_POST_VARS['blockfile']) : "";
        $b_view = ( isset($HTTP_POST_VARS['view']) ) ? trim($HTTP_POST_VARS['view']) : 0;
        $b_border = ( isset($HTTP_POST_VARS['border']) ) ? intval($HTTP_POST_VARS['border']) : 0;
        $b_titlebar = ( isset($HTTP_POST_VARS['titlebar']) ) ? intval($HTTP_POST_VARS['titlebar']) : 0;
        $b_local = ( isset($HTTP_POST_VARS['local']) ) ? intval($HTTP_POST_VARS['local']) : 0;
        $b_background = ( isset($HTTP_POST_VARS['background']) ) ? intval($HTTP_POST_VARS['background']) : 0;

        $sql = "SELECT MAX(group_id) max_group_id FROM " . GROUPS_TABLE . " WHERE group_single_user = 0";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
        }
        $row = $db->sql_fetchrow($result);
        $b_group = '';
        $not_first = FALSE;
        for($i = 1; $i <= $row['max_group_id']; $i++)
        {
            if(isset($HTTP_POST_VARS['group' . strval($i)]))
            {
                if($not_first)
                {
                    $b_group .= ',' . strval($i);
                }else
                {
                    $b_group .= strval($i);
                    $not_first = TRUE;
                }
            }
        }

        $layout = $l_id;

        if($b_title == "")
        {
            message_die(GENERAL_MESSAGE, $lang['Must_enter_block']);
        }

        $bbcode_uid = '';
        if($b_type)
        {
            if(!empty($b_content))
            {
                $bbcode_uid = $bbcode_parse->make_bbcode_uid();
                $b_content = prepare_message(trim($b_content), TRUE, TRUE, TRUE, $bbcode_uid);
                $b_content = str_replace("\'", "''", $b_content);
            }
        }

        if( $b_id )
        {
            $sql = "UPDATE " . BLOCKS_TABLE . "
                SET
                title = '" . str_replace("\'", "''", $b_title) . "',
                bposition = '" . str_replace("\'", "''", $b_bposition) . "',
                active = '" . $b_active . "',
                type = '" . $b_type . "',
                content = '" . $b_content . "',
                block_bbcode_uid = '" . $bbcode_uid . "',
                blockfile = '" . str_replace("\'", "''", $b_blockfile) . "',
                layout = '" . $layout . "',
                view = '" . $b_view . "',
                border = '" . $b_border . "',
                titlebar = '" . $b_titlebar . "',
                local = '" . $b_local . "',
                background = '" . $b_background . "',
                groups = '" . $b_group . "'
                WHERE bid = $b_id";

            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not insert data into blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
            $message = $lang['Block_updated'];

            if(!empty($b_blockfile))
            {
                if(file_exists($phpbb_root_path .'/blocks/' . $b_blockfile . '.cfg'))
                {
                    include($phpbb_root_path .'/blocks/' . $b_blockfile . '.cfg');

                    $message .= '<br /><br />' . $lang['B_BV_added'];

                    for($i = 0; $i < $block_count_variables; $i++)
                    {
                        $sql = "SELECT count(1) existing FROM " . BLOCKS_VARIABLE_TABLE . "
                            WHERE config_name = '" . $block_variables[$i][2] . "'";
                        if(!$result = $db->sql_query($sql))
                        {
                            message_die(GENERAL_ERROR, "Could not query information from block variable table", $lang['Error'], __LINE__, __FILE__, $sql);
                        }
                        $row = $db->sql_fetchrow($result);

                        if(!$row['existing'])
                        {
                            $sql = "INSERT INTO " . BLOCKS_VARIABLE_TABLE . " (label, sub_label, config_name, field_options, field_values, type, block)
                                VALUES ('" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
                            if(!$result = $db->sql_query($sql))
                            {
                                message_die(GENERAL_ERROR, "Could not insert data into block variable table", $lang['Error'], __LINE__, __FILE__, $sql);
                            }

                            $sql = "INSERT INTO " . BLOCKS_CONFIG_TABLE . " (config_name, config_value)
                                VALUES ('" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][7]) . "')";
                            if(!$result = $db->sql_query($sql))
                            {
                                message_die(GENERAL_ERROR, "Could not insert data into portal config table", $lang['Error'], __LINE__, __FILE__, $sql);
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $sql = "SELECT max(weight) mweight FROM " . BLOCKS_TABLE . " WHERE layout ='" . $l_id . "' AND bposition ='" . $b_bposition . "'";
            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not query from blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }

            $row = $db->sql_fetchrow($result);
            $weight=$row['mweight'];

            $sql = "INSERT INTO " . BLOCKS_TABLE . " (title, content, bposition, weight, active, type, blockfile, view, layout, block_bbcode_uid, border, titlebar, background, local, groups) VALUES ('" . str_replace("\'", "''", $b_title) . "', '" . $b_content . "', '" . str_replace("\'", "''", $b_bposition) . "', '" . $weight . "', '" . $b_active . "', '" . $b_type . "', '" . str_replace("\'", "''", $b_blockfile) . "', '" . $b_view . "', '" . $layout . "', '" . $bbcode_uid . "', '" . $b_border . "', '" . $b_titlebar . "', '" . $b_background . "', '" . $b_local . "', '" . $b_group . "')";
            $message = $lang['Block_added'];
            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not insert data into blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }

            if(!empty($b_blockfile))
            {
                if(file_exists($phpbb_root_path .'/blocks/' . $b_blockfile . '.cfg'))
                {
                    include($phpbb_root_path .'/blocks/' . $b_blockfile . '.cfg');

                    $message .= '<br /><br />' . $lang['B_BV_added'];

                    for($i = 0; $i < $block_count_variables; $i++)
                    {
                        $sql = "SELECT count(1) existing FROM " . BLOCKS_VARIABLE_TABLE . "
                            WHERE config_name = '" . $block_variables[$i][2] . "'";
                        if(!$result = $db->sql_query($sql))
                        {
                            message_die(GENERAL_ERROR, "Could not query information from block variable table", $lang['Error'], __LINE__, __FILE__, $sql);
                        }
                        $row = $db->sql_fetchrow($result);

                        if(!$row['existing'])
                        {
                            $sql = "INSERT INTO " . BLOCKS_VARIABLE_TABLE . " (label, sub_label, config_name, field_options, field_values, type, block)
                                VALUES ('" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
                            if(!$result = $db->sql_query($sql))
                            {
                                message_die(GENERAL_ERROR, "Could not insert data into block variable table", $lang['Error'], __LINE__, __FILE__, $sql);
                            }

                            $sql = "INSERT INTO " . BLOCKS_CONFIG_TABLE . " (config_name, config_value)
                                VALUES ('" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][7]) . "')";
                            if(!$result = $db->sql_query($sql))
                            {
                                message_die(GENERAL_ERROR, "Could not insert data into portal config table", $lang['Error'], __LINE__, __FILE__, $sql);
                            }
                        }
                    }
                }
            }
        }
        fix_weight_blocks($layout);

		$cache->destroy('portal_config');

        $message .= "<br /><br />" . sprintf($lang['Click_return_blocksadmin'], "<a href=\"" . append_sid("admin_blocks.$phpEx?mode=blocks&amp;id=$l_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
        message_die(GENERAL_MESSAGE, $message);
    }
    else if( $mode == "delete" )
    {
        if( isset($HTTP_POST_VARS['bid']) ||  isset($HTTP_GET_VARS['bid']) )
        {
            $b_id = ( isset($HTTP_POST_VARS['bid']) ) ? intval($HTTP_POST_VARS['bid']) : intval($HTTP_GET_VARS['bid']);
        }
        else
        {
            $b_id = 0;
        }

        if( isset($HTTP_POST_VARS['id']) ||  isset($HTTP_GET_VARS['id']) )
        {
            $l_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
        }
        else
        {
            $l_id = 0;
        }

        if(!isset($HTTP_POST_VARS['confirm']))
        {
            $hidden_fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="bid" value="'.$b_id.'" /><input type="hidden" name="id" value="'.$l_id.'" />';

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

                "S_CONFIRM_ACTION" => append_sid("admin_blocks.$phpEx"),
                "S_HIDDEN_FIELDS" => $hidden_fields)
            );

            $template->pparse("confirm");
            exit();
        }else
        {
            if( $b_id )
            {
                $sql = "DELETE FROM " . BLOCKS_TABLE . "
                    WHERE bid = $b_id";

                if(!$result = $db->sql_query($sql))
                {
                    message_die(GENERAL_ERROR, "Could not remove data from blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
                }

                $message = $lang['Block_removed'] . "<br /><br />" . sprintf($lang['Click_return_blocksadmin'], "<a href=\"" . append_sid("admin_blocks.$phpEx?mode=blocks&amp;id=$l_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

                message_die(GENERAL_MESSAGE, $message);
            }
            else
            {
                message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
            }
            fix_weight_blocks($l_id);
            fix_weight_blocks(0);
        }
    }
}
else if ($mode == "blocks")
{
    $template->set_filenames(array(
        "body" => "blocks_list_body.tpl")
    );

    if( isset($HTTP_POST_VARS['id']) ||  isset($HTTP_GET_VARS['id']) )
    {
        $l_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
    }
    else
    {
        $l_id = 0;
    }
    $move = ( isset($HTTP_GET_VARS['move']) ) ? $HTTP_GET_VARS['move'] : -1;

    if( $move == '0' || $move == '1' )
    {
        $b_id = ( isset($HTTP_GET_VARS['bid']) ) ? intval($HTTP_GET_VARS['bid']) : 0;
        $b_weight = ( isset($HTTP_GET_VARS['weight']) ) ? $HTTP_GET_VARS['weight'] : 0;
        $b_position = ( isset($HTTP_GET_VARS['pos']) ) ? $HTTP_GET_VARS['pos'] : 0;
        //if($b_position == '@' || $b_position == '*')
        //{
        //    $layout = 0;
        //}else
        //{
            $layout = $l_id;
        //}
        if( ($move == '1' && $b_weight != '1' && $b_weight != '0') || ( $move == '0' && $b_weight != '0' ) )
        {
            if( $move == '1')
            {
                $temp = $b_weight-1;
            }else
            {
                $temp = $b_weight+1;
            }
            $sql = "UPDATE " . BLOCKS_TABLE . " SET weight = '" . $b_weight . "' WHERE layout = '" . $layout . "' AND weight = '" . $temp . "' AND bposition = '" . $b_position . "'";
            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not update data in blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
            $sql = "UPDATE " . BLOCKS_TABLE . " SET weight = '" . $temp . "' WHERE bid = '" . $b_id . "'";
            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, "Could not update data in blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
            }
            fix_weight_blocks($layout);
        }
    }

    $sql = "SELECT name FROM " . BLOCKS_LAYOUT_TABLE . " WHERE lid ='" . $l_id . "'";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query layout table", $lang['Error'], __LINE__, __FILE__, $sql);
    }
    $l_row = $db->sql_fetchrow($result);
    $l_name = $l_row['name'];

    //$temp_layout = ($l_row['forum_wide']) ? "'" . $l_id . "','0'" : $temp_layout = "'" . $l_id . "'";

    $temp_layout = "'" . $l_id . "','0'";


    $sql = "SELECT * FROM " . BLOCKS_TABLE . " WHERE layout in (". $temp_layout .") ORDER BY bposition, weight";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query blocks table", $lang['Error'], __LINE__, __FILE__, $sql);
    }

    $b_rows = $db->sql_fetchrowset($result);
    $b_count = count($b_rows);

    $s_hidden_fields .= '<input type="hidden" name="lid" value="' . $l_id . '" />';

    $template->assign_vars(array(
        "L_BLOCKS_TITLE" => $lang['Blocks_Title'],
        "L_BLOCKS_TEXT" => $lang['Blocks_Explain'],
        "L_B_TITLE" => $lang['B_Title'],
        "L_B_POSITION" => $lang['B_Position'],
        "L_B_ACTIVE" => $lang['B_Active'],
        "L_B_DISPLAY" => $lang['B_Display'],
        "L_B_TYPE" => $lang['B_Type'],
        "L_B_LAYOUT" => $lang['B_Layout'],
        "L_B_PAGE" => $lang['B_Page'],
        "LAYOUT_NAME" => $l_name,
        "PAGE" => strval($l_id),
        "L_B_VIEW_BY" => $lang['B_View'],
        "L_B_BORDER" => $lang['B_Border'],
        "L_B_TITLEBAR" => $lang['B_Titlebar'],
        "L_B_LOCAL" => $lang['B_Local'],
        "L_B_BACKGROUND" => $lang['B_Background'],
        "L_B_GROUPS" => $lang['B_Groups'],
        "L_ACTION" => $lang['Action'],
        "L_BLOCKS_ADD" => $lang['B_Add'],
        "L_EDIT" => $lang['Edit'],
        "L_DELETE" => $lang['Delete'],
        "L_MOVE_UP" => $lang['B_Move_Up'],
        "L_MOVE_DOWN" => $lang['B_Move_Down'],
        "S_BLOCKS_ACTION" => append_sid("admin_blocks.$phpEx"),
        "S_HIDDEN_FIELDS" => $s_hidden_fields)
    );

    $sql = "SELECT bposition, pkey FROM " . BLOCKS_POSITION_TABLE . " WHERE layout IN ('" . $l_id . "','0')";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query blocks position table", $lang['Error'], __LINE__, __FILE__, $sql);
    }
    $position = array();
    while ($row = $db->sql_fetchrow($result))
    {
        $position[$row['bposition']] = $row['pkey'];
    }

    for($i = 0; $i < $b_count; $i++)
    {

        $b_id = $b_rows[$i]['bid'];
        $b_weight = $b_rows[$i]['weight'];
        $b_position = $b_rows[$i]['bposition'];

        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        switch ($b_rows[$i]['view'])
        {
            case '0':
                $b_view = $lang['B_All'];
                break;
            case '1':
                $b_view = $lang['B_Guests'];
                break;
            case '2':
                $b_view = $lang['B_Reg'];
                break;
            case '3':
                $b_view = $lang['B_Mod'];
                break;
            case '4':
                $b_view = $lang['B_Admin'];
                break;
        }

        if(!empty($b_rows[$i]['groups']))
        {
            $sql = "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id in (" . $b_rows[$i]['groups'] . ")";
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
                }else
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


        $template->assign_block_vars("blocks", array(
            "ROW_COLOR" => "#" . $row_color,
            "ROW_CLASS" => $row_class,
            "TITLE" => $b_rows[$i]['title'],
            "POSITION" => $position[$b_position],
            "ACTIVE" => ($b_rows[$i]['active']) ? $lang['Yes'] : $lang['No'],
            "TYPE" => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCode'] : $lang['B_HTML']) : '',
            "BORDER" => ($b_rows[$i]['border']) ? $lang['Yes'] : $lang['No'],
            "TITLEBAR" => ($b_rows[$i]['titlebar']) ? $lang['Yes'] : $lang['No'],
            "LOCAL" => ($b_rows[$i]['local']) ? $lang['Yes'] : $lang['No'],
            "BACKGROUND" => ($b_rows[$i]['background']) ? $lang['Yes'] : $lang['No'],
            "GROUPS" => $groups,
            "CONTENT" => (empty($b_rows[$i]['blockfile'])) ? $lang['B_Text'] : $lang['B_File'],
            "VIEW" => $b_view,
            "U_EDIT" => append_sid("admin_blocks.$phpEx?mode=edit&amp;id=$b_id&amp;lid=$l_id"),
            "U_DELETE" => append_sid("admin_blocks.$phpEx?mode=delete&amp;bid=$b_id&amp;id=$l_id"),
            "U_MOVE_UP" => append_sid("admin_blocks.$phpEx?mode=blocks&amp;id=$l_id&amp;move=1&amp;bid=$b_id&amp;weight=$b_weight&amp;pos=$b_position"),
            "U_MOVE_DOWN" => append_sid("admin_blocks.$phpEx?mode=blocks&amp;id=$l_id&amp;move=0&amp;bid=$b_id&amp;weight=$b_weight&amp;pos=$b_position")
            )
        );
    }
}
else
{
    $template->set_filenames(array(
        "body" => "blocks_layout_list_body.tpl")
    );

    $sql = "SELECT lid, name
    FROM " . BLOCKS_LAYOUT_TABLE . "
        ORDER BY lid";
    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, "Could not query layout table", $lang['Error'], __LINE__, __FILE__, $sql);
    }

    $l_rows = $db->sql_fetchrowset($result);
    $l_count = count($l_rows);

    $template->assign_vars(array(
        "L_BLOCKS_TITLE" => $lang['Blocks_Title'],
        "L_BLOCKS_TEXT" => $lang['Blocks_Explain'],
        "L_CHOOSE_LAYOUT" => $lang['Choose_Layout'],
        "S_BLOCKS_ACTION" => append_sid("admin_blocks.$phpEx"),
        "S_HIDDEN_FIELDS" => '')
    );

    for($i = 0; $i < $l_count; $i++)
    {
        $l_name = $l_rows[$i]['name'];
        $l_id = $l_rows[$i]['lid'];

        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        $template->assign_block_vars("layout", array(
            "ROW_COLOR" => "#" . $row_color,
            "ROW_CLASS" => $row_class,
            "NAME" => $l_name,
            "U_LAYOUT" => append_sid("admin_blocks.$phpEx?mode=blocks&amp;id=$l_id")
            )
        );
    }
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
