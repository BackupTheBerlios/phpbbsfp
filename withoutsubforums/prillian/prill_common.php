<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: prill_common.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : prill_common.php
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
define('IN_PRILLIAN', true);
define('CURRENT_LANG_PATH', $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/');
// CONSTANTS - END

// FILES - BEGIN

include_once(PRILL_PATH . 'functions_im.' . $phpEx);
include_once(CURRENT_LANG_PATH . 'lang_prillian.' . $phpEx);

// FILES - END

// VARIABLES - BEGIN

// Preset common vars.
$error = false;
$meta_headers = '';
$refresh_javascript = '';
$mode_append = '';
$read_mark = '';
$im_auto_popup = 0;
$l_prillian_text = $lang['Launch_Prillian'];
$im_userdata = array();
$default_im_subject = $lang['phpBB_IM_default_subject'];
// VARIABLES - END

if( !defined('IN_NETWORK'))
{
  include_once(PRILL_PATH . 'contact_common.' . $phpEx);
}
else
{
  include_once(PRILL_PATH . 'functions_common.' . $phpEx);
}


secure_superglobals();
$prill_config = get_prillian_config();
// Skip unneeded page headers on small windows
$simple = 0;
$append_msg = '';
if( !empty($_REQUEST['simple']) || $gen_simple_header )
{
    $gen_simple_header = true;
    $simple = 1;
    $append_msg = $lang['Close_window_link'];
}

?>