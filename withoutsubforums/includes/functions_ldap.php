<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_ldap.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_ldap.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003, 2004 Piotr Kuczyñski, Adam Larsen
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('LDAP_AUTH_OK', 1);
define('LDAP_INVALID_USERNAME', 2);
define('LDAP_INVALID_PASSWORD', 4);
define('ALLOW_BASIC_AUTH', true);

// ----------------------------------------------------
// ldap_auth()
//
// Authenticate user using LDAP directory
// ----------------------------------------------------
function ldap_auth ($username, $password)
{
	global $db, $board_config;

	if ($username == '' || ($password == '' && !ntlm_check()))
	{
		message_die(GENERAL, 'No Username and/or Password.  Can\'t authenticate', '', __LINE__, __FILE__, '');
	}

	// turn off reporting errors in case the password will be incorrect during binding
	$reporting = error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR );

	$connection = ldap_connect_ex();
	if ($connection == false)
	{
		message_die(GENERAL_ERROR, 'Could not connect to LDAP directory.', '', __LINE__, __FILE__, '');
	}
   	else
	{
		if ($board_config['ldap_proxy_dn'] != '')
		{
			$bind = ldap_bind($connection, $board_config['ldap_proxy_dn'], $board_config['ldap_proxy_dn_pass']);
		}
		else
		{
			$bind = ldap_bind($connection);
		}
		if ($bind == false)
		{
			message_die(GENERAL_ERROR, 'Could not bind to LDAP directory.', '', __LINE__, __FILE__, '');
		}
		else
		{
			$query = ldap_search($connection, $board_config['ldap_dn'], $board_config['ldap_uid'].'='. $username);
			if ($query == false)
			{
				message_die(GENERAL_ERROR, 'Could not perform query to LDAP directory.', '', __LINE__, __FILE__, 'User Name: ' . $board_config['ldap_uid'] . '=' . $username . ' Base DN: ' . $board_config['ldap_dn']);
			}
			else
			{
				$query_result = ldap_get_entries($connection, $query);

				if ($query_result['count'] != 1)
				{
					$result = LDAP_INVALID_USERNAME;
				}
				else
				{
					$userdn = $query_result[0]['dn'];
					$email = ' user_email =  \'' . str_replace("\'", "''", $query_result[0][$board_config['ldap_email']][0]) . '\', ';
					$web = ( $board_config['ldap_web'] != '' ) ? 'user_website = \'' . str_replace("\'", "''", $query_result[0][$board_config['ldap_web']][0]) . '\', ' : '';
					$location = ( $board_config['ldap_location'] != '' ) ? 'user_from = \'' . str_replace("\'", "''", $query_result[0][$board_config['ldap_location']][0]) . '\', ' : '';
					$occupation = ( $board_config['ldap_occupation'] != '' ) ? 'user_occ = \'' . str_replace("\'", "''", $query_result[0][$board_config['ldap_occupation']][0]) . '\', ' : '';
					$signature = ( $board_config['ldap_signature'] != '' ) ? 'user_sig = \'' . str_replace("\'", "''", $query_result[0][$board_config['ldap_signature']][0]) . '\', ' : '';

					// Update user
					$sql = "UPDATE " . USERS_TABLE . ' SET '
						. $email
						. $web
						. $occupation
						. $location
						. $signature;
					$sql = substr($sql,0,strlen($sql) - 2);
					$sql = $sql . " WHERE username = '" . $username . "'";

					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
					}
					if (ntlm_check())
					{
						//Logon using NTLM
						$result = LDAP_AUTH_OK;
					}
					else
					{
						ldap_close($connection);

						// bind using user's DN and given $password to check if the password is correct
						$connection = ldap_connect_ex();
						$bind = ldap_bind($connection, $userdn, $password);

						if ($bind == false or $password=='')
						{
							$result = LDAP_INVALID_PASSWORD;
						}
						else
						{
							$result = LDAP_AUTH_OK;
						}
					}
				}
			}
		}
	}

	ldap_close($connection);
	error_reporting($reporting);

	return $result;
}

// ----------------------------------------------------
// ldap_connect_ex()
//
// Connects to LDAP on specifing port, if it was configured
// using Authentication Settings in Control Panel
// ----------------------------------------------------
function ldap_connect_ex()
{
	global $board_config;

	if ($board_config['ldap_port'] != '')
	{
		$connection  = ldap_connect($board_config['ldap_host'], $board_config['ldap_port']);
		if (!$connection && $board_config['ldap_host2'] != '')
		{
			//Unable to connect the host 1, try host 2
			$connection  = ldap_connect($board_config['ldap_host2'], $board_config['ldap_port2']);
		}
	}
	else
	{
		$connection  = ldap_connect($board_config['ldap_host']);
		if (!$connection && $board_config['ldap_host2'] != '')
		{
			//Unable to connect the host 1, try host 2
			$connection  = ldap_connect($board_config['ldap_host2']);
		}
	}
	if ($board_config['ldap_start_tls'] != '0')
	{
		ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_start_tls($connection);
	}
	return $connection;
}

// ----------------------------------------------------
// add_ldap_user()
//
// Adds new user to phpBB database, basing on the information
// found in LDAP directory.
// ----------------------------------------------------
function add_ldap_user ($username)
{
	global $db, $board_config;

	// reading user informations from ldap
	$connection = ldap_connect_ex();
	if ($board_config['ldap_proxy_dn'] != '') {
		$bind = ldap_bind($connection, $board_config['ldap_proxy_dn'], $board_config['ldap_proxy_dn_pass']);
	}
	else
	{
		$bind = ldap_bind($connection);
	}
   	$query = ldap_search($connection, $board_config['ldap_dn'], $board_config['ldap_uid'].'='.$username);
	$query_result = ldap_get_entries($connection, $query);

	$email = $query_result[0][$board_config['ldap_email']][0];
	$web = $query_result[0][$board_config['ldap_web']][0];
	$location = $query_result[0][$board_config['ldap_location']][0];
	$occupation = $query_result[0][$board_config['ldap_occupation']][0];
	$signature = $query_result[0][$board_config['ldap_signature']][0];

	ldap_close($connection);

	// obtaining new user id
	$sql = "SELECT MAX(user_id) AS total
		FROM " . USERS_TABLE;

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
	}
	$user_id = $row['total'] + 1;

	// creating new user
	$sql = "INSERT INTO " . USERS_TABLE .
		"( user_id, "
		. "username, "
		. "user_regdate, "
		. "user_password, "
		. "user_email, "
		. "user_website, "
		. "user_occ, "
		. "user_from, "
		. "user_sig, "
		. "user_viewemail, "
		. "user_attachsig, "
		. "user_allowsmile, "
		. "user_allowhtml, "
		. "user_allowbbcode, "
		. "user_allow_viewonline, "
		. "user_notify, "
		. "user_notify_pm, "
		. "user_popup_pm, "
		. "user_timezone, "
		. "user_dateformat, "
		. "user_lang, "
		. "user_style, "
		. "user_level, "
		. "user_allow_pm, "
		. "user_active,"
		. "user_type"
		.")"
	."VALUES ("
		. "$user_id, "															// user_id
		. "'" . str_replace("\'", "''", $username) . "', "						// username
		. time() . ", "															// user_regdate
		. "'', "																// user_password
		. "'" . str_replace("\'", "''", $email) . "', "							// user_email
		. "'" . str_replace("\'", "''", $web) . "', "							// user_website
		. "'" . str_replace("\'", "''", $occupation) . "', "					// user_occ
		. "'" . str_replace("\'", "''", $location) . "', "						// user_from
		. "'" . str_replace("\'", "''", $signature) . "', "						// user_sig
		. "1, "																	// user_viewemail
		. $board_config['allow_sig'] . ", "										// user_attachsig
		. $board_config['allow_smilies'] . ", "									// user_allowsmile
		. $board_config['allow_html'] . ", "									// user_allowhtml
		. $board_config['allow_bbcode'] . ", "									// user_allowbbcode
		. "1, "																	// user_allow_viewonline
		. "0, "																	// user_notify
		. "1, "																	// user_notify_pm
		. "1, "																	// user_popup_pm
		. $board_config['board_timezone'] . ", "								// user_timezone
		. "'" . $board_config['default_dateformat'] . "', "						// user_dateformat
		. "'" . $board_config['default_lang'] . "', "							// user_lang
		. $board_config['default_style'] . ", "									// user_style
		. "0, "																	// user_level
		. "1, "																	// user_allow_pm
		. "1, "
		. User_Type_LDAP . " "																// user_active
		.")";

	if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
	}

	// creating new 'personal user' group
	$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
		VALUES ('', 'Personal User', 1, 0)";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
	}

	$group_id = $db->sql_nextid();

	// assigning new user to the new 'personal user' group
	$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
		VALUES ($user_id, $group_id, 0)";
	if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
	}
}

function ntlm_check ()
{
	global $board_config;
	if (isset($_SERVER['AUTH_TYPE']) && (($_SERVER['AUTH_TYPE'] == 'Basic' && ALLOW_BASIC_AUTH) || $_SERVER['AUTH_TYPE'] == 'NTLM' || $_SERVER['AUTH_TYPE'] == 'Negotiate') && $board_config['auth_mode'] == 'ldap')
	{
		return true;
	}
	else
	{
		return false;
	}
}

function ntlm_get_user($skip = FALSE)
{
	if ( $skip || ntlm_check())
	{
		$ntlm_user = $_SERVER['REMOTE_USER'];
		$strloc = strpos($ntlm_user,"\\");
		$strloc++;
		if (substr($ntlm_user, $strloc, 1) == "\\" )
		{
			$strloc++;
		}
		if ($strloc > 2)
		{
			$username = substr($ntlm_user, $strloc);
		}
		else
		{
			$username = $ntlm_user;
		}
		return $username;
	}
	else
	{
		return false;
	}
}

?>
