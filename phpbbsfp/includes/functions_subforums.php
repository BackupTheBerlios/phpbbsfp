<?php

// $Id: functions_subforums.php,v 1.2 2004/08/31 23:27:48 toonarmy Exp $

function display_forums($root_data = '', $display_moderators = TRUE)
{
	global $board_config, $db, $template, $lang, $auth, $userdata, $phpEx, $SID, $forum_moderators, $phpbb_root_path;

	// Get posted/get info
	$mark_read = request_var('mark', '');

	$forum_id_ary = $active_forum_ary = $forum_rows = $subforums = $forum_moderators = $mark_forums = array();
	$visible_forums = 0;

	if (!$root_data)
	{
		$root_data = array('forum_id' => 0);
		$sql_where = '';
	}
	else
	{
		$sql_where = ' WHERE left_id > ' . $root_data['left_id'] . ' AND left_id < ' . $root_data['right_id'];
	}

	// Display list of active topics for this category?
	$show_active = (isset($root_data['forum_flags']) && $root_data['forum_flags'] & 16) ? true : false;

	if ($board_config['load_db_lastread'] && $userdata['user_id'] != ANONYMOUS)
	{
		switch (SQL_LAYER)
		{
			case 'oracle':
				break;

			default:
				$sql_from = '(' . FORUMS_TABLE . ' f LEFT JOIN ' . FORUMS_TRACK_TABLE . ' ft ON (ft.user_id = ' . $userdata['user_id'] . ' AND ft.forum_id = f.forum_id))';
				break;
		}
		$lastread_select = ', ft.mark_time ';
	}
	else
	{
		$sql_from = FORUMS_TABLE . ' f ';
		$lastread_select = $sql_lastread = '';

		$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_track'])) ? unserialize(stripslashes($_COOKIE[$board_config['cookie_name'] . '_track'])) : array();
	}

	$sql = "SELECT f.* $lastread_select 
		FROM $sql_from 
		$sql_where
		"; //ORDER BY f.left_id";
	$result = $db->sql_query($sql);
print_r ($db->sql_error());
	$branch_root_id = $root_data['forum_id'];
	$forum_ids		= array($root_data['forum_id']);

	while ($row = $db->sql_fetchrow($result))
	{
		if ($mark_read == 'forums' && $userdata['user_id'] != ANONYMOUS)
		{
			//if ($auth->acl_get('f_list', $row['forum_id']))
			//{
				$forum_id_ary[] = $row['forum_id'];
			//}

			continue;
		}

		if (isset($right_id))
		{
			if ($row['left_id'] < $right_id)
			{
				continue;
			}
			unset($right_id);
		}

		if ($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']))
		{
			// Non-postable forum with no subforums: don't display
			continue;
		}

		$forum_id = $row['forum_id'];

		//if (!$auth->acl_get('f_list', $forum_id))
		//{
			// if the user does not have permissions to list this forum, skip everything until next branch
			//$right_id = $row['right_id'];
			//continue;
		//}

		// Display active topics from this forum?
		if ($show_active && $row['forum_type'] == FORUM_POST && $auth->acl_get('f_read', $forum_id) && ($row['forum_flags'] & 16))
		{
			$active_forum_ary['forum_id'][]		= $forum_id;
			$active_forum_ary['enable_icons'][] = $row['enable_icons'];
			$active_forum_ary['forum_topics']	+= $row['forum_topics'];
			$active_forum_ary['forum_posts']	+= $row['forum_posts'];
		}

		if ($row['parent_id'] == $root_data['forum_id'] || $row['parent_id'] == $branch_root_id)
		{
			// Direct child
			$parent_id = $forum_id;
			$forum_rows[$forum_id] = $row;
			$forum_ids[] = $forum_id;

			if (!$row['parent_id'] && $row['forum_type'] == FORUM_CAT && $row['parent_id'] == $root_data['forum_id'])
			{
				$branch_root_id = $forum_id;
			}
			$forum_rows[$parent_id]['forum_id_last_post'] = $row['forum_id'];
		}
		elseif ($row['forum_type'] != FORUM_CAT)
		{
			$subforums[$parent_id]['display'] = ($row['display_on_index']) ? true : false;;
			$subforums[$parent_id]['name'][$forum_id] = $row['forum_name'];

			// Include subforum topic/post counts in parent counts
			$forum_rows[$parent_id]['forum_topics'] += $row['forum_topics'];
			
			// Do not list redirects in LINK Forums as Posts.
			if ($row['forum_type'] != FORUM_LINK)
			{
				$forum_rows[$parent_id]['forum_posts'] += $row['forum_posts'];
			}

			if (isset($forum_rows[$parent_id]) && $row['forum_last_post_time'] > $forum_rows[$parent_id]['forum_last_post_time'])
			{
				$forum_rows[$parent_id]['forum_last_post_id'] = $row['forum_last_post_id'];
				$forum_rows[$parent_id]['forum_last_post_time'] = $row['forum_last_post_time'];
				$forum_rows[$parent_id]['forum_last_poster_id'] = $row['forum_last_poster_id'];
				$forum_rows[$parent_id]['forum_last_poster_name'] = $row['forum_last_poster_name'];
				$forum_rows[$parent_id]['forum_id_last_post'] = $forum_id;
			}
			else
			{
				$forum_rows[$parent_id]['forum_id_last_post'] = $forum_id;
			}
		}

		if (!isset($row['mark_time']))
		{
			$row['mark_time'] = 0;
		}

		$mark_time_forum = ($board_config['load_db_lastread']) ? $row['mark_time'] : ((isset($tracking_topics[$forum_id][0])) ? base_convert($tracking_topics[$forum_id][0], 36, 10) + $board_config['board_startdate'] : 0);

		if ($mark_time_forum < $row['forum_last_post_time'] && $userdata['user_id'] != ANONYMOUS)
		{
			$forum_unread[$parent_id] = true;
		}
	}
	$db->sql_freeresult();

	// Handle marking posts
	if ($mark_read == 'forums')
	{
		markread('mark', $forum_id_ary);

		$redirect = (!empty($_SERVER['REQUEST_URI'])) ? preg_replace('#^(.*?)&(amp;)?mark=.*$#', '\1', htmlspecialchars($_SERVER['REQUEST_URI'])) : "index.$phpEx$SID";
		meta_refresh(3, $redirect);

		$message = (strstr($redirect, 'viewforum')) ? 'RETURN_FORUM' : 'RETURN_INDEX';
		$message = $lang['FORUMS_MARKED'] . '<br /><br />' . sprintf($lang[$message], '<a href="' . $redirect . '">', '</a> ');
		trigger_error($message);
	}

	// Grab moderators ... if necessary
	if ($display_moderators)
	{
		get_moderators($forum_moderators, $forum_ids);
	}

	// Loop through the forums
	$root_id = $root_data['forum_id'];

	foreach ($forum_rows as $row)
	{
		if ($row['parent_id'] == $root_id && !$row['parent_id'])
		{
			if ($row['forum_type'] == FORUM_CAT)
			{
				$hold = $row;
				continue;
			}
			else
			{
				unset($hold);
			}
		}
		else if (!empty($hold))
		{
			$template->assign_block_vars('forumrow', array(
				'S_IS_CAT'			=>	TRUE,
				'FORUM_ID'			=>	$hold['forum_id'],
				'FORUM_NAME'		=>	$hold['forum_name'],
				'FORUM_DESC'		=>	$hold['forum_desc'],
				'U_VIEWFORUM'		=>	append_sid("viewforum.$phpEx?f=" . $hold['forum_id']))
			);
			unset($hold);
		}

		$visible_forums++;
		$forum_id = $row['forum_id'];


		// Generate list of subforums if we need to
		if (isset($subforums[$forum_id]))
		{
			if ($subforums[$forum_id]['display'])
			{
				$alist = array();
				foreach ($subforums[$forum_id]['name'] as $sub_forum_id => $subforum_name)
				{
					if (!empty($subforum_name))
					{
						$alist[$sub_forum_id] = $subforum_name;
					}
				}

				if (sizeof($alist))
				{
					$links = array();
					foreach ($alist as $subforum_id => $subforum_name)
					{
						$links[] = '<a href="viewforum.' . $phpEx . $SID . '&amp;f=' . $subforum_id . '">' . $subforum_name . '</a>';
					}
					$subforums_list = implode(', ', $links);

					$l_subforums = (count($subforums[$forum_id]) == 1) ? $lang['SUBFORUM'] . ': ' : $lang['SUBFORUMS'] . ': ';
				}
			}

			$folder_image = (!empty($forum_unread[$forum_id])) ? 'sub_forum_new' : 'sub_forum';
		}
		else
		{
			switch ($row['forum_type'])
			{
				case FORUM_POST:
					$folder_image = (!empty($forum_unread[$forum_id])) ? 'forum_new' : 'forum';
					break;

				case FORUM_LINK:
					$folder_image = 'forum_link';
					break;
			}

			$subforums_list = '';
			$l_subforums = '';
		}


		// Which folder should we display?
		if ($row['forum_status'] == ITEM_LOCKED)
		{
			$folder_image = 'forum_locked';
			$folder_alt = 'FORUM_LOCKED';
		}
		else
		{
			$folder_alt = (!empty($forum_unread[$forum_id])) ? 'NEW_POSTS' : 'NO_NEW_POSTS';
		}


		// Create last post link information, if appropriate
		if ($row['forum_last_post_id'])
		{
			//$last_post_time = $user->format_date($row['forum_last_post_time']);

			//$last_poster = ($row['forum_last_poster_name'] != '') ? $row['forum_last_poster_name'] : $lang['GUEST'];
			//$last_poster_url = ($row['forum_last_poster_id'] == ANONYMOUS) ? '' : "memberlist.$phpEx$SID&amp;mode=viewprofile&amp;u="  . $row['forum_last_poster_id'];

			//$last_post_url = "viewtopic.$phpEx$SID&amp;f=" . $row['forum_id_last_post'] . '&amp;p=' . $row['forum_last_post_id'] . '#' . $row['forum_last_post_id'];
		}
		else
		{
			$last_post_time = $last_poster = $last_poster_url = $last_post_url = '';
		}


		// Output moderator listing ... if applicable
		$l_moderator = $moderators_list = '';
		if ($display_moderators && !empty($forum_moderators[$forum_id]))
		{
			$l_moderator = (count($forum_moderators[$forum_id]) == 1) ? $lang['MODERATOR'] : $lang['MODERATORS'];
			$moderators_list = implode(', ', $forum_moderators[$forum_id]);
		}

		$l_post_click_count = ($row['forum_type'] == FORUM_LINK) ? 'CLICKS' : 'POSTS';
		$post_click_count = ($row['forum_type'] != FORUM_LINK || $row['forum_flags'] & 1) ? $row['forum_posts'] : '';

		$template->assign_block_vars('forumrow', array(
			'S_IS_CAT'			=> false, 
			'S_IS_LINK'			=> ($row['forum_type'] != FORUM_LINK) ? false : true, 

			'LAST_POST_IMG'		=> '',//$user->img('icon_post_latest', 'VIEW_LATEST_POST'), 

			'FORUM_ID'			=> $row['forum_id'], 
			'FORUM_FOLDER_IMG'	=> '', //($row['forum_image']) ? '<img src="' . $phpbb_root_path . $row['forum_image'] . '" alt="' . $folder_alt . '" border="0" />' : $user->img($folder_image, $folder_alt),
			'FORUM_NAME'		=> $row['forum_name'],
			'FORUM_DESC'		=> $row['forum_desc'], 
			$l_post_click_count	=> $post_click_count,
			'TOPICS'			=> $row['forum_topics'],
			'LAST_POST_TIME'	=> $last_post_time,
			'LAST_POSTER'		=> $last_poster,
			'MODERATORS'		=> $moderators_list,
			'SUBFORUMS'			=> $subforums_list,
			
			'L_SUBFORUM_STR'	=> $l_subforums,
			'L_MODERATOR_STR'	=> $l_moderator,
			'L_FORUM_FOLDER_ALT'=> $folder_alt,
			
			'U_LAST_POSTER'		=> $last_poster_url, 
			'U_LAST_POST'		=> $last_post_url, 
			'U_VIEWFORUM'		=> ($row['forum_type'] != FORUM_LINK || $row['forum_flags'] & 1) ? "viewforum.$phpEx$SID&amp;f=" . $row['forum_id'] : $row['forum_link'])
		);
	}

	$template->assign_vars(array(
		'U_MARK_FORUMS'		=> "viewforum.$phpEx$SID&amp;f=" . $root_data['forum_id'] . '&amp;mark=forums', 

		'S_HAS_SUBFORUM'	=>	($visible_forums) ? true : false,

		'L_SUBFORUM'		=>	($visible_forums == 1) ? $lang['SUBFORUM'] : $lang['SUBFORUMS'])
	);

	return $active_forum_ary;
}

// Obtain list of moderators of each forum
function get_moderators(&$forum_moderators, $forum_id = false)
{
	global $config, $template, $db, $phpEx, $SID;

	// Have we disabled the display of moderators? If so, then return
	// from whence we came ... 
	if (empty($config['load_moderators']))
	{
		return;
	}

	if (!empty($forum_id) && is_array($forum_id))
	{
		$forum_sql = 'AND forum_id IN (' . implode(', ', $forum_id) . ')';
	}
	else
	{
		$forum_sql = ($forum_id) ? 'AND forum_id = ' . $forum_id : '';
	}

	$sql = 'SELECT *
		FROM ' . MODERATOR_TABLE . "
		WHERE display_on_index = 1
			$forum_sql";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$forum_moderators[$row['forum_id']][] = (!empty($row['user_id'])) ? '<a href="memberlist.' . $phpEx . $SID . '&amp;mode=viewprofile&amp;u=' . $row['user_id'] . '">' . $row['username'] . '</a>' : '<a href="groupcp.' . $phpEx . $SID . '&amp;g=' . $row['group_id'] . '">' . $row['groupname'] . '</a>';
	}
	$db->sql_freeresult($result);

	return;
}


//
// Compatibility Funcs
//

function set_var(&$result, $var, $type)
{
	settype($var, $type);
	$result = $var;

	if ($type == 'string')
	{
		$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", '\xFF'), array("\n", "\n", ' '), $result)));
		$result = preg_replace("#\n{3,}#", "\n\n", $result);
		$result = (STRIP) ? stripslashes($result) : $result;
	}
}

function request_var($var_name, $default)
{
	if (!isset($_REQUEST[$var_name]))
	{
		return $default;
	}
	else
	{
		$var = $_REQUEST[$var_name];
		$type = gettype($default);

		if (is_array($var))
		{
			foreach ($var as $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v as $_k => $_v)
					{
						set_var($var[$k][$_k], $_v, $type);
					}
				}
				else
				{
					set_var($var[$k], $v, $type);
				}
			}
		}
		else
		{
			set_var($var, $var, $type);
		}

		return $var;
	}
}

?>