<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: common.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : common.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team and © 2001, 2003 The phpBB	Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if (defined('PHPBB_INSTALLED'))
{
	// This	means that common.php was already included
	return;
}

//
function unset_vars(&$var)
{
	foreach ($var as $var_name => $void)
	{
		unset($GLOBALS[$var_name]);
	}
	return;
}

function array_addslashes(&$array)
{
	foreach ($array as $k => $v)
	{
		if ( is_array($array[$k]))
		{
			array_addslashes($array[$k]);
		}
		else
		{
			$array[$k] = addslashes($v);
		}
	}
}
//



// Server Load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

/*** Storing for later use.
$endtime = explode (' ', microtime());
echo (( $endtime[0] + $endtime[1] ) - $starttime) . "<br />";
*/

error_reporting (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); //	Disable	magic_quotes_runtime

// Unset globally registered vars - PHP5 ... hhmmm
if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
{
	$var_prefix = 'HTTP';
	$var_suffix = '_VARS';

	$test = array('_GET', '_POST', '_SERVER', '_COOKIE', '_ENV');

	foreach ($test as $var)
	{
		if (is_array(${$var_prefix . $var . $var_suffix}))
		{
			unset_vars(${$var_prefix . $var . $var_suffix});
			@reset(${$var_prefix . $var . $var_suffix});
		}

		if (is_array(${$var}))
		{
			unset_vars(${$var});
			@reset(${$var});
		}
	}

	if (is_array(${'_FILES'}))
	{
		unset_vars(${'_FILES'});
		@reset(${'_FILES'});
	}

	if (is_array(${'HTTP_POST_FILES'}))
	{
		unset_vars(${'HTTP_POST_FILES'});
		@reset(${'HTTP_POST_FILES'});
	}
}

// PHP5 with register_long_arrays off?
if (!isset($HTTP_POST_VARS) && isset($_POST))
{
   $HTTP_POST_VARS = $_POST;
   $HTTP_GET_VARS = $_GET;
   $HTTP_SERVER_VARS = $_SERVER;
   $HTTP_COOKIE_VARS = $_COOKIE;
   $HTTP_ENV_VARS = $_ENV;
   $HTTP_POST_FILES = $_FILES;
}

//
// addslashes to vars if magic_quotes_gpc is off
// this	is a security precaution to	prevent	someone
// trying to break out of a	SQL	statement.
//
if (!get_magic_quotes_gpc())
{
	if (is_array($HTTP_GET_VARS))
	{
		array_addslashes($HTTP_GET_VARS);
	}

	if (is_array($HTTP_POST_VARS))
	{
		array_addslashes($HTTP_POST_VARS);
	}

	if (is_array($HTTP_COOKIE_VARS))
	{
		array_addslashes($HTTP_COOKIE_VARS);
	}

	if (is_array($_GET))
	{
		array_addslashes($_GET);
	}

	if (is_array($_POST))
	{
		array_addslashes($_POST);
	}

	if (is_array($_COOKIE))
	{
		array_addslashes($_COOKIE);
	}
}

//
// Define some basic configuration arrays this also	prevents
// malicious rewriting of language and otherarray values via
// URI params
//
$board_config =	array();
$userdata =	array();
$theme = array();
$images	= array();
$lang =	array();
$nav_links = array();
$gen_simple_header = FALSE;
$page_title = '';
$user_maps = array(); // A Proper fix should be made.

if (!isset($mvModuleTemplates) || !is_array($mvModuleTemplates))
{
	 $mvModuleTemplates = array();
}

@include($phpbb_root_path . 'config.'.$phpEx);

if (!defined('PHPBB_INSTALLED'))
{
	header("Location: install/install.$phpEx");
	exit;
}

include($phpbb_root_path . 'includes/constants.'.$phpEx);
include($phpbb_root_path . 'includes/template.'.$phpEx);
include($phpbb_root_path . 'includes/sessions.'.$phpEx);
include($phpbb_root_path . 'includes/auth.'.$phpEx);
include($phpbb_root_path . 'includes/functions.'.$phpEx);
include($phpbb_root_path . 'includes/acm/acm_' . $acm_type . '.'.$phpEx);
include($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);

if (!defined('SQL_LAYER'))
{
	message_die (CRITICAL_ERROR, 'No SQL Layer Present');
}

$cache = new acm();
$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);

if ( !$db->db_connect_id )
{
	message_die(CRITICAL_ERROR, 'Could not connect to the database');
}

/**
	Misc. Function Files
**/

if (defined('IN_CASHMOD'))
{
	include($phpbb_root_path . 'includes/functions_cash.'.$phpEx);
}
include($phpbb_root_path . 'includes/modules.'.$phpEx);
include($phpbb_root_path . 'attach/attachment_mod.'.$phpEx);

// I'm removing HTTP_X_FORWARDED_FOR ... this may well cause other problems such as
// private range IP's appearing instead of the guilty routable IP, tough, don't
// even bother complaining ... go scream and shout at the idiots out there who feel
// "clever" is doing harm rather than good ... karma is a great thing ... :)
//
$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );

$user_ip = encode_ip($client_ip);

//
// Setup forum wide	options, if	this fails
// then	we output a	CRITICAL_ERROR since
// basic forum information is not available
//
// re-cache	if necessary
//
if ($board_config =	$cache->get('board_config'))
{
	$sql = 'SELECT *
		FROM ' . CONFIG_TABLE .	'
		WHERE is_dynamic = 1';

	if (!($result =	$db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR,	'Could not query config	information', '', __LINE__,	__FILE__, $sql);
	}

	while ($row	= $db->sql_fetchrow($result))
	{
		$board_config[$row['config_name']] = $row['config_value'];
	}

	$db->sql_freeresult($result);
}
else
{
	$board_config =	$cached_board_config = array();

	$sql = 'SELECT *
		FROM ' . CONFIG_TABLE;

	if (!($result =	$db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR,	'Could not query config	information', '', __LINE__,	__FILE__, $sql);
	}

	while ($row	= $db->sql_fetchrow($result))
	{
		if (!$row['is_dynamic'])
		{
			$cached_board_config[$row['config_name']] =	$row['config_value'];
		}

		$board_config[$row['config_name']] = $row['config_value'];
	}

	$db->sql_freeresult($result);

	$cache->put('board_config',	$cached_board_config);
	unset($cached_board_config);
}

// Tidy	the	cache
if (method_exists($cache, 'tidy') && time()	- $board_config['cache_gc']	> $board_config['cache_last_gc'])
{
	$cache->tidy();
	set_config('cache_last_gc',	time(),	TRUE);
}

// Only through a error when we are outside of a sandbox.
if ((file_exists('install') || file_exists('contrib')) && !file_exists('CVS'))
{
    message_die(GENERAL_MESSAGE, 'Please ensure both the install/ and contrib/ directories are deleted');
}

if ( $board_config['auth_mode'] == 'ldap' )
{
	include($phpbb_root_path . 'includes/functions_ldap_groups.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_ldap.'.$phpEx);
}

$board_config['referers_on'] = TRUE; // Needs SQL and Config Option ## ToonArmy

if ( $board_config['referers_on'] && !empty($HTTP_POST_VARS['HTTP_REFERER']) )
{
	$referer_url = strtolower($HTTP_POST_VARS['HTTP_REFERER']);
	
	if ( strpos($referer_url, server_path_info(FALSE)) === FALSE  )
	{
		record_referer($referer_url);
	}
}

// Databae Optimize
if ( intval($board_config['cron_enable']) )
{
	if ( !intval($board_config['cron_lock']) && intval($board_config['cron_next']) <= time() )
	{
		include($phpbb_root_path . 'includes/optimize_database_cron.'.$phpEx);
	}
	elseif ( intval($board_config['cron_lock']) && ($board_config['cron_next'] + $board_config['cron_every']) <= time() )
	{
		// Unlock the Cron it must have got stuck.
		set_config('cron_lock', 0, 1);
	}
}
?>