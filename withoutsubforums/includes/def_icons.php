<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: def_icons.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : def_icons.php
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

$icones = array(
		array(
			'ind'	=> 1,
			'img'	=> 'post_icon_1',
			'alt'	=> 'icon_note',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 2,
			'img'	=> 'post_icon_2',
			'alt'	=> 'icon_important',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 3,
			'img'	=> 'post_icon_3',
			'alt'	=> 'icon_idea',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 4,
			'img'	=> 'post_icon_4',
			'alt'	=> 'icon_warning',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 5,
			'img'	=> 'post_icon_5',
			'alt'	=> 'icon_question',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 6,
			'img'	=> 'post_icon_6',
			'alt'	=> 'icon_cool',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 7,
			'img'	=> 'post_icon_7',
			'alt'	=> 'icon_funny',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 8,
			'img'	=> 'post_icon_8',
			'alt'	=> 'icon_angry',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 9,
			'img'	=> 'post_icon_9',
			'alt'	=> 'icon_sad',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 10,
			'img'	=> 'post_icon_10',
			'alt'	=> 'icon_mocker',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 11,
			'img'	=> 'post_icon_11',
			'alt'	=> 'icon_shocked',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 12,
			'img'	=> 'post_icon_12',
			'alt'	=> 'icon_complicity',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 13,
			'img'	=> 'post_icon_13',
			'alt'	=> 'icon_bad',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 14,
			'img'	=> 'post_icon_14',
			'alt'	=> 'icon_great',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 15,
			'img'	=> 'post_icon_15',
			'alt'	=> 'icon_disgusting',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 16,
			'img'	=> 'post_icon_16',
			'alt'	=> 'icon_winner',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 17,
			'img'	=> 'post_icon_17',
			'alt'	=> 'icon_impressed',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 18,
			'img'	=> 'post_icon_18',
			'alt'	=> 'icon_roleplay',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 19,
			'img'	=> 'post_icon_19',
			'alt'	=> 'icon_fight',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 20,
			'img'	=> 'post_icon_20',
			'alt'	=> 'icon_loot',
			'auth'	=> AUTH_ALL,
	),
		array(
			'ind'	=> 21,
			'img'	=> 'post_icon_21',
			'alt'	=> 'icon_picture',
			'auth'	=> AUTH_MOD,
	),
		array(
			'ind'	=> 22,
			'img'	=> 'post_icon_22',
			'alt'	=> 'icon_calendar',
			'auth'	=> AUTH_MOD,
	),
		array(
			'ind'	=> 0,
			'img'	=> '',
			'alt'	=> 'icon_none',
			'auth'	=> AUTH_ALL,
	),
	);

// definition of special topic
$icon_defined_special = array(
		'POST_ATTACHMENT' => array(
		'lang_key'	=> 'Sort_Attachments',
		'icon'		=> 0,
	),
		'POST_PICTURE' => array(
		'lang_key'	=> 'Pic_album',
		'icon'		=> 0,
	),
		'POST_CALENDAR' => array(
		'lang_key'	=> 'Calendar',
		'icon'		=> 0,
	),
		'POST_BIRTHDAY' => array(
		'lang_key'	=> 'Birthday',
		'icon'		=> 0,
	),
		'POST_GLOBAL_ANNOUNCE' => array(
		'lang_key'	=> 'Post_Global_Announcement',
		'icon'		=> 0,
	),
		'POST_ANNOUNCE' => array(
		'lang_key'	=> 'Post_Announcement',
		'icon'		=> 0,
	),
		'POST_STICKY' => array(
		'lang_key'	=> 'Post_Sticky',
		'icon'		=> 0,
	),
		'POST_NORMAL' => array(
		'lang_key'	=> 'Post_Normal',
		'icon'		=> 0,
	),
	);

?>