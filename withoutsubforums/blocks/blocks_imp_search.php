<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_search.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_search.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

if(!function_exists('imp_search_block_func'))
{
	function imp_search_block_func()
	{
		global $lang, $template, $portal_config, $board_config;

		$template->assign_vars(array(
			"L_ADVANCED_SEARCH" => $lang['Advanced_search'],
			"L_FORUM_OPTION" => (!empty($portal_config['md_search_option_text'])) ? $portal_config['md_search_option_text'] : $board_config ['sitename']
			)
		);
	}
}

imp_search_block_func();
?>