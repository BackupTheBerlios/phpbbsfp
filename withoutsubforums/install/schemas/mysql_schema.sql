#
# phpBB2 - MySQL schema
#
# $Id: mysql_schema.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
#

CREATE TABLE phpbb_admin_nav_module (
  user_id mediumint(8) NOT NULL default '0',
  modulname varchar(200) NOT NULL default ''
);

#
# Table structure for table 'phpbb_acronyms'
#
CREATE TABLE phpbb_acronyms (
    acronym_id MEDIUMINT NOT NULL AUTO_INCREMENT,
    acronym VARCHAR( 80 ) NOT NULL ,
    description VARCHAR( 255 ) NOT NULL ,
    PRIMARY KEY ( acronym_id )
);

#
# Table structure for table 'phpbb_smart'
#
CREATE TABLE phpbb_smart (
    smart_id MEDIUMINT NOT NULL AUTO_INCREMENT,
    smart VARCHAR( 80 ) NOT NULL ,
    url VARCHAR( 255 ) NOT NULL ,
    PRIMARY KEY ( smart_id )
);

#
# Table structure for table 'phpbb_auth_access'
#
CREATE TABLE phpbb_auth_access (
   group_id mediumint(8) DEFAULT '0' NOT NULL,
   forum_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   auth_view tinyint(1) DEFAULT '0' NOT NULL,
   auth_read tinyint(1) DEFAULT '0' NOT NULL,
   auth_post tinyint(1) DEFAULT '0' NOT NULL,
   auth_reply tinyint(1) DEFAULT '0' NOT NULL,
   auth_edit tinyint(1) DEFAULT '0' NOT NULL,
   auth_delete tinyint(1) DEFAULT '0' NOT NULL,
   auth_sticky tinyint(1) DEFAULT '0' NOT NULL,
   auth_announce tinyint(1) DEFAULT '0' NOT NULL,
   auth_global_announce TINYINT(1) NOT NULL,
   auth_vote tinyint(1) DEFAULT '0' NOT NULL,
   auth_pollcreate tinyint(1) DEFAULT '0' NOT NULL,
   auth_attachments tinyint(1) DEFAULT '0' NOT NULL,
   auth_mod tinyint(1) DEFAULT '0' NOT NULL,
   auth_download tinyint(1) DEFAULT '0' NOT NULL,
   auth_ban TINYINT (1) not null DEFAULT "0",
   auth_greencard TINYINT (1) not null DEFAULT "0",
   auth_bluecard TINYINT (1) not null DEFAULT "0",
   KEY group_id (group_id),
   KEY forum_id (forum_id)
);

#
# Table structure for table 'phpbb_user_group'
#
CREATE TABLE phpbb_user_group (
   group_id mediumint(8) DEFAULT '0' NOT NULL,
   user_id mediumint(8) DEFAULT '0' NOT NULL,
   user_pending tinyint(1),
   group_moderator TINYINT(1) NOT NULL,
   KEY group_id (group_id),
   KEY user_id (user_id)
);



#
# Table structure for table 'phpbb_groups'
#
CREATE TABLE phpbb_groups (
   group_id mediumint(8) NOT NULL auto_increment,
   group_type tinyint(4) DEFAULT '1' NOT NULL,
   group_name varchar(40) NOT NULL,
   group_description varchar(255) NOT NULL,
   group_priority MEDIUMINT(8) DEFAULT '-1' NOT NULL,
   group_moderator mediumint(8) DEFAULT '0' NOT NULL,
   group_single_user tinyint(1) DEFAULT '1' NOT NULL,
   group_ldap_update smallint NULL,
   group_count INT (4) UNSIGNED DEFAULT "99999999",
   group_count_max INT (4) UNSIGNED DEFAULT "99999999",
   group_count_enable SMALLINT (2) UNSIGNED DEFAULT "0",
   subscription_id MEDIUMINT( 8 ) DEFAULT '0' NOT NULL,
   PRIMARY KEY (group_id),
   KEY group_single_user (group_single_user)
);

#
# Table structure for table 'phpbb_groups_subscriptions_log'
#
CREATE TABLE phpbb_groups_subscriptions_log (
  groups_transaction_id int(11) NOT NULL auto_increment,
  groups_purchase_date varchar(20) NOT NULL default '',
  groups_paid varchar(20) NOT NULL default '',
  groups_id_number varchar(20) NOT NULL default '',
  groups_user_ip varchar(20) NOT NULL default '',
  groups_phpbb_id varchar(20) NOT NULL default '',
  groups_phpbb_username varchar(200) NOT NULL default '',
  groups_gross_amount varchar(200) NOT NULL default '',
  groups_action varchar(200) NOT NULL default '',
  PRIMARY KEY  (groups_transaction_id)
);

#
# Table structure for table 'phpbb_groups_subscriptions'
#
CREATE TABLE phpbb_groups_subscriptions (
	subscription_id MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
	subscription_enabled TINYINT( 1 ) DEFAULT '1' NOT NULL ,
	subscription_length SMALLINT( 4 ) NOT NULL ,
	subscription_unit CHAR( 1 ) NOT NULL,
	subscription_cost DECIMAL( 11, 2 ) NOT NULL ,
	subscription_currency VARCHAR( 3 ) NOT NULL ,
	paypal_email VARCHAR( 255 ) NOT NULL ,
	paypal_bgcolor VARCHAR( 1 ) DEFAULT 'W' NOT NULL ,
	PRIMARY KEY ( subscription_id ) ,
	INDEX ( subscription_enabled )
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_banlist'
#
CREATE TABLE phpbb_banlist (
   ban_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   ban_userid mediumint(8) NOT NULL,
   ban_ip char(8) NOT NULL,
   ban_email varchar(255),
   PRIMARY KEY (ban_id),
   KEY ban_ip_user_id (ban_ip, ban_userid)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_categories'
#
CREATE TABLE phpbb_categories (
   cat_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   cat_title varchar(100),
   cat_order mediumint(8) UNSIGNED NOT NULL,
   cat_main_type CHAR(1),
   cat_main MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
   cat_desc TEXT NOT NULL,
   icon VARCHAR(255),
   PRIMARY KEY (cat_id),
   KEY cat_order (cat_order)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_config'
#
CREATE TABLE phpbb_config (
    config_name varchar(255) NOT NULL,
    config_value varchar(255) NOT NULL,
    is_dynamic tinyint(1) DEFAULT '0' NOT NULL,
    PRIMARY KEY (config_name),
    KEY is_dynamic (is_dynamic)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_confirm'
#
CREATE TABLE phpbb_confirm (
  confirm_id char(32) DEFAULT '' NOT NULL,
  session_id char(32) DEFAULT '' NOT NULL,
  code char(6) DEFAULT '' NOT NULL,
  PRIMARY KEY  (session_id,confirm_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_disallow'
#
CREATE TABLE phpbb_disallow (
   disallow_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   disallow_username varchar(25) DEFAULT '' NOT NULL,
   PRIMARY KEY (disallow_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forum_prune'
#
CREATE TABLE phpbb_forum_prune (
   prune_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   forum_id smallint(5) UNSIGNED NOT NULL,
   prune_days smallint(5) UNSIGNED NOT NULL,
   prune_freq smallint(5) UNSIGNED NOT NULL,
   PRIMARY KEY(prune_id),
   KEY forum_id (forum_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_forums'
#
CREATE TABLE phpbb_forums (
   forum_id smallint(5) UNSIGNED NOT NULL,
   cat_id mediumint(8) UNSIGNED NOT NULL,
   forum_name varchar(150),
   forum_desc text,
   forum_status tinyint(4) DEFAULT '0' NOT NULL,
   forum_order mediumint(8) UNSIGNED DEFAULT '1' NOT NULL,
   forum_posts mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_topics mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_last_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   prune_next int(11),
   prune_enable tinyint(1) DEFAULT '0' NOT NULL,
   auth_view tinyint(2) DEFAULT '0' NOT NULL,
   auth_read tinyint(2) DEFAULT '0' NOT NULL,
   auth_post tinyint(2) DEFAULT '0' NOT NULL,
   auth_reply tinyint(2) DEFAULT '0' NOT NULL,
   auth_edit tinyint(2) DEFAULT '0' NOT NULL,
   auth_delete tinyint(2) DEFAULT '0' NOT NULL,
   auth_sticky tinyint(2) DEFAULT '0' NOT NULL,
   auth_announce tinyint(2) DEFAULT '0' NOT NULL,
   auth_global_announce TINYINT(2) DEFAULT '5' NOT NULL,
   auth_vote tinyint(2) DEFAULT '0' NOT NULL,
   auth_pollcreate tinyint(2) DEFAULT '0' NOT NULL,
   auth_attachments tinyint(2) DEFAULT '0' NOT NULL,
   auth_ban tinyint(2) DEFAULT '3' NOT NULL,
   auth_greencard tinyint(2) DEFAULT '5' NOT NULL,
   auth_bluecard tinyint(2) DEFAULT '1' NOT NULL,
   auth_download tinyint(2) DEFAULT '0' NOT NULL,
   forum_link VARCHAR(255),
   forum_link_internal TINYINT(1) NOT NULL,
   forum_link_hit_count TINYINT(1) NOT NULL,
   forum_link_hit BIGINT(20) UNSIGNED NOT NULL,
   icon VARCHAR(255),
   main_type CHAR(1),
   forum_display_sort TINYINT(1) NOT NULL,
   forum_display_order TINYINT(1) NOT NULL,
   PRIMARY KEY (forum_id),
   KEY forums_order (forum_order),
   KEY cat_id (cat_id),
   KEY forum_last_post_id (forum_last_post_id)
);

ALTER TABLE phpbb_forums ADD auth_rate TINYINT(2) NOT NULL default -1;

CREATE TABLE phpbb_rate_results (
  rating_id mediumint(8) unsigned NOT NULL auto_increment,
  user_id mediumint(8) unsigned NOT NULL default '0',
  topic_id mediumint(8) unsigned NOT NULL default '0',
  rating mediumint(8) unsigned NOT NULL default '0',
  user_ip char(8) NOT NULL default '',
  rating_time int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (rating_id),
  KEY topic_id (topic_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_jr_admin_users'
#
CREATE TABLE phpbb_jr_admin_users (
  user_id mediumint(9) NOT NULL default '0',
  user_jr_admin longtext NOT NULL,
  start_date int(10) unsigned NOT NULL default '0',
  update_date int(10) unsigned NOT NULL default '0',
  admin_notes text NOT NULL,
  notes_view tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (user_id)
);

CREATE TABLE phpbb_log (
  log_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL auto_increment,
  log_type tinyint(4) UNSIGNED DEFAULT '0' NOT NULL,
  user_id mediumint(8) DEFAULT '0' NOT NULL,
  forum_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  reportee_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL, 
  log_ip varchar(8) NOT NULL,
  log_time int(11) NOT NULL,
  log_operation text,
  log_data text,
  PRIMARY KEY (log_id),
  KEY log_type (log_type),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY reportee_id (reportee_id), 
  KEY user_id (user_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts'
#
CREATE TABLE phpbb_posts (
   post_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   forum_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   poster_id mediumint(8) DEFAULT '0' NOT NULL,
   post_time int(11) DEFAULT '0' NOT NULL,
   poster_ip char(8) NOT NULL,
   post_username varchar(25),
   enable_bbcode tinyint(1) DEFAULT '1' NOT NULL,
   enable_html tinyint(1) DEFAULT '0' NOT NULL,
   enable_smilies tinyint(1) DEFAULT '1' NOT NULL,
   enable_sig tinyint(1) DEFAULT '1' NOT NULL,
   post_edit_time int(11),
   post_edit_count smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   post_attachment TINYINT(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (post_id),
   KEY forum_id (forum_id),
   KEY topic_id (topic_id),
   KEY poster_id (poster_id),
   post_icon TINYINT(2),
   post_bluecard TINYINT (1),
   INDEX (post_icon),
   KEY post_time (post_time)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_posts_text'
#
CREATE TABLE phpbb_posts_text (
   post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   bbcode_uid char(10) NOT NULL,
   post_subject char(96),
   post_text text,
   PRIMARY KEY (post_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_instmsgs'
#
CREATE TABLE phpbb_instmsgs (
   instmsgs_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   instmsgs_type tinyint(4) DEFAULT '0' NOT NULL,
   instmsgs_subject varchar(255) DEFAULT '0' NOT NULL,
   instmsgs_from_userid mediumint(8) DEFAULT '0' NOT NULL,
   instmsgs_to_userid mediumint(8) DEFAULT '0' NOT NULL,
   instmsgs_date int(11) DEFAULT '0' NOT NULL,
   instmsgs_ip char(8) NOT NULL,
   instmsgs_enable_bbcode tinyint(1) DEFAULT '1' NOT NULL,
   instmsgs_enable_html tinyint(1) DEFAULT '0' NOT NULL,
   instmsgs_enable_smilies tinyint(1) DEFAULT '1' NOT NULL,
   instmsgs_attach_sig tinyint(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (instmsgs_id),
   KEY instmsgs_from_userid (instmsgs_from_userid),
   instmsgs_from_username varchar(25) DEFAULT '' NOT NULL,
   instmsgs_to_username varchar(25) DEFAULT '' NOT NULL,
   site_id mediumint(8) DEFAULT '0' NOT NULL,
   INDEX site_id (site_id),
   KEY instmsgs_to_userid (instmsgs_to_userid)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_instmsgs_text'
#
CREATE TABLE phpbb_instmsgs_text (
   instmsgs_text_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   instmsgs_bbcode_uid char(10) DEFAULT '0' NOT NULL,
   instmsgs_text text,
   PRIMARY KEY (instmsgs_text_id)
);

CREATE TABLE phpbb_privmsga (
  privmsg_id mediumint(8) unsigned NOT NULL auto_increment,
  privmsg_subject varchar(255) default NULL,
  privmsg_text text,
  privmsg_bbcode_uid varchar(10) default NULL,
  privmsg_time int(11) NOT NULL default '0',
  privmsg_enable_bbcode tinyint(1) NOT NULL default '0',
  privmsg_enable_html tinyint(1) NOT NULL default '0',
  privmsg_enable_smilies tinyint(1) NOT NULL default '0',
  privmsg_attach_sig tinyint(1) NOT NULL default '0',
  privmsg_icon tinyint(2) default NULL,
  privmsg_attachment TINYINT(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY  (privmsg_id)
);

CREATE TABLE phpbb_privmsga_folders (
  folder_id mediumint(8) unsigned NOT NULL auto_increment,
  folder_main tinyint(2) NOT NULL default '0',
  folder_user_id mediumint(8) unsigned NOT NULL default '0',
  folder_name varchar(255) NOT NULL default '',
  folder_order mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (folder_id)
);

CREATE TABLE phpbb_privmsga_recips (
  privmsg_recip_id mediumint(8) unsigned NOT NULL auto_increment,
  privmsg_id mediumint(8) unsigned NOT NULL default '0',
  privmsg_direct tinyint(1) unsigned NOT NULL default '0',
  privmsg_user_id mediumint(8) unsigned NOT NULL default '0',
  privmsg_ip varchar(8) default NULL,
  privmsg_folder_id mediumint(8) unsigned NOT NULL default '0',
  privmsg_read tinyint(1) unsigned NOT NULL default '0',
  privmsg_status tinyint(1) unsigned NOT NULL default '0',
  privmsg_distrib tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (privmsg_recip_id),
  KEY privmsg_id (privmsg_id)
);

CREATE TABLE phpbb_privmsga_rules (
  rules_id mediumint(8) unsigned NOT NULL auto_increment,
  rules_user_id mediumint(8) NOT NULL default '0',
  rules_folder_id smallint(8) unsigned NOT NULL default '0',
  rules_name varchar(255) NOT NULL default '',
  rules_group_id mediumint(8) NOT NULL default '0',
  rules_word varchar(255) default NULL,
  KEY rules_id (rules_id)
);

CREATE TABLE phpbb_referers (
    referer_id mediumint(8) UNSIGNED NOT NULL auto_increment,
    referer_host varchar(255) NOT NULL default '',
    referer_url varchar(255) NOT NULL default '',
    referer_ip varchar(8) NOT NULL default '',
    referer_hits int(10) NOT NULL default '1',
    referer_firstvisit int(11) DEFAULT '0' NOT NULL,
    referer_lastvisit int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY (referer_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_ranks'
#
CREATE TABLE phpbb_ranks (
   rank_id smallint(5) UNSIGNED NOT NULL auto_increment,
   rank_title varchar(255) NOT NULL,
   rank_min mediumint(8) DEFAULT '0' NOT NULL,
   rank_max mediumint(8) NOT NULL,
   rank_special tinyint(1) DEFAULT '0',
   rank_image varchar(255),
   PRIMARY KEY (rank_id)
);

CREATE TABLE phpbb_rules (
  rule_id mediumint(8) NOT NULL default '0',
  rule_text text NOT NULL,
  rule_date int(11) NOT NULL default '0',
  in_force tinyint(1) NOT NULL default '1',
  PRIMARY KEY  ( rule_id )
);

# --------------------------------------------------------
#
# Table structure for table `phpbb_search_results`
#
CREATE TABLE phpbb_search_results (
  search_id int(11) UNSIGNED NOT NULL default '0',
  session_id char(32) NOT NULL default '',
  search_array text NOT NULL,
  PRIMARY KEY  (search_id),
  KEY session_id (session_id)
);


# --------------------------------------------------------
#
# Table structure for table `phpbb_search_wordlist`
#
CREATE TABLE phpbb_search_wordlist (
  word_text varchar(50) binary NOT NULL default '',
  word_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  word_common tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (word_text),
  KEY word_id (word_id)
);

# --------------------------------------------------------
#
# Table structure for table `phpbb_search_wordmatch`
#
CREATE TABLE phpbb_search_wordmatch (
  post_id mediumint(8) UNSIGNED NOT NULL default '0',
  word_id mediumint(8) UNSIGNED NOT NULL default '0',
  title_match tinyint(1) NOT NULL default '0',
  KEY post_id (post_id),
  KEY word_id (word_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_sessions'
#
# Note that if you're running 3.23.x you may want to make
# this table a type HEAP. This type of table is stored
# within system memory and therefore for big busy boards
# is likely to be noticeably faster than continually
# writing to disk ...
#
CREATE TABLE phpbb_sessions (
   session_id char(32) DEFAULT '' NOT NULL,
   session_user_id mediumint(8) DEFAULT '0' NOT NULL,
   session_start int(11) DEFAULT '0' NOT NULL,
   session_time int(11) DEFAULT '0' NOT NULL,
   session_ip char(8) DEFAULT '0' NOT NULL,
   session_page int(11) DEFAULT '0' NOT NULL,
   session_logged_in tinyint(1) DEFAULT '0' NOT NULL,
   session_robot VARCHAR(32),
   PRIMARY KEY (session_id),
   KEY session_user_id (session_user_id),
   KEY session_id_ip_user_id (session_id, session_ip, session_user_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_smilies'
#
CREATE TABLE phpbb_smilies (
   smilies_id smallint(5) UNSIGNED NOT NULL auto_increment,
   code varchar(50),
   smile_url varchar(100),
   emoticon varchar(75),
   PRIMARY KEY (smilies_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_themes'
#
CREATE TABLE phpbb_themes (
   themes_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   template_name varchar(30) NOT NULL default '',
   style_name varchar(30) NOT NULL default '',
   head_stylesheet varchar(100) default NULL,
   body_background varchar(100) default NULL,
   body_bgcolor varchar(6) default NULL,
   body_text varchar(6) default NULL,
   body_link varchar(6) default NULL,
   body_vlink varchar(6) default NULL,
   body_alink varchar(6) default NULL,
   body_hlink varchar(6) default NULL,
   tr_color1 varchar(6) default NULL,
   tr_color2 varchar(6) default NULL,
   tr_color3 varchar(6) default NULL,
   tr_class1 varchar(25) default NULL,
   tr_class2 varchar(25) default NULL,
   tr_class3 varchar(25) default NULL,
   th_color1 varchar(6) default NULL,
   th_color2 varchar(6) default NULL,
   th_color3 varchar(6) default NULL,
   th_class1 varchar(25) default NULL,
   th_class2 varchar(25) default NULL,
   th_class3 varchar(25) default NULL,
   td_color1 varchar(6) default NULL,
   td_color2 varchar(6) default NULL,
   td_color3 varchar(6) default NULL,
   td_class1 varchar(25) default NULL,
   td_class2 varchar(25) default NULL,
   td_class3 varchar(25) default NULL,
   fontface1 varchar(50) default NULL,
   fontface2 varchar(50) default NULL,
   fontface3 varchar(50) default NULL,
   fontsize1 tinyint(4) default NULL,
   fontsize2 tinyint(4) default NULL,
   fontsize3 tinyint(4) default NULL,
   fontcolor1 varchar(6) default NULL,
   fontcolor2 varchar(6) default NULL,
   fontcolor3 varchar(6) default NULL,
   span_class1 varchar(25) default NULL,
   span_class2 varchar(25) default NULL,
   span_class3 varchar(25) default NULL,
   img_size_poll smallint(5) UNSIGNED,
   img_size_privmsg smallint(5) UNSIGNED,
   PRIMARY KEY  (themes_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_themes_name'
#
CREATE TABLE phpbb_themes_name (
   themes_id smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   tr_color1_name char(50),
   tr_color2_name char(50),
   tr_color3_name char(50),
   tr_class1_name char(50),
   tr_class2_name char(50),
   tr_class3_name char(50),
   th_color1_name char(50),
   th_color2_name char(50),
   th_color3_name char(50),
   th_class1_name char(50),
   th_class2_name char(50),
   th_class3_name char(50),
   td_color1_name char(50),
   td_color2_name char(50),
   td_color3_name char(50),
   td_class1_name char(50),
   td_class2_name char(50),
   td_class3_name char(50),
   fontface1_name char(50),
   fontface2_name char(50),
   fontface3_name char(50),
   fontsize1_name char(50),
   fontsize2_name char(50),
   fontsize3_name char(50),
   fontcolor1_name char(50),
   fontcolor2_name char(50),
   fontcolor3_name char(50),
   span_class1_name char(50),
   span_class2_name char(50),
   span_class3_name char(50),
   PRIMARY KEY (themes_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_topics'
#
CREATE TABLE phpbb_topics (
   topic_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   forum_id smallint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_title char(60) NOT NULL,
   topic_poster mediumint(8) DEFAULT '0' NOT NULL,
   topic_time int(11) DEFAULT '0' NOT NULL,
   topic_views mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_replies mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_status tinyint(3) DEFAULT '0' NOT NULL,
   topic_vote tinyint(1) DEFAULT '0' NOT NULL,
   topic_type tinyint(3) DEFAULT '0' NOT NULL,
   topic_first_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_last_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_moved_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   topic_announce_duration MEDIUMINT(5) NOT NULL,
   topic_icon TINYINT(2),
   topic_attachment TINYINT(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (topic_id),
   KEY forum_id (forum_id),
   KEY topic_moved_id (topic_moved_id),
   KEY topic_status (topic_status),
   KEY topic_type (topic_type)
);

ALTER TABLE phpbb_topics ADD topic_desc varchar(255) DEFAULT '' AFTER topic_title;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_topics_watch'
#
CREATE TABLE phpbb_topics_watch (
  topic_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  user_id mediumint(8) NOT NULL DEFAULT '0',
  notify_status tinyint(1) NOT NULL default '0',
  KEY topic_id (topic_id),
  KEY user_id (user_id),
  KEY notify_status (notify_status)
);

CREATE TABLE phpbb_topics_view(
	topic_id mediumint(8) NOT NULL,
	user_id mediumint(8) NOT NULL,
	view_time int(11) NOT NULL,
	view_count int(11) NOT NULL,
  	KEY topic_id (topic_id),
  	KEY user_id (user_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_users'
#
CREATE TABLE phpbb_users (
   user_id mediumint(8) NOT NULL,
   user_active tinyint(1) DEFAULT '1',
   username varchar(25) NOT NULL,
   user_password varchar(32) NOT NULL,
   user_session_time int(11) DEFAULT '0' NOT NULL,
   user_session_page smallint(5) DEFAULT '0' NOT NULL,
   user_lastvisit int(11) DEFAULT '0' NOT NULL,
   user_regdate int(11) DEFAULT '0' NOT NULL,
   user_level tinyint(4) DEFAULT '0',
   user_posts mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
   user_timezone decimal(5,2) DEFAULT '0' NOT NULL,
   user_style tinyint(4),
   user_lang varchar(255),
   user_dateformat varchar(14) DEFAULT 'd M Y H:i' NOT NULL,
   user_new_privmsg smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   user_unread_privmsg smallint(5) UNSIGNED DEFAULT '0' NOT NULL,
   user_last_privmsg int(11) DEFAULT '0' NOT NULL,
   user_emailtime int(11),
   user_viewemail tinyint(1),
   user_attachsig tinyint(1),
   user_allowhtml tinyint(1) DEFAULT '1',
   user_allowbbcode tinyint(1) DEFAULT '1',
   user_allowsmile tinyint(1) DEFAULT '1',
   user_allowavatar tinyint(1) DEFAULT '1' NOT NULL,
   user_allow_pm tinyint(1) DEFAULT '1' NOT NULL,
   user_allow_viewonline tinyint(1) DEFAULT '1' NOT NULL,
   user_notify tinyint(1) DEFAULT '1' NOT NULL,
   user_notify_pm tinyint(1) DEFAULT '0' NOT NULL,
   user_popup_pm tinyint(1) DEFAULT '0' NOT NULL,
   user_rank int(11) DEFAULT '0',
   user_avatar varchar(100),
   user_avatar_type tinyint(4) DEFAULT '0' NOT NULL,
   user_email varchar(255),
   user_icq varchar(15),
   user_website varchar(100),
   user_from varchar(100),
   user_sig text,
   user_sig_bbcode_uid char(10),
   user_aim varchar(255),
   user_yim varchar(255),
   user_msnm varchar(255),
   user_occ varchar(100),
   user_interests varchar(255),
   user_actkey varchar(32),
   user_newpasswd varchar(32),
   user_sub_forum TINYINT(1) DEFAULT '1' NOT NULL,
   user_split_cat TINYINT(1) DEFAULT '1' NOT NULL,
   user_last_topic_title TINYINT(1) DEFAULT '1' NOT NULL,
   user_sub_level_links TINYINT(1) DEFAULT '2' NOT NULL,
   user_display_viewonline TINYINT(1) DEFAULT '2' NOT NULL,
   user_layout TINYINT( 4 ) DEFAULT NULL ,
   user_split_global_announce TINYINT(1) DEFAULT '1' NOT NULL,
   user_split_announce TINYINT(1) DEFAULT '1' NOT NULL,
   user_split_sticky TINYINT(1) DEFAULT '1' NOT NULL,
   user_split_topic_split TINYINT(1) DEFAULT '0' NOT NULL,
   user_announcement_date_display TINYINT(1) DEFAULT '1' NOT NULL,
   user_announcement_display TINYINT(1) DEFAULT '1' NOT NULL,
   user_announcement_display_forum TINYINT(1) DEFAULT '1' NOT NULL,
   user_announcement_split TINYINT(1) DEFAULT '1' NOT NULL,
   user_announcement_forum TINYINT(1) DEFAULT '1' NOT NULL,
   user_unread_topics TEXT,
   user_realname VARCHAR(25) NOT NULL,
   user_gender TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
   user_birthday VARCHAR(8) DEFAULT '0' NOT NULL,
   user_last_birthday INT(11) UNSIGNED DEFAULT '0' NOT NULL,
   user_home_phone VARCHAR(20),
   user_home_fax VARCHAR(20),
   user_work_phone VARCHAR(20),
   user_work_fax VARCHAR(20),
   user_cellular VARCHAR(20),
   user_pager VARCHAR(20),
   user_summer_time TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
   user_list_option VARCHAR(255),
   user_allow_email TINYINT(1) DEFAULT '1' NOT NULL,
   user_allow_website TINYINT(1) DEFAULT '1' NOT NULL,
   user_allow_messanger TINYINT(1) DEFAULT '1' NOT NULL,
   user_allow_real TINYINT(1) DEFAULT '1' NOT NULL,
   user_allow_sig TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewpm TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewwebsite TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewmessanger TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewreal TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewavatar TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewsig TINYINT(1) DEFAULT '1' NOT NULL,
   user_viewimg TINYINT(1) DEFAULT '1' NOT NULL,
   user_buddy_friend_display TINYINT(1) DEFAULT '1',
   user_buddy_ignore_display TINYINT(1) DEFAULT '1',
   user_buddy_friend_of_display TINYINT(1) DEFAULT '1',
   user_buddy_ignored_by_display TINYINT(1) DEFAULT '1',
   user_watched_topics_per_page SMALLINT(3) DEFAULT '15',
   user_privmsgs_per_page SMALLINT(3) DEFAULT '5',
   user_cash decimal(11,2) NOT NULL default '0.00',
   user_holidays TINYINT(1) UNSIGNED DEFAULT '2' NOT NULL,
   user_flag varchar(25) NULL,
   user_rules INT( 11 ) NOT NULL ,
   user_inactive_emls TINYINT( 1 ) NOT NULL ,
   user_inactive_last_eml INT( 11 ) NOT NULL ,
   user_custom_title varchar(255) NULL,
   user_custom_title_status tinyint(1) default '0' NOT NULL,
   user_type tinyint NOT NULL DEFAULT 0,
   user_max_inbox_privmsgs SMALLINT(5) DEFAULT '50' NOT NULL,
   user_max_sentbox_privmsgs SMALLINT(5) DEFAULT '50' NOT NULL,
   user_max_savebox_privmsgs SMALLINT(5) DEFAULT '150' NOT NULL,
   user_apm_save_to_mail TINYINT(1) DEFAULT '1' NOT NULL,
   user_retrosig SMALLINT(1) DEFAULT '0' NOT NULL,
   user_warnings SMALLINT (5) DEFAULT "0",
   PRIMARY KEY (user_id),
   KEY user_session_time (user_session_time)
);


CREATE TABLE phpbb_flags (
   flag_id int(10) NOT NULL auto_increment,
   flag_name varchar(25),
   flag_image varchar(25),
   PRIMARY KEY (flag_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_desc'
#
CREATE TABLE phpbb_vote_desc (
  vote_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  topic_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_text text NOT NULL,
  vote_start int(11) NOT NULL DEFAULT '0',
  vote_length int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (vote_id),
  KEY topic_id (topic_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_results'
#
CREATE TABLE phpbb_vote_results (
  vote_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_option_id tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  vote_option_text varchar(255) NOT NULL,
  vote_result int(11) NOT NULL DEFAULT '0',
  KEY vote_option_id (vote_option_id),
  KEY vote_id (vote_id)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_vote_voters'
#
CREATE TABLE phpbb_vote_voters (
  vote_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  vote_user_id mediumint(8) NOT NULL DEFAULT '0',
  vote_user_ip char(8) NOT NULL,
  KEY vote_id (vote_id),
  KEY vote_user_id (vote_user_id),
  KEY vote_user_ip (vote_user_ip)
);


# --------------------------------------------------------
#
# Table structure for table 'phpbb_words'
#
CREATE TABLE phpbb_words (
   word_id mediumint(8) UNSIGNED NOT NULL auto_increment,
   word char(100) NOT NULL,
   replacement char(100) NOT NULL,
   PRIMARY KEY (word_id)
);

# --------------------------------------------------------
#
# Table structure for table 'phpbb_modules'
#
CREATE TABLE phpbb_modules (
  module_id mediumint(8) unsigned NOT NULL auto_increment,
  module_name varchar(64) NOT NULL default '',
  module_type int(6) NOT NULL default '0',
  module_displayname varchar(64) NOT NULL default '',
  module_description varchar(255) NOT NULL default '',
  module_version varchar(10) NOT NULL default '',
  module_copyright varchar(255) NOT NULL default '',
  module_files text,
  module_templates text,
  module_admin text,
  module_blocks text,
  module_language text,
  module_constants text,
  module_headers text,
  module_state tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (module_id)
);

#
# Table structure for table phpbb_block_config
#

CREATE TABLE phpbb_block_config (
  id int(10) unsigned NOT NULL auto_increment,
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
);

#
# Table structure for table phpbb_block_layout
#

CREATE TABLE phpbb_block_layout (
  lid int(10) unsigned NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  view tinyint(1) NOT NULL default '0',
  groups tinytext NOT NULL,
  PRIMARY KEY  (lid)
);

#
# Table structure for table phpbb_block_position
#

CREATE TABLE phpbb_block_position (
  bpid int(10) NOT NULL auto_increment,
  pkey varchar(30) NOT NULL default '',
  bposition char(1) NOT NULL default '',
  layout int(10) NOT NULL default '1',
  PRIMARY KEY  (bpid)
);

#
# Table structure for table phpbb_block_variable
#

CREATE TABLE phpbb_block_variable (
  bvid int(10) NOT NULL auto_increment,
  label varchar(30) NOT NULL default '',
  sub_label varchar(255) default NULL,
  config_name varchar(30) NOT NULL default '',
  field_options varchar(255) default NULL,
  field_values varchar(255) default NULL,
  type tinyint(1) DEFAULT '0' NOT NULL,
  block varchar(255) default NULL,
  PRIMARY KEY  (config_name),
  KEY bvid (bvid)
);

#
# Table structure for table phpbb_blocks
#

CREATE TABLE phpbb_blocks (
  bid int(10) NOT NULL auto_increment,
  title varchar(60) NOT NULL default '',
  content text NOT NULL,
  bposition char(1) NOT NULL default '',
  weight int(10) NOT NULL default '1',
  active TINYINT( 1 ) DEFAULT '1' NOT NULL,
  blockfile varchar(255) NOT NULL default '',
  view TINYINT( 1 ) DEFAULT '0' NOT NULL,
  layout int(10) NOT NULL default '1',
  block_bbcode_uid varchar(10) default NULL,
  type TINYINT( 1 ) DEFAULT '1' NOT NULL,
  border tinyint(1) NOT NULL default '1',
  titlebar tinyint(1) NOT NULL default '1',
  background tinyint(1) NOT NULL default '1',
  local tinyint(1) NOT NULL default '0',
  groups tinytext NOT NULL,
  PRIMARY KEY  (bid)
);

CREATE TABLE phpbb_menu_links (
    bl_id MEDIUMINT( 8 ) NOT NULL auto_increment,
    bl_img VARCHAR( 150 ) NOT NULL ,
    bl_name VARCHAR( 50 ) NOT NULL ,
    bl_parameter VARCHAR( 50 ) NOT NULL ,
    bl_link VARCHAR( 128 ) NOT NULL ,
    bl_level TINYINT( 1 ) NOT NULL ,
    bl_dsort MEDIUMINT( 8 ) NOT NULL ,
    PRIMARY KEY (bl_id)
);

CREATE TABLE phpbb_user_menu_links (
    user_id MEDIUMINT( 8 ) NOT NULL ,
    board_link MEDIUMINT( 8 ) NOT NULL ,
    board_sort MEDIUMINT( 8 ) NOT NULL
);

CREATE TABLE phpbb_buddy (
  user_id mediumint(8) NOT NULL default '0',
  buddy_id mediumint(8) NOT NULL default '0',
  buddy_ignore tinyint(1) NOT NULL default '0',
  buddy_visible tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (user_id, buddy_id)
) TYPE=MyISAM;

CREATE TABLE phpbb_cash (
  cash_id smallint(6) NOT NULL auto_increment,
  cash_order smallint(6) NOT NULL default '0',
  cash_settings smallint(4) NOT NULL default '3313',
  cash_dbfield varchar(64) NOT NULL default '',
  cash_name varchar(64) NOT NULL default 'GP',
  cash_default int(11) NOT NULL default '0',
  cash_decimals tinyint(2) NOT NULL default '0',
  cash_imageurl varchar(255) NOT NULL default '',
  cash_exchange int(11) NOT NULL default '1',
  cash_perpost int(11) NOT NULL default '25',
  cash_postbonus int(11) NOT NULL default '2',
  cash_perreply int(11) NOT NULL default '25',
  cash_maxearn int(11) NOT NULL default '75',
  cash_perpm int(11) NOT NULL default '0',
  cash_perchar int(11) NOT NULL default '20',
  cash_allowance tinyint(1) NOT NULL default '0',
  cash_allowanceamount int(11) NOT NULL default '0',
  cash_allowancetime tinyint(2) NOT NULL default '2',
  cash_allowancenext int(11) NOT NULL default '0',
  cash_forumlist varchar(255) NOT NULL default '',
  PRIMARY KEY  (cash_id)
);

CREATE TABLE phpbb_cash_events (
  event_name varchar(32) NOT NULL default '',
  event_data varchar(255) NOT NULL default '',
  PRIMARY KEY  (event_name)
);

CREATE TABLE phpbb_cash_exchange (
  ex_cash_id1 int(11) NOT NULL default '0',
  ex_cash_id2 int(11) NOT NULL default '0',
  ex_cash_enabled int(1) NOT NULL default '0',
  PRIMARY KEY  (ex_cash_id1,ex_cash_id2)
);

CREATE TABLE phpbb_cash_groups (
  group_id mediumint(6) NOT NULL default '0',
  group_type tinyint(2) NOT NULL default '0',
  cash_id smallint(6) NOT NULL default '0',
  cash_perpost int(11) NOT NULL default '0',
  cash_postbonus int(11) NOT NULL default '0',
  cash_perreply int(11) NOT NULL default '0',
  cash_perchar int(11) NOT NULL default '0',
  cash_maxearn int(11) NOT NULL default '0',
  cash_perpm int(11) NOT NULL default '0',
  cash_allowance tinyint(1) NOT NULL default '0',
  cash_allowanceamount int(11) NOT NULL default '0',
  cash_allowancetime tinyint(2) NOT NULL default '2',
  cash_allowancenext int(11) NOT NULL default '0',
  PRIMARY KEY  (group_id,group_type,cash_id)
);

CREATE TABLE phpbb_cash_log (
  log_id int(11) NOT NULL auto_increment,
  log_time int(11) NOT NULL default '0',
  log_type smallint(6) NOT NULL default '0',
  log_action varchar(255) NOT NULL default '',
  log_text varchar(255) NOT NULL default '',
  PRIMARY KEY  (log_id)
);



#
# phpBB2 - attach_mod schema
#
# $Id: mysql_schema.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
#

#
# Table structure for table 'phpbb_attachments_config'
#
CREATE TABLE phpbb_attachments_config (
  config_name varchar(255) NOT NULL,
  config_value varchar(255) NOT NULL,
  PRIMARY KEY (config_name)
);

#
# Table structure for table 'phpbb_forbidden_extensions'
#
CREATE TABLE phpbb_forbidden_extensions (
  ext_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  extension varchar(100) NOT NULL,
  PRIMARY KEY (ext_id)
);

#
# Table structure for table 'phpbb_extension_groups'
#
CREATE TABLE phpbb_extension_groups (
  group_id mediumint(8) NOT NULL auto_increment,
  group_name char(20) NOT NULL,
  cat_id tinyint(2) DEFAULT '0' NOT NULL,
  allow_group tinyint(1) DEFAULT '0' NOT NULL,
  download_mode tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
  upload_icon varchar(100) DEFAULT '',
  max_filesize int(20) DEFAULT '0' NOT NULL,
  forum_permissions varchar(255) default '' NOT NULL,
  PRIMARY KEY group_id (group_id)
);

#
# Table structure for table 'phpbb_extensions'
#
CREATE TABLE phpbb_extensions (
  ext_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  group_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  extension varchar(100) NOT NULL,
  comment varchar(100),
  PRIMARY KEY ext_id (ext_id)
);

#
# Table structure for table 'phpbb_attachments_desc'
#
CREATE TABLE phpbb_attachments_desc (
  attach_id mediumint(8) UNSIGNED NOT NULL auto_increment,
  physical_filename varchar(255) NOT NULL,
  real_filename varchar(255) NOT NULL,
  download_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  comment varchar(255),
  extension varchar(100),
  mimetype varchar(100),
  filesize int(20) NOT NULL,
  filetime int(11) DEFAULT '0' NOT NULL,
  thumbnail tinyint(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (attach_id),
  KEY filetime (filetime),
  KEY physical_filename (physical_filename(10)),
  KEY filesize (filesize)
);

#
# Table structure for table 'phpbb_attachments'
#
CREATE TABLE phpbb_attachments (
  attach_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  instmsgs_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
  user_id_1 mediumint(8) NOT NULL,
  user_id_2 mediumint(8) NOT NULL,
  KEY attach_id_post_id (attach_id, post_id),
  KEY attach_id_instmsgs_id (attach_id, instmsgs_id)
);

#
# Table structure for table 'phpbb_quota_limits'
#
CREATE TABLE phpbb_quota_limits (
  quota_limit_id mediumint(8) unsigned NOT NULL auto_increment,
  quota_desc varchar(20) NOT NULL default '',
  quota_limit bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (quota_limit_id)
);

#
# Table structure for table 'phpbb_attach_quota'
#
CREATE TABLE phpbb_attach_quota (
  user_id mediumint(8) unsigned NOT NULL default '0',
  group_id mediumint(8) unsigned NOT NULL default '0',
  quota_type smallint(2) NOT NULL default '0',
  quota_limit_id mediumint(8) unsigned NOT NULL default '0',
  KEY quota_type (quota_type)
);



# --------------------------------------------------------
#
# Table structure for table `phpbb_im_prefs`
#
# You might want to change the default values for some of the columns in this
# table if you want to restrict the default settings for users.  Any column with
# "tinyint(1)" can have default values of 1 (on/true) or 0 (off/false).  The
# exceptions to this are:
# show_controls : 0 = no controls, 1 = images only, 2 = links only, 3 = both
# list_all_online : 1 = all online users, 2 = buddies on forums, 3 = buddies on IM,
#     4 = all users on im
# refresh_method : 0 = Meta, 1 = Javascript, 2 = Both
# network_user_list : 0 = Don't list Off-Site Users, 1 = List with On-Sites,
#     2 = List separately
# default_mode : 0 = Last mode used, 1 = Main Mode
# current_mode : 1 = Main Mode

CREATE TABLE phpbb_im_prefs (
  user_id mediumint(8) NOT NULL default '0',
  user_allow_ims tinyint(1) NOT NULL default '1',
  user_allow_shout tinyint(1) NOT NULL default '1',
  user_allow_chat tinyint(1) NOT NULL default '1',
  user_allow_network tinyint(1) NOT NULL default '1',
  admin_allow_ims tinyint(1) NOT NULL default '1',
  admin_allow_shout tinyint(1) NOT NULL default '1',
  admin_allow_chat tinyint(1) NOT NULL default '1',
  admin_allow_network tinyint(1) NOT NULL default '1',
  new_ims smallint(5) unsigned NOT NULL default '0',
  unread_ims smallint(5) unsigned NOT NULL default '0',
  read_ims smallint(5) unsigned NOT NULL default '0',
  total_sent mediumint(8) unsigned NOT NULL default '0',
  attach_sig tinyint(1) NOT NULL default '0',
  refresh_rate smallint(5) unsigned NOT NULL default '60',
  success_close tinyint(1) NOT NULL default '1',
  refresh_method tinyint(1) NOT NULL default '2',
  auto_launch tinyint(1) NOT NULL default '1',
  popup_ims tinyint(1) NOT NULL default '1',
  list_ims tinyint(1) NOT NULL default '0',
  show_controls tinyint(1) NOT NULL default '1',
  list_all_online tinyint(1) NOT NULL default '1',
  default_mode tinyint(1) NOT NULL default '1',
  current_mode tinyint(1) NOT NULL default '1',
  mode1_height varchar(4) NOT NULL default '400',
  mode1_width varchar(4) NOT NULL default '225',
  prefs_width varchar(4) NOT NULL default '500',
  prefs_height varchar(4) NOT NULL default '350',
  read_height varchar(4) NOT NULL default '300',
  read_width varchar(4) NOT NULL default '400',
  send_height varchar(4) NOT NULL default '365',
  send_width varchar(4) NOT NULL default '460',
  play_sound tinyint(1) NOT NULL default '1',
  default_sound tinyint(1) NOT NULL default '1',
  sound_name varchar(255) default NULL,
  themes_id mediumint(8) unsigned NOT NULL default '1',
  network_user_list tinyint(1) NOT NULL default '1',
  auto_delete tinyint(1) NOT NULL default '0',
  use_frames tinyint(1) NOT NULL default '1',
  user_override tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_sites'
#
CREATE TABLE phpbb_im_sites (
  site_id mediumint(8) NOT NULL auto_increment,
  site_name varchar(50) NOT NULL default '',
  site_url varchar(100) NOT NULL default '',
  site_phpex varchar(4) NOT NULL default 'php',
  site_profile varchar(50) NOT NULL default 'profile',
  site_enable tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (site_id)
) TYPE=MyISAM;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_sessions'
#
CREATE TABLE phpbb_im_sessions (
  session_user_id mediumint(8) NOT NULL default '0',
  session_id char(32) NOT NULL default '',
  session_time int(11) NOT NULL default '0',
  session_popup tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (session_id),
  KEY session_user_id (session_user_id)
) TYPE=MyISAM;

# --------------------------------------------------------
#
# Table structure for table 'phpbb_im_config'
#
CREATE TABLE phpbb_im_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name)
) TYPE=MyISAM;