<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: cash_help.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : cash_help.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Xore
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------


define('IN_PHPBB', 1);
define('IN_CASHMOD', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

if ( $board_config['cash_adminnavbar'] )
{
	$navbar = 1;
	include('./admin_cash.'.$phpEx);
}
//
// Start page proper
//
$template->set_filenames(array(
	"body" => "cash_menu.tpl")
);

$j = 0;
$help = array();
$help[0] = new cash_menucat($lang['Cmenu_cash_help']);
$help[0]->additem(new cash_menuitem($j,	'Cmh_support',			'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#623226',	$lang['Cmhe_support']));
$help[0]->additem(new cash_menuitem($j,	'Cmh_troubleshooting',	'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#625402',	$lang['Cmhe_troubleshooting']));
$help[0]->additem(new cash_menuitem($j,	'Cmh_upgrading',		'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#648190',	sprintf($lang['Cmhe_upgrading'],$board_config['cash_version'])));
$help[0]->additem(new cash_menuitem($j,	'Cmh_addons',			'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#655651',	$lang['Cmhe_addons']));
$help[0]->additem(new cash_menuitem($j,	'Cmh_demo_boards',		'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#658468',	$lang['Cmhe_demo_boards']));
$help[0]->additem(new cash_menuitem($j,	'Cmh_translations',		'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#662158',	$lang['Cmhe_translations']));
$help[0]->additem(new cash_menuitem($j,	'Cmh_features',			'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#664549',	$lang['Cmhe_features']));

for ( $i = 0; $i < count($help); $i++ )
{
	$template->assign_block_vars("menucat",array("L_CATEGORY" => $help[$i]->category));
	for ( $j = 0; $j < $help[$i]->num(); $j++ )
	{
		$template->assign_block_vars("menucat.menuitem",$help[$i]->items[$j]->data('',1,' target="cmh"',false));
	}
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
