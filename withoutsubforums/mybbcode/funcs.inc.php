<?php

/***************************************************************************
 *						funcs.inc.php
 *						-----------------------------
 *	begin			: 7/12/2004
 *	copyright		: Fierce Recon
 *	email			: infected2506@hotmail.com
 *
 *	version			: 0.0.1-alpha.1 - 7/12/2004
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

$MyBBCode_codes = array();

/*function MyBBCode_Init($even_disabled = false, $order_by = 'id ASC', $styles = true)
{
	global $db, $MyBBCode_codes, $userdata, $board_config;
	$sql = "SELECT * FROM " . MYBBCODE_TABLE;
	if (!$even_disabled) $sql .= " WHERE `disabled` != 1 ORDER BY $order_by";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't get a list of bbcodes", "", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$style = ($userdata['user_style']) ? $userdata['user_style'] : $board_config['default_style'];
		$sql2 = "SELECT tag_open, tag_close, tag FROM " . MYBBCODE_STYLE_TABLE . " WHERE `code`=$row[id] && `style`=".$style;
		if (!$result2 = $db->sql_query($sql2))
		{
			message_die(GENERAL_ERROR, "Couldn't get a list of bbcodes for theme", "", __LINE__, __FILE__, $sql2);
		}
		$MyBBCode_codes[$order_by][] = $row;
		if ($db->sql_numrows($result2) && $styles)
		{
			while ($row2 = $db->sql_fetchrow($result2))
			{
				// Our style table has 2 possabilites
				// a. Style differences
				// b. Extra styles needed by some tag (eg. quote)
				if ($row['open_tag'] == $row2['tag'])
				{
					$MyBBCode_codes[$order_by][count($MyBBCode_codes[$order_by])-1]['tag_open'] = $row2['tag_open'];
					$MyBBCode_codes[$order_by][count($MyBBCode_codes[$order_by])-1]['tag_close'] = $row2['tag_close'];
					continue;
				}
				$MyBBCode_codes[$order_by][] = array('show_button' => 0) + $row2 + $row;	//ORDER IS IMPORTANT!!!
			}
		}
	}
}
*/

function MyBBCode_GetCodes($order_by = 'id ASC')
{
	global $board_config, $MyBBCode_codes, $MyBBCode_disabled, $MyBBCode_styles, $userdata, $MyBBCode_code, $db;
	if ( !$board_config['allow_bbcode'] )
		return array();
	if (!isset($MyBBCode_codes[$order_by]))
	{
		$sql = "SELECT * FROM " . MYBBCODE_TABLE;
		if (!$MyBBCode_disabled) $sql .= " WHERE `disabled` != 1";
		$sql .= " ORDER BY $order_by";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't get a list of bbcodes", "", __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$style = ($userdata['user_style']) ? $userdata['user_style'] : $board_config['default_style'];
			$sql2 = "SELECT * FROM " . MYBBCODE_STYLE_TABLE . " WHERE `code`=$row[id] && `style`=".$style;
			if (!$result2 = $db->sql_query($sql2))
			{
				message_die(GENERAL_ERROR, "Couldn't get a list of bbcodes for theme", "", __LINE__, __FILE__, $sql2);
			}
			if ($MyBBCode_code)
				$MyBBCode_codes[$order_by][] = $row;
			if ($db->sql_numrows($result2) && $MyBBCode_styles)
			{
				while ($row2 = $db->sql_fetchrow($result2))
				{
					// Our style table has 2 possabilites
					// a. Style differences
					// b. Extra styles needed by some tag (eg. quote)
					if ($row['open_tag'] == $row2['tag'])
					{
						$MyBBCode_codes[$order_by][count($MyBBCode_codes[$order_by])-1]['tag_open'] = $row2['tag_open'];
						$MyBBCode_codes[$order_by][count($MyBBCode_codes[$order_by])-1]['tag_close'] = $row2['tag_close'];
						continue;
					}
					$MyBBCode_codes[$order_by][] = $row2;
				}
			}
		}
	}
	return $MyBBCode_codes[$order_by];
}

function MyBBCode_Include($include_file)
{
	global $db, $lang, $phpbb_root_path;
	$file = $phpbb_root_path . 'mybbcode/' . $include_file;
	if (!file_exists($file))
	{
		$sql = "UPDATE " . MYBBCODE_TABLE . " SET `disabled`=1 WHERE `include_file` = '$include_file'";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode info", "", __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_ERROR, $lang['MyBBCode_Include_file_doesnt_exist']);
	}
	return include_once($file);
}

function MyBBCode_AddPregSlashes($string)
{
	$characters = array("\\" => "\\\\",
						"^" => "\\^",
						"$" => "\\$",
						"*" => "\\*",
						"=" => "\\=",
						"[" => "\\[",
						"]" => "\\]",
						"(" => "\\(",
						")" => "\\)",
						"}" => "\\}",
						"{" => "\\{",
						"!" => "\\!",
						"<" => "\\<",
						">" => "\\>",
						"|" => "\\|",
						"." => "\\.",
						":" => "\\:",
						"+" => "\\+", 
						"/" => "\\/",
						"#" => "\\#");
	$search = array_keys($characters);
	$replacement = array_values($characters);
	for ($i = 0; $i < count($search); $i++)
	{
		$string = str_replace($search[$i], $replacement[$i], $string);
	}
	return $string;
}

function MyBBCode_StripPregSlashes($string)
{
	$characters = array("\\" => "\\\\",
						"^" => "\\^",
						"$" => "\\$",
						"*" => "\\*",
						"=" => "\\=",
						"[" => "\\[",
						"]" => "\\]",
						"(" => "\\(",
						")" => "\\)",
						"}" => "\\}",
						"{" => "\\{",
						"!" => "\\!",
						"<" => "\\<",
						">" => "\\>",
						"|" => "\\|",
						"." => "\\.",
						":" => "\\:",
						"+" => "\\+", 
						"/" => "\\/",
						"#" => "\\#");
	$search = array_values($characters);
	$replacement = array_keys($characters);
	for ($i = 0; $i < count($search); $i++)
	{
		$string = str_replace($search[$i], $replacement[$i], $string);
	}
	return $string;
}

function MyBBCode_proccess_file($file)
{
	//Most of this function is from phpBB's load_bbcode_tpl() and prepare_bbcode_template()
	global $template;
	$tpl_filename = $template->make_filename($file);
	$tpl = fread(fopen($tpl_filename, 'r'), filesize($tpl_filename));

	// replace \ with \\ and then ' with \'.
	$tpl = str_replace('\\', '\\\\', $tpl);
	$tpl  = str_replace('\'', '\\\'', $tpl);

	// strip newlines.
	$tpl  = str_replace("\n", '', $tpl);

	// Turn template blocks into PHP assignment statements for the values of $bbcode_tpls..
	$tpl = preg_replace('#<!-- BEGIN (.*?) -->(.*?)<!-- END (.*?) -->#', "\n" . '$bbcode_tpls[\'\\1\'] = \'\\2\';', $tpl);

	$bbcode_tpl = array();

	eval($tpl);

	$bbcode_tpl['img'] = explode('{URL}', $bbcode_tpl['img']);
	$bbcode_tpl['img_open'] = $bbcode_tpl['img'][0];
	$bbcode_tpl['img_close'] = $bbcode_tpl['img'][1];
	unset($bbcode_tpl['img']);

	$bbcode_tpl['url'] = str_replace('{URL}', '{TAG_ATTR}', $bbcode_tpl['url']);
	$bbcode_tpl['url'] = explode('{DESCRIPTION}', $bbcode_tpl['url']);
	$bbcode_tpl['url_open'] = $bbcode_tpl['url'][0];
	$bbcode_tpl['url_close'] = $bbcode_tpl['url'][1];
	unset($bbcode_tpl['url']);
	
	$bbcode_tpl['email'] = explode('{EMAIL}', $bbcode_tpl['email']);
	$bbcode_tpl['email_open'] = $bbcode_tpl['email'][0] . "{EMAIL}" . $bbcode_tpl['email'][1];
	$bbcode_tpl['email_close'] = $bbcode_tpl['email'][2];
	unset($bbcode_tpl['email']);
	
	$mybbcode_tpl = array();
	foreach($bbcode_tpl as $name => $value)
	{
		if (!empty($value))
			$mybbcode_tpl[] = array('name' => $name, 'content' => $value);
	}
	
	return $mybbcode_tpl;
}
if (!isset($MyBBCode_disabled)) $MyBBCode_disabled = false;
if (!isset($MyBBCode_styles)) $MyBBCode_styles = true;
if (!isset($MyBBCode_code)) $MyBBCode_code = true;
?>