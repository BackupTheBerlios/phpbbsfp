<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: lang_extend_mac.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_extend_mac.php
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
	exit;
}

if ( !defined('LANG_EXTEND_DONE') )
{
	// check for admin part
	$lang_extend_admin = defined('IN_ADMIN');
	global $board_config;
	// get the english settings
	if ( $board_config['default_lang'] != 'english' )
	{
		$dir = @opendir($phpbb_root_path . 'language/lang_english');
		while( $file = @readdir($dir) )
		{
			if( preg_match("/^lang_extend_.*?\." . $phpEx . "$/", $file) )
			{
				include_once($phpbb_root_path . 'language/lang_english/' . $file);
			}
		}
		// include the personalisations
		@include_once($phpbb_root_path . 'language/lang_english/lang_extend.' . $phpEx);
		@closedir($dir);
	}

	// get the user settings
	if ( !empty($board_config['default_lang']) )
	{
		$dir = @opendir($phpbb_root_path . 'language/lang_' . $board_config['default_lang']);
		while( $file = @readdir($dir) )
		{
			if( preg_match("/^lang_extend_.*?\." . $phpEx . "$/", $file) )
			{
				include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/' . $file);
			}
		}
		// include the personalisations
		@include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_extend.' . $phpEx);
		@closedir($dir);
	}
	define('LANG_EXTEND_DONE', true);
}

?>