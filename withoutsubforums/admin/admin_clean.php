<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_clean.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_board_extend.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Florian
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if( !empty($setmodules) )
{
    $filename = basename(__FILE__);
    $module['DB']['DB_clean'] = $filename . "?mode=activate";
    return;
}

define('IN_PHPBB', 1);

$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

//
// Language File
//
include($phpbb_root_path.'language/lang_' . $board_config['default_lang'] . '/lang_admin_clean.'.$phpEx);

$template->set_filenames(array(
    "body" => "admin_clean.tpl")
);

$template->assign_vars(array(
    'ACTION' => append_sid("admin_clean.$phpEx"),
    'L_CHOICE' => $lang['Choice'],
    'L_TYPE' => $lang['Type'],
    'L_NUMBER' => $lang['Items'],
    'L_LAUNCH' => $lang['Submit'])
    );

// ----------------------------------------
// Counting items
// ----------------------------------------
function items_count($sql,$choice,$type)
{
global $affected;

    global $db,$template;
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, $lang['Retrieve_error'] . $error, '', __LINE__, __FILE__, $sql);
    }
    $row = $db->sql_fetchrow($result);
    if($row[0] == 0)
    {
        $choice = "";
    }
    $affected = $affected + intval($row[0]);
    $template->assign_block_vars("admin_clean", array(
            'CHOICE' => $choice,
            'TYPE' => $type,
            'NUMBER' => $row[0])
    );
    $db->sql_freeresult($result);
}
// ----------------------------------------

// ----------------------------------------
// Deleting items
// ----------------------------------------
function items_delete($sql_select,$sql_delete,$type)
{
    global $db,$template;
    if ( !($result = $db->sql_query($sql_select)) )
    {
        message_die(GENERAL_ERROR, $lang['Retrieve_error'] . $type, '', __LINE__, __FILE__, $sql);
    }
    $i=0;
    while($row = $db->sql_fetchrow($result))
    {
        if($i!=0)
        {
            $sql_delete .= ",";
        }
        $sql_delete .= $row[0];
        $i++;
    }
    $sql_delete .= ")";
    $db->sql_freeresult($row);
    if( ! $db->sql_query($sql_delete) )
    {
        message_die(GENERAL_ERROR, $lang['Delete_error'] . $type, "", __LINE__, __FILE__, $sql);
    }
}
// ----------------------------------------

$n = 0;
$affected = 0;

// ----------------------------------------
// Topics without posts
// ----------------------------------------
// Deleting?
$type = $lang['Topic_post'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT t.topic_id
    FROM " . TOPICS_TABLE . " t LEFT JOIN ". POSTS_TABLE . " p ON t.topic_id = p.topic_id
    WHERE p.topic_id Is NULL";
    $sql_delete = "DELETE FROM " .TOPICS_TABLE . " where topic_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql= "SELECT count(DISTINCT t.topic_id)
FROM ". TOPICS_TABLE . " t LEFT JOIN ". POSTS_TABLE . "  p ON t.topic_id = p.topic_id
WHERE p.topic_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Posts without topics
// ----------------------------------------
// Deleting?
$type = $lang['Posts_topics'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT p.post_id
    FROM " . POSTS_TABLE . " p LEFT JOIN " . TOPICS_TABLE . " t ON p.topic_id = t.topic_id
    WHERE t.topic_id Is NULL";
    $sql_delete = "DELETE FROM " . POSTS_TABLE . " WHERE post_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT p.post_id)
FROM " . POSTS_TABLE . " p LEFT JOIN " . TOPICS_TABLE . " t ON p.topic_id = t.topic_id
WHERE t.topic_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Topics watch without topics
// ----------------------------------------
// Deleting?
$type = $lang['Watchs_topics'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT w.topic_id
    FROM " . TOPICS_WATCH_TABLE . " w LEFT JOIN " . TOPICS_TABLE . " t ON w.topic_id = t.topic_id
    WHERE t.topic_id Is NULL";
    $sql_delete = "DELETE FROM " . TOPICS_WATCH_TABLE . " WHERE topic_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT w.topic_id)
FROM " . TOPICS_WATCH_TABLE . " w LEFT JOIN " . TOPICS_TABLE . " t ON w.topic_id = t.topic_id
WHERE t.topic_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Topics view without topics
// ----------------------------------------
// Deleting?
$type = $lang['Views_topics'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT w.topic_id
    FROM " . TOPICS_VIEW_TABLE . " w LEFT JOIN " . TOPICS_TABLE . " t ON w.topic_id = t.topic_id
    WHERE t.topic_id Is NULL";
    $sql_delete = "DELETE FROM " . TOPICS_VIEW_TABLE . " WHERE topic_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT w.topic_id)
FROM " . TOPICS_VIEW_TABLE . " w LEFT JOIN " . TOPICS_TABLE . " t ON w.topic_id = t.topic_id
WHERE t.topic_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Topics watch without user
// ----------------------------------------
// Deleting?
$type = $lang['Watchs_user'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT w.topic_id
    FROM " . TOPICS_WATCH_TABLE . " w LEFT JOIN " . USERS_TABLE . " u ON w.user_id = u.user_id
    WHERE u.user_id Is NULL";
    $sql_delete = "DELETE FROM " . TOPICS_WATCH_TABLE . " WHERE topic_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT w.topic_id)
FROM " . TOPICS_WATCH_TABLE . " w LEFT JOIN " . USERS_TABLE . " u ON w.user_id = u.user_id
WHERE u.user_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Topics view without user
// ----------------------------------------
// Deleting?
$type = $lang['Views_user'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT w.topic_id
    FROM " . TOPICS_VIEW_TABLE . " w LEFT JOIN " . USERS_TABLE . " u ON w.user_id = u.user_id
    WHERE u.user_id Is NULL";
    $sql_delete = "DELETE FROM " . TOPICS_VIEW_TABLE . " WHERE topic_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT w.topic_id)
FROM " . TOPICS_VIEW_TABLE . " w LEFT JOIN " . USERS_TABLE . " u ON w.user_id = u.user_id
WHERE u.user_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Votes without topic
// ----------------------------------------
// Deleting?
$type = $lang['Votes_topics'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT v.vote_id
    FROM " . VOTE_DESC_TABLE . " v LEFT JOIN " . TOPICS_TABLE . " t ON v.topic_id = t.topic_id
    WHERE t.topic_id Is NULL";
    $sql_delete = "DELETE FROM " . VOTE_DESC_TABLE . " WHERE vote_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT v.vote_id)
FROM " . VOTE_DESC_TABLE . " v LEFT JOIN " . TOPICS_TABLE . " t ON v.topic_id = t.topic_id
WHERE t.topic_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Votes results without votes
// ----------------------------------------
// Deleting?
$type = $lang['Results_poll'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT r.vote_id
    FROM " . VOTE_RESULTS_TABLE . " r LEFT JOIN " . VOTE_DESC_TABLE . " v ON r.vote_id = v.vote_id
    WHERE v.vote_id Is NULL";
    $sql_delete = "DELETE FROM " . VOTE_RESULTS_TABLE . " WHERE vote_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT r.vote_id)
FROM " . VOTE_RESULTS_TABLE . " r LEFT JOIN " . VOTE_DESC_TABLE . " v ON r.vote_id = v.vote_id
WHERE v.vote_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

// ----------------------------------------
// Voters without vote
// ----------------------------------------
// Deleting?
$type = $lang['Voters_poll'];

if ( isset($HTTP_POST_VARS['action']) && isset($HTTP_POST_VARS['c' . $n ]) )
{
    $sql_select = "SELECT DISTINCT v.vote_id
    FROM " .VOTE_USERS_TABLE . " v LEFT JOIN " . VOTE_DESC_TABLE . " d ON v.vote_id = d.vote_id
    WHERE d.vote_id Is NULL";
    $sql_delete = "DELETE FROM " .VOTE_USERS_TABLE . " WHERE vote_id in (";
    items_delete($sql_select,$sql_delete,$type);
}
// Counting
$sql = "SELECT count(DISTINCT v.vote_id)
FROM " .VOTE_USERS_TABLE . " v LEFT JOIN " . VOTE_DESC_TABLE . " d ON v.vote_id = d.vote_id
WHERE d.vote_id Is NULL";
$choice = "<input type=checkbox name=c" . $n . ">";
$n++;
items_count($sql,$choice,$type);
// ----------------------------------------

$template->pparse('body');
if ($affected == 0)
{
    echo "No action needed, you don't have inconsistant data in your forum database.";
}
else
{
    echo "You have inconsistant data in you forum database, you should clean it.";
}
include('./page_footer_admin.'.$phpEx);
?>
