<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: mod_advanced_postcount.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mod_advanced_postcount.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2004		Andrey Babak <minerva@club60.org>
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// service functions
include_once($phpbb_root_path . 'includes/functions_mods_settings.' . $phpEx);

// mod definition
$mod_name = 'Advanced_Postcount';
$config_fields = array(
	'no_post_count_forum_id' => array(
		'lang_key'	=> 'no_post_count',
		'explain'	=> 'no_post_count_explain',
		'type'		=> 'VARCHAR',
		'action'	=> 'resync_post_count',
		'default'	=> '',
		),
);

// init config table
init_board_config($mod_name, $config_fields);

?>