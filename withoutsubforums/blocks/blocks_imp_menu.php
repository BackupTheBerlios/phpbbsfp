<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_menu.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_menu.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       OXPUS
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------


if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

if(!function_exists('imp_menu_block_func'))
{
    function imp_menu_block_func()
    {

        global $template, $portal_config, $db, $userdata, $images, $lang, $phpEx, $cache, $phpbb_root_path;

        include_once($phpbb_root_path . 'includes/functions_menu.'.$phpEx);

		// Get the Menus

		if ( $cache->exists('_menu_data') )
		{
			$menu_data = $cache->get('_menu_data');
		}
		else
		{
			$menu_data = array();

			$sql = "SELECT * FROM " . BOARD_LINKS_TABLE . " ORDER BY bl_dsort, bl_id";

            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
            }

			while ( $row = $db->sql_fetchrow($result) )
			{
				$menu_data[$row['bl_id']] = array();
				$menu_data[$row['bl_id']]['bl_img'] = $row['bl_img'];
				$menu_data[$row['bl_id']]['bl_name'] = $row['bl_name'];
				$menu_data[$row['bl_id']]['bl_parameter'] = $row['bl_parameter'];
				$menu_data[$row['bl_id']]['bl_link'] = $row['bl_link'];
				$menu_data[$row['bl_id']]['bl_level'] = $row['bl_level'];
				$menu_data[$row['bl_id']]['bl_dsort'] = $row['bl_dsort'];
			}

			$cache->put('_menu_data', $menu_data);

		}

        // Create the personal board menu

        if ( $userdata['session_logged_in'] )
        {
            $sql = "SELECT board_link as ID FROM " . USER_BOARD_LINKS_TABLE . "
                WHERE user_id = " . $userdata['user_id'] . "
                ORDER BY board_sort";

            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
            }

            $user_links_count = $db->sql_numrows($result);

			if ( $user_links_count > 0 )
			{
				$menus = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$menus[] = $row['ID'];
				}

				$db->sql_freeresult($result);
			}
        }

        $sqlwhereaccess = get_bllink_access();

        if ( !$userdata['session_logged_in'] || $user_links_count == 0 )
        {
			// Use the Default Menu

			if ( $cache->exists('default_menu') )
			{
				$menus = $cache->get('default_menu');
			}
			else
			{
	            /*
	            $sql = "SELECT bl_id as ID FROM " . BOARD_LINKS_TABLE . "
	                WHERE bl_level = " . ANONYMOUS . "
	                ORDER BY bl_dsort";

	            if ( !$result = $db->sql_query($sql) )
	            {
	                message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
	            }
	            */

	            if ( !$userdata['session_logged_in'] || $user_links_count == 0 )
				{
					$sql = "SELECT bl_id as ID FROM " . BOARD_LINKS_TABLE . "
							$sqlwhereaccess
							ORDER BY bl_dsort";

					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not read board menu for user', '', __LINE__, __FILE__, $sql);
					}
				}

				$menus = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$menus[] = $row['ID'];
				}

				$db->sql_freeresult($result);
			}
        }

		$board_menu_links = compile_menu($menu_data, $menus);

        $template->assign_vars(array(
            'BOARD_MENU' => $board_menu_links
            )
        );

    }


	function compile_menu($menu_data, $user_menu_data)
	{
		global $template, $portal_config, $db, $userdata, $images, $lang, $phpEx, $phpbb_root_path, $board_config;

		$images_root = $phpbb_root_path . $images['root'];

        $board_menu_links = '';
        $board_config['bl_seperator_content'] = str_replace('SPACE', '&nbsp;&nbsp;&nbsp;', $board_config['bl_seperator_content']);

		$i = 0;

		foreach ($user_menu_data as $menu_id)
		{
			$menu_item = $menu_data[$menu_id];

	        $board_menu_links .= ( $i % $board_config['bl_break'] ) ? '' : ( ( $i != 0 ) ? '<br />' : '');
	        $board_menu_links .= ( $i % $board_config['bl_break'] ) ? ( ( $board_config['bl_seperator'] == 1 ) ? '&nbsp;<img src="'.$board_config['bl_seperator_content'].'" border="0" />&nbsp;' : $board_config['bl_seperator_content'] ) : '';
	        $board_menu_links .= ( $menu_item['bl_img'] != '' ) ? '<span><img src="'. $images_root . $menu_item['bl_img'].'" border="0" alt="' . $lang[$menu_item['bl_name']] . '"  />&nbsp;' : '<span>';
	        if (substr($menu_item['bl_link'],0,10) !== 'javascript')
	        {
				$board_menu_links .= '<a href="'.append_sid($menu_item['bl_link'].'.'.$phpEx.(( $menu_item['bl_parameter'] != '') ? '?'.$menu_item['bl_parameter'] : ''));
			}
			else
			{
				$board_menu_links .= '<a href="'.$menu_item['bl_link'].(( $menu_item['bl_parameter'] != '') ? '?'.$menu_item['bl_parameter'] : '');
			}
	        $board_menu_links .= '" class="mainmenu" title="'.$lang[$menu_item['bl_name']].'">'.$lang[$menu_item['bl_name']].'</a></span><br />';

	        $i++;
		}
        $board_menu_links .= ( $userdata['user_id'] <> ANONYMOUS ) ? '<br /><div align="center"><span class="mainmenu">[ <a href="'.append_sid("board_menu_manager.$phpEx").'">'.$lang['Board_menu_manager'].'</a> ]</span></div>' : '';

		return $board_menu_links;
	}

}

imp_menu_block_func();
?>