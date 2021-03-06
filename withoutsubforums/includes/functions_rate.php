<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_rate.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_rate.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team
//		     : � 2001, 2003 The phpBB Group
//           : � 2003 		Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( defined('INCLUDE_RATE') )
{
	return;
}

define('INCLUDE_RATE', TRUE);

/*******************************************************************************************
/** Some Constants for Rate use only.
/******************************************************************************************/
define('RATE_AUTH_DENY', 0);
define('RATE_AUTH_ALLOWED', 1);
define('RATE_AUTH_PRIVATE', 2);
define('RATE_AUTH_NONE', 3);

$rating_switched_on = FALSE;
$template->assign_var('L_RATINGS', $lang['Rating']);

/*******************************************************************************************
/** General Functions.
/******************************************************************************************/
	/**
	* @return string
	* @param user_id int
	* @param mode string
	* @desc Returns the username string for $user_id
	*/
	function id_to_value($id, $mode, $make_clickable = FALSE)
	{
		global $db, $lang;

		if (!isset($id))
		{
			$mode = FALSE;
		}

		switch($mode)
		{
			case 'user':
			{
				$sql = 'SELECT username	FROM ' . USERS_TABLE . "
	   		      WHERE user_id = $id";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't query users table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				return ($row['username'] == "") ? $lang['Anonymous'] : $row['username'];
				break;
			}
			case 'forum':
			{
				$sql = 'SELECT forum_name FROM ' . FORUMS_TABLE . "
	   		   WHERE forum_id = $id";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't query forums table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				return $row['forum_name'];
				break;
			}
			case 'topic':
			{
				$sql = 'SELECT topic_title FROM ' . TOPICS_TABLE . "
	   		   WHERE topic_id = $id";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't query topics table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				return $row['topic_title'];
				break;
			}
			case 'topictoforum':
			{
				$sql = 'SELECT forum_id FROM ' . TOPICS_TABLE . "
	   		   WHERE topic_id = $id";
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't query topics table", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				return $row['forum_id'];
				break;
			}
			default:
			{
				return FALSE;
			}
		}
	}
	function get_forum_list()
	{
		global $db, $userdata;
		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

		return $is_auth_ary;
	}
	function make_forum_drop_down_box()
	{
		global $db, $userdata;

		$forums_row = get_forum_list();

		$forum_list = "<select name=\"forum_top\">";
		$forum_list .= "<option value=\"-1\" selected=\"selected\">All</option>";
		for ($i=1; $i < count($forums_row) + 1; $i++)
		{
			if (isset($forums_row[$i]) && $forums_row[$i]['auth_view'])
			{
				$forum_list .= "<option value=\"" . $i . "\">" . strip_tags(id_to_value($i, 'forum')) . "</option>";
			}
		}
		$forum_list .= "</select>";

		return $forum_list;
	}
	function last_rating_info($topic_id)
	{
		global $db, $userdata;

		return 1;
	}
if (!function_exists('nivisec_copyright'))
{
	/**
	* @return void
	* @desc Prints a sytlized line of copyright for module
	*/
	function nivisec_copyright()
	{
		print '<br /><span class="copyright"><center>Ratings Module &copy; 2001-2003 <a href="http://www.nivisec.com" class="copyright">Nivisec.com</a>.</center></span>';
	}
}
/*******************************************************************************************
/** Rating Functions.
/******************************************************************************************/
/**
* @return void
* @param user_id int
* @param topic_id int
* @param rating int
* @param mode string[optional]
* @desc Insert a $rating for $user_id into the ratings database.
*/
function rate_topic($user_id, $topic_id, $rating, $mode = 'rate')
{
	global $db, $user_ip, $board_config, $template, $lang, $phpEx;

	if ($mode == 'rate')
	{
		$sql = 'INSERT INTO ' . RATINGS_TABLE . "
		   (user_id, topic_id, rating, user_ip, rating_time)
		   VALUES ($user_id, $topic_id, $rating, '$user_ip', " . time() . ")";
	}
	elseif ($mode = 'rerate')
	{
		if (!$board_config['allow_rerate'])
		{
			message_die(GENERAL_ERROR, $lang['Rerate_Not_Allowed'], '', __LINE__, __FILE__);
		}
		if ($user_id == ANONYMOUS)
		{
			$sql = 'UPDATE ' . RATINGS_TABLE . "
			   SET rating = $rating, rating_time = " . time() . "
			   WHERE user_id = " . ANONYMOUS . "
			   AND user_ip = '$user_ip'
               AND topic_id = $topic_id";
		}
		else
		{
			$sql = 'UPDATE ' . RATINGS_TABLE . "
			   SET rating = $rating, rating_time = " . time() . "
			   WHERE user_id = $user_id
               AND topic_id = $topic_id";
		}
	}
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$template->assign_var('META', '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '#' . $topic_id . '">');
	message_die(GENERAL_MESSAGE, $lang['Topic_Rated'] . "<br /><br />" . sprintf($lang['Click_return_topic'], "<a href=\"" . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . "#$topic_id\">", "</a>"));
}
function rating_inserted($user_id, $topic_id)
{
	global $db, $user_ip, $board_config;

	if ($user_id == ANONYMOUS)
	{
		if (!$board_config['check_anon_ip_when_rating'])
		{
			return FALSE;
		}
		$sql = 'SELECT rating FROM ' . RATINGS_TABLE . "
		   WHERE user_id = $user_id
	       AND user_ip = '$user_ip'
	       AND topic_id = $topic_id
	       LIMIT 1";
	}
	else
	{
		$sql = 'SELECT rating FROM ' . RATINGS_TABLE . "
		   WHERE user_id = $user_id
	       AND topic_id = $topic_id
	       LIMIT 1";
	}
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	return (isset($row['rating'])) ? TRUE : FALSE;
}
function rating_value($user_id, $topic_id)
{
	global $db;
	$sql = 'SELECT rating FROM ' . RATINGS_TABLE . "
	   WHERE user_id = $user_id
	   AND topic_id = $topic_id
	   LIMIT 1";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	return $row['rating'];
}
/**
* @return array
* @param topic_id int
* @desc Returns a 1-D array of average, min, max, number of rates for $topic_id
*/
function rating_stats($topic_id)
{
	global $db;

	$sql = "SELECT AVG(rating) AS average,
	   MIN(rating) AS minimum,
	   MAX(rating) AS maximum,
	   COUNT(rating) AS number_of_rates
	   FROM " . RATINGS_TABLE . "
	   WHERE topic_id = $topic_id";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	return $row;
}
/**
* @return int
* @param user_id int
* @param topic_id int
* @desc Returns the rating for $user_id on $topic_id
*/
function user_rating($user_id, $topic_id)
{
	global $db, $board_config, $user_ip;

	if ($user_id == ANONYMOUS)
	{
		$sql = 'SELECT rating FROM ' . RATINGS_TABLE . "
		   WHERE user_id = " . ANONYMOUS . "
		   AND user_ip = '$user_ip'
		   AND topic_id = $topic_id";
	}
	else
	{
		$sql = 'SELECT rating FROM ' . RATINGS_TABLE . "
		   WHERE user_id = $user_id
		   AND topic_id = $topic_id";
	}

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	return $row['rating'];
}
/**
* @return int
* @param user_id int
* @param forum_id int
* @param topic_id int
* @desc Rating Auth function.  Returns 0 for NO, 1 for YES, 2 for Private, 3 for None.  Was not changed from original.
*/
function rate_auth($user_id, $forum_id, $topic_id)
{
	global $db, $userdata;

	//Get forum_id info
	$sql = "SELECT auth_rate FROM " . FORUMS_TABLE .
	" WHERE forum_id = " . $forum_id;
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Error getting forum data.", "", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$forum_row = $row['auth_rate'];
	$db->sql_freeresult($result);

	$value = 0;
	//First check topic_id
	//NOT IMPLEMENTED YET
	if ($topic_id != $topic_id) $value = RATE_AUTH_ALLOWED;
	//If topic_id check ok, check if NONE (which is -1)
	else if ($forum_row == -1) $value = RATE_AUTH_NONE;
	//Check if ALL
	else if ($forum_row == AUTH_ALL) $value = RATE_AUTH_ALLOWED;
	//Now check if REG
	else if ($forum_row == AUTH_REG && $user_id != ANONYMOUS) $value = RATE_AUTH_ALLOWED;
	//Now check if PRIVATE
	else if ($forum_row == AUTH_ACL) $value = RATE_AUTH_PRIVATE;
	//Now check if MOD
	else if ($forum_row == AUTH_MOD)
	{
		/* MESSY AUTH_MOD CODE START THAT I COPIED FROM INDEX.PHP SO IT WORKS :) */
		$sql = "SELECT aa.forum_id, g.group_name, g.group_id, g.group_single_user, u.user_id, u.username
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
		WHERE aa.auth_mod = " . TRUE . "
		AND ug.group_id = aa.group_id
		AND g.group_id = aa.group_id
		AND u.user_id = ug.user_id
		ORDER BY aa.forum_id, g.group_id, u.user_id";
		if(!$q_forum_mods = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query forum moderator information", "", __LINE__, __FILE__, $sql);
		}
		$forum_mods_list = $db->sql_fetchrowset($q_forum_mods);

		for($i = 0; $i < count($forum_mods_list); $i++)
		{
			if($forum_mods_list[$i]['group_single_user'] || !$forum_mods_list[$i]['group_id'])
			{
				$forum_mods_single_user[$forum_mods_list[$i]['forum_id']][] = 1;

				$forum_mods_name[$forum_mods_list[$i]['forum_id']][] = $forum_mods_list[$i]['username'];
				$forum_mods_id[$forum_mods_list[$i]['forum_id']][] = $forum_mods_list[$i]['user_id'];
			}
			else
			{
				$forum_mods_single_user[$forum_mods_list[$i]['forum_id']][] = 0;

				$forum_mods_name[$forum_mods_list[$i]['forum_id']][] = $forum_mods_list[$i]['group_name'];
				$forum_mods_id[$forum_mods_list[$i]['forum_id']][] = $forum_mods_list[$i]['group_id'];
			}
		}

		for($mods = 0; $mods < count($forum_mods_name[$forum_id]); $mods++)
		{
			if ($user_id == $forum_mods_id[$forum_id][$mods]) $value = 1;
		}
		$sql = "SELECT user_level
		FROM " . USERS_TABLE .
		" WHERE user_id = " . $user_id;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Error getting user data.", "", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$auth_row = $row['user_level'];
		if ($auth_row == ADMIN) $value = RATE_AUTH_ALLOWED;
		/*MESSY AUTH_MOD CODE END */
	}
	//Now check if ADMIN
	else if ($forum_row == AUTH_ADMIN)
	{
		$sql = "SELECT user_level FROM " . USERS_TABLE .
		" WHERE user_id = " . $user_id;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Error getting user data.", "", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$auth_row = $row['user_level'];
		if ($auth_row == ADMIN) $value = RATE_AUTH_ALLOWED;
	}
	//If all that fails then no access
	else $value = RATE_AUTH_DENY;

	return $value;
}
/**
* @return string
* @desc Returns a comma (,) delemented list of topic_ids that the current user can view.  For use in a SQL query.
*/
function auth_rated_topics()
{
	global $db, $userdata;

	/* Get forum list */
	$sql = "SELECT forum_id, forum_name	FROM " . FORUMS_TABLE . "
	   WHERE auth_rate <> -1 ";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Auth'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$forums_row = $db->sql_fetchrowset($result);

	/* Narrow Down the Forum List */
	$forum_id_sql = "-1";
	for ($i=0; $i < count($forums_row); $i++)
	{
		$is_auth = auth(AUTH_VIEW, $forums_row[$i]['forum_id'], $userdata);
		if ($is_auth['auth_view'])
		{
			$forum_id_sql .= ($forum_id_sql != "") ? ', ' . $forums_row[$i]['forum_id'] : $forums_row[$i]['forum_id'];

		}
	}

	/* Get Our Topics List */
	$sql = "SELECT t.topic_id FROM " . TOPICS_TABLE . " t, " . RATINGS_TABLE . " r
		WHERE t.topic_id = r.topic_id
		AND t.forum_id IN ($forum_id_sql)";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Auth'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$topic_id_sql = "";
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_id_sql .= ($topic_id_sql != "") ? ', ' . $row['topic_id'] : $row['topic_id'];
	}

	return ($topic_id_sql == "") ? '-1' : $topic_id_sql;
}
/**
* @return array
* @param return_limit int [optional]
* @desc Returns the top rated topics viewable by the current user, limit of $return_limit
*/
function top_rated_topics($return_limit = '10', $forum_id = '-1')
{
	global $db, $board_config;
	$auth_topic_list = auth_rated_topics();

	if ($forum_id == -1)
	{
		$sql = "SELECT	AVG(rating) AS average, COUNT(rating) AS rating_number, MIN(rating) AS min, MAX(rating) AS max, topic_id
		   FROM " . RATINGS_TABLE . "
		   WHERE topic_id IN ($auth_topic_list)
		   GROUP BY topic_id DESC
		   HAVING rating_number >= " . $board_config['min_rates_number'] . "
		   ORDER BY average DESC
		   LIMIT $return_limit";
	}
	else
	{
		$sql = "SELECT	AVG(r.rating) AS average, COUNT(r.rating) AS rating_number, MIN(r.rating) AS min, MAX(r.rating) AS max, r.topic_id
		   FROM " . RATINGS_TABLE . " r, " . TOPICS_TABLE . " t
		   WHERE r.topic_id IN ($auth_topic_list)
		   AND r.topic_id = t.topic_id
	       AND t.forum_id = $forum_id
		   GROUP BY r.topic_id DESC
		   HAVING rating_number >= " . $board_config['min_rates_number'] . "
		   ORDER BY average DESC
		   LIMIT $return_limit";
	}
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrowset($result);
	return ($row);
}
/*******************************************************************************************
/** Display Oriented Rating Functions.
/******************************************************************************************/
/**
* @return void
* @param topic_id int
* @desc Parses and displays a page of detailed rating info for $topic_id
*/
function ratings_detailed($topic_id)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang;

	$rank = 0;

	if (!isset($topic_id))
	{
		$topic_id = -1;
	}

	$sql = 'SELECT * FROM ' . RATINGS_TABLE . "
	   WHERE topic_id = $topic_id
	   ORDER BY rating_time";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$template->assign_block_vars("user_rates_row", array(
		"RANK" => ++$rank,
		"CLASS" => (!($rank % 2)) ? $theme['td_class1'] : $theme['td_class2'],
		'USER_RATE' => $row['rating'],
		"USER_MAX_RATE" => $board_config['rating_max'],
		'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $row['user_id']),
		"USER_RATE_DATE" => (create_date($board_config['default_dateformat'], $row['rating_time'], $board_config['board_timezone'])),
		"USERNAME" => id_to_value($row['user_id'], 'user', TRUE)));
	}

	$template->assign_vars(array(
	"DEFAULT_CLASS" => $theme['td_class1'],
	"L_TOPIC_RETURN" => sprintf($lang['Click_return_topic'], "<a href=\"" . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . "#$topic_id\">", "</a>"),
	"L_TITLE" => sprintf($lang['Details_For_Topic'], id_to_value($topic_id, 'topic')),
	"L_USER_RATED" => $lang['User_Rate'],
	"L_USER_MAX_RATE" => $lang['Max_Rate'],
	"L_USER_RATE_DATE" => $lang['Rate_Date'],
	"L_USER_RATE_TIME" => $lang['Rate_Time'],
	"L_RANK" => $lang['Rate_Order'],
	"PAGE_NAME" => $lang['Detailed_Rating'])
	);

	$template->set_filenames(array("body" => "rate_detailed.tpl"));
	$template->pparse("body");
}
/**
* @return void
* @desc Compiles a var of {RATING_INDEX} to be inserted on a template page.
*/
function ratings_index()
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang;

	$rank = 0;
	$top_rated_row = top_rated_topics($board_config['index_rating_return']);
	if ( count($top_rated_row) )
	{
		for ($i=0; $i < count($top_rated_row); $i++)
		{
			$last_rating_info = last_rating_info($top_rated_row[$i]['topic_id']);
			$template->assign_block_vars("ratingrow", array(
			"CLASS" => ( !($rank % 2) ) ? $theme['td_class2'] : $theme['td_class1'],
			"RANK" => ++$rank,
			"URL" => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $top_rated_row[$i]['topic_id']),
			"LAST_RATER" => id_to_value($last_rating_info['user_id'], 'user'),
			'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $last_rating_info['user_id']),
			"LAST_RATER_TIME" => create_date($board_config['default_dateformat'], $last_rating_info['rate_time'], $board_config['board_timezone']),
			"TITLE" => id_to_value($top_rated_row[$i]['topic_id'], 'topic'),
			"FORUM" => id_to_value(id_to_value($top_rated_row[$i]['topic_id'], 'topictoforum'), 'forum'),
			"RATING" => sprintf("%.2f", $top_rated_row[$i]['average']),
			"MIN" => $top_rated_row[$i]['min'],
			"MAX" => $top_rated_row[$i]['max'],
			"L_VIEW_DETAILS" => ($board_config['allow_ext_rating']) ? sprintf($lang['View_Details_2'], append_sid("rate.$phpEx?rate_mode=detailed&amp;topic_id=" . $top_rated_row[$i]['topic_id'])) : "",
			"NUMBER_OF_RATES" => $top_rated_row[$i]['rating_number'])
			);
		}
	}
	else
	{
		$template->assign_block_vars("notopics", array(
		"MESSAGE" => $lang['No_Topics_Rated'])
		);
	}

	$template->assign_vars( array(
	"L_TOP_RATED" => sprintf($lang['Top_Topics'], $board_config['index_rating_return']))
	);
	$template->set_filenames(array('rating_index_body' => 'rating_index_body.tpl'));
	$template->assign_var_from_handle('RATING_INDEX', 'rating_index_body');
}
/**
* @return void
* @desc Compiles a var of {RATING_HEADER} to be inserted on a template page.
*/
function ratings_header()
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang;

	$sql = 'SELECT config_value FROM ' . CONFIG_TABLE . "
		WHERE config_name = 'header_rating_return_limit'";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Config'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$rank = 0;
	$top_rated_row = top_rated_topics($row['config_value']);
	if ( count($top_rated_row) )
	{
		for ($i=0; $i < count($top_rated_row); $i++)
		{
			$last_rating_info = last_rating_info($top_rated_row[$i]['topic_id']);
			$template->assign_block_vars("hratingrow", array(
			"CLASS" => ( !($rank % 2) ) ? $theme['td_class2'] : $theme['td_class1'],
			"RANK" => ++$rank,
			"URL" => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $top_rated_row[$i]['topic_id']),
			"LAST_RATER" => id_to_value($last_rating_info['user_id'], 'user'),
			'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $last_rating_info['user_id']),
			"LAST_RATER_TIME" => create_date($board_config['default_dateformat'], $last_rating_info['rate_time'], $board_config['board_timezone']),
			"TITLE" => id_to_value($top_rated_row[$i]['topic_id'], 'topic'),
			"FORUM" => id_to_value(id_to_value($top_rated_row[$i]['topic_id'], 'topictoforum'), 'forum'),
			"RATING" => sprintf("%.2f", $top_rated_row[$i]['average']),
			"MIN" => $top_rated_row[$i]['min'],
			"MAX" => $top_rated_row[$i]['max'],
			"L_VIEW_DETAILS" => ($board_config['allow_ext_rating']) ? sprintf($lang['View_Details_2'], append_sid("rate.$phpEx?rate_mode=detailed&amp;topic_id=" . $top_rated_row[$i]['topic_id'])) : "",
			"NUMBER_OF_RATES" => $top_rated_row[$i]['rating_number'])
			);
		}
	}
	else
	{
		$template->assign_block_vars("hnotopics", array(
		"MESSAGE" => $lang['No_Topics_Rated'])
		);
	}

	$template->assign_vars( array(
	"L_TOP_RATED" => sprintf($lang['Top_Topics'], $row['config_value']))
	);
	$template->set_filenames(array('rate_header' => 'rate_header.tpl'));
	$template->assign_var_from_handle('RATING_HEADER', 'rate_header');
}
function ratings_large()
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $page_title, $forum_top;

	if (!isset($forum_top))
	{
		$forum_top = -1;
	}

	$top_rated_row = top_rated_topics($board_config['large_rating_return_limit'], $forum_top);

	$rank = 0;
	if (count($top_rated_row))
	{
		for ($i=0; $i < count($top_rated_row); $i++)
		{
			$last_rate_info = last_rating_info($top_rated_row[$i]['topic_id']);

			$template->assign_block_vars("topicrow", array(
			"RANK" => ++$rank,
			"CLASS" => (!($rank % 2)) ? $theme['td_class2'] : $theme['td_class1'],
			"URL" => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $top_rated_row[$i]['topic_id']),
			"LAST_RATER" => id_to_value($last_rate_info['user'], 'user'),
			"LAST_RATER_TIME" => create_date($board_config['default_dateformat'], $last_rate_info['time'], $board_config['board_timezone']),
			"TITLE" => id_to_value($top_rated_row[$i]['topic_id'], 'topic'),
			"FORUM" => id_to_value(id_to_value($top_rated_row[$i]['topic_id'], 'topictoforum'), 'forum'),
			"RATING" => sprintf("%.2f", $top_rated_row[$i]['average']),
			"MIN" => $top_rated_row[$i]['min'],
			"MAX" => $top_rated_row[$i]['max'],
			"L_VIEW_DETAILS" => ($board_config['allow_ext_rating']) ? sprintf($lang['View_Details_2'], append_sid("rate.$phpEx?rate_mode=detailed&amp;topic_id=" . $top_rated_row[$i]['topic_id'])) : "",
			"NUMBER_OF_RATES" => $top_rated_row[$i]['rating_number'])
			);
		}
	}
	else
	{
		$template->assign_block_vars("notopics", array(
		"MESSAGE" => $lang['No_Topics_Rated'])
		);
	}

	$template->assign_vars(array(
	"PAGE_NAME" => $page_title,
	"L_FOR_FORUM" => ( $forum_top != -1 ) ? sprintf($lang['For_Forum'],id_to_value($forum_top, 'forum')) : $lang['All_Forums'],
	"L_LAST_RATED" => $lang['Last_Rated'],
	"L_RATES" => $lang['Number_of_Rates'],
	"L_RATING" => $lang['Rating'],
	"L_MIN" => $lang['Min_Rating'],
	"L_MAX" => $lang['Max_Rate'],
	"S_FORUMS" => make_forum_drop_down_box(),
	"S_MODE_ACTION" => append_sid('rate.'.$phpEx),
	"L_BY_FORUM" => $lang['By_Forum'],
	"L_VIEW" => $lang['Go'])
	);

	$template->set_filenames(array(
	"body" => "rate_main.tpl")
	);
	$template->pparse("body");
}
function ratings_view_topic()
{
	global $userdata, $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $page_title, $forum_id, $topic_id;

	$rath_auth_data = rate_auth($userdata['user_id'], $forum_id, $topic_id);

	if ($rath_auth_data == RATE_AUTH_NONE)
	{
		$template->assign_block_vars("noauth", array(
		"RATE_TOPIC_USER" => '')
		);
	}
	else if ($rath_auth_data != RATE_AUTH_PRVIATE || ($rath_auth_data == RATE_AUTH_PRVIATE && $userdata['user_id'] != ANONYMOUS))
	{
		if ($rath_auth_data == RATE_AUTH_DENY)
		{
			$template->assign_block_vars("noauth", array(
			"RATE_TOPIC_USER" => sprintf($lang['Not_Authorized_To_Rate']))
			);
		}
		else
		{
			$select_rate_choices = "<select name=\"rating\">";
			for ($i=1; $i <= $board_config['rating_max']; $i++)
			{
				$select_rate_choices .= "<option value=\"" . $i . "\"$selected>" . $i . "</option>";
			}
			$select_rate_choices .= "</select>";

			if (!rating_inserted($userdata['user_id'], $topic_id))
			{
				$template->assign_block_vars("rate", array(
				"L_CHOOSE_RATING" => $lang['Choose_Rating'],
				"L_RATE" => $lang['Rate'],
				"S_RATE_SELECT" => $select_rate_choices,
				"S_HIDDEN_FIELDS" => '<input type="hidden" name="topic_id" value="'. $topic_id . '" /><input type="hidden" name="rate_mode" value="rate" />',
				"S_RATE_ACTION" => append_sid("rate.$phpEx"),
				"RATE_TOPIC_USER" => "")
				);
			}
			else
			{
				if ($board_config['allow_rerate'])
				{
					$template->assign_block_vars("rerate", array(
					"L_CHANGE_RATING" => $lang['Change_Rating'],
					"L_RATE" => $lang['Rate'],
					"S_RATE_SELECT" => $select_rate_choices,
					"S_HIDDEN_FIELDS" => '<input type="hidden" name="topic_id" value="'. $topic_id . '" /><input type="hidden" name="rate_mode" value="rerate" />',
					"S_RATE_ACTION" => append_sid("rate.$phpEx"))
					);
				}
				$template->assign_block_vars("rated", array(
				"RATE_TOPIC_USER" => sprintf($lang['Already_Rated'], rating_value($userdata['user_id'], $topic_id)))
				);
			}

		}
		//
		//Common Output Variables
		//
		$rating_row = rating_stats($topic_id);
		$template->assign_vars(array(
		"L_RATE_TOPIC_USER_ANON" => ($board_config['check_anon_ip_when_rating'] && $userdata['user_id'] == ANONYMOUS) ? sprintf($lang['Or_Someone_From_IP']) : "",
		"RATE_TOPIC_STATS" => sprintf($lang['Rate_Stats'], $rating_row['average'],$rating_row['minimum'],$rating_row['maximum'],$rating_row['number_of_rates']),
		"TOPIC_TITLE" => id_to_value($topic_id, 'topic'),
		"L_SUMMARY" => $lang['Summary'])
		);
		if ($board_config['allow_ext_rating'])
		{
			$template->assign_vars(array(
			"FULL_STATS_URL" => sprintf($lang['View_Details'], append_sid("rate.$phpEx?rate_mode=detailed&amp;topic_id=" . $topic_id)))
			);
		}

		$template->set_filenames(array(
		"rate_viewtopic" => "rate_viewtopic.tpl")
		);
		$template->assign_var_from_handle('RATING_VIEWTOPIC', 'rate_viewtopic');

	}
}
/**
* @return float
* @param topic_id int
* @desc Returns the current rating for $topic_id in float form.
*/
function ratings_view_forum($topic_id)
{
	global $db, $template, $lang;

	$sql = 'SELECT AVG(rating) AS average
	   		   FROM ' . RATINGS_TABLE . "
	   	   	   WHERE topic_id = $topic_id";

	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$template->assign_var('L_RATING', $lang['Rating']);
	return $row['average'];
}

function ratings_check_forum($topic_id)
{
	global $db, $template, $rating_switched_on;

	$sql = 'SELECT f.auth_rate FROM ' . FORUMS_TABLE . ' f, ' . TOPICS_TABLE . " t
	   WHERE f.forum_id = t.forum_id
	   AND t.topic_id = $topic_id";

	if (!$f_result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Error_Dbase_Ratings'], $lang['Database_Error'], __LINE__, __FILE__, $sql);
	}
	$f_row = $db->sql_fetchrow($f_result);

	if ($f_row['auth_rate'] != -1)
	{
		//Make ratings viewable
		if (!$rating_switched_on)
		{
			print 'switched r';
			$template->assign_block_vars('rating_switch', array());
			$template->assign_var('COLSPAN_SETTING', '7');
			$rating_switched_on = TRUE;
		}
		$template->assign_block_vars('topicrow.rate_switch_msg', array());
	}
	else
	{
		$template->assign_var('COLSPAN_SETTING', '6');
		return FALSE;
	}

}
?>