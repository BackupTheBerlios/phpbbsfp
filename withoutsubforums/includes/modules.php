<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: modules.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : modules.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if ($cache->exists('modules'))
{
	$mvModules = $cache->get('modules');
}
else
{
	$sql = 'SELECT * FROM ' . MODULES_TABLE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query module information', '', __LINE__, __FILE__, $sql);
	}
	
	$mvModules = array();
	$names = array();

	while ($row = $db->sql_fetchrow($result))
	{
		$name = $row['module_name'];
		$mvModules[$name]['type'] = $row['module_type'];
		$mvModules[$name]['displayname'] = $row['module_displayname'];
		$mvModules[$name]['description'] = $row['module_description'];
		$mvModules[$name]['version'] = $row['module_version'];
		$mvModules[$name]['copyright'] = $row['module_copyright'];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_files'], $found);
		$mvModules[$name]['files'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_templates'], $found);
		$mvModules[$name]['templates'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_admin'], $found);
		$mvModules[$name]['admin'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_blocks'], $found);
		$mvModules[$name]['blocks'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_language'], $found);
		$mvModules[$name]['language'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_constants'], $found);
		$mvModules[$name]['constants'] = $found[1];
		preg_match_all("/\s*([^,]+)\s*/", $row['module_headers'], $found);
		$mvModules[$name]['headers'] = $found[1];
		$mvModules[$name]['state'] = $row['module_state'];
	}
	$db->sql_freeresult($result);
	
	$cache->put('modules', $mvModules);
}

$requested_name = ( isset($mvModuleName) ) ? strtolower($mvModuleName) : '';
$default_name = '';
$valid_name = '';
$mods = array();

foreach ($mvModules as $name => $value)
{
	if ($value['state'] != 1 && $value['state'] != 5)
	{
		continue;
	}

	$mvModuleName = $name;
/*
	foreach ($value['constants'] as $file)
	{
		@include($phpbb_root_path . 'modules/' . $name . '/' . $file);
	}
*/
	ImportmvConstants($name);
    if (defined('IN_ADMIN'))
    {
    	@include($phpbb_root_path . 'modules/' . $name . '/mvConfig.' . $phpEx);
    }

	if ($requested_name != '')
	{
		if ($value['state'] == 5)
		{
			$default_name = $name;
		}
		if (strtolower($name) == $requested_name)
		{
			$valid_name = $name;
		}
	}
}

$mvModuleName = ( $valid_name != '' ) ? $valid_name : $default_name;

if ($mvModuleName != '')
{
	$mvModuleTemplates = $mvModules[$mvModuleName]['templates'];
	$mvModuleAdmin = $mvModules[$mvModuleName]['admin'];
	$mvModuleBlocks = $mvModules[$mvModuleName]['blocks'];
	$mvModuleCopyright = $mvModules[$mvModuleName]['copyright'];
	$mvModule_root_path = $phpbb_root_path . 'modules/' . $mvModuleName . '/';
}
else
{
	$mvModuleTemplates = array();
	$mvModuleAdmin = array();
	$mvModuleBlocks = array();
	$mvModuleCopyright = '';
	$mvModule_root_path = '';
}

function IsmvModuleDefault($modulename)
{
	global $mvModules;
	if (GetmvModuleStatus($modulename) == 5)
	{
		return true;
	}
	return false;
}
function GetmvModuleVersion($modulename)
{
	global $mvModules;
	return $mvModules[$modulename]['version'];
}
function ImportmvHeaders($modulename)
{
	global $mvModules, $phpbb_root_path;
	foreach	($mvModules[$modulename]['headers'] as $file)
	{
		@include($phpbb_root_path . 'modules/' .	$modulename .	'/'	. $file);
	}
}
function ImportmvConstants($modulename)
{
	global $mvModules, $phpbb_root_path, $table_prefix, $phpEx;
	foreach	($mvModules[$modulename]['constants'] as $file)
	{
		@include($phpbb_root_path . 'modules/' .	$modulename .	'/'	. $file);
	}
}
function GetmvInstalled($status)
{
	global $db, $mvModules;
	$sql = 'SELECT * FROM ' . MODULES_TABLE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query module information', '', __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		if (GetmvModuleStatus($row['module_name']) == $status)
		{
			$installed[] = $row['module_name'];
		}
	}
	return $installed;
}
function GetmvFilePath($modulename, $filename)
{
	global $phpbb_root_path;
	return $phpbb_root_path . 'modules/' . $modulename . '/' . $filename;
}
function GetmvModuleStatus($modulename)
{
	global $mvModules;
	return $mvModules[$modulename]['state'];
}
function GetmvDependacy($modulename)
{
	global $mvModules;
	if (GetmvModuleStatus($modulename) == 1 || GetmvModuleStatus($modulename) !== 5)
	{
		return GetmvModuleVersion($modulename);
	}
	return;
}
function GetmvModuleRootPath($modulename)
{
	global $mvModules, $phpEx;
	return "index.$phpEx?module=$mvModuleName";
}
?>