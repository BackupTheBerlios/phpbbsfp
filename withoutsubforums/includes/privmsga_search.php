<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_search.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsga_search.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') || !defined('IN_PRIVMSG') )
{
	die('Hacking attempt');
}

//--------------------------
//
//  get parameters
//
//--------------------------
_hidden_init();

// vars
$folder_id		= _read_var('folder', 1, INBOX);
$search_folder	= _read_var('search_folder', 1);
$username		= htmlspecialchars(unprepare_message(stripslashes(urldecode(_read_var('username')))));
$words			= htmlspecialchars(unprepare_message(stripslashes(urldecode(_read_var('words')))));

// buttons
$submit			= _button_var('submit_search');
$cancel			= _button_var('cancel');

//-----------------------------
//
//	performed some checks
//
//-----------------------------
$error = false;
$error_msg = '';

// folder
if ( !isset($folders['data'][$folder_id]) )
{
	$folder_id = INBOX;
}
$folder_main = $folder_id;
if ( !empty($folders['main'][$folder_id]) )
{
	$folder_main = $folders['main'][$folder_id];
}

// searched folder
if ( empty($search_folder) )
{
	$search_folder = $folder_id;
}
$s_search_folders = '';
if ( $search_folder == -1 )
{
	// all folders required
	@reset($folders['data']);
	while ( list($key, $data) = @each($folders['data']) )
	{
		$s_search_folders .= ( empty($s_search_folders) ? '' : ', ' ) . $data['folder_id'];
	}
}
else
{
	$s_search_folders = $search_folder . ( empty($folders['sub'][$search_folder]) ? '' : ', ' . implode(', ', $folders['sub'][$search_folder]) );
}

// username
$search_user_id = ANONYMOUS;
if ( !empty($username) )
{
	$sql = "SELECT user_id
				FROM " . USERS_TABLE . "
				WHERE username = '" . str_replace("'", "\'", $username) . "'";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not read user', '', __LINE__, __FILE__, $sql);
	}
	if ( !$row = $db->sql_fetchrow($result) )
	{
		_error('User_not_exist');
	}
	else
	{
		$search_user_id = intval($row['user_id']);
	}
}

// word list
$search_words = array();
if ( !empty($words) )
{
	$w_words = explode(',', $words);
	for ( $i = 0; $i < count($w_words); $i++ )
	{
		$word = trim($w_words[$i]);
		if ( !empty($word) )
		{
			$search_words[] = $word;
		}
	}
}

if ( ($search_user_id == ANONYMOUS) && empty($search_words) )
{
	_error('Search_no_criteria');
}

//-----------------------------
//
//	main entry
//
//-----------------------------
if ( $pmmode == 'search' )
{
	if ( $cancel )
	{
		$pmmode = '';
		$pm_start = 0;
		$cancel = false;
	}
	else if ( $submit )
	{
		// send any error messages
		if ( $error )
		{
			$l_link = 'Click_return_folders';
			$u_link = append_sid("$main_pgm&pmmode=search&folder=$folder_id&username=" . urlencode($username) . "&words=" . urlencode($words) );
			_message_return($error_msg, $l_link, $u_link);
		}

		// base request
		$sql_tables = PRIVMSGA_RECIPS_TABLE . ' pr, ' . PRIVMSGA_TABLE . ' p';
		$sql_where = "p.privmsg_id = pr.privmsg_id
						AND pr.privmsg_user_id = $view_user_id
						AND pr.privmsg_status <> " . STS_DELETED . "
						AND pr.privmsg_folder_id IN ($s_search_folders)";

		// add recipient selection
		if ( !empty($search_user_id) && ($search_user_id != ANONYMOUS) )
		{
			$sql_tables .= ', ' . PRIVMSGA_RECIPS_TABLE . ' pa';
			$sql_where .= "
						AND pa.privmsg_id = pr.privmsg_id
						AND pa.privmsg_user_id = $search_user_id
						AND pa.privmsg_direct <> pr.privmsg_direct";
		}

		// add words selection
		$cond = 'OR';
		if ( !empty($search_words) )
		{
			$s_search_words_subject = '';
			$s_search_words_text = '';
			for ( $i = 0; $i < count($search_words); $i++ )
			{
				$word = str_replace("'", "\'", $search_words[$i]);
				$s_search_words_subject .= ( empty($s_search_words_subject) ? '' : "
								$cond ") . " p.privmsg_subject LIKE '%$word%'";
				$s_search_words_text .= ( empty($s_search_words_text) ? '' : "
								$cond ") . " p.privmsg_text LIKE '%$word%'";
			}
			if ( !empty($s_search_words_subject) || !empty($s_search_words_text) )
			{
				$cond = '';
				if ( !empty($s_search_words_subject) && !empty($s_search_words_text) )
				{
					$cond = ")
									OR
									(";
				}
				$sql_where .= "
						AND (
									( $s_search_words_subject $cond $s_search_words_text )
						)";
			}
		}

		// request
		$sql = "SELECT pr.privmsg_recip_id
					FROM $sql_tables
					WHERE $sql_where";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not read messages', '', __LINE__, __FILE__, $sql);
		}
		$count = $db->sql_numrows($result);

		// process a page
		$s_privmsg_recip_ids = '';
		$privmsg_rowset = array();
		if ( $count > 0 )
		{
			$sql .= "
					ORDER BY p.privmsg_time DESC, pr.privmsg_recip_id
					LIMIT $pm_start, " . intval($board_config['topics_per_page']);
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not read messages', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				$s_privmsg_recip_ids .= ( empty($s_privmsg_recip_ids) ? '' : ', ' ) . $row['privmsg_recip_id'];
			}

			// process the display
			$sql = "SELECT p.*,
							pa.*,
							pr.privmsg_recip_id AS selected_pm_id, pr.privmsg_status AS selected_status, pr.privmsg_read AS selected_read,
							ua.username AS privmsg_from_username
						FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " ua
						WHERE pr.privmsg_recip_id IN ($s_privmsg_recip_ids)
							AND p.privmsg_id = pr.privmsg_id
							AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
							AND ( (pa.privmsg_user_id = 0 AND ua.user_id = " . ANONYMOUS . ") OR (pa.privmsg_user_id <> 0 AND ua.user_id = pa.privmsg_user_id) )
						ORDER BY p.privmsg_time DESC, p.privmsg_id";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Can\'t read pms in selected sub-folder', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				$privmsg_rowset[] = $row;
				$s_privmsg_ids .= ( empty($s_privmsg_ids) ? '' : ', ' ) . $row['privmsg_id'];
			}
		}

		// read the recipients
		if ( !empty($privmsg_rowset) )
		{
			$sql = "SELECT pr.privmsg_id, pr.privmsg_recip_id, pr.privmsg_user_id, ur.username AS privmsg_to_username
						FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " ur
						WHERE pr.privmsg_id IN ($s_privmsg_ids) AND pr.privmsg_direct = 1
							AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
							AND ur.user_id = pr.privmsg_user_id
							AND (pr.privmsg_user_id = $view_user_id OR pa.privmsg_user_id = $view_user_id)
						ORDER BY pr.privmsg_id, ur.username, pr.privmsg_recip_id";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Can\'t read recipients', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( count($recips[ $row['privmsg_id'] ]) <= $cfg_max_userlist )
				{
					$recips['data'][ $row['privmsg_id'] ][] = $row;
				}
				else
				{
					$recips['over'][ $row['privmsg_id'] ] = true;
				}
			}
		}

		// set the page title and include the page header
		$page_title = _lang('Search_pm');
		if ( !defined('IN_PCP') )
		{
			include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
		}

		// template name
		$template->set_filenames(array(
			'body' => 'privmsga_body.tpl')
		);

		// send header
		privmsg_header($view_user_id, $folder_id);

		// send list
		$select = false;
		$mark_ids = array();
		$detailed = true;
		privmsg_list($privmsg_rowset, $recips, $folder_id, $select, $mark_ids, $detailed);

		// pagination
		$page_list		= generate_pagination("$main_pgm&pmmode=search&amp;folder=$folder_id&amp;search_folder=$search_folder&amp;submit_search=1&amp;" . POST_USERS_URL . "=$view_user_id&amp;username=" . urlencode($username) . "&amp;words=" . urlencode($words), $count, $board_config['topics_per_page'], $pm_start);
		$page_number	= sprintf(_lang('Page_of'), ( floor( $pm_start / $board_config['topics_per_page'] ) + 1 ), ceil( $count / $board_config['topics_per_page'] ));
		//$page_list		= str_replace('&amp;start', '&start', $page_list);
		$page_list		= preg_replace("/\&start\=(\d*)/", "javascript:document.post.start.value='\\1'; document.post.submit();", $page_list);

		// save some values
		_hide('pmmode', $pmmode);
		_hide('folder', $folder_id);
		_hide(POST_USERS_URL, $view_user_id);
		_hide('sid', $userdata['session_id']);
		_hide('start', $pm_start);
		_hide('submit_search', true);

		_hide('search_folder', $search_folder);
		if ( !empty($username) )
		{
			_hide( 'username', str_replace("''", "'", $username) );
		}
		if ( !empty($search_words) )
		{
			_hide( 'words', str_replace("''", "'", implode(', ', $search_words)) );
		}

		// system
		$template->assign_vars(array(
			'S_ACTION'			=> append_sid($main_pgm),
			'S_HIDDEN_FIELDS'	=> _hidden_get(),

			'L_GOTO_PAGE'		=> ($count <  $board_config['topics_per_page']) ? '' : _lang('Goto_page'),
			'PAGINATION'		=> ($count <  $board_config['topics_per_page']) ? '' : $page_list,
			'PAGE_NUMBER'		=> $page_number,
			)
		);

		// send to browser
		privmsg_footer();
		$template->pparse('body');
		if ( !defined('IN_PCP') )
		{
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
	}
	else
	{
		// display the page
		$page_title = _lang('Search_pm');
		if ( !defined('IN_PCP') )
		{
			include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
		}

		$template->set_filenames(array(
			'body' => 'privmsga_search_body.tpl')
		);
		privmsg_header($view_user_id, $folder_id, $privmsg_recip_id);

		// header
		$template->assign_vars(array(
			'L_TITLE'					=> _lang('Search_pm'),
			'L_SEARCH_FOLDER'			=> _lang('Search_folder'),
			'L_SEARCH_FOLDER_EXPLAIN'	=> _lang('Search_folder_explain'),
			'L_SEARCH_AUTHOR'			=> _lang('Search_recipient'),
			'L_SEARCH_AUTHOR_EXPLAIN'	=> _lang('Search_recipient_explain'),
			'L_SEARCH_WORDS'			=> _lang('Search_words'),
			'L_SEARCH_WORDS_EXPLAIN'	=> _lang('Search_words_explain'),

			'L_SUBMIT'					=> _lang('Submit'),
			'L_CANCEL'					=> _lang('Cancel'),
			'L_FIND_USERNAME'			=> _lang('Find_username'),
			'U_SEARCH_USER'				=> append_sid("search.$phpEx?mode=searchuser"),
			)
		);

		// data
		$s_folders = '<option value="-1">' . _lang('All_folders') . '</option>' . get_folders_list(0, $folder_id);

		// vars
		$template->assign_vars(array(
			'S_FOLDERS'		=> $s_folders,
			'USERNAME'		=> empty($username) ? '' : str_replace("''", "'", $username),
			'WORDS'			=> empty($search_words) ? '' : str_replace("''", "'", implode(', ', $search_words)),
			)
		);

		// system
		_hide(POST_USERS_URL, $view_user_id);
		_hide('pmmode', $pmmode);
		_hide('sid', $userdata['session_id']);

		$template->assign_vars(array(
			'S_ACTION'			=> append_sid($main_pgm),
			'S_HIDDEN_FIELDS'	=> _hidden_get(),
			)
		);

		// send to browser
		privmsg_footer();
		$template->pparse('body');
		if ( !defined('IN_PCP') )
		{
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
	}
}

?>