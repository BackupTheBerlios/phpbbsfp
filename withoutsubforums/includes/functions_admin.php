<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_admin.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_admin.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

//
// Simple version of jumpbox, just lists authed forums
//
function make_forum_select($box_name, $ignore_forum = false, $select_forum = '')
{
    global $db, $userdata;

    $is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

    $sql = "SELECT forum_id, forum_name
        FROM " . FORUMS_TABLE . "
        ORDER BY cat_id, forum_order";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Couldn not obtain forums information', '', __LINE__, __FILE__, $sql);
    }

    $forum_list = '';
    while( $row = $db->sql_fetchrow($result) )
    {
        if ( $is_auth_ary[$row['forum_id']]['auth_read'] && $ignore_forum != $row['forum_id'] )
        {
            $selected = ( $select_forum == $row['forum_id'] ) ? ' selected="selected"' : '';
            $forum_list .= '<option value="' . $row['forum_id'] . '"' . $selected .'>' . $row['forum_name'] . '</option>';
        }
    }

    $forum_list = ( $forum_list == '' ) ? '<option value="-1">-- ! No Forums ! --</option>' : '<select name="' . $box_name . '">' . $forum_list . '</select>';

    return $forum_list;
}

//
// Synchronise functions for forums/topics
//
function sync($type, $id = false)
{
    global $db;

    switch($type)
    {
        case 'all forums':
            $sql = "SELECT forum_id
                FROM " . FORUMS_TABLE;
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get forum IDs', '', __LINE__, __FILE__, $sql);
            }

            while( $row = $db->sql_fetchrow($result) )
            {
                sync('forum', $row['forum_id']);
            }
            break;

        case 'all topics':
            $sql = "SELECT topic_id
                FROM " . TOPICS_TABLE;
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get topic ID', '', __LINE__, __FILE__, $sql);
            }

            while( $row = $db->sql_fetchrow($result) )
            {
                sync('topic', $row['topic_id']);
            }
            break;

        case 'forum':
            $sql = "SELECT MAX(post_id) AS last_post, COUNT(post_id) AS total
                FROM " . POSTS_TABLE . "
                WHERE forum_id = $id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
            }

            if ( $row = $db->sql_fetchrow($result) )
            {
                $last_post = ( $row['last_post'] ) ? $row['last_post'] : 0;
                $total_posts = ($row['total']) ? $row['total'] : 0;
            }
            else
            {
                $last_post = 0;
                $total_posts = 0;
            }

            $sql = "SELECT COUNT(topic_id) AS total
                FROM " . TOPICS_TABLE . "
                WHERE forum_id = $id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get topic count', '', __LINE__, __FILE__, $sql);
            }

            $total_topics = ( $row = $db->sql_fetchrow($result) ) ? ( ( $row['total'] ) ? $row['total'] : 0 ) : 0;

            $sql = "UPDATE " . FORUMS_TABLE . "
                SET forum_last_post_id = $last_post, forum_posts = $total_posts, forum_topics = $total_topics
                WHERE forum_id = $id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update forum', '', __LINE__, __FILE__, $sql);
            }
            break;

        case 'topic':
            $sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts
                FROM " . POSTS_TABLE . "
                WHERE topic_id = $id";
            if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
            }

            if ( $row = $db->sql_fetchrow($result) )
            {
                $sql = ( $row['total_posts'] ) ? "UPDATE " . TOPICS_TABLE . " SET topic_replies = " . ( $row['total_posts'] - 1 ) . ", topic_first_post_id = " . $row['first_post'] . ", topic_last_post_id = " . $row['last_post'] . " WHERE topic_id = $id" : "DELETE FROM " . TOPICS_TABLE . " WHERE topic_id = $id";
                if ( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, 'Could not update topic', '', __LINE__, __FILE__, $sql);
                }
            }

            attachment_sync_topic($id);

            break;
    }

    return true;
}

//
// Prune Functions
// these require functions_search.php
//

function prune($forum_id, $prune_date, $prune_all = false)
{
    global $db, $lang;

    $prune_all = ($prune_all) ? '' : 'AND t.topic_vote = 0 AND t.topic_type <> ' . POST_ANNOUNCE;
    //
    // Those without polls and announcements ... unless told otherwise!
    //
    $sql = 'SELECT t.topic_id
        FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . " t
        WHERE t.forum_id = $forum_id
            $prune_all
            AND ( p.post_id = t.topic_last_post_id
                OR t.topic_last_post_id = 0 )";
    if ( $prune_date != '' )
    {
        $sql .= " AND p.post_time < $prune_date";
    }

    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain lists of topics to prune', '', __LINE__, __FILE__, $sql);
    }

    $sql_topics = '';
    while( $row = $db->sql_fetchrow($result) )
    {
        $sql_topics .= ( ( $sql_topics != '' ) ? ', ' : '' ) . $row['topic_id'];
    }
    $db->sql_freeresult($result);

    if( $sql_topics != '' )
    {
        $sql = 'SELECT post_id
            FROM ' . POSTS_TABLE . "
            WHERE forum_id = $forum_id
                AND topic_id IN ($sql_topics)";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain list of posts to prune', '', __LINE__, __FILE__, $sql);
        }

        $sql_post = '';
        while ( $row = $db->sql_fetchrow($result) )
        {
            $sql_post .= ( ( $sql_post != '' ) ? ', ' : '' ) . $row['post_id'];
        }
        $db->sql_freeresult($result);

        if ( $sql_post != '' )
        {
            $sql = 'DELETE FROM ' . TOPICS_WATCH_TABLE . "
                WHERE topic_id IN ($sql_topics)";
            if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
            {
                message_die(GENERAL_ERROR, 'Could not delete watched topics during prune', '', __LINE__, __FILE__, $sql);
            }

            $sql = 'DELETE FROM ' . TOPICS_VIEW_TABLE . "
                WHERE topic_id IN ($sql_topics)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete topic views during prune', '', __LINE__, __FILE__, $sql);
            }

            $sql = 'DELETE FROM ' . TOPICS_TABLE . "
                WHERE topic_id IN ($sql_topics)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete topics during prune', '', __LINE__, __FILE__, $sql);
            }

            $pruned_topics = $db->sql_affectedrows();

            $sql = 'DELETE FROM ' . POSTS_TABLE . "
                WHERE post_id IN ($sql_post)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete post_text during prune', '', __LINE__, __FILE__, $sql);
            }

            $pruned_posts = $db->sql_affectedrows();

            $sql = 'DELETE FROM ' . POSTS_TEXT_TABLE . "
                WHERE post_id IN ($sql_post)";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not delete post during prune', '', __LINE__, __FILE__, $sql);
            }

            remove_search_post($sql_post);
            prune_attachments($sql_post);

            return array ('topics' => $pruned_topics, 'posts' => $pruned_posts);
        }
    }

    return array('topics' => 0, 'posts' => 0);
}

//
// Function auto_prune(), this function will read the configuration data from
// the auto_prune table and call the prune function with the necessary info.
//
function auto_prune($forum_id = 0)
{
    global $db, $lang;

    $sql = 'SELECT *
        FROM ' . PRUNE_TABLE . "
        WHERE forum_id = $forum_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not read auto_prune table', '', __LINE__, __FILE__, $sql);
    }

    if ( $row = $db->sql_fetchrow($result) )
    {
        if ( $row['prune_freq'] && $row['prune_days'] )
        {
            $prune_date = time() - ( $row['prune_days'] * 86400 );
            $next_prune = time() + ( $row['prune_freq'] * 86400 );

            prune($forum_id, $prune_date);
            sync('forum', $forum_id);

            $sql = 'UPDATE ' . FORUMS_TABLE . "
                SET prune_next = $next_prune
                WHERE forum_id = $forum_id";
            if ( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, 'Could not update forum table', '', __LINE__, __FILE__, $sql);
            }
        }
    }

	$db->sql_freeresult($result);

    return;
}


?>