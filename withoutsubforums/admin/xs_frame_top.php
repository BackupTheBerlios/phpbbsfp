<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_frame_top.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_frame_top.php
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
 *   file revision        : 30
 *   project revision     : 31
 *   last modified        : 17 Jul 2004  14:55:05
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
$phpbb_root_path = "./../";
$no_page_header = true;
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

define('IN_XS', true);
define('NO_XS_HEADER', true);
include_once('xs_include.' . $phpEx);

$template->set_filenames(array('body' => 'frame_top.tpl'));

$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_index.'.$phpEx),
	'TEXT'	=> $lang['xs_menu_lc']
	));
/* $template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_download.'.$phpEx),
	'TEXT'	=> $lang['xs_download_styles_lc']
	)); */
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_import.'.$phpEx),
	'TEXT'	=> $lang['xs_import_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_install.'.$phpEx),
	'TEXT'	=> $lang['xs_install_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> 'http://www.phpbbstyles.com',
	'TEXT'	=> $lang['xs_support_forum_lc']
	));


$template->pparse('body');
xs_exit();

?>
