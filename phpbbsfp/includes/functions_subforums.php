<?php

// $Id: functions_subforums.php,v 1.8 2004/09/02 04:29:36 dmaj007 Exp $

function display_forums($root_data = '', $display_moderators = TRUE)
{
	global $board_config, $db, $template, $user, $auth, $phpEx, $forum_moderators, $phpbb_root_path, $user;

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

	if ($board_config['load_db_lastread'] && $user->data['user_id'] != ANONYMOUS)
	{
		switch (SQL_LAYER)
		{
			case 'oracle':
				break;

			default:
				$sql_from = '(' . FORUMS_TABLE . ' f LEFT JOIN ' . FORUMS_TRACK_TABLE . ' ft ON (ft.user_id = ' . $user->data['user_id'] . ' AND ft.forum_id = f.forum_id))';
				break;
		}
		$lastread_select = ', ft.mark_time ';
	}
	else
	{
		$sql_from = "(( " . FORUMS_TABLE . " f
				LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
				LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
				ORDER BY f.cat_id, f.forum_order";
		$lastread_select = ', p.post_time, p.post_username, u.username, u.user_id';
		$sql_lastread = '';

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
		if ($mark_read == 'forums' && $user->data['user_id'] != ANONYMOUS)
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

		if ($mark_time_forum < $row['forum_last_post_time'] && $user->data['user_id'] != ANONYMOUS)
		{
			$forum_unread[$parent_id] = true;
		}
	}
	$db->sql_freeresult($result);

	// Handle marking posts
	if ($mark_read == 'forums')
	{
		markread('mark', $forum_id_ary);

		$redirect = (!empty($_SERVER['REQUEST_URI'])) ? preg_replace('#^(.*?)&(amp;)?mark=.*$#', '\1', htmlspecialchars($_SERVER['REQUEST_URI'])) : append_sid("index.$phpEx");
		meta_refresh(3, $redirect);

		$message = (strstr($redirect, 'viewforum')) ? 'RETURN_FORUM' : 'RETURN_INDEX';
		$message = $user->lang['FORUMS_MARKED'] . '<br /><br />' . sprintf($user->lang[$message], '<a href="' . $redirect . '">', '</a> ');
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
						$links[] = '<a href="' . append_sid('viewforum.' . $phpEx . '?f=' . $subforum_id) . '">' . $subforum_name . '</a>';
					}
					$subforums_list = implode(', ', $links);

					$l_subforums = (count($subforums[$forum_id]) == 1) ? $user->lang['SUBFORUM'] . ': ' : $user->lang['SUBFORUMS'] . ': ';
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
			$last_post_time = $user->format_date($row['post_time']);

			$last_poster = ($row['username'] != '') ? $row['username'] : $user->lang['GUEST'];
			$last_poster_url = ($row['user_id'] == ANONYMOUS) ? '' : append_sid("profile.$phpEx?mode=viewprofile&amp;u="  . $row['user_id']);

			$last_post_url = append_sid("viewtopic.$phpEx?f=" . $row['forum_id_last_post'] . '&amp;p=' . $row['forum_last_post_id'] . '#' . $row['forum_last_post_id']);
		}
		else
		{
			$last_post_time = $last_poster = $last_poster_url = $last_post_url = '';
		}


		// Output moderator listing ... if applicable
		$l_moderator = $moderators_list = '';
		if ($display_moderators && !empty($forum_moderators[$forum_id]))
		{
			$l_moderator = (count($forum_moderators[$forum_id]) == 1) ? $user->lang['MODERATOR'] : $user->lang['MODERATORS'];
			$moderators_list = implode(', ', $forum_moderators[$forum_id]);
		}

		$l_post_click_count = ($row['forum_type'] == FORUM_LINK) ? 'CLICKS' : 'POSTS';
		$post_click_count = ($row['forum_type'] != FORUM_LINK || $row['forum_flags'] & 1) ? $row['forum_posts'] : '';

		$template->assign_block_vars('forumrow', array(
			'S_IS_CAT'			=> false, 
			'S_IS_LINK'			=> ($row['forum_type'] != FORUM_LINK) ? false : true, 

			'LAST_POST_IMG'		=> $user->img('icon_latest_reply', 'VIEW_LATEST_POST'), 

			'FORUM_ID'			=> $row['forum_id'], 
			'FORUM_FOLDER_IMG'	=> ($row['forum_image']) ? '<img src="' . $phpbb_root_path . $row['forum_image'] . '" alt="' . $folder_alt . '" border="0" />' : $user->img($folder_image, $folder_alt),
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
			'U_VIEWFORUM'		=> ($row['forum_type'] != FORUM_LINK || $row['forum_flags'] & 1) ? append_sid("viewforum.$phpEx?f=" . $row['forum_id']) : $row['forum_link'])
		);
	}

	$template->assign_vars(array(
		'U_MARK_FORUMS'		=> append_sid("viewforum.$phpEx?f=" . $root_data['forum_id'] . '&amp;mark=forums'), 
		'L_LAST_POST' => $user->lang['Last_Post'],
		'S_HAS_SUBFORUM'	=>	($visible_forums) ? true : false,

		'L_SUBFORUM'		=>	($visible_forums == 1) ? $user->lang['SUBFORUM'] : $user->lang['SUBFORUMS'])
	);

	return $active_forum_ary;
}

// Obtain list of moderators of each forum
function get_moderators(&$forum_moderators, $forum_id = false)
{
	global $board_config, $template, $db, $phpEx;

	// Have we disabled the display of moderators? If so, then return
	// from whence we came ... 
	if (empty($board_config['load_moderators']))
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
		$forum_moderators[$row['forum_id']][] = (!empty($row['user_id'])) ? '<a href="' . append_sid('memberlist.' . $phpEx . '?mode=viewprofile&amp;u=' . $row['user_id']) . '">' . $row['username'] . '</a>' : '<a href="' . append_sid('groupcp.' . $phpEx . '?g=' . $row['group_id']) . '">' . $row['groupname'] . '</a>';
	}
	$db->sql_freeresult($result);

	return;
}

// User authorisation levels output
function gen_forum_auth_level($mode, &$forum_id)
{
	global $template, $auth, $user;

	$rules = array('post', 'reply', 'edit', 'delete', 'attach');

	foreach ($rules as $rule)
	{
		$template->assign_block_vars('rules', array(
			'RULE' => ($auth->acl_get('f_' . $rule, intval($forum_id))) ? $user->lang['RULES_' . strtoupper($rule) . '_CAN'] : $user->lang['RULES_' . strtoupper($rule) . '_CANNOT'])
		);
	}

	return;
}

// Create forum navigation links for given forum, create parent
// list if currently null, assign basic forum info to template
function generate_forum_nav(&$forum_data)
{
	global $db, $user, $template, $phpEx, $phpbb_root_path;

	// Get forum parents
	$forum_parents = get_forum_parents($forum_data);

	// Build navigation links
	foreach ($forum_parents as $parent_forum_id => $parent_data)
	{
		list($parent_name, $parent_type) = array_values($parent_data);

		$template->assign_block_vars('navlinks', array(
			'S_IS_CAT'		=> ($parent_type == FORUM_CAT) ? true : false,
			'S_IS_LINK'		=> ($parent_type == FORUM_LINK) ? true : false,
			'S_IS_POST'		=> ($parent_type == FORUM_POST) ? true : false,
			'FORUM_NAME'	=> $parent_name,
			'FORUM_ID'		=> $parent_forum_id,
			'U_VIEW_FORUM'	=> append_sid("{$phpbb_root_path}viewforum.$phpEx?f=$parent_forum_id"))
		);
	}

	$template->assign_block_vars('navlinks', array(
		'S_IS_CAT'		=> ($forum_data['forum_type'] == FORUM_CAT) ? true : false,
		'S_IS_LINK'		=> ($forum_data['forum_type'] == FORUM_LINK) ? true : false,
		'S_IS_POST'		=> ($forum_data['forum_type'] == FORUM_POST) ? true : false,
		'FORUM_NAME'	=> $forum_data['forum_name'],
		'FORUM_ID'		=> $forum_data['forum_id'],
		'U_VIEW_FORUM'	=> append_sid("{$phpbb_root_path}viewforum.$phpEx?f=" . $forum_data['forum_id']))
	);

	$template->assign_vars(array(
		'FORUM_ID' 		=> $forum_data['forum_id'],
		'FORUM_NAME'	=> $forum_data['forum_name'],
		'FORUM_DESC'	=> strip_tags($forum_data['forum_desc']))
	);

	return;
}

// Returns forum parents as an array. Get them from forum_data if available, or update the database otherwise
function get_forum_parents(&$forum_data)
{
	global $db;

	$forum_parents = array();

	if ($forum_data['parent_id'] > 0)
	{
		if ($forum_data['forum_parents'] == '')
		{
			$sql = 'SELECT forum_id, forum_name, forum_type
				FROM ' . FORUMS_TABLE . '
				WHERE left_id < ' . $forum_data['left_id'] . '
					AND right_id > ' . $forum_data['right_id'] . '
				ORDER BY left_id ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$forum_parents[$row['forum_id']] = array($row['forum_name'], (int) $row['forum_type']);
			}
			$db->sql_freeresult($result);

			$forum_data['forum_parents'] = serialize($forum_parents);

			$sql = 'UPDATE ' . FORUMS_TABLE . "
				SET forum_parents = '" . $db->sql_escape($forum_data['forum_parents']) . "'
				WHERE parent_id = " . $forum_data['parent_id'];
			$db->sql_query($sql);
		}
		else
		{
			$forum_parents = unserialize($forum_data['forum_parents']);
		}
	}

	return $forum_parents;
}

// Create forum rules for given forum 
function generate_forum_rules(&$forum_data)
{
	if (!$forum_data['forum_rules'] && !$forum_data['forum_rules_link'])
	{
		return;
	}

	global $template, $phpbb_root_path, $phpEx;

	if ($forum_data['forum_rules'])
	{
		$text_flags = explode(':', $forum_data['forum_rules_flags']);
	}

	$template->assign_vars(array(
		'S_FORUM_RULES'	=> true,
		'U_FORUM_RULES'	=> $forum_data['forum_rules_link'],
		'FORUM_RULES'	=> (!$forum_data['forum_rules_link']) ? parse_text_display($forum_data['forum_rules'], $forum_data['forum_rules_flags']) : '')
	);
}

// prepare text to be displayed/previewed...
// This function is here to save memory (this function is used by viewforum/viewtopic/posting... and to include another huge file is pure memory waste)
function parse_text_display($text, $text_rules)
{
	global $bbcode, $user;

	$text_flags = explode(':', $text_rules);

	$allow_bbcode = (int) $text_flags[0] & 1;
	$allow_smilies = (int) $text_flags[0] & 2;
	$allow_magic_url = (int) $text_flags[0] & 4;

	$bbcode_uid = trim($text_flags[1]);
	$bbcode_bitfield = (int) $text_flags[2];

	// Really, really process bbcode only if we have something to process...
	if (!$bbcode && $allow_bbcode && strpos($text, '[') !== false)
	{
		global $phpbb_root_path, $phpEx;

		include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);
	//	$bbcode = new bbcode();
	}

	// Second parse bbcode here
	if ($allow_bbcode)
	{
	//	$bbcode->bbcode_second_pass($text, $bbcode_uid, $bbcode_bitfield);
	}

	// If we allow users to disable display of emoticons we'll need an appropriate 
	// check and preg_replace here
	if ($allow_smilies)
	{
	//	$text = smilie_text($text, !$allow_smilies);
	}

	// Replace naughty words such as farty pants
	$text = str_replace("\n", '<br />', ($text));

	return $text;
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

function markread($mode, $forum_id = 0, $topic_id = 0, $marktime = false)
{
	global $board_config, $db, $user;
	
	if ($user->data['user_id'] == ANONYMOUS)
	{
		return;
	}

	if (!is_array($forum_id))
	{
		$forum_id = array($forum_id);
	}

	// Default tracking type
	$type = TRACK_NORMAL;
	$current_time = ($marktime) ? $marktime : time();
	$topic_id = (int) $topic_id;

	switch ($mode)
	{
		case 'mark':
			if ($board_config['load_db_lastread'])
			{
				$sql = 'SELECT forum_id 
					FROM ' . FORUMS_TRACK_TABLE . ' 
					WHERE user_id = ' . $user->data['user_id'] . '
						AND forum_id IN (' . implode(', ', array_map('intval', $forum_id)) . ')';
				$result = $db->sql_query($sql);
				
				$sql_update = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$sql_update[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);

				if (sizeof($sql_update))
				{
					$sql = 'UPDATE ' . FORUMS_TRACK_TABLE . "
						SET mark_time = $current_time 
						WHERE user_id = " . $user->data['user_id'] . '
							AND forum_id IN (' . implode(', ', $sql_update) . ')';
					$db->sql_query($sql);
				}

				if ($sql_insert = array_diff($forum_id, $sql_update))
				{
					foreach ($sql_insert as $forum_id)
					{
						$sql = '';
						switch (SQL_LAYER)
						{
							case 'mysql':
							case 'mysql4':
								$sql .= (($sql != '') ? ', ' : '') . '(' . $user->data['user_id'] . ", $forum_id, $current_time)";
								$sql = 'VALUES ' . $sql;
								break;

							case 'mssql':
							case 'sqlite':
								$sql .= (($sql != '') ? ' UNION ALL ' : '') . ' SELECT ' . $user->data['user_id'] . ", $forum_id, $current_time";
								break;

							default:
								$sql = 'INSERT INTO ' . FORUMS_TRACK_TABLE . ' (user_id, forum_id, mark_time)
									VALUES (' . $user->data['user_id'] . ", $forum_id, $current_time)";
								$db->sql_query($sql);
								$sql = '';
						}

						if ($sql)
						{
							$sql = 'INSERT INTO ' . FORUMS_TRACK_TABLE . " (user_id, forum_id, mark_time) $sql";
							$db->sql_query($sql);
						}
					}
				}
				unset($sql_update);
				unset($sql_insert);
			}
			else
			{
				$tracking = (isset($_COOKIE[$board_config['cookie_name'] . '_track'])) ? unserialize(stripslashes($_COOKIE[$board_config['cookie_name'] . '_track'])) : array();

				foreach ($forum_id as $f_id)
				{
					unset($tracking[$f_id]);
					$tracking[$f_id][0] = base_convert($current_time - $board_config['board_startdate'], 10, 36);
				}

				//$user->set_cookie('track', serialize($tracking), time() + 31536000);
				unset($tracking);
			}
			break;

		case 'post':
			// Mark a topic as read and mark it as a topic where the user has made a post.
			$type = TRACK_POSTED;

		case 'topic':
			$forum_id =	(int) $forum_id[0];
	
			// Mark a topic as read
			if ($board_config['load_db_lastread'] || ($board_config['load_db_track'] && $type == TRACK_POSTED))
			{
				$sql = 'UPDATE ' . TOPICS_TRACK_TABLE . "
					SET mark_type = $type, mark_time = $current_time
					WHERE topic_id = $topic_id
						AND user_id = " . $user->data['user_id'] . " 
						AND mark_time < $current_time";
				if (!$db->sql_query($sql) || !$db->sql_affectedrows())
				{
					$db->sql_return_on_error(true);

					$sql = 'INSERT INTO ' . TOPICS_TRACK_TABLE . ' (user_id, topic_id, mark_type, mark_time)
						VALUES (' . $user->data['user_id'] . ", $topic_id, $type, $current_time)";
					$db->sql_query($sql);

					$db->sql_return_on_error(false);
				}
			}

			if (!$board_config['load_db_lastread'])
			{
				$tracking = array();
				if (isset($_COOKIE[$board_config['cookie_name'] . '_track']))
				{
					$tracking = unserialize(stripslashes($_COOKIE[$board_config['cookie_name'] . '_track']));

					// If the cookie grows larger than 2000 characters we will remove
					// the smallest value
					if (strlen($_COOKIE[$board_config['cookie_name'] . '_track']) > 2000)
					{
						foreach ($tracking as $f => $t_ary)
						{
							if (!isset($m_value) || min($t_ary) < $m_value)
							{
								$m_value = min($t_ary);
								$m_tkey = array_search($m_value, $t_ary);
								$m_fkey = $f;
							}
						}
						unset($tracking[$m_fkey][$m_tkey]);
					}
				}

				if (base_convert($tracking[$forum_id][0], 36, 10) < $current_time)
				{
					$tracking[$forum_id][base_convert($topic_id, 10, 36)] = base_convert($current_time - $board_config['board_startdate'], 10, 36);

					$user->set_cookie('track', serialize($tracking), time() + 31536000);
				}
				unset($tracking);
			}
			break;
	}
}
function on_page($num_items, $per_page, $start)
{
	global $template, $user;

	$on_page = floor($start / $per_page) + 1;

	$template->assign_var('ON_PAGE', $on_page);

	return sprintf($user->lang['PAGE_OF'], $on_page, max(ceil($num_items / $per_page), 1));
}
function gen_sort_selects(&$limit_days, &$sort_by_text, &$sort_days, &$sort_key, &$sort_dir, &$s_limit_days, &$s_sort_key, &$s_sort_dir, &$u_sort_param)
{
	global $user;

	$sort_dir_text = array('a' => $user->lang['ASCENDING'], 'd' => $user->lang['DESCENDING']);

	$s_limit_days = '<select name="st">';
	foreach ($limit_days as $day => $text)
	{
		$selected = ($sort_days == $day) ? ' selected="selected"' : '';
		$s_limit_days .= '<option value="' . $day . '"' . $selected . '>' . $text . '</option>';
	}
	$s_limit_days .= '</select>';

	$s_sort_key = '<select name="sk">';
	foreach ($sort_by_text as $key => $text)
	{
		$selected = ($sort_key == $key) ? ' selected="selected"' : '';
		$s_sort_key .= '<option value="' . $key . '"' . $selected . '>' . $text . '</option>';
	}
	$s_sort_key .= '</select>';

	$s_sort_dir = '<select name="sd">';
	foreach ($sort_dir_text as $key => $value)
	{
		$selected = ($sort_dir == $key) ? ' selected="selected"' : '';
		$s_sort_dir .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
	}
	$s_sort_dir .= '</select>';

	$u_sort_param = "st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir";

	return;
}
?>