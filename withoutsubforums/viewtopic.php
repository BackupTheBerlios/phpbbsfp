<?php
//-- mod : sub-template	----------------------------------------------------------------------------
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: viewtopic.php,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $
//
// FILENAME	 : viewtopic.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team and © 2001, 2003 The phpBB	Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
define('IN_CASHMOD', true);
define('CM_VIEWTOPIC', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'),	1);

include($phpbb_root_path . 'common.'.$phpEx);

$layout = $core_layout[LAYOUT_FORUM];

include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
include($phpbb_root_path . 'profilcp/functions_profile.'.$phpEx);
//-- fin mod : profile cp --------------------------------------------------------------------------

//
// Start initial var setup
//
$topic_id =	$post_id = 0;
if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id =	intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if	( isset($HTTP_GET_VARS['topic']) )
{
	$topic_id =	intval($HTTP_GET_VARS['topic']);
}

if ( isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
}

if ( !isset($topic_id) && !isset($post_id) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$download = ( isset($HTTP_GET_VARS['download']) ) ? $HTTP_GET_VARS['download'] : '';

// Printer Friendly START
if ( isset($HTTP_GET_VARS['printertopic']) )
{
	$start = ( isset($HTTP_GET_VARS['start_rel']) ) && ( isset($HTTP_GET_VARS['printertopic']) ) ? intval($HTTP_GET_VARS['start_rel']) - 1 : $start;
	// $finish when positive indicates last message; when negative it indicates range; can't be 0
	if ( isset($HTTP_GET_VARS['finish_rel']) )
	{
		$finish = intval($HTTP_GET_VARS['finish_rel']);
	}
	if ( ($finish >= 0) && (($finish - $start) <=0) )
	{
		unset($finish);
	}
}
// Printer Friendly END


// download the topic if the user has requested a download.
if ( $download )
{
	// From My POV this code is quite ugly

	$sql_download = ( $download != -1 ) ? " AND p.post_id = $download " : '';

	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	$sql = "SELECT u.*, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
		WHERE p.topic_id = $topic_id
			$sql_download
			AND pt.post_id = p.post_id
			AND u.user_id = p.poster_id
			ORDER BY p.post_time ASC, p.post_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not create download stream for post.", '', __LINE__, __FILE__, $sql);
	}

	$download_file = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$poster_id = $row['user_id'];
		$poster = ( $poster_id == ANONYMOUS ) ? $lang['Guest'] : $row['username'];

		$post_date = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

		$post_subject = ( $row['post_subject'] != '' ) ? $row['post_subject'] : '';

		$bbcode_uid = $row['bbcode_uid'];
		$message = $row['post_text'];
		$message = strip_tags($message);
		$message = preg_replace("/\[.*?:$bbcode_uid:?.*?\]/si", '', $message);
		$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
		$message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);

		$message = unprepare_message($message);
		$message = preg_replace('/&#40;/', '(', $message);
		$message = preg_replace('/&#41;/', ')', $message);
		$message = preg_replace('/&#58;/', ':', $message);

		if (count($orig_word))
		{
			$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

			$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
		}

		$break = "\n";
		$line = '-----------------------------------';
		$download_file .= $break.$line.$break.$poster.$break.$post_date.$break.$break.$post_subject.$break.$line.$break.$message.$break;
	}

	$disp_folder = ( $download == -1 ) ? 'Topic_'.$topic_id : 'Post_'.$download;
	$filename = $board_config['sitename']."_".$disp_folder."_".date("Ymd",time()).".txt";
	header('Content-Type: text/x-delimtext; name="'.$filename.'"');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Content-Transfer-Encoding: plain/text');
	header('Content-Length: '.strlen($download_file));
	print $download_file;

	exit;
}

//
// Find	topic id if	user requested a newer
// or older	topic
//
if ( isset($HTTP_GET_VARS['view']) && empty($HTTP_GET_VARS[POST_POST_URL]) )
{
	if ( $HTTP_GET_VARS['view']	== 'newest'	)
	{
		if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .	'_sid']) ||	isset($HTTP_GET_VARS['sid']) )
		{
			$session_id	= isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) ?	$HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid'] : $HTTP_GET_VARS['sid'];

			if ( $session_id )
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p,	" .	SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '$session_id'
						AND	u.user_id =	s.session_user_id
						AND	p.topic_id = $topic_id
						AND	p.post_time	>= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain newer/older topic information', '', __LINE__, __FILE__, $sql);
				}

				if ( !($row	= $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}

				$post_id = $row['post_id'];

				if (isset($HTTP_GET_VARS['sid']))
				{
					redirect("viewtopic.$phpEx?sid=$session_id&" . POST_POST_URL . "=$post_id#$post_id");
				}
				else
				{
					redirect("viewtopic.$phpEx?" . POST_POST_URL . "=$post_id#$post_id");
				}
			}
		}

		redirect(append_sid("viewtopic.$phpEx?"	. POST_TOPIC_URL . "=$topic_id", true));
	}
	else if	( $HTTP_GET_VARS['view'] ==	'next' || $HTTP_GET_VARS['view'] ==	'previous' )
	{
		$sql_condition = ( $HTTP_GET_VARS['view'] == 'next'	) ?	'>'	: '<';
		$sql_ordering =	( $HTTP_GET_VARS['view'] ==	'next' ) ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id
			FROM " . TOPICS_TABLE .	" t, " . TOPICS_TABLE .	" t2
			WHERE
				t2.topic_id	= $topic_id
				AND	t.forum_id = t2.forum_id
				AND	t.topic_last_post_id $sql_condition	t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain newer/older topic information", '', __LINE__, __FILE__, $sql);
		}

		if ( $row =	$db->sql_fetchrow($result) )
		{
			$topic_id =	intval($row['topic_id']);
		}
		else
		{
			$message = ( $HTTP_GET_VARS['view']	== 'next' )	? 'No_newer_topics'	: 'No_older_topics';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// This	rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page	the	post is	on and the correct display of viewtopic)
//
$join_sql_table	= (	empty($post_id)	) ?	'' : ",	" .	POSTS_TABLE	. "	p, " . POSTS_TABLE . " p2 ";
$join_sql =	( empty($post_id) )	? "t.topic_id =	$topic_id" : "p.post_id	= $post_id AND t.topic_id =	p.topic_id AND p2.topic_id	= p.topic_id AND p2.post_id	<= $post_id";
$count_sql = ( empty($post_id) ) ? '' :	", COUNT(p2.post_id) AS	prev_posts";

$order_sql = ( empty($post_id) ) ? '' :	"GROUP BY p.post_id, t.topic_id, t.topic_title,	t.topic_status,	t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id,	f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky,	f.auth_announce, f.auth_pollcreate,	f.auth_vote, f.auth_attachments, f.auth_download, t.topic_attachment, f.auth_ban, f.auth_greencard, f.auth_bluecard ORDER BY p.post_id ASC";

$sql = "SELECT t.topic_id, t.topic_title, t.topic_desc, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read,	f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.auth_download, t.topic_attachment, f.auth_ban, f.auth_greencard, f.auth_bluecard" . $count_sql .	"
	FROM " . TOPICS_TABLE .	" t, " . FORUMS_TABLE .	" f" . $join_sql_table . "
	WHERE $join_sql
		AND	f.forum_id = t.forum_id
		$order_sql";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$forum_id =	intval($forum_topic_data['forum_id']);
// Start add - Who viewed a topic MOD
$topic_id = intval($forum_topic_data['topic_id']);
// End add - Who viewed a topic MOD

//
// Start session management
//
$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);

// Printer Friendly START
if ( !file_exists(@realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_printertopic.'.$phpEx)) )
{
	include($phpbb_root_path . 'language/lang_english/lang_printertopic.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_printertopic.' . $phpEx);
}
// Printer Friendly END

//
// End session management
//

//
// Start auth check
//
$is_auth = array();

$is_auth	= auth(AUTH_ALL, $forum_id,	$userdata, $forum_topic_data);
if( !$is_auth['auth_view'] || !$is_auth['auth_read'])
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect =	( isset($post_id) )	? POST_POST_URL	. "=$post_id" :	POST_TOPIC_URL . "=$topic_id";
		$redirect .= ( isset($start) ) ? "&start=$start" : '';
		redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&$redirect",	true));
	}

$message = ( !$is_auth['auth_view']	) ?	$lang['Topic_post_not_exist'] :	sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}
//
// End auth	check
//

// Start add - Who viewed a topic MOD
$user_id=$userdata['user_id'];
$sql='UPDATE '.TOPICS_VIEW_TABLE.' SET topic_id="'.$topic_id.'", view_time="'.time().'", view_count=view_count+1 WHERE topic_id='.$topic_id.' AND user_id='.$user_id;
if ( !$db->sql_query($sql) || !$db->sql_affectedrows() )
{
	$sql = 'INSERT IGNORE INTO '.TOPICS_VIEW_TABLE.' (topic_id, user_id, view_time,view_count)
		VALUES ('.$topic_id.', "'.$user_id.'", "'.time().'","1")';
	if ( !($db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, 'Error create user view topic information ', '', __LINE__, __FILE__, $sql);
	}
}
// End add - Who viewed a topic MOD

$forum_name = $forum_topic_data['forum_name'];
$topic_title = $forum_topic_data['topic_title'];
$topic_desc = $forum_topic_data['topic_desc'];
$topic_id =	intval($forum_topic_data['topic_id']);
$topic_time	= $forum_topic_data['topic_time'];

if ( !empty($post_id) )
{
	$start = floor(($forum_topic_data['prev_posts']	- 1) / intval($board_config['posts_per_page']))	* intval($board_config['posts_per_page']);
}

//
// Is user watching	this thread?
//
if(	$userdata['session_logged_in'] )
{
	$can_watch_topic = TRUE;

	$sql = "SELECT notify_status
		FROM " . TOPICS_WATCH_TABLE	. "
		WHERE topic_id = $topic_id
			AND	user_id	= "	. $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain topic watch information", '', __LINE__, __FILE__, $sql);
	}

	if ( $row =	$db->sql_fetchrow($result) )
	{
		if ( isset($HTTP_GET_VARS['unwatch']) )
		{
			if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
			{
				$is_watching_topic = 0;

				$sql_priority =	(SQL_LAYER == "mysql") ? "LOW_PRIORITY"	: '';
				$sql = "DELETE $sql_priority FROM "	. TOPICS_WATCH_TABLE . "
					WHERE topic_id = $topic_id
						AND	user_id	= "	. $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?"	. POST_TOPIC_URL . "=$topic_id&amp;start=$start")	. '">')
			);

			$message = $lang['No_longer_watching'] . '<br /><br	/>'	. sprintf($lang['Click_return_topic'], '<a href="'	. append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = TRUE;

			if ( $row['notify_status'] )
			{
				$sql_priority =	(SQL_LAYER == "mysql") ? "LOW_PRIORITY"	: '';
				$sql = "UPDATE $sql_priority " . TOPICS_WATCH_TABLE	. "
					SET	notify_status =	0
					WHERE topic_id = $topic_id
						AND	user_id	= "	. $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not update topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	else
	{
		if ( isset($HTTP_GET_VARS['watch'])	)
		{
			if ( $HTTP_GET_VARS['watch'] ==	'topic'	)
			{
				$is_watching_topic = TRUE;

				$sql_priority =	(SQL_LAYER == "mysql") ? "LOW_PRIORITY"	: '';
				$sql = "INSERT $sql_priority INTO "	. TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
					VALUES (" .	$userdata['user_id'] . ", $topic_id, 0)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not insert topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?"	. POST_TOPIC_URL . "=$topic_id&amp;start=$start")	. '">')
			);

			$message = $lang['You_are_watching'] . '<br	/><br />' .	sprintf($lang['Click_return_topic'], '<a href="'	. append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = 0;
		}
	}
}
else
{
	if ( isset($HTTP_GET_VARS['unwatch']) )
	{
		if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
		{
			redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&" .	POST_TOPIC_URL . "=$topic_id&unwatch=topic",	true));
		}
	}
	else
	{
		$can_watch_topic = 0;
		$is_watching_topic = 0;
	}
}

//
// Generate	a 'Show	posts in previous x	days' select box. If the postdays var is POSTed
// then	get	it's value,	find the number	of topics with dates newer than	it (to properly
// handle pagination) and alter	the	main query
//
$previous_days = array(0, 1, 7,	14,	30,	90,	180, 364);
$previous_days_text	= array($lang['All_Posts'],	$lang['1_Day'],	$lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'],	$lang['1_Year']);

if(	!empty($HTTP_POST_VARS['postdays'])	|| !empty($HTTP_GET_VARS['postdays']) )
{
	$post_days = ( !empty($HTTP_POST_VARS['postdays']) ) ? intval($HTTP_POST_VARS['postdays']) : intval($HTTP_GET_VARS['postdays']);
	$min_post_time = time()	- (intval($post_days) *	86400);

	$sql = "SELECT COUNT(p.post_id)	AS num_posts
		FROM " . TOPICS_TABLE .	" t, " . POSTS_TABLE . " p
		WHERE t.topic_id = $topic_id
			AND	p.topic_id = t.topic_id
			AND	p.post_time	>= $min_post_time";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain limited topics	count information",	'',	__LINE__, __FILE__,	$sql);
	}

	$total_replies = ( $row	= $db->sql_fetchrow($result) ) ? intval($row['num_posts']) : 0;

	$limit_posts_time =	"AND p.post_time >=	$min_post_time ";

	if ( !empty($HTTP_POST_VARS['postdays']))
	{
		$start = 0;
	}
}
else
{
	$total_replies = intval($forum_topic_data['topic_replies'])	+ 1;

	$limit_posts_time =	'';
	$post_days = 0;
}

$select_post_days =	'<select name="postdays">';
for($i = 0;	$i < count($previous_days);	$i++)
{
	$selected =	($post_days	== $previous_days[$i]) ? ' selected="selected"'	: '';
	$select_post_days .= '<option value="' . $previous_days[$i]	. '"' .	$selected .	'>'	. $previous_days_text[$i] .	'</option>';
}
$select_post_days .= '</select>';

//
// Decide how to order the post	display
//
if ( !empty($HTTP_POST_VARS['postorder']) || !empty($HTTP_GET_VARS['postorder']) )
{
   $post_order = (!empty($HTTP_POST_VARS['postorder']))	? htmlspecialchars($HTTP_POST_VARS['postorder']) : htmlspecialchars($HTTP_GET_VARS['postorder']);
   $post_time_order	= ($post_order == "asc") ? "ASC" : "DESC";
}
else
{
   $post_order = 'asc';
   $post_time_order	= 'ASC';
}


$select_post_order = '<select name="postorder">';
if ( $post_time_order == 'ASC' )
{
	$select_post_order .= '<option value="asc" selected="selected">' . $lang['Oldest_First'] . '</option><option value="desc">' . $lang['Newest_First'] . '</option>';
}
else
{
	$select_post_order .= '<option value="asc">' . $lang['Oldest_First'] . '</option><option value="desc" selected="selected">' .	$lang['Newest_First'] .	'</option>';
}
$select_post_order .= '</select>';

//
// Go ahead	and	pull all data for this topic
//
//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
// $sql	= "SELECT u.username, u.user_id, u.user_posts, u.user_from,	u.user_website,	u.user_email, u.user_icq, u.user_aim,	u.user_yim,	u.user_regdate,	u.user_msnm, u.user_viewemail, u.user_rank,	u.user_sig,	u.user_sig_bbcode_uid, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
//-- add
$sql = "SELECT u.*, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
    FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
    WHERE p.topic_id = $topic_id
        $limit_posts_time
        AND pt.post_id = p.post_id
        AND u.user_id = p.poster_id
    ORDER BY p.post_time $post_time_order
    LIMIT $start, ".(isset($finish)? ((($finish - $start) > 0)? ($finish - $start): -$finish): $board_config['posts_per_page']);
$cm_viewtopic->generate_columns($template,$forum_id,$sql);
//-- fin mod : profile cp --------------------------------------------------------------------------

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__,	__FILE__, $sql);
}

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
$user_ids =	array();
//-- fin mod : profile cp --------------------------------------------------------------------------

$postrow = array();
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$postrow[] = $row;
//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
		$user_ids[]	= $row['user_id'];
//-- fin mod : profile cp --------------------------------------------------------------------------
	}
	while ($row	= $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	$total_posts = count($postrow);
}
else
{
   include($phpbb_root_path	. 'includes/functions_admin.' .	$phpEx);
   sync('topic', $topic_id);

   message_die(GENERAL_MESSAGE,	$lang['No_posts_topic']);
}

$resync	= FALSE;
if ($forum_topic_data['topic_replies'] + 1 < $start	+ count($postrow))
{
   $resync = TRUE;
}
elseif ($start + $board_config['posts_per_page'] > $forum_topic_data['topic_replies'])
{
   $row_id = intval($forum_topic_data['topic_replies'])	% intval($board_config['posts_per_page']);
   if ($postrow[$row_id]['post_id']	!= $forum_topic_data['topic_last_post_id'] || $start + count($postrow) < $forum_topic_data['topic_replies'])
   {
	  $resync =	TRUE;
   }
}
elseif (count($postrow)	< $board_config['posts_per_page'])
{
   $resync = TRUE;
}

if ($resync)
{
   include($phpbb_root_path	. 'includes/functions_admin.' .	$phpEx);
   sync('topic', $topic_id);

   $result = $db->sql_query('SELECT	COUNT(post_id) AS total	FROM ' . POSTS_TABLE . ' WHERE topic_id	= '	. $topic_id);
   $row	= $db->sql_fetchrow($result);
   $total_replies =	$row['total'];
}

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
// $sql	= "SELECT *
//	FROM " . RANKS_TABLE . "
//	ORDER BY rank_special, rank_min";
// if (	!($result =	$db->sql_query($sql)) )
// {
//	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__,	__FILE__, $sql);
// }
//
// $ranksrow = array();
// while ( $row	= $db->sql_fetchrow($result) )
// {
//	$ranksrow[]	= $row;
// }
// $db->sql_freeresult($result);
//-- add
$buddys	= array();
if (count($user_ids) > 0)
{
	$s_user_ids	= implode(', ',	$user_ids);

	// get base	info
	$sql = "SELECT * FROM "	. BUDDYS_TABLE . " WHERE user_id=" . $userdata['user_id'] .	" and buddy_id in ($s_user_ids)";
	if ( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR,	"Could not obtain buddys information.",	'',	__LINE__, __FILE__,	$sql);
	while (	$row = $db->sql_fetchrow($result) )
	{
		$buddys[ $row['buddy_id'] ]['buddy_my_ignore'] = $row['buddy_ignore'];
		$buddys[ $row['buddy_id'] ]['buddy_my_friend'] = !$row['buddy_ignore'];
		$buddys[ $row['buddy_id'] ]['buddy_friend']	= false;
		$buddys[ $row['buddy_id'] ]['buddy_visible'] = false;
	}

	// check if	in the topic author's friend list and "always visible" status he granted
	$sql = "SELECT * FROM "	. BUDDYS_TABLE . " WHERE buddy_id="	. $userdata['user_id'] . " and user_id in ($s_user_ids)";
	if ( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR,	"Could not obtain buddys information.",	'',	__LINE__, __FILE__,	$sql);
	while (	$row = $db->sql_fetchrow($result) )
	{
		if ( !isset($buddys[ $row['user_id'] ])	) $buddys[ $row['user_id'] ]['buddy_my_ignore']	= false;
		if ( !isset($buddys[ $row['user_id'] ])	) $buddys[ $row['user_id'] ]['buddy_my_friend']	= false;
		$buddys[ $row['user_id'] ]['buddy_friend'] = !$row['buddy_ignore'];
		$buddys[ $row['user_id'] ]['buddy_visible']	= $row['buddy_visible'];
	}
}
//-- fin mod : profile cp --------------------------------------------------------------------------

//
// Define censored word	matches
//
$orig_word = array();
$replacement_word =	array();
obtain_word_list($orig_word, $replacement_word);

//
// Censor topic	title
//
if ( count($orig_word) )
{
	$topic_title = preg_replace($orig_word,	$replacement_word, $topic_title);
}

//
// Was a highlight request part	of the URI?
//
$highlight_match = $highlight =	'';
if (isset($HTTP_GET_VARS['highlight']))
{
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars(urldecode($HTTP_GET_VARS['highlight']))));

	for($i = 0;	$i < sizeof($words); $i++)
	{
		if (trim($words[$i]) !=	'')
		{
			$highlight_match .=	(($highlight_match != '') ?	'|'	: '') .	str_replace('*', '\w*',	phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($HTTP_GET_VARS['highlight']);
}

//
// Post, reply and other URL generation	for
// templating vars
//
$printer_topic_url = append_sid("viewtopic.$phpEx?printertopic=1&" . POST_TOPIC_URL . "=$topic_id&start=$start&postdays=$post_days&postorder=$post_order&vote=viewresult");
$new_topic_url = append_sid("posting.$phpEx?mode=newtopic&amp;"	. POST_FORUM_URL . "=$forum_id");
$reply_topic_url = append_sid("posting.$phpEx?mode=reply&amp;" . POST_TOPIC_URL	. "=$topic_id");
$view_forum_url	= append_sid("viewforum.$phpEx?" . POST_FORUM_URL .	"=$forum_id");
$view_prev_topic_url = append_sid("viewtopic.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;view=previous");
$view_next_topic_url = append_sid("viewtopic.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;view=next");

//
// Mozilla navigation bar
//
$nav_links['prev'] = array(
	'url' => $view_prev_topic_url,
	'title'	=> $lang['View_previous_topic']
);
$nav_links['next'] = array(
	'url' => $view_next_topic_url,
	'title'	=> $lang['View_next_topic']
);
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title'	=> $forum_name
);

$reply_img =      ( $forum_topic_data['forum_status'] ==	FORUM_LOCKED ||	$forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $images['reply_locked'] : $images['reply_new'];
$reply_img_mini = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum__topic_data['topic_status'] == TOPIC_LOCKED ) ? $images['icon_locked'] : $images['icon_reply'];
$reply_alt = ( $forum_topic_data['forum_status'] ==	FORUM_LOCKED ||	$forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
$post_img =	( $forum_topic_data['forum_status']	== FORUM_LOCKED	) ?	$images['post_locked'] : $images['post_new'];
$post_alt =	( $forum_topic_data['forum_status']	== FORUM_LOCKED	) ?	$lang['Forum_locked'] :	$lang['Post_new_topic'];

//
// Set a cookie	for	this topic
//
 if (	$userdata['session_logged_in'] )
 {
	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .	'_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t'])	: array();
	$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .	'_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f'])	: array();

	if ( !empty($tracking_topics[$topic_id]) &&	!empty($tracking_forums[$forum_id])	)
	{
		$topic_last_read = ( $tracking_topics[$topic_id] > $tracking_forums[$forum_id] ) ? $tracking_topics[$topic_id]	: $tracking_forums[$forum_id];
	}
	else if	( !empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]) )
	{
		$topic_last_read = ( !empty($tracking_topics[$topic_id]) ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else
	{
		$topic_last_read = $userdata['user_lastvisit'];
	}

	if ( count($tracking_topics) >=	150	&& empty($tracking_topics[$topic_id]) )
	{
		asort($tracking_topics);
		unset($tracking_topics[key($tracking_topics)]);
	}

	$tracking_topics[$topic_id]	= time();

	setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics),	0, $board_config['cookie_path'], $board_config['cookie_domain'],	$board_config['cookie_secure']);
 }

//
// Load templates
//
if(isset($HTTP_GET_VARS['printertopic']))
{
	$template->set_filenames(array(
		'quick_reply_body' => 'quick_reply_body.tpl',
		'body' => 'printertopic_body.tpl')
	);
}
else
{
	$template->set_filenames(array(
		'quick_reply_body' => 'quick_reply_body.tpl',
		'body' => 'viewtopic_body.tpl')
	);
}
make_jumpbox('viewforum.'.$phpEx, $forum_id);

//
// Output page header
//
$page_title = $lang['View_topic'] .' - ' . $topic_title;
if(isset($HTTP_GET_VARS['printertopic']))
{
	include($phpbb_root_path . 'includes/page_header_printer.'.$phpEx);
}
else
{
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);
}


//-- mod : sub-template	----------------------------------------------------------------------------
//-- add
$reply_img = ( $forum_topic_data['forum_status'] ==	FORUM_LOCKED ||	$forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $images['reply_locked'] : $images['reply_new'];
$post_img =	( $forum_topic_data['forum_status']	== FORUM_LOCKED	) ?	$images['post_locked'] : $images['post_new'];
//-- fin mod : sub-template	------------------------------------------------------------------------

//
// User	authorisation levels output
//
$s_auth_can	= (	( $is_auth['auth_post']	) ?	$lang['Rules_post_can']	: $lang['Rules_post_cannot'] ) . '<br />';
$s_auth_can	.= ( ( $is_auth['auth_reply'] )	? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot'] ) . '<br />';
$s_auth_can	.= ( ( $is_auth['auth_edit'] ) ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot'] )	. '<br />';
$s_auth_can	.= ( ( $is_auth['auth_delete'] ) ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot']	) .	'<br />';
$s_auth_can	.= ( ( $is_auth['auth_vote'] ) ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot'] )	. '<br />';
$s_auth_can .= ( $is_auth['auth_ban'] ) ? $lang['Rules_ban_can'] . '<br />' : '';
$s_auth_can .= ( $is_auth['auth_greencard'] ) ? $lang['Rules_greencard_can'] . '<br />' : '';
$s_auth_can .= ( $is_auth['auth_bluecard'] ) ? $lang['Rules_bluecard_can'] . '<br />' : '';
if (!intval($attach_config['disable_mod']))
{
	$s_auth_can .= ( ( $is_auth['auth_attachments'] && $is_auth['auth_post'] ) ? $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';
	$s_auth_can .= (($is_auth['auth_download']) ? $lang['Rules_download_can'] : $lang['Rules_download_cannot'] ) . '<br />';
}

$topic_mod = '';

if ( $is_auth['auth_mod'] )
{
	$s_auth_can	.= sprintf($lang['Rules_moderate'],	"<a	href=\"modcp.$phpEx?" .	POST_FORUM_URL . "=$forum_id&amp;sid=" .	$userdata['session_id']	. '">',	'</a>');

	//$topic_mod .=	"<a	href=\"modcp.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;mode=delete&amp;sid=" . $userdata['session_id']	. '"><img src="' . $images['topic_mod_delete'] . '"	alt="' . $lang['Delete_topic'] . '"	title="' . $lang['Delete_topic'] . '"></a>&nbsp;';

	$topic_mod .= (	$is_auth['auth_delete']	) ?	"<a	href=\"modcp.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;mode=delete&amp;sid=" .	$userdata['session_id']	. '"><img src="' . $images['topic_mod_delete'] . '" alt="'	. $lang['Delete_topic']	. '" title="' .	$lang['Delete_topic'] .	'" /></a>&nbsp;' : "";

	$topic_mod .= "<a href=\"modcp.$phpEx?"	. POST_TOPIC_URL . "=$topic_id&amp;mode=move&amp;sid=" . $userdata['session_id']	. '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '"	title="' . $lang['Move_topic'] . '"></a>&nbsp;';

	$topic_mod .= (	$forum_topic_data['topic_status'] == TOPIC_UNLOCKED	) ?	"<a	href=\"modcp.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;mode=lock&amp;sid=" . $userdata['session_id'] . '"><img src="'	. $images['topic_mod_lock'] .	'" alt="' .	$lang['Lock_topic']	. '" title="' .	$lang['Lock_topic']	. '"></a>&nbsp;' : "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL	. "=$topic_id&amp;mode=unlock&amp;sid="	. $userdata['session_id']	. '"><img src="' . $images['topic_mod_unlock'] . '"	alt="' . $lang['Unlock_topic'] . '"	title="' . $lang['Unlock_topic'] . '"></a>&nbsp;';

	$topic_mod .= "<a href=\"modcp.$phpEx?"	. POST_TOPIC_URL . "=$topic_id&amp;mode=split&amp;sid="	. $userdata['session_id']	. '"><img src="' . $images['topic_mod_split'] .	'" alt="' .	$lang['Split_topic'] . '" title="' . $lang['Split_topic']	. '"  ></a>&nbsp;';

	// MOD Modcp Extansion BEGIN
	$normal_button = "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL	. "=$topic_id&amp;mode=normalise&amp;sid=" . $userdata['session_id']	. '"><img src="' . $images['topic_mod_normal'] . '"	alt="' . $lang['Normal_topic'] . '"	title="' . $lang['Normal_topic'] . '" /></a>&nbsp;';
	$sticky_button = ( $is_auth['auth_sticky'] ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;mode=sticky&amp;sid=" .	$userdata['session_id']	. '"><img src="' . $images['topic_mod_sticky'] . '" alt="'	. $lang['Sticky_topic']	. '" title="' .	$lang['Sticky_topic'] .	'" /></a>&nbsp;' : "";
	$announce_button = ( $is_auth['auth_announce'] ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;mode=announce&amp;sid="	. $userdata['session_id'] .	'"><img	src="' . $images['topic_mod_announce'] .	'" alt="' .	$lang['Announce_topic']	. '" title="' .	$lang['Announce_topic']	. '" /></a>&nbsp;' : "";
	switch(	$forum_topic_data['topic_type']	)
	{
		case POST_NORMAL:
			$topic_mod .= $sticky_button . $announce_button;
			break;
		case POST_STICKY:
			$topic_mod .= $announce_button . $normal_button;
			break;
		case POST_ANNOUNCE:
			$topic_mod .= $sticky_button . $normal_button;
			break;
	}
	// MOD Modcp Extansion END


}

//
// Topic watch information
//
$s_watching_topic =	'';
$s_watching_topic_img =	'';
if ( $can_watch_topic )
{
	if ( $is_watching_topic	)
	{
		$s_watching_topic =	"<a	href=\"viewtopic.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Stop_watching_topic'] . '</a>';
		$s_watching_topic_img =	( isset($images['topic_un_watch']) ) ? '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start") . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_topic'] . '" title="' . $lang['Stop_watching_topic'] . '"  ></a>' :	'';
	}
	else
	{
		$s_watching_topic =	"<a	href=\"viewtopic.$phpEx?" .	POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Start_watching_topic'] . '</a>';
		$s_watching_topic_img = ( isset($images['topic_watch']) ) ? '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start"). '"><img src="' . $images['topic_watch'] . '" alt="' . $lang['Start_watching_topic'] . '" title="' . $lang['Start_watching_topic'] . '"></a>' : '';
	}
}

//
// Add the email topic to friend
//
$s_email_topic = '';
$s_email_topic_img = '';
if ( $userdata['session_logged_in'] )
{
	//$action = ( isset($post_id) ) ? POST_POST_URL . "=$post_id" : POST_TOPIC_URL . "=$topic_id&start=$start";
	$s_email_topic = '<a href="' . append_sid("email_topic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&start=$start") . '">' . $lang['Email_topic'] . '</a>';
	$s_email_topic_img = ( isset($images['ns_email']) ) ? '<a href="' . append_sid("email_topic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&start=$start") . '"><img src="' . $images['ns_email'] . '" alt="' . $lang['Email_topic'] . '" title="' . $lang['Email_topic'] . '"></a>' : '';
}

// Start add - Who viewed a topic MOD
$s_topic_view = '';
$s_topic_view_img = '';
if ( $userdata['session_logged_in'] )
{
	$s_topic_view = '<a href="' . append_sid("topic_view_users.$phpEx?".POST_TOPIC_URL."=$topic_id") . '">' . $lang['Topic_view_users'] . '</a>';
	$s_topic_view_img = ( isset($images['ns_view']) ) ? '<a href="' . append_sid("topic_view_users.$phpEx?" . POST_TOPIC_URL . "=$topic_id&start=$start") . '"><img src="' . $images['ns_view'] . '" alt="' . $lang['Topic_view_users'] . '" title="' . $lang['Topic_view_users'] . '"></a>' : '';
}
// End add - Who viewed a topic MOD

// Download Topic
$s_topic_download = '<a href="' . append_sid("viewtopic.$phpEx?download=-1&amp;".POST_TOPIC_URL."=$topic_id") . '">' . $lang['Download_topic'] . '</a>';
$s_topic_download_img = ( isset($images['ns_download']) ) ? '<a href="' . append_sid("viewtopic.$phpEx?download=-1&amp;" . POST_TOPIC_URL . "=$topic_id&start=$start") . '"><img src="' . $images['ns_download'] . '" alt="' . $lang['Download_topic'] . '" title="' . $lang['Download_topic'] . '"></a>' : '';

//
// If we've	got	a hightlight set pass it on	to pagination,
// I get annoyed when I	lose my	highlight after	the	first page.
//
//$pagination	= (	$highlight != '' ) ? generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight", $total_replies, $board_config['posts_per_page'], $start) : generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL	. "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order",	$total_replies,	$board_config['posts_per_page'], $start);

if(isset($HTTP_GET_VARS['printertopic']))
{
	$pagination_printertopic = "printertopic=1&amp;";
}
if($highlight != '')
{
	$pagination_highlight = "highlight=$highlight&amp;";
}

$pagination_ppp = $board_config['posts_per_page'];
if(isset($finish))
{
	$pagination_ppp = ($finish < 0)? -$finish: ($finish - $start);
	$pagination_finish_rel = "finish_rel=". -$pagination_ppp. "&amp";
}

$pagination = generate_pagination("viewtopic.$phpEx?". $pagination_printertopic . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;". $pagination_highlight . $pagination_finish_rel, $total_replies, $pagination_ppp, $start);
if($pagination != '' && isset($pagination_printertopic))
{
	$pagination .= " &nbsp;<a href=\"viewtopic.$phpEx?". $pagination_printertopic. POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;". $pagination_highlight. "start=0&amp;finish_rel=-10000\" title=\"" . $lang['printertopic_cancel_pagination_desc'] . "\">:|&nbsp;|:</a>";
}

$topic_description = ( !empty($topic_desc) ) ? $bbcode_parse->smilies_pass($topic_desc) : '';

//
// Send	vars to	template
//
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'START_REL' => ($start + 1),
	'FINISH_REL' => (isset($HTTP_GET_VARS['finish_rel'])? intval($HTTP_GET_VARS['finish_rel']) : ($board_config['posts_per_page'] - $start)),

	'FORUM_NAME' =>	$forum_name,
	'TOPIC_ID' => $topic_id,
	'TOPIC_TITLE' => $topic_title,
	'TOPIC_DESCRIPTION' => $topic_description,
	'PAGINATION' =>	$pagination,
	//'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor(	$start / intval($board_config['posts_per_page']) ) + 1 ), ceil( $total_replies / intval($board_config['posts_per_page']) )),
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pagination_ppp ) + 1 ), ceil( $total_replies / $pagination_ppp )),

	'POST_IMG' => $post_img,
	'REPLY_IMG'	=> $reply_img,
	'REPLY_IMG_MINI' => $reply_img_mini,

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE'	=> $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' =>	$lang['Post_subject'],
	'L_VIEW_NEXT_TOPIC'	=> $lang['View_next_topic'],
	'L_VIEW_PREVIOUS_TOPIC'	=> $lang['View_previous_topic'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' =>	$reply_alt,
	'L_BACK_TO_TOP'	=> $lang['Back_to_top'],
	'L_DISPLAY_POSTS' => $lang['Display_posts'],
	'L_LOCK_TOPIC' => $lang['Lock_topic'],
	'L_UNLOCK_TOPIC' =>	$lang['Unlock_topic'],
	'L_MOVE_TOPIC' => $lang['Move_topic'],
	'L_SPLIT_TOPIC'	=> $lang['Split_topic'],
	'L_DELETE_TOPIC' =>	$lang['Delete_topic'],
	'L_GOTO_PAGE' => $lang['Goto_page'],

	'S_TOPIC_LINK' => POST_TOPIC_URL,
	'S_SELECT_POST_DAYS' =>	$select_post_days,
	'S_SELECT_POST_ORDER' => $select_post_order,
	'S_POST_DAYS_ACTION' =>	append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL	. '=' .	$topic_id .	"&amp;start=$start"),
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN'	=> $topic_mod,

	'S_WATCH_TOPIC'	=> $s_watching_topic,
	'S_WATCH_TOPIC_IMG'	=> $s_watching_topic_img,
	'S_EMAIL_TOPIC' => $s_email_topic,
	'S_EMAIL_TOPIC_IMG'	=> $s_email_topic_img,
	'S_DOWNLOAD_TOPIC' => $s_topic_download,
	'S_DOWNLOAD_TOPIC_IMG'	=> $s_topic_download_img,
	'S_VIEWED_TOPIC' => $s_topic_view,
	'S_VIEWED_TOPIC_IMG' => $s_topic_view_img,

	// Nav-slices
	'NS_PAGE_BOTTOM_IMG' => $images['ns_icon_down'],
	'L_PAGE_BOTTOM' => $lang['Goto_bottom'],
	'NS_PAGE_TOP_IMG' => $images['ns_icon_up'],
	'L_PAGE_TOP' => $lang['Goto_top'],
	'VIEW_PREVIOUS_TOPIC_IMG' => $images['topic_previous'],
	'VIEW_NEXT_TOPIC_IMG' => $images['topic_next'],
	'PRINTER_TOPIC_IMG' => $images['ns_print'],
	'L_PRINTER_TOPIC' => $lang['printertopic_button'],
	'U_PRINTER_TOPIC' => $printer_topic_url,
	// Nav-slices

	'U_VIEW_TOPIC' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL .	"=$topic_id&amp;start=$start&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight"),
	'U_VIEW_FORUM' => $view_forum_url,
	'U_VIEW_OLDER_TOPIC' =>	$view_prev_topic_url,
	'U_VIEW_NEWER_TOPIC' =>	$view_next_topic_url,
	'U_POST_NEW_TOPIC' => $new_topic_url,
	'U_POST_REPLY_TOPIC' =>	$reply_topic_url)
);

//
// Does	this topic contain a poll?
//
if ( !empty($forum_topic_data['topic_vote']) )
{
	$s_hidden_fields = '';

	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start,	vd.vote_length,	vr.vote_option_id, vr.vote_option_text,	vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE	. "	vr
		WHERE vd.topic_id =	$topic_id
			AND	vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain vote data for this	topic",	'',	__LINE__, __FILE__,	$sql);
	}

	if ( $vote_info	= $db->sql_fetchrowset($result)	)
	{
		$db->sql_freeresult($result);
		$vote_options =	count($vote_info);
		$vote_id = $vote_info[0]['vote_id'];
		$vote_title	= $vote_info[0]['vote_text'];

//
// MOD:	Allow Guest	Voting
//
		if ($userdata['user_id'] ==	ANONYMOUS)
		{
			$guest_sql = " AND vote_user_ip	= '$user_ip'";
		}
		else
		{
			$guest_sql = '';
		}
//
// MOD:	-END-
//


		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE .	"
			WHERE vote_id =	$vote_id
				AND	vote_user_id = " . intval($userdata['user_id'])	. $guest_sql;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain user vote data	for	this topic", '', __LINE__, __FILE__, $sql);
		}

		$user_voted	= (	$db->sql_numrows($result) )	? TRUE : 0;
		$db->sql_freeresult($result);

		if ( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
		{
			$view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] :	$HTTP_POST_VARS['vote']	) == 'viewresult' )	? TRUE : 0;
		}
		else
		{
			$view_result = 0;
		}

		$poll_expired =	( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() )	? TRUE : 0 ) : 0;

		if ( $user_voted ||	$view_result ||	$poll_expired || !$is_auth['auth_vote']	|| $forum_topic_data['topic_status'] == TOPIC_LOCKED )
		{
/*			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_result.tpl')
			);
*/
			$vote_results_sum =	0;

			for($i = 0;	$i < $vote_options;	$i++)
			{
				$vote_results_sum += $vote_info[$i]['vote_result'];
			}

			$vote_graphic =	0;
			$vote_graphic_max =	count($images['voting_graphic']);

			for($i = 0;	$i < $vote_options;	$i++)
			{
				$vote_percent =	( $vote_results_sum	> 0	) ?	$vote_info[$i]['vote_result'] /	$vote_results_sum :	0;
				$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

				$vote_graphic_img =	$images['voting_graphic'][$vote_graphic];
				$vote_graphic =	($vote_graphic < $vote_graphic_max - 1)	? $vote_graphic	+ 1	: 0;

				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option",	array(
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
					'POLL_OPTION_RESULT' =>	$vote_info[$i]['vote_result'],
					'POLL_OPTION_PERCENT' => sprintf("%.1d%%", ($vote_percent *	100)),

					'POLL_OPTION_IMG' => $vote_graphic_img,
					'POLL_OPTION_IMG_WIDTH'	=> $vote_graphic_length)
				);
			}

			$template->assign_vars(array(
				'L_TOTAL_VOTES'	=> $lang['Total_votes'],
				'POLL_RESULT' => true,
				'TOTAL_VOTES' => $vote_results_sum)
			);

		}
		else
		{
/*			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_ballot.tpl')
			);
*/
			for($i = 0;	$i < $vote_options; $i++)
			{
				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option",	array(
					'POLL_OPTION_ID' =>	$vote_info[$i]['vote_option_id'],
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
				);
			}

			$template->assign_vars(array(
				'L_SUBMIT_VOTE'	=> $lang['Submit_vote'],
				'L_VIEW_RESULTS' =>	$lang['View_results'],

				'U_VIEW_RESULTS' =>	append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL	. "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"))
			);

			$s_hidden_fields = '<input type="hidden" name="topic_id" value="' .	$topic_id .	'" /><input	type="hidden" name="mode" value="vote" />';
		}

		if ( count($orig_word) )
		{
			$vote_title	= preg_replace($orig_word, $replacement_word, $vote_title);
		}

		$s_hidden_fields .=	'<input	type="hidden" name="sid" value="' .	$userdata['session_id']	. '" />';

		$template->assign_vars(array(
			'POLL_QUESTION'	=> $vote_title,

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'POLL_BALLOT' => true,
			'S_POLL_ACTION'	=> append_sid("posting.$phpEx?mode=vote&amp;" .	POST_TOPIC_URL . "=$topic_id"))
		);
	}
}

if ( (intval($forum_topic_data['topic_attachment']) == 1) || (!intval($attach_config['disable_mod'])) || (( ($is_auth['auth_download']) && ($is_auth['auth_view']))))
{
	$post_id_array = array();
	
	for ($i = 0; $i < $total_posts; $i++)
	{
	    if ($postrow[$i]['post_attachment'] == 1)
	    {
	        $post_id_array[] = $postrow[$i]['post_id'];
	    }
	}
	
	if (count($post_id_array) > 0)
	{
		$rows = get_attachments_from_post($post_id_array);
		$num_rows = count($rows);
		
		if ($num_rows > 0)
		{
			@reset($attachments);
			
			for ($i = 0; $i < $num_rows; $i++)
			{
			    $attachments['_' . $rows[$i]['post_id']][] = $rows[$i];
			}
						
			init_complete_extensions_data();
			
			$template->assign_vars(array(
			    'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
			    'L_KILOBYTE' => $lang['KB'])
			);
		}
	}
}


//
// Update the topic	view counter
//
if (!($postrow[0]['user_id'] ==	$userdata['user_id']))
{
	$sql = "UPDATE " . TOPICS_TABLE	. "
		SET	topic_views	= topic_views +	1
		WHERE topic_id = $topic_id";
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not update topic views.",	'',	__LINE__, __FILE__,	$sql);
	}
}

//
// Okay, let's do the loop,	yeah come on baby let's	do the loop
// and it goes like	this ...
//
for($i = 0;	$i < $total_posts; $i++)
{

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
	$author_panel =	'';
	$postrow[$i]['user_friend']		= $buddys[ $postrow[$i]['user_id'] ]['buddy_friend'];
	$postrow[$i]['user_visible']	= $buddys[ $postrow[$i]['user_id'] ]['buddy_visible'];
	$postrow[$i]['user_my_friend']	= $buddys[ $postrow[$i]['user_id'] ]['buddy_my_friend'];
	$postrow[$i]['user_my_ignore']	= $buddys[ $postrow[$i]['user_id'] ]['buddy_my_ignore'];
	$postrow[$i]['user_online']		= (	$postrow[$i]['user_session_time'] >= (time()-300) );
	$postrow[$i]['user_pm']	= 1;

	// sig
	if (!$userdata['user_viewsig'] || !$postrow[$i]['user_allow_sig'])
	{
		$postrow[$i]['user_sig'] = '';
	}

	// get the panels
	$author_panel	= pcp_output_panel('PHPBB.viewtopic.left', $postrow[$i]);
	$buttons_panel	= pcp_output_panel('PHPBB.viewtopic.buttons', $postrow[$i]);
	$ignore_panel	= pcp_output_panel('PHPBB.viewtopic.left.ignore', $postrow[$i]);
	$ignore_buttons	= pcp_output_panel('PHPBB.viewtopic.buttons.ignore', $postrow[$i]);
//-- fin mod : profile cp --------------------------------------------------------------------------

	$poster_id = $postrow[$i]['user_id'];
	$poster	= (	$poster_id == ANONYMOUS	) ?	$lang['Guest'] : $postrow[$i]['username'];

	$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//-- No	sure if	I got this part	of the mod correct,	if there are problem they might	be here!
/*
	$poster_posts =	( $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Posts'] .	': ' . $postrow[$i]['user_posts'] :	'';

	$poster_from = ( $postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': '	. $postrow[$i]['user_from']	: '';

	$poster_joined = ( $postrow[$i]['user_id'] != ANONYMOUS	) ?	$lang['Joined']	. ': ' . create_date($lang['DATE_FORMAT'], $postrow[$i]['user_regdate'],	$board_config['board_timezone']) : '';

	$poster_avatar = '';
	if ( $postrow[$i]['user_avatar_type'] && $poster_id	!= ANONYMOUS &&	$postrow[$i]['user_allowavatar'] )
	{
		switch(	$postrow[$i]['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$poster_avatar = ( $board_config['allow_avatar_upload']	) ?	'<img src="' . $board_config['avatar_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt=""  >' : '';
				break;
			case USER_AVATAR_REMOTE:
				$poster_avatar = ( $board_config['allow_avatar_remote']	) ?	'<img src="' . $postrow[$i]['user_avatar']	. '" alt=""	 >'	: '';
				break;
			case USER_AVATAR_GALLERY:
				$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="'	. $board_config['avatar_gallery_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt=""  >' : '';
				break;
		}
	}
*/
//-- fin mod : profile cp --------------------------------------------------------------------------


	//
	// Define the little post icon
	//
if ( $userdata['session_logged_in']	&& $postrow[$i]['post_time'] > $userdata['user_lastvisit'] && $postrow[$i]['post_time'] >	$topic_last_read )
	{
		$mini_post_img = $images['icon_minipost_new'];
		$mini_post_alt = $lang['New_post'];
	}
	else
	{
		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];
	}

	$mini_post_url = append_sid("viewtopic.$phpEx?"	. POST_POST_URL	. '=' .	$postrow[$i]['post_id']) . '#' . $postrow[$i]['post_id'];

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
//-- No	sure if	I got this part	of the mod correct,	if there are problem they might	be here!
/*
	//
	// Generate	ranks, set them	to empty string	initially.
	//
	$poster_rank = '';
	$rank_image	= '';
	if ( $postrow[$i]['user_id'] ==	ANONYMOUS )
	{
	}
	else if	( $postrow[$i]['user_rank']	)
	{
		for($j = 0;	$j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special']	)
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image	= (	$ranksrow[$j]['rank_image']	) ?	'<img src="' . $ranksrow[$j]['rank_image'] . '"	alt="' . $poster_rank .	'" title="'	. $poster_rank . '"	 ><br />' :	'';
			}
		}
	}
	else
	{
		for($j = 0;	$j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_posts']	>= $ranksrow[$j]['rank_min'] &&	!$ranksrow[$j]['rank_special'] )
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image	= (	$ranksrow[$j]['rank_image']	) ?	'<img src="' . $ranksrow[$j]['rank_image'] . '"	alt="' . $poster_rank .	'" title="'	. $poster_rank . '"	 ><br />' :	'';
			}
		}
	}

	//
	// Handle anon users posting with usernames
	//
	if ( $poster_id	== ANONYMOUS &&	$postrow[$i]['post_username'] != ''	)
	{
		$poster	= $postrow[$i]['post_username'];
		$poster_rank = $lang['Guest'];
	}

	$temp_url =	'';

	if ( $poster_id	!= ANONYMOUS )
	{
		$temp_url =	append_sid("profile.$phpEx?mode=viewprofile&amp;" .	POST_USERS_URL . "=$poster_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="'	. $images['icon_profile'] .	'" alt="' .	$lang['Read_profile'] .	'" title="'	. $lang['Read_profile']	. '"></a>';
		$profile = '<a href="' . $temp_url . '">' .	$lang['Read_profile'] .	'</a>';

		$temp_url =	append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL	. "=$poster_id");
		$pm_img	= '<a href="' .	$temp_url .	'"><img	src="' . $images['icon_pm']	. '" alt="'	. $lang['Send_private_message'] .	'" title="'	. $lang['Send_private_message']	. '"></a>';
		$pm	= '<a href="' .	$temp_url .	'">' . $lang['Send_private_message'] . '</a>';

		if ( !empty($postrow[$i]['user_viewemail'])	|| $is_auth['auth_mod']	)
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'='	. $poster_id) :	'mailto:' .	$postrow[$i]['user_email'];

			$email_img = '<a href="' . $email_uri .	'"><img	src="' . $images['icon_email'] . '"	alt="' . $lang['Send_email']	. '" title="' .	$lang['Send_email']	. '"></a>';
			$email = '<a href="' . $email_uri .	'">' . $lang['Send_email'] . '</a>';
		}
		else
		{
			$email_img = '';
			$email = '';
		}

		$www_img = ( $postrow[$i]['user_website'] )	? '<a href="' .	$postrow[$i]['user_website'] . '" target="_userwww"><img src="' .	$images['icon_www']	. '" alt="'	. $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '"></a>' :	'';
		$www = ( $postrow[$i]['user_website'] )	? '<a href="' .	$postrow[$i]['user_website'] . '" target="_userwww">'	. $lang['Visit_website'] . '</a>' :	'';

		if ( !empty($postrow[$i]['user_icq']) )
		{
			$icq_status_img	= '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq']	. '#pager"><img	src="http://web.icq.com/whitepages/online?icq='	. $postrow[$i]['user_icq'] . '&img=5" width="18" height="18"></a>';
			$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to='	. $postrow[$i]['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ']	. '" title="' .	$lang['ICQ'] . '"></a>';
			$icq =	'<a	href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq']	. '">' . $lang['ICQ'] . '</a>';
		}
		else
		{
			$icq_status_img	= '';
			$icq_img = '';
			$icq = '';
		}

		$aim_img = ( $postrow[$i]['user_aim'] )	? '<a href="aim:goim?screenname=' .	$postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="'	. $images['icon_aim'] .	'" alt="' .	$lang['AIM'] . '" title="' . $lang['AIM']	. '"></a>' : '';
		$aim = ( $postrow[$i]['user_aim'] )	? '<a href="aim:goim?screenname=' .	$postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?">' .	$lang['AIM'] . '</a>' :	'';

		$temp_url =	append_sid("profile.$phpEx?mode=viewprofile&amp;" .	POST_USERS_URL . "=$poster_id");
		$msn_img = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="'	. $images['icon_msnm'] . '" alt="'	. $lang['MSNM']	. '" title="' .	$lang['MSNM'] .	'"></a>' : '';
		$msn = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '">' .	$lang['MSNM'] .	'</a>' : '';

		$yim_img = ( $postrow[$i]['user_yim'] )	? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='	. $postrow[$i]['user_yim'] . '&amp;.src=pg"><img src="' .	$images['icon_yim']	. '" alt="'	. $lang['YIM'] . '"	title="' . $lang['YIM']	. '"></a>' : '';
		$yim = ( $postrow[$i]['user_yim'] )	? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='	. $postrow[$i]['user_yim'] . '&amp;.src=pg">'	. $lang['YIM'] . '</a>'	: '';
	}
	else
	{
		$profile_img = '';
		$profile = '';
		$pm_img	= '';
		$pm	= '';
		$email_img = '';
		$email = '';
		$www_img = '';
		$www = '';
		$icq_status_img	= '';
		$icq_img = '';
		$icq = '';
		$aim_img = '';
		$aim = '';
		$msn_img = '';
		$msn = '';
		$yim_img = '';
		$yim = '';
	}
*/
//-- fin mod : profile cp --------------------------------------------------------------------------

    $temp_url = append_sid("posting.$phpEx?mode=quote&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
    $quote_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '"></a>';
    $quote = '<a href="' . $temp_url . '">' . $lang['Reply_with_quote'] . '</a>';

    $temp_url = append_sid("search.$phpEx?search_author=" . urlencode($postrow[$i]['username']) . "&amp;showresults=posts");
    $search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . $lang['Search_user_posts'] . '" title="' . $lang['Search_user_posts'] . '"  ></a>';
    $search = '<a href="' . $temp_url . '">' . $lang['Search_user_posts'] . '</a>';

    if ( ( $userdata['user_id'] == $poster_id && $is_auth['auth_edit'] ) || $is_auth['auth_mod'] )
    {
        $temp_url = append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
        $edit_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '"></a>';
        $edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
    }
    else
    {
        $edit_img = '';
        $edit = '';
    }

    if ( $is_auth['auth_mod'] )
    {
        $temp_url = "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_TOPIC_URL . "=" . $topic_id . "&amp;sid=" . $userdata['session_id'];
        $ip_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '"></a>';
        $ip = '<a href="' . $temp_url . '">' . $lang['View_IP'] . '</a>';

        $temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
        $delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '"></a>';
        $delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
    }
    else
    {
        $ip_img = '';
        $ip = '';

        if ( $userdata['user_id'] == $poster_id && $is_auth['auth_delete'] && $forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id'] )
        {
            $temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
            $delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '"></a>';
            $delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
        }
        else
        {
            $delpost_img = '';
            $delpost = '';
        }
    }
	if($poster_id != ANONYMOUS && $postrow[$i]['user_level'] != ADMIN)
	{
		$current_user = str_replace("'","\'", $postrow[$i]['username']);
		if ($is_auth['auth_greencard'])
		{
			$g_card_img = ' <input type="image" name="unban" value="unban" onClick="return confirm(\''.sprintf($lang['Green_card_warning'], $current_user) . '\')" src="' . $images['icon_g_card'] . '" alt="' . $lang['Give_G_card'] . '" >';
		}
		else
		{
			$g_card_img = '';
		}
		$user_warnings = $postrow[$i]['user_warnings'];
		$card_img = ($user_warnings) ? '<img src="'.(( $user_warnings < $board_config['max_user_bancard']) ? $images['icon_y_card'] . '" alt="'. sprintf($lang['Warnings'], $user_warnings) .'">' : $images['icon_r_card'] . '" alt="'. $lang['Banned'] .'">') : '';
		if ($user_warnings < $board_config['max_user_bancard'] && $is_auth['auth_ban'] )
		{
			$y_card_img = ' <input type="image" name="warn" value="warn" onClick="return confirm(\''.sprintf($lang['Yellow_card_warning'],$current_user).'\')" src="'. $images['icon_y_card'] . '" alt="' . sprintf($lang['Give_Y_card'],$user_warnings+1) . '" >';
			$r_card_img = ' <input type="image" name="ban" value="ban"  onClick="return confirm(\''.sprintf($lang['Red_card_warning'],$current_user).'\')" src="'. $images['icon_r_card'] . '" alt="' . $lang['Give_R_card'] . '" >';
		}
		else
		{
			$y_card_img = '';
			$r_card_img = '';
		}
	}
	else
	{
		$card_img = '';
		$g_card_img = '';
		$y_card_img = '';
		$r_card_img = '';
	}

	if ($is_auth['auth_bluecard'])
	{
		if ($is_auth['auth_mod'])
		{
			$b_card_img = (($postrow[$i]['post_bluecard'])) ? ' <input type="image" name="report_reset" value="report_reset" onClick="return confirm(\''.$lang['Clear_blue_card_warning'].'\')" src="'. $images['icon_bhot_card'] . '" alt="'. sprintf($lang['Clear_b_card'],$postrow[$i]['post_bluecard']) . '">':' <input type="image" name="report" value="report" onClick="return confirm(\''.$lang['Blue_card_warning'].'\')" src="'. $images['icon_b_card'] . '" alt="'. $lang['Give_b_card'] . '" >';
		}
		else
		{
			$b_card_img = ' <input type="image" name="report" value="report" onClick="return confirm(\''.$lang['Blue_card_warning'].'\')" src="'. $images['icon_b_card'] . '" alt="'. $lang['Give_b_card'] . '" >';

		}
	}
	else
	{
		$b_card_img = '';
	}

	// parse hidden filds if cards visible
	$card_hidden = ($g_card_img || $r_card_img || $y_card_img || $b_card_img) ? '<input type="hidden" name="post_id" value="'. $postrow[$i]['post_id'].'">' :'';
    $post_subject = ( $postrow[$i]['post_subject'] != '' ) ? $postrow[$i]['post_subject'] : '';

    $message = $postrow[$i]['post_text'];
    $bbcode_uid = $postrow[$i]['bbcode_uid'];

    if ( $postrow[$i]['user_retrosig'] || $board_config['post_retrosig'] )
    {
        $postrow[$i]['enable_sig'] = true;
    }

    $user_sig = ( $postrow[$i]['enable_sig'] && $postrow[$i]['user_sig'] != '' && $board_config['allow_sig'] ) ? $postrow[$i]['user_sig'] : '';
    $user_sig_bbcode_uid = $postrow[$i]['user_sig_bbcode_uid'];

    //
    // Note! The order used for parsing the message _is_ important, moving things around could break any
    // output
    //

    //
    // If the board has HTML off but the post has HTML
    // on then we process it, else leave it alone
    //
    if ( !$board_config['allow_html'] )
    {
        if ( $user_sig != '' && $userdata['user_allowhtml'] )
        {
            $user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
        }

        if ( $postrow[$i]['enable_html'] )
        {
            $message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
        }
    }

    //
    // Parse message and/or sig for BBCode if reqd
    //
    if ( $board_config['allow_bbcode'] )
    {
        if ( $user_sig != '' && $user_sig_bbcode_uid != '' )
        {
            $user_sig = ( $board_config['allow_bbcode'] ) ? $bbcode_parse->bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
        }

        if ( $bbcode_uid != '' )
        {
            $message = ( $board_config['allow_bbcode'] ) ? $bbcode_parse->bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
        }
    }

    if ( $user_sig != '' )
    {
        $user_sig = $bbcode_parse->make_clickable($user_sig);
    }
    $message = $bbcode_parse->make_clickable($message);

    //
    // Parse smilies
    //
    if ( $board_config['allow_smilies'] )
    {
        if ( $postrow[$i]['user_allowsmile'] && $user_sig != '' )
        {
            $user_sig = $bbcode_parse->smilies_pass($user_sig);
        }

        if ( $postrow[$i]['enable_smilies'] )
        {
            $message = $bbcode_parse->smilies_pass($message);
        }
    }

    //
    // Highlight active words (primarily for search)
    //
    if ($highlight_match)
    {
        // This was shamelessly 'borrowed' from volker at multiartstudio dot de
        // via php.net's annotated manual
        $message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $message . '<'), 1, -1));
    }

    //
    // Replace naughty words
    //
    if (count($orig_word))
    {
        $post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

        if ($user_sig != '')
        {
            $user_sig = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
        }

        $message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
    }

    //
    // Replace newlines (we use this rather than nl2br because
    // till recently it wasn't XHTML compliant)
    //
    if ( $user_sig != '' )
    {
        $user_sig = '_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
    }

    $message = str_replace("\n", "\n<br />\n", $message);

    $message = $bbcode_parse->acronym_pass( $message );
    $message = $bbcode_parse->smart_pass( $message );

    //
    // Editing information
    //
    if ( $postrow[$i]['post_edit_count'] )
    {
        $l_edit_time_total = ( $postrow[$i]['post_edit_count'] == 1 ) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

        $l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $poster, create_date($board_config['default_dateformat'], $postrow[$i]['post_edit_time'], $board_config['board_timezone']), $postrow[$i]['post_edit_count']);
    }
    else
    {
        $l_edited_by = '';
    }


    //
    // Again this will be handled by the templating
    // code at some point
    //
    $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

    $s_post_download = '<a href="' . append_sid("viewtopic.$phpEx?download=".$postrow[$i]['post_id']."&amp;".POST_TOPIC_URL."=$topic_id") . '">' . $lang['Download_post'] . '</a>';
	$s_post_download_img = ( isset($images['icon_download']) ) ? '<a href="' . append_sid("viewtopic.$phpEx?download=".$postrow[$i]['post_id']."&amp;" . POST_TOPIC_URL . "=$topic_id") . '"><img src="' . $images['icon_download'] . '" alt="' . $lang['Download_post'] . '" title="' . $lang['Download_post'] . '"></a>' : '';


    $num_attachments = count($attachments['_' . $postrow[$i]['post_id']]);

	if ( $num_attachments > 0 )
	{
		$s_has_attachment = TRUE;
	}

	$template->assign_block_vars('postrow',	array(

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
		'POST_ID' => $postrow[$i]['post_id'],
		'AUTHOR_PANEL'	=> $postrow[$i]['user_my_ignore'] ?	$ignore_panel :	$author_panel,
		'BUTTONS_PANEL'	=> $buttons_panel,
		'IGNORE_IMG'	=> $ignore_buttons,
//-- fin mod : profile cp --------------------------------------------------------------------------

		'ROW_COLOR'	=> '#' . $row_color,
		'ROW_CLASS'	=> $row_class,

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
/*
		'POSTER_NAME' => $poster,
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' =>	$rank_image,
		'POSTER_JOINED'	=> $poster_joined,
		'POSTER_POSTS' => $poster_posts,
		'POSTER_FROM' => $poster_from,
		'POSTER_AVATAR'	=> $poster_avatar,
*/
//-- fin mod : profile cp --------------------------------------------------------------------------

		'POSTER_NAME' => $poster,
		'POST_NUMBER' => ($i + $start + 1),
		'POST_DATE'	=> $post_date,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message,
		'SIGNATURE'	=> $user_sig,
		'EDITED_MESSAGE' =>	$l_edited_by,
		'MINI_POST_IMG'	=> $mini_post_img,
		'S_DOWNLOAD_POST' => $s_post_download,
		'S_DOWNLOAD_POST_IMG'	=> $s_post_download_img,

//-- mod : profile cp ------------------------------------------------------------------------------
//-- delete
/*
		'PROFILE_IMG' => $profile_img,
		'PROFILE' => $profile,
		'SEARCH_IMG' =>	$search_img,
		'SEARCH' =>	$search,
		'PM_IMG' =>	$pm_img,
		'PM' =>	$pm,
		'EMAIL_IMG'	=> $email_img,
		'EMAIL'	=> $email,
		'WWW_IMG' => $www_img,
		'WWW' => $www,
		'ICQ_STATUS_IMG' =>	$icq_status_img,
		'ICQ_IMG' => $icq_img,
		'ICQ' => $icq,
		'AIM_IMG' => $aim_img,
		'AIM' => $aim,
		'MSN_IMG' => $msn_img,
		'MSN' => $msn,
		'YIM_IMG' => $yim_img,
		'YIM' => $yim,
*/
//-- fin mod : profile cp --------------------------------------------------------------------------

        'EDIT_IMG' => $edit_img,
        'EDIT' => $edit,
        'QUOTE_IMG' => $quote_img,
        'QUOTE' => $quote,
        'IP_IMG' => $ip_img,
        'IP' => $ip,
        'DELETE_IMG' => $delpost_img,
        'DELETE' => $delpost,
		'USER_WARNINGS' => $user_warnings,
		'CARD_IMG' => $card_img,
		'CARD_HIDDEN_FIELDS' => $card_hidden,
		'CARD_EXTRA_SPACE' => ($r_card_img || $y_card_img || $g_card_img || $b_card_img) ? ' ' : '',

        'L_MINI_POST_ALT' => $mini_post_alt,

        'U_MINI_POST' => $mini_post_url,
		'U_G_CARD' => $g_card_img,
		'U_Y_CARD' => $y_card_img,
		'U_R_CARD' => $r_card_img,
		'U_B_CARD' => $b_card_img,
		'S_CARD' => append_sid("card.".$phpEx),
        'U_POST_ID' => $postrow[$i]['post_id'],
		'S_HAS_ATTACHMENT' => $s_has_attachment)
	);

	if ( $s_has_attachment )
	{
		display_attachments($postrow[$i]['post_id'], $num_attachments);
	}

	$cm_viewtopic->post_vars($postrow[$i], $userdata, $forum_id);

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
	if ($postrow[$i]['user_my_ignore'])
	{
		$template->assign_block_vars('postrow.switch_buddy_ignore',	array());
	}
	else
	{
		$template->assign_block_vars('postrow.switch_no_buddy_ignore', array());
	}
//-- fin mod : profile cp --------------------------------------------------------------------------
}

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
$template->assign_vars(array(
	'L_IGNORE_CHOOSEN' => $lang['Ignore_choosed'],
	)
);
//-- fin mod : profile cp --------------------------------------------------------------------------

// Start Add - Quick Reply Mod
if ( ((!$is_auth['auth_reply']) || (isset($HTTP_GET_VARS['printertopic'])) || ($forum_topic_data['forum_status'] == FORUM_LOCKED) || ($forum_topic_data['topic_status'] == TOPIC_LOCKED)) && ($userdata['user_level'] != ADMIN) )
{
	$quick_reply_form = '';

	$template->assign_vars(array(
		'QUICK_REPLY_FORM' => $quick_reply_form)
	);

	$template->assign_var_from_handle('', 'quick_reply_body');
}
else
{
	$notify_user = (( $userdata['session_logged_in'] ) ? $userdata['user_notify'] : 0) ? '1' : '';
	$attach_sig = (( $userdata['session_logged_in'] ) ? $userdata['user_attachsig'] : 0) ? '1' : '';
	$quick_reply_form = '
		<input type="hidden" name="attach_sig" value="' . $attach_sig . '" />
		<input type="hidden" name="mode" value="reply" />
		<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />
		<input type="hidden" name="t" value="' . $topic_id . '" />
		<input type="hidden" name="notify" value="' . $notify_user . '" />';

	$template->assign_vars(array(
		'S_QUICK_REPLY_ACTION' => append_sid("posting.$phpEx"),

		'L_QUICK_REPLY' => $lang['Quick_Reply'],
		'L_USERNAME' => $lang['Username'],
		'L_SUBMIT' => $lang['Submit'],
		'L_PREVIEW' => $lang['Preview'],
		'L_EMPTY_MESSAGE' => $lang['Empty_message'],

		'QUICK_REPLY_FORM' => $quick_reply_form)
	);

	$template->assign_var_from_handle('QUICK_REPLY_BODY', 'quick_reply_body');
}
// End Add - Quick Reply Mod

ratings_view_topic();

$template->pparse('body');

if(isset($HTTP_GET_VARS['printertopic']))
{
	$gen_simple_header = 1;
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
