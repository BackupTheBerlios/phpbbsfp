<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_frameset.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_frameset.php
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
define('NO_XS_HEADER', true);
include_once('xs_include.' . $phpEx);

$action = isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '';
$get_data = array();
foreach($HTTP_GET_VARS as $var => $value)
{
	if($var !== 'action' && $var !== 'sid')
	{
		$get_data[] = $var . '=' . urlencode(stripslashes($value));
	}
}

// check for style download command
if(isset($HTTP_POST_VARS['action']) && $HTTP_POST_VARS['action'] === 'web')
{
	$action = 'import';
	$get_data[] = 'get_remote=' . urlencode(stripslashes($HTTP_POST_VARS['source']));
	if(isset($HTTP_POST_VARS['return']))
	{
		$get_data[] = 'return=' . urlencode(stripslashes($HTTP_POST_VARS['return']));
	}
}

$get_data = count($get_data) ? $phpEx . '?' . implode('&', $get_data) : $phpEx;

$content_url = array(
	'config'	=> append_sid('xs_config.'.$get_data),
	'install'	=> append_sid('xs_install.'.$get_data),
	'uninstall'	=> append_sid('xs_uninstall.'.$get_data),
	'default'	=> append_sid('xs_styles.'.$get_data),
	'cache'		=> append_sid('xs_cache.'.$get_data),
	'import'	=> append_sid('xs_import.'.$get_data),
	'export'	=> append_sid('xs_export.'.$get_data),
	'clone'		=> append_sid('xs_clone.'.$get_data),
	'download'	=> append_sid('xs_download.'.$get_data),
	'edittpl'	=> append_sid('xs_edit.'.$get_data),
	'editdb'	=> append_sid('xs_edit_data.'.$get_data),
	'exportdb'	=> append_sid('xs_export_data.'.$get_data),
	'updates'	=> append_sid('xs_update.'.$get_data),
	'portal'	=> append_sid('xs_portal.'.$get_data),
	);

if(isset($content_url[$action]))
{
	$content = $content_url[$action];
}
else
{
	$content = append_sid('xs_index.'.$get_data);
}

$template->set_filenames(array('body' => 'frameset.tpl'));
$template->assign_vars(array(
	'FRAME_TOP'		=> append_sid('xs_frame_top.'.$phpEx),
	'FRAME_MAIN'	=> $content,
	));

$template->pparse('body');
xs_exit();

?>
