<?php
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: memberlist.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : memberlist.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_VIEWMEMBERS);
init_userprefs($userdata);
//
// End session management
//

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
redirect(append_sid("./profile.$phpEx?mode=buddy&sub=memberlist"));
//-- fin mod : profile cp --------------------------------------------------------------------------

?>