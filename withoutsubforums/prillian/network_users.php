<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: network_users.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : network_users.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Thoul
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------


if ( !defined('IN_PHPBB') || !defined('IN_PRILLIAN') )
{
	die('Hacking attempt');
}

$die_string = 'None';

// Session update to delete old sessions
$userdata = array();
$im_userdata['refresh_rate'] = $prill_config['refresh_rate'];
im_session_update(true, false, true);

$sql = 'SELECT u.username, u.user_id FROM ' . USERS_TABLE . ' u, ' . IM_SESSIONS_TABLE . ' s WHERE u.user_id = s.session_user_id AND u.user_allow_viewonline = 1 ORDER BY u.username ASC';

if( !$result = $db->sql_query($sql) )
{
	exit_the_network('Could not obtain user/online information', $sql);
}

$prillian_online = array();
$prev_user_id = 0;

while( $row = $db->sql_fetchrow($result) )
{
	// Skip multiple sessions for one user
	if ( $row['user_id'] != $prev_user_id )
	{
		$prillian_online[] = $row;
		$prev_user_id = $row['user_id'];
	}
}
$db->sql_freeresult($result);


// Send Prillian version number to template
echo $prill_config['version'];

if ( empty($prillian_online) )
{
	exit_the_network();
}
else
{
	foreach($prillian_online as $key=>$val)
	{
		// We won't show ignored users or ones the person can't contact
		echo "\n" . addslashes($val['username']);
		echo "\n" . $val['user_id'];
	}
	exit_the_network('no_msg');
}
?>