<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_modules.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_modules.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['Portal']['Modules'] = $file;
	return;
}

define('IN_PHPBB', true);

//
// Load default header
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

//
// Functions
//
function populate_file_tree($path, $filter, $recurse = false, $prefix='')
{
	$out = '';
	$p = substr($path, -1, 1) == '/' ? $path : $path . '/';
	if (@file_exists($p))
	{
		$base = opendir($p);
	}
	if ($base)
	{
		while (($file = readdir($base)) !== false)
		{
			if ($file[0] != '.')
			{
				if (is_dir($p . $file))
				{
					if ($recurse)
					{
						$sub_out = populate_file_tree($p . $file, $filter, $recurse, $prefix . $file . '/');
						$out .= ($sub_out ? ($out ? ', ':'') . $sub_out : '');
					}
					else
					{
						continue;
					}
				}
				elseif (preg_match('~.+\.' . $filter . '$~i', $file))
				{
					$out .= ($out ? ', ':'') . $prefix . $file;
				}
			}
		}
	}
	return $out;
}
//
// Main job
//

//
// Mode setting
//
if (isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']))
{
	$mode = isset($HTTP_POST_VARS['mode']) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = '';
}

if (isset($HTTP_POST_VARS['module']) || isset($HTTP_GET_VARS['module']))
{
	$module = isset($HTTP_POST_VARS['module']) ? $HTTP_POST_VARS['module'] : $HTTP_GET_VARS['module'];
}
else
{
	$module = '';
}

if (($mode == 'enable' || $mode == 'disable') && $module != '')
{
	$state = ($mode == 'enable' ? 1:2);
	$sql = 'UPDATE ' . MODULES_TABLE . " SET module_state = $state WHERE module_name = '$module'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_update_error'], "", __LINE__, __FILE__, $sql);
	}
	if (!$db->sql_affectedrows())
	{
		message_die(GENERAL_ERROR, sprintf($lang['Module_not_found'], $module));
	}
	
	$cache->destroy('modules');
	
	$mvModules[$module]['state'] = $state;
	$mode = '';
	$template->assign_var('SCRIPT', "\n<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.parent.frames['nav'].location.replace('index.$phpEx?pane=left&$SID');\n//-->\n</script>\n");
}
else if ($mode == 'default' && $module != '')
{
	$state = 5; // Default module
	$sql = 'UPDATE ' . MODULES_TABLE . " SET module_state = 1 WHERE module_state = $state";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_update_error'], '', __LINE__, __FILE__, $sql);
	}
	$sql = 'UPDATE ' . MODULES_TABLE . " SET module_state = $state WHERE module_name = '$module'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_update_error'], '', __LINE__, __FILE__, $sql);
	}
	if (!$db->sql_affectedrows())
	{
		message_die(GENERAL_ERROR, sprintf($lang['Module_not_found'], $module));
	}
	
	$cache->destroy('modules');
	
	foreach ($mvModules as $name => $value)
	{
		if ($mvModules[$name]['state'] == $state)
		{
			$mvModules[$name]['state'] = 1; // Active
		}
	}
	$mvModules[$module]['state'] = $state;
	$mode = '';
}
else if ($mode == 'uninstall' && $module != '')
{
	$sql = 'DELETE FROM ' . MODULES_TABLE . " WHERE `module_name` = '$module'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_delete_error'], '', __LINE__, __FILE__, $sql);
	}
	if (!$db->sql_affectedrows())
	{
		message_die(GENERAL_ERROR, sprintf($lang['Module_not_found'], $module));
	}
	
	$cache->destroy('modules');
	
	$mvModule_root_path = $phpbb_root_path . 'modules/' . $module . '/';
	$mod_uninstall_file = $mvModule_root_path . "install/mvUninstall.$phpEx";
	$message = '';
	if (@file_exists($mod_uninstall_file))
	{
		$message .= "<iframe width=\"100%\" height=\"300\" src=\"$mod_uninstall_file\"></iframe><br/><br/>";
	}
	$message .= "\n<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.parent.frames['nav'].location.replace('index.$phpEx?pane=left&$SID');\n//-->\n</script>\n";
	$message .= sprintf($lang['Module_was_uninstalled'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'cleanup' && $module != '')
{
	$sql = 'DELETE FROM ' . MODULES_TABLE . " WHERE `module_name` = '$module'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_delete_error'], '', __LINE__, __FILE__, $sql);
	}
	if (!$db->sql_affectedrows())
	{
		message_die(GENERAL_ERROR, sprintf($lang['Module_not_found'], $module));
	}
	
	$cache->destroy('modules');
	
	$message .= sprintf($lang['Module_cleaned'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'install' && $module != '')
{
	if (array_key_exists($module, $mvModules))
	{
		$message .= sprintf($lang['Module_already_installed'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$mvModule_root_path = $phpbb_root_path . 'modules/' . $module . '/';
	$mod_version_file = $mvModule_root_path . "mvVersion.$phpEx";
	$mod_constants_file = $mvModule_root_path . "mvConstants.$phpEx";
	$mod_headers_file = $mvModule_root_path . "mvHeaders.$phpEx";
	$mvVersion = array();
	if (@file_exists($mod_version_file) && (@file_exists($mvModule_root_path . "$module.$phpEx") || @file_exists($mvModule_root_path . "index.$phpEx")))
	{
		include($mod_version_file);
	}
	if (count($mvVersion) < 4) // Incomplete version information
	{
		$message .= sprintf($lang['Module_incompatible'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$mvModuleFilesList = '';
	$mvModuleTemplatesList = '';
	$mvModuleAdminTemplatesList = '';
	$mvModuleAdminList = '';
	$mvModuleLanguageList = '';
	$mvModuleBlocksList = '';

	// Let's build a file list
	$whereToLook = array(   "mvModuleFilesList#$phpEx#false" => '.',
							"mvModuleTemplatesList#tpl#true" => 'templates/' . $theme['template_name'],
							"mvModuleAdminTemplatesList#tpl#false" => 'templates/admin',
							"mvModuleAdminList#$phpEx#false" => 'admin',
							"mvModuleLanguageList#$phpEx#false" => 'language/lang_' . $board_config['default_lang'],
							"mvModuleBlocksList#$phpEx#false" => 'blocks');
	foreach ($whereToLook as $what => $dir)
	{
		list($var, $filter, $recurse, $prefix) = explode('#', $what);
		$recurse = ($recurse == "true") ? true:false;
		$$var = populate_file_tree($mvModule_root_path . $dir, $filter, $recurse, $prefix);
	}
	$mvModuleTemplatesList .= ($mvModuleTemplatesList ? ', ':'') . $mvModuleAdminTemplatesList;
	$sql = "INSERT INTO " . MODULES_TABLE . " ( `module_id` , `module_name` , `module_type` , `module_displayname` , `module_description` , `module_version` , `module_copyright` , `module_files` , `module_templates` , `module_admin` , `module_blocks` , `module_language` , `module_constants` , `module_headers` , `module_state` ) VALUES ('', '$module', $mvVersion[type], '$mvVersion[displayname]', '$mvVersion[description]', '$mvVersion[version]', '$mvVersion[copyright]'";
	$sql .= ', ' . ($mvModuleFilesList ? "'$mvModuleFilesList'" : 'NULL');
	$sql .= ', ' . ($mvModuleTemplatesList ? "'$mvModuleTemplatesList'": 'NULL');
	$sql .= ', ' . ($mvModuleAdminList ? "'$mvModuleAdminList'" : 'NULL');
	$sql .= ', ' . ($mvModuleBlocksList ? "'$mvModuleBlocksList'" : 'NULL');
	$sql .= ', ' . ($mvModuleLanguageList ? "'$mvModuleLanguageList'" : 'NULL');
	$sql .= ', ' . (@file_exists($mod_constants_file) ? "'mvConstants.$phpEx'": 'NULL');
	$sql .= ', ' . (@file_exists($mod_headers_file) ? "'mvHeaders.$phpEx'": 'NULL');
	$sql .= ', 1)';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_add_error'], '', __LINE__, __FILE__, $sql);
	}
	
	$cache->destroy('modules');
	
	$mod_install_file = $mvModule_root_path . "install/mvInstall.$phpEx";
	$message = '';
	if (@file_exists($mod_install_file))
	{
		$message .= "<iframe width=\"100%\" height=\"300\" src=\"$mod_install_file\"></iframe><br/><br/>";
	}
	$message .= "\n<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.parent.frames['nav'].location.replace('index.$phpEx?pane=left&$SID');\n//-->\n</script>\n";
	$message .= sprintf($lang['Module_installed'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'update' && $module != '')
{
	if (!array_key_exists($module, $mvModules))
	{
		$message .= sprintf($lang['Module_not_found'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$mvModule_root_path = $phpbb_root_path . 'modules/' . $module . '/';
	$mod_version_file = $mvModule_root_path . "mvVersion.$phpEx";
	$mod_constants_file = $mvModule_root_path . "mvConstants.$phpEx";
	$mod_headers_file = $mvModule_root_path . "mvHeaders.$phpEx";
	$mvVersion = array();
	if (@file_exists($mod_version_file) && (@file_exists($mvModule_root_path . "$module.$phpEx") || @file_exists($mvModule_root_path . "index.$phpEx")))
	{
		include($mod_version_file);
	}
	if (count($mvVersion) < 4) // Incomplete version information
	{
		$message .= sprintf($lang['Module_incompatible'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	
	$mvModuleFilesList = '';
	$mvModuleTemplatesList = '';
	$mvModuleAdminTemplatesList = '';
	$mvModuleAdminList = '';
	$mvModuleLanguageList = '';
	$mvModuleBlocksList = '';
	
	// Let's build a file list
	$whereToLook = array(   "mvModuleFilesList#$phpEx#false" => '.',
							"mvModuleTemplatesList#tpl#true" => 'templates/' . $theme['template_name'],
							"mvModuleAdminTemplatesList#tpl#false" => 'templates/admin',
							"mvModuleAdminList#$phpEx#false" => 'admin',
							"mvModuleLanguageList#$phpEx#false" => 'language/lang_' . $board_config['default_lang'],
							"mvModuleBlocksList#$phpEx#false" => 'blocks');
	foreach ($whereToLook as $what => $dir)
	{
		list($var, $filter, $recurse, $prefix) = explode('#', $what);
		$recurse = ($recurse == "true") ? true:false;
		$$var = populate_file_tree($mvModule_root_path . $dir, $filter, $recurse, $prefix);
	}
	$mvModuleTemplatesList .= ($mvModuleTemplatesList ? ', ':'') . $mvModuleAdminTemplatesList;
	$sql = "UPDATE " . MODULES_TABLE . " SET `module_name` = '$module', `module_type` = $mvVersion[type], `module_displayname` = '$mvVersion[displayname]', `module_description` = '$mvVersion[description]', `module_version` = '$mvVersion[version]', `module_copyright` = '$mvVersion[copyright]'";
	$sql .= ', `module_files` = ' . ($mvModuleFilesList ? "'$mvModuleFilesList'" : 'NULL');
	$sql .= ', `module_templates` = ' . ($mvModuleTemplatesList ? "'$mvModuleTemplatesList'": 'NULL');
	$sql .= ', `module_admin` = ' . ($mvModuleAdminList ? "'$mvModuleAdminList'" : 'NULL');
	$sql .= ', `module_blocks` = ' . ($mvModuleBlocksList ? "'$mvModuleBlocksList'" : 'NULL');
	$sql .= ', `module_language` = ' . ($mvModuleLanguageList ? "'$mvModuleLanguageList'" : 'NULL');
	$sql .= ', `module_constants` = ' . (@file_exists($mod_constants_file) ? "'mvConstants.$phpEx'": 'NULL');
	$sql .= ', `module_headers` = ' . (@file_exists($mod_headers_file) ? "'mvHeaders.$phpEx'": 'NULL');
	$sql .= ", `module_state` = 1 WHERE `module_name` = '$module'";
	
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, $lang['Module_update_error'], '', __LINE__, __FILE__, $sql);
	}
	
	$cache->destroy('modules');
	
	$mod_update_file = $mvModule_root_path . "install/mvUpdate.$phpEx";
	$message = '';
	if (@file_exists($mod_update_file))
	{
		$message .= "<iframe width=\"100%\" height=\"300\" src=\"$mod_update_file\"></iframe><br/><br/>";
	}
	$message .= "\n<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.parent.frames['nav'].location.replace('index.$phpEx?pane=left&$SID');\n//-->\n</script>\n";
	$message .= sprintf($lang['Module_updated'], $module) . '<br /><br />' . sprintf($lang['Module_click_return_admin'], '<a href="' . append_sid("admin_modules.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

if ($mode == '')
{
	//
	// get list of loaded modules
	//
	$modules_path = $phpbb_root_path . 'modules/';
	$mvModulesList = $mvModules;
	foreach ($mvModulesList as $name => $value)
	{
		$mvModulesList[$name]['state'] = 4;
	}
	if ($base = opendir($modules_path))
	{
		while (($dir = readdir($base)) !== false)
		{
			$mod_version_file = $modules_path . $dir . '/mvVersion.php';
			if ($dir[0] != "." && @file_exists($mod_version_file))
			{
				$mvVersion = array();
				include($mod_version_file);
				if (count($mvVersion) < 4) // Incomplete version information
				{
					continue;
				}
				if (array_key_exists($dir, $mvModules))
				{
					$aDB = explode('.', $mvModules[$dir]['version']);
					$aFL = explode('.', $mvVersion['version']);
					$vCompare = 0;
					for ($i = 0; $i < max(count($aDB), count($aFL)); $i++)
					{
						$vDB = $i < count($aDB) ? intval($aDB[$i]) : 0;
						$vFL = $i < count($aFL) ? intval($aFL[$i]) : 0;
						if ($vDB < $vFL)
						{
							$vCompare = -1;
							break;
						}
						else if ($vDB > $vFL)
						{
							$vCompare = 1;
							break;
						}
					}
					// States:
					// 0 = not installed
					// 1 = installed, running
					// 2 = installed, disabled
					// 3 = not updated
					// 4 = removed
					// 5 = default
					if ($vCompare == 0) // Equal
					{
						$mvModulesList[$dir]['state'] = $mvModules[$dir]['state'];
					}
					else if ($vCompare < 0) // update
					{
						$mvModulesList[$dir] = $mvVersion;
						$mvModulesList[$dir]['state'] = 3; // update
					}
					else if ($vCompare > 0) // older
					{
						$mvModulesList[$dir] = $mvVersion;
						$mvModulesList[$dir]['state'] = -1; // error
					}
				}
				else
				{
					$mvModulesList[$dir] = $mvVersion;
					$mvModulesList[$dir]['state'] = 0; // not installed
				}
			}
		}
		closedir($base);
	}
	ksort($mvModulesList);
	reset($mvModulesList);
	$i = 0;
	foreach ($mvModulesList as $name => $value)
	{
		$command1 = '';
		$command1_text = '';
		$command2 = '';
		$command2_text = '';
		$command3 = '';
		$command3_text = '';
		$bold_in = '';
		$bold_out = '';
		switch($value['state'])
		{
			case 0:
				$state = $lang['Module_short_not_installed'];
				$command1 = 'install';
				$command1_text = $lang['Module_short_install'];
				$value['description'] = stripslashes($value['description']);
				break;
			case 1:
				$state = $lang['Module_short_active'];
				$command1 = 'disable';
				$command1_text = $lang['Module_short_disable'];
				$command2 = 'uninstall';
				$command2_text = $lang['Module_short_uninstall'];
				$command3 = 'default';
				$command3_text = $lang['Module_short_make_default'];
				$bold_in = '<b>';
				$bold_out = '</b>';
				break;
			case 2:
				$state = $lang['Module_short_disabled'];
				$command1 = 'enable';
				$command1_text = $lang['Module_short_enable'];
				$command2 = 'uninstall';
				$command2_text = $lang['Module_short_uninstall'];
				break;
			case 3:
				$state = sprintf($lang['Module_short_needs_updating'], $mvModules[$name]['version']);
				$command1 = 'update';
				$command1_text = $lang['Module_short_update'];
				$bold_in = '<b style="color: red;">';
				$bold_out = '</b>';
				break;
			case 4:
				$state = $lang['Module_short_incorrect_removal'];
				$command1 = 'cleanup';
				$command1_text = $lang['Module_short_cleanup'];
				$bold_in = '<b style="color: red;">';
				$bold_out = '</b>';
				break;
			case 5:
				$state = $lang['Module_short_default'];
				$command1 = 'disable';
				$command1_text = $lang['Module_short_disable'];
				$command2 = 'uninstall';
				$command2_text = $lang['Module_short_uninstall'];
				$bold_in = '<b>';
				$bold_out = '</b>';
				break;
			case -1:
				$state = sprintf($lang['Module_short_was_newer'], $mvModules[$name]['version']);
				$command1_text = '[Fix manually]';
				$bold_in = '<b style="color: red;">';
				$bold_out = '</b>';
				break;
		}
	
		$template->assign_block_vars('modules', array(
			'ID'                => $bold_in . $name . $bold_out,
			'VERSION'           => $value['version'],
			'L_NAME'            => $bold_in . $value['displayname'] . $bold_out,
			'U_NAME'            => append_sid($phpbb_root_path."index.$phpEx?module=$name"),
			'DESCRIPTION'       => $value['description'],
			'STATE'             => $bold_in . $state . $bold_out,
			'L_COMMAND1'        => $command1_text,
			'U_COMMAND1'        => $command1 ? append_sid("admin_modules.$phpEx?mode=$command1&amp;module=$name") : '#',
			'L_COMMAND2'        => $command2_text,
			'U_COMMAND2'        => $command2 ? append_sid("admin_modules.$phpEx?mode=$command2&amp;module=$name") : '#',
			'L_COMMAND3'        => $command3_text,
			'U_COMMAND3'        => $command3 ? append_sid("admin_modules.$phpEx?mode=$command3&amp;module=$name") : '#'
			)
		);
	}
}

$template->assign_vars(array(
	'L_MODULES_TITLE'   => $lang['Module_administration'],
	'L_MODULES_EXPLAIN' => $lang['Module_administration_explain'],
	'L_MODULE_ID'       => $lang['Module_id'],
	'L_MODULE_NAME'     => $lang['Module_module'],
	'L_MODULE_STATE'    => $lang['Module_status'],
	'L_MODULE_COMMAND'  => $lang['Module_action'],
	)
);

$template->set_filenames(array('body' => 'modules_body.tpl'));
$template->pparse('body');
include('./page_footer_admin.'.$phpEx);


?>
