<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_admin.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_admin.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/* CONTRIBUTORS
    2002-12-15  Philip M. White (pwhite@mailhaven.com)
        Fixed many minor grammatical mistakes
*/

//
// Format is same as lang_main
//

//
// Modules, this replaces the keys used
// in the modules[][] arrays in each module file
//
$lang['DB'] = 'Database';
$lang['General'] = 'General Admin';
$lang['Users'] = 'User Admin';
$lang['Groups'] = 'Group Admin';
$lang['Forums'] = 'Forum Admin';
$lang['Styles'] = 'Styles Admin';

$lang['Configuration'] = 'Configuration';
$lang['Permissions'] = 'Permissions';
$lang['Manage'] = 'Management';
$lang['Disallow'] = 'Disallow names';
$lang['Prune'] = 'Prune Forums';
$lang['Rules'] = 'Rules';
$lang['Mass_Email'] = 'Mass Email';
$lang['Ranks'] = 'Ranks';
$lang['Smilies'] = 'Smilies';
$lang['Ban_Management'] = 'Ban Control';
$lang['Word_Censor'] = 'Word Censors';
$lang['Export'] = 'Export';
$lang['Create_new'] = 'Create';
$lang['Add_new'] = 'Add';
$lang['DB_backup'] = 'Backup Database';
$lang['DB_restore'] = 'Restore Database';
$lang['DB_clean'] = 'Clean Database';
$lang['DB_optimize'] = 'Optimize Database';


//
// Index
//
$lang['Admin'] = 'Administration';
$lang['Not_admin'] = 'You are not authorised to administer this site';
$lang['Welcome_phpBB'] = 'Welcome to Minerva';
$lang['Admin_intro'] = 'Thank you for choosing Minerva as your portal solution. This screen will give you a quick overview of all the various statistics of your site. You can get back to this page by clicking on the <u>Admin Index</u> link in the left pane. To return to the index of your site, click the Minerva logo also in the left pane. The other links on the left hand side of this screen will allow you to control every aspect of your website. Each screen will have instructions on how to use the tools.';
$lang['Main_index'] = 'Forum Index';
$lang['Forum_stats'] = 'Forum Statistics';
$lang['Admin_Index'] = 'Admin Index';
$lang['Preview_forum'] = 'Preview Forum';

$lang['Click_return_admin_index'] = 'Click %sHere%s to return to the Admin Index';

$lang['Statistic'] = 'Statistic';
$lang['Value'] = 'Value';
$lang['Number_posts'] = 'Number of posts';
$lang['Posts_per_day'] = 'Posts per day';
$lang['Number_topics'] = 'Number of topics';
$lang['Topics_per_day'] = 'Topics per day';
$lang['Number_users'] = 'Number of users';
$lang['Users_per_day'] = 'Users per day';
$lang['Board_started'] = 'Board started';
$lang['Avatar_dir_size'] = 'Avatar directory size';
$lang['Database_size'] = 'Database size';
$lang['Gzip_compression'] ='Gzip compression';
$lang['Not_available'] = 'Not available';

$lang['ON'] = 'ON'; // This is for GZip compression
$lang['OFF'] = 'OFF';


//
// DB Utils
//
$lang['Database_Utilities'] = 'Database Utilities';

$lang['Restore'] = 'Restore';
$lang['Backup'] = 'Backup';
$lang['Restore_explain'] = 'This will perform a full restore of all Minerva tables from a saved file. If your server supports it, you may upload a gzip-compressed text file and it will automatically be decompressed. <b>WARNING</b>: This will overwrite any existing data. The restore may take a long time to process, so please do not move from this page until it is complete.';
$lang['Backup_explain'] = 'Here you can back up all your Minerva-related data. If you have any additional custom tables in the same database with Minerva that you would like to back up as well, please enter their names, separated by commas, in the Additional Tables textbox below. If your server supports it you may also gzip-compress the file to reduce its size before download.';

// Start Optimize Database

$lang['Optimize'] = 'Optimize';
$lang['Optimize_explain'] = 'Here it\'s possible to optimize the contained data in the tables of the database. You can eliminate in this way the parts of the data that contain some empty spaces.<br />This operation must regularly be performs so that your database to make reliable and it has to maintain a speed of correct execution.';
$lang['Optimize_DB'] = 'Optimize Database';
$lang['Optimize_Enable_cron'] = "Enable Cron";
$lang['Optimize_Cron_every'] = "Cron Every";
$lang['Optimize_month'] = "Month";
$lang['Optimize_2weeks'] = "2 weeks";
$lang['Optimize_week'] = "Week";
$lang['Optimize_3days'] = "3 days";
$lang['Optimize_day'] = "Day";
$lang['Optimize_6hours'] = "6 hours";
$lang['Optimize_hour'] = "Hour";
$lang['Optimize_30minutes'] = "30 minutes";
$lang['Optimize_20seconds'] = "20 seconds (only for test)";
$lang['Optimize_Current_time'] = "Current Time";
$lang['Optimize_Next_cron_action'] = "Next Cron Action";
$lang['Optimize_Performed_Cron'] = "Performed Cron";
$lang['Optimize_Show_not_optimized'] = 'Show only tables not optimized';
$lang['Optimize_Show_begin_for'] = 'Show only tables that begin for';
$lang['Optimize_Configure'] = 'Configure';
$lang['Optimize_Table'] = 'Table';
$lang['Optimize_Record'] = 'Record';
$lang['Optimize_Type'] = 'Type';
$lang['Optimize_Size'] = 'Size';
$lang['Optimize_Status'] = 'Status';
$lang['Optimize_CheckAll'] = 'Check All';
$lang['Optimize_UncheckAll'] = 'Uncheck All';
$lang['Optimize_InvertChecked'] = 'Invert Checked';
$lang['Optimize_return'] = 'Click %sHere%s to return to the Optimize Database';
$lang['Optimize_success'] = 'The Database has been successfully optimized';
$lang['Optimize_NoTableChecked'] = '<b>No</b> Tables Checked';

// End Optimize Database

$lang['Backup_options'] = 'Backup options';
$lang['Start_backup'] = 'Start Backup';
$lang['Full_backup'] = 'Full backup';
$lang['Structure_backup'] = 'Structure-Only backup';
$lang['Data_backup'] = 'Data only backup';
$lang['Additional_tables'] = 'Additional tables';
$lang['Gzip_compress'] = 'Gzip compress file';
$lang['Select_file'] = 'Select a file';
$lang['Start_Restore'] = 'Start Restore';

$lang['Restore_success'] = 'The Database has been successfully restored.<br /><br />Your site should be back to the state it was when the backup was made.';
$lang['Backup_download'] = 'Your download will start shortly; please wait until it begins.';
$lang['Backups_not_supported'] = 'Sorry, but database backups are not currently supported for your database system.';

$lang['Restore_Error_uploading'] = 'Error in uploading the backup file';
$lang['Restore_Error_filename'] = 'Filename problem; please try an alternative file';
$lang['Restore_Error_decompress'] = 'Cannot decompress a gzip file; please upload a plain text version';
$lang['Restore_Error_no_file'] = 'No file was uploaded';


//
// Auth pages
//
$lang['Select_a_User'] = 'Select a User';
$lang['Select_a_Group'] = 'Select a Group';
$lang['Select_a_Forum'] = 'Select a Forum';
$lang['Auth_Control_User'] = 'User Permissions Control';
$lang['Auth_Control_Group'] = 'Group Permissions Control';
$lang['Auth_Control_Forum'] = 'Forum Permissions Control';
$lang['Look_up_User'] = 'Look up User';
$lang['Look_up_Group'] = 'Look up Group';
$lang['Look_up_Forum'] = 'Look up Forum';

$lang['Group_auth_explain'] = 'Here you can alter the permissions and moderator status assigned to each user group. Do not forget when changing group permissions that individual user permissions may still allow the user entry to forums, etc. You will be warned if this is the case.';
$lang['User_auth_explain'] = 'Here you can alter the permissions and moderator status assigned to each individual user. Do not forget when changing user permissions that group permissions may still allow the user entry to forums, etc. You will be warned if this is the case.';
$lang['Forum_auth_explain'] = 'Here you can alter the authorisation levels of each forum. You will have both a simple and advanced method for doing this, where advanced offers greater control of each forum operation. Remember that changing the permission level of forums will affect which users can carry out the various operations within them.';

$lang['Simple_mode'] = 'Simple Mode';
$lang['Advanced_mode'] = 'Advanced Mode';
$lang['Moderator_status'] = 'Moderator status';

$lang['Allowed_Access'] = 'Allowed Access';
$lang['Disallowed_Access'] = 'Disallowed Access';
$lang['Is_Moderator'] = 'Is Moderator';
$lang['Not_Moderator'] = 'Not Moderator';

$lang['Conflict_warning'] = 'Authorisation Conflict Warning';
$lang['Conflict_access_userauth'] = 'This user still has access rights to this forum via group membership. You may want to alter the group permissions or remove this user the group to fully prevent them having access rights. The groups granting rights (and the forums involved) are noted below.';
$lang['Conflict_mod_userauth'] = 'This user still has moderator rights to this forum via group membership. You may want to alter the group permissions or remove this user the group to fully prevent them having moderator rights. The groups granting rights (and the forums involved) are noted below.';

$lang['Conflict_access_groupauth'] = 'The following user (or users) still have access rights to this forum via their user permission settings. You may want to alter the user permissions to fully prevent them having access rights. The users granted rights (and the forums involved) are noted below.';
$lang['Conflict_mod_groupauth'] = 'The following user (or users) still have moderator rights to this forum via their user permissions settings. You may want to alter the user permissions to fully prevent them having moderator rights. The users granted rights (and the forums involved) are noted below.';

$lang['Public'] = 'Public';
$lang['Private'] = 'Private';
$lang['Registered'] = 'Registered';
$lang['Administrators'] = 'Administrators';
$lang['Hidden'] = 'Hidden';

// These are displayed in the drop down boxes for advanced
// mode forum auth, try and keep them short!
$lang['Forum_ALL'] = 'ALL';
$lang['Forum_REG'] = 'REG';
$lang['Forum_PRIVATE'] = 'PRIVATE';
$lang['Forum_MOD'] = 'MOD';
$lang['Forum_ADMIN'] = 'ADMIN';

$lang['View'] = 'View';
$lang['Read'] = 'Read';
$lang['Post'] = 'Post';
$lang['Reply'] = 'Reply';
$lang['Edit'] = 'Edit';
$lang['Delete'] = 'Delete';
$lang['Sticky'] = 'Sticky';
$lang['Announce'] = 'Announce';
$lang['Vote'] = 'Vote';
$lang['Pollcreate'] = 'Poll create';

$lang['Permissions'] = 'Permissions';
$lang['Simple_Permission'] = 'Simple Permissions';

$lang['User_Level'] = 'User Level';
$lang['Auth_User'] = 'User';
$lang['Auth_Admin'] = 'Administrator';
$lang['Group_memberships'] = 'Usergroup memberships';
$lang['Usergroup_members'] = 'This group has the following members';

$lang['Forum_auth_updated'] = 'Forum permissions updated';
$lang['User_auth_updated'] = 'User permissions updated';
$lang['Group_auth_updated'] = 'Group permissions updated';

$lang['Auth_updated'] = 'Permissions have been updated';
$lang['Click_return_userauth'] = 'Click %sHere%s to return to User Permissions';
$lang['Click_return_groupauth'] = 'Click %sHere%s to return to Group Permissions';
$lang['Click_return_forumauth'] = 'Click %sHere%s to return to Forum Permissions';


//
// Banning
//
$lang['Ban_control'] = 'Ban Control';
$lang['Ban_explain'] = 'Here you can control the banning of users. You can achieve this by banning either or both of a specific user or an individual or range of IP addresses or hostnames. These methods prevent a user from even reaching the index page of your site. To prevent a user from registering under a different username you can also specify a banned email address. Please note that banning an email address alone will not prevent that user from being able to log on to your site. You should use one of the first two methods to achieve this.';
$lang['Ban_explain_warn'] = 'Please note that entering a range of IP addresses results in all the addresses between the start and end being added to the banlist. Attempts will be made to minimise the number of addresses added to the database by introducing wildcards automatically where appropriate. If you really must enter a range, try to keep it small or better yet state specific addresses.';

$lang['Select_username'] = 'Select a Username';
$lang['Select_ip'] = 'Select an IP address';
$lang['Select_email'] = 'Select an Email address';

$lang['Ban_username'] = 'Ban one or more specific users';
$lang['Ban_username_explain'] = 'You can ban multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Ban_IP'] = 'Ban one or more IP addresses or hostnames';
$lang['IP_hostname'] = 'IP addresses or hostnames';
$lang['Ban_IP_explain'] = 'To specify several different IP addresses or hostnames separate them with commas. To specify a range of IP addresses, separate the start and end with a hyphen (-); to specify a wildcard, use an asterisk (*).';

$lang['Ban_email'] = 'Ban one or more email addresses';
$lang['Ban_email_explain'] = 'To specify more than one email address, separate them with commas. To specify a wildcard username, use * like *@hotmail.com';

$lang['Unban_username'] = 'Un-ban one more specific users';
$lang['Unban_username_explain'] = 'You can unban multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_IP'] = 'Un-ban one or more IP addresses';
$lang['Unban_IP_explain'] = 'You can unban multiple IP addresses in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_email'] = 'Un-ban one or more email addresses';
$lang['Unban_email_explain'] = 'You can unban multiple email addresses in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['No_banned_users'] = 'No banned usernames';
$lang['No_banned_ip'] = 'No banned IP addresses';
$lang['No_banned_email'] = 'No banned email addresses';

$lang['Ban_update_sucessful'] = 'The banlist has been updated successfully';
$lang['Click_return_banadmin'] = 'Click %sHere%s to return to Ban Control';


//
// Configuration
//
$lang['General_Config'] = 'General Configuration';
$lang['Config_explain'] = 'The form below will allow you to customize all the general site options.';

$lang['Click_return_config'] = 'Click %sHere%s to return to General Configuration';

$lang['General_settings'] = 'General Site Settings';
$lang['Server_name'] = 'Domain Name';
$lang['Server_name_explain'] = 'The domain name from which this site runs';
$lang['Script_path'] = 'Script path';
$lang['Script_path_explain'] = 'The path where Minerva is located relative to the domain name';
$lang['Server_port'] = 'Server Port';
$lang['Server_port_explain'] = 'The port your server is running on, usually 80. Only change if different';
$lang['Site_name'] = 'Site name';
$lang['Site_desc'] = 'Site description';
$lang['Board_disable'] = 'Disable site';
$lang['Board_disable_explain'] = 'This will make the site unavailable to users. Administrators are able to access the Administration Panel while the site is disabled.';
$lang['Board_disable_msg'] = 'Disable site message';
$lang['Board_disable_msg_explain'] = 'This text will be displayed to members and guests if the site has been disabled.';
$lang['Board_disable_adminview'] = 'Administrator access when site disabled';
$lang['Board_disable_adminview_explain'] = 'This will allow Administrators to access the entire site even when it has been disabled.';

$lang['Acct_activation'] = 'Enable account activation';
$lang['Acc_None'] = 'None'; // These three entries are the type of activation
$lang['Acc_User'] = 'User';
$lang['Acc_Admin'] = 'Admin';

$lang['Auth_settings'] = 'Authentication Settings';
$lang['Disable_Guest'] = 'Disable Guest Account';
$lang['Auth_mode'] = 'Authentication Mode';
$lang['Ldap_host'] = 'LDAP Host Name';
$lang['Ldap_port'] = 'LDAP Port';
$lang['Ldap_host2'] = 'LDAP Secondary Host Name (OPTIONAL)';
$lang['Ldap_port2'] = 'LDAP Secondery Host Port (OPTIONAL)';
$lang['Ldap_dn'] = 'Base DN';
$lang['Ldap_dn_explain'] = 'Base DN, which will be used as start point for LDAP directory';
$lang['Ldap_uid'] = 'LDAP User ID';
$lang['Ldap_uid_explain'] = 'LDAP User ID, what LDAP property do you want to use as your site user names. (default = \'uid\', Active Directory = \'samaccountname\')';
$lang['Ldap_uid'] = 'LDAP User ID Field';
$lang['Ldap_uid_explain'] = 'LDAP User ID Field, what LDAP property/field do you want to use as your forum user names. (default = \'uid\', Active Directory = \'samaccountname\')';
$lang['ldap_group_sync'] = 'Enable Group sync with LDAP';
$lang['ldap_gid'] = 'LDAP Group Membership Field';
$lang['ldap_gid_explain'] = 'What LDAP property/field do you want to use to determen that groups a user is a member of.  This will be a user property. (default = \'member\', Active Directory = \'memberof\')';
$lang['ldap_email'] = 'LDAP property/field containing user e-mail addresses';
$lang['ldap_web'] = 'LDAP property/field containing user web site addresses';
$lang['ldap_location'] = 'LDAP property/field containing user locations';
$lang['ldap_occupation'] = 'LDAP property/field containing user occupation';
$lang['ldap_signature'] = 'LDAP property/field containing user signature (I.E. display name)';
$lang['Ldap_proxy_dn'] = 'Proxy DN (OPTIONAL)';
$lang['Ldap_proxy_dn_explain'] = 'Used if your LDAP server does not allow anonymous access (I.E. Active Directory).  This must be the FULL distinguished name of a user that has read access to your LDAP server';
$lang['Ldap_proxy_dn_pass'] = 'Proxy DN Password (OPTIONAL)';
$lang['Ldap_proxy_dn_pass_explain'] = 'This password is stored in CLEAR text, in your Database!!!  Make sure this user only has read access to LDAP and nothing else (make it a guest).  And make sure users can not get to your DB';
$lang['Ldap_start_tls'] = 'TLS support';

$lang['Abilities_settings'] = 'User and Basic Site Settings';
$lang['Max_poll_options'] = 'Max number of poll options';
$lang['Flood_Interval'] = 'Flood Interval';
$lang['Flood_Interval_explain'] = 'Number of seconds a user must wait between posts';
$lang['Board_email_form'] = 'User email via site';
$lang['Board_email_form_explain'] = 'Users send email to each other via this site';
$lang['Topics_per_page'] = 'Topics Per Page';
$lang['Posts_per_page'] = 'Posts Per Page';
$lang['Hot_threshold'] = 'Posts for Popular Threshold';
$lang['Default_style'] = 'Default Style';
$lang['Override_style'] = 'Override user style';
$lang['Override_style_explain'] = 'Replaces users style with the default';
$lang['Default_language'] = 'Default Language';
$lang['Date_format'] = 'Date Format';
$lang['System_timezone'] = 'System Timezone';
$lang['Enable_gzip'] = 'Enable GZip Compression';
$lang['Enable_prune'] = 'Enable Forum Pruning';
$lang['Allow_HTML'] = 'Allow HTML';
$lang['Allow_BBCode'] = 'Allow BBCode';
$lang['Allowed_tags'] = 'Allowed HTML tags';
$lang['Allowed_tags_explain'] = 'Separate tags with commas';
$lang['Allow_smilies'] = 'Allow Smilies';
$lang['Smilies_path'] = 'Smilies Storage Path';
$lang['Smilies_path_explain'] = 'Path relative to your template images directory, e.g. smiles';
$lang['Allow_sig'] = 'Allow Signatures';
$lang['Max_sig_length'] = 'Maximum signature length';
$lang['Max_sig_length_explain'] = 'Maximum number of characters in user signatures';
$lang['Allow_name_change'] = 'Allow Username changes';

$lang['Avatar_settings'] = 'Avatar Settings';
$lang['Allow_local'] = 'Enable gallery avatars';
$lang['Allow_remote'] = 'Enable remote avatars';
$lang['Allow_remote_explain'] = 'Avatars linked to from another website';
$lang['Allow_upload'] = 'Enable avatar uploading';
$lang['Max_filesize'] = 'Maximum Avatar File Size';
$lang['Max_filesize_explain'] = 'For uploaded avatar files';
$lang['Max_avatar_size'] = 'Maximum Avatar Dimensions';
$lang['Max_avatar_size_explain'] = '(Height x Width in pixels)';
$lang['Avatar_storage_path'] = 'Avatar Storage Path';
$lang['Avatar_storage_path_explain'] = 'Path under your Minerva root dir, e.g. files/avatars';
$lang['Avatar_gallery_path'] = 'Avatar Gallery Path';
$lang['Avatar_gallery_path_explain'] = 'Path under your Minerva root dir for pre-loaded images, e.g. files/avatars/gallery';

$lang['COPPA_settings'] = 'COPPA Settings';
$lang['COPPA_fax'] = 'COPPA Fax Number';
$lang['COPPA_mail'] = 'COPPA Mailing Address';
$lang['COPPA_mail_explain'] = 'This is the mailing address to which parents will send COPPA registration forms';

$lang['Email_settings'] = 'Email Settings';
$lang['Admin_email'] = 'Admin Email Address';
$lang['Email_sig'] = 'Email Signature';
$lang['Email_sig_explain'] = 'This text will be attached to all emails the site sends';
$lang['Use_SMTP'] = 'Use SMTP Server for email';
$lang['Use_SMTP_explain'] = 'Say yes if you want or have to send email via a named server instead of the local mail function';
$lang['SMTP_server'] = 'SMTP Server Address';
$lang['SMTP_username'] = 'SMTP Username';
$lang['SMTP_username_explain'] = 'Only enter a username if your SMTP server requires it';
$lang['SMTP_password'] = 'SMTP Password';
$lang['SMTP_password_explain'] = 'Only enter a password if your SMTP server requires it';

$lang['Disable_privmsg'] = 'Private Messaging';
$lang['Inbox_limits'] = 'Max posts in Inbox';
$lang['Sentbox_limits'] = 'Max posts in Sentbox';
$lang['Savebox_limits'] = 'Max posts in Savebox';

$lang['Cookie_settings'] = 'Cookie settings';
$lang['Cookie_settings_explain'] = 'These details define how cookies are sent to your users\' browsers. In most cases the default values for the cookie settings should be sufficient, but if you need to change them do so with care -- incorrect settings can prevent users from logging in';
$lang['Cookie_domain'] = 'Cookie domain';
$lang['Cookie_name'] = 'Cookie name';
$lang['Cookie_path'] = 'Cookie path';
$lang['Cookie_secure'] = 'Cookie secure';
$lang['Cookie_secure_explain'] = 'If your server is running via SSL, set this to enabled, else leave as disabled';
$lang['Session_length'] = 'Session length [ seconds ]';

//
// Forum Management
//
$lang['Forum_admin'] = 'Forum Administration';
$lang['Forum_admin_explain'] = 'From this panel you can add, delete, edit, re-order and re-synchronise categories and forums';
$lang['Edit_forum'] = 'Edit forum';
$lang['Create_forum'] = 'Create new forum';
$lang['Create_category'] = 'Create new category';
$lang['Remove'] = 'Remove';
$lang['Action'] = 'Action';
$lang['Update_order'] = 'Update Order';
$lang['Config_updated'] = 'Forum Configuration Updated Successfully';
$lang['Edit'] = 'Edit';
$lang['Delete'] = 'Delete';
$lang['Move_up'] = 'Move up';
$lang['Move_down'] = 'Move down';
$lang['Resync'] = 'Resync';
$lang['No_mode'] = 'No mode was set';
$lang['Forum_edit_delete_explain'] = 'The form below will allow you to customize all the general site options. For User and Forum configurations use the related links on the left hand side';

$lang['Move_contents'] = 'Move all contents';
$lang['Forum_delete'] = 'Delete Forum';
$lang['Forum_delete_explain'] = 'The form below will allow you to delete a forum (or category) and decide where you want to put all topics (or forums) it contained.';

$lang['Status_locked'] = 'Locked';
$lang['Status_unlocked'] = 'Unlocked';
$lang['Forum_settings'] = 'General Forum Settings';
$lang['Forum_name'] = 'Site name';
$lang['Forum_desc'] = 'Description';
$lang['Forum_status'] = 'Site status';
$lang['Forum_pruning'] = 'Auto-pruning';

$lang['prune_freq'] = 'Check for topic age every';
$lang['prune_days'] = 'Remove topics that have not been posted to in';
$lang['Set_prune_data'] = 'You have turned on auto-prune for this forum but did not set a frequency or number of days to prune. Please go back and do so.';

$lang['Move_and_Delete'] = 'Move and Delete';

$lang['Delete_all_posts'] = 'Delete all posts';
$lang['Nowhere_to_move'] = 'Nowhere to move to';

$lang['Edit_Category'] = 'Edit Category';
$lang['Edit_Category_explain'] = 'Use this form to modify a category\'s name.';

$lang['Forums_updated'] = 'Forum and Category information updated successfully';

$lang['Must_delete_forums'] = 'You need to delete all forums before you can delete this category';

$lang['Click_return_forumadmin'] = 'Click %sHere%s to return to Site Administration';


//
// Smiley Management
//
$lang['smiley_title'] = 'Smiles Editing Utility';
$lang['smile_desc'] = 'From this page you can add, remove and edit the emoticons or smileys that your users can use in their posts and private messages.';

$lang['smiley_config'] = 'Smiley Configuration';
$lang['smiley_code'] = 'Smiley Code';
$lang['smiley_url'] = 'Smiley Image File';
$lang['smiley_emot'] = 'Smiley Emotion';
$lang['smile_add'] = 'Add a new Smiley';
$lang['Smile'] = 'Smile';
$lang['Emotion'] = 'Emotion';

$lang['Select_pak'] = 'Select Pack (.pak) File';
$lang['replace_existing'] = 'Replace Existing Smiley';
$lang['keep_existing'] = 'Keep Existing Smiley';
$lang['smiley_import_inst'] = 'You should unzip the smiley package and upload all files to the appropriate Smiley directory for your installation. Then select the correct information in this form to import the smiley pack.';
$lang['smiley_import'] = 'Smiley Pack Import';
$lang['choose_smile_pak'] = 'Choose a Smile Pack .pak file';
$lang['import'] = 'Import Smileys';
$lang['smile_conflicts'] = 'What should be done in case of conflicts';
$lang['del_existing_smileys'] = 'Delete existing smileys before import';
$lang['import_smile_pack'] = 'Import Smiley Pack';
$lang['export_smile_pack'] = 'Create Smiley Pack';
$lang['export_smiles'] = 'To create a smiley pack from your currently installed smileys, click %sHere%s to download the smiles.pak file. Name this file appropriately making sure to keep the .pak file extension.  Then create a zip file containing all of your smiley images plus this .pak configuration file.';

$lang['smiley_add_success'] = 'The Smiley was successfully added';
$lang['smiley_edit_success'] = 'The Smiley was successfully updated';
$lang['smiley_import_success'] = 'The Smiley Pack was imported successfully!';
$lang['smiley_del_success'] = 'The Smiley was successfully removed';
$lang['Click_return_smileadmin'] = 'Click %sHere%s to return to Smiley Administration';


//
// User Management
//
$lang['User_admin'] = 'User Administration';
$lang['User_admin_explain'] = 'Here you can change your users\' information and certain options. To modify the users\' permissions, please use the user and group permissions system.';
$lang['User_Type'] = 'User Authentication system';
$lang['User_Type_explain'] = 'Selects what systems the user can authenticate against.  See install file or phpBB.com forum for more explanation';

$lang['Look_up_user'] = 'Look up user';

$lang['Admin_user_fail'] = 'Couldn\'t update the user\'s profile.';
$lang['Admin_user_updated'] = 'The user\'s profile was successfully updated.';
$lang['Click_return_useradmin'] = 'Click %sHere%s to return to User Administration';

$lang['User_delete'] = 'Delete this user';
$lang['User_delete_explain'] = 'Click here to delete this user; this cannot be undone.';
$lang['User_deleted'] = 'User was successfully deleted.';

$lang['User_status'] = 'User is active';
$lang['User_allowpm'] = 'Can send Private Messages';
$lang['User_allowavatar'] = 'Can display avatar';

$lang['Admin_avatar_explain'] = 'Here you can see and delete the user\'s current avatar.';

$lang['User_special'] = 'Special admin-only fields';
$lang['User_special_explain'] = 'These fields are not able to be modified by the users.  Here you can set their status and other options that are not given to users.';

//
// Custom Title MOD
//
$lang['Custom_title_status'] = 'Custom title activation status';
$lang['Custom_title_status_regdate'] = 'Based on registration date and posts';
$lang['Custom_title_status_disabled'] = 'Disabled';
$lang['Custom_title_status_enabled'] = 'Enabled';
$lang['Custom_title_settings'] = 'Custom Title Settings';
$lang['Custom_title_days'] = 'Days of registration required';
$lang['Custom_title_posts'] = 'Posts required';
$lang['Custom_title_mode'] = 'Rank Replacement Mode';
$lang['Custom_title_mode_explain'] = 'Here you can choose whether or not the rank and rank image are replaced if the user has set a custom title. If you choose \'No replacement\', it will instead appear as a separate title.';
$lang['Custom_title_mode_independent'] = 'No replacement';
$lang['Custom_title_mode_replace_rank'] = 'Replace rank only';
$lang['Custom_title_mode_replace_both'] = 'Replace rank and rank image';
$lang['Custom_title_maxlength'] = 'Maximum length';
$lang['Custom_title_maxlength_explain'] = 'This controls how long a user may set their custom title.  Please enter a number from 0 to 255.';

// Added for enhanced user management
$lang['User_lookup_explain'] = 'You can lookup users by specifying one or more of the criteria below. No wildcards are needed, they will be added automatically.';
$lang['One_user_found'] = 'Only one user was found, you are being taken to that user';
$lang['Click_goto_user'] = 'Click %sHere%s to edit this users profile';
$lang['User_joined_explain'] = 'The syntax used is identical to the PHP <a href="http://www.php.net/strtotime" target="_other">strtotime()</a> function';
$lang['Click_return_perms_admin'] = 'Click %sHere%s to return to User Permissions Control';

//
// Group Management
//
$lang['Group_administration'] = 'Group Administration';
$lang['Group_admin_explain'] = 'From this panel you can administer all your usergroups. You can delete, create and edit existing groups. You may choose moderators, toggle open/closed group status and set the group name and description';
$lang['Error_updating_groups'] = 'There was an error while updating the groups';
$lang['Updated_group'] = 'The group was successfully updated';
$lang['Added_new_group'] = 'The new group was successfully created';
$lang['Deleted_group'] = 'The group was successfully deleted';
$lang['New_group'] = 'Create new group';
$lang['Edit_group'] = 'Edit group';
$lang['group_name'] = 'Group name';
$lang['group_description'] = 'Group description';
$lang['group_moderator'] = 'Group moderator';
$lang['group_status'] = 'Group status';
$lang['group_open'] = 'Open group';
$lang['group_closed'] = 'Closed group';
$lang['group_hidden'] = 'Hidden group';
$lang['group_auto'] = 'Auto join group';
$lang['group_delete'] = 'Delete group';
$lang['group_delete_check'] = 'Delete this group';
$lang['submit_group_changes'] = 'Submit Changes';
$lang['reset_group_changes'] = 'Reset Changes';
$lang['No_group_name'] = 'You must specify a name for this group';
$lang['No_group_moderator'] = 'You must specify a moderator for this group';
$lang['No_group_mode'] = 'You must specify a mode for this group, open or closed';
$lang['No_group_action'] = 'No action was specified';
$lang['delete_group_moderator'] = 'Delete the old group moderator?';
$lang['delete_moderator_explain'] = 'If you\'re changing the group moderator, check this box to remove the old moderator from the group.  Otherwise, do not check it, and the user will become a regular member of the group.';
$lang['group_ldap_update'] = 'Group membership is managed in LDAP';
$lang['Click_return_groupsadmin'] = 'Click %sHere%s to return to Group Administration.';
$lang['Select_group'] = 'Select a group';
$lang['Look_up_group'] = 'Look up group';


//
// Prune Administration
//
$lang['Forum_Prune'] = 'Forum Prune';
$lang['Forum_Prune_explain'] = 'This will delete any topic which has not been posted to within the number of days you select. If you do not enter a number then all topics will be deleted. It will not remove topics in which polls are still running nor will it remove announcements. You will need to remove those topics manually.';
$lang['Do_Prune'] = 'Do Prune';
$lang['All_Forums'] = 'All Forums';
$lang['Prune_topics_not_posted'] = 'Prune topics with no replies in this many days';
$lang['Topics_pruned'] = 'Topics pruned';
$lang['Posts_pruned'] = 'Posts pruned';
$lang['Prune_success'] = 'Pruning of forums was successful';


//
// Word censor
//
$lang['Words_title'] = 'Word Censoring';
$lang['Words_explain'] = 'From this control panel you can add, edit, and remove words that will be automatically censored on your site. In addition people will not be allowed to register with usernames containing these words. Wildcards (*) are accepted in the word field. For example, *test* will match detestable, test* would match testing, *test would match detest.';
$lang['Word'] = 'Word';
$lang['Edit_word_censor'] = 'Edit word censor';
$lang['Replacement'] = 'Replacement';
$lang['Add_new_word'] = 'Add new word';
$lang['Update_word'] = 'Update word censor';

$lang['Must_enter_word'] = 'You must enter a word and its replacement';
$lang['No_word_selected'] = 'No word selected for editing';

$lang['Word_updated'] = 'The selected word censor has been successfully updated';
$lang['Word_added'] = 'The word censor has been successfully added';
$lang['Word_removed'] = 'The selected word censor has been successfully removed';

$lang['Click_return_wordadmin'] = 'Click %sHere%s to return to Word Censor Administration';

//
// Acronyms
//
$lang['Acronyms_title'] = 'Acronyms Administration';
$lang['Acronyms_explain'] = 'From this control panel you can add, edit, and remove acronyms that will be automatically added to posts.';
$lang['Acronym'] = 'Acronym';
$lang['Acronyms'] = 'Acronyms';
$lang['Edit_acronym'] = 'Edit Acronym';
$lang['Description'] = 'Description';
$lang['Add_new_acronym'] = 'Add new acronym';
$lang['Update_acronym'] = 'Update acronym';

$lang['Must_enter_acronym'] = 'You must enter a acronym and its description';
$lang['No_acronym_selected'] = 'No acronym selected for editing';

$lang['Acronym_updated'] = 'The selected acronym has been successfully updated';
$lang['Acronym_added'] = 'The acronym has been successfully added';
$lang['Acronym_removed'] = 'The selected acronym has been successfully removed';

$lang['Click_return_acronymadmin'] = 'Click %sHere%s to return to Acronym Administration';

//
// Smart Tags
//
$lang['Smart_title'] = 'Smart Tag Administration';
$lang['Smart_explain'] = 'From this control panel you can add, edit, and remove smart tags that will be automatically added to posts.';
$lang['Smart'] = 'Smart Tag';
$lang['Smarts'] = 'Smart Tags';
$lang['Edit_smart'] = 'Edit Smart Tag';
$lang['Smart_URL'] = 'URL';
$lang['Add_new_smart'] = 'Add new Smart Tag';
$lang['Update_smart'] = 'Update Smart Tag';

$lang['Must_enter_smart'] = 'You must enter a Smart Tag and its description';
$lang['No_smart_selected'] = 'No Smart Tag selected for editing';

$lang['Smart_updated'] = 'The selected Smart Tag has been successfully updated';
$lang['Smart_added'] = 'The Smart Tag has been successfully added';
$lang['Smart_removed'] = 'The selected Smart Tag has been successfully removed';

$lang['Click_return_smartadmin'] = 'Click %sHere%s to return to Smart Tag Administration';

//
// Mass Email
//
$lang['Mass_email_explain'] = 'Here you can email a message to either all of your users or all users of a specific group.  To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time and you will be notified when the script has completed';
$lang['Compose'] = 'Compose';

$lang['Recipients'] = 'Recipients';
$lang['All_users'] = 'All Users';

$lang['Email_successfull'] = 'Your message has been sent';
$lang['Click_return_massemail'] = 'Click %sHere%s to return to the Mass Email form';


//
// Ranks admin
//
$lang['Ranks_title'] = 'Rank Administration';
$lang['Ranks_explain'] = 'Using this form you can add, edit, view and delete ranks. You can also create custom ranks which can be applied to a user via the user management facility';

$lang['Add_new_rank'] = 'Add new rank';

$lang['Rank_title'] = 'Rank Title';
$lang['Rank_special'] = 'Set as Special Rank';
$lang['Rank_minimum'] = 'Minimum Posts';
$lang['Rank_maximum'] = 'Maximum Posts';
$lang['Rank_image'] = 'Rank Image';
$lang['Rank_image_explain'] = 'Used to select a small image associated with rank. Rank image is relative to the <b>templates/<i>templatename</i>/images/lang_<i>xxx</i>/</b> directory.';


$lang['Must_select_rank'] = 'You must select a rank';
$lang['No_assigned_rank'] = 'No special rank assigned';

$lang['Rank_updated'] = 'The rank was successfully updated';
$lang['Rank_added'] = 'The rank was successfully added';
$lang['Rank_removed'] = 'The rank was successfully deleted';
$lang['No_update_ranks'] = 'The rank was successfully deleted. However, user accounts using this rank were not updated.  You will need to manually reset the rank on these accounts';

$lang['Click_return_rankadmin'] = 'Click %sHere%s to return to Rank Administration';


//
// Disallow Username Admin
//
$lang['Disallow_control'] = 'Username Disallow Control';
$lang['Disallow_explain'] = 'Here you can control usernames which will not be allowed to be used.  Disallowed usernames are allowed to contain a wildcard character of *.  Please note that you will not be allowed to specify any username that has already been registered. You must first delete that name then disallow it.';

$lang['Delete_disallow'] = 'Delete';
$lang['Delete_disallow_title'] = 'Remove a Disallowed Username';
$lang['Delete_disallow_explain'] = 'You can remove a disallowed username by selecting the username from this list and clicking submit';

$lang['Add_disallow'] = 'Add';
$lang['Add_disallow_title'] = 'Add a disallowed username';
$lang['Add_disallow_explain'] = 'You can disallow a username using the wildcard character * to match any character';

$lang['No_disallowed'] = 'No Disallowed Usernames';

$lang['Disallowed_deleted'] = 'The disallowed username has been successfully removed';
$lang['Disallow_successful'] = 'The disallowed username has been successfully added';
$lang['Disallowed_already'] = 'The name you entered could not be disallowed. It either already exists in the list, exists in the word censor list, or a matching username is present.';

$lang['Click_return_disallowadmin'] = 'Click %sHere%s to return to Disallow Username Administration';


//
// Styles Admin
//
$lang['Styles_admin'] = 'Styles Administration';
$lang['Styles_explain'] = 'Using this facility you can add, remove and manage styles (templates and themes) available to your users';
$lang['Styles_addnew_explain'] = 'The following list contains all the themes that are available for the templates you currently have. The items on this list have not yet been installed into the Minerva database. To install a theme, simply click the install link beside an entry.';

$lang['Select_template'] = 'Select a Template';

$lang['Style'] = 'Style';
$lang['Template'] = 'Template';
$lang['Install'] = 'Install';
$lang['Download'] = 'Download';

$lang['Edit_theme'] = 'Edit Theme';
$lang['Edit_theme_explain'] = 'In the form below you can edit the settings for the selected theme';

$lang['Create_theme'] = 'Create Theme';
$lang['Create_theme_explain'] = 'Use the form below to create a new theme for a selected template. When entering colours (for which you should use hexadecimal notation) you must not include the initial #, i.e.. CCCCCC is valid, #CCCCCC is not';

$lang['Export_themes'] = 'Export Themes';
$lang['Export_explain'] = 'In this panel you will be able to export the theme data for a selected template. Select the template from the list below and the script will create the theme configuration file and attempt to save it to the selected template directory. If it cannot save the file itself it will give you the option to download it. In order for the script to save the file you must give write access to the webserver for the selected template dir. For more information on this see the Minerva users guide.';

$lang['Theme_installed'] = 'The selected theme has been installed successfully';
$lang['Style_removed'] = 'The selected style has been removed from the database. To fully remove this style from your system you must delete the appropriate style from your templates directory.';
$lang['Theme_info_saved'] = 'The theme information for the selected template has been saved. You should now return the permissions on the theme_info.cfg (and if applicable the selected template directory) to read-only';
$lang['Theme_updated'] = 'The selected theme has been updated. You should now export the new theme settings';
$lang['Theme_created'] = 'Theme created. You should now export the theme to the theme configuration file for safe keeping or use elsewhere';

$lang['Confirm_delete_style'] = 'Are you sure you want to delete this style?';

$lang['Download_theme_cfg'] = 'The exporter could not write the theme information file. Click the button below to download this file with your browser. Once you have downloaded it you can transfer it to the directory containing the template files. You can then package the files for distribution or use elsewhere if you desire';
$lang['No_themes'] = 'The template you selected has no themes attached to it. To create a new theme click the Create New link on the left hand panel';
$lang['No_template_dir'] = 'Could not open the template directory. It may be unreadable by the webserver or may not exist';
$lang['Cannot_remove_style'] = 'You cannot remove the style selected since it is currently the site default. Please change the default style and try again.';
$lang['Style_exists'] = 'The style name to selected already exists, please go back and choose a different name.';

$lang['Click_return_styleadmin'] = 'Click %sHere%s to return to Style Administration';

$lang['Theme_settings'] = 'Theme Settings';
$lang['Theme_element'] = 'Theme Element';
$lang['Simple_name'] = 'Simple Name';
$lang['Value'] = 'Value';
$lang['Save_Settings'] = 'Save Settings';

$lang['Stylesheet'] = 'CSS Stylesheet';
$lang['Background_image'] = 'Background Image';
$lang['Background_color'] = 'Background Colour';
$lang['Theme_name'] = 'Theme Name';
$lang['Link_color'] = 'Link Colour';
$lang['Text_color'] = 'Text Colour';
$lang['VLink_color'] = 'Visited Link Colour';
$lang['ALink_color'] = 'Active Link Colour';
$lang['HLink_color'] = 'Hover Link Colour';
$lang['Tr_color1'] = 'Table Row Colour 1';
$lang['Tr_color2'] = 'Table Row Colour 2';
$lang['Tr_color3'] = 'Table Row Colour 3';
$lang['Tr_class1'] = 'Table Row Class 1';
$lang['Tr_class2'] = 'Table Row Class 2';
$lang['Tr_class3'] = 'Table Row Class 3';
$lang['Th_color1'] = 'Table Header Colour 1';
$lang['Th_color2'] = 'Table Header Colour 2';
$lang['Th_color3'] = 'Table Header Colour 3';
$lang['Th_class1'] = 'Table Header Class 1';
$lang['Th_class2'] = 'Table Header Class 2';
$lang['Th_class3'] = 'Table Header Class 3';
$lang['Td_color1'] = 'Table Cell Colour 1';
$lang['Td_color2'] = 'Table Cell Colour 2';
$lang['Td_color3'] = 'Table Cell Colour 3';
$lang['Td_class1'] = 'Table Cell Class 1';
$lang['Td_class2'] = 'Table Cell Class 2';
$lang['Td_class3'] = 'Table Cell Class 3';
$lang['fontface1'] = 'Font Face 1';
$lang['fontface2'] = 'Font Face 2';
$lang['fontface3'] = 'Font Face 3';
$lang['fontsize1'] = 'Font Size 1';
$lang['fontsize2'] = 'Font Size 2';
$lang['fontsize3'] = 'Font Size 3';
$lang['fontcolor1'] = 'Font Colour 1';
$lang['fontcolor2'] = 'Font Colour 2';
$lang['fontcolor3'] = 'Font Colour 3';
$lang['span_class1'] = 'Span Class 1';
$lang['span_class2'] = 'Span Class 2';
$lang['span_class3'] = 'Span Class 3';
$lang['img_poll_size'] = 'Polling Image Size [px]';
$lang['img_pm_size'] = 'Private Message Status size [px]';


//
// Install Process
//
$lang['Welcome_install'] = 'Welcome to Minerva R3 Installation';
$lang['Initial_config'] = 'Basic Configuration';
$lang['DB_config'] = 'Database Configuration';
$lang['Admin_config'] = 'Admin Configuration';
$lang['continue_upgrade'] = 'Once you have downloaded your config file to your local machine you may\'Continue Upgrade\' button below to move forward with the upgrade process.  Please wait to upload the config file until the upgrade process is complete.';
$lang['upgrade_submit'] = 'Continue Upgrade';

$lang['Installer_Error'] = 'An error has occurred during installation';
$lang['Previous_Install'] = 'A previous installation has been detected';
$lang['Install_db_error'] = 'An error occurred trying to update the database';

$lang['Re_install'] = 'Your previous installation is still active.<br /><br />If you would like to re-install Minerva you should click the Yes button below. Please be aware that doing so will destroy all existing data and no backups will be made! The administrator username and password you have used to login in to the site will be re-created after the re-installation and no other settings will be retained.<br /><br />Think carefully before pressing Yes!';

$lang['Inst_Step_0'] = 'Thank you for choosing Minerva. In order to complete this install please fill out the details requested below. Please note that the database you install into should already exist. If you are installing to a database that uses ODBC, e.g. MS Access you should first create a DSN for it before proceeding.';

$lang['Start_Install'] = 'Start Install';
$lang['Finish_Install'] = 'Finish Installation';

$lang['Default_lang'] = 'Default site language';
$lang['DB_Host'] = 'Database Server Hostname / DSN';
$lang['DB_Name'] = 'Your Database Name';
$lang['DB_Username'] = 'Database Username';
$lang['DB_Password'] = 'Database Password';
$lang['Database'] = 'Your Database';
$lang['Install_lang'] = 'Choose Language for Installation';
$lang['dbms'] = 'Database Type';
$lang['Table_Prefix'] = 'Prefix for tables in database';
$lang['Admin_Username'] = 'Administrator Username';
$lang['Admin_Password'] = 'Administrator Password';
$lang['Admin_Password_confirm'] = 'Administrator Password [ Confirm ]';

$lang['Inst_Step_2'] = 'Your admin username has been created.  At this point your basic installation is complete. You will now be taken to a screen which will allow you to administer your new installation. Please be sure to check the General Configuration details and make any required changes. Thank you for choosing Minerva.';

$lang['Unwriteable_config'] = 'Your config file is un-writeable at present. A copy of the config file will be downloaded to your computer when you click the button below. You should upload this file to the same directory as Minerva. Once this is done you should log in using the administrator name and password you provided on the previous form and visit the admin control center (a link will appear at the bottom of each screen once logged in) to check the general configuration. Thank you for choosing Minerva.';
$lang['Download_config'] = 'Download Config';

$lang['ftp_choose'] = 'Choose Download Method';
$lang['ftp_option'] = '<br />Since FTP extensions are enabled in this version of PHP you may also be given the option of first trying to automatically FTP the config file into place.';
$lang['ftp_instructs'] = 'You have chosen to FTP the file to the account containing Minerva automatically.  Please enter the information below to facilitate this process. Note that the FTP path should be the exact path via FTP to your Minerva installation as if you were FTPing to it using any normal client.';
$lang['ftp_info'] = 'Enter Your FTP Information';
$lang['Attempt_ftp'] = 'Attempt to FTP config file into place';
$lang['Send_file'] = 'Just send the file to me and I\'ll FTP it manually';
$lang['ftp_path'] = 'FTP path to Minerva';
$lang['ftp_username'] = 'Your FTP Username';
$lang['ftp_password'] = 'Your FTP Password';
$lang['Transfer_config'] = 'Start Transfer';
$lang['NoFTP_config'] = 'The attempt to FTP the config file into place failed.  Please download the config file and FTP it into place manually.';

$lang['Install'] = 'Install';
$lang['Upgrade'] = 'Upgrade';


$lang['Install_Method'] = 'Choose your installation method';

$lang['Install_No_Ext'] = 'The PHP configuration on your server doesn\'t support the database type that you chose';

$lang['Install_No_PCRE'] = 'Minerva Requires the Perl-Compatible Regular Expressions Module for PHP which your PHP configuration doesn\'t appear to support!';

// addded to Auto group mod
$lang['group_count'] = 'Required number of posts';
$lang['group_count_max'] = 'Maximum number of posts';
$lang['group_count_updated'] = '%d member(s) have been removed, %d members are added to this group';
$lang['Group_count_enable'] = 'Automatically add users when posting';
$lang['Group_count_update'] = 'Add/Update new users';
$lang['Group_count_delete'] = 'Delete/Update old users';
$lang['User_allow_ag'] = "Activate Auto Group";
$lang['group_count_explain'] = 'When users have posted more posts than this value <i>(in any forum)</i> then they will be added to this usergroup<br /> This only applys if "'.$lang['Group_count_enable'].'" are enabled';

//Beginning Inactive Users

$lang['Users_Inactive'] = 'Inactive Users';
$lang['Users_Inactive_Explain'] = 'If in "Enable account activation" you have selected "User" or "Admin", in this list you will have the Users who have never been activated.<br /><br />By clicking on "Contact" you will send a contact e-mail to all the selected Users.<br />By clicking on "Activate" you will activate all the selected Users.<br />By clicking on "Delete" you will send an e-mail and delete all the selected Users.';
$lang['UI_Check_None'] = '"Enable account activation" is on <b>None</b>.';
$lang['UI_Check_User'] = '"Enable account activation" is on <b>User</b>';
$lang['UI_Check_Admin'] = '"Enable account activation" is on <b>Admin</b>.';
$lang['UI_User'] = 'User';
$lang['UI_Registration_Date'] = 'Registration Date';
$lang['UI_Active'] = 'Active';
$lang['UI_Last_Visit'] = 'Last Visit';
$lang['UI_Email_Sents'] = 'Email Sents';
$lang['UI_Last_Email_Sents'] = 'Last Email';
$lang['UI_CheckAll'] = 'Check All';
$lang['UI_UncheckAll'] = 'Uncheck All';
$lang['UI_InvertChecked'] = 'Invert Checked';
$lang['UI_Contact_Users'] = 'Contact';
$lang['UI_Delete_Users'] = 'Delete';
$lang['UI_Activate_Users'] = 'Activate';
$lang['UI_select_user_first'] = 'You must to select a User before';
$lang['UI_return'] = 'Click %sHere%s to return to the Inactive Users';
$lang['UI_Deleted_Users'] = 'The Users has been removed';
$lang['UI_Activated_Users'] = 'The Users has been actived';
$lang['UI_Alert_Days'] = "days";
$lang['UI_with_zero_messages'] = "with zero messages";
$lang['UI_Alert_Every'] = "Every";
$lang['UI_Alert_UpTo'] = "Up to";
$lang['UI_Alert_Over'] = "Over";

//End Inactive Users

$lang['disable_reg'] = 'Disable registrations';
$lang['disable_reg_explain'] = 'This will disable registrations to your site.';
$lang['disable_reg_msg'] = 'Disabled registration message';
$lang['disable_reg_msg_explain'] = 'This is the message that will be displayed if users try to register when registration is disabled';

// Meta tags setting
$lang['Meta_settings'] = 'META Tag Settings';
$lang['Meta_settings_explain'] = 'META tags is mainly use for search engine referencing, to get a good position in search engines such as google, fill it with the more informations as possible';
$lang['Meta_keywords'] = 'META Keywords';
$lang['Meta_keywords_explain'] = 'Define the search keywords for the search engines spiders';
$lang['Meta_description'] = 'META Description';
$lang['Meta_description_explain'] = 'Describe your site in more than 250 words';
$lang['Meta_revisit'] = 'META Revisit';
$lang['Meta_revisit_explain'] = 'Tell search engines spider to revisit after xx days';
$lang['Meta_author'] = 'META Author';
$lang['Meta_author_explain'] = 'Author of this website (email or name).';
$lang['Meta_owner'] = 'META Owner';
$lang['Meta_owner_explain'] = 'Owner of this website (email).';
$lang['Meta_distribution'] = 'META Distribution (Global | Local | Internal Use)';
$lang['Meta_distribution_explain'] = 'Defines the level or degree of distribution of your webpage';
$lang['Meta_robots'] = 'META Robots (ALL | NONE | NOINDEX | NOFOLLOW)';
$lang['Meta_robots_explain'] = 'To index your site or to follow or all';
$lang['Meta_abstract'] = 'META Abstract';
$lang['Meta_abstract_explain'] = 'The Abstract Tag defines a brief overview of your website (max 100 words)';

//
// IM Portal
//
$lang['BP_Title'] = 'Blocks Position Tag';
$lang['BP_Explain'] = 'From this control panel, you can add, edit or delete blocks position that can be used in IM Portal.  The default positions are \'header\', \'footer\', \'right\' and \'center\'.  These positions corresponds to the layout being used.  Only existing positions per layout must be added here. Position keys that are not existing in the specified layout will not appear in the portal page.  Each position tag key and character must be unique per layout.';
$lang['BP_Position'] = 'Position character';
$lang['BP_Key'] = 'Position Tag Key';
$lang['BP_Layout'] = 'Layout';
$lang['BP_Add_Position'] = 'Add New Position';
$lang['No_bp_selected'] = 'No position selected for editing';
$lang['BP_Edit_Position'] = 'Edit block position';
$lang['Must_enter_bp'] = 'You must enter a position tag key, position character and layout';
$lang['BP_updated'] = 'Block position updated';
$lang['BP_added'] = 'Block position added';
$lang['Click_return_bpadmin'] = 'Click %sHere%s to return to Blocks Position Administration';
$lang['BP_removed'] = 'Block position removed';
$lang['Portal_wide'] = 'Portal Wide';

$lang['No_layout_selected'] = 'No layout selected for editing';
$lang['Layout_Title'] = 'Layout';
$lang['Layout_Explain'] = 'Add, edit or delete layouts for your site. Each layout item corresponds to a module page, the Forums or the Profile Control Panel. You are not able to delete the default layout. Deleting a layout also deletes the corresponding block positions for that layout and all the blocks assigned to it.';
$lang['Layout_Name'] = 'Name';
$lang['Layout_Template'] = 'Template File';
$lang['Layout_Edit'] = 'Edit layout';
$lang['Layout_Page'] = 'Page ID';
$lang['Layout_View'] = 'View by';
$lang['Layout_Forum_wide'] = 'Forum-wide blocks?';
$lang['Must_enter_layout'] = 'You must enter a name and a template file';
$lang['Layout_updated'] = 'Layout Updated';
$lang['Click_return_layoutadmin'] = 'Click %sHere%s to return to Layout Administration';
$lang['Layout_added'] = 'Layout added';
$lang['Layout_removed'] = 'Layout removed';
$lang['Layout_Add'] = 'Add Layout';
$lang['Layout_BP_added'] = 'Layout Config file available: Block Position Tags automatically inserted';
$lang['Layout_default'] = 'Default';
$lang['Layout_make_default'] = 'Make Default';

$lang['Blocks_Title'] = 'Blocks Management';
$lang['Blocks_Explain'] = 'Add, edit, delete and move blocks for each of the layout you have created. There are 3 block types, block that display content based on the block code, blocks which display some BBCode you enter and blocks which display some HTML you enter.';
$lang['Choose_Layout'] = 'Choose layout';
$lang['B_Title'] = 'Block Title';
$lang['B_Position'] = 'Block Position';
$lang['B_Active'] = 'Active?';
$lang['B_Display'] = 'Content';
$lang['B_HTML'] = 'HTML';
$lang['B_BBCode'] = 'BBCode';
$lang['B_Type'] = 'Type';
$lang['B_Border'] = 'Show Border';
$lang['B_Titlebar'] = 'Show Titlebar';
$lang['B_Background'] = 'Show BG';
$lang['B_Local'] = 'Localize Titlebar';
$lang['B_Cache'] = 'Cache?';
$lang['B_Cachetime'] = 'Cache Duration';
$lang['B_Groups'] = 'Usergroups';
$lang['B_All'] = 'All';
$lang['B_Guests'] = 'Guests Only';
$lang['B_Reg'] = 'Registered Users';
$lang['B_Mod'] = 'Moderators';
$lang['B_Admin'] = 'Administrators';
$lang['B_None'] = 'None';
$lang['B_Layout'] = 'Layout';
$lang['B_Page'] = 'Page ID';
$lang['B_Add'] = 'Add Blocks';
$lang['Yes'] = 'Yes';
$lang['No'] = 'No';
$lang['B_Text'] = 'Text';
$lang['B_File'] = 'Block File';
$lang['B_Move_Up'] = 'Move Up';
$lang['B_Move_Down'] = 'Move Down';
$lang['B_View'] = 'View By';
$lang['No_blocks_selected'] = 'No block selected';
$lang['B_Content'] = 'Content';
$lang['B_Blockfile'] = 'Block File';
$lang['Block_Edit'] = 'Block Edit';
$lang['Block_updated'] = 'Block updated';
$lang['Must_enter_block'] = 'You must enter a block title';
$lang['Block_added'] = 'Block added';
$lang['Click_return_blocksadmin'] = 'Click %sHere%s to return to Blocks Management';
$lang['Block_removed'] = 'Block removed';
$lang['B_BV_added'] = 'Block Config file available: Block Variables automatically inserted';

$lang['BV_Title'] = 'Blocks Variables';
$lang['BV_Explain'] = 'Add, edit or delete blocks configuration variables that are used by blocks. These variables can then be configured through the Blocks Configuration page to personalize your site.';
$lang['BV_Label'] = 'Field Label';
$lang['BV_Sub_Label'] = 'Field Info';
$lang['BV_Name'] = 'Config Name';
$lang['BV_Options'] = 'Options';
$lang['BV_Values'] = 'Field Values';
$lang['BV_Type'] = 'Control Type';
$lang['BV_Block'] = 'Block';
$lang['BV_Add_Variable'] = 'Add Block Variable';
$lang['No_bv_selected'] = 'No block variable selected';
$lang['BV_Edit_Variable'] = 'Edit block variable';
$lang['Must_enter_bv'] = 'You must enter a field label and config name';
$lang['BV_updated'] = 'Block variable updated';
$lang['BV_added'] = 'Block variable added';
$lang['Click_return_bvadmin'] = 'Click %sHere%s to return to Blocks Variables Administration';
$lang['Config_Name_Explain'] = 'Must have no space';
$lang['Field_Options_Explain'] = 'Mandatory for dropdown lists and<br />radio buttons (comma delimited).';
$lang['Field_Values_Explain'] = 'Mandatory for dropdown lists and<br />radio buttons (comma delimited).';
$lang['BV_removed'] = 'Block variable removed';

$lang['Portal_Config'] = 'Blocks Configuration';
$lang['Portal_Explain'] = 'Setup the configuration needed for your blocks. Block variables listed in this page can be created/updated in Blocks Variables configuration page';
$lang['Portal_General_Config'] = 'General Configuration';
$lang['Default_Layout'] = 'Default Layout';
$lang['Default_Layout_Explain'] = 'This layout will be used by all modules and pages which have not had a custom layout defined.';
$lang['Confirm_delete_item'] = 'Are you sure you want to delete this item?';

$lang['bbcode_b_help'] = 'Bold text: [b]text[/b]  (alt+b)';
$lang['bbcode_i_help'] = 'Italic text: [i]text[/i]  (alt+i)';
$lang['bbcode_u_help'] = 'Underline text: [u]text[/u]  (alt+u)';
$lang['bbcode_q_help'] = 'Quote text: [quote]text[/quote]  (alt+q)';
$lang['bbcode_c_help'] = 'Code display: [code]code[/code]  (alt+c)';
$lang['bbcode_l_help'] = 'List: [list]text[/list] (alt+l)';
$lang['bbcode_o_help'] = 'Ordered list: [list=]text[/list]  (alt+o)';
$lang['bbcode_p_help'] = 'Insert image: [img]http://image_url[/img]  (alt+p)';
$lang['bbcode_w_help'] = 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]  (alt+w)';
$lang['bbcode_a_help'] = 'Close all open bbCode tags';
$lang['bbcode_s_help'] = 'Font color: [color=red]text[/color]  Tip: you can also use color=#FF0000';
$lang['bbcode_f_help'] = 'Font size: [size=x-small]small text[/size]';

$lang['Emoticons'] = 'Emoticons';
$lang['More_emoticons'] = 'View more Emoticons';

$lang['Font_color'] = 'Font colour';
$lang['color_default'] = 'Default';
$lang['color_dark_red'] = 'Dark Red';
$lang['color_red'] = 'Red';
$lang['color_orange'] = 'Orange';
$lang['color_brown'] = 'Brown';
$lang['color_yellow'] = 'Yellow';
$lang['color_green'] = 'Green';
$lang['color_olive'] = 'Olive';
$lang['color_cyan'] = 'Cyan';
$lang['color_blue'] = 'Blue';
$lang['color_dark_blue'] = 'Dark Blue';
$lang['color_indigo'] = 'Indigo';
$lang['color_violet'] = 'Violet';
$lang['color_white'] = 'White';
$lang['color_black'] = 'Black';

$lang['Font_size'] = 'Font size';
$lang['font_tiny'] = 'Tiny';
$lang['font_small'] = 'Small';
$lang['font_normal'] = 'Normal';
$lang['font_large'] = 'Large';
$lang['font_huge'] = 'Huge';

$lang['Close_Tags'] = 'Close Tags';
$lang['Styles_tip'] = 'Tip: Styles can be applied quickly to selected text.';

// Admin HTTP Referrers Mod
$lang['HTTP_Referers_Title'] = 'HTTP Referrers';
$lang['HTTP_Referers_Explain'] = 'Here you can see and delete the HTTP Referrers to the forum';
$lang['Referer_urls_show'] = 'Show URLs';
$lang['Referer_urls_hide'] = 'Hide URLs';
$lang['Referer_host'] = 'Referrer Host';
$lang['Referer_url'] = 'Referrer URL';
$lang['Referer_ip'] = 'Last IP referred';
$lang['Referer_hits'] = 'Hits';
$lang['referer_del_success'] = 'HTTP Referrers were successfully removed.';
$lang['Click_return_referersadmin'] = 'Click %sHere%s to return to HTTP Referrers Administration';
$lang['Referer_firstvisit'] = 'First visit';
$lang['Referer_lastvisit'] = 'Last visit';
$lang['Confirm_delete_referer'] = 'Are you sure you want to delete this HTTP Referrer?';
$lang['Confirm_delete_referers'] = 'Are you sure you want to delete all HTTP Referrers?';


// Modules
$lang['Module_administration'] = 'Module Administration';
$lang['Module_administration_explain'] = 'From this panel you can install, uninstall, update, enable and disable modules';
$lang['Module_id'] = 'id';
$lang['Module_module'] = 'Module';
$lang['Module_status'] = 'Status';
$lang['Module_action'] = 'Action';
$lang['Module_add_error'] = 'Could not add module information';
$lang['Module_update_error'] = 'Could not update module information';
$lang['Module_delete_error'] = 'Could not delete module information';
$lang['Module_not_found'] = 'Module "%s" not found';
$lang['Module_installed'] = 'Module "%s" installed';
$lang['Module_updated'] = 'Module "%s" updated';
$lang['Module_was_uninstalled'] = 'Module "%s" was uninstalled';
$lang['Module_already_installed'] = 'Module "%s" is already installed';
$lang['Module_incompatible'] = 'Module "%s" is incompatible';
$lang['Module_click_return_admin'] = 'Click %sHere%s to return to Module Administration';
$lang['Module_cleaned'] = 'Module "%s" was removed from the modules list.<br />As this module was not properly uninstalled, some module-related tables may be left orphaned.';
$lang['Module_short_not_installed'] = 'Not installed';
$lang['Module_short_install'] = 'Install module';
$lang['Module_short_active'] = 'Active';
$lang['Module_short_disable'] = 'Disable module';
$lang['Module_short_enable'] = 'Enable module';
$lang['Module_short_uninstall'] = 'Uninstall module';
$lang['Module_short_update'] = 'Update module';
$lang['Module_short_make_default'] = 'Make default';
$lang['Module_short_disabled'] = 'Disabled';
$lang['Module_short_needs_updating'] = 'Needs updating from %s';
$lang['Module_short_incorrect_removal'] = 'Incorrect removal';
$lang['Module_short_cleanup'] = 'Clean-up';
$lang['Module_short_default'] = 'Default';
$lang['Module_short_was_newer'] = 'Was newer (%s)';
$lang['Module_short_fix_manually'] = '[Fix manually]';

// Yellow Card
$lang['Ban'] = 'Ban';
$lang['Max_user_bancard'] = 'Maximum number of warnings';
$lang['Max_user_bancard_explain'] = 'If a user gets more yellow cards than this limit, the user will be banned';
$lang['ban_card'] = 'Yellow card';
$lang['ban_card_explain'] = 'The user will be banned when he/she is in excess of %d yellow cards';
$lang['Greencard'] = 'Un-ban';
$lang['Bluecard'] = 'Post report';
$lang['Bluecard_limit'] = 'Interval of bluecard';
$lang['Bluecard_limit_explain'] = 'Notify the moderators again for every x bluecards given to a post';
$lang['Bluecard_limit_2'] = 'Limit of bluecard';
$lang['Bluecard_limit_2_explain'] = 'First notification to moderators is sent, when a post get this amount of blue cards';
$lang['Report_forum']= 'Report forum';
$lang['Report_forum_explain'] = 'Fill with the forum ID where users reports are to be posted, a value of 0 will disable this feature, users MUST atleast have post/reply access to this forum';
//
// That's all Folks!
// -------------------------------------------------

?>