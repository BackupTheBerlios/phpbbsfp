<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_mods_settings.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_mods_settings.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

// some standard lists
$list_yes_no = array('Yes' => 1, 'No' => 0);
if (!defined('BOARD_ADMIN')) define('BOARD_ADMIN', 98);

//---------------------------------------------------------------
//
//	mods_settings_get_lang() : translation keys
//
//---------------------------------------------------------------
function mods_settings_get_lang($key)
{
	global $lang;
	return ((!empty($key) && isset($lang[$key])) ? $lang[$key] : $key);
}

//---------------------------------------------------------------
//
//	init_board_config_key() : add a key and its value to the board config table
//
//---------------------------------------------------------------
function init_board_config_key($key, $value, $force=false)
{
	if ($sql = set_config($key, $value, 0, NULL, TRUE))
	{
		message_die(GENERAL_ERROR, 'Could not add key ' . $key . ' in config table', '', __LINE__, __FILE__, $sql);
	}
}

//---------------------------------------------------------------
//
//	user_board_config_key() : get the user choice if defined
//
//---------------------------------------------------------------
function user_board_config_key($key, $user_field='', $over_field='')
{
	global $board_config, $userdata;

	// get the user fields name if not given
	if (empty($user_field))
	{
		$user_field = 'user_' . $key;
	}

	// get the overwrite allowed switch name if not given
	if (empty($over_field))
	{
		$over_field = $key . '_over';
	}

	// does the key exists ?
	if (!isset($board_config[$key])) return;

	// does the user field exists ?
	if (!isset($userdata[$user_field])) return;

	// does the overwrite switch exists ?
	if (!isset($board_config[$over_field]))
	{
		$board_config[$over_field] = 0; // no overwrite
	}

	// overwrite with the user data only if not overwrite sat, not anonymous, and logged in
	if (!intval($board_config[$over_field]) && ($userdata['user_id'] != ANONYMOUS) && $userdata['session_logged_in'])
	{
		$board_config[$key] = $userdata[$user_field];
	}
	else
	{
		$userdata[$user_field] = $board_config[$key];
	}
}

//---------------------------------------------------------------
//
//	init_board_config() : get the user choice if defined
//
//---------------------------------------------------------------
function init_board_config($mod_name, $config_fields, $sub_name='', $sub_sort=0, $mod_sort=0, $menu_name='Preferences', $menu_sort=0)
{
	global $mods;

	@reset($config_fields);
	while (list($config_key, $config_data) = each($config_fields))
	{
		if (!isset($config_data['user_only']) || !$config_data['user_only'])
		{
			// create the key value
			init_board_config_key($config_key, ( !empty($config_data['values']) ? $config_data['values'][ $config_data['default'] ] : $config_data['default']) );
			if (!empty($config_data['user']))
			{
				// create the "overwrite user choice" value
				init_board_config_key($config_key . '_over', 0);

				// get user choice value
				user_board_config_key($config_key, $config_data['user']);
			}
		}

		// deliever it for input only if not hidden
		if (!array_key_exists('hide', $config_data))
		{
			$mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$config_key] = $config_data;

			// sort values : overwrite only if not yet provided
			if (empty($mods[$menu_name]['sort']) || ($mods[$menu_name]['sort'] == 0) )
			{
				$mods[$menu_name]['sort'] = $menu_sort;
			}
			if (empty($mods[$menu_name]['data'][$mod_name]['sort']) || ($mods[$menu_name]['data'][$mod_name]['sort'] == 0) )
			{
				$mods[$menu_name]['data'][$mod_name]['sort'] = $mod_sort;
			}
			if (empty($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort']) || ($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort'] == 0) )
			{
				$mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort'] = $sub_sort;
			}
		}
	}
}

?>