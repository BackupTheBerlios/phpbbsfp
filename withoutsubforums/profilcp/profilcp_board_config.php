<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_board_config.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profile_board_config.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------


// start
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}
if ( !empty($setmodules) )
{
	// first pass : get main maps
	$w_maps = array();
	foreach ($user_maps as $map_name => $map_data)
	{
		$map_tree = explode('.', $map_name);
		if ( ($map_tree[0] = 'PCP') && ($map_data['custom'] == 1) )
		{
			// get this map
			$map_tree = explode('.', $map_name);
			$w_maps['name'][] = $map_name;
			$w_maps['depth'][] = count($map_tree)-1;
		}
	}

	// second pass : get sub maps
	$res_maps = array();
	@reset($user_maps);
	foreach ($user_maps as $map_name => $map_data)
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

					// main menu
					$pgm = '';
					if ( (count($map_tree)-$start) == 0 )
					{
						$pgm = __FILE__;
					}
					$res_maps[ $map_tree[$start-1] ]['']['order'] = $user_maps[$map_root]['order'];
					$res_maps[ $map_tree[$start-1] ]['']['pgm'] = $pgm;
					$res_maps[ $map_tree[$start-1] ]['']['shortcut'] = $user_maps[$map_root]['title'];
					$res_maps[ $map_tree[$start-1] ]['']['pagetitle'] = $user_maps[$map_root]['title'];

					// sub-menu
					$map_root .= ( empty($map_root) ? '' : '.' ) . $map_tree[$start];
					$res_maps[ $map_tree[$start-1] ][ $map_tree[$start] ]['order'] = $user_maps[$map_root]['order'];
					$res_maps[ $map_tree[$start-1] ][ $map_tree[$start] ]['pgm'] = __FILE__;
					$res_maps[ $map_tree[$start-1] ][ $map_tree[$start] ]['shortcut'] = $user_maps[$map_root]['title'];
					$res_maps[ $map_tree[$start-1] ][ $map_tree[$start] ]['pagetitle'] = $user_maps[$map_root]['title'];
					break;
				}
			}
		}
	}

	// process the maps found
	@reset($res_maps);
	while ( list($main, $main_data) = @each($res_maps) )
	{
		@reset($main_data);
		while ( list($sub, $data) = @each($main_data) )
		{
			if ( empty($sub) )
			{
				pcp_set_menu( $main, $data['order'], $data['pgm'], $data['shortcut'], $data['pagetitle'] );
			}
			else
			{
				pcp_set_sub_menu( $main, $sub, $data['order'], $data['pgm'], $data['shortcut'], $data['pagetitle'] );
			}
		}
	}
	return;
}

// access to users admins and himself
if ( ($userdata['user_id'] != $view_userdata['user_id']) && !is_admin($userdata) ) return;

// create entry if NULL : fix isset issue
@reset($view_userdata);
while (list($key, $data) = each($view_userdata) )
{
	if ($view_userdata[$key] == NULL )
	{
		$view_userdata[$key] = '';
	}
}

// levels
$is_prior = ( $level_prior[get_user_level($userdata)] > $level_prior[get_user_level($view_userdata)] ) || (get_user_level($userdata) == ADMIN_FOUNDER);
$is_admin = ( is_admin($userdata) && $is_prior );
$is_board_admin = $is_admin && ($userdata['user_level'] == ADMIN);

//
// get all the mods settings
//
$mods = array();
$dir = @opendir($phpbb_root_path . 'includes/mods_settings');
while( $file = @readdir($dir) )
{
	if( preg_match("/^mod_.*?\." . $phpEx . "$/", $file) )
	{
		include($phpbb_root_path . 'includes/mods_settings/' . $file);
	}
}
@closedir($dir);

// main_menu
$menu_name = $sub;
if ( !isset($mods[$menu_name]['data']) )
{
	$menu_name = '';
}

// mod_id
$mod_id = 0;
if ( isset($HTTP_GET_VARS['mod']) || isset($HTTP_POST_VARS['mod_id']) )
{
	$mod_id = isset($HTTP_POST_VARS['mod_id']) ? intval($HTTP_POST_VARS['mod_id']) : intval($HTTP_GET_VARS['mod']);
}

// sub_id
$sub_id = 0;
if ( isset($HTTP_GET_VARS['msub']) || isset($HTTP_POST_VARS['mod_sub_id']) )
{
	$sub_id = isset($HTTP_POST_VARS['mod_sub_id']) ? intval($HTTP_POST_VARS['mod_sub_id']) : intval($HTTP_GET_VARS['msub']);
}

// build a key array
$mod_keys = array();
$mod_sort = array();
$sub_keys = array();
$sub_sort = array();
@reset($mods[$menu_name]['data']);
while ( list($mod_name, $mod) = @each($mods[$menu_name]['data']) )
{
	// check if there is some users fields
	$found = false;
	@reset($mod['data']);
	while ( list($sub_name, $subdata) = @each($mod['data']) )
	{
		@reset($subdata['data']);
		while ( list($field_name, $field) = @each($subdata['data']) )
		{
			$is_auth = ( empty($field['auth']) || ( $field['auth'] == USER ) || ( ($field['auth'] == ADMIN) && $is_admin ) || ( ($field['auth'] == BOARD_ADMIN) && $is_board_admin ) );
			if ( ( ( !empty($field['user']) && isset($view_userdata[ $field['user'] ]) && !$board_config[ $field_name . '_over'] )  || $field['system'] ) && $is_auth )
			{
				$found=true;
				break;
			}
		}
	}
	if ($found)
	{
		$i = count($mod_keys);
		$mod_keys[$i] = $mod_name;
		$mod_sort[$i] = $mod['sort'];

		// init sub levels
		$sub_keys[$i] = array();
		$sub_sort[$i] = array();

		// sub names
		@reset($mod['data']);
		while ( list($sub_name, $subdata) = @each($mod['data']) )
		{
			if ( !empty($sub_name) )
			{
				// user fields in this level
				$found = false;
				@reset($subdata['data']);
				while ( list($field_name, $field) = @each($subdata['data']) )
				{
					$is_auth = ( empty($field['auth']) || ( $field['auth'] == USER ) || ( ($field['auth'] == ADMIN) && $is_admin ) || ( ($field['auth'] == BOARD_ADMIN) && $is_board_admin ) );
					if ( ( ( !empty($field['user']) && isset($view_userdata[ $field['user'] ]) && !$board_config[ $field_name . '_over'] ) || $field['system'] ) && $is_auth )
					{
						$found=true;
						break;
					}
				}
				if ($found)
				{
					$sub_keys[$i][] = $sub_name;
					$sub_sort[$i][] = $subdata['sort'];
				}
			}
		}
		@array_multisort($sub_sort[$i], $sub_keys[$i]);
	}
}
@array_multisort($mod_sort, $mod_keys, $sub_sort, $sub_keys);

// fix mod id
if ( $mod_id > count($mod_keys) )
{
	$mod_id = 0;
}
if ( $sub_id > count($sub_keys[$mod_id]) )
{
	$sub_id = 0;
}

// mod name
$mod_name = $mod_keys[$mod_id];

// sub name
$sub_name = $sub_keys[$mod_id][$sub_id];

// buttons
$submit = isset($HTTP_POST_VARS['submit']);

// validate
if ($submit)
{
	// init for error
	$error = false;
	$error_msg = '';

	// format and verify data
	@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
	{
		$user_field = $field['user'];
		$is_auth = ( empty($field['auth']) || ( $field['auth'] == USER ) || ( ($field['auth'] == ADMIN) && $is_admin ) || ( ($field['auth'] == BOARD_ADMIN) && $is_board_admin ) );
		if ( isset($HTTP_POST_VARS[$user_field]) && $is_auth )
		{
			switch ($field['type'])
			{
				case 'LIST_RADIO':
				case 'LIST_DROP':
					$$user_field = $HTTP_POST_VARS[$user_field];
					if (!in_array($$user_field, $mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$field_name]['values']))
					{
						$error = true;
						$msg = mods_settings_get_lang( $mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$field_name]['lang_key'] );
						$error_msg = (empty($error_msg) ? '' : '<br />') . $lang['Error'] . ':&nbsp;' . $msg;
					}
					break;
				case 'TINYINT':
				case 'SMALLINT':
				case 'MEDIUMINT':
				case 'INT':
					$$user_field = intval($HTTP_POST_VARS[$user_field]);
					break;
				case 'VARCHAR':
				case 'TEXT':
				case 'DATEFMT':
					$$user_field = trim(str_replace("\'", "''", htmlspecialchars($HTTP_POST_VARS[$user_field])));
					break;
				case 'HTMLVARCHAR':
				case 'HTMLTEXT':
					$$user_field = trim(str_replace("\'", "''", $HTTP_POST_VARS[$user_field]));
					break;
				default:
					$$user_field = '';
					if ( !empty($field['chk_func']) && function_exists($field['chk_func']) )
					{
						$$user_field = $field['chk_func']($user_field, $HTTP_POST_VARS[$user_field]);
					}
					else
					{
						message_die(GENERAL_ERROR, 'Unknown type of config data : ' . $field_name, '', __LINE__, __FILE__, '');
					}
					break;
			}
			if ($error)
			{
				$ret_link = append_sid("./profile.$phpEx?mode=profil&sub=$sub&mod=$mod_id&msub=$sub_id&" . POST_USERS_URL . "=$view_user_id");
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . $ret_link . '">')
				);
				$message = $error_msg . '<br /><br />' . sprintf($lang['Click_return_preferences'], '<a href="' . $ret_link . '">', '</a>') . '<br /><br />';
				message_die(GENERAL_MESSAGE, $message);
			}
		}
	}

	// save result
	@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
	{
		$user_field = $field['user'];
		$is_auth = ( empty($field['auth']) || ( $field['auth'] == USER ) || ( ($field['auth'] == ADMIN) && $is_admin ) || ( ($field['auth'] == BOARD_ADMIN) && $is_board_admin ) );
		if ( isset($$user_field) && !empty($user_field) && isset($view_userdata[$user_field]) && !$board_config[ $field_name . '_over'] && $is_auth )
		{
			// update
			$sql = "UPDATE " . USERS_TABLE . "
					SET $user_field='" . $$user_field . "'
					WHERE user_id = " . $view_userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update user configuration for ' . $field['user'], '', __LINE__, __FILE__, $sql);
			}
		}
	}

	// send an update message
	$ret_link = append_sid("./profile.$phpEx?mode=profil&sub=$sub&mod=$mod_id&msub=$sub_id&" . POST_USERS_URL . "=$view_user_id");
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . $ret_link . '">')
	);
	$message = $lang['Profile_updated'] . "<br /><br />" . sprintf($lang['Click_return_profilcp'], '<a href="' . $ret_link . '">', '</a>') . '<br /><br />';
	message_die(GENERAL_MESSAGE, $message);
}
else
{
	// template
	$template->set_filenames(array(
		'body' => 'profilcp/board_config_body.tpl')
	);

	// header
	$template->assign_vars(array(
		'L_MOD_NAME'		=> mods_settings_get_lang($mod_name) . ( !empty($sub_name) ? ' - ' . mods_settings_get_lang($sub_name) : '' ),
		'L_SUBMIT'			=> $lang['Submit'],
		'L_RESET'			=> $lang['Reset'],
		)
	);

	// send menu
	for ($i=0; $i < count($mod_keys); $i++)
	{
		$l_mod = $mod_keys[$i];
		if ( count($sub_keys[$i]) == 1 )
		{
			$l_mod = $sub_keys[$i][0];
		}
		$template->assign_block_vars('mod', array(
			'CLASS'	=> ($mod_id == $i) ? 'row1' : 'row2',
			'ALIGN'	=> ( ($mod_id == $i) && (count($sub_keys[$i]) > 1) ) ? 'left' : 'center',
			'U_MOD'	=> append_sid("./profile.$phpEx?mode=profil&sub=$sub&mod=$i&" . POST_USERS_URL . "=$view_user_id"),
			'L_MOD'	=> sprintf( (($mod_id == $i) ? '<b>%s</b>' : '%s'), mods_settings_get_lang($l_mod) ),
			)
		);
		if ($mod_id == $i)
		{
			if ( count($sub_keys[$i]) > 1 )
			{
				$template->assign_block_vars('mod.sub', array());
				for ($j=0; $j < count($sub_keys[$i]); $j++)
				{
					$template->assign_block_vars('mod.sub.row', array(
						'CLASS'	=> ($sub_id == $j) ? 'row1' : 'row1',
						'U_MOD' => append_sid("./profile.$phpEx?mode=profil&sub=$sub&mod=$i&msub=$j&" . POST_USERS_URL . "=$view_user_id"),
						'L_MOD'	=> sprintf( (($sub_id == $j) ? '<b>%s</b>' : '%s'), mods_settings_get_lang($sub_keys[$i][$j]) ),
						)
					);
				}
			}
		}
	}

	// send items
	@reset($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']);
	while ( list($field_name, $field) = @each($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data']) )
	{
		// process only not overwritten fields from users table and system fields
		$user_field = $field['user'];
		$is_auth = ( empty($field['auth']) || ( $field['auth'] == USER ) || ( ($field['auth'] == ADMIN) && $is_admin ) || ( ($field['auth'] == BOARD_ADMIN) && $is_board_admin ) );
		if ( ( ( !empty($user_field) && isset($view_userdata[$user_field]) && !$board_config[ $field_name . '_over'] ) || $field['system'] ) && $is_auth )
		{
			// get the field input statement
			$input = '';
			switch ($field['type'])
			{
				case 'LIST_RADIO':
					@reset($field['values']);
					while ( list($key, $val) = @each($field['values']) )
					{
						$selected = ($view_userdata[$user_field] == $val) ? ' checked="checked"' : '';
						$l_key = mods_settings_get_lang($key);
						$input .= '<input type="radio" name="' . $user_field . '" value="' . $val . '"' . $selected . ' />' . $l_key . '&nbsp;&nbsp;';
					}
					break;
				case 'LIST_DROP':
					@reset($field['values']);
					while ( list($key, $val) = @each($field['values']) )
					{
						$selected = ($view_userdata[$user_field] == $val) ? ' selected="selected"' : '';
						$l_key = mods_settings_get_lang($key);
						$input .= '<option value="' . $val . '"' . $selected . '>' . $l_key . '</option>';
					}
					$input = '<select name="' . $user_field . '">' . $input . '</select>';
					break;
				case 'TINYINT':
					$input = '<input type="text" name="' . $user_field . '" maxlength="3" size="2" class="post" value="' . $view_userdata[$user_field] . '" />';
					break;
				case 'SMALLINT':
					$input = '<input type="text" name="' . $user_field . '" maxlength="5" size="5" class="post" value="' . $view_userdata[$user_field] . '" />';
					break;
				case 'MEDIUMINT':
					$input = '<input type="text" name="' . $user_field . '" maxlength="8" size="8" class="post" value="' . $view_userdata[$user_field] . '" />';
					break;
				case 'INT':
					$input = '<input type="text" name="' . $user_field . '" maxlength="13" size="11" class="post" value="' . $view_userdata[$user_field] . '" />';
					break;
				case 'VARCHAR':
				case 'HTMLVARCHAR':
					$input = '<input type="text" name="' . $user_field . '" maxlength="255" size="45" class="post" value="' . $view_userdata[$user_field] . '" />';
					break;
				case 'TEXT':
				case 'HTMLTEXT':
					$input = '<textarea rows="5" cols="45" wrap="virtual" name="' . $user_field . '" class="post">' . $view_userdata[$user_field] . '</textarea>';
					break;
				default:
					$input = '';
					if ( !empty($field['get_func']) && function_exists($field['get_func']) )
					{
						$input = $field['get_func']($user_field, $view_userdata[$user_field]);
					}
					break;
			}

			// dump to template
			$template->assign_block_vars('field', array(
				'L_NAME'	=> mods_settings_get_lang($field['lang_key']),
				'L_EXPLAIN'	=> !empty($field['explain']) ? '<br />' . mods_settings_get_lang($field['explain']) : '',
				'INPUT'		=> $input,
				)
			);
		}
	}

	// system
	$s_hidden_fields .= '<input type="hidden" name="mod_id" value="' . $mod_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mod_sub_id" value="' . $sub_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="set" value="add" />';
	$s_hidden_fields .= '<input type="hidden" name="submit" value="1" />';
	$template->assign_vars(array(
		'S_PROFILCP_ACTION' => append_sid("profile.$phpEx"),
		'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
		)
	);

	// page
	$template->pparse('body');
}

?>