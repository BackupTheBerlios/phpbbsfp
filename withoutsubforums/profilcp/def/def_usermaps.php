<?php
//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- mod : add-on country flags for pcp ------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: def_usermaps.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : def_usermaps.php
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
// $user_maps array
//
//      key = name of the map,
//          order   : order,
//          split   : split the display in a new column
//          custom  : use a dedicated program or a standard program : 0: dedicated, 1: standard input, 2: standard output
//          title   : title or set of fields
//          fields  : set of fields
//
//          title and fields :
//              field_name  : Field name
//              lang_key    : Legend of the field
//              explain     : Explanation of the field
//              image       : Image
//              title       : Image title
//              class       : Class
//              type        : Type
//              link        : Link
//              dsp_func    : Display function
//              leg         : Display the legend
//              txt         : Display the text value
//              img         : Display the image value
//              crlf        : Text to next line
//              lnk         : Use the link
//              style       : Span display
//              input_id    : Config name field
//              user_only   : Not a config value
//              system      : System field
//              get_mode    : Get mode
//              get_func    : Get function
//              chk_func    : Check function
//              default     : Default value
//              values      : Values list
//              auth        : Auth level
//              ind         : Option address
//              dft         : Checked by default
//              rqd         : Force the selection
//              hidden      : Add the field as hidden
//              sql_def     : SQL definition
//
//--------------------------------------------------------------------------------------------------
$user_maps = array(

    'PCP' => array(
        'title'     => 'Profile',
    ),

    'PCP.viewprofile' => array(
        'title'     => 'profilcp_public_shortcut',
    ),

    'PCP.viewprofile.base' => array(
        'custom'    => 2,
        'title'     => 'profilcp_public_base_shortcut',
        'fields'    => array(
        ),
    ),

    'PCP.viewprofile.base.avatar' => array(
        'split'     => true,
        'title'     => 'Avatar',
        'fields'    => array(
            'user_avatar' => array(
                'img'          => true,
            ),
            'user_rank_title' => array(
                'txt'          => true,
                'img'          => true,
                'crlf'         => true,
                'style'        => '<span class="gensmall">%s</span>',
            ),
        ),
    ),

    'PCP.viewprofile.base.messangers' => array(
        'order'     => 10,
        'title' => array(
            '[leg]contact' => array(
                'lang_key'     => 'Contact',
                'leg'          => true,
                'style'        => '%s&nbsp;',
            ),
            'username' => array(
                'txt'          => true,
                'style'        => '%s&nbsp;',
            ),
            'user_online' => array(
                'img'          => true,
            ),
        ),
        'fields'    => array(
            '[lf]0' => array(
            ),
            'user_my_friend' => array(
                'leg'          => true,
                'img'          => true,
            ),
            'user_my_ignore' => array(
                'leg'          => true,
                'img'          => true,
            ),
            'user_email' => array(
                'leg'          => true,
                'img'          => true,
            ),
            'user_pm' => array(
                'leg'          => true,
                'img'          => true,
            ),
            '[lf]1' => array(
            ),
            'user_icq' => array(
                'leg'          => true,
                'img'          => true,
                'style'        => '<table cellpadding="0" cellspacing="0" border="0" width="60"><tr><td valign="middle" nowrap="nowrap"><span class="gensmall">%s</span></td></tr></table>',
            ),
            'user_aim' => array(
                'leg'          => true,
                'img'          => true,
            ),
            'user_yim' => array(
                'leg'          => true,
                'img'          => true,
            ),
            'user_msnm' => array(
                'leg'          => true,
                'img'          => true,
            ),
            '[lf]2' => array(
            ),
        ),
    ),

    'PCP.viewprofile.base.international' => array(
        'order'     => 20,
        'title'     => 'Profile_control_panel_i18n',
        'fields'    => array(
            'user_timezone' => array(
                'img'          => true,
            ),
            'user_lang' => array(
                'txt'          => true,
                'style'        => '<b>%s</b>',
            ),
        ),
    ),

    'PCP.viewprofile.base.groups' => array(
        'order'     => 30,
        'title'     => 'Usergroups',
        'fields'    => array(
            'user_groups' => array(
                'txt'          => true,
            ),
        ),
    ),

    'PCP.viewprofile.base.webinfo' => array(
        'order'     => 40,
        'split'     => true,
        'title' => array(
            'username' => array(
                'txt'          => true,
                'style'        => 'About_user',
            ),
        ),
        'fields'    => array(
            '[lf]0' => array(
            ),
            'user_regdate' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_lastvisit' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            '[lf]1' => array(
            ),
            'user_posts_stat' => array(
                'leg'          => true,
                'txt'          => true,
                'crlf'         => true,
                'style'        => '<span class="genmed">%s</span>',
            ),
            'user_topics_stat' => array(
                'leg'          => true,
                'txt'          => true,
                'img'          => true,
                'crlf'         => true,
            ),
            'user_forums_stat' => array(
                'leg'          => true,
                'txt'          => true,
                'img'          => true,
                'crlf'         => true,
            ),
            '[lf]2' => array(
            ),
            'user_website' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            '[lf]3' => array(
            ),
        ),
    ),

    'PCP.viewprofile.base.real' => array(
        'order'     => 50,
        'title'     => 'Real_info',
        'fields'    => array(
            '[lf]0' => array(
            ),
            'user_realname' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_gender' => array(
                'leg'          => true,
                'txt'          => true,
                'img'          => true,
            ),
            'user_birthday' => array(
                'leg'          => true,
                'txt'          => true,
                'img'          => true,
            ),
            '[lf]1' => array(
            ),
            'user_from' => array(
                'leg'          => true,
                'txt'          => true,
            ),

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
            'user_flag' => array(
                'dsp_func'     => 'pcp_output_flag',
                'leg'          => true,
                'lang_key'     => 'Country_Flag',
                'img'          => true,
            ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

            'user_occ' => array(
                'leg'          => true,
                'txt'          => true,
            ),

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
            'user_holidays' => array(
                'leg'          => true,
                'txt'          => true,
            ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

            'user_interests' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            '[lf]2' => array(
            ),
            'user_home_phone' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_home_fax' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_work_phone' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_work_fax' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_cellular' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            'user_pager' => array(
                'leg'          => true,
                'txt'          => true,
            ),
            '[lf]3' => array(
            ),
        ),
    ),

    'PCP.viewprofile.base.signature' => array(
        'order'     => 60,
        'title'     => 'Signature',
        'fields'    => array(
            'user_sig' => array(
                'txt'          => true,
                'style'        => '<div align="left" class="postbody">%s</span>',
            ),
        ),
    ),

    'PCP.home' => array(
        'order'     => 10,
        'title'     => 'profilcp_index_shortcut',
    ),

    'PCP.register' => array(
        'order'     => 20,
        'title'     => 'profilcp_register_shortcut',
    ),

    'PCP.profil' => array(
        'order'     => 30,
        'title'     => 'profilcp_profil_shortcut',
    ),

    'PCP.profil.profile_prefer' => array(
        'custom'    => 1,
        'title'     => 'profilcp_prefer_shortcut',
    ),

    'PCP.profil.profile_prefer.base' => array(
        'title'     => 'profilcp_profil_base_shortcut',
    ),

    'PCP.profil.profile_prefer.base.Real_info' => array(
        'title'     => 'Real_info',
        'fields'    => array(
            'user_realname' => array(
                'input_id'     => 'realname',
                'user_only'    => true,
            ),
            'user_gender' => array(
                'input_id'     => 'gender',
                'user_only'    => true,
            ),
            'user_birthday' => array(
                'input_id'     => 'birthday',
                'user_only'    => true,
            ),
            'user_from' => array(
                'input_id'     => 'location',
                'user_only'    => true,
            ),

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
            'user_flag' => array(
                'input_id'     => 'flag',
                'user_only'    => true,
                'lang_key'     => 'Country_Flag',
                'type'         => 'FLAG',
                'default'      => 'blank.gif',
                'get_func'     => 'mods_settings_get_flags',
                'chk_func'     => 'mods_settings_check_flags',
            ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

            'user_occ' => array(
                'input_id'     => 'occupation',
                'user_only'    => true,
            ),

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
            'user_holidays' => array(
                'input_id'     => 'holidays',
                'user_only'    => true,
            ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

            'user_interests' => array(
                'input_id'     => 'interests',
                'user_only'    => true,
            ),
        ),
    ),

    'PCP.profil.profile_prefer.base.Contact' => array(
        'order'     => 10,
        'title'     => 'Contact',
        'fields'    => array(
            'user_home_phone' => array(
                'input_id'     => 'home_phone',
                'user_only'    => true,
            ),
            'user_home_fax' => array(
                'input_id'     => 'home_fax',
                'user_only'    => true,
            ),
            'user_work_phone' => array(
                'input_id'     => 'work_phone',
                'user_only'    => true,
            ),
            'user_work_fax' => array(
                'input_id'     => 'work_fax',
                'user_only'    => true,
            ),
            'user_cellular' => array(
                'input_id'     => 'cellular',
                'user_only'    => true,
            ),
            'user_pager' => array(
                'input_id'     => 'pager',
                'user_only'    => true,
            ),
        ),
    ),

    'PCP.profil.profile_prefer.base.Messangers' => array(
        'order'     => 20,
        'title'     => 'Messangers',
        'fields'    => array(
            'user_icq' => array(
                'input_id'     => 'ICQ',
                'user_only'    => true,
            ),
            'user_aim' => array(
                'input_id'     => 'AIM',
                'user_only'    => true,
            ),
            'user_yim' => array(
                'input_id'     => 'YIM',
                'user_only'    => true,
            ),
            'user_msnm' => array(
                'input_id'     => 'MSNM',
                'user_only'    => true,
            ),
        ),
    ),

    'PCP.profil.profile_prefer.base.Web_info' => array(
        'order'     => 30,
        'title'     => 'Web_info',
        'fields'    => array(
            'user_website' => array(
                'input_id'     => 'website',
                'user_only'    => true,
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP' => array(
        'order'     => 10,
        'title'     => 'Profile_control_panel',
    ),

    'PCP.profil.profile_prefer.PCP.i18n' => array(
        'title'     => 'Profile_control_panel_i18n',
        'fields'    => array(
            'user_lang' => array(
                'input_id'     => 'default_lang',
            ),
            'user_style' => array(
                'input_id'     => 'default_style',
            ),
            'user_timezone' => array(
                'input_id'     => 'board_timezone',
            ),
            'user_summer_time' => array(
                'input_id'     => 'summer_time_user',
            ),
            'user_dateformat' => array(
                'input_id'     => 'default_dateformat',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP.privacy' => array(
        'order'     => 10,
        'title'     => 'Profile_control_panel_privacy',
        'fields'    => array(
            'user_allow_viewonline' => array(
                'input_id'     => 'allow_viewonline',
            ),
            'user_viewemail' => array(
                'input_id'     => 'allow_viewemail',
            ),
            'user_viewpm' => array(
                'input_id'     => 'allow_viewpm',
            ),
            'user_viewwebsite' => array(
                'input_id'     => 'allow_viewwebsite',
            ),
            'user_viewmessanger' => array(
                'input_id'     => 'allow_viewmessengers',
            ),
            'user_viewreal' => array(
                'input_id'     => 'allow_viewreal',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP.notification' => array(
        'order'     => 20,
        'title'     => 'Profile_control_panel_notification',
        'fields'    => array(
            'user_notify' => array(
                'input_id'     => 'topic_notify',
            ),
            'user_notify_pm' => array(
                'input_id'     => 'pm_notify',
            ),
            'user_popup_pm' => array(
                'input_id'     => 'pm_popup',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP.posting' => array(
        'order'     => 30,
        'title'     => 'Profile_control_panel_posting',
        'fields'    => array(
            'user_attachsig' => array(
                'input_id'     => 'post_sig',
            ),
            'user_retrosig' => array(
                'input_id'     => 'post_retrosig',
            ),
            'user_allowbbcode' => array(
                'input_id'     => 'post_bbcode',
            ),
            'user_allowhtml' => array(
                'input_id'     => 'post_html',
            ),
            'user_allowsmile' => array(
                'input_id'     => 'post_smilies',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP.reading' => array(
        'order'     => 40,
        'title'     => 'Profile_control_panel_reading',
        'fields'    => array(
            'user_viewavatar' => array(
                'input_id'     => 'read_viewavatar',
            ),
            'user_viewsig' => array(
                'input_id'     => 'read_viewsig',
            ),
            'user_viewimg' => array(
                'input_id'     => 'read_viewimg',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.PCP.system' => array(
        'order'     => 50,
        'title'     => 'Profile_control_panel_system',
        'fields'    => array(
            'summer_time' => array(
                'lang_key'     => 'summer_time_set',
                'type'         => 'TINYINT',
                'input_id'     => 'summer_time',
                'get_mode'     => 'LIST_RADIO',
                'default'      => 'Yes',
                'values'       => 'list_yes_no',
            ),
            'robotic_register' => array(
                'lang_key'     => 'anti_robotic',
                'explain'      => 'anti_robotic_explain',
                'type'         => 'TINYINT',
                'input_id'     => 'robotic_register',
                'get_mode'     => 'LIST_RADIO',
                'default'      => 'Yes',
                'values'       => 'list_yes_no',
            ),
            'forum_rules' => array(
                'lang_key'     => 'Forum_rules',
                'type'         => 'INT',
                'input_id'     => 'forum_rules',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.admin_main' => array(
        'order'     => 20,
        'title'     => 'profilcp_admin_shortcut',
    ),

    'PCP.profil.profile_prefer.admin_main.admin_sub' => array(
        'order'     => 20,
        'title'     => 'profilcp_admin_shortcut',
        'fields'    => array(
            'user_active' => array(
                'input_id'     => 'user_active',
                'user_only'    => true,
            ),
            'user_allow_email' => array(
                'input_id'     => 'user_allow_email',
                'user_only'    => true,
            ),
            'user_allow_pm' => array(
                'input_id'     => 'user_allow_pm',
                'user_only'    => true,
            ),
            'user_allow_website' => array(
                'input_id'     => 'user_allow_website',
                'user_only'    => true,
            ),
            'user_allow_messanger' => array(
                'input_id'     => 'user_allow_messanger',
                'user_only'    => true,
            ),
            'user_allow_real' => array(
                'input_id'     => 'user_allow_real',
                'user_only'    => true,
            ),
            'user_allowavatar' => array(
                'input_id'     => 'user_allowavatar',
                'user_only'    => true,
            ),
            'user_allow_sig' => array(
                'input_id'     => 'user_allow_sig',
                'user_only'    => true,
            ),
            'user_rank' => array(
                'input_id'     => 'user_rank_title',
                'user_only'    => true,
                'get_mode'     => 'LIST_DROP',
                'values'       => 'list_special_ranks',
                'auth'         => ADMIN,
            ),
            'delete_user' => array(
                'lang_key'     => 'User_delete',
                'explain'      => 'User_delete_explain',
                'input_id'     => 'delete_user',
                'user_only'    => true,
                'system'       => true,
                'get_func'     => 'mods_settings_get_delete_user',
                'chk_func'     => 'mods_settings_check_delete_user',
                'auth'         => BOARD_ADMIN,
            ),
        ),
    ),

    'PCP.profil.Preferences' => array(
        'order'     => 10,
        'custom'    => 1,
        'title'     => 'Preferences',
    ),

    'PCP.profil.Preferences.home' => array(
        'title'     => 'Profile_control_panel_home',
    ),

    'PCP.profil.Preferences.home.buddy' => array(
        'title'     => 'Profile_control_panel_home_buddy',
        'fields'    => array(
            'user_buddy_friend_display' => array(
                'input_id'     => 'buddy_friend_display',
            ),
            'user_buddy_ignore_display' => array(
                'input_id'     => 'buddy_ignore_display',
            ),
            'user_buddy_friend_of_display' => array(
                'input_id'     => 'buddy_friend_of_display',
            ),
            'user_buddy_ignored_by_display' => array(
                'input_id'     => 'buddy_ignored_by_display',
            ),
        ),
    ),

    'PCP.profil.Preferences.home.privmsgs' => array(
        'order'     => 10,
        'title'     => 'Profile_control_panel_home_privmsg',
        'fields'    => array(
            'user_privmsgs_per_page' => array(
                'input_id'     => 'privmsgs_per_page',
            ),
        ),
    ),

    'PCP.profil.Preferences.home.watched_topics' => array(
        'order'     => 20,
        'title'     => 'Profile_control_panel_home_wtopics',
        'fields'    => array(
            'user_watched_topics_per_page' => array(
                'input_id'     => 'topics_watched_per_page',
            ),
        ),
    ),

    'PCP.profil.avatar' => array(
        'order'     => 20,
        'title'     => 'profilcp_avatar_shortcut',
    ),

    'PCP.profil.signature' => array(
        'order'     => 30,
        'title'     => 'profilcp_signature_shortcut',
    ),

    'PCP.buddy' => array(
        'order'     => 40,
        'title'     => 'profilcp_buddy_shortcut',
        'fields'    => array(
            'user_my_friend' => array(
                'dsp_func'     => 'pcp_output_my_friend_buddy',
                'ind'          => '27',
                'img'          => true,
                'dft'          => true,
            ),
            'user_my_visible' => array(
                'hidden'       => true,
            ),
            'user_my_ignore' => array(
                'hidden'       => true,
            ),
            'user_friend' => array(
                'hidden'       => true,
            ),
            'user_visible' => array(
                'hidden'       => true,
            ),
            'user_ignore' => array(
                'hidden'       => true,
            ),
            'user_online' => array(
                'img'          => true,
                'ind'          => '1',
                'dft'          => true,
            ),
            'username' => array(
                'dsp_func'     => 'pcp_output_username_linked',
                'ind'          => '2',
                'rqd'          => true,
            ),
            'user_pm' => array(
                'img'          => true,
                'ind'          => '3',
            ),
            'user_email' => array(
                'img'          => true,
                'ind'          => '4',
            ),
            'user_icq' => array(
                'img'          => true,
                'style'        => '<table cellpadding="0" cellspacing="0" border="0" width="60"><tr><td valign="middle" nowrap="nowrap"><span class="gensmall">%s</span></td></tr></table>',
                'ind'          => '5',
            ),
            'user_aim' => array(
                'img'          => true,
                'ind'          => '6',
            ),
            'user_yim' => array(
                'img'          => true,
                'ind'          => '7',
            ),
            'user_msnm' => array(
                'img'          => true,
                'ind'          => '8',
            ),
            'user_regdate' => array(
                'ind'          => '9',
                'dft'          => true,
            ),
            'user_lastvisit' => array(
                'ind'          => '10',
                'dft'          => true,
            ),
            'user_posts' => array(
                'ind'          => '11',
                'dft'          => true,
            ),
            'user_rank_title' => array(
                'txt'          => true,
                'img'          => true,
                'crlf'         => true,
                'ind'          => '27',
                'dft'          => true,
            ),
            'user_timezone' => array(
                'ind'          => '12',
            ),
            'user_lang' => array(
                'ind'          => '13',
            ),
            'user_website' => array(
                'img'          => true,
                'ind'          => '14',
            ),
            'user_realname' => array(
                'ind'          => '15',
            ),
            'user_gender' => array(
                'ind'          => '16',
                'img'          => true,
            ),
            'user_birthday' => array(
                'txt'          => true,
                'img'          => true,
                'ind'          => '17',
            ),
            'user_from' => array(
                'ind'          => '18',
            ),
//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
            'user_flag' => array(
                'dsp_func'     => 'pcp_output_flag',
                'lang_key'     => 'Country_Flag',
                'img'          => true,
                'ind'          => '30',
                'dft'          => true,
            ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

            'user_occ' => array(
                'ind'          => '19',
            ),

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
            'user_holidays' => array(
                'ind'          => '29',
            ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

            'user_interests' => array(
                'ind'          => '20',
            ),
            'user_home_phone' => array(
                'ind'          => '21',
            ),
            'user_home_fax' => array(
                'ind'          => '22',
            ),
            'user_work_phone' => array(
                'ind'          => '23',
            ),
            'user_work_fax' => array(
                'ind'          => '24',
            ),
            'user_cellular' => array(
                'ind'          => '25',
            ),
            'user_pager' => array(
                'ind'          => '26',
            ),

        ),
    ),

    'PCP.privmsg' => array(
        'order'     => 50,
        'title'     => 'Private_Messaging',
    ),

    'PHPBB' => array(
        'order'     => 10,
        'title'     => 'phpBB',
    ),

    'PHPBB.privmsgs' => array(
        'title'     => '',
    ),

    'PHPBB.privmsgs.buttons' => array(
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_birthday' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_my_friend' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_my_ignore' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_pm' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_email' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_website' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_aim' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_yim' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_msnm' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_icq' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s</span></td>',
            ),
        ),
    ),

    'PHPBB.privmsgs.buttons.ignore' => array(
        'title'     => '',
        'fields'    => array(
            'user_my_ignore' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
        ),
    ),

    'PHPBB.privmsgs.left' => array(
        'order'     => 10,
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'txt'          => true,
                'style'        => '<span class="name">%s</span>',
            ),
            'user_online' => array(
                'img'          => true,
            ),
        ),
    ),

    'PHPBB.privmsgs.left.ignore' => array(
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'txt'          => true,
                'style'        => '<span class="name">%s</span>',
            ),
        ),
    ),

    'PHPBB.viewtopic' => array(
        'order'     => 10,
        'title'     => '',
    ),

    'PHPBB.viewtopic.buttons' => array(
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_birthday' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_my_friend' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_my_ignore' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_pm' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_email' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_website' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_aim' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_yim' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_msnm' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
            'user_icq' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s</span></td>',
            ),
        ),
    ),

    'PHPBB.viewtopic.buttons.ignore' => array(
        'title'     => '',
        'fields'    => array(
            'user_my_ignore' => array(
                'img'          => true,
                'style'        => '<td valign="absbottom" nowrap="nowrap"><span class="gensmall">%s&nbsp;</span></td>',
            ),
        ),
    ),

    'PHPBB.viewtopic.left' => array(
        'order'     => 10,
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'txt'          => true,
                'style'        => '<span class="name">%s</span>',
            ),
            'user_online' => array(
                'img'          => true,
            ),
            'user_rank_title' => array(
                'txt'          => true,
                'img'          => true,
                'crlf'         => true,
                'style'        => '<div class="gensmall">%s</div>',
            ),
            'user_avatar' => array(
                'img'          => true,
                'style'        => '<div align="center">%s</div><br />',
            ),
            'user_regdate' => array(
                'leg'          => true,
                'txt'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),
            'user_posts' => array(
                'leg'          => true,
                'txt'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),
            'user_from' => array(
                'leg'          => true,
                'txt'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),

//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- add
            'user_flag' => array(
                'dsp_func'     => 'pcp_output_flag',
                'leg'          => true,
                'lang_key'     => 'Country_Flag',
                'img'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),
//-- fin mod : add-on country flags for pcp --------------------------------------------------------

            'user_age' => array(
                'leg'          => true,
                'txt'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),
            'user_gender' => array(
                'leg'          => true,
                'txt'          => true,
                'img'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
            'user_holidays' => array(
                'leg'          => true,
                'txt'          => true,
                'style'        => '<div align="left" class="gensmall">%s</div>',
            ),
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------
        ),
    ),

    'PHPBB.viewtopic.left.ignore' => array(
        'title'     => '',
        'fields'    => array(
            'username' => array(
                'txt'          => true,
                'style'        => '<span class="name">%s</span>',
            ),
        ),
    ),

    'PCP.profil.profile_prefer.admin_main.privmsga_settings' => array(
        'order'     => 10,
        'title'     => 'privmsga_settings',
        'fields'    => array(
            'user_max_inbox_privmsgs' => array(
                'type'         => 'VARCHAR',
                'get_mode'     => 'SMALLINT',
            ),
            'user_max_sentbox_privmsgs' => array(
                'type'         => 'VARCHAR',
                'get_mode'     => 'SMALLINT',
            ),
            'user_max_savebox_privmsgs' => array(
                'type'         => 'VARCHAR',
                'get_mode'     => 'SMALLINT',
            ),
            'user_max_userlist' => array(
                'type'         => 'VARCHAR',
                'get_mode'     => 'SMALLINT',
            ),
            'user_apm_save_to_mail' => array(
                'type'         => 'VARCHAR',
            ),
        ),
    ),

);

?>