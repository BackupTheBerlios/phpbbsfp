<?php
//-- mod : profile cp ------------------------------------------------------------------------------
//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- mod : add-on country flags for pcp ------------------------------------------------------------
//-- mod : avanced privmsg -------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: constants.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : constants.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// Debug Level
define('DEBUG', 1); // Debugging on
//define('DEBUG', 0); // Debugging off

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);

define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);

// User related
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);

define('USER_AVATAR_NONE', 0);
define('USER_AVATAR_UPLOAD', 1);
define('USER_AVATAR_REMOTE', 2);
define('USER_AVATAR_GALLERY', 3);

// Group settings
define('GROUP_OPEN', 0);
define('GROUP_CLOSED', 1);
define('GROUP_HIDDEN', 2);
define('GROUP_AUTO', 3);

// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);

// Topic status
define('TOPIC_UNLOCKED', 0);
define('TOPIC_LOCKED', 1);
define('TOPIC_MOVED', 2);
define('TOPIC_WATCH_NOTIFIED', 1);
define('TOPIC_WATCH_UN_NOTIFIED', 0);

// Topic types
define('POST_NORMAL', 0);
define('POST_STICKY', 1);
define('POST_ANNOUNCE', 2);
define('POST_GLOBAL_ANNOUNCE', 3);

// SQL codes
define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);

// Error codes
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);

// URL PARAMETERS
define('POST_TOPIC_URL', 't');
define('POST_CAT_URL', 'c');
define('POST_FORUM_URL', 'f');
define('POST_USERS_URL', 'u');
define('POST_POST_URL', 'p');
define('POST_GROUPS_URL', 'g');

// Session parameters
define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);

// Page numbers for session handling
define('PAGE_INDEX', 0);
define('PAGE_LOGIN', -1);
define('PAGE_SEARCH', -2);
define('PAGE_REGISTER', -3);
define('PAGE_PROFILE', -4);
define('PAGE_VIEWONLINE', -6);
define('PAGE_VIEWMEMBERS', -7);
define('PAGE_FAQ', -8);
define('PAGE_POSTING', -9);
define('PAGE_PRIVMSGS', -10);
define('PAGE_GROUPCP', -11);
define('PAGE_FORUM', -12);
define('PAGE_RULES', -13);
define('PAGE_PRILLIAN', -14);
define('PAGE_CARD', -15);
define('PAGE_TOPIC_OFFSET', 5000);
define('PAGE_UACP', -32); // Attach Mod

// Start page id for modules
define('PAGE_BASE', -200);

// Auth settings
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);
define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_ADMIN', 5);

define('AUTH_VIEW', 1);
define('AUTH_READ', 2);
define('AUTH_POST', 3);
define('AUTH_REPLY', 4);
define('AUTH_EDIT', 5);
define('AUTH_DELETE', 6);
define('AUTH_ANNOUNCE', 7);
define('AUTH_STICKY', 8);
define('AUTH_POLLCREATE', 9);
define('AUTH_VOTE', 10);
define('AUTH_ATTACH', 11);
define('AUTH_DOWNLOAD', 20);
// Table names
define('ACRONYMS_TABLE', $table_prefix.'acronyms');
define('ADMIN_MODULE_TABLE', $table_prefix.'admin_nav_module');
define('ATTACH_CONFIG_TABLE', $table_prefix . 'attachments_config');
define('AUTH_ACCESS_TABLE', $table_prefix.'auth_access');
define('ATTACHMENTS_DESC_TABLE', $table_prefix . 'attachments_desc');
define('ATTACHMENTS_TABLE', $table_prefix . 'attachments');
define('BANLIST_TABLE', $table_prefix.'banlist');
define('BLOCKS_TABLE', $table_prefix.'blocks');
define('BLOCKS_POSITION_TABLE', $table_prefix.'block_position');
define('BLOCKS_LAYOUT_TABLE',$table_prefix.'block_layout');
define('BLOCKS_CONFIG_TABLE',$table_prefix.'block_config');
define('BLOCKS_VARIABLE_TABLE',$table_prefix.'block_variable');
define('BOARD_LINKS_TABLE', $table_prefix.'menu_links');
define('BUDDYS_TABLE', $table_prefix . 'buddy');
define('CATEGORIES_TABLE', $table_prefix.'categories');
define('CONFIG_TABLE', $table_prefix.'config');
define('DISALLOW_TABLE', $table_prefix.'disallow');
define('EXTENSION_GROUPS_TABLE', $table_prefix . 'extension_groups');
define('EXTENSIONS_TABLE', $table_prefix . 'extensions');
define('FLAG_TABLE', $table_prefix.'flags');
define('FORBIDDEN_EXTENSIONS_TABLE', $table_prefix . 'forbidden_extensions');
define('FORUMS_TABLE', $table_prefix.'forums');
define('GROUPS_TABLE', $table_prefix.'groups');
define('GROUPS_SUBSCRIPTIONS_TABLE', $table_prefix . 'groups_subscriptions');
define('IM_PREFS_TABLE', $table_prefix.'im_prefs');
define('IM_CONFIG_TABLE', $table_prefix.'im_config');
define('IM_SITES_TABLE', $table_prefix.'im_sites');
define('IM_SESSIONS_TABLE', $table_prefix.'im_sessions');
define('INSTMSGS_TABLE', $table_prefix.'instmsgs');
define('INSTMSGS_TEXT_TABLE', $table_prefix.'instmsgs_text');
define('JR_ADMIN_TABLE', $table_prefix.'jr_admin_users');
define('LOG_TABLE', $table_prefix.'log');
define('MODULES_TABLE', $table_prefix.'modules');
define('POSTS_TABLE', $table_prefix.'posts');
define('POSTS_TEXT_TABLE', $table_prefix.'posts_text');
define('PRIVMSGA_TABLE', $table_prefix . 'privmsga');
define('PRIVMSGA_RECIPS_TABLE', $table_prefix . 'privmsga_recips');
define('PRIVMSGA_FOLDERS_TABLE', $table_prefix . 'privmsga_folders');
define('PRIVMSGA_RULES_TABLE', $table_prefix . 'privmsga_rules');
define('PRUNE_TABLE', $table_prefix.'forum_prune');
define('QUOTA_TABLE', $table_prefix . 'attach_quota');
define('QUOTA_LIMITS_TABLE', $table_prefix . 'quota_limits');
define('RANKS_TABLE', $table_prefix.'ranks');
define('RATINGS_TABLE', $table_prefix.'rate_results');
define('REFERERS_TABLE', $table_prefix.'referers');
define('RULES_TABLE', $table_prefix.'rules');
define('SEARCH_TABLE', $table_prefix.'search_results');
define('SEARCH_WORD_TABLE', $table_prefix.'search_wordlist');
define('SEARCH_MATCH_TABLE', $table_prefix.'search_wordmatch');
define('SESSIONS_TABLE', $table_prefix.'sessions');
define('SMART_TABLE', $table_prefix.'smart');
define('SMILIES_TABLE', $table_prefix.'smilies');
define('THEMES_TABLE', $table_prefix.'themes');
define('THEMES_NAME_TABLE', $table_prefix.'themes_name');
define('TOPICS_TABLE', $table_prefix.'topics');
define('TOPICS_WATCH_TABLE', $table_prefix.'topics_watch');
define('TOPICS_VIEW_TABLE', $table_prefix.'topics_view');
define('USER_BOARD_LINKS_TABLE', $table_prefix.'user_menu_links');
define('USER_GROUP_TABLE', $table_prefix.'user_group');
define('USERS_TABLE', $table_prefix.'users');
define('WORDS_TABLE', $table_prefix.'words');
define('VOTE_DESC_TABLE', $table_prefix.'vote_desc');
define('VOTE_RESULTS_TABLE', $table_prefix.'vote_results');
define('VOTE_USERS_TABLE', $table_prefix.'vote_voters');

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
define('ADMIN_FOUNDER', 99);

define('NO', 0);
define('YES', 1);
define('FRIEND_ONLY',2);

define('UNKNOWN', 0);
define('MALE', 1);
define('FEMALE', 2);

//-- fin mod : profile cp --------------------------------------------------------------------------

//-- mod : add-on holidays for pcp -----------------------------------------------------------------
//-- add
define('NO_SPECIF', 2);
//-- fin mod : add-on holidays for pcp -------------------------------------------------------------

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//
// Set paths for including files later
define('PRILL_PATH', $phpbb_root_path . 'prillian/');
define('PRILL_URL', $phpbb_root_path . 'imclient.' . $phpEx);

// Network Constants - do not change these if you want Network
// Messaging to work.
define('OFF_SITE', -2); // Off-Site User level
define('OFF_SITE_USERS_URL', 'u');
define('OFF_SITE_POST_URL', 'p');

// Instant Message flags
// These cannot be the same as any of the private message flags in constants.php
// unless you're trying to get IMs & PMs in the pm inbox

// *** Old *** phpBB Private messaging - Still here for reference
// Will be useful for removing some more redundant code from Prillian
define('INSTMSGS_READ_MAIL', 0);
define('INSTMSGS_NEW_MAIL', 1);
define('INSTMSGS_SENT_MAIL', 2);
define('INSTMSGS_SAVED_IN_MAIL', 3);
define('INSTMSGS_SAVED_OUT_MAIL', 4);
define('INSTMSGS_UNREAD_MAIL', 5);

define('IM_NEW_MAIL', 6);
define('IM_READ_MAIL', 7);
define('IM_UNREAD_MAIL', 8);

// Prillian Mode Flags
define('MAIN_MODE', 1);
define('WIDE_MODE', 2);
define('MINI_MODE', 3);
define('FRAMES_MODE', 4);
define('NO_FRAMES_MODE', 5);

//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------

// Custom Title MOD
define('CUSTOM_TITLE_REGDATE', 0);
define('CUSTOM_TITLE_DISABLED', 1);
define('CUSTOM_TITLE_ENABLED', 2);
define('CUSTOM_TITLE_MODE_INDEPENDENT', 0);
define('CUSTOM_TITLE_MODE_REPLACE_RANK', 1);
define('CUSTOM_TITLE_MODE_REPLACE_BOTH', 2);

//-- mod : avanced privmsg -------------------------------------------------------------------------
//-- add
// groups
define('FRIEND_LIST_GROUP', -99);

// main folders id
define('INBOX', 1);
define('OUTBOX', 2);
define('SENTBOX', 3);
define('SAVEBOX', 4);

// pm status
define('STS_TRANSIT', 1);
define('STS_SAVED', 4 );
define('STS_DELETED', 9 );

// read status
define('NEW_MAIL', 1);
define('UNREAD_MAIL', 2);
define('READ_MAIL', 3);
//-- fin mod : avanced privmsg ---------------------------------------------------------------------

// LDAP
define('User_Type_Both',0);
define('User_Type_phpBB',1);
define('User_Type_LDAP',2);

// Rules
define ('COPY_ID', -5);
define ('PRIVACY_ID', -4);
define ('TERMS_ID', -3);
define ('SITE_ID', -2);

// Attach Stuff
define('ATTACH_VERSION', '2.3.9');
define('ATTACH_DEBUG', 0);
// Download Modes
define('INLINE_LINK', 1);
define('PHYSICAL_LINK', 2);
// Categories
define('NONE_CAT', 0);
define('IMAGE_CAT', 1);
define('STREAM_CAT', 2);
define('SWF_CAT', 3);
// Misc.
define('MEGABYTE', 1024);
define('ADMIN_MAX_ATTACHMENTS', 50); // Maximum Attachments in Posts or PM's for Admin Users
define('THUMB_DIR', 'thumbs');
define('MODE_THUMBNAIL', 1);
// Forum Extension Group Permissions
define('GPERM_ALL', 0); // ALL FORUMS
// Quota Types
define('QUOTA_UPLOAD_LIMIT', 1);
define('QUOTA_PM_LIMIT', 2);

// Logs
define('LOG_ADMIN', 0);
define('LOG_MOD', 1);
define('LOG_CRITICAL', 2);
define('LOG_USERS', 3);

// Core layouts
define('LAYOUT_DEFAULT', 0);
define('LAYOUT_PROFILE', 1);
define('LAYOUT_FAQ', 2);
define('LAYOUT_RULES', 3);
define('LAYOUT_USERGROUP', 4);
define('LAYOUT_VIEWONLINE', 5);
define('LAYOUT_CASH', 6);
define('LAYOUT_FORUM', 7);

$core_layout = array();
$core_layout[LAYOUT_DEFAULT]    = 'Default';
$core_layout[LAYOUT_PROFILE]    = 'Profile';
$core_layout[LAYOUT_FAQ]        = 'FAQ';
$core_layout[LAYOUT_RULES]      = 'Rules';
$core_layout[LAYOUT_USERGROUP]  = 'User Group';
$core_layout[LAYOUT_VIEWONLINE] = 'View Online';
$core_layout[LAYOUT_CASH]       = 'Cash';
$core_layout[LAYOUT_FORUM] 		= 'Forum';

?>