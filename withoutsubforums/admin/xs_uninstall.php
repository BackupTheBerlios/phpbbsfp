<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_uninstall.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_uninstall.php
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

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_uninstall.'.$phpEx) . '">' . $lang['xs_uninstall_styles'] . '</a>'));

$lang['xs_uninstall_back'] = str_replace('{URL}', append_sid('xs_uninstall.'.$phpEx), $lang['xs_uninstall_back']);
$lang['xs_goto_default'] = str_replace('{URL}', append_sid('xs_styles.'.$phpEx), $lang['xs_goto_default']);

//
// uninstall style
//
if(isset($HTTP_GET_VARS['remove']) && !defined('DEMO_MODE'))
{
	$remove_id = intval($HTTP_GET_VARS['remove']);
	if($board_config['default_style'] == $remove_id)
	{
		xs_error(str_replace('{URL}', append_sid('xs_styles.'.$phpEx), $lang['xs_uninstall_default']) . '<br /><br />' . $lang['xs_uninstall_back']);
	}
	$sql = "SELECT themes_id, template_name, style_name FROM " . THEMES_TABLE . " WHERE themes_id='{$remove_id}'";
	if(!$result = $db->sql_query($sql))
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_uninstall_back'], __LINE__, __FILE__);
	}
	$row = $db->sql_fetchrow($result);
	if(empty($row['themes_id']))
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_uninstall_back'], __LINE__, __FILE__);
	}
	$sql = "UPDATE " . USERS_TABLE . " SET user_style=NULL WHERE user_style='{$remove_id}'";
	$db->sql_query($sql);
	$sql = "DELETE FROM " . THEMES_TABLE . " WHERE themes_id='{$remove_id}'";
	$db->sql_query($sql);
	$template->assign_block_vars('removed', array());
	// remove files
	if(!empty($HTTP_GET_VARS['dir']))
	{
		$HTTP_POST_VARS['remove'] = addslashes($row['template_name']);
	}
}

function remove_all($dir)
{
	$res = opendir($dir);
	if(!$res)
	{
		return false;
	}
	while(($file = readdir($res)) !== false)
	{
		if($file !== '.' && $file !== '..')
		{
			$str = $dir . '/' . $file;
			if(is_dir($str))
			{
				remove_all($str);
				@rmdir($str);
			}
			else
			{
				@unlink($str);
			}
		}
	}
	closedir($res);
}

//
// remove files
//
if(isset($HTTP_POST_VARS['remove']) && !defined('DEMO_MODE'))
{
	$remove = stripslashes($HTTP_POST_VARS['remove']);
	$params = array('remove' => $remove);
	if(!get_ftp_config(append_sid('xs_uninstall.'.$phpEx), $params, true))
	{
		xs_exit();
	}
	xs_ftp_connect(append_sid('xs_uninstall.'.$phpEx), $params, true);
	$write_local = false;
	if($ftp === XS_FTP_LOCAL)
	{
		$write_local = true;
		$write_local_dir = '../templates/';
	}
	if(!$write_local)
	{
		//
		// Generate actions list
		//
		$actions = array();
		// chdir to templates directory
		$actions[] = array(
				'command'	=> 'chdir',
				'dir'		=> 'templates'
			);
		// chdir to template
		$actions[] = array(
				'command'	=> 'chdir',
				'dir'		=> $remove
			);
		// remove all files
		$actions[] = array(
				'command'	=> 'removeall',
				'ignore'	=> true
			);
		$actions[] = array(
				'command'	=> 'cdup'
			);
		$actions[] = array(
				'command'	=> 'rmdir',
				'dir'		=> $remove
			);
		$ftp_log = array();
		$ftp_error = '';
		$res = ftp_myexec($actions);
/*		echo "<!--\n\n";
		echo "\$actions dump:\n\n";
		print_r($actions);
		echo "\n\n\$ftp_log dump:\n\n";
		print_r($ftp_log);
		echo "\n\n -->"; */
	}
	else
	{
		remove_all('../templates/'.$remove);
		@rmdir('../templates/'.$remove);
	}
	$template->assign_block_vars('removed', array());
}



//
// get list of installed styles
//
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name, style_name';
if(!$result = $db->sql_query($sql))
{
	xs_error($lang['xs_no_style_info'], __LINE__, __FILE__);
}
$style_rowset = $db->sql_fetchrowset($result);

$tpl = array();
for($i=0; $i<count($style_rowset); $i++)
{
	$item = $style_rowset[$i];
	$tpl[$item['template_name']][] = $item;
}

$j = 0;
foreach($tpl as $tpl => $styles)
{
	$row_class = $xs_row_class[$j % 2];
	$j++;
	$template->assign_block_vars('styles', array(
			'ROW_CLASS'	=> $row_class,
			'TPL'		=> htmlspecialchars($tpl),
			'ROWS'		=> count($styles),
		)
	);
	if(count($styles) > 1)
	{
		for($i=0; $i<count($styles); $i++)
		{
			$template->assign_block_vars('styles.item', array(
					'ID'		=> $styles[$i]['themes_id'],
					'THEME'		=> htmlspecialchars($styles[$i]['style_name']),
					'U_DELETE'	=> append_sid('xs_uninstall.'.$phpEx.'?remove='.$styles[$i]['themes_id']),
				)
			);
			$template->assign_block_vars('styles.item.nodelete', array());
		}
	}
	else
	{
		$i = 0;
		$template->assign_block_vars('styles.item', array(
				'ID'		=> $styles[$i]['themes_id'],
				'THEME'		=> htmlspecialchars($styles[$i]['style_name']),
				'U_DELETE'	=> append_sid('xs_uninstall.'.$phpEx.'?remove='.$styles[$i]['themes_id']),
			)
		);
		$template->assign_block_vars('styles.item.delete', array(
				'U_DELETE'	=> append_sid('xs_uninstall.'.$phpEx.'?dir=1&remove='.$styles[$i]['themes_id']),
			)
		);
	}
}

$template->set_filenames(array('body' => 'uninstall.tpl'));
$template->pparse('body');
xs_exit();

?>
