<?php

/***************************************************************************
 *						admin_mybbcode.php
 *						-----------------------------
 *	begin			: 7/12/2004
 *	copyright		: Fierce Recon
 *	email			: infected2506@hotmail.com
 *
 *	version			: 0.0.2 - 7/19/2004
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['MyBBCode']['MyBBCode_List_Codes'] = $file;
	$module['MyBBCode']['MyBBCode_Add_A_Code'] = $file . '?mode=addcode';
	$module['MyBBCode']['MyBBCode_Add_A_Code_for_Style'] = $file . '?mode=addstylecode';
	$module['MyBBCode']['MyBBCode_List_Style_Codes'] = $file . '?mode=liststylecodes';
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
$no_page_header = true;
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);
if ( !$board_config['allow_bbcode'] )
{
	message_die(GENERAL_MESSAGE, $lang['MyBBCode_bbcode_off']);
}
$MyBBCode_disabled = true;
$MyBBCode_styles = false;
require($phpbb_root_path . "mybbcode/funcs.inc.$phpEx");

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
}
else
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['addcode']) )
	{
		$mode = "addcode";
	}
	else if( isset($HTTP_POST_VARS['editcode']) )
	{
		$mode = "editcode";
	}
	else if( isset($HTTP_POST_VARS['deletecode']) )
	{
		$mode = "deletecode";
	}
	else if( isset($HTTP_POST_VARS['addstylecode']) )
	{
		$mode = "addstylecode";
	}
	else if( isset($HTTP_POST_VARS['editstylecode']) )
	{
		$mode = "editstylecode";
	}
	else if( isset($HTTP_POST_VARS['deletestylecode']) )
	{
		$mode = "deletestylecode";
	}
	else if( isset($HTTP_POST_VARS['savestylecode']) )
	{
		$mode = "savestylecode";
	}
	else if( isset($HTTP_POST_VARS['savecode']) )
	{
		$mode = "savecode";
	}
	else if( isset($HTTP_POST_VARS['movecode']) )
	{
		$mode = "movecode";
	}
	else
	{
		$mode = "";
	}
}

if ($mode != "movecode")
{
	include('./page_header_admin.'.$phpEx);
}

if ( $mode == 'do_delete' || $mode == 'do_style_delete')
{
	if ( !$HTTP_POST_VARS['confirm'] )
	{
		$mode = '';
	}
}

if ($mode == 'addcode' || $mode == 'editcode')
{
	$code_id = (isset($HTTP_GET_VARS['id'])) ? $HTTP_GET_VARS['id'] : 0;

	$hidden_fields = "";

	if ($mode == "edit")
	{
		$sql = 'SELECT * FROM ' . MYBBCODE_TABLE . '
						WHERE `id`=' . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$code_info = $db->sql_fetchrow($result);

		$hidden_fields .= '<input type="hidden" name="id" value="' . $code_id . '" />';
	}
	$hidden_fields .= '<input type="hidden" name="mode" value="savecode" />';

	$template->set_filenames(array(
		"body" => "mybbcode_edit_body.tpl")
	);

	$template->assign_vars(array(
		"NAME" => $code_info['name'],
		"OPEN_TAG" => $code_info['open_tag'],
		"CLOSE_TAG" => $code_info['close_tag'],

		"TAG_OPEN" => $code_info['tag_open'],
		"TAG_CLOSE" => $code_info['tag_close'],
		"ATTR_CHARS" => ( $code_info['attr_chars_pure'] == 0 ) ? MyBBCode_StripPregSlashes($code_info['attr_chars']) : $code_info['attr_chars'],
		"ATTR_CHARS_PURE" => ($code_info['attr_chars_pure']) ? " checked=\"checked\"" : "",
		"CONTENT_CHARS" => ( $code_info['content_chars_pure'] == 0 ) ? MyBBCode_StripPregSlashes($code_info['attr_chars']) : $code_info['attr_chars'],
		"CONTENT_CHARS_PURE" => ($code_info['content_chars_pure']) ? " checked=\"checked\"" : "",

		"INCLUDE_FILE" => $code_info['include_file'],
		"PARSE_FUNC_1" => $code_info['parse_func_1'],
		"PARSE_FUNC_2" => $code_info['parse_func_2'],

		"HELP" => $code_info['help'],
		"SHORTCUT_KEY" => $code_info['shortcut_key'],
		"QUICKTIP" => $code_info['quicktip'],
		//Added in 0.0.2
		"SHOW_BUTTON" => ($code_info['show_button'] || !$code_id) ? " checked=\"checked\"" : "",
		"STYLE" => $code_info['style'],
		//End Added

		"L_EXPLAIN_EDITADD" => $lang['MyBBCode_Explain_Edit_Add'],
		"L_EXPLAIN_ATTR_CHARS" => $lang['MyBBCode_Explain_Attr_Chars'],
		"L_EXPLAIN_TAG_OPEN" => $lang['MyBBCode_Explain_Tag_Open'],
		"L_EXPLAIN_TAG_CLOSE" => $lang['MyBBCode_Explain_Tag_Close'],
		"L_EXPLAIN_HELP" => $lang['MyBBCode_Explain_Help'],
		"L_EXPLAIN_SHORTCUT_KEY" => $lang['MyBBCode_Explain_Shortcut_Key'],
		"L_EXPLAIN_QUICKTIP" => $lang['MyBBCode_Explain_Quicktip'],
		//Added in 0.0.2
		"L_EXPLAIN_SHOW_BUTTON" => $lang['MyBBCode_Explain_Show_Button'],
		"L_EXPLAIN_OPEN_TAG" => $lang['MyBBCode_Explain_Open_Tag'],
		"L_EXPLAIN_CLOSE_TAG" => $lang['MyBBCode_Explain_Close_Tag'],
		"L_EXPLAIN_CONTENT_CHARS" => $lang['MyBBCode_Explain_Content_Chars'],
		"L_EXPLAIN_STYLE" => $lang['MyBBCode_Explain_Style_HTML_Tag'],
		"L_YES" => $lang['Yes'],
		//End Added

		"L_NAME" => $lang['MyBBCode_Name'],
		"L_OPEN_TAG" => $lang['MyBBCode_Open_Tag'],
		"L_CLOSE_TAG" => $lang['MyBBCode_Close_Tag'],
		"L_TAG_OPEN" => $lang['MyBBCode_Tag_Open'],
		"L_TAG_CLOSE" => $lang['MyBBCode_Tag_Close'],
		"L_ATTR_CHARS" => $lang['MyBBCode_Attr_Chars'],
		"L_ATTR_CHARS_PURE" => $lang['MyBBCode_Attr_Chars_Pure'],
		"L_INCLUDE_FILE" => $lang['MyBBCode_Include_File'],
		"L_PARSE_FUNC_1" => $lang['MyBBCode_Parse_Func_1'],
		"L_PARSE_FUNC_2" => $lang['MyBBCode_Parse_Func_2'],
		"L_HELP" => $lang['MyBBCode_Help'],
		"L_SHORTCUT_KEY" => $lang['MyBBCode_Shortcut_Key'],
		"L_QUICKTIP" => $lang['MyBBCode_Quicktip'],
		//Added in 0.0.2
		"L_SHOW_BUTTON" => $lang['MyBBCode_Show_Button'],
		"L_CONTENT_CHARS" => $lang['MyBBCode_Content_Chars'],
		"L_CONTENT_CHARS_PURE" => $lang['MyBBCode_Content_Chars_Pure'],
		"L_STYLE" => $lang['Style'],
		//End Added

		"L_OP" => ($mode == 'editcode') ? $lang['Edit'] : $lang['MyBBCode_Add'],
		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"S_ACTION" => append_sid("admin_mybbcode.$phpEx"),
		"S_HIDDEN_FIELDS" => $hidden_fields)
	);
}
else if ($mode == 'savecode')
{
	$name = ($HTTP_POST_VARS["name"] != "") ? htmlspecialchars($HTTP_POST_VARS["name"]) : "";
	$open_tag = ($HTTP_POST_VARS["open_tag"] != "") ? strtolower($HTTP_POST_VARS["open_tag"]) : "";
	$close_tag = ($HTTP_POST_VARS["close_tag"] != "") ? strtolower($HTTP_POST_VARS["close_tag"]) : "";
	$tag_open = ($HTTP_POST_VARS["tag_open"] != "") ? str_replace('\\\\', '\\\\\\\\', $HTTP_POST_VARS["tag_open"]) : "";
	$tag_close = ($HTTP_POST_VARS["tag_close"] != "") ? $HTTP_POST_VARS["tag_close"] : "";
	$attr_chars_pure = ($HTTP_POST_VARS["attr_chars_pure"] != "") ? 1 : 0;
	$attr_chars = (!$attr_chars_pure && $HTTP_POST_VARS["attr_chars"] != "") ? MyBBCode_AddPregSlashes($HTTP_POST_VARS["attr_chars"]) : $HTTP_POST_VARS["attr_chars"];
	$content_chars_pure = ($HTTP_POST_VARS["content_chars_pure"] != "") ? 1 : 0;
	$content_chars = (!$content_chars_pure && $HTTP_POST_VARS["content_chars"] != "") ? MyBBCode_AddPregSlashes($HTTP_POST_VARS["content_chars"]) : $HTTP_POST_VARS["content_chars"];
	$include_file = ($HTTP_POST_VARS["include_file"] != "") ? $HTTP_POST_VARS["include_file"] : "";
	$parse_func_1 = ($HTTP_POST_VARS["parse_func_1"] != "") ? $HTTP_POST_VARS["parse_func_1"] : "";
	$parse_func_2 = ($HTTP_POST_VARS["parse_func_2"] != "") ? $HTTP_POST_VARS["parse_func_2"] : "";
	$help = ($HTTP_POST_VARS["help"] != "") ? $HTTP_POST_VARS["help"] : "";
	$shortcut_key = ($HTTP_POST_VARS["shortcut_key"] != "") ? $HTTP_POST_VARS["shortcut_key"] : "";
	$quicktip = ($HTTP_POST_VARS["quicktip"] != "") ? $HTTP_POST_VARS["quicktip"] : "";
	$quicktip_pure = ($HTTP_POST_VARS["quicktip_pure"] != "") ? 1 : 0;
	$show_button = ($HTTP_POST_VARS["show_button"] != "") ? 1 : 0;
	$style = ($HTTP_POST_VARS["style"] != "") ? str_replace('"', '', $HTTP_POST_VARS["style"]) : "";
	$code_id = (isset($HTTP_POST_VARS['id'])) ? $HTTP_POST_VARS['id'] : 0;
	if ($attr_chars == '\.\*\?') $attr_chars = '.*?';	//Just a little fix, since this means any character any number of times
	if ($name == "" || $open_tag == "" || $close_tag == "" || $quicktip == "")
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}



	if ($include_file != "")
	{
		if (!file_exists($phpbb_root_path . 'mybbcode/' . $include_file))
		{
			message_die(GENERAL_MESSAGE, $lang['MyBBCode_Include_file_doesnt_exist']);
		}
		MyBBCode_Include($include_file);
		if (!function_exists($parse_func_1))
		{
			message_die(GENERAL_MESSAGE, $lang['MyBBCode_Parse_func_1_doesnt_exist']);
		}
		if (!function_exists($parse_func_2))
		{
			message_die(GENERAL_MESSAGE, $lang['MyBBCode_Parse_func_2_doesnt_exist']);
		}
	}

	if ($tag_open == "" && $tag_close == "" && $parse_func_2 == "" && $include_file == "")
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_No_Content_In_Tag']);
	}

	if (eregi("[^A-Z0-9_- ]", $name))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Illegal_chars_in_name']);
	}
	if (eregi("[^A-Z0-9_-]", $open_tag))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Illegal_chars_in_open_tag']);
	}
	if (eregi("[^A-Z0-9_-]", $close_tag))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Illegal_chars_in_close_tag']);
	}
	$usedopentags = array();
	$usedclosetags = array();
	$usedskeys = array();
	$usednames = array();
	$codes = MyBBCode_GetCodes();
	for ($i = 0; $i < count($codes); $i++)
	{
		$usedopentags[] = $codes[$i]['open_tag'];
		$usedclosetags[] = $codes[$i]['close_tag'];
		$usedskeys[] = $codes[$i]['shortcut_key'];
		$usednames[] = $codes[$i]['name'];
	}

	if (in_array_i($name, $usednames))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Name_already_in_use']);
	}
	if (in_array_i($open_tag, $usedopentags))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Open_Tag_already_in_use']);
	}
	if (in_array_i($close_tag, $usedclosetags))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Close_Tag_already_in_use']);
	}
	if (in_array_i($shortcut_key, $usedskeys) && $shortcut_key != "")
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Shortcut_Key_already_in_use']);
	}
	$quicktip = 'return \'' . ($quicktip_pure) ? stripslashes($quicktip) : $quicktup . '\';';
	if ($code_id)
	{
		$sql = "UPDATE " . MYBBCODE_TABLE . " SET
						`name`='" . str_replace("\'", "''", $name) . "',
						`open_tag`='" . str_replace("\'", "''", $open_tag) . "',
						`close_tag`='" . str_replace("\'", "''", $close_tag) . "',
						`tag_open`='" . str_replace("\'", "''", $tag_open) . "',
						`tag_close`='" . str_replace("\'", "''", $tag_close) . "',
						`attr_chars`='" . str_replace("\'", "''", $attr_chars) . "',
						`attr_chars_pure`=$attr_chars_pure,
						`content_chars`='" . str_replace("\'", "''", $content_chars) . "',
						`content_chars_pure`=$content_chars_pure,
						`include_file`='" . str_replace("\'", "''", $include_file) . "',
						`parse_func_1`='" . str_replace("\'", "''", $parse_func_1) . "',
						`parse_func_2`='" . str_replace("\'", "''", $parse_func_2) . "',
						`help`='" . str_replace("\'", "''", $help) . "',
						`shortcut_key`='" . str_replace("\'", "''", $shortcut_key) . "',
						`quicktip`='" . str_replace("\'", "''", $quicktip) . "',
						`quicktip_pure`=$quicktip_pure,
						`show_button`=$show_button,
						`style='" . str_replace("\'", "''", $style) . "',
						`disabled`=0
								WHERE `id`=" . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_BBCode_updated_successfully']);
	}
	else
	{
		// Cheap trick - MAN am I lazy
		$sql = "INSERT INTO " . MYBBCODE_TABLE . " SET
						`name`='" . str_replace("\'", "''", $name) . "',
						`open_tag`='" . str_replace("\'", "''", $open_tag) . "',
						`close_tag`='" . str_replace("\'", "''", $close_tag) . "',
						`tag_open`='" . str_replace("\'", "''", $tag_open) . "',
						`tag_close`='" . str_replace("\'", "''", $tag_close) . "',
						`attr_chars`='" . str_replace("\'", "''", $attr_chars) . "',
						`attr_chars_pure`=$attr_chars_pure,
						`content_chars`='" . str_replace("\'", "''", $content_chars) . "',
						`content_chars_pure`=$content_chars_pure,
						`include_file`='" . str_replace("\'", "''", $include_file) . "',
						`parse_func_1`='" . str_replace("\'", "''", $parse_func_1) . "',
						`parse_func_2`='" . str_replace("\'", "''", $parse_func_2) . "',
						`help`='" . str_replace("\'", "''", $help) . "',
						`shortcut_key`='" . str_replace("\'", "''", $shortcut_key) . "',
						`quicktip`='" . str_replace("\'", "''", $quicktip) . "',
						`quicktip_pure`=$quicktip_pure,
						`show_button`=$show_button,
						`style='" . str_replace("\'", "''", $style) . "'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't add bbcode", "", __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_BBCode_added_successfully']);
	}
}
else if( $mode == 'deletecode' )
{
	if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
	{
		$code_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
	}
	else
	{
		$code_id = 0;
	}
	$hidden_fields = '<input type="hidden" name="id" value="' . $code_id . '" /><input type="hidden" name="mode" value="do_delete" />';

	//
	// Set template files
	//
	$template->set_filenames(array(
		'body' => 'confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['MyBBCode_confirm_delete'],
		'MESSAGE_TEXT' => $lang['MyBBCode_confirm_delete_text'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid("admin_mybbcode.$phpEx"),
		'S_HIDDEN_FIELDS' => $hidden_fields)
	);
}
else if( $mode == 'do_delete' )
{
	if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
	{
		$code_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
	}
	else
	{
		$code_id = 0;
	}

	if( $code_id )
	{
		$sql = 'SELECT * FROM ' . MYBBCODE_TABLE . '
						WHERE `id`=' . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$code_info = $db->sql_fetchrow($result);
		$sql = "DELETE FROM " . MYBBCODE_TABLE . "
			WHERE `id` = $code_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete bbcode data", "", __LINE__, __FILE__, $sql);
		}
		$sql = "DELETE FROM " . MYBBCODE_STYLE_TABLE . "
			WHERE `code` = $code_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . MYBBCODE_TABLE . " SET show_order=show_order-1
			WHERE show_order > $code_info[show_order]";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . MYBBCODE_TABLE . " SET proc_order=proc_order-1
			WHERE proc_order > $code_info[proc_order]";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		$message = $lang['MyBBCode_code_removed'] . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Must_select_code']);
	}
}
else if ( $mode == 'movecode' )
{
	$direction = ($HTTP_GET_VARS['direction'] == "up" || $HTTP_GET_VARS['direction'] == "down") ? $HTTP_GET_VARS['direction'] : "";
	if( isset($HTTP_GET_VARS['id']) )
	{
		$code_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
	}
	else
	{
		$code_id = 0;
	}
	$sql = "SELECT * FROM " . MYBBCODE_TABLE . "
				WHERE `id`=$code_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get bbcode data", "", __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	if ($direction == "up")
	{		
		$sql = "UPDATE " . MYBBCODE_TABLE . "
					SET `show_order`=`show_order`+1 WHERE `show_order`=" . $row['show_order'] . "-1";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		
		$sql = "UPDATE " . MYBBCODE_TABLE . "
					SET `show_order`=`show_order`-1 WHERE `id`=$code_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
	}
	else if ($direction == "down")
	{
		$sql = "UPDATE " . MYBBCODE_TABLE . "
					SET `show_order`=`show_order`-1 WHERE `show_order`=" . $row['show_order'] . "+1";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		
		$sql = "UPDATE " . MYBBCODE_TABLE . "
					SET `show_order`=`show_order`+1 WHERE `id`=$code_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		
	}
	else
		die("Invalid Direction!");
	redirect(append_sid("admin/admin_mybbcode.$phpEx", true));
}
else if ( $mode == 'editstylecode' || $mode == 'addstylecode')
{
	$code_id = (isset($HTTP_GET_VARS['id'])) ? $HTTP_GET_VARS['id'] : 0;

	$hidden_fields = "";
	$real_code_info = array();

	if ($mode == "editstylecode")
	{
		$sql = 'SELECT * FROM ' . MYBBCODE_STYLE_TABLE . '
						WHERE `id`=' . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$code_info = $db->sql_fetchrow($result);

		$sql = 'SELECT * FROM ' . MYBBCODE_TABLE . '
						WHERE `id`=' . $code_info['code'];
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$real_code_info = $db->sql_fetchrow($result);

		$hidden_fields .= '<input type="hidden" name="id" value="' . $code_id . '" />';
	}
	$hidden_fields .= '<input type="hidden" name="mode" value="savestylecode" />';
	$MyBBCode_styles = false;
	$codes = MyBBCode_GetCodes('show_order ASC');

	$template->set_filenames(array(
		"body" => "mybbcode_edit_style_body.tpl")
	);

	$bbcode_list = "";
	for ($i = 0; $i < count($codes); $i++)
	{
		$selected = ($code_info['code'] == $codes[$i]['id']) ? " selected=\"selected\"" : "";
		$bbcode_list .= "<option value=\"" . $codes[$i]['id'] . "\"$selected>" . $codes[$i]['name'] . "</option>\n";
	}

	$sql = "SELECT themes_id, template_name FROM " . THEMES_TABLE;

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain styles data", "", __LINE__, __FILE__, $sql);
	}
	$style_list = "";
	while($row = $db->sql_fetchrow($result))
	{
		$selected = ($code_info['style'] == $row['themes_id']) ? " selected=\"selected\"" : "";
		$style_list .= "<option value=\"" . $row['themes_id'] . "\"$selected>" . $row['template_name'] . "</option>\n";
	}

	$template->assign_vars(array(
		"TAG" => $code_info['tag'],

		"TAG_OPEN" => $code_info['tag_open'],
		"TAG_CLOSE" => $code_info['tag_close'],
		"BBCODE_LIST" => $bbcode_list,
		"STYLE_LIST" => $style_list,

		"L_EXPLAIN" => $lang['MyBBCode_Explain_Edit_Add_Style'],
		"L_EXPLAIN_TAG" => $lang['MyBBCode_Explain_Tag'],

		"L_NAME" => $lang['MyBBCode_Name'],
		"L_TAG" => $lang['MyBBCode_Tag'],
		"L_TAG_OPEN" => $lang['MyBBCode_Tag_Open'],
		"L_TAG_CLOSE" => $lang['MyBBCode_Tag_Close'],
		"L_STYLE" => $lang['Style'],

		"L_OP" => ($mode == 'editstylecode') ? $lang['Edit'] : $lang['MyBBCode_Add'],
		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"S_ACTION" => append_sid("admin_mybbcode.$phpEx"),
		"S_HIDDEN_FIELDS" => $hidden_fields)
	);
}
else if ($mode == 'savestylecode')
{
	$tag = ($HTTP_POST_VARS["tag"] != "") ? strtolower($HTTP_POST_VARS["tag"]) : "";
	$tag_open = ($HTTP_POST_VARS["tag_open"] != "") ? str_replace('\\\\', '\\\\\\\\', $HTTP_POST_VARS["tag_open"]) : "";
	$tag_close = ($HTTP_POST_VARS["tag_close"] != "") ? $HTTP_POST_VARS["tag_close"] : "";
	$code_id = (isset($HTTP_POST_VARS['id'])) ? (int) $HTTP_POST_VARS['id'] : 0;
	$bbcode_id = (isset($HTTP_POST_VARS['bbcode_id'])) ? (int) $HTTP_POST_VARS['bbcode_id'] : 0;
	$style_id = (isset($HTTP_POST_VARS['style_id'])) ? (int) $HTTP_POST_VARS['style_id'] : 0;

	if ($tag == "" || ($tag_open == "" && $tag_close == "") || !$bbcode_id || !$style_id)
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}

	$sql = 'SELECT * FROM ' . MYBBCODE_TABLE . '
					WHERE `id`=' . $bbcode_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
	}

	$real_code_info = $db->sql_fetchrow($result);

	if (!$real_code_info['id'])
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}

	if (eregi("[^A-Z0-9_-]", $tag))
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Illegal_chars_in_tag']);
	}

	$sql = 'SELECT * FROM ' . MYBBCODE_STYLE_TABLE . '
					WHERE `style`=' . $style_id . ' && `code`=' . $bbcode_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result))
	{
		$row = $db->sql_fetchrow($result);
		if ($code_id != $row['id'])
		{
			message_die(GENERAL_MESSAGE, $lang['MyBBCode_Styled_BBCode_Already_Exists']);
		}
	}
	if ($code_id)
	{
		$sql = "UPDATE " . MYBBCODE_STYLE_TABLE . " SET
						`tag`='" . str_replace("\'", "''", $tag) . "',
						`tag_open`='" . str_replace("\'", "''", $tag_open) . "',
						`tag_close`='" . str_replace("\'", "''", $tag_close) . "',
						`code`=$bbcode_id,
						`style`=$style_id
								WHERE `id`=" . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update bbcode data", "", __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_BBCode_style_updated_successfully']);
	}
	else
	{
		// Cheap trick - MAN am I lazy
		$sql = "INSERT INTO " . MYBBCODE_STYLE_TABLE . " SET
						`tag`='" . str_replace("\'", "''", $tag) . "',
						`tag_open`='" . str_replace("\'", "''", $tag_open) . "',
						`tag_close`='" . str_replace("\'", "''", $tag_close) . "',
						`code`=$bbcode_id,
						`style`=$style_id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't add bbcode", "", __LINE__, __FILE__, $sql);
		}
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_BBCode_style_added_successfully']);
	}
}
else if( $mode == 'deletestylecode' )
{
	if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
	{
		$code_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
	}
	else
	{
		$code_id = 0;
	}
	$hidden_fields = '<input type="hidden" name="id" value="' . $code_id . '" /><input type="hidden" name="mode" value="do_style_delete" />';

	//
	// Set template files
	//
	$template->set_filenames(array(
		'body' => 'confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['MyBBCode_confirm_style_delete'],
		'MESSAGE_TEXT' => $lang['MyBBCode_confirm_style_delete_text'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid("admin_mybbcode.$phpEx"),
		'S_HIDDEN_FIELDS' => $hidden_fields)
	);
}
else if( $mode == 'do_style_delete' )
{
	if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
	{
		$code_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
	}
	else
	{
		$code_id = 0;
	}

	if( $code_id )
	{
		$sql = 'SELECT * FROM ' . MYBBCODE_STYLE_TABLE . '
						WHERE `id`=' . $code_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql);
		}

		$code_info = $db->sql_fetchrow($result);
		$sql = "DELETE FROM " . MYBBCODE_STYLE_TABLE . "
			WHERE `id` = $code_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't delete bbcode data", "", __LINE__, __FILE__, $sql);
		}
		$message = $lang['MyBBCode_style_code_removed'] . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['MyBBCode_Must_select_style_code']);
	}
}
else if ( $mode == 'liststylecodes' )
{
	$template->set_filenames(array(
		"body" => "mybbcode_list_style_body.tpl")
	);
	$template->assign_vars(array(
							"L_EXPLAIN" => $lang['MyBBCode_Explain_List_styles'],
							"L_TAG" => $lang['MyBBCode_Tag'],
							"L_STYLE" => $lang['Style'],
							"L_EDIT" => $lang['Edit'],
							"L_DELETE" => $lang['Delete'])
							);

	$sql = "SELECT * FROM " . MYBBCODE_STYLE_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get bbcode data", "", __LINE__, __FILE__, $sql);
	}
	for ($i = 0; $row = $db->sql_fetchrow($result); $i++)
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$sql2 = "SELECT template_name FROM " . THEMES_TABLE . " WHERE themes_id=" . $row['style'];
		if(!$result2 = $db->sql_query($sql2))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql2);
		}
		$style_name = $db->sql_fetchrow($result2);
		$style_name = $style_name['template_name'];
		$sql2 = "SELECT name FROM " . MYBBCODE_TABLE . " WHERE id=" . $row['code'];
		if(!$result2 = $db->sql_query($sql2))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain bbcode data", "", __LINE__, __FILE__, $sql2);
		}
		$code_name = $db->sql_fetchrow($result2);
		$code_name = $code_name['name'];
		$template->assign_block_vars('mybbcode_code', array(
									"ID" => $row['id'],
									"TAG" => $row['tag'],
									"OPEN_TAG" => $row['open_tag'],
									"CLOSE_TAG" => $row['close_tag'],
									"STYLE" => $style_name,
									"CODE" => $code_name,

									"ROW_COLOR" => '#' . $row_color,
									"ROW_CLASS" => $row_class,
									"U_EDIT_LINK" => "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=editstylecode&id=" . $row['id']) . "\">" . $lang['Edit'] . "</a>",
									"U_DELETE_LINK" => "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=deletestylecode&id=" . $row['id'])."\">" . $lang['Delete'] . "</a>")
									);
	}
}
else
{
	$template->set_filenames(array(
		"body" => "mybbcode_list_body.tpl")
	);
	$template->assign_vars(array(
							"L_MOVE_UP" => $lang['Move_up'],
							"L_MOVE_DOWN" => $lang['Move_down'],
							"L_EXPLAIN" => $lang['MyBBCode_Explain_List'],
							"L_NAME" => $lang['MyBBCode_Name'],
							"L_OPEN_TAG" => $lang['MyBBCode_Open_Tag'],
							"L_CLOSE_TAG" => $lang['MyBBCode_Close_Tag'],
							"L_ATTR_CHARS" => $lang['MyBBCode_Attr_Chars'],
							"L_CONTENT_CHARS" => $lang['MyBBCode_Content_Chars'],
							"L_INCLUDE_FILE" => $lang['MyBBCode_Include_File'],
							"L_PARSE_FUNC_1" => $lang['MyBBCode_Parse_Func_1'],
							"L_PARSE_FUNC_2" => $lang['MyBBCode_Parse_Func_2'],
							"L_SHORTCUT_KEY" => $lang['MyBBCode_Shortcut_Key'],
							"L_SHOW_BUTTON" => $lang['MyBBCode_Show_Button'],
							"L_EDIT" => $lang['Edit'],
							"L_DELETE" => $lang['Delete'],
							"L_DISABLED" => $lang['Disabled'])
							);
	$codes = MyBBCode_GetCodes('show_order ASC');
	for ($i = 0; $i < count($codes); $i++)
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('mybbcode_code', array(
									"ID" => $codes[$i]['id'],
									"NAME" => $codes[$i]['name'],
									"OPEN_TAG" => $codes[$i]['open_tag'],
									"CLOSE_TAG" => $codes[$i]['close_tag'],

									"ATTR_CHARS" => ( !$codes[$i]['attr_chars_pure'] ) ? MyBBCode_StripPregSlashes($codes[$i]['attr_chars']) : $codes[$i]['attr_chars'],
									"CONTENT_CHARS" => ( !$codes[$i]['content_chars_pure'] ) ? MyBBCode_StripPregSlashes($codes[$i]['content_chars']) : $codes[$i]['content_chars'],

									"INCLUDE_FILE" => $codes[$i]['include_file'],
									"PARSE_FUNC_1" => $codes[$i]['parse_func_1'],
									"PARSE_FUNC_2" => $codes[$i]['parse_func_2'],

									"SHORTCUT_KEY" => $codes[$i]['shortcut_key'],
									"SHOW_BUTTON" => ($codes[$i]['show_button']) ? $lang['Yes'] : $lang['No'],
									"DISABLED" => ($codes[$i]['disabled']) ? $lang['Yes'] : $lang['No'],
									"ROW_COLOR" => '#' . $row_color,
									"ROW_CLASS" => $row_class,
									"U_EDIT_LINK" => "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=editcode&id=" . $codes[$i]['id']) . "\">" . $lang['Edit'] . "</a>",
									"U_DELETE_LINK" => "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=deletecode&id=" . $codes[$i]['id']) . "\">" . $lang['Delete'] . "</a>",
									"U_MOVE_UP_LINK" => ($i != 0) ? "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=movecode&direction=up&id=" . $codes[$i]['id']) . "\">" . $lang['Move_up'] . "</a>" : "",
									"U_MOVE_DOWN_LINK" => ($i != count($codes)-1) ? "<a href=\"" . append_sid("admin_mybbcode.$phpEx?mode=movecode&direction=down&id=" . $codes[$i]['id']) . "\">" . $lang['Move_down'] . "</a>" : "",)
									);
	}
}
$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

/*****************************************************************/
/*																 */
/* Taken from http://us2.php.net/manual/en/function.in-array.php */
/* in_array case insensetive by timmie at goliathdesigns dot nl  */
/* 					modified by Fierce Recon					 */
/*																 */
/*****************************************************************/

function in_array_i($item, $array)
{
	foreach($array as $arrayvalue)
	{
		if(strtoupper($item) == strtoupper($arrayvalue))
		{
			return true;
		}
	}
	return false;
}
?>
