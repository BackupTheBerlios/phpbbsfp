<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_recent_topics.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_recent_topics.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------


if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

if(!function_exists('imp_recent_topics_block_func'))
{
	function imp_recent_topics_block_func()
	{
		global $template, $portal_config, $userdata, $board_config, $db, $phpEx;

		$sql = "SELECT * FROM ". FORUMS_TABLE . " ORDER BY forum_id";
		if (!$result1 = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}
		$forum_data = array();
		while( $row1 = $db->sql_fetchrow($result1) )
		{
			$forum_data[] = $row1;
		}

		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_data);

		if( empty($portal_config['md_except_forum_id']))
		{
			$except_forum_id = '\'start\'';
		}
		else
		{
			$except_forum_id = $portal_config['md_except_forum_id'];
		}

		for ($i = 0; $i < count($forum_data); $i++)
		{
			if ((!$is_auth_ary[$forum_data[$i]['forum_id']]['auth_read']) or (!$is_auth_ary[$forum_data[$i]['forum_id']]['auth_view']))
			{
				if ($except_forum_id == '\'start\'')
				{
					$except_forum_id = $forum_data[$i]['forum_id'];
				}
				else
				{
					$except_forum_id .= ',' . $forum_data[$i]['forum_id'];
				}
			}
		}

		if ($portal_config['md_approve_mod_installed']){
			$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username
				FROM " . TOPICS_TABLE . " AS t LEFT OUTER JOIN " . APPROVE_POSTS_TABLE . " AS a ON (t.topic_first_post_id = a.post_id), " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
				WHERE t.forum_id NOT IN (" . $except_forum_id . ")
					AND t.topic_status <> 2
					AND p.post_id = t.topic_last_post_id
					AND p.poster_id = u.user_id
					AND a.post_id is NULL
				ORDER BY p.post_id DESC
				LIMIT " . $portal_config['md_num_recent_topics'];
		}else{
			$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username
				FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
				WHERE t.forum_id NOT IN (" . $except_forum_id . ")
					AND t.topic_status <> 2
					AND p.post_id = t.topic_last_post_id
					AND p.poster_id = u.user_id
				ORDER BY p.post_id DESC
				LIMIT " . $portal_config['md_num_recent_topics'];
		}

		if (!$result1 = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query recent topics information', '', __LINE__, __FILE__, $sql);
		}
		$number_recent_topics = $db->sql_numrows($result1);
		$recent_topic_row = array();
		while ($row1 = $db->sql_fetchrow($result1))
		{
			$recent_topic_row[] = $row1;
		}

		if($portal_config['md_recent_topics_style']){
			$style_row = 'scroll';
		}else
		{
			$style_row = 'static';
		}

		$template->assign_block_vars($style_row,"");

		for ($i = 0; $i < $number_recent_topics; $i++)
		{
			$template->assign_block_vars($style_row . '.recent_topic_row', array(
				'U_TITLE' => append_sid("viewtopic.$phpEx?" . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#' .$recent_topic_row[$i]['post_id'],
				'L_TITLE' => $recent_topic_row[$i]['topic_title'],
				'U_POSTER' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $recent_topic_row[$i]['user_id']),
				'S_POSTER' => $recent_topic_row[$i]['username'],
				'S_POSTTIME' => create_date($board_config['default_dateformat'], $recent_topic_row[$i]['post_time'], $board_config['board_timezone'])
				)
			);
		}
	}
}

imp_recent_topics_block_func();
?>