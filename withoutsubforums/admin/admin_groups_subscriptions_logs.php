<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_groups_subscriptions_logs.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_groups_subscriptions_log.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2004		Damien A
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Groups']['Subscriptions_Logs'] = "$filename?order=DESC&amp;start=0&amp;order_by=groups_transaction_id";
	return;
}

define('IN_PHPBB', TRUE);

$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);
// Langauge File
include($phpbb_root_path.'language/lang_' . $board_config['default_lang'] . '/lang_groups_subscriptions.'.$phpEx);

    $template->set_filenames(array(
        "body" => "admin_groups_subscriptions_logs.tpl")
    );


    // Delete logs if we need to
    if( isset($truncate) && $HTTP_GET_VARS['truncate'] == 'true' )
    {
            $sql = "TRUNCATE {$table_prefix}groups_subscriptions_log";
            $result=mysql_query($sql);

            //$result = $db->sql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't truncate subscription logs", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_delete_finished'],append_sid("admin_groups_subscriptions_logs.$phpEx?order=DESC&start=0&order_by=groups_transaction_id"), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
    }

    // Delete invalid logs if we need to
    if( isset($delete_invalid) && $HTTP_GET_VARS['delete_invalid'] == 'true' )
    {
            $sql = "DELETE FROM {$table_prefix}groups_subscriptions_log WHERE groups_paid = '0'";
            $result=mysql_query($sql);

            //$result = $db->sql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't delete invalid subscription logs", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_delete_finished'],append_sid("admin_groups_subscriptions_logs.$phpEx?order=DESC&start=0&order_by=groups_transaction_id"), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
    }

    // Delete canceled logs if we need to
    if( isset($delete_canceled) && $HTTP_GET_VARS['delete_canceled'] == 'true' )
    {
            $sql = "DELETE FROM {$table_prefix}groups_subscriptions_log WHERE groups_action = 'subscr_cancel'";
            $result=mysql_query($sql);

            //$result = $db->sql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't delete cancelled subscription logs", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_delete_finished'],append_sid("admin_groups_subscriptions_logs.$phpEx?order=DESC&start=0&order_by=groups_transaction_id"), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
    }

    // Delete signup logs if we need to
    if( isset($delete_signups) && $HTTP_GET_VARS['delete_signups'] == 'true' )
    {
            $sql = "DELETE FROM {$table_prefix}groups_subscriptions_log WHERE groups_action = 'subscr_signup'";
            $result=mysql_query($sql);

            //$result = $db->sql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't delete signup subscription logs", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_delete_finished'],append_sid("admin_groups_subscriptions_logs.$phpEx?order=DESC&start=0&order_by=groups_transaction_id"), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
    }

if($order == 'DESC')
{
$order_flip = "ASC";
}
else
{
$order_flip = "DESC";
}
define('ROWS_PER_PAGE', 25);

    $topics_per_pg = ROWS_PER_PAGE;

    $sql = "SELECT count(*) AS total FROM {$table_prefix}groups_subscriptions_log";

    if(!$result = $db->sql_query($sql))
    {
        message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
    }
    $total = $db->sql_fetchrow($result);
    $total_gst = ($total['total'] > 0) ? $total['total'] : 1;

    $pagination = generate_pagination("admin_groups_subscriptions_logs.$phpEx?order=$order&amp;order_by=$order_by", $total_gst, $topics_per_pg, $start)."&nbsp;";

    $template->assign_vars(array(
    "PAGINATION" => $pagination,
    "PAGE_NUMBER" => sprintf($lang['Page_of'], ( floor( $start / $topics_per_pg ) + 1 ), ceil( $total_gst / $topics_per_pg )),

    "L_GOTO_PAGE" => $lang['Goto_page'])
    );

    //
    // This is the main display of the page before the admin has selected
    // any options.
    //

    $sql = "SELECT *
        FROM {$table_prefix}groups_subscriptions_log ORDER BY $order_by $order LIMIT $start, $topics_per_pg";
    $result = $db->sql_query($sql);
    if( !$result )
    {
        message_die(GENERAL_ERROR, "Couldn't obtain subscription logs from database", "", __LINE__, __FILE__, $sql);
    }

    $groups = $db->sql_fetchrowset($result);

    $template->assign_vars(array(

        'L_GROUP_SUBSCRIPTIONS_LOGS_TITLE' => $lang['Group_subscriptions_logs_title'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_ID' => $lang['Group_subscriptions_logs_id'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DATE' => $lang['Group_subscriptions_logs_date'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_PAID' => $lang['Group_subscriptions_logs_paid'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_GROUP' => $lang['Group_subscriptions_logs_group'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_USERNAME' => $lang['Group_subscriptions_logs_username'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_IP' => $lang['Group_subscriptions_logs_ip'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_AMOUNT' => $lang['Group_subscriptions_logs_amount'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_ACTION' => $lang['Group_subscriptions_logs_action'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL' => $lang['Group_subscriptions_logs_delete_all'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_LINK' => $lang['Group_subscriptions_logs_delete_all_link'],

        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_INVALID' => $lang['Group_subscriptions_logs_delete_all_invalid'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_INVALID_LINK' => $lang['Group_subscriptions_logs_delete_all_invalid_link'],

        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_CANCELED' => $lang['Group_subscriptions_logs_delete_all_canceled'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_CANCELED_LINK' => $lang['Group_subscriptions_logs_delete_all_canceled_link'],

        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_SIGNUPS' => $lang['Group_subscriptions_logs_delete_all_signups'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_SIGNUPS_LINK' => $lang['Group_subscriptions_logs_delete_all_signups_link'],


        'L_GROUP_SUBSCRIPTIONS_LOGS_WARN_ALL' => $lang['Group_subscriptions_logs_delete_all_warn'],
        'L_GROUP_SUBSCRIPTIONS_SYMBOL' => $lang['Group_subscriptions_symbol'],
        'L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_TITLE' => $lang['Group_subscriptions_delete_title'],

        'GROUP_SUBSCRIPTIONS_LIST_PAYPAL' => $group_subscriptions_list2,
        'GROUP_SUBSCRIPTIONS_BG_COLOR' => $group_subscriptions_bg_color,



        'U_GROUP_SUBSCRIPTIONS_DATE' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_purchase_date"),
        'U_GROUP_SUBSCRIPTIONS_PAID' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_paid"),
        'U_GROUP_SUBSCRIPTIONS_ID' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_transaction_id"),
        'U_GROUP_SUBSCRIPTIONS_GROUP' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_id_number"),
        'U_GROUP_SUBSCRIPTIONS_USERNAME' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_phpbb_username"),
        'U_GROUP_SUBSCRIPTIONS_IP' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_user_ip"),
        'U_GROUP_SUBSCRIPTIONS_AMOUNT' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_gross_amount"),
        'U_GROUP_SUBSCRIPTIONS_ACTION' => append_sid("./admin_groups_subscriptions_logs.$phpEx?start=0&order=$order_flip&amp;order_by=groups_action"),
        'U_GROUP_SUBSCRIPTIONS_TRUNCATE' => append_sid("./admin_groups_subscriptions_logs.$phpEx?truncate=true"),
        'U_GROUP_SUBSCRIPTIONS_DELETE_ALL_INVALID' => append_sid("./admin_groups_subscriptions_logs.$phpEx?delete_invalid=true"),
        'U_GROUP_SUBSCRIPTIONS_DELETE_ALL_CANCELED' => append_sid("./admin_groups_subscriptions_logs.$phpEx?delete_canceled=true"),
        'U_GROUP_SUBSCRIPTIONS_DELETE_ALL_SIGNUPS' => append_sid("./admin_groups_subscriptions_logs.$phpEx?delete_signups=true"),
        'S_GROUP_SUBSCRIPTIONS_ACTION' => append_sid("admin_groups_subscriptions.php"))
    );

    //
    // Loop throuh the rows of groups setting block vars for the template.
    //
    for($i = 0; $i < count($groups); $i++)
    {
$groups_date = ''.$groups[$i]['groups_purchase_date'].'';

$groups_date           = date('m/d/y - g:i:s a', $groups_date);
$groups_paid           = ''.$groups[$i]['groups_paid'].'';
$groups_id_number      = ''.$groups[$i]['groups_id_number'].'';
$groups_phpbb_username = ''.$groups[$i]['groups_phpbb_username'].'';
$groups_phpbb_id       = ''.$groups[$i]['groups_phpbb_id'].'';
$groups_user_ip        = ''.$groups[$i]['groups_user_ip'].'';
$groups_gross_amount   = ''.$groups[$i]['groups_gross_amount'].'';
$groups_action         = ''.$groups[$i]['groups_action'].'';


if ($groups_paid == "1")
{
$groups_paid_display       = $lang['Group_subscriptions_logs_paid_yes'];
$groups_paid_display_color = $lang['Group_subscriptions_logs_paid_yes_color'];
}
elseif ($groups_paid == "0")
{
$groups_paid_display       = $lang['Group_subscriptions_logs_paid_no'];
$groups_paid_display_color = $lang['Group_subscriptions_logs_paid_no_color'];
}
else
{
}


    // Alternate table colors
        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];


        $template->assign_block_vars("groups", array(
            "ROW_COLOR" => "#" . $row_color,
            "ROW_CLASS" => $row_class,
            "SUBSCRIPTION_ENABLED" => $groups[$i]['subscription_enabled'],
            "SUBSCRIPTION_COST" => $groups[$i]['subscription_cost'],
            "GROUP_P2" => $groups[$i]['p2'],
            "GROUP_T2" => $groups[$i]['t2'],
            "GROUP_T2_DISPLAY" => $cycle_display,
            "GROUP_DAYS" => $lang['Group_subscriptions_days'],
            "GROUP_WEEKS" => $lang['Group_subscriptions_weeks'],
            "GROUP_MONTHS" => $lang['Group_subscriptions_months'],
            "GROUP_YEARS" => $lang['Group_subscriptions_years'],
            "GROUP_DESCRIPTION" => $groups[$i]['group_description'],
            "GROUP_LIST_ID" => $groups[$i]['group_id'],
            "GROUP_GROUPS_ID_NUMBER" => ''.$groups[$i]['groups_id_number'].'',
            "GROUP_TRANS_ID" => ''.$groups[$i]['groups_transaction_id'].'',
            "GROUP_PAID" => ''.$groups_paid_display.'',
            "GROUP_PAID_COLOR" => ''.$groups_paid_display_color.'',
            "GROUP_DATE" => ''.$groups_date.'',
            "GROUP_USERNAME" => ''.$groups_phpbb_username.'',
            "GROUP_USER_IP" => ''.$groups_user_ip.'',
            "GROUP_GROSS_AMOUNT" => ''.$groups_gross_amount.'',
            "GROUP_ACTION" => ''.$groups_action.'',
            'U_GROUP_ID' => append_sid("../groupcp.php?g=$groups_id_number"),
            'U_USER_ID' => append_sid("../profile.php?mode=viewprofile&u=$groups_phpbb_id")
            )
        );
    }

    //
    // Spit out the page.
    //
    $template->pparse("body");

    //
    // Page Footer
    //
    include('./page_footer_admin.'.$phpEx);

?>
