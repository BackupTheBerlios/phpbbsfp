<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_statistics.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_statistics.php
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

if(!function_exists('imp_statistics_block_func'))
{
	function imp_statistics_block_func()
	{
		global $template, $lang, $phpEx;

		$total_posts = get_db_stat('postcount');
		$total_users = get_db_stat('usercount');
		$total_topics = get_db_stat('topiccount');
		$newest_userdata = get_db_stat('newestuser');
		$newest_user = $newest_userdata['username'];
		$newest_uid = $newest_userdata['user_id'];

		if( $total_posts == 0 )
		{
			$l_total_post_s = $lang['Posted_articles_zero_total'];
		}
		else if( $total_posts == 1 )
		{
			$l_total_post_s = $lang['Posted_article_total'];
		}
		else
		{
			$l_total_post_s = $lang['Posted_articles_total'];
		}

		if( $total_users == 0 )
		{
			$l_total_user_s = $lang['Registered_users_zero_total'];
		}
		else if( $total_users == 1 )
		{
			$l_total_user_s = $lang['Registered_user_total'];
		}
		else
		{
			$l_total_user_s = $lang['Registered_users_total'];
		}

		$template->assign_vars(array(
			'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
			'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
			'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
			'TOTAL_TOPICS' => sprintf($lang['total_topics'], $total_topics)
			)
		);
	}
}

imp_statistics_block_func();
?>