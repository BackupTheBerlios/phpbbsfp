<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_rate.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_post_count_resync.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Rating_Configuration'] = $filename . "?mode=config";
	$module['Forums']['Rating_Authorization'] = $filename . "?mode=auth";
	return;
}

$phpbb_root_path = "./../";
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rate.' . $phpEx);
require($phpbb_root_path . 'includes/functions_rate.php');

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = "";
}

if( isset($HTTP_POST_VARS['admin_message']) || isset($HTTP_GET_VARS['admin_message']) )
{
	$admin_message = ( isset($HTTP_POST_VARS['admin_message']) ) ? $HTTP_POST_VARS['admin_message'] : $HTTP_GET_VARS['admin_message'];
}
else
{
	$admin_message = "";
}

//
//Begin Config Mode
//
if ($mode == 'config')
{

	$configs_name = array(
	allow_ext_rating,
	rating_max,
	allow_rerate,
	check_anon_ip_when_rating,
	min_rates_number,
	index_rating_return,
	large_rating_return_limit,
	header_rating_return_limit);

	$configs_desc = array(
	$lang['Allow_Detailed_Ratings_Page'],
	$lang['Max_Rating'],
	$lang['Allow_Users_To_ReRate'],
	$lang['Check_Anon_IP'],
	$lang['Min_Rates'],
	$lang['Main_Page_Number'],
	$lang['Big_Page_Number'],
	$lang['Header_Page_Number']);

	$sql = "SELECT *
		FROM " . CONFIG_TABLE;
	if(!$result	= $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR,	"Could not query config	information", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row	= $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value =	$row['config_value'];
			$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name])	) ?	$HTTP_POST_VARS[$config_name] :	$default_config[$config_name];

			if ($config_name ==	'cookie_name')
			{
				$cookie_name = str_replace('.',	'_', $new['cookie_name']);
			}

			if(	isset($HTTP_POST_VARS['submit']) )
			{
				if ( $sql =	set_config($config_name, str_replace("\'", "''", $new[$config_name])) )
				{
					message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__,	__FILE__, $sql);
				}
			}
	}

	if(	isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Config_updated'] . "<br /><br	/>"	. sprintf($lang['Update'],	"<a	href=\"" . append_sid("admin_rate.$phpEx") . "\">", "</a>") .	"<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\""	. append_sid("index.$phpEx?pane=right")	. "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}
}

	// exmaple
	//$disable_board_yes = ( $new['board_disable'] ) ? "checked=\"checked\"" : "";
	//$disable_board_no =	( !$new['board_disable'] ) ? "checked=\"checked\"" : "";

	$allow_ext_rating_yes = ( $new['allow_ext_rating'] ) ? 'checked="checked"' : '';
	$allow_ext_rating_no  = ( !$new['allow_ext_rating'] ) ? 'checked="checked"' : '';
	$allow_rerate_yes = ( $new['allow_rerate'] ) ? 'checked="checked"' : '';
	$allow_rerate_no  = ( !$new['allow_rerate'] ) ? 'checked="checked"' : '';

	$allow_ext_rating = '<input type="radio" name="allow_ext_rating" value="1" '. $allow_ext_rating_yes . ' /> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="allow_ext_rating" value="0" '. $allow_ext_rating_no . ' /> ' . $lang['No'];

	$allow_rerate = '<input type="radio" name="allow_rerate" value="1" '. $allow_rerate_yes . ' /> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="allow_rerate" value="0" '. $allow_rerate_no . ' /> ' . $lang['No'];
	$max_rating = '<input type="text" size="3" maxlength="3" name="rating_max" value="'. $new['rating_max'] . '" />';
	$hidden_submits = '<input type="hidden" name="mode" value="config" />';
	$check_anon_ip_yes = ( $new['check_anon_ip_when_rating'] != '0' ) ? 'checked="checked"' : '';
	$check_anon_ip_no  = ( $new['check_anon_ip_when_rating'] == '0' ) ? 'checked="checked"' : '';
	$check_anon_ip = '<input type="radio" name="check_anon_ip_when_rating" value="1" '. $check_anon_ip_yes . ' /> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="check_anon_ip_when_rating" value="0" '. $check_anon_ip_no . ' /> ' . $lang['No'];
	$main_page_number = '<input type="text" size="5" maxlength="10" name="index_rating_return" value="'. $new['index_rating_return'] . '" />';
	$header_page_number = '<input type="text" size="5" maxlength="10" name="header_rating_return_limit" value="'. $new['header_rating_return_limit'] . '" />';
	$big_page_number = '<input type="text" size="5" maxlength="10" name="large_rating_return_limit" value="'. $new['large_rating_return_limit'] . '" />';
	$min_rates_number = '<input type="text" size="5" maxlength="10" name="min_rates_number" value="'. $new['min_rates_number'] . '" />';

	$configs_sumbits = array(
	$allow_ext_rating,
	$max_rating,
	$allow_rerate,
	$check_anon_ip,
	$min_rates_number,
	$main_page_number,
	$big_page_number,
	$header_page_number);

	//Set Configs for template
	for($i = 0; $i < count($configs_sumbits); $i++)
	{
		$template->assign_block_vars("config_row", array(
		"S_CONFIG" => $configs_sumbits[$i],
		"L_CONFIG" => $configs_desc[$i])
		);
	}

	$template->set_filenames(array(
	"body" => "rate_config_body.tpl")
	);
}
//
//End Config Mode
//

//
//Begin Auth Mode
//
else if ($mode == 'auth')
{
	$forum_auth_levels = array("NONE", "ALL", "REG", "PRIVATE", "MOD", "ADMIN");
	$forum_auth_const = array(-1, AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);
	$forum_auth_desc = array($lang['NONE'], $lang['ALL'], $lang['REG'], $lang['PRIVATE'], $lang['MOD'], $lang['ADMIN']);

	$page_title = $lang['Forum'] . ' ' . $lang['Authorization'];

	$sql = "SELECT forum_id, forum_name, auth_rate
	FROM " . FORUMS_TABLE;
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Error getting forum data.", "", __LINE__, __FILE__, $sql);
	}
	$forum_row = $db->sql_fetchrowset($result);

	//Get a list of forums (can't use function here, need ALL for admin)
	$sql = "SELECT topic_id
	FROM " . RATINGS_TABLE;
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Error getting forum data.", "", __LINE__, __FILE__, $sql);
	}
	$topics_row = $db->sql_fetchrowset($result);

	$hidden_submits = '<input type="hidden" name="mode" value="auth" />';

	//Purge if option selected
	if( isset($HTTP_POST_VARS['forum_purge']) || isset($HTTP_GET_VARS['forum_purge']) )
	{
		//Compare each topic to see if it exists in DB
		for($i = 0; $i < count($topics_row); $i++)
		{
			$sql = "SELECT *
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = " . $topics_row[$i]['topic_id'];
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Error getting topic data.", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			//If a blank title was returned, we know the topic doesn't exist anymore
			if ( !isset($row['topic_title']) )
			{
				$sql = "DELETE
				FROM " . RATINGS_TABLE . "
				WHERE topic_id = " . $topics_row[$i]['topic_id'];
				if ( !$result = $db->sql_query($sql) ) {
					message_die(GENERAL_ERROR, "Error deleting rating data.", "", __LINE__, __FILE__, $sql);
				}
				$admin_message .= "<br />" . $lang['Purged'] . ":&nbsp;&nbsp;&nbsp;" . $lang['Topic'] . "&nbsp;#&nbsp;&nbsp;" . $topics_row[$i]['topic_id'];
			}
		}
		$admin_message .= "<br />" . $lang['Purge'] . ":&nbsp;&nbsp;&nbsp;" . $lang['Complete'];
	}

	//Clear all the data if option selected
	if( isset($HTTP_POST_VARS['ratings_clear']) || isset($HTTP_GET_VARS['ratings_clear']) )
	{
		if( isset($HTTP_POST_VARS['ratings_clear_confirm']) || isset($HTTP_GET_VARS['ratings_clear_confirm']) )
		{
			$clear_confirm = ( isset($HTTP_POST_VARS['ratings_clear_confirm']) ) ? $HTTP_POST_VARS['ratings_clear_confirm'] : $HTTP_GET_VARS['ratings_clear_confirm'];
			if ( strtoupper($clear_confirm) == 'YES' )
			{
				$sql = "DELETE
				FROM " . RATINGS_TABLE;
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error deleting rating data.", "", __LINE__, __FILE__, $sql);
				}
				$admin_message .= "<br />" . $lang['Clear'] . ":&nbsp;&nbsp;&nbsp;" . $lang['Complete'];
			}
		}
	}

	for ($x = 0; $x < count($forum_row); $x++) {
		$current_auth = $forum_row[$x]['auth_rate'];

		if( isset($HTTP_POST_VARS['forum_update_id_'. $forum_row[$x]['forum_id']]) || isset($HTTP_GET_VARS['forum_update_id_'. $forum_row[$x]['forum_id']]) )
		{
			$id_value = ( isset($HTTP_POST_VARS['forum_update_id_'. $forum_row[$x]['forum_id']]) ) ? $HTTP_POST_VARS['forum_update_id_'. $forum_row[$x]['forum_id']] : $HTTP_GET_VARS['forum_update_id_'. $forum_row[$x]['forum_id']];
			$name_value = ( isset($HTTP_POST_VARS['forum_update_name_'. $forum_row[$x]['forum_id']]) ) ? $HTTP_POST_VARS['forum_update_name_'. $forum_row[$x]['forum_id']] : $HTTP_GET_VARS['forum_update_name_'. $forum_row[$x]['forum_id']];
			$update_value = ( isset($HTTP_POST_VARS['forum_update_value_'. $forum_row[$x]['forum_id']]) ) ? $HTTP_POST_VARS['forum_update_value_'. $forum_row[$x]['forum_id']] : $HTTP_GET_VARS['forum_update_value_'. $forum_row[$x]['forum_id']];
			if ($update_value != $current_auth)
			{
				$sql = "UPDATE " . FORUMS_TABLE . "
				SET auth_rate = " . $update_value . "
				WHERE forum_id = " . $id_value;
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error updating rating auth data.", "", __LINE__, __FILE__, $sql);
				}
				$admin_message .= "<br />" . $lang['Update'] . ":&nbsp;&nbsp;&nbsp;" . $lang['Forum'] . "&nbsp;" . $name_value;
				$current_auth = $update_value;
			}
		}

		$hidden_submits .= '<input type="hidden" name="forum_update_id_'. $forum_row[$x]['forum_id'] . '" value="' . $forum_row[$x]['forum_id'] . '" /><input type="hidden" name="forum_update_name_'. $forum_row[$x]['forum_id'] . '" value="' . strip_tags($forum_row[$x]['forum_name']) . '" />';

		$select_auth_mode = '<select name="forum_update_value_' . $forum_row[$x]['forum_id'] . '">';
		for($i = 0; $i < count($forum_auth_levels); $i++)
		{
			$selected = ($current_auth == $forum_auth_const[$i]) ? " selected=\"selected\"" : "";
			$select_auth_mode .= "<option value=\"" . $forum_auth_const[$i] . "\"$selected>" . $forum_auth_levels[$i] . "</option>";
		}
		$select_auth_mode .= "</select>";

		$template->assign_block_vars("forums_row", array(
		"FORUM_NAME" => $forum_row[$x]['forum_name'],
		"S_FORUM_AUTH" => $select_auth_mode)
		);
	}

	$template->set_filenames(array(
	"body" => "rate_auth_body.tpl")
	);

	//Set Description Part
	for($i = 0; $i < count($forum_auth_levels); $i++)
	{
		$template->assign_block_vars("descrow", array(
		"L_AUTH_TYPE" => $forum_auth_levels[$i],
		"L_AUTH_DESC" => $forum_auth_desc[$i])
		);
	}

	//Set Options Part
	$options_types = array($lang['Purge'], $lang['Clear']);
	$options_sumbits = array('<input type="checkbox" name="forum_purge">', '<input type="checkbox" name="ratings_clear">&nbsp;&nbsp;<input type="text" size="3" maxlength="3" name="ratings_clear_confirm" value="NO">');
	$options_desc = array($lang['Purge_Desc'], $lang['Clear_Desc']);

	for($i = 0; $i < count($options_types); $i++)
	$template->assign_block_vars("optionrow", array(
	"L_OPT_TYPE" => $options_types[$i],
	"S_OPT_PART" => $options_sumbits[$i],
	"L_OPT_DESC" => $options_desc[$i])
	);
}
//
//End Auth Mode
//
else
{
	print "No mode specified";
}

//Assign page wide vars
$template->assign_vars(array(
	"ADMIN_MESSAGE" => $admin_message . "<br />" . create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
	"CLASS_1" => $theme['td_class1'],
	"CLASS_2" => $theme['td_class2'],

	"S_MODE_ACTION" => append_sid("$filename"),
	"S_HIDDEN_FIELDS" => $hidden_submits,
	"S_MASS_UPDATE" => $mass_auth_mode,

	"L_SUBMIT" => $lang['Update'],
	"L_RESET" => $lang['Reset'],
	"L_MASS_UPDATE" => $lang['Purge'],
	"L_STATUS" => $lang['Status'],

	"L_AUTH_DESCRIPTION" => $lang['Auth_Description'],
	"L_PERMISSIONS" => $lang['Permissions'],
	"L_FORUM" => $lang['Forum'],
	"L_OPTIONS" => $lang['Options'],
	"L_PAGE_NAME" => $page_title)
);

$template->pparse("body");
include('page_footer_admin.'.$phpEx);

?>
