<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_ldap_groups.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_ldap_groups.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004 		Adam Larsen
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

function ldapUpdateGroups($username) {
	// turn off reporting errors in case the password will be incorrect during binding
	$reporting = error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR );

	global $db;
	$ldapGroup = new ldapGroups();
	$ldapGroup->ldap_members_set($username);

	// Get the User_id from the DB
	$sql = "SELECT user_id FROM ".USERS_TABLE." WHERE username = '".$username."'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query User information', '', __LINE__, __FILE__, $sql);
	}
	$user_data = $db->sql_fetchrow($result);
	$userid = $user_data['user_id'];

	// Get the list of group that are LDAP updated that the user is a member of
	//    But not moderator (we don't want to delete the moderator).
	$sql = "SELECT gt.group_id, gt.group_name FROM " . GROUPS_TABLE . " gt, ".USER_GROUP_TABLE." ugt
		WHERE gt.group_id = ugt.group_id
			AND gt.group_ldap_update = " . TRUE . "
			AND ugt.user_id = " . $userid . "
			AND gt.group_moderator <>" . $userid;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query Group membership information', '', __LINE__, __FILE__, $sql);
	}
	$user_group = array();
	while( $user_group_data = $db->sql_fetchrow($result) )
	{
		// See if they are apart of any phpBB groups and not in LDAP
		$user_group[] = array($user_group_data['group_name'], $user_group_data['group_id']);
	}

	foreach ($user_group as $group)
	{
		if (!in_array($group[0], $ldapGroup->ldapMembers))
		{
			// If they are not members of the LDAP group, remove them from the phpBB group
			$sql = "DELETE FROM " . USER_GROUP_TABLE . "
				WHERE group_id = " . $group[1] . "
					AND user_id = " . $userid;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not remove user from group', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	// Get the new list of memberships
	//    Include the groups the user moderates
	$sql = "SELECT gt.group_id, gt.group_name FROM " . GROUPS_TABLE . " gt , ".USER_GROUP_TABLE." ugt
		WHERE gt.group_id = ugt.group_id
			AND gt.group_ldap_update = " . TRUE . "
			AND ugt.user_id = " . $userid;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query Group membership information', '', __LINE__, __FILE__, $sql);
	}
	// Fill an array
	$user_group = array();
	while( $user_group_data = $db->sql_fetchrow($result) )
	{
		$user_group[] = $user_group_data['group_name'];
	}

	// Get list of groups in phpBB that are LDAP updated
	$sql = "SELECT gt.group_id, gt.group_name FROM " . GROUPS_TABLE . " gt
		WHERE gt.group_ldap_update = 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query Group LDAP information', '', __LINE__, __FILE__, $sql);
	}
	// Fill an array
	$group_ldap = array();
	while( $group_ldap_data = $db->sql_fetchrow($result) )
	{
		$group_ldap[$group_ldap_data['group_name']] = $group_ldap_data['group_id'];
	}

	// Go thought the list and see if they are not members of any groups that they are in LDAP
	foreach($ldapGroup->ldapMembers as $value)
	{

		if (!in_array($value,$user_group) && array_key_exists($value,$group_ldap))
		{
			// Add user the Groups
			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
				VALUES (" . $userid . ", " . $group_ldap[$value] . ", 0)";
			if( !($result = $db->sql_query($sql, END_TRANSACTION)) ) {
				message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	unset ($ldapGroup);
}

class ldapGroups
{
	var $ldapConn;
	var $ldapMembers;
	var $userDN;
	// ----------------------------------------------------
	//  ldap_memberof_set
	//
	//  unsets LdapMembersOf and reloads it
	// ----------------------------------------------------
	function ldap_members_set ($username) {
		// turn off reporting errors in case the password will be incorrect during binding
		$reporting = error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR );

		global $board_config ;
		$this->ldapMembers = array();
		$this->ldapConn = ldap_connect_ex();
		if ($this->ldapConn == false) {
	      message_die(GENERAL_ERROR, 'Could not connect to LDAP directory.', '', __LINE__, __FILE__, '');
	      return false;
	   	}
	   	else {
			if ($board_config["ldap_proxy_dn"] != "") {
				$bind = ldap_bind($this->ldapConn, $board_config["ldap_proxy_dn"], $board_config["ldap_proxy_dn_pass"]);
			}
			else {
				$bind = ldap_bind($this->ldapConn);
			}
			if ($bind == false) {
				message_die(GENERAL_ERROR, 'Could not bind to LDAP directory.', '', __LINE__, __FILE__, '');
				return false;
			}
			else {

				//-------------------------------------
				//   Get Primary Group ID
				//-------------------------------------
				$ldapSearch = ldap_search($this->ldapConn, $board_config["ldap_dn"], $board_config['ldap_uid'].'='. $username,array("primarygroupid"));
				//$ldapSearch = ldap_read($this->ldapConn, $ObjectDN, 'objectClass=*',array("primarygroupid"));
				$ldapResults = ldap_get_entries($this->ldapConn, $ldapSearch);
				if ($ldapResults["count"] != 1) {
					message_die(GENERAL_ERROR, 'Could not find user in LDAP directory.', '', __LINE__, __FILE__, '');
					return false;
				}
				else
				{
					$this->userDN = $ldapResults[0]["dn"];
					if ($this->userDN == '')
						message_die(GENERAL_ERROR, 'Could not find DN.', '', __LINE__, __FILE__, '');
					if (isset($ldapResults[0]['primarygroupid'][0]))
						$this->ldapMembers[] = $ldapResults[0]['primarygroupid'][0];
					//else - Non AD systems don't have PrimaryGroupID's so no need for an error.
						//message_die(GENERAL_ERROR, 'Could did find Primary Group ID.', '', __LINE__, __FILE__, '');
					ldap_free_result($ldapSearch);
					//-------------------------------------
					//  Get the other Groups
					//-------------------------------------

					$this->ldap_members ($this->userDN);
					return true;
				}
				//-------------------------------------
				//  Clean up
				//-------------------------------------
				ldap_unbind($this->ldapConn);
			}
	   	}
	}

	// ----------------------------------------------------
	// ldap_member()
	//
	// Fill array with the list of group this CN is a member of
	// ----------------------------------------------------
	function ldap_members ($ObjectDN) {
		// turn off reporting errors in case the password will be incorrect during binding
		$reporting = error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR );

		global $board_config;

		$attribute = $board_config['ldap_gid'];
		$ldapUserName = $board_config['ldap_uid'];

		$ldapSearch = ldap_read($this->ldapConn, $ObjectDN, 'objectclass=*', array($ldapUserName,"cn",$attribute) );
		$ldapResults = ldap_get_entries($this->ldapConn, $ldapSearch);
		// Find any sub groups
		if (isset($ldapResults[0][$attribute]))
		{
			for ($detail = 0; $detail < $ldapResults[0][$attribute]['count']; $detail++)
			{
				$MemberCN = $ldapResults[0][$attribute][$detail];
				$this->ldap_members ($MemberCN);
			}
		}
		// Add the current CN to the list
		// and check for duplicits
		if (isset($ldapResults[0][$ldapUserName][0]) && !in_array($ldapResults[0][$ldapUserName][0],$this->ldapMembers)){

			$this->ldapMembers[] = $ldapResults[0][$ldapUserName][0];
		}
		ldap_free_result($ldapSearch);
	}
}
?>