<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: mod_categories_hierarchy.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mod_categories_hierarchy.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team
//		     : � 2001, 2003 The phpBB Group
//           : � 2003 		Ptirhiik
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
$mod_name = 'Hierarchy_setting';
$config_fields = array(

	'sub_forum' => array(
		'lang_key'	=> 'Use_sub_forum',
		'explain'	=> 'Index_packing_explain',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Medium',
		'user'		=> 'user_sub_forum',
		'values'	=> array(
			'None'		=> 0,
			'Medium'	=> 1,
			'Full'		=> 2,
			),
		),

	'split_cat' => array(
		'lang_key'	=> 'Split_categories',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_split_cat',
		'values'	=> $list_yes_no,
		),

	'last_topic_title' => array(
		'lang_key'	=> 'Use_last_topic_title',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Yes',
		'user'		=> 'user_last_topic_title',
		'values'	=> $list_yes_no,
		),

	'last_topic_title_length' => array(
		'lang_key'	=> 'Last_topic_title_length',
		'type'		=> 'TINYINT',
		'default'	=> 24,
		),

	'sub_level_links' => array(
		'lang_key'	=> 'Sub_level_links',
		'explain'	=> 'Sub_level_links_explain',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'With_pics',
		'user'		=> 'user_sub_level_links',
		'values'	=> array(
			'No'		=> 0,
			'Yes'		=> 1,
			'With_pics'	=> 2,
			),
		),

	'display_viewonline' => array(
		'lang_key'	=> 'Display_viewonline',
		'type'		=> 'LIST_RADIO',
		'default'	=> 'Always',
		'user'		=> 'user_display_viewonline',
		'values'	=> array(
			'Never'				=> 0,
			'Root_index_only'	=> 1,
			'Always'			=> 2,
			),
		),
);

// init config table
init_board_config($mod_name, $config_fields);

?>