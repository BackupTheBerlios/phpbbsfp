<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_profile_control_panel.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME  : lang_profile_control_panel.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
    $lang['Lang_extend_profile_control_panel'] = 'Profile Control Panel language pack';
}

// who's online
$lang['Admin_founder_online_color']         = '%sSite Founder%s';
$lang['User_online_color']                  = '%sUser%s';

// topic or privmsg display
$lang['Add_to_friend_list']                 = 'Add to your friend list';
$lang['Remove_from_friend_list']            = 'Remove from your friend list';
$lang['Add_to_ignore_list']                 = 'Add to your ignore list';
$lang['Remove_from_ignore_list']            = 'Remove from your ignore list';
$lang['Happy_birthday']                     = 'Happy Birthday !';
$lang['Ignore_choosed']                     = 'You have choosen to ignore this user';
$lang['Online']                             = 'Online';
$lang['Offline']                            = 'Offline';
$lang['Hidden']                             = 'Hidden';
$lang['Gender']                             = 'Gender';
$lang['Male']                               = 'Male';
$lang['Female']                             = 'Female';
$lang['No_gender_specify']                  = 'Unknown';
$lang['Age']                                = 'Age';
$lang['Do_not_allow_pm']                    = 'This user doesn\'t accept private messages';

// main entry (profile.php)
$lang['Click_return_profilcp']              = 'Click %sHere%s to return to the profile';

// birthday popup (profile_birthday.php)
$lang['Birthday']                           = 'Birthday';
$lang['birthday_msg']                       = 'Hi %s, <br /><br /><br /> %s is glad to wish you an Happy Birthday !';

// home panel (profilcp_home.php)
$lang['profilcp_index_shortcut']            = 'Home';
$lang['profilcp_index_pagetitle']           = 'Private Profile home';

// home panel : mini buddy list (functions_profile.php)
$lang['Friend_list']                        = 'Friend list';
$lang['Friend_list_of']                     = 'Friend of';
$lang['Ignore_list']                        = 'Ignore list';
$lang['Ignore_list_of']                     = 'Ignored by';
$lang['Nobody']                             = 'Nobody';
$lang['Always_visible']                     = 'Always visible to this user';
$lang['Not_always_visible']                 = 'This user won\'t see you online when you\'ll be in hidden mode';

// home panel : watched topics (functions_profile.php)
$lang['Stop_watching_selected_topics']      = 'Stop watching the selected topics';
$lang['New_subscribed_topic']               = 'Subscribed Topics';
$lang['Submit_period']                      = 'See topics since';

// buddy list (profilcp_buddy.php)
$lang['profilcp_buddy_shortcut']            = 'Buddy list';
$lang['profilcp_buddy_pagetitle']           = 'Buddy list';
$lang['profilcp_buddy_friend_shortcut']     = 'Friend list';
$lang['profilcp_buddy_friend_pagetitle']    = 'Edit your Friend list';
$lang['profilcp_buddy_ignore_shortcut']     = 'Ignore list';
$lang['profilcp_buddy_ignore_pagetitle']    = 'Edit your Ignore list';
$lang['profilcp_buddy_list_shortcut']       = 'All the members';
$lang['profilcp_buddy_list_pagetitle']      = 'Member list';
$lang['Click_return_privmsg']               = 'Click %sHere%s to return to the private message';
$lang['profilcp_buddy_could_not_add_user']  = 'The user you selected does not exist.';
$lang['profilcp_buddy_could_not_anon_user'] = 'You cannot make Anonymous a buddy member.';
$lang['profilcp_buddy_add_yourself']        = 'You can\'t add yourself to your buddy lists';
$lang['profilcp_buddy_already']             = 'User is already in the buddy list';
$lang['profilcp_buddy_ignore']              = 'Addition impossible : this user ignores you';
$lang['profilcp_buddy_you_admin']           = 'Being an admin or moderator, you can\'t ignore people';
$lang['profilcp_buddy_admin']               = 'You can\'t ignore admins or moderators';
$lang['User_fields']                        = 'User fields list';
$lang['Friend']                             = 'Friend';
$lang['Comp_LE']                            = 'is less than';
$lang['Comp_EQ']                            = 'is equal to';
$lang['Comp_NE']                            = 'is different from';
$lang['Comp_GE']                            = 'is greater than';
$lang['Comp_IN']                            = 'includes';
$lang['Comp_NI']                            = 'doesn\'t include';
$lang['Sort_none']                          = 'Unsorted';
$lang['date_entry']                         = 'YYYYMMDD';

// update profile (profilcp_profil.php)
$lang['profilcp_profil_shortcut']           = 'Profile';
$lang['profilcp_profil_pagetitle']          = 'Profile Edition';
$lang['profilcp_prefer_shortcut']           = 'Your profile';
$lang['profilcp_prefer_pagetitle']          = 'Your profile preferences';
$lang['profilcp_signature_shortcut']        = 'Signature';
$lang['profilcp_signature_pagetitle']       = 'Signature';
$lang['profilcp_avatar_shortcut']           = 'Avatar';
$lang['profilcp_avatar_pagetitle']          = 'Avatar';

// update profile : preferences - functions (mod_profile_control_panel.php)
$lang['Other']                              = 'Other...';
$lang['Friend_only']                        = 'Only friends';

// update profile : public informations : web info (mod_profile_control_public_web.php)
$lang['profilcp_profil_base_shortcut']      = 'Public information';
$lang['Web_info']                           = 'Web informations';

// update profile : public informations : real info (mod_profile_control_public_real.php)
$lang['Real_info']                          = 'Personal informations';
$lang['Realname']                           = 'Real name';
$lang['Date_error']                         = 'day %d, month %d, year %d is not a valid date';

// update profile : public informations : messengers info (mod_profile_control_public_messengers.php)
$lang['Messangers']                         = 'Messengers';

// update profile : public informations : contact info (mod_profile_control_public_contact.php)
$lang['Home_phone']                         = 'Home phone';
$lang['Home_fax']                           = 'Home fax';
$lang['Work_phone']                         = 'Work phone';
$lang['Work_fax']                           = 'Work fax';
$lang['Cellular']                           = 'Cellular';
$lang['Pager']                              = 'Pager';

// update profile : preferences - preferences panel ("Your profile")
$lang['Profile_control_panel']              = 'Profile Settings';

// update profile : preferences - i18n panel (mod_profile_control_panel_international.php)
$lang['Profile_control_panel_i18n']         = 'Internationalization';
$lang['summer_time']                        = 'Are you in a daylight saving zone ?';

// update profile : preferences - notification panel (mod_profile_control_panel_notification.php)
$lang['Profile_control_panel_notification'] = 'Notification';

// update profile : preferences - posting panel (mod_profile_control_panel_posting.php)
$lang['Profile_control_panel_posting']      = 'Posting';

// update profile : preferences - privacy panel (mod_profile_control_panel_privacy.php)
$lang['Profile_control_panel_privacy']      = 'Privacy';
$lang['View_user']                          = 'Show me online';
$lang['Public_view_pm']                     = 'Accept private message';
$lang['Public_view_website']                = 'Display my web informations';
$lang['Public_view_messengers']             = 'Display my messengers references';
$lang['Public_view_real_info']              = 'Display my personnal informations';

// update profile : preferences - reading panel (mod_profile_control_panel_reading.php)
$lang['Profile_control_panel_reading']      = 'Reading';
$lang['Public_view_avatar']                 = 'Display avatars';
$lang['Public_view_sig']                    = 'Display signatures';
$lang['Public_view_img']                    = 'Display images';

// update profile : preferences - profile preferences
$lang['profile_prefer']                     = 'Profile options';

// update profile : preferences - system panel (mod_profile_control_panel_system.php)
$lang['Profile_control_panel_system']       = 'System';
$lang['summer_time_set']                    = 'Is it summer time ? (add +1 hour to local time)';
$lang['Forum_rules']                        = 'Enable Forum Rules? 1 = On ; 0 = Off';

// update profile : preferences - admin part (mod_profile_control_panel_admin.php)
$lang['profilcp_admin_shortcut']            = 'Administration';
$lang['User_deleted']                       = 'User was successfully deleted.';
$lang['User_special']                       = 'Special admin-only fields';
$lang['User_special_explain']               = 'These fields are not able to be modified by the users.  Here you can set their status and other options that are not given to users.';
$lang['User_status']                        = 'User is active';
$lang['User_allow_email']                   = 'Can send emails';
$lang['User_allow_pm']                      = 'Can send Private Messages';
$lang['User_allow_website']                 = 'Can show his web info';
$lang['User_allow_messanger']               = 'Can share his messengers adresses';
$lang['User_allow_real']                    = 'Can show his personal informations';
$lang['User_allowavatar']                   = 'Can display avatar';
$lang['User_allow_sig']                     = 'Can display his signature';
$lang['Rank_title']                         = 'Rank Title';
$lang['User_delete']                        = 'Delete this user';
$lang['User_delete_explain']                = 'Click here to delete this user; this cannot be undone.';
$lang['No_assigned_rank']                   = 'No special rank assigned';
$lang['User_self_delete']                   = 'You can delete your account being a board administrator';

// update profile : signature (profilcp_profile_signature.php)
$lang['profilcp_sig_preview']               = 'Signature preview';

// display profile (profilcp_public.php)
$lang['profilcp_public_shortcut']           = 'Public';
$lang['profilcp_public_pagetitle']          = 'Public display';
$lang['profilcp_public_base_shortcut']      = 'Base info';
$lang['profilcp_public_base_pagetitle']     = 'Profile base informations';
$lang['profilcp_public_groups_shortcut']    = 'Groups';
$lang['profilcp_public_groups_pagetitle']   = 'Groups this user belong to';

// update profile : preferences - home panel (mod_profile_control_panel_home.php)
$lang['Profile_control_panel_home']         = 'Profile Home panel';
$lang['Profile_control_panel_home_buddy']   = 'Buddy lists';
$lang['Buddy_friend_display']               = 'Display my friend list on home panel';
$lang['Buddy_ignore_display']               = 'Display my ignore list on home panel';
$lang['Buddy_friend_of_display']            = 'Display "Friend of" list on home panel';
$lang['Buddy_ignored_by_display']           = 'Display "Ignored by" list on home panel';

$lang['Profile_control_panel_home_privmsg'] = 'Private messages';
$lang['Privmsgs_per_page']                  = 'Number of private messages displayed per page on home panel';

$lang['Profile_control_panel_home_wtopics'] = 'Watched topics';
$lang['Watched_topics_per_page']            = 'Number of topics watched displayed per page on home panel';

// display profile : base info (profilcp_public_base.php)
$lang['Unavailable']                        = 'Unavailable';
$lang['Last_visit']                         = 'Last visit time';
$lang['User_posts']                         = 'User posts';
$lang['User_post_stats']                    = '%s posts, %.2f%% of total, %.2f posts per day';
$lang['Most_active_topic']                  = 'Most active topic';
$lang['Most_active_topic_stat']             = '%s posts, %.2f%% of the topic, %.2f%% of the forum';
$lang['Most_active_forum']                  = 'Most active forum';
$lang['Most_active_forum_stat']             = '%s posts, %.2f%% of the forum, %.2f%% of total';

// register (profilcp_register.php)
$lang['profilcp_register_shortcut']         = 'Registering';
$lang['profilcp_register_pagetitle']        = 'Registration informations';
$lang['profilcp_email_title']               = 'Email address';
$lang['profilcp_email_confirm']             = 'Confirm your Email address';
$lang['anti_robotic']                       = 'Control picture';
$lang['anti_robotic_explain']               = 'This control is designed to prevent the flooding by the register robots';
$lang['profilcp_password_explain']          = 'You must confirm your current password if you wish to change it';
$lang['Agree_rules']                        = 'By checking this box, you declare having taken knowledge of the terms, and agree with them';
$lang['profilcp_username_missing']          = 'Username missing';
$lang['profilcp_email_not_matching']        = 'Emails doesn\'t match';
$lang['Robot_flood_control']                = 'The control image doesn\'t match what you\'ve typed in';
$lang['Disagree_rules']                     = 'You have declared you disagree with the rules used on this board, so you won\'t be able to register.';

?>