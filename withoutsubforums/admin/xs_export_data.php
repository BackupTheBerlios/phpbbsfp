<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_export_data.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_export_data.php
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

$template->assign_block_vars('nav_left',array('ITEM' => '&raquo; <a href="' . append_sid('xs_export_data.'.$phpEx) . '">' . $lang['xs_edit_styles_data'] . '</a>'));

$lang['xs_export_data_back'] = str_replace('{URL}', append_sid('xs_export_data.'.$phpEx), $lang['xs_export_data_back']);

//
// export style
//
if(isset($HTTP_GET_VARS['export']))
{
	$export = str_replace(array('\\', '/'), array('',''), stripslashes($HTTP_GET_VARS['export']));
	// get list of themes for style
	$sql = "SELECT themes_id, style_name FROM " . THEMES_TABLE . " WHERE template_name = '$export' ORDER BY style_name ASC";
	if(!$result = $db->sql_query($sql))
	{
		xs_error($lang['xs_no_theme_data'] . '<br /><br />' . $lang['xs_export_data_back']);
	}
	$theme_rowset = $db->sql_fetchrowset($result);
	if(count($theme_rowset) == 0)
	{
		xs_error($lang['xs_no_themes'] . '<br /><br />' . $lang['xs_export_data_back']);
	}
	if(count($theme_rowset) == 1)
	{
		$HTTP_POST_VARS['export'] = $HTTP_GET_VARS['export'];
		$HTTP_POST_VARS['export_total'] = '1';
		$HTTP_POST_VARS['export_id_0'] = $theme_rowset[0]['themes_id'];
		$HTTP_POST_VARS['export_check_0'] = 'checked';
	}
	else
	{
		$template->set_filenames(array('body' => 'export_data2.tpl'));
		$template->assign_vars(array(
			'TOTAL'		=> count($theme_rowset),
			'EXPORT'	=> htmlspecialchars($export),
			'U_ACTION'	=> append_sid("xs_export_data.{$phpEx}")
			)
		);
		for($i=0; $i<count($theme_rowset); $i++)
		{
			$row_class = $xs_row_class[$i % 2];
			$template->assign_block_vars('styles', array(
				'ROW_CLASS'		=> $row_class,
				'NUM'			=> $i,
				'ID'			=> $theme_rowset[$i]['themes_id'],
				'STYLE'			=> htmlspecialchars($theme_rowset[$i]['style_name'])
				)
			);
		}
		$template->pparse('body');
		xs_exit();
	}
}

if(!empty($HTTP_POST_VARS['export']) && !defined('DEMO_MODE'))
{
	$export = xs_tpl_name($HTTP_POST_VARS['export']);
	// get ftp configuration
	$params = array('export' => $export);
	$total = intval($HTTP_POST_VARS['export_total']);
	$count = 0;
	for($i=0; $i<$total; $i++)
	{
		if(!empty($HTTP_POST_VARS['export_check_'.$i]))
		{
			$params['export_id_'.$count] = intval($HTTP_POST_VARS['export_id_'.$i]);
			$params['export_check_'.$count] = 'checked';
			$count ++;
		}
	}
	$params['export_total'] = $count;
	if(!$count)
	{
		xs_error($lang['xs_export_noselect_themes'] . '<br /><br />' . $lang['xs_export_data_back']);
	}
	$write_local = false;
	if(!get_ftp_config(append_sid('xs_export_data.'.$phpEx), $params, true))
	{
		xs_exit();
	}
	xs_ftp_connect(append_sid('xs_export_data.'.$phpEx), $params, true);
	if($ftp === XS_FTP_LOCAL)
	{
		$write_local = true;
		$local_filename = '../templates/'. $export . '/theme_info.cfg';
	}
	else
	{
		$local_filename = XS_TEMP_DIR . 'export_' . time() . '.tmp';
	}
	// get all themes for style
	$export_list = array();
	for($i=0; $i<$total; $i++)
	{
		if(!empty($HTTP_POST_VARS['export_check_'.$i]))
		{
			$export_list[] = intval($HTTP_POST_VARS['export_id_'.$i]);
		}
	}
	$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id IN (" . implode(', ', $export_list) . ") ORDER BY style_name ASC";
	if(!$result = $db->sql_query($sql))
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_export_data_back'], __LINE__, __FILE__);
	}
	$style_rowset = $db->sql_fetchrowset($result);
	if(!count($style_rowset))
	{
		xs_error($lang['xs_no_style_info'] . '<br /><br />' . $lang['xs_export_data_back'], __LINE__, __FILE__);
	}
	$data = xs_generate_themeinfo($style_rowset, $export, $export, 0);
	$f = @fopen($local_filename, 'wb');
	if(!$f)
	{
		xs_error(str_replace('{FILE}', $local_filename, $lang['xs_error_cannot_create_file']) . '<br /><br />' . $lang['xs_export_data_back']);
	}
	fwrite($f, $data);
	fclose($f);
	if($write_local)
	{
		xs_message($lang['Information'], $lang['xs_export_data_saved'] . '<br /><br />' . $lang['xs_export_data_back']);
	}
	// generate ftp actions
	$actions = array();
	// chdir to template directory
	$actions[] = array(
			'command'	=> 'chdir',
			'dir'		=> 'templates'
		);
	$actions[] = array(
			'command'	=> 'chdir',
			'dir'		=> $export
		);
	$actions[] = array(
			'command'	=> 'upload',
			'local'		=> $local_filename,
			'remote'	=> 'templates/' . $export . '/theme_info.cfg'
			);
	$ftp_log = array();
	$ftp_error = '';
	$res = ftp_myexec($actions);
/*	echo "<!--\n\n";
	echo "\$actions dump:\n\n";
	print_r($actions);
	echo "\n\n\$ftp_log dump:\n\n";
	print_r($ftp_log);
	echo "\n\n -->"; */
	@unlink($local_filename);
	if($res)
	{
		xs_message($lang['Information'], $lang['xs_export_data_saved'] . '<br /><br />' . $lang['xs_export_data_back']);
	}
	xs_error($ftp_error . '<br /><br />' . $lang['xs_export_data_back']);
}




$template->set_filenames(array('body' => 'export_data.tpl'));
//
// get list of installed styles
//
$sql = 'SELECT themes_id, template_name, style_name FROM ' . THEMES_TABLE . ' ORDER BY template_name';
if(!$result = $db->sql_query($sql))
{
	xs_error($lang['xs_no_style_info'], __LINE__, __FILE__);
}
$style_rowset = $db->sql_fetchrowset($result);

$prev_id = -1;
$prev_tpl = '';
$style_names = array();
$j = 0;
for($i=0; $i<count($style_rowset); $i++)
{
	$item = $style_rowset[$i];
	if($item['template_name'] === $prev_tpl)
	{
		$style_names[] = htmlspecialchars($item['style_name']);
	}
	else
	{
		if($prev_id > 0)
		{
			$str = implode('<br />', $style_names);
			$str2 = urlencode($prev_tpl);
			$row_class = $xs_row_class[$j % 2];
			$j++;
			$template->assign_block_vars('styles', array(
					'ROW_CLASS'	=> $row_class,
					'TPL'		=> $prev_tpl,
					'STYLES'	=> $str,
					'U_EXPORT'	=> "xs_export_data.{$phpEx}?export={$str2}&sid={$userdata['session_id']}",
				)
			);
		}
		$prev_id = $item['themes_id'];
		$prev_tpl = $item['template_name'];
		$style_names = array(htmlspecialchars($item['style_name']));
	}
}

if($prev_id > 0)
{
	$str = implode('<br />', $style_names);
	$str2 = urlencode($prev_tpl);
	$row_class = $xs_row_class[$j % 2];
	$j++;
	$template->assign_block_vars('styles', array(
			'ROW_CLASS'	=> $row_class,
			'TPL'		=> $prev_tpl,
			'STYLES'	=> $str,
			'U_EXPORT'	=> "xs_export_data.{$phpEx}?export={$str2}&sid={$userdata['session_id']}",
		)
	);
}

$template->pparse('body');
xs_exit();

?>
