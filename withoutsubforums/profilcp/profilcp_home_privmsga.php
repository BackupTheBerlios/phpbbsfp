<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_home_privmsga.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilecp_home_privmsga.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
    exit;
}

if ( !empty($setmodules) ) return;

if ( !empty($set_homemodules) )
{
    $file = basename(__FILE__);
    $home_modules['pos'][] = 'left';
    $home_modules['sort'][] = 10;
    $home_modules['url'][] = $file;
    return;
}

//-------------------------------------------
//
//  Private messages
//
//-------------------------------------------

// save called pgm
$main_pgm = "./profile.$phpEx?mode=privmsg";

include_once($phpbb_root_path . './includes/functions_privmsga.' . $phpEx);

// pre process : count the privmsgs for the pagination fields
if ( $process == 'pre' )
{
    // get page parm
    $privmsg_page_size = (isset($board_config['privmsgs_per_page'])) ? intval($board_config['privmsgs_per_page']) : 0;
    $privmsg_total = 0;
    $privmsg_start = 0;
    if ( isset($HTTP_POST_VAR['privmsgs_start']) || isset($HTTP_GET_VARS['privmsgs_start']) )
    {
        $privmsg_start = isset($HTTP_POST_VAR['privmsgs_start']) ? intval($HTTP_POST_VAR['privmsgs_start']) : intval($HTTP_GET_VARS['privmsgs_start']);
    }

    if ($privmsg_page_size > 0)
    {
        $user_id = $userdata['user_id'];
        $sql = "SELECT p.*,
                        pa.*,
                        pr.privmsg_recip_id AS selected_pm_id, pr.privmsg_status AS selected_status, pr.privmsg_read AS selected_read,
                        ua.username AS privmsg_from_username, ua.*
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . PRIVMSGA_RECIPS_TABLE . " pa, " . PRIVMSGA_TABLE . " p, " . USERS_TABLE . " ua
                    WHERE pr.privmsg_user_id = $view_user_id AND pr.privmsg_direct = 1
                        AND pr.privmsg_read IN (" . NEW_MAIL . ", " . UNREAD_MAIL . ")
                        AND pr.privmsg_status = " . STS_TRANSIT . "
                        AND pa.privmsg_id = pr.privmsg_id AND pa.privmsg_direct = 0
                        AND p.privmsg_id = pr.privmsg_id
                        AND ( (pa.privmsg_user_id <> 0 AND ua.user_id = pa.privmsg_user_id) OR (pa.privmsg_user_id = 0 AND ua.user_id = " . ANONYMOUS . ") )
                    ORDER BY p.privmsg_time DESC, p.privmsg_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not query private message post information', '', __LINE__, __FILE__, $sql);
        }
        $privmsg_sql = $sql;
        $privmsg_total = $db->sql_numrows($result);
        if ( $privmsg_start >= $privmsg_total )
        {
            $privmsg_start = $privmsg_total - 1;
        }
        if ( $privmsg_start < 0 )
        {
            $privmsg_start = 0;
        }

        // pagination fields
        $s_pagination_fields .= "&privmsgs_start=$privmsg_start";
    }
}

// post process : display
if ( ($process == 'post') && ($privmsg_page_size > 0) )
{
    $privmsg_rowset = array();
    $recips = array();
    if ($privmsg_total > 0)
    {
        $sql = $privmsg_sql . " LIMIT $privmsg_start, $privmsg_page_size";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not query private message post information', '', __LINE__, __FILE__, $sql);
        }
        $s_privmsg_ids = '';
        while ( $row = $db->sql_fetchrow($result) )
        {
            $privmsg_rowset[] = $row;
            $s_privmsg_ids .= ( empty($s_privmsg_ids) ? '' : ', ' ) . $row['privmsg_id'];
        }
    }

    // read the recipients
    if ( !empty($privmsg_rowset) )
    {
        $sql = "SELECT pr.privmsg_id, pr.privmsg_recip_id, pr.privmsg_user_id, ur.username AS privmsg_to_username
                    FROM " . PRIVMSGA_RECIPS_TABLE . " pr, " . USERS_TABLE . " ur
                    WHERE pr.privmsg_user_id = $view_user_id AND pr.privmsg_direct = 1
                        AND pr.privmsg_id IN ($s_privmsg_ids)
                        AND ur.user_id = pr.privmsg_user_id
                    ORDER BY pr.privmsg_id, ur.username, pr.privmsg_recip_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Can\'t read recipients', '', __LINE__, __FILE__, $sql);
        }
        while ( $row = $db->sql_fetchrow($result) )
        {
            if ( count($recips[ $row['privmsg_id'] ]) <= $cfg_max_userlist )
            {
                $recips['data'][ $row['privmsg_id'] ][] = $row;
            }
            else
            {
                $recips['over'][ $row['privmsg_id'] ] = true;
            }
        }
    }
    // save template state
    $sav_tpl = $template->_tpldata;

    // build list
    privmsg_list($privmsg_rowset, $recips, INBOX);

    // transfert to a var
    $res = $template->vars['PRIVMSGA_BOX'];

    // restore template saved state
    $template->_tpldata = $sav_tpl;

    // init right part of the home panel
    if ( !$right_part )
    {
        $template->assign_block_vars('right_part', array());
        $right_part = true;
    }

    // send result to template
    $template->assign_block_vars('right_part.box', array(
        'BOX' => $res,
        )
    );

    // hidden fields
    $s_hidden_fields .= '<input type="hidden" name="privmsgs_start" value="' . $privmsg_start . '" />';

    // fix pagination display bug
    if ($privmsg_total == 0)
    {
        $privmsg_total = 1;
    }

    // remove the current paginations data (will be added by the generate_pagination() func)
    $w_pagination = str_replace( "&privmsgs_start=$privmsg_start", '', $s_pagination_fields );

    // send the pagination sentence to display
    $template->assign_block_vars('right_part.box.pagination', array(
        'PAGINATION'    => generate_pagination("./profile.$phpEx?$w_pagination", $privmsg_total, $privmsg_page_size, $privmsg_start, true, 'privmsgs_start'),
        'PAGE_NUMBER'   => sprintf($lang['Page_of'], ( floor( $privmsg_start / $privmsg_page_size ) + 1 ), ceil( $privmsg_total / $privmsg_page_size )),
        )
    );
}

?>