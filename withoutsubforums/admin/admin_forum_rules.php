<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_forum_rules.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_forum_rules.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Sko22
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !empty($setmodules) )
{
    $filename = basename(__FILE__);
    $module['General']['Rules'] = $filename;

    return;
}

define('IN_PHPBB', true);

//
// Load default header
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

include_once($phpbb_root_path . './includes/functions_messages.' . $phpEx);

if (file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rule.' . $phpEx))
{
	include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rule.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'language/lang_english/lang_rule.' . $phpEx);
}

$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : 'list';

$template->assign_var('S_MODE', $mode);

switch ($mode)
{
    case 'save':

		$prev_mode = ( isset($HTTP_GET_VARS['prev_mode']) ) ? $HTTP_GET_VARS['prev_mode'] : '';
		$id = ( isset($HTTP_GET_VARS['id']) ) ? $HTTP_GET_VARS['id'] : 0;

	    $rule_date = time();

		$rule_text = str_replace("\'", "''", $HTTP_POST_VARS['rule_text']);

		if ( $prev_mode == 'modify' )
		{
	        //
	        // Save data rules.
	        //
	        $sql = "UPDATE " . RULES_TABLE . " SET rule_text = '" . $rule_text . "', rule_date = '" . $rule_date . "'
				WHERE rule_id = '" . $id . "'";

		}
		else
		{
			$sql = "INSERT INTO " . RULES_TABLE . " ( rule_id, rule_text, rule_date )
				VALUES ( '" . $id . "', '" . $rule_text . "', '" . $rule_date . "' )";
		}

        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not create/modify rules', '', __LINE__, __FILE__, $sql);
        }

		$cache->destroy('rules');

		message_die(GENERAL_MESSAGE, 'Done!');

        break;

	case '':
	case 'list':

		$temp_url = $phpbb_root_path . 'admin/' . basename(__FILE__);

		$u_create = "<a href='" . append_sid($temp_url . "?mode=create&amp;id=%s") . "'>" . $lang['rules_Create'] . "</a>";
		$u_modify = "<a href='" . append_sid($temp_url . "?mode=modify&amp;id=%s") . "'>" . $lang['rules_Modify'] . "</a>";

		$sql = "SELECT * FROM " . RULES_TABLE . "
			ORDER BY rule_id";

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain the rules', '', __LINE__, __FILE__, $sql);
		}

		$rules = array();

		while ($row = $db->sql_fetchrow($result))
		{
			$rules[$row['rule_id']] = array(
				'TEXT'	=> $row['rule_text'],
				'DATE'	=> $row['rule_date']
			);
		}

		$db->sql_freeresult($result);

		$forum_ids = array_keys($tree['type'], 'f');

		foreach ($forum_ids as $forum_id)
		{
			$template->assign_block_vars('rules_list', array(
				'FORUM_NAME'	=> $tree['data'][$forum_id]['forum_name'],
				'U_ACTION'		=> sprintf((isset($rules[$forum_id]) ? $u_modify : $u_create), $forum_id),
			));
		}

		$template->assign_vars(array(
			'L_PRIVACY'	=> $lang['rules_Privacy'],
			'L_TERMS'	=> $lang['rules_Terms'],
			'L_SITE'	=> $lang['rules_Site'],
			'L_COPY'	=> $lang['rules_Copyright'],

			'U_PRIVACY'	=> sprintf((isset($rules[PRIVACY_ID]) ? $u_modify : $u_create), PRIVACY_ID),
			'U_TERMS'	=> sprintf((isset($rules[TERMS_ID]) ? $u_modify : $u_create), TERMS_ID),
			'U_SITE'	=> sprintf((isset($rules[SITE_ID]) ? $u_modify : $u_create), SITE_ID),
			'U_COPY'	=> sprintf((isset($rules[COPY_ID]) ? $u_modify : $u_create), COPY_ID),

			'L_MODIFY'	=> 'Modify',
			'L_CREATE'	=> 'Create',
			'L_DELETE'	=> 'Delete'
		));

		$page = 'admin/forum_rules_body.tpl';

		break;

	case 'create':
	case 'modify':
		$id = ( isset($HTTP_GET_VARS['id']) ) ? $HTTP_GET_VARS['id'] : 0;

		if ($mode == 'modify')
		{
		    //
		    // Get message of the rules from database.
		    //
		    $sql = "SELECT * FROM " . RULES_TABLE . "
				WHERE rule_id = '" . $id . "'";

		    if( !($result = $db->sql_query($sql)) )
		    {
		        message_die(GENERAL_ERROR, 'Could not obtain the rules', '', __LINE__, __FILE__, $sql);
		    }

		    $row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$rule_text = $row['rule_text'];
		    $rule_date = create_date($lang['DATE_FORMAT'], $row['rule_date'], $board_config['board_timezone']);
		}
		else
		{
			$rule_text = '';
			$rule_date = 0;
		}
        //
        // Assign the template data rules.
        //
        $template->assign_vars(array(
			'L_LAST_MODIFIED'	=> $lang['last_modified'],

            'RULE_DATE' => $rule_date,

            'L_NOTVIEW_RULES' => $lang['NotView_Rules'],
            'L_DO' => $lang['Save'],

            'U_NOTVIEW_RULES' => "",

            'S_RULE_TEXT' => trim($rule_text),
            'S_RULE_ACTION' => append_sid($phpbb_root_path . "admin/admin_forum_rules.$phpEx?mode=save&amp;prev_mode=$mode&amp;id=$id")
            )
        );

        $page = 'admin/forum_rules_body.tpl';

        break;


    case "notview":

		message_die (GENERAL_ERROR, "Erm...");

        //
        // Users that don't have read the rules.
        //
        $sql = "SELECT user_id, username, user_rules FROM " . USERS_TABLE . " WHERE user_rules < $HTTP_GET_VARS[trd] AND user_id <> " . ANONYMOUS . " ORDER BY username ASC";

        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain list of users that don\'t have read the rules', '', __LINE__, __FILE__, $sql);
        }

        while( $row = $db->sql_fetchrow($result) )
        {
            $user_notview_rules .= '<option value="' . $row[user_id] . '">' . $row[username] . '</option>';
            $hidden_input .= $row[user_id] . ",";
        }

        //
        // Get last PM
        //
        $sql = "SELECT pm_subject, pm_message FROM " . RULES_TABLE;

        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain last PM', '', __LINE__, __FILE__, $sql);
        }

        $pm = $db->sql_fetchrow($result);

        //
        // Assign the template data rules.
        //
        $template->assign_vars(array(
            'L_SEND_PM_ALL' => $lang['Send_PM_All'],
            'L_SEND_PM_SELECT' => $lang['Send_PM_Select'],
            'L_SEND_EMAIL' => $lang['Send_Email_Rules'],
            'L_NOTVIEW_RULES' => $lang['NotView_Rules'],
            'L_DO' => $lang['Send'],
            'L_SUBJECT' => $lang['Subject'],
            'L_MESSAGE_BODY' => $lang['Message_body'],
            'L_FORUM_RULES' => $lang['Forum_Rules'],
            'L_FORUM_RULES_EXPLAIN' => $lang['Forum_Rules_explain_NotView'],
            'L_SEND_PM_TO' => $lang['Send_PM_to'],
            'L_USERS_NOTVIEW_RULES' => $lang['Users_NotView_Rules'],
            'L_SELECT_USER_FIRST' => $lang['Select_User_First'],

            'S_FORUMRULES_ACTION' => append_sid("admin_forum_rules.$phpEx") . "&mode=notview_sendpm",
            'S_NOTVIEW_RULES' => $user_notview_rules,
            'S_PM_SUBJECT' => $pm["pm_subject"],
            'S_PM_MESSAGE' => $pm["pm_message"],
            'S_HIDDEN_VARS' => $hidden_input
            )
        );

        $page = 'admin/forum_rules_notview_body.tpl';

        break;


    case "notview_sendpm":

		message_die (GENERAL_ERROR, "Erm...");

        $privmsg_subject = trim(strip_tags($HTTP_POST_VARS['subject']));
        $msg_time = time();
        $html_on = $userdata['user_allowhtml'];
        $bbcode_on = $userdata['user_allowbbcode'];
        $smilies_on =  $userdata['user_allowsmile'];
        $attach_sig = $userdata['user_attachsig'];

        //
        //Save the last PM
        //
        $sql = "UPDATE " . RULES_TABLE . " SET pm_subject='$privmsg_subject' , pm_message='" . $HTTP_POST_VARS['message'] . "'";

        if ( !$db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not update rules table', '', __LINE__, __FILE__, $sql);
        }

        if ( $bbcode_on ) $bbcode_uid = make_bbcode_uid();

        if (substr($HTTP_POST_VARS['all_user_notview_rules'], -1) == ",") $HTTP_POST_VARS['all_user_notview_rules'] = substr($HTTP_POST_VARS['all_user_notview_rules'], 0, -1);

        //
        //Send a PM to All Users or Selected Users
        //
        if ( $HTTP_POST_VARS['send_pm'] != "send_pm_all" )
        {
            if ( isset($HTTP_POST_VARS['user_notview_rules']) ) $user_to_send = $HTTP_POST_VARS['user_notview_rules'];
        }

        else $user_to_send = split(",", $HTTP_POST_VARS['all_user_notview_rules'] );

        //
        //Send PMs to Users
        //



        foreach ($user_to_send as $to_userdata)
        {

	        $sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
	        			FROM " . USERS_TABLE . "
	        			WHERE user_id = '" . $to_userdata . "' AND
	        			user_id <> " . ANONYMOUS;

			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user to send PM', '', __LINE__, __FILE__, $sql);
			}

        	$to_userdata = $db->sql_fetchrow($result);

        	send_pm(0, $to_userdata, $userdata['user_id'], $privmsg_subject, $privmsg_message, 0, $html_on, $bbcode_on, $smilies_on, $userdata['attachsig'] );

        	//Send email with PM
        	if ( isset($HTTP_POST_VARS['send_email']) )
			{

				if ( $to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'] )
            	{
					$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
					$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.'.$phpEx : 'privmsg.'.$phpEx;
					$server_name = trim($board_config['server_name']);
					$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
					$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

					$emailer = new emailer($board_config['smtp_delivery']);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
					$emailer->email_address($to_userdata['user_email']);
					$emailer->set_subject($lang['Notification_subject']);

					$emailer->assign_vars(array(
						'USERNAME' => $to_username,
						'SITENAME' => $board_config['sitename'],
						'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
						'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox')
					);

					$emailer->send();
					$emailer->reset();
				}
			}
        }

        $message = $lang['Message_sent'] .
            "<br /><br />" . sprintf($lang['Click_return_Rules'], "<a href=\"" . append_sid("admin_forum_rules.$phpEx?mode=modify") . "\">", "</a>")     .
            "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>")   ;

        message_die(GENERAL_MESSAGE, $message);

        break;

}

$template->assign_vars(array(
    'L_RULES' => $lang['Rules'],
    'L_RULES_EXPLAIN' => $lang['Rules_explain']
));

//
// Output the form to retrieve Prune information.
//
$template->set_filenames(array(
    'body' =>$page )
);


//
// Actually output the page here.
//
$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
