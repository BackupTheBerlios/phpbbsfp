<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_blocks.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_blocks.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

function block_assign_var_from_handle($template_var, $handle)
{
    ob_start();
    $template_var->pparse($handle);
    $str = ob_get_contents();
    ob_end_clean();

    return $str;
}

function blocks_config_init(&$portal_config)
{
    global $db, $cache;
    if ($cache->exists('portal_config'))
    {
        $portal_config = $cache->get('portal_config');
    }
    else
    {
        $portal_config = array();
        $sql = "SELECT * FROM " . BLOCKS_CONFIG_TABLE;
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(CRITICAL_ERROR, "Could not query portal config table", "", __LINE__, __FILE__, $sql);
        }
        while ($row = $db->sql_fetchrow($result))
        {
            $portal_config[$row['config_name']] = $row['config_value'];
        }
        $cache->put('portal_config', $portal_config);
    }
}

function portal_blocks_view($type=true)
{
    global $userdata;

    if ($userdata['user_id'] == ANONYMOUS)
    {
        $bview = '(0,1)';
        $append = '01';
    }
    else
    {
        switch($userdata['user_level'])
        {
            case USER:
                $bview = '(0,2)';
                $append = '02';
                break;
            case MOD:
                $bview = '(0,2,3)';
                $append = '023';
                break;
            case ADMIN:
                $bview = '(0,1,2,3,4)';
                $append = '01234';
                break;
            default:
                $bview = '(0)';
                $append = '0';
        }
    }
    if(isset($type)){
        return $bview;
    }else{
        return $append;
	}
}

function block_groups($user_id)
{
    global $db;
    static $layout_groups;

    if(!isset($layout_groups))
    {
        $sql = "SELECT group_id FROM " . USER_GROUP_TABLE . " WHERE user_id = '" . $user_id . "' AND user_pending = 0";
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(CRITICAL_ERROR, "Could not query user group information", "", __LINE__, __FILE__, $sql);
        }

        $layout_groups = array();
        $i=0;
        while ($row = $db->sql_fetchrow($result))
        {
            $layout_groups[$i] = intval($row['group_id']);
            $i++;
        }
    }
    return $layout_groups;
}

function parse_blocks($layout, $type='')
{
    global $db, $template, $userdata, $phpbb_root_path, $phpEx, $board_config, $lang, $portal_config, $theme, $bbcode_parse;

    include_once( $phpbb_root_path . 'includes/bbcode.' . $phpEx );

	static $pos_array = array();

    $layout_pos=array();


	if ( !isset($pos_array[$layout]) )
	{
	    $sql_pos = 'SELECT * FROM ' . BLOCKS_POSITION_TABLE . " WHERE layout ='" . $layout . "'";

	    if( !($block_pos_result = $db->sql_query($sql_pos)) )
	    {
	        message_die(CRITICAL_ERROR, 'Could not query portal blocks position', '', __LINE__, __FILE__, $sql);
	    }

	    while ($block_pos_row = $db->sql_fetchrow($block_pos_result))
	    {
	        $layout_pos[$block_pos_row['bposition']] = $block_pos_row['pkey'];
	    }

	    $db->sql_freeresult($block_pos_result);

		$pos_array[$layout] = $layout_pos;
	}
	else
	{
		$layout_pos = $pos_array[$layout];
	}

    $block_info=array();

    $temp_type = $type;

    if($type=='top')
    {
        $temp_pos = 't';
    }
    else if($type=='left')
    {
        $temp_pos = 'l';
    }
    else if($type=='bottom')
    {
        $temp_pos = 'b';
    }
    else if($type=='right')
    {
        $temp_pos = 'r';
    }

    $sql = "SELECT *
            FROM " . BLOCKS_TABLE . "
            WHERE layout ='" . $layout . "'
            AND active = '1'
            AND view IN " . portal_blocks_view() . "
            AND bposition = '" . $temp_pos . "'
            ORDER BY weight";

    if( !($block_im_result = $db->sql_query($sql)) )
    {
        message_die(CRITICAL_ERROR, "Could not query portal blocks information", "", __LINE__, __FILE__, $sql);
    }

    $block_info = $db->sql_fetchrowset($block_im_result);

    $db->sql_freeresult($block_im_result);

    $block_count = count($block_info);

    // If this block position (t,l,r,b) has block in it then switch them on.

    if ($block_count <> 0)
    {
        $template->assign_var('S_' . strtoupper($type) . '_BLOCKS', TRUE);
    }

    for ($b_counter = 0; $b_counter < $block_count; $b_counter++)
    {
        $is_group_allowed = TRUE;
        if(!empty($block_info[$b_counter]['groups']))
        {
            $is_group_allowed = FALSE;
            $group_content = explode(",",$block_info[$b_counter]['groups']);
            for ($i = 0; $i < count($group_content); $i++)
            {
                if(in_array(intval($group_content[$i]), block_groups($userdata['user_id'])))
                {
                    $is_group_allowed = TRUE;
                }
            }
        }

        if(isset($is_group_allowed))
        {
            $position = $type;


            $lang_exist = FALSE;
            $block_name = str_replace('blocks_imp_','',$block_info[$b_counter]['blockfile']);
            if(file_exists('blocks/language/lang_' . $board_config['default_lang'] . '/lang_' . $block_name . '_block.' . $phpEx))
            {
                $lang_exist = TRUE;
                include($phpbb_root_path . 'blocks/language/lang_' . $board_config['default_lang'] . '/lang_' . $block_name . '_block.' . $phpEx);
            }
            if(!empty($block_info[$b_counter]['blockfile']))
            {

                $template->set_filenames(array(
                    $block_name . '_block' => $block_name . '_block.tpl')
                );
                $output_block='';
                include($phpbb_root_path . 'blocks/' . $block_info[$b_counter]['blockfile'] . '.' . $phpEx);

                $output_block = block_assign_var_from_handle($template, $block_name . '_block');

                $template->assign_block_vars($position . '_blocks_row',array(
                    'OUTPUT' => $output_block
                    )
                );

                if($block_info[$b_counter]['titlebar'] == 1)
                {
                    if(($lang_exist) && ($block_info[$b_counter]['local'] == 1))
                    {
                        $template->assign_block_vars($position . '_blocks_row.title',array(
                            'TITLE' => $lang['Title_' . $block_name]
                            )
                        );
                    }
                    else
                    {
                        $template->assign_block_vars($position . '_blocks_row.title',array(
                            'TITLE' => $block_info[$b_counter]['title']
                            )
                        );
                    }
                }

                if($block_info[$b_counter]['border'] == 1)
                {
                    $template->assign_block_vars($position . '_blocks_row.border','');
                }

                if($block_info[$b_counter]['background'] == 1)
                {
                    $template->assign_block_vars($position . '_blocks_row.background','');
                }
            }
            else
            {

                $text=$block_info[$b_counter]['content'];
                if($block_info[$b_counter]['type'])
                {
                    $text = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $text);
                    if ( $block_info[$b_counter]['block_bbcode_uid'] != '' )
                    {
                        $text = $bbcode_parse->bbencode_second_pass($text, $block_info[$b_counter]['block_bbcode_uid']);
                    }
                    $text = $bbcode_parse->make_clickable($text);
                    $text = $bbcode_parse->smilies_pass($text);

                    $text = str_replace("\n", "\n<br />\n", $text);

                    $text = $bbcode_parse->acronym_pass( $text );
                    $text = $bbcode_parse->smart_pass( $text );

                    $text = '<span class="postbody">' . $text . '</span>';
                }
                $template->assign_block_vars($position . '_blocks_row',array(
                    'OUTPUT' => $text
                    )
                );
                if($block_info[$b_counter]['titlebar'] == 1)
                {
                    if(($lang_exist) && ($block_info[$b_counter]['local'] == 1))
                    {
                        $template->assign_block_vars($position . '_blocks_row.title',array(
                            'TITLE' => $lang['Title_' . $block_name]
                            )
                        );
                    }
                    else
                    {
                        $template->assign_block_vars($position . '_blocks_row.title',array(
                            'TITLE' => $block_info[$b_counter]['title']
                            )
                        );
                    }
                }
                if($block_info[$b_counter]['border'] == 1)
                {
                    $template->assign_block_vars($position . '_blocks_row.border','');
                }
                if($block_info[$b_counter]['background'] == 1)
                {
                    $template->assign_block_vars($position . '_blocks_row.background','');
                }
            }
        }
    }
}
?>