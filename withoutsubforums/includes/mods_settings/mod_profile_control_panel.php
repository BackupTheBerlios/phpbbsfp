<?php
//-- mod : add-on country flags for pcp ------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: mod_profile_control_panel.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mod_profile_control_panel.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

include_once($phpbb_root_path . 'includes/functions_mods_settings.' . $phpEx);

//-------------------------------------------
//
//  list of values for direct usage
//
//-------------------------------------------
$list_yes_no = array('Yes' => 1, 'No' => 0);
$list_yes_no_friend = array('Yes' => 1, 'No' => 0, 'Friend_only' => 2);

//-------------------------------------------
//
//  get all maps relative to profile_prefer and preferences
//
//-------------------------------------------
// first pass : get main maps
$w_maps = array();

if ( is_array($user_maps) )
{

	foreach($user_maps as $map_name => $map_data)
	{
	    $map_tree = explode('.', $map_name);
	    if ( ($map_tree[0] == 'PCP') && ($map_data['custom'] == 1) )
	    {
	        // get this map
	        $map_tree = explode('.', $map_name);
	        $w_maps['name'][] = $map_name;
	        $w_maps['depth'][] = count($map_tree)-1;
	    }
	}
	
	// second pass : get sub maps
	foreach($user_maps as $map_name => $map_data)
	{
	    for ( $i=0; $i < count($w_maps['name']); $i++ )
	    {
	        if ( substr($map_name, 0, strlen($w_maps['name'][$i])) == $w_maps['name'][$i] )
	        {
	            // we must stay within 3 sub levels
	            $map_tree = explode('.', $map_name);
	            if ( ( (count($map_tree) - 1 - $w_maps['depth'][$i]) < 3 ) && ( (count($map_tree) - 1 - $w_maps['depth'][$i]) > 0 ) )
	            {
	                // map name
	                $start = $w_maps['depth'][$i];
	                $map_root = '';
	                for ( $j=0; $j < $start; $j++ )
	                {
	                    if ( !empty($map_tree[$j]) )
	                    {
	                        $map_root .= ( empty($map_root) ? '' : '.' ) . $map_tree[$j];
	                    }
	                }
	
	                // get the menu name entries
	                $menu = array();
	                for ( $j=0; $j < 3; $j++ )
	                {
	                    $local_name = '';
	                    $local_sort = 0;
	                    if ( !empty($map_tree[ $j + $start ]) )
	                    {
	                        $map_root .= '.' . $map_tree[ $j + $start ];
	                        $local_name = pcp_get_mods_setting_menu('title', $map_root);
	                        if ( ($j==0) && in_array($w_maps['name'][$i], array('PCP.profil.Preferences', 'PCP.profil.profile_prefer')) )
	                        {
	                            $local_name = $map_tree[$start];
	                        }
	                        $local_sort = pcp_get_mods_setting_menu('order', $map_root);
	                    }
	                    $menu[$j]['name'] = $local_name;
	                    $menu[$j]['sort'] = $local_sort;
	                }
	
	                // init config table
	                $config_fields  = pcp_get_mods_setting_config_fields($map_name);
	                if ( !empty($config_fields) )
	                {
	                    init_board_config($menu[1]['name'], $config_fields, $menu[2]['name'], $menu[2]['sort'], $menu[1]['sort'], $menu[0]['name'], $menu[0]['sort']);
	                }
	                break;
	            }
	        }
	    }
	}

}

//-------------------------------------------
//
//  DATEFMT format service functions :
//  ---------------------------------
//      mods_settings_get_datefmt() : return the datefmt input fields definition
//      mods_settings_check_datefmt() : check and format the datefmt fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_datefmt'))
{
    function mods_settings_get_datefmt($field, $value)
    {
        global $board_config, $lang, $userdata;

        // define a set of date presentation
        $timeset = array(
            'D m d, Y g:i a',
            'D d-m-Y, G:i',
            'M Y, D d, g:i a',
            'D d M Y, G:i',
            'd M Y h:i a',
            'd M Y, G:i',
            'D M d, Y g:i a',
            'D M d, Y G:i',
        );

        // build the date format list
        $s_time = '<select name="timeformat" onChange="' . $field . '.value=this.options[this.selectedIndex].value;">';
        $time = time();
        $found = false;
        for ($i=0; $i < count($timeset); $i++)
        {
            $selected = ($value == $timeset[$i]) ? ' selected="selected"' : '';
            if ($selected != '') $found = true;
            $s_time .= '<option value="' . $timeset[$i] . '"' . $selected . '>' . create_date($timeset[$i], $time, $userdata['user_timezone']) . '</option>';
        }
        $selected = ( !$found ) ? ' selected="selected"' : '';
        $s_time .= '<option value=""' . $selected . '>' . $lang['Other'] . '</option></select>';

        $res = $s_time . '&nbsp;<input type="text" name="' . $field . '" value="' . $value . '" maxlength="14" class="post" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_datefmt'))
{
    function mods_settings_check_datefmt($field, $value)
    {
        global $error, $error_msg, $lang;

        $res = trim(str_replace("\'", "''", htmlspecialchars($value)));
        return $res;
    }
}

//-------------------------------------------
//
//  URL format service functions :
//  ---------------------------------
//      mods_settings_get_url() : return the url input fields definition
//      mods_settings_check_url() : check and format the url fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_url'))
{
    function mods_settings_get_url($field, $value)
    {
        global $board_config, $lang, $userdata;

        $res = '<input type="text" name="' . $field . '" value="' . $value . '" size="25" maxlength="255" class="post" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_url'))
{
    function mods_settings_check_url($field, $value)
    {
        global $error, $error_msg, $lang;

        $url = trim(str_replace("\'", "''", htmlspecialchars($value)));
        if ( !empty($url) )
        {
            if (!preg_match('#^http[s]?:\/\/#i', $url))
            {
                $url = 'http://' . $url;
            }

            if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $url))
            {
                $url = '';
                $error = true;
                $error_msg = (empty($error_msg) ? '' : '<br />') . $lang['Incomplete_URL'];
            }
        }
        return $url;
    }
}

//-------------------------------------------
//
//  BIRTHDAY format service functions :
//  ---------------------------------
//      mods_settings_get_birthday() : return the birthday input fields definition
//      mods_settings_check_birthday() : check and format the birthday fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_birthday'))
{
    function mods_settings_get_birthday($field, $value)
    {
        global $board_config, $lang, $userdata;

        $months = array(
            ' ------------ ',
            $lang['datetime']['January'],
            $lang['datetime']['February'],
            $lang['datetime']['March'],
            $lang['datetime']['April'],
            $lang['datetime']['May'],
            $lang['datetime']['June'],
            $lang['datetime']['July'],
            $lang['datetime']['August'],
            $lang['datetime']['September'],
            $lang['datetime']['October'],
            $lang['datetime']['November'],
            $lang['datetime']['December'],
        );

        $year = intval(substr($value, 0, 4));
        $month = intval(substr($value, 4, 2));
        $day = intval(substr($value, 6, 2));

        // day list
        $s_birthday_day = '';
        for ($i=0; $i <= 31; $i++)
        {
            $select = ( $day == $i ) ? ' selected="selected"' : '';
            $s_birthday_day .= '<option value="' . $i . '"' . $select . '>' . ( ($i == 0) ? ' -- ' : (($i < 10) ? '0' . $i : $i) ) . '</option>';
        }
        $s_birthday_day = sprintf('<select name="' . $field . '_day">%s</select>', $s_birthday_day);

        // month list
        $s_birthday_month = '';
        for ($i=0; $i <= 12; $i++)
        {
            $select = ( $month == $i ) ? ' selected="selected"' : '';
            $s_birthday_month .= '<option value="' . $i . '"' . $select . '>' . $months[$i] . '</option>';
        }
        $s_birthday_month = sprintf('<select name="' . $field . '_month">%s</select>', $s_birthday_month);

        // year list
        $s_birthday_year = '';
        $select = ( $year == 0 ) ? ' selected="selected"' : '';
        $s_birthday_year .= '<option value="0"' . $select . '> ---- </option>';
        for ($i=1930; $i <= date('Y', time()); $i++)
        {
            $select = ( $year == $i) ? ' selected="selected"' : '';
            $s_birthday_year .= '<option value="' . $i . '"' . $select . '>' . $i . '</option>';
        }
        $s_birthday_year = sprintf('<select name="' . $field . '_year">%s</select>', $s_birthday_year);

        $res = $s_birthday_day . $s_birthday_month . $s_birthday_year . '<input type="hidden" name="' . $field . '" value="' . $value . '" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_birthday'))
{
    function mods_settings_check_birthday($field, $value)
    {
        global $error, $error_msg, $lang;
        global $HTTP_POST_VARS;

        $day = intval($HTTP_POST_VARS[$field . '_day']);
        $month = intval($HTTP_POST_VARS[$field . '_month']);
        $year = intval($HTTP_POST_VARS[$field . '_year']);

        if (empty($day) || empty($month) || empty($year) ) return 0;

        $valid = checkdate($month, $day, $year);
        if (!$valid)
        {
            $res = 0;
            $error = true;
            $error_msg .= (empty($error_msg) ? '' : '<br />') . sprintf($lang['Date_error'], $day, $month, $year);
        }
        else
        {
            $res = $year * 10000 + $month * 100 + $day;
        }
        return $res;
    }
}

//-------------------------------------------
//
//  ICQ format service functions :
//  ---------------------------------
//      mods_settings_get_icq() : return the icq input fields definition
//      mods_settings_check_icq() : check and format the icq fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_icq'))
{
    function mods_settings_get_icq($field, $value)
    {
        global $board_config, $lang, $userdata;

        $res = '<input type="text" name="' . $field . '" value="' . $value . '" size="10" maxlength="15" class="post" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_icq'))
{
    function mods_settings_check_icq($field, $value)
    {
        global $error, $error_msg, $lang;

        $res = trim(str_replace("\'", "''", htmlspecialchars($value)));

        // ICQ number has to be only numbers.
        if (!preg_match('/^[0-9]+$/', $res))
        {
            $res = '';
        }
        return $res;
    }
}

//-------------------------------------------
//
//  MESSENGER format service functions :
//  -----------------------------------
//      mods_settings_get_messenger() : return the messengers input fields definition
//      mods_settings_check_messenger() : check and format the messengers fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_messenger'))
{
    function mods_settings_get_messenger($field, $value)
    {
        global $board_config, $lang, $userdata;

        $res = '<input type="text" name="' . $field . '" value="' . $value . '" size="20" maxlength="255" class="post" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_messenger'))
{
    function mods_settings_check_messenger($field, $value)
    {
        global $error, $error_msg, $lang;

        $res = trim(str_replace("\'", "''", htmlspecialchars($value)));
        return $res;
    }
}

//-------------------------------------------
//
//  MSNM format service functions :
//  ---------------------------------
//      mods_settings_get_msnm() : return the MSNM input fields definition
//      mods_settings_check_msnm() : check and format the MSNM fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_msnm'))
{
    function mods_settings_get_msnm($field, $value)
    {
        global $board_config, $lang, $userdata;

        $res = '<input type="text" name="' . $field . '" value="' . $value . '" size="20" maxlength="255" class="post" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_msnm'))
{
    function mods_settings_check_msnm($field, $value)
    {
        global $error, $error_msg, $lang, $db;

        $email = trim(str_replace("\'", "''", htmlspecialchars($value)));
        $banned = false;
        if (!empty($email))
        {
            if (preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email))
            {
                $sql = "SELECT ban_email
                    FROM " . BANLIST_TABLE;
                if ($result = $db->sql_query($sql))
                {
                    while ( ($row = $db->sql_fetchrow($result)) && !$banned)
                    {
                        $match_email = str_replace('*', '.*?', $row['ban_email']);
                        if (preg_match('/^' . $match_email . '$/is', $email))
                        {
                            $banned = true;
                        }
                    }
                }
                $db->sql_freeresult($result);
            }
        }
        if ($banned)
        {
            $email = '';
            $error = true;
            $error_msg = (empty($error_msg) ? '' : '<br />') .$lang['Email_banned'];
        }
        return $email;
    }
}

//-------------------------------------------
//
//  Delete a user service functions :
//  ---------------------------------
//      mods_settings_get_delete_user() : return the button input field
//      mods_settings_check_delete_user() : perform the delete
//
//-------------------------------------------
if (!function_exists('mods_settings_get_delete_user'))
{
    function mods_settings_get_delete_user($field, $value)
    {
        global $board_config, $lang, $userdata, $view_userdata;

        $res = '<input type="submit" name="' . $field . '" value="' . $lang['User_delete'] . '" class="liteoption" />';

        return $res;
    }
}

if (!function_exists('mods_settings_check_delete_user'))
{
    function mods_settings_check_delete_user($field, $value)
    {
        global $error, $error_msg, $lang, $db, $userdata, $view_userdata;

        // check auth
        if ( ($userdata['user_id'] == $view_userdata['user_id']) && ($view_userdata['user_level'] == ADMIN) )
        {
            $error = true;
            $error_msg = $lang['User_self_delete'];
        }
        else
        {
            $view_user_id = $view_userdata['user_id'];
            $replace_user_id = $userdata['user_id'];
            if ($replace_user_id == $view_user_id)
            {
                // get an admin user id
                $sql = "SELECT user_id FROM " . USERS_TABLE . " WHERE user_id <> $view_user_id AND user_level = " . ADMIN . " AND user_active = 1";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not obtain admin user information', '', __LINE__, __FILE__, $sql);
                }
                $row = $db->sql_fetchrow($sql);
                if ( empty($row['user_id']) )
                {
                    message_die(GENERAL_ERROR, 'Could not obtain another admin user');
                }
                else
                {
                    $replace_user_id = $row['user_id'];
                }
            }

            // single user group
            $sql = "SELECT g.group_id
                    FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
                    WHERE ug.user_id = $view_user_id
                        AND g.group_id = ug.group_id
                        AND g.group_single_user = 1";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);

            // poster name
            $username = str_replace("''", "'", $view_userdata['username'] );
            $username = str_replace("'", "\'", $username);
            $sql = "UPDATE " . POSTS_TABLE . "
                    SET poster_id = " . DELETED . ", post_username = '$username'
                    WHERE poster_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update posts for this user', '', __LINE__, __FILE__, $sql);
            }

            // topic poster name
            $sql = "UPDATE " . TOPICS_TABLE . "
                    SET topic_poster = " . DELETED . "
                    WHERE topic_poster = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update topics for this user', '', __LINE__, __FILE__, $sql);
            }

            // vote
            $sql = "UPDATE " . VOTE_USERS_TABLE . "
                    SET vote_user_id = " . DELETED . "
                    WHERE vote_user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
            }

            // multi-user groups
            $sql = "SELECT group_id
                    FROM " . GROUPS_TABLE . "
                    WHERE group_moderator = $view_user_id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not select groups where user was moderator', '', __LINE__, __FILE__, $sql);
            }
            while ( $row_group = $db->sql_fetchrow($result) )
            {
                $group_moderator[] = $row_group['group_id'];
            }
            if ( count($group_moderator) )
            {
                $update_moderator_id = implode(', ', $group_moderator);

                $sql = "UPDATE " . GROUPS_TABLE . "
                        SET group_moderator = $replace_user_id
                        WHERE group_moderator IN ($update_moderator_id)";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
                }
            }

            // groups
            $sql = "DELETE FROM " . GROUPS_TABLE . " WHERE group_id = " . $row['group_id'];
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
            }

            // auth
            $sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = " . $row['group_id'];
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
            }

            // topic subscribed
            $sql = "DELETE FROM " . TOPICS_WATCH_TABLE . " WHERE user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
            }

            // viewed topics
            $sql = "DELETE FROM " . TOPICS_VIEW_TABLE . " WHERE user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user from viewed topic table', '', __LINE__, __FILE__, $sql);
            }

            // banlist
            $sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_userid = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
            }

			//jnr. admin
            $sql = 'DELETE FROM ' . JR_ADMIN_TABLE . '
                WHERE user_id = ' . $view_user_id;
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user jnr. admin permissions', '', __LINE__, __FILE__, $sql);
            }

            //navigation menu manager
            $sql = 'DELETE FROM ' . USER_BOARD_LINKS_TABLE . '
                WHERE user_id = ' . $view_user_id;
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user navigation menu settings', '', __LINE__, __FILE__, $sql);
            }

            //buddys
            $sql = 'DELETE FROM ' . BUDDYS_TABLE . '
                WHERE
                	user_id = ' . $view_user_id .' OR
                	buddy_id = ' . $view_user_id;
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user buddy listings', '', __LINE__, __FILE__, $sql);
            }

            // prillian
            $sql = 'DELETE FROM ' . IM_PREFS_TABLE . '
                WHERE user_id = ' . $view_user_id;
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user IM prefs', '', __LINE__, __FILE__, $sql);
            }

            // privmsg
            $sql = "SELECT instmsgs_id
                    FROM " . INSTMSGS_TABLE . "
                    WHERE instmsgs_from_userid = $view_user_id
                        OR instmsgs_to_userid = $view_user_id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
            }

            // This little bit of code directly from the private messaging section.
            while ( $row_instmsgs = $db->sql_fetchrow($result) )
            {
                $privmsg_list[] = $row_instmsgs['instmsgs_id'];
            }
            if ( count($privmsg_list) > 0 )
            {
                $delete_sql_id = implode(', ', $privmsg_list);

                $sql = "DELETE FROM " . INSTMSGS_TEXT_TABLE . " WHERE instmsgs_text_id IN ($delete_sql_id)";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $sql);
                }

                $sql = "DELETE FROM " . INSTMSGS_TABLE . " WHERE instmsgs_id IN ($delete_sql_id)";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $sql);
                }
            }

			//apm folders
            $sql = "DELETE FROM " . PRIVMSGA_FOLDERS_TABLE . "
                WHERE folder_user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete users private message folders table', '', __LINE__, __FILE__, $sql);
            }

            //apm rules
            $sql = "DELETE FROM " . PRIVMSGA_RULES_TABLE . "
                WHERE rules_user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete users private message rules table', '', __LINE__, __FILE__, $sql);
            }

            //apm messages

            // get the pm recipient data for the user_id we are deleting
			$sql = "SELECT pr.* FROM " . PRIVMSGA_RECIPS_TABLE . " AS pr
                		WHERE pr.privmsg_user_id = " . $view_user_id;
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Can\'t read pms selected', '', __LINE__, __FILE__, $sql);
			}

			// run a loop to delete the pm's this user has recieved and sent
			while ( $row = $db->sql_fetchrow($result) )
			{
				// get all the privmsg_ids of the private messages for deletion
				$sql2 = "SELECT pr.* FROM " . PRIVMSGA_RECIPS_TABLE . " AS pr
                		WHERE pr.privmsg_id = " . $row['privmsg_id'];
				if ( !$result2 = $db->sql_query($sql2) )
				{
					message_die(GENERAL_ERROR, 'Can\'t read pms selected', '', __LINE__, __FILE__, $sql2);
				}

				// build an array of the privmsg_recip_ids we are going to delete
				$recip_ids = array();

				while ( $row2 = $db->sql_fetchrow($result2) )
				{
					$recip_ids[] = $row2['privmsg_recip_id'];
				}

				// delete 'em
				delete_pm($recip_ids, $view_user_id);
			}

            // user group
            $sql = "DELETE FROM " . USER_GROUP_TABLE . " WHERE user_id = $view_user_id";
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
            }

            // user
            $sql = "DELETE FROM " . USERS_TABLE . " WHERE user_id = $view_user_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
            }

            // send message
            $error = true;
            $error_msg = $lang['User_deleted'];
        }

        return '';
    }
}

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
//-------------------------------------------
//
//  FLAG format service functions :
//  -----------------------------
//      mods_settings_get_flag() : return the flag input fields definition
//      mods_settings_check_flag() : check and format the flag fields value
//
//-------------------------------------------
if (!function_exists('mods_settings_get_flags'))
{
    function mods_settings_get_flags($field, $value)
    {
        global $board_config, $db, $lang, $userdata, $template;

        // read flag datas
        $sql = "SELECT * FROM " . FLAG_TABLE . " ORDER BY flag_name";
        if (!$result = $db->sql_query($sql)) message_die(GENERAL_ERROR, 'Couldn\'t obtain flags information.', '', __LINE__, __FILE__, $sql);
        $flag_row = $db->sql_fetchrowset($result);
        $num_flags = $db->sql_numrows($result) ;

        // init
        if ( empty($value) )
        {
            $value = 'blank.gif';
        }

        // build the html select statement
        $flag_select = '<select name="' . $field . '" onChange="document.images[\'img_' . $field . '\'].src = \'templates/' . substr($template->root, $template->tpldir_len) . '/images/flags/\' + this.value;" >';
        $selected = ( $value == $flag_image ) ? 'selected="selected"' : '';
        $flag_select .= '<option value="templates/' . substr($template->root, $template->tpldir_len) . '/images/flags/blank.gif"' . $selected . '>' . $lang['Select_Country'] . '</option>';
        for ($i = 0; $i < $num_flags; $i++)
        {
            $flag_name = $flag_row[$i]['flag_name'];
            $flag_image = $flag_row[$i]['flag_image'];
            $selected = ( $value == $flag_image ) ? 'selected="selected"' : '';
            $flag_select .= '<option value="' . $flag_image . '"' . $selected . '>' . ucfirst($flag_name) . '</option>';
        }
        $flag_select .= '</select>';

        // image
        $flag_select .= '&nbsp;&nbsp;&nbsp;<img src="templates/' . substr($template->root, $template->tpldir_len) . '/images/flags/' . $value . '" width="20" height="13" border="0" align="middle" name="img_' . $field . '" />';

        return $flag_select;
    }
}

if (!function_exists('mods_settings_check_flags'))
{
    function mods_settings_check_flags($field, $value)
    {
        global $board_config, $error, $error_msg, $lang, $userdata;

        if ( $value == 'blank.gif' )
        {
            $value = '';
        }

        return trim(htmlspecialchars($value));
    }
}
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

?>