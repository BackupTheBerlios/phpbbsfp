<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: privmsga_review.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : privmsgs_review.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

function privmsg_review($view_user_id, $privmsg_recip_id, $is_inline_review)
{
    global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
    global $userdata, $user_ip;
    global $orig_word, $replacement_word;
    global $starttime;
    global $admin_level, $level_prior, $bbcode_parse;
    global $icones;

    include_once($phpbb_root_path . './includes/functions_messages.' . $phpEx);

    // fix parameters
    $privmsg_recip_id = intval($privmsg_recip_id);
    $view_user_id = intval($view_user_id);

    // check if exists and belongs to the user
    $sql = "SELECT privmsg_id
                FROM " . PRIVMSGA_RECIPS_TABLE . "
                WHERE privmsg_user_id = $view_user_id
                    AND privmsg_recip_id = $privmsg_recip_id";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain private message information', '', __LINE__, __FILE__, $sql);
    }
    if ( !$row = $db->sql_fetchrow($result) )
    {
        message_die(GENERAL_MESSAGE, 'No_post_id');
    }
    $privmsg_id = intval($row['privmsg_id']);

    if ( !$is_inline_review )
    {
        //
        // Start session management
        //
        $userdata = session_pagestart($user_ip, $forum_id);
        init_userprefs($userdata);
        //
        // End session management
        //
        $sql = "SELECT *
                    FROM " . USERS_TABLE . "
                    WHERE user_id = $view_user_id";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not read user information', '', __LINE__, __FILE__, $sql);
        }
        if ( !$view_userdata = $db->sql_fetchrow($result) )
        {
            message_die(GENERAL_MESSAGE, 'User_not_exist');
        }
        check_user($view_userdata);
    }

    //
    // Define censored word matches
    //
    if ( empty($orig_word) && empty($replacement_word) )
    {
        $orig_word = array();
        $replacement_word = array();
        obtain_word_list($orig_word, $replacement_word);
    }

    //
    // Dump out the page header and load viewtopic body template
    //
    if ( !$is_inline_review )
    {
        $gen_simple_header = true;
        $page_title = _lang('Topic_review');
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);
    }
    $template->set_filenames(array(
        'reviewbody' => 'posting_topic_review.tpl')
    );

    // Read the message id
    $sql = "SELECT p.*, pa.*, u.username AS privmsg_from_username
                FROM " . PRIVMSGA_TABLE . " p, " . PRIVMSGA_RECIPS_TABLE . " pa, " . USERS_TABLE . " u
                WHERE p.privmsg_id = $privmsg_id
                    AND pa.privmsg_id = p.privmsg_id AND pa.privmsg_direct = 0
                    AND ( (pa.privmsg_user_id <> 0 AND u.user_id = pa.privmsg_user_id) OR (pa.privmsg_user_id = 0 AND u.user_id = " . ANONYMOUS . ") )";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain post/user information', '', __LINE__, __FILE__, $sql);
    }
    if ( $row = $db->sql_fetchrow($result) )
    {
        $poster_id = $row['privmsg_user_id'];
        $poster = empty($poster_id) ? $board_config['sitename'] : ($poster_id == ANONYMOUS) ? _lang('Guest') : $row['privmsg_from_username'];
        $post_date = create_date($userdata['user_dateformat'], $row['privmsg_time'], $userdata['user_timezone']);

        $post_subject = empty($row['privmsg_subject']) ? '' : $row['privmsg_subject'];
        $message = $row['privmsg_text'];
        $bbcode_uid = $row['privmsg_bbcode_uid'];

        //
        // If the board has HTML off but the post has HTML
        // on then we process it, else leave it alone
        //
        if ( !$board_config['allow_html'] && $row['privmsg_enable_html'] )
        {
            $message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $message);
        }

        if ( !empty($bbcode_uid) )
        {
            $message = ( $board_config['allow_bbcode'] ) ? $bbcode_parse->bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
        }
        $message = $bbcode_parse->make_clickable($message);

        if ( count($orig_word) )
        {
            $post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
            $message = preg_replace($orig_word, $replacement_word, $message);
        }

        if ( $board_config['allow_smilies'] && $row['enable_smilies'] )
        {
            $message = $bbcode_parse->smilies_pass($message);
        }

        $message = str_replace("\n", '<br />', $message);

        $message = $bbcode_parse->acronym_pass( $message );
        $message = $bbcode_parse->smart_pass( $message );

        if ( function_exists('get_icon_title') )
        {
            $post_subject = get_icon_title($row['post_icon']) . '&nbsp;' . $post_subject;
        }

        // just for the template : no signification here
        $mini_post_img = _images('icon_minipost');
        $mini_post_alt = _lang('Post');

        //
        // Again this will be handled by the templating
        // code at some point
        //
        $color = true;
        $row_color = $color ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = $color ? $theme['td_class1'] : $theme['td_class2'];

        $template->assign_block_vars('postrow', array(
            'ROW_COLOR'         => '#' . $row_color,
            'ROW_CLASS'         => $row_class,

            'MINI_POST_IMG'     => $mini_post_img,
            'POSTER_NAME'       => $poster,
            'POST_DATE'         => $post_date,
            'POST_SUBJECT'      => $post_subject,
            'MESSAGE'           => $message,

            'L_MINI_POST_ALT'   => $mini_post_alt,
            )
        );
    }
    else
    {
        message_die(GENERAL_MESSAGE, 'No_post_id', '', __LINE__, __FILE__, $sql);
    }

    $template->assign_vars(array(
        'L_AUTHOR'          => _lang('Author'),
        'L_MESSAGE'         => _lang('Message'),
        'L_POSTED'          => _lang('Posted'),
        'L_POST_SUBJECT'    => _lang('Post_subject'),
        'L_TOPIC_REVIEW'    => _lang('Topic_review'),
        )
    );

    if ( !$is_inline_review )
    {
        $template->pparse('reviewbody');
        include( $phpbb_root_path . 'includes/page_tail.' . $phpEx );
    }
}

?>