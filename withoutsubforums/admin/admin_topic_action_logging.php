<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_topic_action_logging.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_topic_action_logging.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
define('MOD_VERSION', '0.95');

if( !empty($setmodules) ){
    include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_topic_action_logging.' . $phpEx);
    $module['Forums']['Action_Logging'] = basename(__FILE__);

    return;
}

$phpbb_root_path = '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_admin_topic_action_logging.'.$phpEx);

(file_exists('pagestart.' . $phpEx)) ? include('pagestart.' . $phpEx) : include('pagestart.inc');

include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_topic_action_logging.' . $phpEx);

/****************************************************************************
/** Constants and Main Vars.
/***************************************************************************/
$page_title = $lang['Action_Logging'];
$order_types = array('DESC', 'ASC');
$sort_types = array('u.username', 'tl.log_time', 'tl.log_action', 'tl.topic_title');
$log_types = array(LOGGING_ACTION_ALL, LOGGING_ACTION_MOVED, LOGGING_ACTION_LOCKED, LOGGING_ACTION_UNLOCKED, LOGGING_ACTION_DELETED, LOGGING_ACTION_SPLIT);
$status_message = '';

/****************************************************************************
/** Admin Only Functions
/***************************************************************************/
function topic_action_logging_make_drop_box($type = 'sort')
{
    global $sort_types, $sort;
    global $order_types, $lang, $order;
    global $log_type, $log_types;

    $rval = '<select name="'.$type.'">';

    switch($type){
        case 'sort':{

            foreach($sort_types as $val){
                $selected = ($sort == $val) ? 'selected="selected"' : '';
                $rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
            }
            break;
        }
        case 'order':{
            foreach($order_types as $val){
                $selected = ($order == $val) ? 'selected="selected"' : '';
                $rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
            }
            break;
        }
        case 'log_type':{
            foreach($log_types as $val){
                $selected = ($log_type == $val) ? 'selected="selected"' : '';
                $rval .= "<option value=\"$val\" $selected>" . $lang['LOG_' . $val] . '</option>';
            }
            break;
        }
    }

    $rval .= '</select>';

    return $rval;
}

function atal_id_2_name($id, $mode = 'user')
{
    global $db;

    if ($id == '')
    {
        return '?';
    }

    switch($mode)
    {
        case 'user':
        {
            $sql = 'SELECT username FROM ' . USERS_TABLE . "
                WHERE user_id = $id";

            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, 'Error on users table.', '', __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);
            return $row['username'];
            break;
        }
        case 'reverse':
        {
            $sql = 'SELECT user_id FROM ' . USERS_TABLE . "
                WHERE username = '$id'";

            if(!$result = $db->sql_query($sql))
            {
                message_die(GENERAL_ERROR, 'Error on users table.', '', __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);
            return $row['user_id'];
            break;
        }
        default:
        break;
    }
}
function atal_do_pagination()
{
    global $db, $lang, $template, $order, $mode, $sort, $start, $board_config, $phpEx, $log_type_text, $mode;

    $sql = 'SELECT count(*) AS total FROM ' . TOPICS_TABLE . "_action_log tl
           WHERE 1
           $log_type_text";

    if(!$result = $db->sql_query($sql))
    {
        message_die(GENERAL_ERROR, $lang['Error_Logs_Table'], '', __LINE__, __FILE__, $sql);
    }
    else
    {
        $total = $db->sql_fetchrow($result);
        $total_items = ($total['total'] > 0) ? $total['total'] : 1;

        $pagination = generate_pagination("admin_topic_action_logging.$phpEx?mode=$mode&amp;order=$order&amp;sort=$sort", $total_items, $board_config['topics_per_page'], $start)."&nbsp;";
    }

    $template->assign_vars(array(
    "PAGINATION" => $pagination,
    "PAGE_NUMBER" => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_items / $board_config['topics_per_page'] )),
    "L_GOTO_PAGE" => $lang['Goto_page']
    ));
}
/*******************************************************************************************
/** Get parameters.  'var_name' => 'default'
/******************************************************************************************/
$params = array(
'mode' => '', 'start' => 0, 'order' => 'DESC',
'log_type' => LOGGING_ACTION_ALL, 'sort' => 'tl.log_time',
'filter_user' => '', 'delete_true' => false
);

foreach($params as $var => $default)
{
    $$var = $default;
    if( isset($HTTP_POST_VARS[$var]) || isset($HTTP_GET_VARS[$var]) )
    {
        $$var = ( isset($HTTP_POST_VARS[$var]) ) ? $HTTP_POST_VARS[$var] : $HTTP_GET_VARS[$var];
    }
}

$filter_user_id = atal_id_2_name($filter_user, 'reverse');

$filter_by_user_text = ($filter_user != '') ? "AND u.user_id = $filter_user_id"  : '';
$log_type_text = ($log_type != LOGGING_ACTION_ALL) ? "AND tl.log_action = $log_type" : '';
if ($log_type != LOGGING_ACTION_ALL && $filter_user != '') $filter_by_user_text .= ',';
if (count($HTTP_POST_VARS)) {
    $delete_count = 0;
    foreach($HTTP_POST_VARS as $key => $val) {
        /*******************************************************************************************
        /** Check for deletion items
        /******************************************************************************************/
        if (substr_count($key, 'delete_id_')) {
            $log_id = substr($key, 10);
            $delete_count++;

            $sql = "DELETE FROM " . TOPICS_TABLE . "_action_log
                   WHERE log_id = $log_id";
            if(!$db->sql_query($sql)) message_die(GENERAL_ERROR, $lang['Error_Logs_Table'], '', __LINE__, __FILE__, $sql);
        }
    }
    if ($delete_true) $status_message .= sprintf($lang['Deleted_Num_Items'], $delete_count);
}

//Check if they entered a user who doesn't exist
if ($filter_user != '' && !isset($filter_user_id)) $status_message .= sprintf($lang['Error_No_User'], stripslashes($filter_user));
else {
    $sql = 'SELECT tl.*, u.username FROM ' . TOPICS_TABLE . '_action_log tl, ' . USERS_TABLE . " u
               WHERE tl.user_id = u.user_id
               $filter_by_user_text
               $log_type_text
               ORDER BY $sort $order
               LIMIT $start, " . $board_config['topics_per_page'];

    if(!$result = $db->sql_query($sql)) message_die(GENERAL_ERROR, $lang['Error_Logs_Table'], '', __LINE__, __FILE__, $sql);

    $i = 0;
    while($row = $db->sql_fetchrow($result)){
        //Gotta get the forum info seperate :(
        $sql = 'SELECT f.forum_id, f.forum_name FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . ' f
                       WHERE t.topic_id = ' . $row['topic_id'] . '
                       AND t.forum_id = f.forum_id';
        if(!$forum_data_result = $db->sql_query($sql)) message_die(GENERAL_ERROR, $lang['Error_Logs_Table'], '', __LINE__, __FILE__, $sql);

        $forum_data_row = $db->sql_fetchrow($forum_data_result);

        if (strlen($row['topic_text']) == 255) $row['topic_text'] .= '...';

        $template->assign_block_vars('msgrow', array(
        'ROW_CLASS' => (!(++$i% 2)) ? $theme['td_class1'] : $theme['td_class2'],
        'LOG_ID' => $row['log_id'],
        'USERNAME' => "<a href=\"$phpbb_root_path" . "profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . '=' . $row['user_id'] . '">' . atal_id_2_name($row['user_id']) . '</a>',
        'TOPIC_TITLE' => stripslashes($row['topic_title']),
        'TOPIC_TEXT' => stripslashes($row['topic_text']),
        'ACTION' => $lang['LOG_' . $row['log_action']],
        'TOPIC_STATUS' => (isset($forum_data_row['forum_name'])) ? sprintf($lang['Topic_Exists'], "<a href=\"$phpbb_root_path" . "viewforum.$phpEx?" . POST_FORUM_URL . '=' . $forum_data_row['forum_id'] . '">' . $forum_data_row['forum_name'] . '</a>') : $lang['Topic_Not_Exist'],
        'LOG_TIME' => create_date($lang['DATE_FORMAT'], $row['log_time'], $board_config['board_timezone']))
        );
    }
}
if ($i == 0 || !isset($i))
{
    $template->assign_block_vars('empty_switch', array());
    $template->assign_var('L_NO_ACTIONS', $lang['No_Actions']);
}

$page_title = $lang['Topic_Actions'];

$template->set_filenames(array(
'body' => 'admin_topic_action_logging.tpl')
);

$template->assign_vars(array(
'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
'L_PAGE_NAME' => $page_title,
'L_ORDER' => $lang['Order'],
'L_SORT' => $lang['Sort'],
'L_FILTER_BY_USER' => $lang['Filter_By_User'],
'L_PAGE_DESC' => $lang['Log_Page_Desc'],
'L_FILTER_BY_TYPE' => $lang['Filter_By_Type'],
'L_USERNAME' => $lang['Username'],
'L_TOPIC_TITLE' => $lang['Topic_Title'],
'L_ACTION' => $lang['Action'],
'L_LOG_TIME' => $lang['Log_Time'],
'L_TOPIC_TEXT' => $lang['Topic_Text'],
'L_TOPIC_STATUS' => $lang['Topic_Status'],
'L_VERSION' => $lang['Version'],
'VERSION' => MOD_VERSION,
'L_DELETE' => $lang['Delete'],

'S_USER_FILTER' => stripslashes($filter_user),
'S_MODE' => $mode,
'S_TYPE_SELECT' => topic_action_logging_make_drop_box('log_type'),
'S_MODE_SELECT' => topic_action_logging_make_drop_box('sort'),
'S_ORDER_SELECT' => topic_action_logging_make_drop_box('order'),
'S_MODE_ACTION' => append_sid(basename(__FILE__))
));

atal_do_pagination();

if ($status_message != ''){
    $template->assign_block_vars('statusrow', array());
    $template->assign_vars(array(
    'L_STATUS' => $lang['Status'],
    'I_STATUS_MESSAGE' => $status_message)
    );
}

$template->pparse('body');
include('page_footer_admin.'.$phpEx);

?>
