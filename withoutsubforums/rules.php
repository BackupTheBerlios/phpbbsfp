<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: rules.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : rules.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2003 Sko22
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.'.$phpEx);

$layout = $core_layout[LAYOUT_RULES];

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_RULES);
init_userprefs($userdata);
//
// End session management
//

if (file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rule.' . $phpEx))
{
	include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rule.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'language/lang_english/lang_rule.' . $phpEx);
}

$id = isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']) : SITE_ID;

//
// Get message of the rules.
//

$rule = get_rule ($id, 'both');

if( $rule !== FALSE )
{
    $text = $rule['TEXT'];
    $date = create_date($lang['DATE_FORMAT'], $rule['DATE'], $board_config['board_timezone']);
}
else
{
	message_die (GENERAL_MESSAGE, 'There are no site wide rules available', $lang['Rules']);
}

unset($rule);

//
// Update user rules view
//
if ($userdata['session_logged_in'])
{
	$today = time();
	
	$sql = "UPDATE " . USERS_TABLE . " SET user_rules='" . $today . "' WHERE user_id='" . $userdata['user_id']. "'";
	
	if (!$result = $db->sql_query($sql))
	{
	    message_die(GENERAL_ERROR, 'Could not update user rules view', '', __LINE__, __FILE__, $sql);
	}
}

//
// Lets build a page ...
//
$page_title = $lang['Rules'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
    'body' => 'rules.tpl')
);

$template->assign_vars(array(
	'L_FORUM_RULES' => $lang['Rules'],
	'L_DATE'		=> $lang['Date'],
	'RULES_TEXT' => $text,
	'RULES_DATE' => $date
	)
);


$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>