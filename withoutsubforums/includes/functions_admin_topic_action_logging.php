<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_admin_topic_action_logging.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_admin_topic_action_logging.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003       Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

// mod: $mode, $forum_id, $topic_id, $action, $data.
// user: $mode, $reportee_id, $action, $data.
// else: $mode, $action, $data.

function add_log()
{
	global $db, $userdata, $lang, $user_ip;

	$args = func_get_args();

	$mode			= array_shift($args);
	$reportee_id	= ($mode == LOG_USER) ? intval(array_shift($args)) : '';
	$forum_id		= ($mode == LOG_MOD) ? intval(array_shift($args)) : '';
	$topic_id		= ($mode == LOG_MOD) ? intval(array_shift($args)) : '';
	$action			= array_shift($args);
	$data			= (!sizeof($args)) ? '' : $db->sql_escape(serialize($args));

	switch ($mode)
	{
		case LOG_ADMIN:
			$sql = 'INSERT INTO ' . LOG_TABLE . ' (log_type, user_id, log_ip, log_time, log_operation, log_data)
				VALUES (' . LOG_ADMIN . ', ' . $userdata['user_id'] . ", '$user_ip', " . time() . ", '$action', '$data')";
			break;

		case LOG_MOD:
			$sql = 'INSERT INTO ' . LOG_TABLE . ' (log_type, user_id, forum_id, topic_id, log_ip, log_time, log_operation, log_data)
				VALUES (' . LOG_MOD . ', ' . $userdata['user_id'] . ", $forum_id, $topic_id, '$user_ip', " . time() . ", '$action', '$data')";
			break;

		case LOG_USER:
			$sql = 'INSERT INTO ' . LOG_TABLE . ' (log_type, user_id, reportee_id, log_ip, log_time, log_operation, log_data)
				VALUES (' . LOG_USERS . ', ' . $userdata['user_id'] . ", $reportee_id, '$user_ip', " . time() . ", '$action', '$data')";
			break;

		case LOG_CRITICAL:
			$sql = 'INSERT INTO ' . LOG_TABLE . ' (log_type, user_id, log_ip, log_time, log_operation, log_data)
				VALUES (' . LOG_CRITICAL . ', ' . $userdata['user_id'] . ", '$user_ip', " . time() . ", '$action', '$data')";
			break;
		
		default:
			return;
	}

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['Error_Logs_Table'], '', __LINE__, __FILE__, $sql);
	}

	return;
}

?>