<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: optimize_database_cron.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_database_cron.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Sko22
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

$current_time = time();


//
// Lock cron for contemporary accesses
//
if ( $sql = set_config('cron_lock', 1, 1) )
{
	message_die(GENERAL_ERROR, 'Could not lock optimize database cron', '', __LINE__, __FILE__, $sql);
}

$before_ignore = ignore_user_abort();
ignore_user_abort(TRUE);

//
// Get tables list
//
//$result = mysql_list_tables($dbname);

$sql = 'SHOW TABLE STATUS LIKE \'' . $table_prefix . '%\'';

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Couldn\'t obtain list of tables', '', __LINE__, __FILE__, $sql);
}

//
// Optimize tables
//

$tables = array();

while ( $table = $db->sql_fetchrow($result) )
{
	if ( intval($table['Data_free']) > 0 )
	{
		$tables[] = $table['Name'];
	}
}

$db->sql_freeresult($result);

if ( count($tables) > 0 )
{
	$sql = implode(', ', $tables);
	
	$sql = "OPTIMIZE TABLE $sql";
	
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Couldn\'t optimize database', '', __LINE__, __FILE__, $sql);
	}
}

unset($tables);

if ( $sql = set_config_arr(array('cron_next', 'cron_count'), array($current_time + $board_config['cron_every'], $board_config['cron_count'] + 1), array(1, 1)) )
{
	message_die(GENERAL_ERROR, 'Could not update optimize config', '', __LINE__, __FILE__, $sql);
}

ignore_user_abort($before_ignore);

//
// Unlock cron for contemporary accesses
//
if ( $sql = set_config('cron_lock', 0, 1) )
{
	message_die(GENERAL_ERROR, 'Could not unlock optimize database cron', '', __LINE__, __FILE__, $sql);
}


?>