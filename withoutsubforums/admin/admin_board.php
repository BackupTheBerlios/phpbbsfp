<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_board.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : admin_board.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team and © 2001, 2003 The phpBB	Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if( !empty($setmodules) )
{
	$file =	basename(__FILE__);
	$module['General']['Configuration'] = $file;
	return;
}

define('IN_PHPBB', TRUE);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'),	1);
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

//
// Pull	all	config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result	= $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR,	"Could not query config	information	in admin_board", "", __LINE__, __FILE__, $sql);
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
		$message = $lang['Config_updated'] . "<br /><br	/>"	. sprintf($lang['Click_return_config'],	"<a	href=\"" . append_sid("admin_board.$phpEx") . "\">", "</a>") .	"<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\""	. append_sid("index.$phpEx?pane=right")	. "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$style_select =	style_select($new['default_style'],	'default_style', "../templates");
$lang_select = language_select($new['default_lang'], 'default_lang', "language");
$timezone_select = tz_select($new['board_timezone'], 'board_timezone');
$auth_mode_select =	auth_select($new['auth_mode'], 'auth_mode');

$disable_board_yes = ( $new['board_disable'] ) ? "checked=\"checked\"" : "";
$disable_board_no =	( !$new['board_disable'] ) ? "checked=\"checked\"" : "";

$disable_board_adminview_yes = ( $new['board_disable_adminview'] ) ? "checked=\"checked\"" : "";
$disable_board_adminview_no	= (	!$new['board_disable_adminview'] ) ? "checked=\"checked\"" : "";

$disable_reg_yes = ( $new['disable_reg'] ) ? "checked=\"checked\"" : "";
$disable_reg_no	= (	!$new['disable_reg'] ) ? "checked=\"checked\"" : "";

$cookie_secure_yes = ( $new['cookie_secure'] ) ? "checked=\"checked\"" : "";
$cookie_secure_no =	( !$new['cookie_secure'] ) ? "checked=\"checked\"" : "";

$html_tags = $new['allow_html_tags'];

$override_user_style_yes = ( $new['override_user_style'] ) ? "checked=\"checked\"" : "";
$override_user_style_no	= (	!$new['override_user_style'] ) ? "checked=\"checked\"" : "";

$html_yes =	( $new['allow_html'] ) ? "checked=\"checked\"" : "";
$html_no = ( !$new['allow_html'] ) ? "checked=\"checked\"" : "";

$bbcode_yes	= (	$new['allow_bbcode'] ) ? "checked=\"checked\"" : "";
$bbcode_no = ( !$new['allow_bbcode'] ) ? "checked=\"checked\"" : "";

$activation_none = ( $new['require_activation']	== USER_ACTIVATION_NONE	) ?	"checked=\"checked\"" :	"";
$activation_user = ( $new['require_activation']	== USER_ACTIVATION_SELF	) ?	"checked=\"checked\"" :	"";
$activation_admin =	( $new['require_activation'] ==	USER_ACTIVATION_ADMIN )	? "checked=\"checked\""	: "";

$board_email_form_yes =	( $new['board_email_form'] ) ? "checked=\"checked\"" : "";
$board_email_form_no = ( !$new['board_email_form'] ) ? "checked=\"checked\"" : "";

$gzip_yes =	( $new['gzip_compress']	) ?	"checked=\"checked\"" :	"";
$gzip_no = ( !$new['gzip_compress']	) ?	"checked=\"checked\"" :	"";

$disable_guest_yes = ( $new['disable_guest'] ) ? "checked=\"checked\"" : "";
$disable_guest_no =	( !$new['disable_guest'] ) ? "checked=\"checked\"" : "";
$enable_ldap_group_sync_yes	= (	$new['ldap_group_sync']	) ?	"checked=\"checked\"" :	"";
$enable_ldap_group_sync_no = ( !$new['ldap_group_sync']	) ?	"checked=\"checked\"" :	"";
$tls_yes = ( $new['ldap_start_tls']	) ?	"checked=\"checked\"" :	"";
$tls_no	= (	!$new['ldap_start_tls']	) ?	"checked=\"checked\"" :	"";

$privmsg_on	= (	!$new['privmsg_disable'] ) ? "checked=\"checked\"" : "";
$privmsg_off = ( $new['privmsg_disable'] ) ? "checked=\"checked\"" : "";

$prune_yes = ( $new['prune_enable']	) ?	"checked=\"checked\"" :	"";
$prune_no =	( !$new['prune_enable']	) ?	"checked=\"checked\"" :	"";

$smile_yes = ( $new['allow_smilies'] ) ? "checked=\"checked\"" : "";
$smile_no =	( !$new['allow_smilies'] ) ? "checked=\"checked\"" : "";

$sig_yes = ( $new['allow_sig'] ) ? "checked=\"checked\"" : "";
$sig_no	= (	!$new['allow_sig'] ) ? "checked=\"checked\"" : "";

$namechange_yes	= (	$new['allow_namechange'] ) ? "checked=\"checked\"" : "";
$namechange_no = ( !$new['allow_namechange'] ) ? "checked=\"checked\"" : "";

$avatars_local_yes = ( $new['allow_avatar_local'] )	? "checked=\"checked\""	: "";
$avatars_local_no =	( !$new['allow_avatar_local'] )	? "checked=\"checked\""	: "";
$avatars_remote_yes	= (	$new['allow_avatar_remote']	) ?	"checked=\"checked\"" :	"";
$avatars_remote_no = ( !$new['allow_avatar_remote']	) ?	"checked=\"checked\"" :	"";
$avatars_upload_yes	= (	$new['allow_avatar_upload']	) ?	"checked=\"checked\"" :	"";
$avatars_upload_no = ( !$new['allow_avatar_upload']	) ?	"checked=\"checked\"" :	"";

$smtp_yes =	( $new['smtp_delivery']	) ?	"checked=\"checked\"" :	"";
$smtp_no = ( !$new['smtp_delivery']	) ?	"checked=\"checked\"" :	"";

switch(	$new['custom_title_mode'] )
{
	case CUSTOM_TITLE_MODE_INDEPENDENT:
		$custom_title_mode_independent = "checked=\"checked\"";
		break;
	case CUSTOM_TITLE_MODE_REPLACE_RANK:
		$custom_title_mode_replace_rank	= "checked=\"checked\"";
		break;
	case CUSTOM_TITLE_MODE_REPLACE_BOTH:
		$custom_title_mode_replace_both	= "checked=\"checked\"";
		break;
	default:
		break;
}

$template->set_filenames(array(
	"body" => "board_config_body.tpl")
);
//report forum selection
$sql = "SELECT f.forum_name, f.forum_id
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id ORDER BY c.cat_order ASC, f.forum_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
}
$report_forum_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);
$report_forum_select_list = '<select name="report_forum">';
$report_forum_select_list .= '<option value="0">' . $lang['None'] . '</option>';
for($i = 0; $i < count($report_forum_rows); $i++)
{
	$report_forum_select_list .= '<option value="' . $report_forum_rows[$i]['forum_id'] . '">' . $report_forum_rows[$i]['forum_name'] . '</option>';
}
$report_forum_select_list .= '</select>';
$report_forum_select_list = str_replace("value=\"".$new['report_forum']."\">", "value=\"".$new['report_forum']."\" SELECTED>*" ,$report_forum_select_list);

//
// Escape any quotes in	the	site description for proper	display	in the text
// box on the admin	page
//
$new['site_desc'] =	str_replace('"', '&quot;', $new['site_desc']);
$new['sitename'] = str_replace('"',	'&quot;', strip_tags($new['sitename']));
$new['disable_reg_msg']	= str_replace('"', '&quot;', strip_tags($new['disable_reg_msg']));

$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_board.$phpEx"),
	'S_ZLIB_LOADED' => ( defined('ZLIB_LOADED') ) ? TRUE : FALSE,

	'L_NOT_AVAILABLE' => $lang['Not_available'],
	"L_YES"	=> $lang['Yes'],
	"L_NO" => $lang['No'],
	"L_CONFIGURATION_TITLE"	=> $lang['General_Config'],
	"L_CONFIGURATION_EXPLAIN" => $lang['Config_explain'],
	"L_GENERAL_SETTINGS" =>	$lang['General_settings'],
	"L_SERVER_NAME"	=> $lang['Server_name'],
	"L_SERVER_NAME_EXPLAIN"	=> $lang['Server_name_explain'],
	"L_SERVER_PORT"	=> $lang['Server_port'],
	"L_SERVER_PORT_EXPLAIN"	=> $lang['Server_port_explain'],
	"L_SCRIPT_PATH"	=> $lang['Script_path'],
	"L_SCRIPT_PATH_EXPLAIN"	=> $lang['Script_path_explain'],
	"L_SITE_NAME" => $lang['Site_name'],
	"L_SITE_DESCRIPTION" =>	$lang['Site_desc'],
	"L_DISABLE_BOARD" => $lang['Board_disable'],
	"L_DISABLE_BOARD_EXPLAIN" => $lang['Board_disable_explain'],
	"L_DISABLE_BOARD_MSG" => $lang['Board_disable_msg'],
	"L_DISABLE_BOARD_MSG_EXPLAIN" => $lang['Board_disable_msg_explain'],
	"L_DISABLE_BOARD_ADMINVIEW"	=> $lang['Board_disable_adminview'],
	"L_DISABLE_BOARD_ADMINVIEW_EXPLAIN"	=> $lang['Board_disable_adminview_explain'],
	"L_DISABLE_REG"	=> $lang['disable_reg'],
	"L_DISABLE_REG_EXPLAIN"	=> $lang['disable_reg_explain'],
	"L_DISABLE_REG_MSG"	=> $lang['disable_reg_msg'],
	"L_DISABLE_REG_MSG_EXPLAIN"	=> $lang['disable_reg_msg_explain'],
	"S_DISABLE_REG_YES"	=> $disable_reg_yes,
	"S_DISABLE_REG_NO" => $disable_reg_no,
	"DISABLE_REG_MSG" => $new['disable_reg_msg'],

	"L_ACCT_ACTIVATION"	=> $lang['Acct_activation'],
	"L_NONE" =>	$lang['Acc_None'],
	"L_USER" =>	$lang['Acc_User'],
	"L_ADMIN" => $lang['Acc_Admin'],

	"L_AUTH_SETTINGS" => $lang['Auth_settings'],
	"L_DISABLE_GUEST" => $lang['Disable_Guest'],
	"L_AUTH_MODE" => $lang['Auth_mode'],
	"L_LDAP_HOST" => $lang['Ldap_host'],
	"L_LDAP_PORT" => $lang['Ldap_port'],
	"L_LDAP_HOST2" => $lang['Ldap_host2'],
	"L_LDAP_PORT2" => $lang['Ldap_port2'],
	"L_LDAP_DN"	=> $lang['Ldap_dn'],
	"L_LDAP_DN_EXPLAIN"	=> $lang['Ldap_dn_explain'],
	"L_LDAP_UID" =>	$lang['Ldap_uid'],
	"L_LDAP_UID_EXPLAIN" =>	$lang['Ldap_uid_explain'],
	"L_LDAP_GROUP_SYNC"	=> $lang['ldap_group_sync'],
	"L_LDAP_GID" =>	$lang['ldap_gid'],
	"L_LDAP_GID_EXPLAIN" =>	$lang['ldap_gid_explain'],
	"L_LDAP_EMAIL" => $lang['ldap_email'],
	"L_LDAP_WEB" =>	$lang['ldap_web'],
	"L_LDAP_LOCATION" => $lang['ldap_location'],
	"L_LDAP_OCCUPATION"	=> $lang['ldap_occupation'],
	"L_LDAP_SIGNATURE" => $lang['ldap_signature'],
	"L_LDAP_PROXY_DN" => $lang['Ldap_proxy_dn'],
	"L_LDAP_PROXY_DN_EXPLAIN" => $lang['Ldap_proxy_dn_explain'],
	"L_LDAP_PROXY_DN_PASS" => $lang['Ldap_proxy_dn_pass'],
	"L_LDAP_PROXY_DN_PASS_EXPLAIN" => $lang['Ldap_proxy_dn_pass_explain'],
	"L_LDAP_START_TLS" => $lang['Ldap_start_tls'],

	"L_COOKIE_SETTINGS"	=> $lang['Cookie_settings'],
	"L_COOKIE_SETTINGS_EXPLAIN"	=> $lang['Cookie_settings_explain'],
	"L_COOKIE_DOMAIN" => $lang['Cookie_domain'],
	"L_COOKIE_NAME"	=> $lang['Cookie_name'],
	"L_COOKIE_PATH"	=> $lang['Cookie_path'],
	"L_COOKIE_SECURE" => $lang['Cookie_secure'],
	"L_COOKIE_SECURE_EXPLAIN" => $lang['Cookie_secure_explain'],
	"L_SESSION_LENGTH" => $lang['Session_length'],
	"L_PRIVATE_MESSAGING" => $lang['Private_Messaging'],
	"L_FORUM_SETTINGS" => $lang['Forum_settings'],
	"L_DISABLE_PRIVATE_MESSAGING" => $lang['Disable_privmsg'],
	"L_ENABLED"	=> $lang['Enabled'],
	"L_DISABLED" =>	$lang['Disabled'],
	"L_ABILITIES_SETTINGS" => $lang['Abilities_settings'],
	"L_MAX_POLL_OPTIONS" =>	$lang['Max_poll_options'],
	"L_FLOOD_INTERVAL" => $lang['Flood_Interval'],
	"L_FLOOD_INTERVAL_EXPLAIN" => $lang['Flood_Interval_explain'],
	"L_BOARD_EMAIL_FORM" =>	$lang['Board_email_form'],
	"L_BOARD_EMAIL_FORM_EXPLAIN" =>	$lang['Board_email_form_explain'],
	"L_TOPICS_PER_PAGE"	=> $lang['Topics_per_page'],
	"L_POSTS_PER_PAGE" => $lang['Posts_per_page'],
	"L_HOT_THRESHOLD" => $lang['Hot_threshold'],
	"L_DEFAULT_STYLE" => $lang['Default_style'],
	"L_OVERRIDE_STYLE" => $lang['Override_style'],
	"L_OVERRIDE_STYLE_EXPLAIN" => $lang['Override_style_explain'],
	"L_DEFAULT_LANGUAGE" =>	$lang['Default_language'],
	"L_DATE_FORMAT"	=> $lang['Date_format'],
	"L_SYSTEM_TIMEZONE"	=> $lang['System_timezone'],
	"L_ENABLE_GZIP"	=> $lang['Enable_gzip'],
	"L_ENABLE_PRUNE" =>	$lang['Enable_prune'],
	'L_BLUECARD_LIMIT' => $lang['Bluecard_limit'], 
	'L_BLUECARD_LIMIT_EXPLAIN' => $lang['Bluecard_limit_explain'], 
	'L_BLUECARD_LIMIT_2' => $lang['Bluecard_limit_2'], 
	'L_BLUECARD_LIMIT_2_EXPLAIN' => $lang['Bluecard_limit_2_explain'], 
	'L_MAX_USER_BANCARD' => $lang['Max_user_bancard'], 
	'L_MAX_USER_BANCARD_EXPLAIN' => $lang['Max_user_bancard_explain'], 
	'L_REPORT_FORUM' => $lang['Report_forum'],
	'L_REPORT_FORUM_EXPLAIN' => $lang['Report_forum_explain'],
	"L_CUSTOM_TITLE_SETTINGS" => $lang['Custom_title_settings'],
	"L_CUSTOM_TITLE_DAYS" => $lang['Custom_title_days'],
	"L_CUSTOM_TITLE_POSTS" => $lang['Custom_title_posts'],
	"L_CUSTOM_TITLE_MODE" => $lang['Custom_title_mode'],
	"L_CUSTOM_TITLE_MODE_EXPLAIN" => $lang['Custom_title_mode_explain'],
	"L_CUSTOM_TITLE_MODE_INDEPENDENT" => $lang['Custom_title_mode_independent'],
	"L_CUSTOM_TITLE_MODE_REPLACE_RANK" => $lang['Custom_title_mode_replace_rank'],
	"L_CUSTOM_TITLE_MODE_REPLACE_BOTH" => $lang['Custom_title_mode_replace_both'],
	"L_CUSTOM_TITLE_MAXLENGTH" => $lang['Custom_title_maxlength'],
	"L_CUSTOM_TITLE_MAXLENGTH_EXPLAIN" => $lang['Custom_title_maxlength_explain'],

	"L_META_SETTINGS" => $lang['Meta_settings'],
	"L_META_SETTINGS_EXPLAIN" => $lang['Meta_settings_explain'],
	"L_META_KEYWORDS" => $lang['Meta_keywords'],
	"L_META_KEYWORDS_EXPLAIN" => $lang['Meta_keywords_explain'],
	"L_META_DESCRIPTION" =>	$lang['Meta_description'],
	"L_META_DESCRIPTION_EXPLAIN" =>	$lang['Meta_description_explain'],
	"L_META_REVISIT" =>	$lang['Meta_revisit'],
	"L_META_REVISIT_EXPLAIN" =>	$lang['Meta_revisit_explain'],
	"L_META_AUTHOR"	=> $lang['Meta_author'],
	"L_META_AUTHOR_EXPLAIN"	=> $lang['Meta_author_explain'],
	"L_META_OWNER" => $lang['Meta_owner'],
	"L_META_OWNER_EXPLAIN" => $lang['Meta_owner_explain'],
	"L_META_DISTRIBUTION" => $lang['Meta_distribution'],
	"L_META_DISTRIBUTION_EXPLAIN" => $lang['Meta_distribution_explain'],
	"L_META_ROBOTS"	=> $lang['Meta_robots'],
	"L_META_ROBOTS_EXPLAIN"	=> $lang['Meta_robots_explain'],
	"L_META_ABSTRACT" => $lang['Meta_abstract'],
	"L_META_ABSTRACT_EXPLAIN" => $lang['Meta_abstract_explain'],

	"L_ALLOW_HTML" => $lang['Allow_HTML'],
	"L_ALLOW_BBCODE" =>	$lang['Allow_BBCode'],
	"L_ALLOWED_TAGS" =>	$lang['Allowed_tags'],
	"L_ALLOWED_TAGS_EXPLAIN" =>	$lang['Allowed_tags_explain'],
	"L_ALLOW_SMILIES" => $lang['Allow_smilies'],
	"L_SMILIES_PATH" =>	$lang['Smilies_path'],
	"L_SMILIES_PATH_EXPLAIN" =>	$lang['Smilies_path_explain'],
	"L_ALLOW_SIG" => $lang['Allow_sig'],
	"L_MAX_SIG_LENGTH" => $lang['Max_sig_length'],
	"L_MAX_SIG_LENGTH_EXPLAIN" => $lang['Max_sig_length_explain'],
	"L_ALLOW_NAME_CHANGE" => $lang['Allow_name_change'],
	"L_AVATAR_SETTINGS"	=> $lang['Avatar_settings'],
	"L_ALLOW_LOCAL"	=> $lang['Allow_local'],
	"L_ALLOW_REMOTE" =>	$lang['Allow_remote'],
	"L_ALLOW_REMOTE_EXPLAIN" =>	$lang['Allow_remote_explain'],
	"L_ALLOW_UPLOAD" =>	$lang['Allow_upload'],
	"L_MAX_FILESIZE" =>	$lang['Max_filesize'],
	"L_MAX_FILESIZE_EXPLAIN" =>	$lang['Max_filesize_explain'],
	"L_MAX_AVATAR_SIZE"	=> $lang['Max_avatar_size'],
	"L_MAX_AVATAR_SIZE_EXPLAIN"	=> $lang['Max_avatar_size_explain'],
	"L_AVATAR_STORAGE_PATH"	=> $lang['Avatar_storage_path'],
	"L_AVATAR_STORAGE_PATH_EXPLAIN"	=> $lang['Avatar_storage_path_explain'],
	"L_AVATAR_GALLERY_PATH"	=> $lang['Avatar_gallery_path'],
	"L_AVATAR_GALLERY_PATH_EXPLAIN"	=> $lang['Avatar_gallery_path_explain'],
	"L_COPPA_SETTINGS" => $lang['COPPA_settings'],
	"L_COPPA_FAX" => $lang['COPPA_fax'],
	"L_COPPA_MAIL" => $lang['COPPA_mail'],
	"L_COPPA_MAIL_EXPLAIN" => $lang['COPPA_mail_explain'],
	"L_EMAIL_SETTINGS" => $lang['Email_settings'],
	"L_ADMIN_EMAIL"	=> $lang['Admin_email'],
	"L_EMAIL_SIG" => $lang['Email_sig'],
	"L_EMAIL_SIG_EXPLAIN" => $lang['Email_sig_explain'],
	"L_USE_SMTP" =>	$lang['Use_SMTP'],
	"L_USE_SMTP_EXPLAIN" =>	$lang['Use_SMTP_explain'],
	"L_SMTP_SERVER"	=> $lang['SMTP_server'],
	"L_SMTP_USERNAME" => $lang['SMTP_username'],
	"L_SMTP_USERNAME_EXPLAIN" => $lang['SMTP_username_explain'],
	"L_SMTP_PASSWORD" => $lang['SMTP_password'],
	"L_SMTP_PASSWORD_EXPLAIN" => $lang['SMTP_password_explain'],
	"L_SUBMIT" => $lang['Submit'],
	"L_RESET" => $lang['Reset'],

	"SERVER_NAME" => $new['server_name'],
	"SCRIPT_PATH" => $new['script_path'],
	"SERVER_PORT" => $new['server_port'],
	"SITENAME" => $new['sitename'],
	"SITE_DESCRIPTION" => $new['site_desc'],
	"S_DISABLE_BOARD_YES" => $disable_board_yes,
	"S_DISABLE_BOARD_NO" =>	$disable_board_no,
	"DISABLE_BOARD_MSG"	=> $new['board_disable_msg'],
	"S_DISABLE_BOARD_ADMINVIEW_YES"	=> $disable_board_adminview_yes,
	"S_DISABLE_BOARD_ADMINVIEW_NO" => $disable_board_adminview_no,
	"ACTIVATION_NONE" => USER_ACTIVATION_NONE,
	"ACTIVATION_NONE_CHECKED" => $activation_none,
	"ACTIVATION_USER" => USER_ACTIVATION_SELF,
	"ACTIVATION_USER_CHECKED" => $activation_user,
	"ACTIVATION_ADMIN" => USER_ACTIVATION_ADMIN,
	"ACTIVATION_ADMIN_CHECKED" => $activation_admin,
	"CONFIRM_ENABLE" =>	$confirm_yes,
	"CONFIRM_DISABLE" => $confirm_no,
	"ACTIVATION_NONE_CHECKED" => $activation_none,
	"BOARD_EMAIL_FORM_ENABLE" => $board_email_form_yes,
	"BOARD_EMAIL_FORM_DISABLE" => $board_email_form_no,
	"MAX_POLL_OPTIONS" => $new['max_poll_options'],
	"FLOOD_INTERVAL" =>	$new['flood_interval'],
	"TOPICS_PER_PAGE" => $new['topics_per_page'],
	"POSTS_PER_PAGE" =>	$new['posts_per_page'],
	"HOT_TOPIC"	=> $new['hot_threshold'],
	"STYLE_SELECT" => $style_select,
	"OVERRIDE_STYLE_YES" =>	$override_user_style_yes,
	"OVERRIDE_STYLE_NO"	=> $override_user_style_no,
	"LANG_SELECT" => $lang_select,
	"L_DATE_FORMAT_EXPLAIN"	=> $lang['Date_format_explain'],
	"DEFAULT_DATEFORMAT" =>	$new['default_dateformat'],
	"TIMEZONE_SELECT" => $timezone_select,
	"S_PRIVMSG_ENABLED"	=> $privmsg_on,
	"S_PRIVMSG_DISABLED" =>	$privmsg_off,
	"COOKIE_DOMAIN"	=> $new['cookie_domain'],
	"COOKIE_NAME" => $new['cookie_name'],
	"COOKIE_PATH" => $new['cookie_path'],
	"SESSION_LENGTH" =>	$new['session_length'],
	"S_COOKIE_SECURE_ENABLED" => $cookie_secure_yes,
	"S_COOKIE_SECURE_DISABLED" => $cookie_secure_no,
	"GZIP_YES" => $gzip_yes,
	"GZIP_NO" => $gzip_no,
	"PRUNE_YES"	=> $prune_yes,
	"PRUNE_NO" => $prune_no,
	'BLUECARD_LIMIT' => $new['bluecard_limit'], 
	'BLUECARD_LIMIT_2' => $new['bluecard_limit_2'], 
	'MAX_USER_BANCARD' => $new['max_user_bancard'], 
	'S_REPORT_FORUM' => $report_forum_select_list,
	"CUSTOM_TITLE_DAYS"	=> $new['custom_title_days'],
	"CUSTOM_TITLE_POSTS" =>	$new['custom_title_posts'],
	"CUSTOM_TITLE_MODE_INDEPENDENT"	=> $custom_title_mode_independent,
	"CUSTOM_TITLE_MODE_REPLACE_RANK" =>	$custom_title_mode_replace_rank,
	"CUSTOM_TITLE_MODE_REPLACE_BOTH" =>	$custom_title_mode_replace_both,
	"CUSTOM_TITLE_MAXLENGTH" =>	$new['custom_title_maxlength'],

	"META_KEYWORDS"	=> $new['meta_keywords'],
	"META_DESCRIPTION" => $new['meta_description'],
	"META_REVISIT" => $new['meta_revisit'],
	"META_AUTHOR" => $new['meta_author'],
	"META_OWNER" =>	$new['meta_owner'],
	"META_DISTRIBUTION"	=> $new['meta_distribution'],
	"META_ROBOTS" => $new['meta_robots'],
	"META_ABSTRACT"	=> $new['meta_abstract'],

	"AUTH_MODE_SELECT" => $auth_mode_select,
	"DISABLE_GUEST_YES"	=> $disable_guest_yes,
	"DISABLE_GUEST_NO" => $disable_guest_no,
	"LDAP_HOST"	=> $new['ldap_host'],
	"LDAP_PORT"	=> $new['ldap_port'],
	"LDAP_HOST2" =>	$new['ldap_host2'],
	"LDAP_PORT2" =>	$new['ldap_port2'],
	"LDAP_DN" => $new['ldap_dn'],
	"LDAP_UID" => $new['ldap_uid'],
	"ENABLE_LDAP_GROUP_SYNC_YES" =>	$enable_ldap_group_sync_yes,
	"ENABLE_LDAP_GROUP_SYNC_NO"	=> $enable_ldap_group_sync_no,
	"LDAP_GID" => $new['ldap_gid'],
	"LDAP_EMAIL" =>	$new['ldap_email'],
	"LDAP_WEB" => $new['ldap_web'],
	"LDAP_LOCATION"	=> $new['ldap_location'],
	"LDAP_OCCUPATION" => $new['ldap_occupation'],
	"LDAP_SIGNATURE" =>	$new['ldap_signature'],
	"LDAP_PROXY_DN"	=> $new['ldap_proxy_dn'],
	"LDAP_PROXY_DN_PASS" =>	$new['ldap_proxy_dn_pass'],
	"TLS_YES" => $tls_yes,
	"TLS_NO" =>	$tls_no,

	"HTML_TAGS"	=> $html_tags,
	"HTML_YES" => $html_yes,
	"HTML_NO" => $html_no,
	"BBCODE_YES" =>	$bbcode_yes,
	"BBCODE_NO"	=> $bbcode_no,
	"SMILE_YES"	=> $smile_yes,
	"SMILE_NO" => $smile_no,
	"SIG_YES" => $sig_yes,
	"SIG_NO" =>	$sig_no,
	"SIG_SIZE" => $new['max_sig_chars'],
	"NAMECHANGE_YES" =>	$namechange_yes,
	"NAMECHANGE_NO"	=> $namechange_no,
	"AVATARS_LOCAL_YES"	=> $avatars_local_yes,
	"AVATARS_LOCAL_NO" => $avatars_local_no,
	"AVATARS_REMOTE_YES" =>	$avatars_remote_yes,
	"AVATARS_REMOTE_NO"	=> $avatars_remote_no,
	"AVATARS_UPLOAD_YES" =>	$avatars_upload_yes,
	"AVATARS_UPLOAD_NO"	=> $avatars_upload_no,
	"AVATAR_FILESIZE" => $new['avatar_filesize'],
	"AVATAR_MAX_HEIGHT"	=> $new['avatar_max_height'],
	"AVATAR_MAX_WIDTH" => $new['avatar_max_width'],
	"AVATAR_PATH" => $new['avatar_path'],
	"AVATAR_GALLERY_PATH" => $new['avatar_gallery_path'],
	"SMILIES_PATH" => $new['smilies_path'],
	"EMAIL_FROM" =>	$new['board_email'],
	"EMAIL_SIG"	=> $new['board_email_sig'],
	"SMTP_YES" => $smtp_yes,
	"SMTP_NO" => $smtp_no,
	"SMTP_HOST"	=> $new['smtp_host'],
	"SMTP_USERNAME"	=> $new['smtp_username'],
	"SMTP_PASSWORD"	=> $new['smtp_password'],
	"COPPA_MAIL" =>	$new['coppa_mail'],
	"COPPA_FAX"	=> $new['coppa_fax'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
