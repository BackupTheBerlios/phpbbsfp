<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: mod_announces.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mod_announces.php
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
$mod_name = 'Announce_settings';
$sub_name = 'Board_announcement';
$config_fields = array(

	'announcement_date_display' => array(
		'lang_key'	=> 'announcement_date_display',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_announcement_date_display',
		'values'	=> $list_yes_no,
		),

	'announcement_display' => array(
		'lang_key'	=> 'announcement_display',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_announcement_display',
		'values'	=> $list_yes_no,
		),

	'announcement_display_forum' => array(
		'lang_key'	=> 'announcement_display_forum',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_announcement_display_forum',
		'values'	=> $list_yes_no,
		),

	'announcement_split' => array(
		'lang_key'	=> 'announcement_split',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_announcement_split',
		'values'	=> $list_yes_no,
		),

	'announcement_forum' => array(
		'lang_key'	=> 'announcement_forum',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_announcement_forum',
		'values'	=> $list_yes_no,
		),

	'announcement_duration' => array(
		'lang_key'	=> 'announcement_duration',
		'explain'	=> 'announcement_duration_explain',
		'type'		=> 'TINYINT',
		'default'	=> 7,
		),

	'announcement_prune_strategy' => array(
		'lang_key'	=> 'announcement_prune_strategy',
		'explain'	=> 'announcement_prune_strategy_explain',
		'type'		=> 'LIST_DROP',
		'default'	=> 'Post_Normal',
		'values'	=> array(
			'Post_Normal'	=> POST_NORMAL,
			'Post_Sticky'	=> POST_STICKY,
			),
		),
);

// init config table
init_board_config($mod_name, $config_fields, $sub_name);

?>