<?php
//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- mod : add-on country flags for pcp ------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: def_userfields.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : def_userfields.php
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

//--------------------------------------------------------------------------------------------------
//
// tables_linked array
//
//      key = identifier (used in [*])
//
//          sql_id      : id used in sql request,
//          sql_join    : sql FROM statement
//          sql_where   : WHERE statement
//          sql_order   : ORDER BY statement
//
//--------------------------------------------------------------------------------------------------
$tables_linked = array(
    'BUDDY_MY' => array(
        'sql_id' => 'i',
        'sql_join' => 'LEFT JOIN [cst.BUDDYS_TABLE] AS [BUDDY_MY] ON [BUDDY_MY].user_id=[view.user_id] AND [BUDDY_MY].buddy_id=[USERS].user_id',
    ),

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
    'FLAGS' => array(
        'sql_id' => 'f',
        'sql_join' => 'LEFT JOIN [cst.FLAGS_TABLE] AS [FLAGS].flag_image = [USERS].user_flag',
        'sql_where' => '[USERS].user_id <> ANONYMOUS',
    ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

    'BUDDY_OF' => array(
        'sql_id' => 'b',
        'sql_join' => 'LEFT JOIN [cst.BUDDYS_TABLE] AS [BUDDY_OF] ON [BUDDY_OF].user_id=[USERS].user_id AND [BUDDY_OF].buddy_id=[view.user_id]',
    ),
    'CUSTOM_RANKS' => array(
        'sql_id' => 'cr',
        'sql_join' => 'LEFT JOIN [cst.RANKS_TABLE] AS [CUSTOM_RANKS] ON ([RANKS].rank_id = [USERS].user_custom_rank AND [RANKS].rank_special <> 0)',
        'sql_where' => '',
        'sql_order' => '',
    ),
    'RANKS' => array(
        'sql_id' => 'r',
        'sql_join' => 'LEFT JOIN [cst.RANKS_TABLE] AS [RANKS] ON ([USERS].user_rank > 0 AND [RANKS].rank_special <> 0 AND [RANKS].rank_id = [USERS].user_rank) OR ([USERS].user_rank = 0 AND [RANKS].rank_special = 0 AND [USERS].user_posts >= [RANKS].rank_min AND [USERS].user_posts < [RANKS].rank_max)',
    ),
    'RANKS_SPECIAL' => array(
        'sql_id' => 'rs',
        'sql_join' => '[cst.RANKS_TABLE] AS [RANKS_SPECIAL]',
        'sql_where' => '[RANKS_SPECIAL].rank_special = 1',
        'sql_order' => '[RANKS_SPECIAL].rank_title',
    ),
    'THEMES_TABLE' => array(
        'sql_id' => 'th',
        'sql_join' => '[cst.THEMES_TABLE] AS [THEMES_TABLE]',
        'sql_order' => '[THEMES_TABLE].style_name, [THEMES_TABLE].themes_id',
    ),
    'USERS' => array(
        'sql_id' => 'u',
        'sql_join' => '[cst.USERS_TABLE] AS [USERS]',
    ),
);

//--------------------------------------------------------------------------------------------------
//
// $values_list array
//
//      key = values list name
//          func    = special function
//          table   = use tables to build the list
//              main    : main table the list is based on,
//              key     : key field,
//              txt     : text field,
//              img     : image field
//          values  = field values
//              txt     : text or $lang key entry to display
//              img     : img url or $images key entry to display
//--------------------------------------------------------------------------------------------------
$values_list = array(
    'list_yes_no' => array(
        'values' => array(
            1 => array('txt' => 'Yes', 'img' => ''),
            0 => array('txt' => 'No', 'img' => ''),
        ),
    ),
    'list_yes_no_friend' => array(
        'values' => array(
            1 => array('txt' => 'Yes', 'img' => ''),
            0 => array('txt' => 'No', 'img' => ''),
            2 => array('txt' => 'Friend_only', 'img' => ''),
        ),
    ),
    'list_gender' => array(
        'values' => array(
            0 => array('txt' => 'No_gender_specify', 'img' => 'No_gender_specify'),
            1 => array('txt' => 'Male', 'img' => 'Male'),
            2 => array('txt' => 'Female', 'img' => 'Female'),
        ),
    ),

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
    'list_holidays' => array(
        'values' => array(
            2 => array('txt' => 'No_holidays_specify', 'img' => 'No_holidays_specify'),
            0 => array('txt' => 'No', 'img' => 'No'),
            1 => array('txt' => 'Yes', 'img' => 'Yes'),
        ),
    ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

    'list_langs' => array(
        'func' => 'get_langs_list',
    ),
    'list_timezones' => array(
        'func' => 'get_timezones_list',
    ),
    'list_styles' => array(
        'table' => array(
            'main'  => 'THEMES_TABLE',
            'key'   => '[THEMES_TABLE].themes_id',
            'txt'   => '[THEMES_TABLE].style_name',
            'img'   => '',
        ),
    ),
    'list_special_ranks' => array(
        'table' => array(
            'main'  => 'RANKS_SPECIAL',
            'key'   => '[RANKS_SPECIAL].rank_id',
            'txt'   => '[RANKS_SPECIAL].rank_title',
            'img'   => '[RANKS_SPECIAL].rank_image',
        ),
        'values' => array(
            '' => array('txt' => 'None', 'img' => ''),
        ),
    ),
);

//--------------------------------------------------------------------------------------------------
//
// $classes_fields array
//
//      key = name of the class,
//
//          config_field    : the config table field set by the admin to restrict the class display to everybody
//          admin_field     : the users table field set by the admin to restrict the class display to a particular user,
//          user_field      : the users table field set by the user (preferences) to restrict the class display,
//          sql_def         : sql definition of the condition field
//
//--------------------------------------------------------------------------------------------------
$classes_fields = array(
    'email' => array(
        'config_field'  => 'allow_viewemail',
        'admin_field'   => 'user_allow_email',
        'user_field'    => 'user_viewemail',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewemail] <> 0 OR [board.allow_viewemail_over] <> 1 ) AND [USERS].user_allow_email = 1 AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_viewemail = 1 OR ([board.allow_viewemail] = 1 AND [board.allow_viewemail_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_viewemail = 2 OR ([board.allow_viewemail] = 2 AND [board.allow_viewemail_over] = 1) ) ) ) )',
    ),
    'generic' => array(
        'config_field'  => '',
        'admin_field'   => '',
        'user_field'    => '',
        'sql_def'       => '',
    ),
    'messangers' => array(
        'config_field'  => 'allow_viewmessengers',
        'admin_field'   => 'user_allow_messanger',
        'user_field'    => 'user_viewmessanger ',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewmessengers] <> 0 OR [board.allow_viewmessengers_over] <> 1 ) AND [USERS].user_allow_messanger = 1 AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_viewmessanger  = 1 OR ([board.allow_viewmessengers] = 1 AND [board.allow_viewmessengers_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_viewmessanger  = 2 OR ([board.allow_viewmessengers] = 2 AND [board.allow_viewmessengers_over] = 1) ) ) ) )',
    ),
    'pm' => array(
        'config_field'  => 'allow_viewpm',
        'admin_field'   => 'user_allow_pm',
        'user_field'    => 'user_viewpm',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewpm] <> 0 OR [board.allow_viewpm_over] <> 1 ) AND [USERS].user_allow_pm = 1 AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_viewpm = 1 OR ([board.allow_viewpm] = 1 AND [board.allow_viewpm_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_viewpm = 2 OR ([board.allow_viewpm] = 2 AND [board.allow_viewpm_over] = 1) ) ) ) )',
    ),
    'real' => array(
        'config_field'  => 'allow_viewreal',
        'admin_field'   => 'user_allow_real',
        'user_field'    => 'user_viewreal',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewreal] <> 0 OR [board.allow_viewreal_over] <> 1 ) AND [USERS].user_allow_real = 1 AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_viewreal = 1 OR ([board.allow_viewreal] = 1 AND [board.allow_viewreal_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_viewreal = 2 OR ([board.allow_viewreal] = 2 AND [board.allow_viewreal_over] = 1) ) ) ) )',
    ),
    'viewonline' => array(
        'config_field'  => 'allow_viewonline',
        'admin_field'   => '',
        'user_field'    => 'user_allow_viewonline',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewonline] <> 0 OR [board.allow_viewonline_over] <> 1 ) AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_allow_viewonline = 1 OR ([board.allow_viewonline] = 1 AND [board.allow_viewonline_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_allow_viewonline = 2 OR ([board.allow_viewonline] = 2 AND [board.allow_viewonline_over] = 1) ) ) ) )',
    ),
    'webdisplay' => array(
        'config_field'  => 'allow_viewwebsite',
        'admin_field'   => 'user_allow_website',
        'user_field'    => 'user_viewwebsite',
        'sql_def'       => '[USERS].user_id = [view.user_id] OR ( ( [BUDDY_MY].buddy_ignore <> 1 OR [BUDDY_MY].buddy_ignore IS NULL ) AND ( [board.allow_viewwebsite] <> 0 OR [board.allow_viewwebsite_over] <> 1 ) AND [USERS].user_allow_website = 1 AND ( [BUDDY_OF].buddy_visible = 1 OR ( [USERS].user_viewwebsite = 1 OR ([board.allow_viewwebsite] = 1 AND [board.allow_viewwebsite_over] = 1) ) OR ( [BUDDY_OF].buddy_ignore = 0 AND ( [USERS].user_viewwebsite = 2 OR ([board.allow_viewwebsite] = 2 AND [board.allow_viewwebsite_over] = 1) ) ) ) )',
    ),
);

//--------------------------------------------------------------------------------------------------
//
// $user_fields array
//
//      key = name of the field,
//
//          field_name  : Field name
//          lang_key    : Legend of the field
//          explain     : Explanation of the field
//          image       : Image
//          title       : Image title
//          class       : Class
//          type        : Type
//          link        : Link
//          dsp_func    : Display function
//          leg         : Display the legend
//          txt         : Display the text value
//          img         : Display the image value
//          crlf        : Text to next line
//          lnk         : Use the link
//          style       : Span display
//          input_id    : Config name field
//          user_only   : Not a config value
//          system      : System field
//          get_mode    : Get mode
//          get_func    : Get function
//          chk_func    : Check function
//          default     : Default value
//          values      : Values list
//          auth        : Auth level
//          ind         : Option address
//          dft         : Checked by default
//          rqd         : Force the selection
//          hidden      : Add the field as hidden
//          sql_def     : SQL definition
//
//--------------------------------------------------------------------------------------------------
$user_fields = array(

    // email informations
    'user_email' => array(
        'lang_key'     => 'Email_address',
        'class'        => 'email',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_user_email',
    ),

    // generic informations
    'user_active' => array(
        'lang_key'     => 'User_status',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
        'auth'         => ADMIN,
    ),
    'user_allow_email' => array(
        'lang_key'     => 'User_allow_email',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
        'auth'         => ADMIN,
    ),
    'user_allow_messanger' => array(
        'lang_key'     => 'User_allow_messanger',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'auth'         => ADMIN,
        'values'       => 'list_yes_no',
    ),
    'user_allow_pm' => array(
        'lang_key'     => 'User_allow_pm',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'auth'         => ADMIN,
        'values'       => 'list_yes_no',
    ),
    'user_allow_real' => array(
        'lang_key'     => 'User_allow_real',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
        'auth'         => ADMIN,
    ),
    'user_allow_sig' => array(
        'lang_key'     => 'User_allow_sig',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'auth'         => ADMIN,
        'values'       => 'list_yes_no',
    ),
    'user_allow_viewonline' => array(
        'lang_key'     => 'View_user',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_allow_website' => array(
        'lang_key'     => 'User_allow_website',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'auth'         => ADMIN,
        'values'       => 'list_yes_no',
    ),
    'user_allowavatar' => array(
        'lang_key'     => 'User_allowavatar',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'auth'         => ADMIN,
        'values'       => 'list_yes_no',
    ),
    'user_allowbbcode' => array(
        'lang_key'     => 'Always_bbcode',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_allowhtml' => array(
        'lang_key'     => 'Always_html',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_yes_no',
    ),
    'user_allowsmile' => array(
        'lang_key'     => 'Always_smile',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_attachsig' => array(
        'lang_key'     => 'Always_add_sig',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_yes_no',
    ),
    'user_buddy_friend_display' => array(
        'lang_key'     => 'Buddy_friend_display',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_buddy_friend_of_display' => array(
        'lang_key'     => 'Buddy_friend_of_display',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_buddy_ignore_display' => array(
        'lang_key'     => 'Buddy_ignore_display',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_buddy_ignored_by_display' => array(
        'lang_key'     => 'Buddy_ignored_by_display',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_friend' => array(
        'lang_key'     => 'Friend_of',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '(CASE WHEN [BUDDY_OF].buddy_ignore = 0 THEN 1 ELSE 0 END)',
    ),
    'user_groups' => array(
        'lang_key'     => 'Usergroups',
        'class'        => 'generic',
        'type'         => 'ADVANCED',
        'dsp_func'     => 'pcp_output_usergroups',
    ),
    'user_ignore' => array(
        'lang_key'     => 'Ignore_by',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '(CASE WHEN [BUDDY_OF].buddy_ignore = 1 THEN 1 ELSE 0 END)',
    ),
    'user_my_friend' => array(
        'lang_key'     => 'Friend',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '(CASE WHEN [BUDDY_MY].buddy_ignore = 0 THEN 1 ELSE 0 END)',
        'dsp_func'     => 'pcp_output_my_friend',
    ),
    'user_my_ignore' => array(
        'lang_key'     => 'Ignore',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '(CASE WHEN [BUDDY_MY].buddy_ignore = 1 THEN 1 ELSE 0 END)',
        'dsp_func'     => 'pcp_output_my_ignore',
    ),
    'user_my_visible' => array(
        'lang_key'     => 'Always_visible',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '[BUDDY_MY].buddy_visible',
    ),
    'user_notify' => array(
        'lang_key'     => 'Always_notify',
        'explain'      => 'Always_notify_explain',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_notify_pm' => array(
        'lang_key'     => 'Notify_on_privmsg',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_popup_pm' => array(
        'lang_key'     => 'Popup_on_privmsg',
        'explain'      => 'Popup_on_privmsg_explain',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_privmsgs_per_page' => array(
        'lang_key'     => 'Privmsgs_per_page',
        'type'         => 'SMALLINT',
        'default'      => '5',
        'class'        => 'generic',
    ),
    'user_viewavatar' => array(
        'lang_key'     => 'Public_view_avatar',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_viewemail' => array(
        'lang_key'     => 'Public_view_email',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_viewimg' => array(
        'lang_key'     => 'Public_view_img',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_viewmessanger' => array(
        'lang_key'     => 'Public_view_messengers',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_viewpm' => array(
        'lang_key'     => 'Public_view_pm',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_viewreal' => array(
        'lang_key'     => 'Public_view_real_info',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_viewsig' => array(
        'lang_key'     => 'Public_view_sig',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
    ),
    'user_viewwebsite' => array(
        'lang_key'     => 'Public_view_website',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no_friend',
    ),
    'user_visible' => array(
        'lang_key'     => 'Always_visible',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'sql_def'      => '[BUDDY_OF].buddy_visible',
    ),
    'user_watched_topics_per_page' => array(
        'lang_key'     => 'Watched_topics_per_page',
        'type'         => 'SMALLINT',
        'default'      => '15',
        'class'        => 'generic',
    ),
    'username' => array(
        'lang_key'     => 'Username',
        'class'        => 'generic',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_username',
    ),

    // messangers informations
    'user_aim' => array(
        'lang_key'     => 'AIM',
        'class'        => 'messangers',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_aim',
    ),
    'user_icq' => array(
        'lang_key'     => 'ICQ',
        'class'        => 'messangers',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_icq',
    ),
    'user_msnm' => array(
        'lang_key'     => 'MSNM',
        'class'        => 'messangers',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_msnm',
    ),
    'user_yim' => array(
        'lang_key'     => 'YIM',
        'class'        => 'messangers',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_yim',
    ),

    // pm informations
    'user_pm' => array(
        'lang_key'     => 'Private_Message',
        'image'        => 'icon_pm',
        'title'        => 'Send_private_message',
        'class'        => 'pm',
        'type'         => 'TINYINT',
        'sql_def'      => '1',
        'dsp_func'     => 'pcp_output_user_pm',
    ),

    // real informations
    'user_age' => array(
        'lang_key'     => 'Age',
        'class'        => 'real',
        'type'         => 'DATE',
        'dsp_func'     => 'pcp_output_age',
    ),
    'user_birthday' => array(
        'lang_key'     => 'Birthday',
        'class'        => 'real',
        'type'         => 'BIRTHDAY',
        'get_func'     => 'mods_settings_get_birthday',
        'chk_func'     => 'mods_settings_check_birthday',
    ),
    'user_cellular' => array(
        'lang_key'     => 'Cellular',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_from' => array(
        'lang_key'     => 'Location',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
    'user_flag' => array(
        'lang_key' => 'Country_Flag',
        'class' => 'real',
        'type' => 'FLAG',
        'dsp_func' => 'pcp_output_flag',
    ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
    'user_holidays' => array(
        'lang_key'     => 'Holidays',
        'class'        => 'real',
        'type'         => 'TINYINT',
        'dsp_func'     => 'pcp_output_holidays',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_holidays',
    ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

    'user_gender' => array(
        'lang_key'     => 'Gender',
        'class'        => 'real',
        'type'         => 'TINYINT',
        'dsp_func'     => 'pcp_output_gender',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_gender',
    ),
    'user_home_fax' => array(
        'lang_key'     => 'Home_fax',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_home_phone' => array(
        'lang_key'     => 'Home_phone',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_interests' => array(
        'lang_key'     => 'Interests',
        'class'        => 'real',
        'type'         => 'TEXT',
    ),
    'user_occ' => array(
        'lang_key'     => 'Occupation',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_pager' => array(
        'lang_key'     => 'Pager',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_realname' => array(
        'lang_key'     => 'Realname',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_work_fax' => array(
        'lang_key'     => 'Work_fax',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),
    'user_work_phone' => array(
        'lang_key'     => 'Work_phone',
        'class'        => 'real',
        'type'         => 'VARCHAR',
    ),

    // viewonline informations
    'user_forums_stat' => array(
        'lang_key'     => 'Most_active_forum',
        'class'        => 'viewonline',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_forums_stat',
    ),
    'user_lastvisit' => array(
        'lang_key'     => 'Last_visit',
        'class'        => 'viewonline',
        'type'         => 'DATETIME',
    ),
    'user_online' => array(
        'lang_key'     => 'Online',
        'class'        => 'viewonline',
        'type'         => 'TINYINT',
        'sql_def'      => '(CASE WHEN [USERS].user_session_time >= ([time]-300) THEN 1 ELSE 0 END)',
        'dsp_func'     => 'pcp_output_user_online',
    ),
    'user_posts' => array(
        'lang_key'     => 'Total_posts',
        'class'        => 'viewonline',
        'type'         => 'MEDIUMINT',
    ),
    'user_posts_stat' => array(
        'lang_key'     => 'User_posts',
        'class'        => 'viewonline',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_posts_stat',
    ),
    'user_regdate' => array(
        'lang_key'     => 'Joined',
        'class'        => 'viewonline',
        'type'         => 'DATE',
        'dsp_func'     => 'pcp_output_regdate',
    ),
    'user_session_time' => array(
        'lang_key'     => 'User_session_time',
        'class'        => 'viewonline',
        'type'         => 'DATETIME',
    ),
    'user_topics_stat' => array(
        'lang_key'     => 'Most_active_topic',
        'class'        => 'viewonline',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_topics_stat',
    ),

    // webdisplay informations
    'user_avatar' => array(
        'lang_key'     => 'Avatar',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_avatar',
    ),
    'user_custom_rank' => array(
        'lang_key'     => 'User_custom_rank',
        'class'        => 'webdisplay',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_rank_title',
        'user_only'    => true,
        'get_mode'     => 'LIST_DROP',
        'values'       => 'list_special_ranks',
        'auth'         => USER,
        'sql_def'      => '[CUSTOM_RANKS].rank_id',
    ),
    'user_dateformat' => array(
        'lang_key'     => 'Date_format',
        'explain'      => 'Date_format_explain',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'default'      => 'D M d, Y g:i a',
        'get_func'     => 'mods_settings_get_datefmt',
        'chk_func'     => 'mods_settings_check_datefmt',
    ),
    'user_lang' => array(
        'lang_key'     => 'Board_lang',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'get_mode'     => 'LIST_DROP',
        'default'      => '[board.default_lang]',
        'values'       => 'list_langs',
    ),
    'user_rank' => array(
        'lang_key'     => 'Poster_rank',
        'class'        => 'webdisplay',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_rank_title',
    ),
    'user_rank_title' => array(
        'lang_key'     => 'Poster_rank',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'sql_def'      => '[RANKS].rank_title',
        'dsp_func'     => 'pcp_output_rank_title',
    ),
    'user_sig' => array(
        'lang_key'     => 'Signature',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_sig',
    ),
    'user_style' => array(
        'lang_key'     => 'Board_style',
        'class'        => 'webdisplay',
        'type'         => 'MEDIUMINT',
        'get_mode'     => 'LIST_DROP',
        'default'      => '[board.default_style]',
        'values'       => 'list_styles',
    ),
    'user_summer_time' => array(
        'lang_key'     => 'summer_time',
        'class'        => 'webdisplay',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_yes_no',
    ),
    'user_timezone' => array(
        'lang_key'     => 'Timezone',
        'class'        => 'webdisplay',
        'type'         => 'MEDIUMINT',
        'dsp_func'     => 'pcp_output_timezone',
        'get_mode'     => 'LIST_DROP',
        'default'      => '[board.default_timezone]',
        'values'       => 'list_timezones',
    ),
    'user_website' => array(
        'lang_key'     => 'Website',
        'class'        => 'webdisplay',
        'type'         => 'VARCHAR',
        'dsp_func'     => 'pcp_output_website',
    ),
    'user_retrosig' => array(
        'lang_key'     => 'Retro_sig',
        'explain'      => 'Retro_sig_explain',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'get_mode'     => 'LIST_RADIO',
        'values'       => 'list_yes_no',
        'sql_def'      => '[USERS].user_retrosig',
    ),
    'user_max_inbox_privmsgs' => array(
        'lang_key'     => 'max_inbox_privmsgs',
        'class'        => 'generic',
        'type'         => 'SMALLINT',
        'leg'          => true,
        'txt'          => true,
        'input_id'     => 'max_inbox_privmsgs',
        'default'      => '50',
        'auth'         => ADMIN,
    ),
    'user_max_savebox_privmsgs' => array(
        'lang_key'     => 'max_savebox_privmsgs',
        'class'        => 'generic',
        'type'         => 'SMALLINT',
        'leg'          => true,
        'txt'          => true,
        'input_id'     => 'max_savebox_privmsgs',
        'default'      => '150',
        'auth'         => ADMIN,
    ),
    'user_max_sentbox_privmsgs' => array(
        'lang_key'     => 'max_sentbox_privmsgs',
        'class'        => 'generic',
        'type'         => 'SMALLINT',
        'leg'          => true,
        'txt'          => true,
        'input_id'     => 'max_sentbox_privmsgs',
        'default'      => '50',
        'auth'         => ADMIN,
    ),
    'user_max_userlist' => array(
        'lang_key'     => 'apm_max_userlist',
        'class'        => 'generic',
        'type'         => 'SMALLINT',
        'leg'          => true,
        'txt'          => true,
        'input_id'     => 'max_userlist',
        'default'      => '2',
        'auth'         => ADMIN,
    ),
    'user_apm_save_to_mail' => array(
        'lang_key'     => 'apm_save_to_mail',
        'class'        => 'generic',
        'type'         => 'TINYINT',
        'leg'          => true,
        'txt'          => true,
        'input_id'     => 'apm_save_to_mail',
        'get_mode'     => 'LIST_RADIO',
        'default'      => '1',
        'values'       => 'list_yes_no',
        'auth'         => ADMIN,
    ),

);


?>