<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_user_block.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_user_block.php
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

if(!function_exists('imp_user_block_block_func'))
{
	function imp_user_block_block_func()
	{
		global $userdata, $template, $board_config, $lang, $db, $phpEx;

		if( $userdata['session_logged_in'] )
		{
			$sql = "SELECT COUNT(post_id) as total
					FROM " . POSTS_TABLE . "
					WHERE post_time >= " . $userdata['user_lastvisit'];
			$result = $db->sql_query($sql);
			if( $result )
			{
				$row = $db->sql_fetchrow($result);
				$lang['Search_new'] = $lang['Search_new'] . "&nbsp;(" . $row['total'] . ")";
			}
		}

		$avatar_img = '';
		if ( $userdata['user_avatar_type'] && $userdata['user_allowavatar'] )
		{
			switch( $userdata['user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $userdata['user_avatar'] . '" alt="" border="0" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $userdata['user_avatar'] . '" alt="" border="0" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $userdata['user_avatar'] . '" alt="" border="0" />' : '';
					break;
			}
		}
		if ($userdata['user_id'] != '-1')
		{
			$name_link = '<a href="' . append_sid("profile.$phpEx?mode=editprofile") . '">' . $userdata['username'] . '</a>';
		}
		else
		{
			$name_link = $lang['Guest'];
		}

		$template->assign_vars(array(
			'AVATAR_IMG' => $avatar_img,
			'U_NAME_LINK' => $name_link,
			'L_REMEMBER_ME' => $lang['Remember_me'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),
			'L_REGISTER_NEW_ACCOUNT' => sprintf($lang['Register_new_account'], '<a href="' . append_sid("profile.$phpEx?mode=register") . '">', '</a>'),
			'L_NEW_SEARCH' => $lang['Search_new']
			)
		);
	}
}

imp_user_block_block_func();
?>