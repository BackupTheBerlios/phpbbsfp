<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profile_pic.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME	 : profile_pic.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003		Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_REGISTER);
init_userprefs($userdata);
//
// End session management
//
$key = isset($HTTP_GET_VARS['l']) ?  intval($HTTP_GET_VARS['l']) : 0;
$letter = $userdata['session_robot'][$key];
$path = realpath($images['alphabet_' . $letter]);
if (file_exists($path))
{
    header('Expires: ' . gmdate( 'D, d M Y H:i:s', time()-3600*24 ) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Content-type: image/gif');
    readfile($path);
}

?>