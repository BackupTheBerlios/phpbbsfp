<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: def_userfuncs_vlist.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : def_userfuncs_vlist.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

//-------------------------------------------
//
//	languages installed
//
//-------------------------------------------
function get_langs_list()
{
	global $phpbb_root_path;

	// read all the language available
	$dir_lang = opendir($phpbb_root_path . './language');
	$langs = array();
	$langs_name = array();
	while ( $file_lang = readdir($dir_lang) )
	{
		if ( preg_match('#^lang_#i', $file_lang) && !is_file($phpbb_root_path . './language/' . $file_lang) && !is_link($phpbb_root_path . './language/' . $file_lang) )
		{
			$filename_lang = trim(str_replace('lang_', '', $file_lang));
			$displayname_lang = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename_lang);
			$displayname_lang = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname_lang);

			// store the result
			$langs[$filename_lang] = array( 'txt' => ucwords($displayname_lang) );
			$langs_name[] = ucwords($displayname_lang);
		}
	}
	closedir($dir_lang);
	@array_multisort($langs_name, $langs);

	return $langs;
}

//-------------------------------------------
//
//	timezone available
//
//-------------------------------------------
function get_timezones_list()
{
	global $lang;

	$tz = array('-12', '-11', '-10', '-9', '-8', '-7', '-6', '-5', '-4', '-3.5', '-3', '-2', '-1', '0', '1', '2', '3', '3.5', '4', '4.5', '5', '5.5', '6', '6.5', '7', '8', '9', '9.5', '10', '11', '12', '13' );

	$timezones = array();
	for ($i = 0; $i < count($tz); $i++)
	{
		$timezones[ $tz[$i] ] = array( 'txt' => $tz[$i], 'img' => 'tz_' . $tz[$i] );
	}

	return $timezones;
}

?>