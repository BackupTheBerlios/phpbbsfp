<?php
//-- mod : categories hierarchy	--------------------------------------------------------------------
//-- mod : keep	unread -----------------------------------------------------------------------------
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: page_header.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME	 : page_header.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team and © 2001, 2003 The phpBB	Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

define('HEADER_INC', TRUE);

//--------------------------------------------------------------------------------
// Prillian	- Begin	Code Addition
//

include_once(PRILL_PATH	. 'prill_common.' .	$phpEx);

//
// Prillian	- End Code Addition
//--------------------------------------------------------------------------------

if(!defined('BLOCKS_INIT'))
{
	include($phpbb_root_path . 'includes/functions_blocks.'	. $phpEx);
	blocks_config_init($portal_config);
	define('BLOCKS_INIT', TRUE);
}

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
include_once($phpbb_root_path .	'profilcp/functions_profile.' .	$phpEx);
//-- fin mod : profile cp --------------------------------------------------------------------------

include($phpbb_root_path . 'includes/functions_rate.'.$phpEx);

//
// gzip_compression
//
$do_gzip_compress =	FALSE;
if ( $board_config['gzip_compress'] && !headers_sent() && defined('ZLIB_LOADED') )
{
	$phpver	= phpversion();

	$accept_encoding = ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) ? strtolower($_SERVER['HTTP_ACCEPT_ENCODING']) : '';

	if ( strpos($accept_encoding, 'gzip') !== FALSE )
	{
		if ( $phpver >=	'4.0.4pl1' && strpos($accept_encoding, 'deflate') !== FALSE )
		{
			//
			// Here	we updated the gzip function.
			// With	this method we can get the server up
			// to 10% faster
			//
			ob_start('ob_gzhandler');
		}
		elseif ( $phpver > '4.0' )
		{
			$do_gzip_compress = TRUE;
			ob_start();
			ob_implicit_flush(0);
			header('Content-Encoding: gzip');
		}
	}
}

//
// Parse and show the overall header.
//
$template->set_filenames(array(
	'overall_header' =>	( empty($gen_simple_header)	) ?	'overall_header.tpl' : 'simple_header.tpl')
);

//
// Generate	logged in/logged out status
//
if ( $userdata['session_logged_in']	)
{
	$u_login_logout	= 'login.'.$phpEx.'?logout=true&amp;sid=' .	$userdata['session_id'];
	$l_login_logout	= $lang['Logout'] .	' [	' .	$userdata['username'] .	' ]';
	if ( $board_config['auth_mode'] == 'ldap' && ntlm_check() && !defined("IN_LOGIN"))
	{
		if (strcmp(strtolower(ntlm_get_user()),	strtolower(	$userdata['username']) ))
		{
			//Logout if	we are logged on as	a diffrent user
			header('Location: '	. append_sid("login.$phpEx?logout=true&amp;redirect=index.$phpEx", true));
		}
	}
}
else
{
	$u_login_logout	= 'login.'.$phpEx;
	$l_login_logout	= $lang['Login'];

	if ($board_config['disable_guest'] == 1	&& !defined("IN_LOGIN")	&& !defined("IN_SEARCHUSER"))
	{
		header('Location: '	. append_sid("login.$phpEx?redirect=index.$phpEx", true));
	}
}

//-- mod : keep	unread -----------------------------------------------------------------------------
//-- delete
// $s_last_visit = ( $userdata['session_logged_in']	) ?	create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']) :	'';
//-- add
$s_last_visit =	create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']);
//-- fin mod : keep	unread -------------------------------------------------------------------------

//
// Get basic (usernames	+ totals) online
// situation
//
$logged_visible_online = 0;
$logged_hidden_online =	0;
$guests_online = 0;
//$bots_online = 0;
//$bots_list = "";
$online_userlist = '';
$l_online_users	= '';

if (defined('SHOW_ONLINE'))
{

	$user_forum_sql	= (	!empty($forum_id) )	? "AND s.session_page =	" .	intval($forum_id) :	'';
//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//	$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip
//-- add
	$sql = "SELECT u.*, s.session_logged_in, s.session_time, s.session_page, s.session_ip
		FROM ".USERS_TABLE." u,	".SESSIONS_TABLE." s
		WHERE u.user_id	= s.session_user_id
			AND	s.session_time >= ".( time() - 300 ) . "
			$user_forum_sql
		ORDER BY u.username	ASC, s.session_ip ASC";
//-- fin mod : profile cp --------------------------------------------------------------------------

	if(	!($result =	$db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
	}

	$userlist_ary =	array();
	$userlist_visible =	array();

	$prev_user_id =	0;
	$prev_user_ip =	$prev_session_ip = '';

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//	while( $row	= $db->sql_fetchrow($result) )
//	{
//		// User	is logged in and therefor not a	guest
//		if ( $row['session_logged_in'] )
//		{
//			// Skip	multiple sessions for one user
//			if ( $row['user_id'] !=	$prev_user_id )
//			{
//				$style_color = '';
//				if ( $row['user_level']	== ADMIN )
//				{
//					$row['username'] = '<b>' . $row['username']	. '</b>';
//					$style_color = 'style="color:#'	. $theme['fontcolor3'] . '"';
//				}
//				else if	( $row['user_level'] ==	MOD	)
//				{
//					$row['username'] = '<b>' . $row['username']	. '</b>';
//					$style_color = 'style="color:#'	. $theme['fontcolor2'] . '"';
//				}
//
//				if ( $row['user_allow_viewonline'] )
//				{
//					$user_online_link =	'<a	href="'	. append_sid("profile.$phpEx?mode=viewprofile&amp;"	. POST_USERS_URL . "=" . $row['user_id'])	. '"' .	$style_color .'>' .	$row['username'] . '</a>';
//					$logged_visible_online++;
//				}
//				else
//				{
//					$user_online_link =	'<a	href="'	. append_sid("profile.$phpEx?mode=viewprofile&amp;"	. POST_USERS_URL . "=" . $row['user_id'])	. '"' .	$style_color .'><i>' . $row['username']	. '</i></a>';
//					$logged_hidden_online++;
//				}
//
//				if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
//				{
//					$online_userlist .=	( $online_userlist != '' ) ? ',	' .	$user_online_link :	$user_online_link;
//				}
//			}
//
//			$prev_user_id =	$row['user_id'];
//		}
//		else
//		{
//			// Skip	multiple sessions for one user
//			if ( $row['session_ip']	!= $prev_session_ip	)
//			{
//				$guests_online++;
//			}
//		}
//
//		$prev_session_ip = $row['session_ip'];
//	}
//	$db->sql_freeresult($result);
//-- add

	$connected = array();
	$user_ids =	array();
	while ($row	= $db->sql_fetchrow($result) )
	{
		// User	is logged in and therefor not a	guest
		if ( $row['session_logged_in'] )
		{
			if ( !in_array($row['user_id'],	$user_ids) )
			{
				$row['style'] =	' class="' . get_user_level_class($row['user_level'], 'gen', $row) . '"';
				$connected[] = $row;
				$user_ids[]	= $row['user_id'];
			}
		}
		else
		{
			// Skip	multiple sessions for one user
			if ( $row['session_ip']	!= $prev_session_ip	)
			{
				$row['style'] =	'';
				$connected[] = $row;
//--------------------------------------------------------------------------------
// Prillian	- Begin	Code Addition
//
				$online_array[]	= $row['user_id'];
//
// Prillian	- End Code Addition
//--------------------------------------------------------------------------------
			}
		}
		$prev_session_ip = $row['session_ip'];
	}
	$db->sql_freeresult($result);

	// read	buddy list
	$buddys	= array();
	if (count($user_ids) > 0)
	{
		$s_user_ids	= implode(', ',	$user_ids);

		// get base	info
		$sql = "SELECT * FROM "	. BUDDYS_TABLE . " WHERE user_id=" . $userdata['user_id'] .	" and buddy_id in ($s_user_ids)";
		if ( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR,	"Could not obtain buddys information.",	'',	__LINE__, __FILE__,	$sql);
		while (	$row = $db->sql_fetchrow($result) )
		{
			$buddys[ $row['buddy_id'] ]['buddy_ignore']	= $row['buddy_ignore'];
			$buddys[ $row['buddy_id'] ]['buddy_my_friend'] = !$row['buddy_ignore'];
			$buddys[ $row['buddy_id'] ]['buddy_friend']	= false;
			$buddys[ $row['buddy_id'] ]['buddy_visible'] = false;
		}

		// check if	in the topic author's friend list and "always visible" status he granted
		$sql = "SELECT * FROM "	. BUDDYS_TABLE . " WHERE buddy_id="	. $userdata['user_id'] . " and user_id in ($s_user_ids)";
		if ( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR,	"Could not obtain buddys information.",	'',	__LINE__, __FILE__,	$sql);
		while (	$row = $db->sql_fetchrow($result) )
		{
			if ( !isset($buddys[ $row['user_id'] ])	) $buddys[ $row['user_id'] ]['buddy_ignore'] = false;
			if ( !isset($buddys[ $row['user_id'] ])	) $buddys[ $row['user_id'] ]['buddy_my_friend']	= false;
			$buddys[ $row['user_id'] ]['buddy_friend'] = !$row['buddy_ignore'];
			$buddys[ $row['user_id'] ]['buddy_visible']	= $row['buddy_visible'];
		}
		$db->sql_freeresult($result);
	}

	// get visible/not visible status
	$user_id = $userdata['user_id'];
	$user_level	= $userdata['user_level'];
	$is_admin =	is_admin($userdata);

	for	($i=0; $i <	count($connected); $i++)
	{
		$view_user_id =	$connected[$i]['user_id'];
		$view_is_admin = is_admin($connected[$i]);

		$view_online_set = $connected[$i]['user_allow_viewonline'];

		$view_ignore	= ($is_admin ||	$view_is_admin || ($view_user_id ==	$user_id)) ? false : $buddys[$view_user_id]['buddy_ignore'];
		$view_friend	= $buddys[$view_user_id]['buddy_friend'];
		$view_visible	= ($is_admin ||	($view_user_id == $user_id)) ? YES : $buddys[$view_user_id]['buddy_visible'];

		// online/offline/hidden icon
		if ($view_user_id == ANONYMOUS)
		{
			$status	= 'guest';
		}
		else if	($view_ignore)
		{
			$status	= 'offline';
		}
		else
		{
			switch ($view_online_set)
			{
				case NO:
					$status	= ($view_visible) ?	'hidden' : 'offline';
					break;
				case YES:
					$status	= 'online';
					break;
				case FRIEND_ONLY:
					$status	= ($view_friend	|| $view_visible) ?	'hidden' : 'offline';
					break;
				default:
					$status	= '???';
			}
		}

		// set the status
		switch ($status)
		{
			case 'guest':
				$guests_online++;
				break;
			case 'offline':
				$logged_hidden_online++;
				break;
			case 'online':
				$logged_visible_online++;
				break;
			case 'hidden':
				$connected[$i]['username'] = '<i>' . $connected[$i]['username']	. '</i>';
				$logged_hidden_online++;
				break;
			default:
		}

		$connected[$i]['status'] = $status;

		// add the user	to the online list
		if ( ($status == 'online') || ($status == 'hidden')	)
		{
			$online_userlist .=	( $online_userlist != '' ) ? ',	' :	'';
			$online_userlist .=	'<a	href="'	. append_sid("profile.$phpEx?mode=viewprofile&amp;"	. POST_USERS_URL . "="	. $connected[$i]['user_id']	) .	'"'	. $connected[$i]['style'] .	'>'	. $connected[$i]['username'] . '</a>';
		}
	}
//-- fin mod : profile cp --------------------------------------------------------------------------

	if ( empty($online_userlist) )
	{
		$online_userlist = $lang['None'];
	}
	$online_userlist = ( ( isset($forum_id)	) ?	$lang['Browsing_forum']	: $lang['Registered_users']	) .	' '	. $online_userlist;

	$total_online_users	= $logged_visible_online + $logged_hidden_online + $guests_online;

	if ( $total_online_users > $board_config['record_online_users'])
	{
		if ( $sql =	set_config('record_online_users', $total_online_users, TRUE) )
		{
			message_die(GENERAL_ERROR, 'Could not update online	user record	(nr	of users)',	'',	__LINE__, __FILE__,	$sql);
		}

		if ( $sql =	set_config('record_online_date', time(), TRUE) )
		{
			message_die(GENERAL_ERROR, 'Could not update online	user record	(date)', '', __LINE__, __FILE__, $sql);
		}
	}

	$l_t_user_s = $lang['Online_usersbots_total'];

	if ( $total_online_users ==	0 )
	{
		$l_t_user_s	= $lang['Online_users_zero_total'];
	}
	else if	( $total_online_users == 1 )
	{
		$l_t_user_s	= $lang['Online_user_total'];
	}
	else
	{
		$l_t_user_s	= $lang['Online_users_total'];
	}
	
	if ( $logged_hidden_online == 0	)
	{
		$l_h_user_s	= $lang['Hidden_users_zero_total'];
	}
	else if	( $logged_hidden_online	== 1 )
	{
		$l_h_user_s	= $lang['Hidden_user_total'];
	}
	else
	{
		$l_h_user_s	= $lang['Hidden_users_total'];
	}

	if ( $guests_online	== 0 )
	{
		$l_g_user_s	= $lang['Guest_users_zero_total'];
	}
	else if	( $guests_online ==	1 )
	{
		$l_g_user_s	= $lang['Guest_user_total'];
	}
	else
	{
		$l_g_user_s	= $lang['Guest_users_total'];
	}

	$l_online_users	= sprintf($l_t_user_s, $total_online_users);

	$l_online_users	.= sprintf($l_r_user_s,	$logged_visible_online);
	$l_online_users	.= sprintf($l_h_user_s,	$logged_hidden_online);
	$l_online_users	.= sprintf($l_g_user_s,	$guests_online);
}

//
// Obtain number of	new	private	messages
// if user is logged in
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
{
	if ( $userdata['user_new_privmsg'] )
	{
		$l_message_new = ( $userdata['user_new_privmsg'] ==	1 )	? $lang['New_pm'] :	$lang['New_pms'];
		$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

		if ( $userdata['user_last_privmsg']	> $userdata['user_lastvisit'] )
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET	user_last_privmsg =	" .	$userdata['user_lastvisit']	. "
				WHERE user_id =	" .	$userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__,	__FILE__, $sql);
			}

			$s_privmsg_new = 1;
			$icon_pm = $images['pm_new_msg'];
		}
		else
		{
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_new_msg'];
		}
	}
	else
	{
		$l_privmsgs_text = $lang['No_new_pm'];

		$s_privmsg_new = 0;
		$icon_pm = $images['pm_no_new_msg'];
	}

	if ( $userdata['user_unread_privmsg'] )
	{
		$l_message_unread =	( $userdata['user_unread_privmsg'] == 1	) ?	$lang['Unread_pm'] : $lang['Unread_pms'];
		$l_privmsgs_text_unread	= sprintf($l_message_unread, $userdata['user_unread_privmsg']);
	}
	else
	{
		$l_privmsgs_text_unread	= $lang['No_unread_pm'];
	}
}
else
{
	$icon_pm = $images['pm_no_new_msg'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread	= '';
	$s_privmsg_new = 0;
}

//
// Generate	HTML required for Mozilla Navigation bar
//
if (!isset($nav_links))
{
	$nav_links = array();
}

$nav_links_html	= '';
$nav_link_proto	= '<link rel="%s" href="%s"	title="%s" />' . "\n";
foreach ( $nav_links as $nav_item => $nav_array )
{
	if ( !empty($nav_array['url']) )
	{
		$nav_links_html	.= sprintf($nav_link_proto,	$nav_item, append_sid($nav_array['url']), $nav_array['title']);
	}
	else
	{
		// We have a nested	array, used	for	items like <link rel='chapter'>	that can occur more	than once.
		foreach ( $nav_array as $nested_array )
		{
			$nav_links_html	.= sprintf($nav_link_proto,	$nav_item, $nested_array['url'], $nested_array['title']);
		}
	}
}

//--------------------------------------------------------------------------------
// Prillian	- Begin	Code Addition
//

if(	$userdata['user_id'] !=	ANONYMOUS )
{
	if(	defined('IN_CONTACT_LIST') && defined('SHOW_ONLINE') )
	{
		$contact_list->alert_check();
	}

	if ( empty($im_userdata) )
	{
		$im_userdata = init_imprefs($userdata['user_id'], false, true);
	}
	$im_auto_popup = auto_prill_check();
	if ( $im_userdata['new_ims'] )
	{
		$l_prillian_msg	= (	$im_userdata['new_ims']	> 1	) ?	$lang['New_ims']: $lang['New_im'];
		$l_prillian_text = sprintf($l_prillian_msg,	$im_userdata['new_ims']);
	}
	elseif ( $im_userdata['unread_ims']	)
	{
		$l_prillian_msg	= (	$im_userdata['unread_ims'] > 1 ) ? $lang['Unread_ims']:	$lang['Unread_im'];
		$l_prillian_text = sprintf($l_prillian_msg,	$im_userdata['unread_ims']);
	}

	// More sensible as $im_userdata is not populated for guests ;)
	$template->assign_vars(array(
		'IM_AUTO_POPUP'	=> $im_auto_popup,
		'IM_HEIGHT'	=> $im_userdata['mode_height'],
		'IM_WIDTH' => $im_userdata['mode_width'],
		'U_IM_LAUNCH' => append_sid(PRILL_URL .	$im_userdata['mode_string']),
		'L_IM_LAUNCH' => $l_prillian_text,
	//	'L_CONTACT_MAN'	=> $lang['Contact_Management'],
	//	'U_CONTACT_MAN'	=> append_sid(CONTACT_URL)
	));
}

//
// Prillian	- End Code Addition
//--------------------------------------------------------------------------------

// Format Timezone.	We are unable to use array_pop here, because of	PHP3 compatibility
$l_timezone	= explode('.', $board_config['board_timezone']);
$l_timezone	= (count($l_timezone) >	1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] :	$lang[number_format($board_config['board_timezone'])];

//
// The following assigns all _common_ variables	that may be	used at	any	point
// in a	template.
//

$meta_tags = array(
	'<meta name="keywords" content="' . $board_config['meta_keywords'] . '" />',
	'<meta name="description" content="' . $board_config['meta_description'] . '" />',
	'<meta name="revisit-after" content="' . $board_config['meta_revisit'] . ' days" />',
	'<meta name="author" content="' . $board_config['meta_author'] . '" />',
	'<meta name="owner" content="' . $board_config['meta_owner'] . '" />',
	'<meta name="distribution" content="' . $board_config['meta_distribution'] .'" />',
	'<meta name="robots" content="' . $board_config['meta_robots'] .'" />',
	'<meta name="abstract" content="' . $board_config['meta_abstract'] .'" />'
);

$meta_tags = implode('', $meta_tags);

$template->assign_vars(array(
	'SITENAME' => $board_config['sitename'],
	'SITE_DESCRIPTION' => $board_config['site_desc'],
	'META_TAG' => $meta_tags,
	'PAGE_TITLE' =>	$page_title,
	'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
	'TOTAL_USERS_ONLINE' =>	$l_online_users,
	'LOGGED_IN_USER_LIST' => $online_userlist,
	'RECORD_USERS' => sprintf($lang['Record_online_users'],	$board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone'])),
	'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
	'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
	'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,
	'PRIVMSG_IMG' => $icon_pm,

	'L_USERNAME' =>	$lang['Username'],
	'L_PASSWORD' =>	$lang['Password'],
	'L_LOGIN_LOGOUT' =>	$l_login_logout,
	'L_LOGIN' => $lang['Login'],
	'L_LOG_ME_IN' => $lang['Log_me_in'],
	'L_AUTO_LOGIN' => $lang['Log_me_in'],
	'L_INDEX' => $lang['Site_index'],
	'L_FORUM' => sprintf($lang['Forum_Index'], $board_config['sitename']),
	'L_FORUMS' => sprintf($lang['Forum']),
	'L_REGISTER' =>	$lang['Register'],
	'L_PROFILE'	=> $lang['Profile'],
	'L_SEARCH' => $lang['Search'],
	'L_PRIVATEMSGS'	=> $lang['Private_Messages'],
	'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_FORUM_RULES'	=> $lang['Forum_Rules'],
	'L_FAQ'	=> $lang['FAQ'],
	'L_USERGROUPS' => $lang['Usergroups'],
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
	'L_SEARCH_SELF'	=> $lang['Search_your_posts'],
	'L_BOARD_CURRENTLY_DISABLED' =>	$lang['Board_Currently_Disabled'],

	//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//	'L_WHOSONLINE_ADMIN' =>	sprintf($lang['Admin_online_color'], '<span	style="color:#'	. $theme['fontcolor3'] . '">', '</span>'),
//	'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span	style="color:#'	. $theme['fontcolor2'] . '">', '</span>'),
//-- add
	'L_WHOSONLINE' => get_users_online_color(),
//-- fin mod : profile cp --------------------------------------------------------------------------

	'U_SEARCH_UNANSWERED' => append_sid('search.'.$phpEx.'?search_id=unanswered'),
	'U_SEARCH_SELF'	=> append_sid('search.'.$phpEx.'?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid('search.'.$phpEx.'?search_id=newposts'),
	'U_INDEX' => append_sid('index.'.$phpEx),
	'U_FORUM' => append_sid('forum.'.$phpEx),
	'U_REGISTER' =>	append_sid('profile.'.$phpEx.'?mode=register'),
	'U_PROFILE'	=> append_sid('profile.'.$phpEx.'?mode=editprofile'),
	'U_PRIVATEMSGS'	=> append_sid('privmsg.'.$phpEx.'?folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.'.$phpEx.'?mode=newpm'),
	'U_SEARCH' => append_sid('search.'.$phpEx),
	'U_MEMBERLIST' => append_sid('memberlist.'.$phpEx),
	'U_FORUM_RULES'	=> append_sid('rules.'.$phpEx),
	'U_MODCP' => append_sid('modcp.'.$phpEx),
	'U_FAQ'	=> append_sid('faq.'.$phpEx),
	'U_VIEWONLINE' => append_sid('viewonline.'.$phpEx),
	'U_LOGIN_LOGOUT' =>	append_sid($u_login_logout),
	'U_GROUP_CP' =>	append_sid('groupcp.'.$phpEx),

	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' =>	$lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' =>	$lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
	'S_TIMEZONE' =>	sprintf($lang['All_times'],	$l_timezone),
	'S_LOGIN_ACTION' =>	append_sid('login.'.$phpEx),

	'T_HEAD_STYLESHEET'	=> $theme['head_stylesheet'],
	'T_BODY_BACKGROUND'	=> $theme['body_background'],
	'T_BODY_BGCOLOR' =>	'#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_SPAN_CLASS1'	=> $theme['span_class1'],
	'T_SPAN_CLASS2'	=> $theme['span_class2'],
	'T_SPAN_CLASS3'	=> $theme['span_class3'],

	'NAV_LINKS'	=> $nav_links_html)
);

//Disable Board	Admin Override - BEGIN
$template->assign_var('S_SITE_DISABLED', ( $userdata['user_level'] == ADMIN	&& $board_config['board_disable'] ?	TRUE : FALSE ));
//Disable Board	Admin Override - END

// It becomes not gloabal when page_header is called from message_die
global $mvModules;

reset($mvModules);
foreach	($mvModules	as $name =>	$value)
{
	if ($value['state']	!= 1 &&	$value['state']	!= 5)
	{
		continue;
	}

	foreach	($value['headers'] as $n =>	$file)
	{
		@include($phpbb_root_path . 'modules/' .	$name .	'/'	. $file);
	}
}

//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_user_logged_out', array());
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	if ( !empty($userdata['user_popup_pm'])	)
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}

	$template->assign_var('S_PM_POPUP',	( $userdata['user_popup_pm'] ? TRUE	: FALSE	));
}
$template->assign_var('S_USER_LOGGED_IN', (	$userdata['session_logged_in'] ? TRUE :	FALSE ));


// Add no-cache	control	for	cookies	if they	are	set
//$c_no_cache =	(isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .	'_sid']) ||	isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_data'])) ?	'no-cache="set-cookie",	' :	'';

// Work	around for "current" Apache	2 +	PHP	module which seems to not
// cope	with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) &&	strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control:	no-cache, pre-check=0, post-check=0');
}
else
{
	header ('Cache-Control:	private, pre-check=0, post-check=0,	max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');

//-- mod : categories hierarchy	--------------------------------------------------------------------
//-- add
// get the nav sentence
$nav_key = '';
if (isset($HTTP_POST_VARS[POST_CAT_URL]) ||	isset($HTTP_GET_VARS[POST_CAT_URL]))
{
	$nav_key = POST_CAT_URL	. ((isset($HTTP_POST_VARS[POST_CAT_URL])) ?	intval($HTTP_POST_VARS[POST_CAT_URL]) :	intval($HTTP_GET_VARS[POST_CAT_URL]));
}
if (isset($HTTP_POST_VARS[POST_FORUM_URL]) || isset($HTTP_GET_VARS[POST_FORUM_URL]))
{
	$nav_key = POST_FORUM_URL .	((isset($HTTP_POST_VARS[POST_FORUM_URL])) ?	intval($HTTP_POST_VARS[POST_FORUM_URL])	: intval($HTTP_GET_VARS[POST_FORUM_URL]));
}
if (isset($HTTP_POST_VARS[POST_TOPIC_URL]) || isset($HTTP_GET_VARS[POST_TOPIC_URL]))
{
	$nav_key = POST_TOPIC_URL .	((isset($HTTP_POST_VARS[POST_TOPIC_URL])) ?	intval($HTTP_POST_VARS[POST_TOPIC_URL])	: intval($HTTP_GET_VARS[POST_TOPIC_URL]));
}
if (isset($HTTP_POST_VARS[POST_POST_URL]) || isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$nav_key = POST_POST_URL . ((isset($HTTP_POST_VARS[POST_POST_URL]))	? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]));
}
if ( empty($nav_key) &&	(isset($HTTP_POST_VARS['selected_id']) || isset($HTTP_GET_VARS['selected_id']))	)
{
   $nav_key	= isset($HTTP_GET_VARS['selected_id']) ? $HTTP_GET_VARS['selected_id'] : $HTTP_POST_VARS['selected_id'];
}
if ( empty($nav_key) )
{
	$nav_key = 'Root';
}
$nav_cat_desc =	make_cat_nav_tree($nav_key,	$nav_pgm, 'nav', $topic_topic_title, $topic_forum_id);
if ( !empty($nav_cat_desc) )
{
	$nav_cat_desc =	$nav_separator . $nav_cat_desc;
}

// send	to template
$template->assign_vars(array(
	'SPACER'		=> $images['spacer'],
	'NAV_SEPARATOR'	=> $nav_separator,
	'NAV_CAT_DESC'	=> $nav_cat_desc,
	)
);
//-- fin mod : categories hierarchy	----------------------------------------------------------------

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
if ( $userdata['session_logged_in']	)
{
	if (empty($gen_simple_header))
	{
		$today_year = date('Y', cal_date(time(),$board_config['board_timezone']));
		$today_month = date('m', cal_date(time(),$board_config['board_timezone']));
		$today_day = date('d', cal_date(time(),$board_config['board_timezone']));
		$today = mktime( 0,	0, 1, $today_month,	$today_day,	$today_year	);

		$birthday_year = $today_year;
		$birthday_month	= intval(substr($userdata['user_birthday'],	4, 2));
		$birthday_day =	intval(substr($userdata['user_birthday'], 6, 2 ));
		$birthday =	mktime(	0, 0, 1, $birthday_month, $birthday_day, $birthday_year	);

		$last_year = date('Y', $userdata['user_last_birthday']);
		$last_month	= date('m',	$userdata['user_last_birthday']);
		$last_day =	date('d', $userdata['user_last_birthday']);
		$last =	(intval($userdata['user_last_birthday']) > 0 ) ? mktime( 0,	0, 1, $last_month, $last_day, $last_year ) : 0;

		// one week	limit
		if ( ($last	< $birthday) &&	($today	>= $birthday) && ($today <=	($birthday + 3600*24*7)) )
		{
			$userdata['user_last_birthday'] = cal_date(time(),$board_config['board_timezone']);
			$sql = "UPDATE " . USERS_TABLE . "
					SET	user_last_birthday = " . $userdata['user_last_birthday'] . "
					WHERE user_id =	" .	$userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update user information',	'',	__LINE__, __FILE__,	$sql);
			}
			$template->assign_block_vars('birthday_popup', array(
				'U_BIRTHDAY_POPUP' => append_sid('profile.'.$phpEx.'?mode=birthday_popup'),
				)
			);
		}
	}
}

// birthday	today list
function get_birthday_list(	$time )
{
	global $db,	$phpbb_root_path, $phpEx, $userdata, $admin_level, $level_prior;

	$res = '';

	// no guest	here, sorry	;)
	if ( ($userdata['user_id'] == ANONYMOUS) ||	!$userdata['session_logged_in']) return	$res;

	$today = date("md",	$time);
	$user_id = $userdata['user_id'];
	$sql = "SELECT u.*,
					(CASE WHEN i.buddy_ignore =	1 THEN 1 ELSE 0	END) AS	user_ignore,
					(CASE WHEN b.buddy_ignore =	0 THEN 1 ELSE 0	END) AS	user_friend,
					(CASE WHEN b.buddy_visible = 1 THEN	1 ELSE 0 END) AS user_visible
				FROM ((" . USERS_TABLE . " AS u
				LEFT JOIN "	. BUDDYS_TABLE . " AS b	ON b.user_id=u.user_id AND b.buddy_id=$user_id)
				LEFT JOIN "	. BUDDYS_TABLE . " AS i	ON i.user_id=$user_id AND i.buddy_id=u.user_id)
				WHERE u.user_id	<> " . ANONYMOUS . " AND u.user_birthday <>	0 AND u.user_birthday <> ''	and	RIGHT(u.user_birthday, 4) =	$today
				ORDER BY username";
	if ( !$result =	$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not read user	table to get birthday today	info', '', __LINE__, __FILE__, $sql);
	}
	while ($row	= $db->sql_fetchrow($result))
	{
		// get user	relational status
		$ignore	 = $row['user_ignore'];
		$friend	 = $row['user_friend'];
		$always_visible	= $row['user_visible'];

		// get the status of each info
		$real_display =	( !$ignore && $userdata['user_allow_real'] && $row['user_allow_real'] && ( ($row['user_viewreal'] == YES) || (	($row['user_viewreal'] == FRIEND_ONLY) && $friend )	) );

		// take	care of	admin status
		if ( is_admin($userdata) ||	($row['user_id'] ==	$userdata['user_id']) )	$real_display =	true;

		if ($real_display)
		{
			$txtclass =	get_user_level_class($row['user_level'], 'genmed', $row);
			if ($row['user_allow_viewonline'] != YES) $row['username'] = '<i>' . $row['username'] .	'</i>';
			$temp_url =	append_sid("profile.$phpEx?mode=viewprofile&amp;" .	POST_USERS_URL . "=" . $row['user_id']);
			$row['username'] = '<a href="' . $temp_url . '"	class="' . $txtclass . '">'	. $row['username'] . '</a>';

			// add to the user list
			$res .=	( $res != '' ) ? ',	' :	'';
			$res .=	$row['username'];
		}
	}
	return $res;
}

// send	happy birthday list
if (defined('SHOW_ONLINE') && $userdata['session_logged_in'])
{
	$birthday_fellows = get_birthday_list(cal_date(time(),$board_config['board_timezone']));
	if ( !empty($birthday_fellows) )
	{
		$template->assign_block_vars('switch_happy_birthday', array());
	}
	$template->assign_vars(array(
		'HAPPY_BIRTHDAY_IMG' =>	$images['Happy_birthday'],
		'L_HAPPY_BIRTHDAY' => $lang['Happy_birthday'],
		'HAPPY_BIRTHDAY_FELLOWS' =>	$birthday_fellows,
		)
	);
}
//-- fin mod : profile cp --------------------------------------------------------------------------

//
// START - Blocks
//

if ( (empty($gen_simple_header)) &&	(!defined('HAS_DIED')) )
{

	$sql = "SELECT lid,	view, groups FROM "	. BLOCKS_LAYOUT_TABLE .	" WHERE	name = '" .	$layout	. "'";

	if(	!($layout_result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR,	"Could not query portal	layout information", "", __LINE__, __FILE__, $sql);
	}

	$layout_row	= $db->sql_fetchrow($layout_result);

	$db->sql_freeresult($layout_result);

	$layout_id = $layout_row['lid'];

	// Is the user allowed to view this	layout?

	if ($userdata['user_id'] ==	ANONYMOUS)
	{
		$lview = in_array($layout_row['view'], array(0,1));
	}
	else
	{
		switch($userdata['user_level'])
		{
			case USER:
				$lview = in_array($layout_row['view'], array(0,2));
				break;
			case MOD:
				$lview = in_array($layout_row['view'], array(0,2,3));
				break;
			case ADMIN:
				$lview = in_array($layout_row['view'], array(0,1,2,3,4));
				break;
			default:
				$lview = in_array($layout_row['view'], array(0));
		}
	}

	// Is this user	in a group which can view this layout?

	$not_group_allowed = FALSE;
	if(!empty($layout_row['groups']))
	{
		$not_group_allowed = TRUE;
		$group_content = explode(",",$layout_row['groups']);
		for	($i	= 0; $i	< count($group_content); $i++)
		{
			if(in_array(intval($group_content[$i]),	block_groups($userdata['user_id'])))
			{
				$not_group_allowed = FALSE;
			}
		}
	}

	// The logic below states that the default layout is viewable to everyone, regardless of what is configured	in the	ACP.

	$user_permitted	= TRUE;

	if($layout_id=='')
	{
		$layout_id = $portal_config['default_layout'];
	}
	elseif ((!$lview) || ($not_group_allowed))
	{
		$user_permitted	= FALSE;
	}

	// Top Blocks
	$template->set_filenames(array(
		'top_blocks'   => 'blocks_top.tpl')
	);
	parse_blocks($layout_id, 'top');
	$template->assign_var('TOP_BLOCKS',	block_assign_var_from_handle($template,	'top_blocks'));

	// Left	Blocks
	$template->set_filenames(array(
		'left_blocks'	=> 'blocks_left.tpl')
	);
	parse_blocks($layout_id, 'left');
	$template->assign_var('LEFT_WIDTH',	$portal_config['left_width']);
	$template->assign_var('LEFT_BLOCKS', block_assign_var_from_handle($template, 'left_blocks'));

	// Right Blocks
	$template->set_filenames(array(
		'right_blocks'	=> 'blocks_right.tpl')
	);
	parse_blocks($layout_id, 'right');
	$template->assign_var('RIGHT_WIDTH', $portal_config['right_width']);
	$template->assign_var('RIGHT_BLOCKS', block_assign_var_from_handle($template, 'right_blocks'));

	// Bottom Blocks
	$template->set_filenames(array(
		'bottom_blocks'	=> 'blocks_bottom.tpl')
	);
	parse_blocks($layout_id, 'bottom');
	$template->assign_var('BOTTOM_BLOCKS', block_assign_var_from_handle($template, 'bottom_blocks'));

}
//
// END - Blocks
//

$template->pparse('overall_header');

// Disable Board Admin Override - BEGIN
// Won't Work for Jnr. Admin...

if ( $board_config['board_disable'] && !defined('IN_ADMIN') && !defined('IN_LOGIN') )
{
	$show_disabled_message = FALSE;

	if	( $userdata['user_level'] != ADMIN )
	{
		$show_disabled_message = TRUE;
	}
	elseif ($userdata['user_level'] == ADMIN && intval($board_config['board_disable_adminview']) != 1 )
	{
		$show_disabled_message = TRUE;
	}

	if ( $show_disabled_message	)
	{
		$message = (trim($board_config['board_disable_msg']) != '') ? $board_config['board_disable_msg'] : $lang['Board_Currently_Disabled'];
		message_die(GENERAL_MESSAGE, $message);
	}
}
//Disable Board	Admin Override - END
if ( isset($user_permitted) && (!$user_permitted) && (empty($gen_simple_header)) && (!defined('HAS_DIED')) )
{
	message_die(GENERAL_MESSAGE, $lang['Page_permission_denied']); // Very quick, very dirty.
}

?>