<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: rate.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME	 : rate.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2002, 2003  Nivisec.com
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', TRUE);
$phpbb_root_path = "./";
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);

$layout = $core_layout[LAYOUT_FORUM];

/*******************************************************************************************
/** Start session.
/******************************************************************************************/
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

/*******************************************************************************************
/** Get parameters.
/******************************************************************************************/
$params = array('rate_mode', 'forum_top', 'topic_id', 'rating');

foreach($params as $var)
{
	$$var = '';
	if( isset($HTTP_POST_VARS[$var]) || isset($HTTP_GET_VARS[$var]) )
	{
		$$var = ( isset($HTTP_POST_VARS[$var]) ) ? $HTTP_POST_VARS[$var] : $HTTP_GET_VARS[$var];
	}
}
/*******************************************************************************************
/** Page Titles if Specific!
/******************************************************************************************/
switch($rate_mode)
{
	case 'rate':
			$page_title = $lang['Rating'];
	case 'rerate':
	break;
	case 'detailed':
	{
		if ($topic_id == '')
		{
			message_die(GENERAL_ERROR, $lang['No_Topic_ID'], '', __LINE__, __FILE__);
		}
		$page_title = $lang['Topic_Rating_Details'];
		break;
	}
	default:
	{
		if ($forum_top == '')
		{
			$forum_top = -1;
		}
		$page_title = sprintf($lang['Top_Topics'], $board_config['large_rating_return_limit']);
		break;
	}
}
/*******************************************************************************************
/** Include Header (It Contains Rate Functions).
/******************************************************************************************/
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

/*******************************************************************************************
/** Display modes, for if the page is called seperately
/******************************************************************************************/
switch($rate_mode)
{
	case 'rate':
			rate_topic($userdata['user_id'], $topic_id, $rating, 'rate');
			break;
	case 'rerate':
				rate_topic($userdata['user_id'], $topic_id, $rating, 'rerate');
	break;
	case 'detailed':
	{
		ratings_detailed($topic_id);
		break;
	}
	default:
	{
		ratings_large();
		break;
	}
}
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
?>