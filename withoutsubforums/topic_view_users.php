<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: topic_view_users.php,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $
//
// FILENAME	 : topic_view_users.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//             © 2002, 2004	The phpBB Group
//             © 2003, 2004	Niels Chr. Rød
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------


define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);

$layout = $core_layout[LAYOUT_FORUM];

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FORUM);
init_userprefs($userdata);
//
// End session management
//

// Start add - Who viewed a topic MOD
if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_POST_VARS[POST_TOPIC_URL]);
}

if ( !$userdata['session_logged_in'] )
{
	redirect(append_sid("login.$phpEx?redirect=topic_view_users.$phpEx&" . POST_TOPIC_URL . "=$topic_id", true));
	exit;
}

// find the forum, in witch the topic are located
$sql = "SELECT f.forum_id
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
	WHERE f.forum_id = t.forum_id AND t.topic_id=$topic_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$forum_id = $forum_topic_data['forum_id'];
// End add - Who viewed a topic MOD


$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'joined';
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

//
// Memberlist sorting
//

// Start Replacement - Who viewed a topic MOD
$mode_types_text = array($lang['Sort_Username'], $lang['Topic_time'], $lang['Topic_count']);
$mode_types = array('username', 'topic_time', 'topic_count');
// End Replacement - Who viewed a topic MOD

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

// Start add - Who viewed a topic MOD
$select_sort_order .= '<input type="hidden" name="'.POST_TOPIC_URL.'" value="'.$topic_id.'"/>';
// End add - Who viewed a topic MOD


//
// Generate page
//
$page_title = $lang['Memberlist'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'topic_view_users_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],

	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],

	// Start replacement - Who viewed a topic MOD
	'L_TOPIC_TIME' => $lang['Topic_time'],
	'L_TOPIC_COUNT' => $lang['Topic_count'],
	// End replacement - Who viewed a topic MOD

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,

	//'U_TOPIC' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
	'U_TOPIC' => sprintf ($lang['Click_return_topic'], '<a class="gen" href="' . append_sid ("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>'),

	// Start replacement - Who viewed a topic MOD
	'S_MODE_ACTION' => append_sid("topic_view_users.$phpEx"))
	// End replacement - Who viewed a topic MOD

);

switch( $mode )
{

// Start replacement - Who viewed a topic MOD
case 'username':
	$order_by = "u.username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
case 'topic_count':
	$order_by = "tv.view_count $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
case 'topic_time':
	$order_by = "tv.view_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
default:
	$order_by = "u.user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
// End replacement - Who viewed a topic MOD

}

// Start replacement - Who viewed a topic MOD & Admin/mod colors management MOD
$sql = "SELECT u.username, u.user_id, u.user_level, u.user_regdate, tv.view_time, tv.view_count
	FROM " . USERS_TABLE . " u, " . TOPICS_VIEW_TABLE . " tv
	WHERE u.user_id = tv.user_id AND tv.topic_id= " . $topic_id . "
	ORDER BY $order_by";
// End replacement - Who viewed a topic MOD & Admin/mod colors management MOD

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$username = $row['username'];
		$user_id = $row['user_id'];

		// Start replacement - Who viewed a topic MOD
		$topic_time = ( $row['view_time'] ) ? create_date($board_config['default_dateformat'],$row['view_time'], $board_config['board_timezone']) : $lang['Never_last_logon'];
		$view_count = ( $row['view_count'] ) ? $row['view_count']:'';
		// End replacement - Who viewed a topic MOD

		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
		$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		// Start add - Admins/mods color management MOD
		switch ( $row['user_level'] ) {
			case ADMIN:
				$viewprofile_color = 'class="admincolor"';
				break;
			case MOD:
				$viewprofile_color = 'class="modcolor"';
				break;
			default:
				$viewprofile_color = '';
			break;
		}
		// End add - Admins/mods color management MOD

		$template->assign_block_vars('memberrow', array(
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,

			// Start replacement - Who viewed a topic MOD
			'TIME' => $topic_time,
			'COUNT' => $view_count,
			// End replacement - Who viewed a topic MOD

			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,

			// Start add - Admins/mods color management MOD
			'U_VIEWPROFILE_COLOR' => $viewprofile_color,
			// End add - Admins/mods color management MOD
			'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{

// Start replacement - Who viewed a topic MOD
	$sql = "SELECT count(*) AS total
		FROM " . TOPICS_VIEW_TABLE . "
		WHERE topic_id = " . $topic_id;
// End replacement - Who viewed a topic MOD


	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_members = $total['total'];
		// Start replacement - Who viewed a topic MOD
		$pagination = generate_pagination("topic_view_users.$phpEx?".POST_TOPIC_URL."=$topic_id&amp;mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
		// End replacement - Who viewed a topic MOD
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )),
	'L_GOTO_PAGE' => $lang['Goto_page'])
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>