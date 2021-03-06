<?php
//-- mod : sub-template ----------------------------------------------------------------------------
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: topic_review.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : topic_review.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team and � 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

function topic_review($topic_id, $is_inline_review)
{
    global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
    global $userdata, $user_ip;
    global $orig_word, $replacement_word;
    global $starttime, $bbcode_parse;
//-- mod : sub-template ----------------------------------------------------------------------------
//-- add
    global $sub_template_key_image, $sub_templates;
//-- fin mod : sub-template ------------------------------------------------------------------------

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
    global $admin_level, $level_prior;
//-- fin mod : profile cp --------------------------------------------------------------------------

    if ( !$is_inline_review )
    {
        if ( !isset($topic_id) )
        {
            message_die(GENERAL_MESSAGE, 'Topic_not_exist');
        }

        //
        // Get topic info ...
        //
        $sql = "SELECT t.topic_title, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.auth_download, t.topic_attachment
            FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
            WHERE t.topic_id = $topic_id
                AND f.forum_id = t.forum_id";

        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
        }

        if ( !($forum_row = $db->sql_fetchrow($result)) )
        {
            message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
        }
        $db->sql_freeresult($result);

        $forum_id = $forum_row['forum_id'];
        $topic_title = $forum_row['topic_title'];

        //
        // Start session management
        //
        $userdata = session_pagestart($user_ip, $forum_id);
        init_userprefs($userdata);
        //
        // End session management
        //

        $is_auth = array();
        $is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_row);

        if ( !$is_auth['auth_read'] )
        {
            message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']));
        }
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
        $gen_simple_header = TRUE;

        $page_title = $lang['Topic_review'] . ' - ' . $topic_title;
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);

        $template->set_filenames(array(
            'reviewbody' => 'posting_topic_review.tpl')
        );
    }

    //
    // Go ahead and pull all data for this topic
    //
    $sql = "SELECT u.username, u.user_id, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
        FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
        WHERE p.topic_id = $topic_id
            AND p.poster_id = u.user_id
            AND p.post_id = pt.post_id
        ORDER BY p.post_time DESC
        LIMIT " . $board_config['posts_per_page'];
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain post/user information', '', __LINE__, __FILE__, $sql);
    }

    init_display_review_attachments($is_auth);

    //
    // Okay, let's do the loop, yeah come on baby let's do the loop
    // and it goes like this ...
    //
    if ( $row = $db->sql_fetchrow($result) )
    {
        $mini_post_img = $images['icon_minipost'];
        $mini_post_alt = $lang['Post'];

        $i = 0;
        do
        {
            $poster_id = $row['user_id'];
            $poster = $row['username'];

            $post_date = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

            //
            // Handle anon users posting with usernames
            //
            if( $poster_id == ANONYMOUS && $row['post_username'] != '' )
            {
                $poster = $row['post_username'];
                $poster_rank = $lang['Guest'];
            }
            elseif ( $poster_id == ANONYMOUS )
            {
                $poster = $lang['Guest'];
                $poster_rank = '';
            }

            $post_subject = ( $row['post_subject'] != '' ) ? $row['post_subject'] : '';

            $message = $row['post_text'];
            $bbcode_uid = $row['bbcode_uid'];

            $plain_message = $row['post_text'];
            $plain_message = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $plain_message);
            $plain_message = str_replace('<', '&lt;', $plain_message);
            $plain_message = str_replace('>', '&gt;', $plain_message);
            $plain_message = str_replace('<br />', "\n", $plain_message);

            $orig_word = array();
            $replacement_word = array();
            obtain_word_list($orig_word, $replace_word);

            if ( !empty($orig_word) )
            {
                $plain_message = ( !empty($plain_message) ) ? preg_replace($orig_word, $replace_word, $plain_message) : '';
            }
            $plain_message = addslashes($plain_message);
            $plain_message = str_replace("\n", "\\n", $plain_message);

            //
            // If the board has HTML off but the post has HTML
            // on then we process it, else leave it alone
            //
            if ( !$board_config['allow_html'] && $row['enable_html'] )
            {
                $message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $message);
            }

            if ( $bbcode_uid != "" )
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

            //
            // Again this will be handled by the templating
            // code at some point
            //
            $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
            $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

            $template->assign_block_vars('postrow', array(
                'ROW_COLOR' => '#' . $row_color,
                'ROW_CLASS' => $row_class,

                'MINI_POST_IMG' => $mini_post_img,
                'POSTER_NAME' => $poster,
                'POST_DATE' => $post_date,
                'POST_SUBJECT' => $post_subject,
                'MESSAGE' => $message,
                'U_POST_ID' => $row['post_id'],
                'PLAIN_MESSAGE' => str_replace(chr(13), '', $plain_message),

                'L_MINI_POST_ALT' => $mini_post_alt)
            );

            display_review_attachments($row['post_id'], $row['post_attachment'], $is_auth);

            $i++;
        }
        while ( $row = $db->sql_fetchrow($result) );
    }
    else
    {
        message_die(GENERAL_MESSAGE, 'Topic_post_not_exist', '', __LINE__, __FILE__, $sql);
    }
    $db->sql_freeresult($result);

    $template->assign_vars(array(
        'L_AUTHOR' => $lang['Author'],
        'L_MESSAGE' => $lang['Message'],
        'L_POSTED' => $lang['Posted'],
        'L_POST_SUBJECT' => $lang['Post_subject'],
        'L_TOPIC_REVIEW' => $lang['Topic_review'])
    );

    if ( !$is_inline_review )
    {
        $template->pparse('reviewbody');
        include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
    }
}

?>
