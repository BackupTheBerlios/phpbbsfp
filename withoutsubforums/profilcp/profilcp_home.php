<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_home.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilecp_home.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

if( !empty($setmodules) )
{
	pcp_set_menu('home', 20, __FILE__, 'profilcp_index_shortcut', 'profilcp_index_pagetitle' );
	return;
}

// get the home panel modules
$home_modules = array();
$dir = @opendir($phpbb_root_path . "profilcp");
$set_homemodules = true;
while( $file = @readdir($dir) )
{
	if( preg_match("/^profilcp_home_.*?\." . $phpEx . "$/", $file) )
	{
		include($phpbb_root_path . "profilcp/" . $file);
	}
}
@closedir($dir);
unset($set_homemodules);

// sort them
array_multisort( $home_modules['pos'], $home_modules['sort'], $home_modules['url'] );

// template file
$template->set_filenames(array(
	'body' => 'profilcp/home_body.tpl')
);

// process the includes
$left_part = false;
$right_part = false;

// pre process : global init
$process = 'pre';
for ($home_module=0; $home_module < count($home_modules['url']); $home_module++)
{
	include( $phpbb_root_path . './profilcp/' . $home_modules['url'][$home_module] );
}

// post process : display, paginations and so
$process = 'post';
for ($home_module=0; $home_module < count($home_modules['url']); $home_module++)
{
	include( $phpbb_root_path . './profilcp/' . $home_modules['url'][$home_module] );
}

// achieve the display
$template->assign_vars(array(
	'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
	'S_PROFILCP_ACTION'		=> append_sid("./profile.$phpEx"),
	)
);
$template->pparse('body');

?>