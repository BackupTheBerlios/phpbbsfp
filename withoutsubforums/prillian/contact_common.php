<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: contact_common.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : contact_common.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Thoul
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

// CONSTANTS - BEGIN
//define('CURRENT_LANG_PATH', $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/');
// CONSTANTS - END


// VARIABLES - BEGIN
$online_array = array();
// VARIABLES - END

// FILES - BEGIN
include_once(PRILL_PATH . 'functions_common.' . $phpEx);
// FILES - END

// PRILLIAN ADD-ONS - BEGIN
// These are only necessary if Prillian is not installed
if(!defined('IN_PRILLIAN'))
{
    secure_superglobals();
    // Skip unneeded page headers on small windows
    $simple = 0;
    $append_msg = '';
    if( !empty($_REQUEST['simple']) || $gen_simple_header )
    {
        $gen_simple_header = true;
        $simple = 1;
    }
}
// PRILLIAN ADD-ONS - END

?>