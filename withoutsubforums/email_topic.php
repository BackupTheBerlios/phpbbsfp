<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: email_topic.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : email_topic.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define ('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include ($phpbb_root_path . 'common.'.$phpEx);

$layout = $core_layout[LAYOUT_FORUM];

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FORUM);
init_userprefs($userdata);
//
// End session management
//

//
// Parameters
//
$topic_id = 0;
$post_id = 0;

if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) || isset($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topic_id = isset($HTTP_GET_VARS[POST_TOPIC_URL]) ? $HTTP_GET_VARS[POST_TOPIC_URL] : $HTTP_POST_VARS[POST_TOPIC_URL];
	$where_sql = "p.post_id = $post_id AND t.topic_id = p.topic_id";
	$redirect = POST_POST_URL . "=$post_id";
}
elseif ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
{
	$post_id = isset($HTTP_GET_VARS[POST_POST_URL]) ? $HTTP_GET_VARS[POST_POST_URL] : $HTTP_POST_VARS[POST_POST_URL];
	$where_sql = "t.topic_id = $topic_id";
	$redirect = POST_TOPIC_URL . "=$topic_id&amp;start=$start";
}
else
{
	message_die (GENERAL_MESSAGE, 'Topic_post_not_exist');
}

if ( !$userdata['session_logged_in'] )
{
	redirect(append_sid("login.$phpEx?redirect=email_topic.$phpEx?$redirect", true));
	exit;
}

$start = (isset ($HTTP_GET_VARS['start'])) ? $HTTP_GET_VARS['start'] : ((isset($HTTP_POST_VARS['start'])) ? $HTTP_POST_VARS['start'] : 0);
$submit = (isset ($HTTP_POST_VARS['submit'])) ? TRUE : 0;

$sql = "SELECT t.topic_id, t.topic_title, t.forum_id
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE $where_sql";

if ( !($result = $db->sql_query($sql)) )
{
	message_die (GENERAL_ERROR, 'Could not obtain topic information', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$db->sql_freeresult($result);

$topic_title = $row['topic_title'];
$topic_id = $row['topic_id'];
$forum_id = $row['forum_id'];

if ($submit)
{
	$friend_name = (isset ($HTTP_POST_VARS['friend_name'])) ? trim(strip_tags($HTTP_POST_VARS['friend_name'])) : '';
	$friend_email = (isset ($HTTP_POST_VARS['friend_email'])) ? trim(strip_tags(htmlspecialchars($HTTP_POST_VARS['friend_email']))) : '';

	if (!$friend_name || !$friend_email)
	{
		message_die (GENERAL_MESSAGE, $lang['No_friend_specified']);
	}

	$server_url = server_path_info(FALSE);

	$email_headers = 'From: ' . $userdata['user_email'] . "\nReturn-Path: " . $userdata['user_email'] . "\n";

	include ($phpbb_root_path . 'includes/emailer.'.$phpEx);
	$emailer = new emailer ($board_config['smtp_delivery']);
	$emailer->use_template ('email_topic', $userdata['user_lang']);
	$emailer->email_address ($friend_email);
	$emailer->set_subject(); //$lang['Email_topic']
	$emailer->extra_headers ($email_headers);
	$emailer->assign_vars(array(
		'SITENAME' => $board_config['sitename'],
		'USERNAME' => $userdata['username'],
		'FRIEND_NAME' => $friend_name,
		'TOPIC' => $topic_title,

		'BOARD_EMAIL' => $board_config['board_email'],
		'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $userdata['username']),

		'U_TOPIC' => $server_url . "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id"
	));
	$emailer->send();
	$emailer->reset();

	$redirect .= ($post_id) ? "#$post_id" : '';
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid ("viewtopic.$phpEx?$redirect") . '">'
	));

	$msg = $lang['Email_sent'] . '<br /><br />' . sprintf ($lang['Click_return_topic'], '<a href="' . append_sid ("viewtopic.$phpEx?$redirect") . '">', '</a>') . '<br /><br />' . sprintf ($lang['Click_return_index'], '<a href="' . append_sid ("index.$phpEx") . '">', '</a>');
	message_die (GENERAL_MESSAGE, $msg);
}


//
// Default page
//
$page_title = $lang['Email_topic'];
include ($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'email_topic_body.tpl'
));

make_jumpbox('viewforum.'.$phpEx, $forum_id);

$s_hidden_fields = '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '">';
$s_hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '">';
$s_hidden_fields .= '<input type="hidden" name="start" value="' . $start . '">';

$template->assign_vars(array(
	'L_TITLE' => $lang['Email_topic_settings'],

	'L_FRIEND_NAME' => $lang['Friend_name'],
	'L_FRIEND_EMAIL' => $lang['Friend_email'],
	'L_SUBJECT' => $lang['Subject'],
	'L_SUBMIT' => $lang['Submit'],

	'TOPIC_TITLE' => $topic_title,

	'S_EMAIL_ACTION' => append_sid ("email_topic.$phpEx"),
	'S_HIDDEN_FIELDS' => $s_hidden_fields
));

$template->pparse('body');
include ($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
