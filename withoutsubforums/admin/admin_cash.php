<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_cash.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_cash.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Xore
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------


if ( !defined('ADMIN_MENU') )
{
	define('ADMIN_MENU',1);
	function admin_menu(&$menu)
	{
		global $lang;
		$i = 0;
		$j = 0;
		$menu[$i] = new cash_menucat($lang['Cmcat_main']);
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Configuration',	'cash_config',		$lang['Cmenu_cash_config']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Currencies',		'cash_currencies',	$lang['Cmenu_cash_currencies']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Forums',			'cash_forums',		$lang['Cmenu_cash_forums']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Settings',		'cash_settings',	$lang['Cmenu_cash_settings']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_addons']);
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Events',			'cash_events',		$lang['Cmenu_cash_events']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Reset',			'cash_reset',		$lang['Cmenu_cash_reset']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_other']);
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Exchange',		'cash_exchange',	$lang['Cmenu_cash_exchange']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Groups',			'cash_groups',		$lang['Cmenu_cash_groups']));
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Logs',			'cash_log',			$lang['Cmenu_cash_log']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_help']);
		$menu[$i]->additem(new cash_menuitem($j,	'Cash_Help',			'cash_help',		$lang['Cmenu_cash_help']));
	}
}

if ( !empty($navbar) )
{
	$menu = array();
	admin_menu($menu);

	$template->set_filenames(array(
		"navbar" => "cash_navbar.tpl")
	);

	$class = 0;
	for ( $i = 0; $i < count($menu); $i++ )
	{
		$template->assign_block_vars("navcat",array(	"L_CATEGORY" => $menu[$i]->category,
														"WIDTH" => $menu[$i]->num()));
		for ( $j = 0; $j < $menu[$i]->num(); $j++ )
		{
			$template->assign_block_vars("navitem",$menu[$i]->items[$j]->data($phpEx,$class+1,''));
			$class = ($class + 1)%2;
		}
	}
	$template->assign_var_from_handle('NAVBAR', 'navbar');
	return;
}

if ( !empty($setmodules) )
{
    $phpbb_root_path = './../';
    include($phpbb_root_path . 'config.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_cash.'.$phpEx);
	$menu = array();
	admin_menu($menu);

	if ( $board_config['cash_adminbig'] )
	{
		for ( $i = 0; $i < count($menu); $i++ )
		{
			for ( $j = 0; $j < $menu[$i]->num(); $j++ )
			{
				$module['Cash Mod'][$menu[$i]->items[$j]->title] = $menu[$i]->items[$j]->linkage($phpEx);
				if ( ($j == $menu[$i]->num() - 1) && !($i == count($menu) - 1) )
				{
					$lang[$menu[$i]->items[$j]->title] = $lang[$menu[$i]->items[$j]->title] . '</a></span></td></tr><tr><td class="row2" height="7"><span class="genmed"><a name="cm' . $menu[$i]->num() . '">';
				}
			}
		}
	}
	else
	{
		$file = basename(__FILE__);
		$module['Cash Mod']['Cash_Admin'] = "$file";
		$module['Cash Mod']['Cash_Help'] = "cash_help.$phpEx";
	}
	return;
}

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

//$menu = array();
admin_menu($menu);

$template->set_filenames(array(
	"body" => "cash_menu.tpl")
);

for ( $i = 0; $i < count($menu); $i++ )
{
	$template->assign_block_vars("menucat",array("L_CATEGORY" => $menu[$i]->category));
	for ( $j = 0; $j < $menu[$i]->num(); $j++ )
	{
		$template->assign_block_vars("menucat.menuitem",$menu[$i]->items[$j]->data($phpEx,1,''));
	}
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
