<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_menu.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_menu.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004 		OXPUS
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

function get_menu_cat_names()
{
    global $db, $lang;

    // Prepage links from main language
    $arraykey = array();
    $arraylang = array();

    $arraylang = $lang;
    array_multisort($arraylang);

    foreach(array_keys(array_unique($arraylang)) as $key)
    {
        if ( substr($lang[$key],0,1) > chr(64) && substr($lang[$key],0,1) < chr(91) && !is_array($lang[$key]) && !strpos($lang[$key], '%s') && !strpos($lang[$key], '%d') )
        {
            $arraykey[] = str_replace('\n', '', $key);
        }
    }

    $cat_names = '<select name="cat_name">';
    for ( $i = 0; $i < count($arraykey); $i++)
    {
        $langnames = ( strlen($lang[$arraykey[$i]]) >= 31 ) ? substr($lang[$arraykey[$i]],0,30).'...' : $lang[$arraykey[$i]];
        $cat_names .= '<option value="'.($arraykey[$i]).'">'.$langnames.'</option>';
    }
    $cat_names .= '</select>';

    return $cat_names;
}

function get_menu_language_names()
{
    global $db, $lang;

    // Prepage links from main language
    $arraykey = array();
    $arraylang = array();

    $arraylang = $lang;
    array_multisort($arraylang);

    foreach(array_keys(array_unique($arraylang)) as $key)
    {
        if ( substr($lang[$key],0,1) > chr(64) && substr($lang[$key],0,1) < chr(91) && !is_array($lang[$key]) && !strpos($lang[$key], '%s') && !strpos($lang[$key], '%d') )
        {
            $arraykey[] = str_replace('\n', '', $key);
        }
    }

    $bl_names = '<select name="bl_name">';
    for ( $i = 0; $i < count($arraykey); $i++)
    {
        $langnames = ( strlen($lang[$arraykey[$i]]) >= 31 ) ? substr($lang[$arraykey[$i]],0,30).'...' : $lang[$arraykey[$i]];
        $bl_names .= '<option value="'.($arraykey[$i]).'">'.$langnames.'</option>';
    }
    $bl_names .= '</select>';

    return $bl_names;
}

function get_menu_images()
{
    global $db, $userdata, $phpbb_root_path, $images;

	$link_images_dir = get_bl_theme();

    $dir = @opendir($link_images_dir);

    while($file = @readdir($dir))
    {
        if( !@is_dir($link_images_dir . $file) && substr($file,0,1) != '.' )
        {
            $link_images[] = $file;
        }
    }
    @closedir($dir);

    sort($link_images);

    $bl_images = '<select name="bl_img"><option value="---"></option>';
    foreach ($link_images as $bl_image)
    {
        $bl_images .= '<option value="'.$bl_image.'">'.$bl_image.'</option>';
    }
    $bl_images .= '</select>';

    return $bl_images;
}

function get_bl_access()
{
    global $lang;

    // Prepare Access Levels
    $bl_levels = '<select name="bl_level">';
    $bl_levels .= '<option value="'.ANONYMOUS.'" SELECTED>'.$lang['Bl_guest'].'</option>';
    $bl_levels .= '<option value="'.USER.'">'.$lang['Bl_user'].'</option>';
    $bl_levels .= '<option value="'.MOD.'">'.$lang['Bl_mod'].'</option>';
    $bl_levels .= '<option value="'.ADMIN.'">'.$lang['Bl_admin'].'</option>';
    $bl_levels .= '</select>';

    return $bl_levels;
}

function get_bl_theme()
{
	global $db, $userdata, $board_config, $phpbb_root_path;

	$style_id = ( $userdata['user_style'] ) ? $userdata['user_style'] : $board_config['default_style'];
	// Prepare Images Drop Down
	$sql = "SELECT * FROM " . THEMES_TABLE . "
		WHERE themes_id = ".$style_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not read menu images', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$themepath = $row['template_name'];
	}

	return $phpbb_root_path.'templates/'.$themepath.'/images/';
}

function get_bllink_access()
{
    global $userdata;

	$user_level = ( !$userdata['session_logged_in'] || $userdata['user_id'] == ANONYMOUS ) ? ANONYMOUS : $userdata['user_level'];

    $sql_where = '';

	switch ($user_level)
    {
        case ANONYMOUS:
            $sql_where = 'WHERE bl_level = '.ANONYMOUS;
            break;
        case USER:
            $sql_where = 'WHERE bl_level IN ('.ANONYMOUS.', '.USER.')';
            break;
        case MOD:
            $sql_where = 'WHERE bl_level IN ('.ANONYMOUS.', '.USER.', '.MOD.')';
            break;
        case ADMIN:
            $sql_where = '';
            break;
        default:
            $sql_where = 'WHERE bl_level = '.ANONYMOUS;
            break;
    }

    return $sql_where;
}

function reorder_menu_links($position, $user_id = -99)
{
    global $db, $userdata;

    $user = ( $user_id == -99 ) ? $userdata['user_id'] : $user_id;

    if ( $position == 'board' )
    {
        $sql = "SELECT board_link FROM " . USER_BOARD_LINKS_TABLE . "
            WHERE user_id = $user
            ORDER BY board_sort";
        if( !$result = $db->sql_query ($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not reorder board menu links', '', __LINE__, __FILE__, $sql);
        }
        else
        {
            $j = 0;
            while ( $row = $db->sql_fetchrow($result) )
            {
                $board_correct_sort = $row['board_link'];
                $j += 10;
                $sql_updates = "UPDATE " . USER_BOARD_LINKS_TABLE . "
                        SET board_sort = $j
                        WHERE user_id = $user
                        AND board_link = $board_correct_sort";
                if( !$result_updates = $db->sql_query ($sql_updates) )
                {
                    message_die(GENERAL_ERROR, 'Could not reorder board menu links', '', __LINE__, __FILE__, $sql_updates);
                }
            }
        }
    }
    else if ( $position == 'portal' )
    {
        $sql = "SELECT portal_link FROM " . USER_PORTAL_LINKS_TABLE . "
            WHERE user_id = $user
            ORDER BY portal_sort";
        if( !$result = $db->sql_query ($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not reorder portal menu links', '', __LINE__, __FILE__, $sql);
        }
        else
        {
            $j = 0;
            while ( $row = $db->sql_fetchrow($result) )
            {
                $portal_correct_sort = $row['portal_link'];
                $j += 10;
                $sql_updates = "UPDATE " . USER_PORTAL_LINKS_TABLE . "
                        SET portal_sort = $j
                        WHERE user_id = $user
                        AND portal_link = $portal_correct_sort";
                if( !$result_updates = $db->sql_query ($sql_updates) )
                {
                    message_die(GENERAL_ERROR, 'Could not reorder portal menu links', '', __LINE__, __FILE__, $sql_updates);
                }
            }
        }
    }

    return;
}

?>