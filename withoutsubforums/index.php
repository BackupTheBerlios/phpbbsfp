<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: index.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : index.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'),	1);

//
// Define initial vars
//
if (isset($HTTP_POST_VARS['module']) ||	isset($HTTP_GET_VARS['module']))
{
	$mvModuleName =	( isset($HTTP_POST_VARS['module']) ) ? $HTTP_POST_VARS['module'] : $HTTP_GET_VARS['module'];
}
else
{
	$mvModuleName =	'?';
}

include($phpbb_root_path . 'common.'.$phpEx);

$layout	= $mvModuleName;

$mvModuleFile =	$mvModuleName;
if (isset($HTTP_POST_VARS['file']) || isset($HTTP_GET_VARS['file']))
{
	$mvModuleFile =	(isset($HTTP_POST_VARS['file'])) ? $HTTP_POST_VARS['file'] : $HTTP_GET_VARS['file'];
}

if ($mvModuleName != '')
{
	$file_name = "$mvModule_root_path$mvModuleFile.$phpEx";

	if (@file_exists($file_name))
	{
		include($file_name);
		return;
	}

	$mvModuleFile =	'index';

	$file_name = "$mvModule_root_path$mvModuleFile.$phpEx";

	if (@file_exists($file_name))
	{
		include($file_name);
		return;
	}

	$mvModuleName =	'';
	$file_name = '';
}

//
// There was no default module set, dump out an error.
//

//
// Start session management
//
$userdata =	session_pagestart($user_ip,	PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$page_title = $lang['Site_index'];

include($phpbb_root_path . 'includes/page_header.'.$phpEx);
message_die(GENERAL_MESSAGE, $lang['No_module_defined']);

?>