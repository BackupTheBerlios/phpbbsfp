<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: login.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : login.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

//
// Allow people to reach login page if
// board is shut down
//

define('IN_PHPBB', true);
define('IN_LOGIN', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//

include_once(PRILL_PATH . 'prill_common.' . $phpEx);

//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------


// session id check
if (isset($HTTP_GET_VARS['sid']) && !empty($HTTP_GET_VARS['sid']))
{
    $sid = $HTTP_GET_VARS['sid'];
}
elseif (isset($HTTP_POST_VARS['sid']) && !empty($HTTP_POST_VARS['sid']))
{
    $sid = $HTTP_POST_VARS['sid'];
}
else
{
    $sid = '';
}

$auth_mode = $board_config['auth_mode'];
$use_ldap = ( $board_config['auth_mode'] == 'ldap' && ntlm_check() ? TRUE : FALSE);

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout'])  || $use_ldap )
{
    if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || $use_ldap ) && !$userdata['session_logged_in'] )
    {
        if ($use_ldap)
		{
            $username = ntlm_get_user();
		}
        else
		{
			$username = isset($HTTP_POST_VARS['username']) ? trim(htmlspecialchars($HTTP_POST_VARS['username'])) : '';
		}

        $username = substr(str_replace("\\'", "'", $username), 0, 25);
        $username = str_replace("'", "\\'", $username);
        $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

        $sql = "SELECT user_id, username, user_password, user_active, user_level, user_type
            FROM " . USERS_TABLE . "
            WHERE username = '" . str_replace("\\'", "''", $username) . "'";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
        }

        $row = $db->sql_fetchrow($result);

		$use_ldap = ( $use_ldap && $row['user_type'] !== User_Type_phpBB ) ? TRUE : FALSE;

        if ( $use_ldap )
        {
			// LDAP is in use and the user is not a phpBB Only User.
            $ldap_auth_result = ldap_auth($username, $password);
        }
        else
        {
			// We are not using LDAP or our user is phpBB only.
            $ldap_auth_result = false;
        }

        if ( $row == false && $use_ldap && $ldap_auth_result == LDAP_AUTH_OK )
        {
            add_ldap_user($username);

			// User didnt exist, they do now, reuse the SQL.

            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);
        }

        if( $row )
        {
            if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
            {
                redirect(append_sid("index.$phpEx", true));
            }
            else
            {
				$password_md5 = md5($password);

				if (( ( $use_ldap && ( $ldap_auth_result == LDAP_AUTH_OK || ($ldap_auth_result == LDAP_INVALID_USERNAME && $password_md5 == $row['user_password']))) || ($password_md5 == $row['user_password'] && !$use_ldap )) & $row['user_active'] )
                {
                    if ( $use_ldap && $ldap_auth_result == LDAP_AUTH_OK && $board_config['ldap_group_sync'] == 1)
                    {
                        ldapUpdateGroups($username);
                    }

                    $autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

                    $session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin);

                    if( $session_id )
                    {
                        $url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";
                        redirect(append_sid($url, true));
                    }
                    else
                    {
                        message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
                    }
                }
                else
                {
                    $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
                    $redirect = str_replace('?', '&', $redirect);
                    if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
                    {
						message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
                    } 
                    $template->assign_vars(array(
                        'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
                    );

                    $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

                    message_die(GENERAL_MESSAGE, $message);
                }
            }
        }
        else
        {
            $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
            $redirect = str_replace("?", "&", $redirect);
            if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
            {
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
            } 
            $template->assign_vars(array(
                'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
            );

            $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

            message_die(GENERAL_MESSAGE, $message);
        }
    }
    else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
    {
        if( $userdata['session_logged_in'] )
        {
            session_end($userdata['session_id'], $userdata['user_id']);
        }

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//
        if ( !empty($_REQUEST['in_prill']) )
        {
            im_session_update(true, true);
        }
//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------

        if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
        {
            $url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']);
            $url = str_replace('&amp;', '&', $url);
            redirect(append_sid($url, true));
        }
        else
        {
            if ($use_ldap)
            {
                message_die(GENERAL_MESSAGE, "You have been logged out <br /><br /> Click <a href=login.$phpEx>Here</A> to login again.<br />< br/>Click <A HREF=index.$phpEx>Here</A> to return the the site index.");
            }
            redirect(append_sid("index.$phpEx", true));
        }
    }
    else
    {
        $url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";
        redirect(append_sid($url, true));
    }
}
else
{
    //
    // Do a full login page dohickey if
    // user not already logged in
    //
    if( !$userdata['session_logged_in'] )
    {
        $page_title = $lang['Login'];
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//
        $body_tpl = '';
        if( $gen_simple_header )
        {
            $body_tpl = 'prillian/';
        }
//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------


        $template->set_filenames(array(
            'body' => $body_tpl . 'login_body.tpl')
        );

        if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
        {
            $forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

            if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
            {
                $forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
                $forward_match = explode('&', $forward_to);

                if(count($forward_match) > 1)
                {
                    $forward_page = '';

                    for($i = 1; $i < count($forward_match); $i++)
                    {
                        if( !ereg("sid=", $forward_match[$i]) )
                        {
                            if( $forward_page != '' )
                            {
                                $forward_page .= '&';
                            }
                            $forward_page .= $forward_match[$i];
                        }
                    }
                    $forward_page = $forward_match[0] . '?' . $forward_page;
                }
                else
                {
                    $forward_page = $forward_match[0];
                }
            }
        }
        else
        {
            $forward_page = '';
        }

        $username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

        $s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';

        make_jumpbox('viewforum.'.$phpEx, $forum_id);
        $template->assign_vars(array(
            'USERNAME' => $username,

            'L_ENTER_PASSWORD' => $lang['Enter_password'],
            'L_SEND_PASSWORD' => $lang['Forgotten_password'],

            'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

            'S_HIDDEN_FIELDS' => $s_hidden_fields)
        );

        $template->pparse('body');

        include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
    }
    else
    {
        redirect(append_sid("index.$phpEx", true));
    }

}

?>