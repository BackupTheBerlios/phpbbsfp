<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: mod_post_icons.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mod_post_icons.php
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
	die("Hacking attempt");
}

// service functions
include_once( $phpbb_root_path . 'includes/functions_mods_settings.' . $phpEx );

// mod definition
$mod_name = 'Icons_settings';
$config_fields = array(

	'icon_per_row' => array(
		'lang_key'	=> 'Icons_per_row',
		'explain'	=> 'Icons_per_row_explain',
		'type'		=> 'TINYINT',
		'default'	=> '10',
		),
);

// init config table
init_board_config($mod_name, $config_fields);

?>