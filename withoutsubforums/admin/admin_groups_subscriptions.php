<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_groups_subscriptions.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_groups_subscriptions.php
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
	$module['Groups']['Subscriptions'] = $filename;
	return;
}

define('IN_PHPBB', TRUE);

$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

// Group Subscription Langauge File
if (file_exists($phpbb_root_path.'language/lang_' . $board_config['default_lang'] . '/lang_groups_subscriptions.'.$phpEx))
{
	include($phpbb_root_path.'language/lang_' . $board_config['default_lang'] . '/lang_groups_subscriptions.'.$phpEx);
}
else
{
	include($phpbb_root_path.'language/lang_english/lang_groups_subscriptions.'.$phpEx);
}

    $template->set_filenames(array(
        "body" => "admin_groups_subscriptions.tpl")
    );

    // Grab paypal email
    $sql = 'SELECT * FROM ' . GROUP_SUBSCRIPTIONS_TABLE;

    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain group subscription information', '', __LINE__, __FILE__, $sql);
    }

    while( $row = $db->sql_fetchrow($result) )
    {
        $group_subscriptions_list2 = ''.$row['paypal_email'].'';
        $group_subscriptions_bg_color = ''.$row['paypal_bg_color'].'';
        $group_subscriptions_currency = ''.$row['paypal_currency'].'';
    }

	$db->sql_freeresult($result);

    if ($group_subscriptions_bg_color == "W")
    {
        $bgcolor_display = $lang['Group_subscriptions_white'];
    }
    elseif ($group_subscriptions_bg_color == "B")
    {
        $bgcolor_display = $lang['Group_subscriptions_black'];
    }
    else
    {
        $bgcolor_display = $group_subscriptions_bg_color;
    }

    if ($group_subscriptions_currency == "USD")
    {
        $group_subscriptions_currency_display = $lang['Group_subscriptions_currency_usd'];
    }
    elseif ($group_subscriptions_currency == "GBP")
    {
        $group_subscriptions_currency_display = $lang['Group_subscriptions_currency_gbp'];
    }
    elseif ($group_subscriptions_currency == "EUR")
    {
        $group_subscriptions_currency_display = $lang['Group_subscriptions_currency_eur'];
    }
    elseif ($group_subscriptions_currency == "CAD")
    {
        $group_subscriptions_currency_display = $lang['Group_subscriptions_currency_cad'];
    }
    elseif ($group_subscriptions_currency == "JPY")
    {
        $group_subscriptions_currency_display = $lang['Group_subscriptions_currency_jpy'];
    }
    else
    {
    }

    //
    // This is the main display of the page before the admin has selected
    // any options.
    //
    $sql = "SELECT *
        FROM {$table_prefix}groups WHERE group_single_user = 0";
    $result = $db->sql_query($sql);
    if( !$result )
    {
        message_die(GENERAL_ERROR, "Couldn't obtain groups from database", "", __LINE__, __FILE__, $sql);
    }

    $groups = $db->sql_fetchrowset($result);

    $template->assign_vars(array(
        'L_GROUP_SUBSCRIPTIONS' => $lang['Group_subscriptions'],
        'L_GROUP_SUBSCRIPTIONS_PAYPAL' => $lang['Group_subscriptions_paypal'],
        'L_GROUP_SUBSCRIPTIONS_PAYPAL_EXPLAIN' => $lang['Group_subscriptions_paypal_explain'],
        'L_GROUP_SUBSCRIPTIONS_COLOR_CURRENT' => ''.$bgcolor_display.'',
        'L_GROUP_SUBSCRIPTIONS_BLACK' => $lang['Group_subscriptions_black'],
        'L_GROUP_SUBSCRIPTIONS_WHITE' => $lang['Group_subscriptions_white'],
        'L_GROUP_SUBSCRIPTIONS_SETTINGS' => $lang['Group_subscriptions_settings'],
        'L_GROUP_SUBSCRIPTIONS_EXPLAIN' => $lang['Group_subscriptions_explain'],
        'L_GROUP_SUBSCRIPTIONS_EXPLAIN_SETTINGS' => $lang['Group_subscriptions_explain_settings'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_1' => $lang['Group_subscriptions_filed1'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_2' => $lang['Group_subscriptions_filed2'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_3' => $lang['Group_subscriptions_filed3'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_4' => $lang['Group_subscriptions_filed4'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_5' => $lang['Group_subscriptions_filed5'],
        'L_GROUP_SUBSCRIPTIONS_FIELD_6' => $lang['Group_subscriptions_filed6'],
        'L_GROUP_SUBSCRIPTIONS_DEMO_NAME' => $lang['Group_subscriptions_demo_name'],
        'L_GROUP_SUBSCRIPTIONS_DEMO_DESCRIPTION' => $lang['Group_subscriptions_demo_description'],
        'L_GROUP_DAYS' => $lang['Group_subscriptions_days'],
        'L_GROUP_WEEKS' => $lang['Group_subscriptions_weeks'],
        'L_GROUP_MONTHS' => $lang['Group_subscriptions_months'],
        'L_GROUP_YEARS' => $lang['Group_subscriptions_years'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_DISPLAY' => $group_subscriptions_currency_display,
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_CURRENT' => $group_subscriptions_currency,
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_USD' => $lang['Group_subscriptions_currency_usd'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_GBP' => $lang['Group_subscriptions_currency_gbp'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_EUR' => $lang['Group_subscriptions_currency_eur'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_CAD' => $lang['Group_subscriptions_currency_cad'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_JPY' => $lang['Group_subscriptions_currency_jpy'],
        'L_GROUP_SUBSCRIPTIONS_CURRENCY_QUESTION' => $lang['Group_subscriptions_currency_question'],
        'L_GROUP_SUBSCRIPTIONS_PAYPAL_BG' => $lang['Group_subscriptions_paypal_bg'],

        'GROUP_SUBSCRIPTIONS_LIST_PAYPAL' => $group_subscriptions_list2,
        'GROUP_SUBSCRIPTIONS_BG_COLOR' => $group_subscriptions_bg_color,
        'S_GROUP_SUBSCRIPTIONS_ACTION' => append_sid("admin_groups_subscriptions.php"))
    );

    //
    // Loop throuh the rows of groups setting block vars for the template.
    //
    for($i = 0; $i < count($groups); $i++)
    {
        if ($groups[$i]['t2'] == "D")
        {
            $cycle_display = $lang['Group_subscriptions_days'];
        }
        elseif ($groups[$i]['t2'] == "W")
        {
            $cycle_display = $lang['Group_subscriptions_weeks'];
        }
        elseif ($groups[$i]['t2'] == "M")
        {
            $cycle_display = $lang['Group_subscriptions_months'];
        }
        elseif ($groups[$i]['t2'] == "Y")
        {
            $cycle_display = $lang['Group_subscriptions_years'];
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
            "GROUP_NAME" => $groups[$i]['group_name'],
            )
        );
    }

    // Update paypal email if we need to
    if( $HTTP_POST_VARS['update_groups_subscriptions_paypal'] == 'true' )
    {

            $sql = "UPDATE {$table_prefix}groups_subscriptions
                        SET paypal_email = '{$paypal_email}', paypal_bg_color = '{$paypal_bgcolor}', paypal_currency = '{$paypal_currency}'
                        ";

            //$sql = "UPDATE " . $table_prefix . "groups_subscriptions SET paypal_email = '" . $paypal_email . "', paypal_bg_color = '" . $paypal_bgcolor . "', paypal_currency = '" . $paypal_currency . "'";

            $result=mysql_query($sql);
            //$result = $db->sql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't update PayPal email", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_finished'],append_sid("admin_groups_subscriptions.".$phpEx), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
    }
    // Update group tables if we need to
    if( $HTTP_POST_VARS['update_groups_subscriptions'] == 'true' )
    {
            $sql = "UPDATE {$table_prefix}groups
                        SET subscription_cost = '{$subscription_cost}',
                            p2 = '{$p2}',
                            t2 = '{$t2}',
                            subscription_enabled = '{$subscription_enabled}',
                            subscription_period = '{$p2} {$t2}'
                            WHERE group_id = {$group_id}";
            $result=mysql_query($sql);
            //if( !$result )
            //{
            //    message_die(GENERAL_ERROR, "Couldn't update groups", "", __LINE__, __FILE__, $sql);
            //}

    $bye_message = sprintf($lang['Group_subscriptions_finished'],append_sid("admin_groups_subscriptions.".$phpEx), append_sid("index.".$phpEx."?pane=right"));
    // Say bye bye
    message_die(GENERAL_MESSAGE, $bye_message);
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
