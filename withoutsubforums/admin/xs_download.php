<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_download.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_download.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		CyberAlien
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/***************************************************************************
 *
 *   version              : 2.0.0.rc5
 *
 *   file revision        : 21
 *   project revision     : 31
 *   last modified        : 17 Jul 2004  14:55:05
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
$phpbb_root_path = "./../";
$no_page_header = true;
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

// check if mod is installed
if(empty($template->xs_version) || $template->xs_version !== 4)
{
	message_die(GENERAL_ERROR, 'eXtreme Styles mod is not installed. You forgot to upload includes/template.php');
}

define('IN_XS', true);
include_once('xs_include.' . $phpEx);

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_import.'.$phpEx) . '">' . $lang['xs_import_styles'] . '</a>'));
$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_download.'.$phpEx) . '">' . $lang['xs_download_styles'] . '</a>'));

// submit url
if(isset($HTTP_GET_VARS['url']) && !defined('DEMO_MODE'))
{
	$id = intval($HTTP_GET_VARS['url']);
	$var = 'xs_downloads_' . $id;
	$import_data = array(
		'host'		=> $HTTP_SERVER_VARS['HTTP_HOST'],
		'port'		=> $HTTP_SERVER_VARS['SERVER_PORT'],
		'url'		=> str_replace('xs_download.', 'xs_frameset.', $HTTP_SERVER_VARS['PHP_SELF']),
		'session'	=> $userdata['session_id'],
		'xs'		=> $template->xs_versiontxt,
		'style'		=> STYLE_HEADER_VERSION,
	);
	$str = '<form action="' . $board_config[$var] . '" method="post" style="display: inline;" target="main"><input type="hidden" name="data" value="' . htmlspecialchars(serialize($import_data)) . '" /><input type="submit" value="' . $lang['xs_continue'] . '" class="post" /></form>';
	$message = $lang['xs_import_download_warning'] . '<br /><br />' . $str . '<br /><br />' . str_replace('{URL}', append_sid('xs_download.'.$phpEx), $lang['xs_download_back']);
	xs_message($lang['Information'], $message);
}


if(isset($HTTP_GET_VARS['edit']))
{
	$id = intval($HTTP_GET_VARS['edit']);
	$template->assign_block_vars('edit', array(
		'ID'		=> $id,
		'TITLE'		=> $board_config['xs_downloads_title_'.$id],
		'URL'		=> $board_config['xs_downloads_'.$id]
		));
}

if(isset($HTTP_POST_VARS['edit']) && !defined('DEMO_MODE'))
{
	$id = intval($HTTP_POST_VARS['edit']);
	$update = array();
	if(!empty($HTTP_POST_VARS['edit_delete']))
	{
		// delete link
		$total = $board_config['xs_downloads_count'];
		$update['xs_downloads_count'] = $total - 1;
		for($i=$id; $i<($total-1); $i++)
		{
			$update['xs_downloads_'.$i] = $update['xs_downloads_'.($i+1)];
			$update['xs_downloads_title_'.$i] = $update['xs_downloads_title_'.($i+1)];
		}
		$update['xs_downloads_'.($total-1)] = '';
		$update['xs_downloads_title_'.($total-1)] = '';
	}
	else
	{
		$update['xs_downloads_'.$id] = stripslashes($HTTP_POST_VARS['edit_url']);
		$update['xs_downloads_title_'.$id] = stripslashes($HTTP_POST_VARS['edit_title']);
	}
	foreach($update as $var => $value)
	{
		set_config($var, xs_sql($value));
	}
}

if(!empty($HTTP_POST_VARS['add_url']) && !defined('DEMO_MODE'))
{
	$id = $board_config['xs_downloads_count'];
	$update = array();
	$update['xs_downloads_'.$id] = stripslashes($HTTP_POST_VARS['add_url']);
	$update['xs_downloads_title_'.$id] = stripslashes($HTTP_POST_VARS['add_title']);
	$update['xs_downloads_count'] = $board_config['xs_downloads_count'] + 1;
	foreach($update as $var => $value)
	{
		set_config($var, xs_sql($value));
	}
}

for($i=0; $i<$board_config['xs_downloads_count']; $i++)
{
	$row_class = $xs_row_class[$i % 2];
	$template->assign_block_vars('url', array(
		'ROW_CLASS'		=> $row_class,
		'NUM'			=> $i,
		'NUM1'			=> $i + 1,
		'URL'			=> htmlspecialchars($board_config['xs_downloads_'.$i]),
		'TITLE'			=> htmlspecialchars($board_config['xs_downloads_title_'.$i]),
		'U_DOWNLOAD'	=> append_sid('xs_download.'.$phpEx.'?url='.$i),
		'U_EDIT'		=> append_sid('xs_download.'.$phpEx.'?edit='.$i),
		));
}

$template->assign_vars(array(
	'U_POST'		=> append_sid('xs_download.'.$phpEx)
	));

$template->set_filenames(array('body' => 'downloads.tpl'));
$template->pparse('body');
xs_exit();

?>
