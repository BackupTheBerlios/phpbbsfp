<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: sessions.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : sessions.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/*
Once this class is made standard to Minerva there are several things that require
chnaging. I will note these below:

	- $userdata
		The $userdata array will be phased out and replaced by $user->data,
		there is no difference between the contents of these arrays.

	- $userdata = session_pagestart($user_ip, PAGE_INDEX);
		Line used for starting the session on each page this should be replaced with
		$user->start(PAGE_INDEX);.

	- session_begin(...);
		Only a few instances of this around, should be replaced with
		$user->create($user_id, $autologin, $enable_autologin, PAGE_INDEX);.

	- session_end($session_id, $user_id);
		This is replaced with $user->destroy();.

	- init_userprefs($userdata);
		Another very important function call but again it is replaced with
		$user->setup();. Two parameters can be passed to this function, first
		an array of lang files to be included or a string containing one.
		lang_main and lang_admin are done automagically, including for modules.

	- setup_style($style);
		Replaced with $user->setup_style($style);

	- Language Files Inclusion
		Inclusion of language files is done from one function now ($user->add_lang();),
		see the functions parameter explanation in this file.

	- $board_config
		We do not edit the values of $board_config (eg.: $board_config['default_lang']),
		these settings should reflect the boards settings.

	- create_date($format, $gmepoch, $tz);
		Is replaced with $user->format_date($gmepoch, $format);, I may shuffle the
		parameters around later. Note the timezone is stord in $user->tz

	- $lang
		This array is no longer populated and we use $user->lang instead.
		Important :: Lang files still use $lang.

	- $theme
		Replaced by $user->theme perhaps.

We now have built in support for bots to crawl users websites, all configured bots
will have their SID's removed. Bots are matched by IP and UserAgent so it is
very hard to have users prancing around without SID's. Each bot can have a different
language and style, such as a low graphics version.

There is also support for an extra layer of protection for sessions, this class can
now check that the useragents match up.

The sessions table has its garbage removed by default every hour this will be ACP
configurable.

The new banning system (not done) is very powerful. Time lenghts can be added to bans,
and reasons can be given. The Admin can choose whether the user is told why they are banned
or just keep the reason a secret. The Admin can also exclude certain IP's, usernames or
emails from being banned.

Class variables, some of the new vars.

$user->session_id		- (string) holds the full session id.
$user->session_method	- (integer) method of the session (GET/Cookie). [I may remove this one]
$user->data				- (array) replacement for $userdata.
$user->browser			- (string) stores the users browser string. Currently trimmed to 255 Chars.
$user->ip				- (int) encoded ip of the remote user.
$user->page				- (string) non-documented.
$user->page_id			- (int) page id passed to the setup function.
$user->load				- (int) un-documented.


$user->lang				- (array) Replacement for $lang.
$user->theme			- (array) Replacement for $theme.
$user->date_format		- (string) Users dateformat.
$user->timezone			- (int) Timezone of user.
$user->dst;				- (int) Day Light Saving Time, Unused.

$user->lang_name		- (string) Replacement for $board_config['default_lang']
$user->lang_path		- (string) Full path to language files.
$user->lang_module_path - (string) Full path to module language files. Contains a replacement.
$user->img_lang			- (string) Path to image languages.


*/


// SQL

/*
	ALTER TABLE phpbb_sessions CHANGE 'useragent' 'session_browser' VARCHAR( 255 ) NOT NULL;
	ALTER TABLE phpbb_sessions ADD 'session_last_visit' INT( 11 ) DEFAULT '0' NOT NULL;
	INSERT INTO phpbb_config ( config_name, config_value ) VALUES ( 'browser_check', 0 );
	INSERT INTO phpbb_config ( config_name, config_value ) VALUES ( 'ip_check', 6 );
	INSERT INTO phpbb_config ( config_name, config_value ) VALUES ( 'session_gc', 3600 );
	INSERT INTO phpbb_config ( config_name, config_value, is_dynamic ) VALUES ( 'session_last_gc', 0, 1 );

	DROP TABLE phpbb_banlist;
	CREATE TABLE phpbb_banlist (
	   ban_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	   ban_userid mediumint(8) DEFAULT 0 NOT NULL,
	   ban_ip varchar(8) DEFAULT '' NOT NULL,
	   ban_email varchar(255) DEFAULT '' NOT NULL,
	   ban_start int(11) DEFAULT '0' NOT NULL,
	   ban_end int(11) DEFAULT '0' NOT NULL,
	   ban_exclude tinyint(1) DEFAULT '0' NOT NULL,
	   ban_reason varchar(255) DEFAULT '' NOT NULL,
	   ban_show_reason tinyint(1) DEFAULT 0 NOT NULL,
	   PRIMARY KEY (ban_id)
	);
	
	CREATE TABLE phpbb_bots (
	  bot_id tinyint(3) UNSIGNED NOT NULL auto_increment,
	  bot_active tinyint(1) DEFAULT 1 NOT NULL,
	  bot_name varchar(255) DEFAULT '' NOT NULL,
	  user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	  bot_agent varchar(255)  DEFAULT '' NOT NULL,
	  bot_ip varchar(255) DEFAULT '' NOT NULL,
	  PRIMARY KEY  (bot_id),
	  KEY bot_active (bot_active)
	);
*/

// BUGS

/*
	Weirdness once registered :(
*/


// TODO

/*
	garbage collection - DONE
	remove duplicate code - ALMOST
	
	Robots! - DONE (EX. ACP)
	Migration of $userdata, $lang and session/userdata funcs
*/

/* NOTES :: This is a incomplete sessions.php, to use 
	remove init_userprefs() from includes/functions.php.
	Any feedback will be cool :)

	Dave, The wrapper funcs will go!
*/

define ('BROWSER_MAX_LEN', 255);
define ('BOTS_TABLE', $table_prefix . 'bots');
if ( defined('IN_ADMIN') )
{
	define('NEED_SID', TRUE);
}
$board_config['browser_check'] = 1;
$board_config['ip_check'] = 6;
/*
$this->lang += array(
	'BOARD_BAN_PERM'		=> 'You have been <b>permanently</b> banned from this board.<br /><br />Please contact the %2$sBoard Administrator%3$s for more information.',
	'BOARD_BAN_REASON'		=> 'Reason given for ban: <b>%s</b>',
	'BOARD_BAN_TIME'		=> 'You have been banned from this board until <b>%1$s</b>.<br /><br />Please contact the %2$sBoard Administrator%3$s for more information.',
);
*/
function init_userprefs($userdata)
{
	global $user;
	return $user->setup();
}

//
// Adds/updates a new session to the database for the given userid.
// Returns the new session ID on success.
//
function session_begin($user_id, $user_ip, $page_id, $auto_create = 0, $enable_autologin = 0)
{
	global $user;

	$enable_autologin = (bool) $enable_autologin;
/*******/
	$autologin = ( $enable_autologin ) ? md5($_POST['password']) : ''; // TEMP!!
/*******/
	return $user->create($user_id, $autologin, $enable_autologin, $page_id);
}

//
// Checks for a given user session, tidies session table and updates user
// sessions at each page refresh
//
function session_pagestart($user_ip, $thispage_id)
{
	global $user;
	return $user->start($thispage_id);
}

//
// session_end closes out a session
// deleting the corresponding entry
// in the sessions table
//
function session_end($session_id, $user_id)
{
	global $user;
	return $user->destroy();
}

class session
{
	var $session_id = '';
	var $session_method = 0;
	var $data = array();
	var $browser = '';
	var $ip = '';
	var $page = '';
	var $page_id = '';
	var $load;

	// create
	function create($user_id, $autologin, $set_autologin = FALSE, $page_id)
	{
		global $SID, $db, $board_config;
		
		$sessiondata = array();
		$current_time = time();
		$bot = FALSE;

		// Pull bot information from DB and loop through it
		$sql = 'SELECT user_id, bot_agent, bot_ip 
			FROM ' . BOTS_TABLE . '  
			WHERE bot_active = 1';

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, 'Could not obtain bot data from user table', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['bot_agent'] && preg_match('#' . preg_quote($row['bot_agent'], '#') . '#i', $this->browser))
			{
				$bot = $row['user_id'];
			}
			if ($row['bot_ip'] && (!$row['bot_agent'] || $bot))
			{
				foreach (explode(',', $row['bot_ip']) as $bot_ip)
				{
					if (strpos($this->ip, $bot_ip) === 0)
					{
						$bot = $row['user_id'];
						break;
					}
				}
			}

			if ($bot)
			{
				$user_id = $bot;
				break;
			}
		}
		$db->sql_freeresult($result);

		// Garbage collection ... remove old sessions updating user information
		// if necessary. It means (potentially) 11 queries but only infrequently
		if ($current_time > $board_config['session_last_gc'] + $board_config['session_gc'])
		{
			$this->gc($current_time);
		}

		// Grab user data ... join on session if it exists for session time
		$sql = 'SELECT u.*, s.session_time, s.session_id
			FROM (' . USERS_TABLE . ' u
			LEFT JOIN ' . SESSIONS_TABLE . " s ON s.session_user_id = u.user_id)
			WHERE u.user_id = $user_id
			ORDER BY s.session_time DESC
			LIMIT 1";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, 'Could not obtain lastvisit data from user table', '', __LINE__, __FILE__, $sql);
		}

		$this->data = $db->sql_fetchrow($result);

		$db->sql_freeresult($result);

		// Check autologin request, is it valid?
		if (empty($this->data) || ($this->data['user_password'] != $autologin && $set_autologin) || (!$this->data['user_active'] && !$bot))
		{
			$autologin = '';
			$this->data['user_id'] = $user_id = ANONYMOUS;
		}

		$logged_in = ( $user_id == ANONYMOUS || $this->data['user_id'] == ANONYMOUS ) ? FALSE : TRUE;

		// If we're a bot then we'll re-use an existing id if available
		if ($bot && $this->data['session_id'])
		{
			$this->session_id = $this->data['session_id'];
		}

/*
		Session Limits
*/

		// Is user banned? Are they excluded?
		if ( $this->data['user_id'] != 2 && !$bot )
		{
		/*	$banned = false;

			$sql = 'SELECT ban_ip, ban_userid, ban_email, ban_exclude, ban_reason, ban_show_reason, ban_end  
				FROM ' . BANLIST_TABLE . '
				WHERE ban_end >= ' . time() . '
					OR ban_end = 0';

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(CRITICAL_ERROR,	'Could not obtain ban data', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					if (
						(!empty($row['ban_userid']) && intval($row['ban_userid']) == $this->data['user_id']) || 
						(!empty($row['ban_ip']) && preg_match('#^' . str_replace('*', '.*?', $row['ban_ip']) . '$#i', $this->ip)) || 
						(!empty($row['ban_email']) && preg_match('#^' . str_replace('*', '.*?', $row['ban_email']) . '$#i', $this->data['user_email']))
					)
					{
						if ( $row['ban_exclude'] )
						{
							$banned = false;
							break;
						}
						else
						{
							$banned = true;
							$ban_row = $row;
						}
					}
					
				}
				while ($row = $db->sql_fetchrow($result));
				
			}

			$db->sql_freeresult($result);
			$row = $ban_row;
			if ($banned)
			{
				// Initiate environment ... since it won't be set at this stage
				$this->setup();

				// Determine which message to output 
				$till_date = (!empty($row['ban_end'])) ? $this->format_date($row['ban_end']) : '';
				$message = (!empty($row['ban_end'])) ? 'BOARD_BAN_TIME' : 'BOARD_BAN_PERM';
	
				$message = sprintf($this->lang[$message], $till_date, '<a href="mailto:' . $board_config['board_email'] . '">', '</a>');
				// More internal HTML ... :D

				$message .= ( intval($row['ban_show_reason']) ) ? '<br /><br />' . sprintf($this->lang['BOARD_BAN_REASON'], $row['ban_reason']) : '';
				message_die(GENERAL_MESSAGE, $message, $this->lang['Banned']);
			}*/
		}

		// Is there an existing session? If so, grab last visit time from that
		$this->data['session_last_visit'] = ($this->data['session_time']) ? $this->data['session_time'] : (($this->data['user_lastvisit']) ? $this->data['user_lastvisit'] : time());

		$sql = 'UPDATE ' . SESSIONS_TABLE . "
			SET session_user_id = $user_id, session_last_visit = " . $this->data['session_last_visit'] . ", session_start = $current_time, session_time = $current_time, session_browser = '" . $db->sql_escape($this->browser) . "', session_page = '" . $db->sql_escape($this->page_id) . "', session_logged_in = '$logged_in'
			WHERE session_id = '" . $db->sql_escape($this->session_id) . "'";

		if ($this->session_id == '' || !$db->sql_query($sql) || !$db->sql_affectedrows())
		{
			$this->session_id = md5(uniqid($this->ip));

			$err = $db->sql_error(); if ($err['code'] != 0) print_r ($err);

			$sql = 'INSERT INTO ' . SESSIONS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
				'session_id'				=> (string) $this->session_id,
				'session_user_id'			=> (int) $user_id,
				'session_start'				=> (int) $current_time, 
				'session_time'				=> (int) $current_time, 
				'session_ip'				=> (string) $this->ip,
				'session_browser'			=> (string) $this->browser,
				'session_page'				=> (string) $this->page,
				'session_logged_in'			=> (int) $logged_in,
				'session_last_visit'		=> (int) $this->data['session_last_visit']
			));
			
			if ( !$db->sql_query($sql) )
			{
				message_die(CRITICAL_ERROR, 'Error creating new session', '', __LINE__, __FILE__, $sql);
			}
		}

		// Be Picky with SID's
		if ( !preg_match('/^[A-Za-z0-9]*$/', $this->session_id) )
		{
			$this->session_id = '';
		}

		if (!$bot)
		{
			$this->data['session_id'] = $this->session_id;

			$sessiondata['autologinid'] = ($autologin && $user_id != ANONYMOUS) ? $autologin : '';
			$sessiondata['userid'] = $user_id;

			$this->set_cookie('data', serialize($sessiondata), $current_time + 31536000);
			$this->set_cookie('sid', $this->session_id, 0);
			$SID = ( $this->session_method == SESSION_METHOD_GET ) ? 'sid=' . $this->session_id : '';

			if ($this->data['user_id'] != ANONYMOUS)
			{
				// Trigger EVT_NEW_SESSION
			}
		}
		else
		{
			$SID = ''; // No SID's for bots!
		}

		return $this->data;

		if ( $user_id != ANONYMOUS )
		{
			// Done Else Where!
			$last_visit = ( $this->data['user_session_time'] > 0 ) ? $this->data['user_session_time'] : $current_time;
	
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_session_time = $current_time, user_session_page = $page_id, user_lastvisit = $last_visit
				WHERE user_id = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(CRITICAL_ERROR, 'Error updating last visit time', '', __LINE__, __FILE__, $sql);
			}
	
			$this->data['user_lastvisit'] = $last_visit;
	
			$sessiondata['autologinid'] = ( $enable_autologin && $sessionmethod == SESSION_METHOD_COOKIE ) ? $auto_login_key : '';
			$sessiondata['userid'] = $user_id;
		}
	
		$this->data['session_id']			= $this->session_id;
		$this->data['session_ip']			= $this->ip;
		$this->data['session_user_id']		= $user_id;
		$this->data['session_logged_in']	= $login;
		$this->data['session_page']			= $this->page_id;
		$this->data['session_start']		= $current_time;
		$this->data['session_time']			= $current_time;
	
		return $this->data;
	}

	function start($page_id) // start
	{
		global $db, $board_config, $SID, $mvModuleName;
	
		$current_time = time();

		if ( isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT']) )
		{
			$this->browser = $_SERVER['HTTP_USER_AGENT'];
		}
		elseif ( isset($_ENV['HTTP_USER_AGENT']) && !empty($_ENV['HTTP_USER_AGENT']) )
		{
			$this->browser = $_ENV['HTTP_USER_AGENT'];
		}
		else
		{
			$this->browser = 'Unknown';
		}

		if ( strlen($this->browser) > BROWSER_MAX_LEN )
		{
			$this->browser = substr($this->browser, 0, BROWSER_MAX_LEN);
		}

		if ( isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) )
		{
			$this->page = $_SERVER['REQUEST_URI'];
		}
		elseif ( isset($_ENV['REQUEST_URI']) && !empty($_ENV['REQUEST_URI']) )
		{
			$this->page = $_ENV['REQUEST_URI'];
		}
		else
		{
			$this->page = $_SERVER['PHP_SELF'];
		}

		if (!defined('IN_ADMIN') && $mvModuleName != '')
		{
			$page_id = constant('MODULE_' . $mvModuleName . '_' . $page_id);
		}

		$this->page_id = intval($page_id);

		unset($page_id);
	
		$cookiename = $board_config['cookie_name'];
		
		if ( isset($_COOKIE[$cookiename . '_sid']) || isset($_COOKIE[$cookiename . '_data']) )
		{
			$sessiondata = isset($_COOKIE[$cookiename . '_data'] ) ? unserialize(stripslashes($_COOKIE[$cookiename . '_data'])) : array();
			$this->session_id = isset($_COOKIE[$cookiename . '_sid']) ? $_COOKIE[$cookiename . '_sid'] : '';
			$this->session_method = SESSION_METHOD_COOKIE;
			$SID = (defined('NEED_SID')) ? 'sid=' . $this->session_id : '';
		}
		else
		{
			$sessiondata = array();
			$this->session_id = ( isset($_GET['sid']) ) ? $_GET['sid'] : '';
			$this->session_method = SESSION_METHOD_GET;
			$SID = 'sid=' . $this->session_id;
		}
		
		if (!preg_match('/^[A-Za-z0-9]*$/', $this->session_id))
		{
			$this->session_id = '';
		} 
		
		$this->ip = ( isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : getenv('REMOTE_ADDR');

		if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
		{
			$private_ip = array('#^0\.#', '#^127\.0\.0\.1#', '#^192\.168\.#', '#^172\.16\.#', '#^10\.#', '#^224\.#', '#^240\.#');
			$bits = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ip_list = array();
			foreach ($bits as $x_ip)
			{
				if (preg_match('#([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)#', $x_ip, $ip_list))
				{
					if (($this->ip = trim(preg_replace($private_ip, $this->ip, $ip_list[1]))) == trim($ip_list[1]))
					{
						break;
					}
				}
			}
		}

		$this->ip = encode_ip($this->ip);

/*
		Load Limit
*/
		
		//
		// Does a session exist?
		//
		if ( !empty($this->session_id) && (!defined('NEED_SID') || $this->session_id == (( isset($_GET['sid']) ) ? $_GET['sid'] : '')) )
		//if ( !empty($this->session_id) )
		{
			//
			// session_id exists so go ahead and attempt to grab all
			// data in preparation
			//
			$sql = "SELECT u.*, s.*
				FROM " . SESSIONS_TABLE . " s, " . USERS_TABLE . " u
				WHERE s.session_id = '" . $db->sql_escape($this->session_id) . "'
					AND u.user_id = s.session_user_id";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(CRITICAL_ERROR, 'Error doing DB query userdata row fetch', '', __LINE__, __FILE__, $sql);
			}

			$this->data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			//
			// Did the session exist in the DB?
			//
			if ( isset($this->data['user_id']) )
			{
				// Validate IP length according to admin ... has no effect on IPv6
				$s_ip = substr($this->data['session_ip'], 0, $board_config['ip_check']);
				$u_ip = substr($this->ip, 0, $board_config['ip_check']);

				//$s_ip = implode('.', array_slice(explode('.', $this->data['session_ip']), 0, $board_config['ip_check']));
				//$u_ip = implode('.', array_slice(explode('.', $this->ip), 0, $board_config['ip_check']));

				$s_browser = ( $board_config['browser_check'] ) ? $this->data['session_browser'] : '';
				$u_browser = ( $board_config['browser_check'] ) ? $this->browser : '';

				if ($s_ip == $u_ip && $s_browser == $u_browser)
				{

					// Only update session DB a minute or so after last update or if page changes
					if ($current_time - $this->data['session_time'] > 60 || $this->data['session_page'] != $this->page_id)
					{
						$sql = 'UPDATE ' . SESSIONS_TABLE . "
							SET session_time = $current_time, session_page = '" . $db->sql_escape($this->page_id) . "'
							WHERE session_id = '" . $db->sql_escape($this->session_id) . "'";

						if ( !$db->sql_query($sql) )
						{
							message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
						}

						if ( $this->data['user_id'] != ANONYMOUS ) // Want to remove this soon!
						{
							$sql = 'UPDATE ' . USERS_TABLE . "
								SET user_session_time = $current_time, user_session_page = '" . $this->page_id . "'
								WHERE user_id = '" . $this->data['user_id'] . "'";
							if ( !$db->sql_query($sql) )
							{
								message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
							}
						}
					}

					return $this->data;
					//return true; // Once no more $userdata
				}
				else
				{
//die ('Session Hijacking');
				}
			}
		}
		
		//
		// If we reach here then no (valid) session exists. So we'll create a new one,
		// using the cookie user_id if available to pull basic user prefs.
		//
		$autologin = ( isset($sessiondata['autologinid']) ) ? $sessiondata['autologinid'] : '';
		$user_id = ( isset($sessiondata['userid']) ) ? intval($sessiondata['userid']) : ANONYMOUS;

		if ( !($this->data = $this->create($user_id, $autologin, FALSE, $this->page_id)) )
		{
			message_die(CRITICAL_ERROR, 'Error creating user session', '', __LINE__, __FILE__, $sql);
		}
		
		return $this->data;
	}

	function destroy()
	{
		global $db,	$board_config, $SID;

		$current_time = time();

		$this->set_cookie('data', '', $current_time - 31536000);
		$this->set_cookie('sid', '', $current_time - 31536000);

		$SID = ( $this->session_method == SESSION_METHOD_GET ) ? 'sid=' : '';

		// Delete existing session, update last visit info first!
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_session_time = $current_time, user_session_page = " . $this->page_id . ', user_lastvisit = ' . $this->data['session_time'] . '
			WHERE user_id = ' . $this->data['user_id'];

		if ( !$db->sql_query($sql) )
		{
			message_die(CRITICAL_ERROR, 'Error updating last visit time', '', __LINE__, __FILE__, $sql);
		}

		if (!preg_match('/^[A-Za-z0-9]*$/', $this->session_id))
		{
			return;
		}
	
		//
		// Delete existing session
		//
		$sql = "DELETE FROM " . SESSIONS_TABLE . "
			WHERE session_id = '" . $this->session_id . "'
				AND session_user_id = '" . $this->data['user_id'] . "'";
		if ( !$db->sql_query($sql) )
		{
			message_die(CRITICAL_ERROR, 'Error removing user session', '', __LINE__, __FILE__, $sql);
		}

		$this->session_id = '';
	
		return true;
	}

	// Garbage collection
	function gc($current_time)
	{
		global $db, $board_config;

		switch (SQL_LAYER)
		{
			case 'mysql4':
				// Firstly, delete guest sessions
				$sql = 'DELETE FROM ' . SESSIONS_TABLE . '
					WHERE session_user_id = ' . ANONYMOUS . '
						AND session_time < ' . ($current_time - $board_config['session_length']);

				if ( !$db->sql_query($sql) )
				{
					message_die(CRITICAL_ERROR, 'Error removing guest sessions', '', __LINE__, __FILE__, $sql);
				}

				// Keep only the most recent session for each user
				// Note: if the user is currently browsing the board, his
				// last_visit field won't be updated, which I believe should be
				// the normal behavior anyway

				$sql = 'DELETE FROM ' . SESSIONS_TABLE . '
					USING ' . SESSIONS_TABLE . ' s1, ' . SESSIONS_TABLE . ' s2
					WHERE s1.session_user_id = s2.session_user_id
						AND s1.session_time < s2.session_time';
				$db->sql_query($sql); // Cont. on error

				// Update last visit time
				$sql = 'UPDATE ' . USERS_TABLE. ' u, ' . SESSIONS_TABLE . ' s
					SET u.user_lastvisit = s.session_time, u.user_session_page = s.session_page
					WHERE s.session_time < ' . ($current_time - $board_config['session_length']) . '
						AND u.user_id = s.session_user_id';
				if ( !$db->sql_query($sql) )
				{
					message_die(CRITICAL_ERROR, 'Could not update last visit time', '', __LINE__, __FILE__, $sql);
				}

				// Delete everything else now
				$sql = 'DELETE FROM ' . SESSIONS_TABLE . '
					WHERE session_time < ' . ($current_time - $board_config['session_length']);
				if ( !$db->sql_query($sql) )
				{
					message_die(CRITICAL_ERROR, 'Error removing old sessions', '', __LINE__, __FILE__, $sql);
				}

				set_config('session_last_gc', $current_time);
				break;

			default:

				// Get expired sessions, only most recent for each user
				$sql = 'SELECT session_user_id, session_page, MAX(session_time) AS recent_time
					FROM ' . SESSIONS_TABLE . '
					WHERE session_time < ' . ($current_time - $board_config['session_length']) . '
					GROUP BY session_user_id, session_page
					LIMIT 5';
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(CRITICAL_ERROR, 'Error selecting expired sessions', '', __LINE__, __FILE__, $sql);
				}

				$del_user_id = '';
				$del_sessions = 0;
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						if ($row['session_user_id'] != ANONYMOUS)
						{
							$sql = 'UPDATE ' . USERS_TABLE . '
								SET user_lastvisit = ' . $row['recent_time'] . ", user_session_page = '" . $db->sql_escape($row['session_page']) . "' 
								WHERE user_id = " . $row['session_user_id'];
							if ( !$db->sql_query($sql) )
							{
								message_die(CRITICAL_ERROR, 'Error updating last visit data', '', __LINE__, __FILE__, $sql);
							}	
						}

						$del_user_id .= (($del_user_id != '') ? ', ' : '') . $row['session_user_id'];
						$del_sessions++;
					}
					while ($row = $db->sql_fetchrow($result));
				}

				if ($del_user_id != '')
				{
					// Delete expired sessions
					$sql = 'DELETE FROM ' . SESSIONS_TABLE . "
						WHERE session_user_id IN ($del_user_id)
							AND session_time < " . ($current_time - $board_config['session_length']);
					if ( !$db->sql_query($sql) )
					{
						message_die(CRITICAL_ERROR, 'Error removing expired sessions', '', __LINE__, __FILE__, $sql);
					}
				}

				if ($del_sessions < 5)
				{
					// Less than 5 sessions, update gc timer ... else we want gc
					// called again to delete other sessions
					set_config('session_last_gc', $current_time);
				}
				break;
		}

		return;
	}


	// Set a cookie
	function set_cookie($name, $cookiedata, $cookietime)
	{
		global $board_config;

		setcookie($board_config['cookie_name'] . '_' . $name, $cookiedata, $cookietime, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
	}
}

class user extends session
{
	var $lang = array();
	var $theme = array();
	var $date_format;
	var $timezone;
	var $dst; // Needs Some Code

	var $lang_name;
	var $lang_path;
	var $lang_module_path;
	var $img_lang;

	//var $keyoptions = array('viewimg' => 0, 'viewflash' => 1, 'viewsmilies' => 2, 'viewsigs' => 3, 'viewavatars' => 4, 'viewcensors' => 5, 'attachsig' => 6, 'html' => 7, 'bbcode' => 8, 'smile' => 9, 'popuppm' => 10);
	//var $keyvalues = array();

	function setup($lang_set = false, $style = false)
	{
		global $db, $template, $theme, $board_config, $phpEx, $phpbb_root_path, $mvModules; 
		global $nav_links;

		if ($this->data['user_id'] != ANONYMOUS)
		{
			$this->lang_name = (file_exists($phpbb_root_path . 'language/lang_' . $this->data['user_lang'] . "/lang_main.$phpEx")) ? $this->data['user_lang'] : $board_config['default_lang'];
			$this->date_format = $this->data['user_dateformat'];
			$this->timezone = $this->data['user_timezone'] * 3600;
			$this->dst = 0; // Here (Dave Leave Here)
		}
		else
		{
			$this->lang_name = $board_config['default_lang'];
			$this->date_format = $board_config['default_dateformat'];
			$this->timezone = $board_config['board_timezone'] * 3600;
			$this->dst = 0; // Here (Dave Leave Here)
		}

		$this->lang_path = $phpbb_root_path . 'language/lang_' . $this->lang_name . '/';
		$this->lang_module_path = $phpbb_root_path . 'modules/%s/language/lang_' . $this->lang_name . '/';

		if ( $board_config['override_user_style'] || $this->data['user_id'] == ANONYMOUS)
		{
			// Force Default or we are a Guest
			$style = $board_config['default_style'];
		}
		elseif ( isset($_GET['style']) && $_GET['style'] != $style )
		{
			// Style ID Passed via GET takes priority

			global $SID;

			$style = intval($_GET['style']);

			$SID .= '&amp;style=' . $style;
		}
		elseif ( $style === FALSE )
		{
			$style = $this->data['user_style'];
		}

		$inc_langs = array(); // Languages We Need to Include.

		$inc_langs[] = 'main';
		// $inc_langs[] = 'rate'; // HOW UGLY!!!!!!!!!!!!!!!!!!!!!!!!!

		if ( defined('IN_ADMIN') )
		{
			$inc_langs[] = 'admin';
		}

		if ( defined('IN_CASHMOD') )
		{
			$inc_langs[] = 'cash';
		}

		foreach ($mvModules as $name => $value)
		{
			if ($value['state'] != 1 && $value['state'] != 5)
			{
				continue;
			}

			$inc_langs[] = "$name:main";

			if ( defined('IN_ADMIN') )
			{
				$inc_langs[] = "$name:admin";
			}
		}

		$this->add_lang($inc_langs);
		$this->add_lang($lang_set);
		$this->lang_extend();
		$theme = $this->setup_style($style);
		global $mods, $list_yes_no;
	
		//  get all the mods settings
		$dir = @opendir($phpbb_root_path . 'includes/mods_settings');
		while( ($file = @readdir($dir)) !== FALSE )
		{
			if( preg_match("/^mod_.*?\." . $phpEx . "$/", $file) )
			{
				include_once($phpbb_root_path . 'includes/mods_settings/' . $file);
			}
		}
		@closedir($dir);

		global $admin_level, $level_prior, $level_desc;
		global $values_list, $tables_linked, $classes_fields, $user_maps, $user_fields;
		global $list_yes_no;
	
		include_once($phpbb_root_path . 'profilcp/functions_profile.' . $phpEx);
		$endtime = explode (' ', microtime());
		//echo (( $endtime[0] + $endtime[1] ) - $begin_t) . "<br />";
		//echo $db->num_queries - $b4;
		unset($endtime);

		//
		// Mozilla navigation bar
		// Default items that should be valid on all pages.
		// Defined here to correctly assign the Language Variables
		// and be able to change the variables within code.
		//
		$nav_links['top'] = array (
			'url' => append_sid($phpbb_root_path . 'index.' . $phpEx),
			'title' => sprintf($this->lang['Forum_Index'], $board_config['sitename'])
		);
		$nav_links['search'] = array (
			'url' => append_sid($phpbb_root_path . 'search.' . $phpEx),
			'title' => $this->lang['Search']
		);
		$nav_links['help'] = array (
			'url' => append_sid($phpbb_root_path . 'faq.' . $phpEx),
			'title' => $this->lang['FAQ']
		);
		$nav_links['author'] = array (
			'url' => append_sid($phpbb_root_path . 'memberlist.' . $phpEx),
			'title' => $this->lang['Memberlist']
		);
		
		return;
	}

	function setup_style($style)
	{
		global $db, $cache, $board_config, $template, $images, $phpbb_root_path;
		global $mvModule_root_path;
		
		if ( $cache->exists('themes.theme_id_' . $style) )
		{
			$row = array();
			$row = $cache->get('themes.theme_id_' . $style);
	
			if ( empty($row) )
			{
				$cache->destroy('themes.');
				message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
			}
		}
		else
		{
			$sql = "SELECT *
				FROM " . THEMES_TABLE . "
				WHERE themes_id = $style";
			
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(CRITICAL_ERROR, 'Could not query database for theme info');
			}
			
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
			}
			
			$cache->put('themes.theme_id_' . $style, $row);
		}
	
		$template_path = 'templates/' ;
		$template_name = $row['template_name'] ;

		if(defined('IN_ADMIN'))
		{
			$template_name = 'admin';
		}
		$template = new Template($phpbb_root_path . $template_path . $template_name);
	
		if ( $template )
		{
			$current_template_path = $template_path . $template_name;
			@include($phpbb_root_path . $template_path . $template_name . '/' . $template_name . '.cfg');
			if(defined('IN_ADMIN'))
  			{
  				$current_template_path = '../' . $template_path . 'admin';
  				@include($phpbb_root_path . $template_path . 'admin/admin.cfg');
  			}
			if ($mvModule_root_path)
			{
				$current_template_images = $mvModule_root_path . $template_path . $template_name . '/images';
				@include($mvModule_root_path . $template_path . $template_name . '/' . $template_name . '.cfg');
			}
	
			if ( !defined('TEMPLATE_CONFIG') )
			{
				message_die(CRITICAL_ERROR, "Could not open $template_name template config file", '', __LINE__, __FILE__);
			}
	
			$this->img_lang = ( file_exists(@realpath($phpbb_root_path . $current_template_path . '/images/lang_' . $this->lang_name)) ) ? $this->lang_name : $board_config['default_lang'];
			foreach ( $images as $key => $value )
			{
				if ( !is_array($value) )
				{
					$images[$key] = str_replace('{LANG}', 'lang_' . $this->img_lang, $value);
				}
			}
		}
		if(defined('IN_ADMIN'))
		{
			$row = $admin[0];
		}
		return $row;
	}

	function lang_extend()
	{
		global $phpEx, $phpbb_root_path;

		$lang = array();

		include($phpbb_root_path . 'includes/lang_extend_mac.' . $phpEx);

		if (sizeof($lang))
		{
			$this->lang += $lang;
			
			unset($lang); // Nuke Big Arrs
		}
	}

	// Add Language Items - use_db and use_help are assigned where needed (only use them to force inclusion)
	//
	// $lang_set = array('posting', 'adr:common');
	// $lang_set = array('posting', 'viewtopic')
	// $lang_set = array(array('posting', 'viewtopic'), 'adr:' => array('common', 'common_admin'))
	// $lang_set = 'posting'
	function add_lang($lang_set)
	{
		global $phpEx;

		$lang_arr = array();

		if (is_array($lang_set))
		{
			foreach ($lang_set as $key => $lang_file)
			{
				// Please do not delete this line. 
				// We have to force the type here, else [array] language inclusion will not work
				$key = (string) $key;

				if ( ($pos = strpos($key, ':')) !== FALSE )
				{
					// Module Lang Pack
					$module_name = substr($key, 0, $pos);
				}
				elseif ( ($pos = strpos($lang_file, ':')) !== FALSE )
				{
					// Module Lang Pack
					$parts = explode(':', $lang_file);
					$module_name = $parts[0];
					$lang_file = $parts[1];
				}
				else
				{
					$module_name = '';
				}

				if (!is_array($lang_file))
				{
					$lang_arr += $this->set_lang($lang_file, $module_name);
				}
				else
				{
					$this->add_lang($lang_file, $module_name);
				}
			}
			unset($lang_set);
		}
		else if ($lang_set)
		{
			if ( ($pos = strpos($lang_set, ':')) !== FALSE )
			{
				// Module Lang Pack
				$module_name = substr($lang_set, 0, $pos);
			}
			else
			{
				$module_name = '';
			}

			$lang_arr += $this->set_lang($lang_set, $module_name);
		}

		if (sizeof($lang_arr))
		{
			$this->lang += $lang_arr;
			
			unset($lang_arr); // Nuke Big Arrs
		}

		return;
	}

	function set_lang($lang_file, $module = '')
	{
		global $phpEx;

		$lang = array();
		$file_name = ( empty($module) ) ? $this->lang_path . "lang_$lang_file.$phpEx" : sprintf($this->lang_module_path . "lang_$lang_file.$phpEx", $module);
		
		if ( file_exists($file_name) )
		{
			require ($file_name);
		}

		return $lang;
	}

	function format_date($gmepoch, $format = false)
	{
		static $lang_dates;

		if (empty($lang_dates))
		{
			foreach ($this->lang['datetime'] as $match => $replace)
			{
				$lang_dates[$match] = $replace;
			}
		}

		$format = (!$format) ? $this->date_format : $format;

		return strtr(@gmdate($format, $gmepoch + $this->timezone + $this->dst), $lang_dates);
	}

	function img($img, $alt = '', $width = false, $suffix = '')
	{
		// Should We add this? I think so :)
	}
}

$user = new user();
$GLOBALS['lang'] =& $user->lang; // Backwards Compat. with $lang

//
// Append $SID to a url. Borrowed from phplib and modified. This is an
// extra routine utilised by the session code above and acts as a wrapper
// around every single URL and form action. If you replace the session
// code you must include this routine, even if it's empty.
//
function append_sid($url, $non_html_amp = false)
{
	global $SID, $phpEx;
	global $phpbb_root_path, $mvModules, $mvModuleName;
	$matches = array();

	$amp = ( $non_html_amp ) ? '&' : '&amp;';

	if ( preg_match("~((admin)?[^\/\?&]*)\.$phpEx(?:[\?&]([^<>\s]*))?$~i", $url, $matches) )
	{
		// $matches[1] = "admin_file"
		// $matches[2] = "admin"
		// $matches[3] = "mode=view&f=5"

		if ( isset($matches[2]) && !empty($matches[2]) ) // Admin
		{
			foreach ($mvModules as $name => $value)
			{
				if ( $value['state'] != 1 && $value['state'] != 5 )
				{
					continue;
				}

				if ( in_array("$matches[1].$phpEx", $value['admin']) )
				{
					$url = $phpbb_root_path . "admin/index.$phpEx?module=$name$ampfile=$matches[1]" . (!empty($matches[3])	? "$amp$matches[3]" : '');
					break;
				}
			}

		}
		else if ($mvModuleName != '' && in_array("$matches[1].$phpEx", $mvModules[$mvModuleName]['files']))
		{
			$url = $phpbb_root_path . "index.$phpEx?module=$mvModuleName" . $amp . "file=$matches[1]" . (!empty($matches[3]) ? "$amp$matches[3]" : '');
		}
	}

	if ( !empty($SID) && !preg_match('#sid=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ? $amp  : '?' ) . $SID;
	}

	return $url;
}

// Will be keeping my eye of 'other products' to ensure these things don't
// mysteriously appear elsewhere, think up your own solutions!

// mwhahahahahah! te GPL is wonderful!

define('ACL_OPTIONS_TABLE', $table_prefix.'auth_options');
define('ACL_USERS_TABLE', $table_prefix.'auth_users');
define('ACL_GROUPS_TABLE', $table_prefix.'auth_groups');
define('ACL_PRESETS_TABLE', $table_prefix.'auth_presets');

define('ACL_NO', 0);
define('ACL_YES', 1);
define('ACL_UNSET', -1);

class auth
{
	var $founder = false;
	var $acl = array();
	var $option = array();
	var $acl_options = array();

	function acl(&$userdata)
	{
		global $db, $cache;

		if (!($this->acl_options = $cache->get('acl_options')))
		{
			$sql = 'SELECT auth_option, is_global, is_local
				FROM ' . ACL_OPTIONS_TABLE . '
				ORDER BY auth_option_id';
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Failed to obtain ACL Options', '', __LINE__, __FILE__, $sql);
			}

			$global = $local = 0;
			while ($row = $db->sql_fetchrow($result))
			{
				if (!empty($row['is_global']))
				{
					$this->acl_options['global'][$row['auth_option']] = $global++;
				}
				if (!empty($row['is_local']))
				{
					$this->acl_options['local'][$row['auth_option']] = $local++;
				}
			}
			$db->sql_freeresult($result);

			$cache->put('acl_options', $this->acl_options);
			$this->acl_clear_prefetch();
			$userdata['user_permissions'] = '';
			$this->acl_cache($userdata);


		}
		else if (!$userdata['user_permissions'])
		{
			$this->acl_cache($userdata);
		}

		foreach (explode("\n", $userdata['user_permissions']) as $f => $seq)
		{
			if ($seq)
			{
				$i = 0;
				while ($subseq = substr($seq, $i, 6))
				{
					if (!isset($this->acl[$f]))
					{
						$this->acl[$f] = '';
					}
					$this->acl[$f] .= str_pad(base_convert($subseq, 36, 2), 31, 0, STR_PAD_LEFT);
					$i += 6;
				}
			}
			
		}
		return;
	}

	// Look up an option
	function acl_get($opt, $f = 0)
	{
		static $cache;

		if (!isset($cache[$f][$opt]))
		{
			$cache[$f][$opt] = false;
			if (isset($this->acl_options['global'][$opt]))
			{
				if (isset($this->acl[0]))
				{
					$cache[$f][$opt] = $this->acl[0]{$this->acl_options['global'][$opt]};
				}
			}
			if (isset($this->acl_options['local'][$opt]))
			{
				if (isset($this->acl[$f]))
				{
					$cache[$f][$opt] |= $this->acl[$f]{$this->acl_options['local'][$opt]};
				}
			}
		}

		// Needs to change ... check founder status when updating cache?
		return $cache[$f][$opt];
	}

	function acl_getf($opt)
	{
		static $cache;

		if (isset($this->acl_options['local'][$opt]))
		{
			foreach ($this->acl as $f => $bitstring)
			{
				if (!isset($cache[$f][$opt]))
				{
					$cache[$f][$opt] = false;

					$cache[$f][$opt] = $bitstring{$this->acl_options['local'][$opt]};
					if (isset($this->acl_options['global'][$opt]))
					{
						$cache[$f][$opt] |= $this->acl[0]{$this->acl_options['global'][$opt]};
					}
				}
			}
		}

		return $cache;
	}

	function acl_gets()
	{
		$args = func_get_args();
		$f = array_pop($args);

		if (!is_numeric($f))
		{
			$args[] = $f;
			$f = 0;
		}

		// alternate syntax: acl_gets(array('m_', 'a_'), $forum_id)
		if (is_array($args[0]))
		{
			$args = $args[0];
		}

		$acl = 0;
		foreach ($args as $opt)
		{
			$acl |= $this->acl_get($opt, $f);
		}

		return $acl;
	}

	function acl_get_list($user_id = false, $opts = false, $forum_id = false)
	{
		$hold_ary = $this->acl_raw_data($user_id, $opts, $forum_id);

		$auth_ary = array();
		foreach ($hold_ary as $user_id => $forum_ary)
		{
			foreach ($forum_ary as $forum_id => $auth_option_ary)
			{
				foreach ($auth_option_ary as $auth_option => $auth_setting)
				{
					if ($auth_setting == ACL_YES)
					{
						$auth_ary[$forum_id][$auth_option][] = $user_id;
					}
				}
			}
		}

		return $auth_ary;
	}

	// Cache data
	function acl_cache(&$userdata)
	{
		global $db;

		$hold_ary = $this->acl_raw_data($userdata['user_id'], false, false);
		$hold_ary = $hold_ary[$userdata['user_id']];

		// If this user is founder we're going to force fill the admin options ...
		if ($userdata['user_type'] == USER_FOUNDER)
		{
			foreach ($this->acl_options['global'] as $opt => $id)
			{
				if (strstr($opt, 'a_'))
				{
					$hold_ary[0][$opt] = 1;
				}
			}
		}
		$hold_str = $userdata['user_permissions'];
		if (is_array($hold_ary))
		{
			ksort($hold_ary);
			$last_f = 0;
			foreach ($hold_ary as $f => $auth_ary)
			{
				$ary_key = (!$f) ? 'global' : 'local';
				$bitstring = array();
				foreach ($this->acl_options[$ary_key] as $opt => $id)
				{
					
					if (!empty($auth_ary[$opt]))
					{
						$bitstring[$id] = 1;

						$option_key = substr($opt, 0, strpos($opt, '_') + 1);
						if (empty($holding[$this->acl_options[$ary_key][$option_key]]))
						{
							$bitstring[$this->acl_options[$ary_key][$option_key]] = 1;
						}
					}
					else
					{
						$bitstring[$id] = 0;
					}
				}
				$bitstring = implode('', $bitstring);
				$hold_str .= str_repeat("\n", $f - $last_f);
				for ($i = 0; $i < strlen($bitstring); $i += 31)
				{
					$hold_str .= str_pad(base_convert(str_pad(substr($bitstring, $i, 31), 31, 0, STR_PAD_RIGHT), 2, 36), 6, 0, STR_PAD_LEFT);
				}

				$last_f = $f;
			}
			unset($bitstring);

			$userdata['user_permissions'] = rtrim($hold_str);
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_permissions = '" . $db->sql_escape($userdata['user_permissions']) . "'
				WHERE user_id = " . $userdata['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update users prefetch', '', __LINE__, __FILE__, $sql);
			}
		}
		unset($hold_ary);
		return;
	}

	function acl_raw_data($user_id = false, $opts = false, $forum_id = false)
	{
		global $db;

		$sql_user = ($user_id) ? ((!is_array($user_id)) ? "user_id = $user_id" : 'user_id IN (' . implode(', ', $user_id) . ')') : '';
		$sql_forum = ($forum_id) ? ((!is_array($forum_id)) ? "AND a.forum_id = $forum_id" : 'AND a.forum_id IN (' . implode(', ', $forum_id) . ')') : '';
		$sql_opts = ($opts) ? ((!is_array($opts)) ? "AND ao.auth_option = '$opts'" : 'AND ao.auth_option IN (' . implode(', ', preg_replace('#^[\s]*?(.*?)[\s]*?$#e', "\"'\" . \$db->sql_escape('\\1') . \"'\"", $opts)) . ')') : '';

		$hold_ary = array();
		// First grab user settings ... each user has only one setting for each
		// option ... so we shouldn't need any ACL_NO checks ... he says ...
		$sql = 'SELECT ao.auth_option, a.user_id, a.forum_id, a.auth_setting
			FROM ' . ACL_OPTIONS_TABLE . ' ao, ' . ACL_USERS_TABLE . ' a
			WHERE ao.auth_option_id = a.auth_option_id
				' . (($sql_user) ? 'AND a.' . $sql_user : '') . "
				$sql_forum
				$sql_opts
			ORDER BY a.forum_id, ao.auth_option";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Failed to obtain User ACL Settings', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$hold_ary[$row['user_id']][$row['forum_id']][$row['auth_option']] = $row['auth_setting'];
		}
		$db->sql_freeresult($result);

		// Now grab group settings ... ACL_NO overrides ACL_YES so act appropriatley
		$sql = 'SELECT ug.user_id, ao.auth_option, a.forum_id, a.auth_setting
			FROM ' . USER_GROUP_TABLE . ' ug, ' . ACL_OPTIONS_TABLE . ' ao, ' . ACL_GROUPS_TABLE . ' a
			WHERE ao.auth_option_id = a.auth_option_id
				AND a.group_id = ug.group_id
				' . (($sql_user) ? 'AND ug.' . $sql_user : '') . "
				$sql_forum
				$sql_opts
			ORDER BY a.forum_id, ao.auth_option";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Failed to select Group ACL Settings', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			if (!isset($hold_ary[$row['user_id']][$row['forum_id']][$row['auth_option']]) || (isset($hold_ary[$row['user_id']][$row['forum_id']][$row['auth_option']]) && $hold_ary[$row['user_id']][$row['forum_id']][$row['auth_option']] != ACL_NO))
			{
				$hold_ary[$row['user_id']][$row['forum_id']][$row['auth_option']] = $row['auth_setting'];
			}
		}
		$db->sql_freeresult($result);

		return $hold_ary;
	}

	// Clear one or all users cached permission settings
	function acl_clear_prefetch($user_id = false)
	{
		global $db;

		$where_sql = ($user_id) ? ' WHERE user_id ' . ((is_array($user_id)) ? ' IN (' . implode(', ', array_map('intval', $user_id)) . ')' : " = $user_id") : '';

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_permissions = ''
			$where_sql";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not clear user prefetch', '', __LINE__, __FILE__, $sql);
		}

		return;
	}
}

?>