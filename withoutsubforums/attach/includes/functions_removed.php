// $Id: functions_removed.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $ //

// Temp File containing attach mod functions I've removed.

//
// Setup Basic Authentication (auth.php)
// Minerva :: Hopefully Unused Now ## ToonArmy
function attach_setup_basic_auth($type, &$auth_fields, &$a_sql)
{
    switch( $type )
    {
        case AUTH_ALL:
            $a_sql .= ', a.auth_attachments, a.auth_download';
            $auth_fields[] = 'auth_attachments';
            $auth_fields[] = 'auth_download';
            break;

        case AUTH_ATTACH:
            $a_sql = 'a.auth_attachments';
            $auth_fields = array('auth_attachments');
            break;

        case AUTH_DOWNLOAD:
            $a_sql = 'a.auth_download';
            $auth_fields = array('auth_download');
            break;

        default:
            break;
    }
}

//
// Setup Forum Authentication (admin_forumauth.php)
//
// Minerva :: Hopefully Unused Now ## ToonArmy
function attach_setup_forum_auth(&$simple_auth_ary, &$forum_auth_fields, &$field_names)
{
    global $lang;

    //
    // Add Attachment Auth
    //
    //                  Post Attachments
    $simple_auth_ary[0][] = AUTH_MOD;
    $simple_auth_ary[1][] = AUTH_MOD;
    $simple_auth_ary[2][] = AUTH_MOD;
    $simple_auth_ary[3][] = AUTH_MOD;
    $simple_auth_ary[4][] = AUTH_MOD;
    $simple_auth_ary[5][] = AUTH_MOD;
    $simple_auth_ary[6][] = AUTH_MOD;

    //                  Download Attachments
    $simple_auth_ary[0][] = AUTH_ALL;
    $simple_auth_ary[1][] = AUTH_ALL;
    $simple_auth_ary[2][] = AUTH_REG;
    $simple_auth_ary[3][] = AUTH_ACL;
    $simple_auth_ary[4][] = AUTH_ACL;
    $simple_auth_ary[5][] = AUTH_MOD;
    $simple_auth_ary[6][] = AUTH_MOD;

    $forum_auth_fields[] = 'auth_attachments';
    $field_names['auth_attachments'] = $lang['Auth_attach'];

    $forum_auth_fields[] = 'auth_download';
    $field_names['auth_download'] = $lang['Auth_download'];
}

//
// Setup Usergroup Authentication (admin_ug_auth.php)
//
// Minerva :: Hopefully Unused Now ## ToonArmy
function attach_setup_usergroup_auth(&$forum_auth_fields, &$auth_field_match, &$field_names)
{
    global $lang;

    //
    // Post Attachments
    //
    $forum_auth_fields[] = 'auth_attachments';
    $auth_field_match['auth_attachments'] = AUTH_ATTACH;
    $field_names['auth_attachments'] = $lang['Auth_attach'];

    //
    // Download Attachments
    //
    $forum_auth_fields[] = 'auth_download';
    $auth_field_match['auth_download'] = AUTH_DOWNLOAD;
    $field_names['auth_download'] = $lang['Auth_download'];
}

//
// Setup Viewtopic and topic_review Authentication for f_access
//
// Minerva :: Hopefully Unused Now ## ToonArmy
function attach_setup_viewtopic_auth(&$order_sql, &$sql)
{
    $order_sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $order_sql);
    $sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $sql);
}

//
// Setup s_auth_can in viewforum and viewtopic
//
// Minerva :: Hopefully Unused Now ## ToonArmy
function attach_build_auth_levels($is_auth, &$s_auth_can)
{
    global $lang, $attach_config, $phpEx, $forum_id;

    if (intval($attach_config['disable_mod']))
    {
        return;
    }

    // If you want to have the rules window link within the forum view too, comment out the two lines, and comment the third line
//  $rules_link = '(<a href=$phpbb_root_path . "attach_rules.' . $phpEx . '?f=' . $forum_id . '" target="_blank">Rules</a>)';
//  $s_auth_can .= ( ( $is_auth['auth_attachments'] ) ? $rules_link . ' ' . $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';
    $s_auth_can .= ( ( $is_auth['auth_attachments'] && $is_auth['auth_post'] ) ? $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';

    $s_auth_can .= (($is_auth['auth_download']) ? $lang['Rules_download_can'] : $lang['Rules_download_cannot'] ) . '<br />';
}

// Displaying.php

//
// Initializes some templating variables for displaying Attachments in Posts
//
function init_display_post_attachments($switch_attachment)
{
    global $attach_config, $db, $is_auth, $template, $lang, $postrow, $total_posts, $attachments, $forum_row, $forum_topic_data;

    if ( (empty($forum_topic_data)) && (!empty($forum_row)) )
    {
        $switch_attachment = $forum_row['topic_attachment'];
    }

    if ( (intval($switch_attachment) == 0) || (intval($attach_config['disable_mod'])) || (!( ($is_auth['auth_download']) && ($is_auth['auth_view']))))
    {
        return;
    }

    $post_id_array = array();

    for ($i = 0; $i < $total_posts; $i++)
    {
        if ($postrow[$i]['post_attachment'] == 1)
        {
            $post_id_array[] = $postrow[$i]['post_id'];
        }
    }

    if (count($post_id_array) == 0)
    {
        return;
    }

    $rows = get_attachments_from_post($post_id_array);
    $num_rows = count($rows);

    if ($num_rows == 0)
    {
        return;
    }

    @reset($attachments);

    for ($i = 0; $i < $num_rows; $i++)
    {
        $attachments['_' . $rows[$i]['post_id']][] = $rows[$i];
    }

    //init_display_template('body', '{postrow.ATTACHMENTS}');

    init_complete_extensions_data();
/*
	$template->assign_block_vars('postrow', array(
		'S_ATTACHMENTS' => TRUE,
	));
*/
    $template->assign_vars(array(
        'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
        'L_KILOBYTE' => $lang['KB'])
    );
//print_r ($attachments);
}

