<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_announces.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_announces.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team
//		     : � 2001, 2003 The phpBB Group
//           : � 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

include_once($phpbb_root_path . 'includes/functions_topics_list.'. $phpEx);

function get_announces_title($start, $duration)
{
	global $lang, $board_config;

	$display_announce_dates = intval($board_config['announcement_date_display']);

	if ( empty($start) || ($duration < 0) || ($display_announce_dates == 0) ) return $res;

	$end = mktime(0,0,0, date('m', $start), date('d', $start)+$duration, date('Y', $start));
	$res = sprintf($lang['Announces_from_to'], date( $lang['DATE_FORMAT'], $start), date( $lang['DATE_FORMAT'], $end));

	return $res;
}

function announces_prune($force_prune=false)
{
	global $db, $board_config;

	// do we prune the announces ?
	$today_time = time();
	$today = mktime(0,0,0, date('m', $today_time), date('d', $today_time)+1, date('Y', $today_time))-1;
	$do_prune = false;

	// last prune date
	if (intval($board_config['announcement_last_prune']) < $today || $force_prune)
	{
		$do_prune = true;

		if ( $sql = set_config ('announcement_last_prune', $today, TRUE) )
		{
			message_die(GENERAL_ERROR, 'Could not update key announcement_last_prune in the config table', '', __LINE__, __FILE__, $sql);
		}
	}

	// is the prune function activated ?
	$default_duration = isset($board_config['announcement_duration']) ? intval($board_config['announcement_duration']) : 7;
	if ($default_duration <= 0) $do_prune = false;

	// process fix and prune
	if ($do_prune)
	{
		// fix announces duration
		$default_duration = isset($board_config['announcement_duration']) ? intval($board_config['announcement_duration']) : 7;
		$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_announce_duration = $default_duration
				WHERE topic_announce_duration = 0
					AND (topic_type=" . POST_ANNOUNCE . " OR topic_type=" . POST_GLOBAL_ANNOUNCE .")";
		if( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR, 'Could not update topic duration list', '', __LINE__, __FILE__, $sql);

		// prune announces
		$prune_strategy = isset($board_config['announcement_prune_strategy']) ? intval($board_config['announcement_prune_strategy']) : POST_NORMAL;
		$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_type = $prune_strategy
				WHERE (topic_announce_duration > -1)
					AND ( (topic_time + topic_announce_duration * 86400) <= $today )
					AND (topic_type=" . POST_ANNOUNCE . " OR topic_type=" . POST_GLOBAL_ANNOUNCE .")";
		if( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR, 'Could not update topic type to prune announcements', '', __LINE__, __FILE__, $sql);
	}
}

function announces_from_forums($cur='Root', $force_prune=false)
{
	global $db, $template, $board_config, $userdata, $phpEx, $lang, $images, $HTTP_COOKIE_VARS;
	global $tree;

	// fix and prune announces
	announces_prune($force_prune);

	// get the start point
	$type = POST_CAT_URL;
	$id = 0;
	if ($cur != 'Root')
	{
		$type = substr($cur, 0, 1);
		$id = intval(substr($cur, 1));
		if ($id == 0) $type = POST_CAT_URL;
	}

	// configuration
	$announce_index = isset($board_config['announcement_display']) ? intval($board_config['announcement_display']) : true;
	$announce_forum = isset($board_config['announcement_display_forum']) ? intval($board_config['announcement_display_forum']) : true;
	$announce = ( (($type == POST_CAT_URL) && $announce_index) || (($type == POST_FORUM_URL) && $announce_forum) );
	if (!$announce) return false;

	// read the forums authorized
	$auth_forum_ids = array();
	$tree_forum_ids = array();
	// get the current item selected
	$cid = $type . $id;
	// get the list of authorized forums except the current one
	for ($i=0; $i < count($tree['id']); $i++)
	{
		$fid = $tree['type'][$i] . $tree['id'][$i];
		if ( ($fid != $cid) && ($tree['type'][$i] == POST_FORUM_URL) && $tree['auth'][$fid]['auth_read'] )
		{
			$auth_forum_ids[] = $tree['id'][$i];
		}
	}
	// no forums authed, return an error
	if (empty($auth_forum_ids)) return false;
	// get auth key
	$keys = array();
	$keys = get_auth_keys($cur, true, -1, -1, 'auth_read');
	$tree_forum_ids = array();
	for ($i=1; $i < count($keys['id']); $i++)
	{
		$idx = $keys['idx'][$i];
		$fid = $keys['id'][$i];
		if ( ($fid != $cid) && ($tree['type'][$idx] == POST_FORUM_URL) && $tree['auth'][$fid]['auth_read'] )
		{
			$tree_forum_ids[] = $tree['id'][$idx];
		}
	}
	// go to root on this branch
	if (isset($tree['main'][ $tree['keys'][$cur] ]))
	{
		$fid = $tree['main'][ $tree['keys'][$cur] ];
		while ($fid != 'Root')
		{
			$idx = $tree['keys'][$fid];
			if ( ($fid != $cur) && ($tree['type'][$idx] == POST_FORUM_URL) && ($tree['auth'][$fid]['auth_read']) )
			{
				$tree_forum_ids[] = $tree['id'][$idx];
			}
			$fid = isset($tree['main'][$idx]) ? $tree['main'][$idx] : 'Root';
		}
	}

	// select global
	$sql_where = '(t.topic_type=' . POST_GLOBAL_ANNOUNCE . ' AND t.forum_id IN (' . implode(', ', $auth_forum_ids) . '))';

	// select annonces
	if (!empty($tree_forum_ids))
	{
		$sql_where .= ' OR (t.topic_type=' . POST_ANNOUNCE . ' AND t.forum_id IN (' . implode(', ', $tree_forum_ids) . '))';
	}

	// get topics data
	$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username, f.forum_name
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . FORUMS_TABLE . " f
			WHERE ($sql_where)
				AND t.topic_poster = u.user_id
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u2.user_id
				AND f.forum_id = t.forum_id
			ORDER BY t.topic_type DESC, t.topic_last_post_id DESC ";
	if ( !($result = $db->sql_query($sql)) ) message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$row['topic_id'] = POST_TOPIC_URL . $row['topic_id'];
		$topic_rowset[] = $row;
	}
	$db->sql_freeresult($result);
	if (count($topic_rowset) <= 0) return false;

	// send the list
	$footer = '';
	$allow_split_type = (intval($board_config['announcement_split']) == 1);
	$display_nav_tree = (intval($board_config['announcement_forum']) == 1);
	$inbox = false;
	topic_list('BOARD_ANNOUNCES', 'topics_list_box', $topic_rowset, $lang['Board_announcement'], $allow_split_type, $display_nav_tree, $footer, $inbox);
}

?>