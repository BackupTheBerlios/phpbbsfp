/*

  mssql_schema.sql for phpBB2 (c) 2001, phpBB Group

 $Id: mssql_schema.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $

*/

BEGIN TRANSACTION
GO

CREATE TABLE [phpbb_auth_access] (
    [group_id] [int] NULL ,
    [forum_id] [int] NULL ,
    [auth_view] [smallint] NOT NULL ,
    [auth_read] [smallint] NOT NULL ,
    [auth_post] [smallint] NOT NULL ,
    [auth_reply] [smallint] NOT NULL ,
    [auth_edit] [smallint] NOT NULL ,
    [auth_delete] [smallint] NOT NULL ,
    [auth_sticky] [smallint] NOT NULL ,
    [auth_announce] [smallint] NOT NULL ,
    [auth_vote] [smallint] NOT NULL ,
    [auth_pollcreate] [smallint] NOT NULL ,
    [auth_attachments] [smallint] NOT NULL ,
    [auth_mod] [smallint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_banlist] (
    [ban_id] [int] IDENTITY (1, 1) NOT NULL ,
    [ban_userid] [int] NULL ,
    [ban_ip] [char] (8) NULL ,
    [ban_email] [varchar] (50) NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_categories] (
    [cat_id] [int] IDENTITY (1, 1) NOT NULL ,
    [cat_title] [varchar] (50) NOT NULL ,
    [cat_order] [int] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_config] (
    [config_name] [varchar] (255) NULL ,
    [config_value] [varchar] (255) NULL
) ON [PRIMARY]
GO


CREATE TABLE [phpbb_confirm] (
    [confirm_id] [char] (32) NOT NULL ,
    [session_id] [char] (32) NOT NULL ,
    [code] [char] (6) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_disallow] (
    [disallow_id] [int] IDENTITY (1, 1) NOT NULL ,
    [disallow_username] [varchar] (100) NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_forum_prune] (
    [prune_id] [int] IDENTITY (1, 1) NOT NULL ,
    [forum_id] [int] NOT NULL ,
    [prune_days] [int] NOT NULL ,
    [prune_freq] [int] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_forums] (
    [forum_id] [int] NOT NULL ,
    [cat_id] [int] NOT NULL ,
    [forum_name] [varchar] (100) NOT NULL ,
    [forum_desc] [varchar] (255) NULL ,
    [forum_status] [smallint] NOT NULL ,
    [forum_order] [int] NOT NULL ,
    [forum_posts] [int] NOT NULL ,
    [forum_topics] [smallint] NOT NULL ,
    [forum_last_post_id] [int] NOT NULL ,
    [prune_next] [int] NULL ,
    [prune_enable] [smallint] NOT NULL ,
    [auth_view] [smallint] NOT NULL ,
    [auth_read] [smallint] NOT NULL ,
    [auth_post] [smallint] NOT NULL ,
    [auth_reply] [smallint] NOT NULL ,
    [auth_edit] [smallint] NOT NULL ,
    [auth_delete] [smallint] NOT NULL ,
    [auth_sticky] [smallint] NOT NULL ,
    [auth_announce] [smallint] NOT NULL ,
    [auth_vote] [smallint] NOT NULL ,
    [auth_pollcreate] [smallint] NOT NULL ,
    [auth_attachments] [smallint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_groups] (
    [group_id] [int] IDENTITY (1, 1) NOT NULL ,
    [group_type] [smallint] NULL ,
    [group_name] [varchar] (50) NOT NULL ,
    [group_description] [varchar] (255) NOT NULL ,
    [group_moderator] [int] NULL ,
    [group_single_user] [smallint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_posts] (
    [post_id] [int] IDENTITY (1, 1) NOT NULL ,
    [topic_id] [int] NOT NULL ,
    [forum_id] [int] NOT NULL ,
    [poster_id] [int] NOT NULL ,
    [post_time] [int] NOT NULL ,
    [poster_ip] [char] (8) NULL ,
    [post_username] [char] (25) NULL ,
    [enable_bbcode] [smallint] NULL ,
    [enable_html] [smallint] NULL ,
    [enable_smilies] [smallint] NULL ,
    [enable_sig] [smallint] NULL ,
    [post_edit_time] [int] NULL ,
    [post_edit_count] [smallint] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_posts_text] (
    [post_id] [int] NOT NULL ,
    [bbcode_uid] [char] (10) NULL ,
    [post_subject] [char] (96) NULL ,
    [post_text] [text] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

CREATE TABLE [phpbb_instmsgs] (
    [instmsgs_id] [int] IDENTITY (1, 1) NOT NULL ,
    [instmsgs_type] [smallint] NOT NULL ,
    [instmsgs_subject] [varchar] (100) NOT NULL ,
    [instmsgs_from_userid] [int] NOT NULL ,
    [instmsgs_to_userid] [int] NOT NULL ,
    [instmsgs_date] [int] NOT NULL ,
    [instmsgs_ip] [char] (8) NOT NULL ,
    [instmsgs_enable_bbcode] [smallint] NULL ,
    [instmsgs_enable_html] [smallint] NULL ,
    [instmsgs_enable_smilies] [smallint] NULL ,
    [instmsgs_attach_sig] [smallint] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_instmsgs_text] (
    [instmsgs_text_id] [int] NOT NULL ,
    [instmsgs_bbcode_uid] [char] (10) NULL ,
    [instmsgs_text] [text] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

CREATE TABLE [phpbb_ranks] (
    [rank_id] [int] IDENTITY (1, 1) NOT NULL ,
    [rank_title] [varchar] (50) NOT NULL ,
    [rank_min] [int] NULL ,
    [rank_special] [smallint] NULL ,
    [rank_image] [varchar] (50) NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_search_results] (
    [search_id] [int] NOT NULL ,
    [session_id] [char] (32) NOT NULL ,
    [search_array] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

CREATE TABLE [phpbb_search_wordlist] (
    [word_id] [int] IDENTITY (1, 1) NOT NULL ,
    [word_text] [varchar] (50) NOT NULL ,
    [word_common] [tinyint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_search_wordmatch] (
    [post_id] [int] NOT NULL ,
    [word_id] [int] NOT NULL ,
    [title_match] [smallint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_sessions] (
    [session_id] [char] (32) NOT NULL ,
    [session_user_id] [int] NOT NULL ,
    [session_start] [int] NULL ,
    [session_time] [int] NULL ,
    [session_ip] [char] (8) NOT NULL ,
    [session_page] [int] NULL ,
    [session_logged_in] [smallint] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_smilies] (
    [smilies_id] [int] IDENTITY (1, 1) NOT NULL ,
    [code] [varchar] (10) NOT NULL ,
    [smile_url] [varchar] (50) NOT NULL ,
    [emoticon] [varchar] (50) NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_themes] (
    [themes_id] [int] IDENTITY (1, 1) NOT NULL ,
    [template_name] [varchar] (30) NOT NULL ,
    [style_name] [varchar] (50) NOT NULL ,
    [head_stylesheet] [varchar] (50) NULL ,
    [body_background] [varchar] (50) NULL ,
    [body_bgcolor] [char] (6) NULL ,
    [body_text] [char] (6) NULL ,
    [body_link] [char] (6) NULL ,
    [body_vlink] [char] (6) NULL ,
    [body_alink] [char] (6) NULL ,
    [body_hlink] [char] (6) NULL ,
    [tr_color1] [char] (6) NULL ,
    [tr_color2] [char] (6) NULL ,
    [tr_color3] [char] (6) NULL ,
    [tr_class1] [varchar] (25) NULL ,
    [tr_class2] [varchar] (25) NULL ,
    [tr_class3] [varchar] (25) NULL ,
    [th_color1] [char] (6) NULL ,
    [th_color2] [char] (6) NULL ,
    [th_color3] [char] (6) NULL ,
    [th_class1] [varchar] (25) NULL ,
    [th_class2] [varchar] (25) NULL ,
    [th_class3] [varchar] (25) NULL ,
    [td_color1] [char] (6) NULL ,
    [td_color2] [char] (6) NULL ,
    [td_color3] [char] (6) NULL ,
    [td_class1] [varchar] (25) NULL ,
    [td_class2] [varchar] (25) NULL ,
    [td_class3] [varchar] (25) NULL ,
    [fontface1] [varchar] (50) NULL ,
    [fontface2] [varchar] (50) NULL ,
    [fontface3] [varchar] (50) NULL ,
    [fontsize1] [smallint] NULL ,
    [fontsize2] [smallint] NULL ,
    [fontsize3] [smallint] NULL ,
    [fontcolor1] [char] (6) NULL ,
    [fontcolor2] [char] (6) NULL ,
    [fontcolor3] [char] (6) NULL ,
    [span_class1] [varchar] (25) NULL ,
    [span_class2] [varchar] (25) NULL ,
    [span_class3] [varchar] (25) NULL ,
    [img_size_poll] [smallint] NULL ,
    [img_size_privmsg] [smallint] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_themes_name] (
    [themes_id] [int] NOT NULL ,
    [tr_color1_name] [varchar] (50) NULL ,
    [tr_color2_name] [varchar] (50) NULL ,
    [tr_color3_name] [varchar] (50) NULL ,
    [tr_class1_name] [varchar] (50) NULL ,
    [tr_class2_name] [varchar] (50) NULL ,
    [tr_class3_name] [varchar] (50) NULL ,
    [th_color1_name] [varchar] (50) NULL ,
    [th_color2_name] [varchar] (50) NULL ,
    [th_color3_name] [varchar] (50) NULL ,
    [th_class1_name] [varchar] (50) NULL ,
    [th_class2_name] [varchar] (50) NULL ,
    [th_class3_name] [varchar] (50) NULL ,
    [td_color1_name] [varchar] (50) NULL ,
    [td_color2_name] [varchar] (50) NULL ,
    [td_color3_name] [varchar] (50) NULL ,
    [td_class1_name] [varchar] (50) NULL ,
    [td_class2_name] [varchar] (50) NULL ,
    [td_class3_name] [varchar] (50) NULL ,
    [fontface1_name] [varchar] (50) NULL ,
    [fontface2_name] [varchar] (50) NULL ,
    [fontface3_name] [varchar] (50) NULL ,
    [fontsize1_name] [varchar] (50) NULL ,
    [fontsize2_name] [varchar] (50) NULL ,
    [fontsize3_name] [varchar] (50) NULL ,
    [fontcolor1_name] [varchar] (50) NULL ,
    [fontcolor2_name] [varchar] (50) NULL ,
    [fontcolor3_name] [varchar] (50) NULL ,
    [span_class1_name] [varchar] (50) NULL ,
    [span_class2_name] [varchar] (50) NULL ,
    [span_class3_name] [varchar] (50) NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_topics] (
    [topic_id] [int] IDENTITY (1, 1) NOT NULL ,
    [forum_id] [int] NOT NULL ,
    [topic_title] [varchar] (60) NOT NULL ,
    [topic_poster] [int] NOT NULL ,
    [topic_time] [int] NOT NULL ,
    [topic_views] [int] NOT NULL ,
    [topic_replies] [int] NOT NULL ,
    [topic_status] [smallint] NOT NULL ,
    [topic_type] [smallint] NOT NULL ,
    [topic_vote] [smallint] NOT NULL ,
    [topic_first_post_id] [int] NULL ,
    [topic_last_post_id] [int] NULL ,
    [topic_moved_id] [int] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_topics_watch] (
    [topic_id] [int] NOT NULL ,
    [user_id] [int] NOT NULL ,
    [notify_status] [smallint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_user_group] (
    [group_id] [int] NOT NULL ,
    [user_id] [int] NOT NULL ,
    [user_pending] [smallint] NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_users] (
    [user_id] [int] NOT NULL ,
    [user_active] [smallint] NULL ,
    [username] [varchar] (25) NOT NULL ,
    [user_password] [varchar] (32) NOT NULL ,
    [user_session_time] [int] NOT NULL ,
    [user_session_page] [smallint] NOT NULL ,
    [user_lastvisit] [int] NOT NULL ,
    [user_regdate] [int] NOT NULL ,
    [user_level] [smallint] NOT NULL ,
    [user_posts] [int] NOT NULL ,
    [user_timezone] [decimal] (5,2) NOT NULL ,
    [user_style] [int] NULL ,
    [user_lang] [varchar] (255) NULL ,
    [user_dateformat] [varchar] (14) NOT NULL ,
    [user_new_privmsg] [smallint] NOT NULL ,
    [user_unread_privmsg] [smallint] NOT NULL ,
    [user_last_privmsg] [int] NOT NULL ,
    [user_emailtime] [int] NOT NULL ,
    [user_viewemail] [smallint] NULL ,
    [user_attachsig] [smallint] NULL ,
    [user_allowhtml] [smallint] NULL ,
    [user_allowbbcode] [smallint] NULL ,
    [user_allowsmile] [smallint] NULL ,
    [user_allowavatar] [smallint] NULL ,
    [user_allow_pm] [smallint] NOT NULL ,
    [user_allow_viewonline] [smallint] NOT NULL ,
    [user_notify_pm] [smallint] NOT NULL ,
    [user_popup_pm] [smallint] NULL ,
    [user_rank] [int] NULL ,
    [user_avatar_type] [smallint] NULL ,
    [user_avatar] [varchar] (100) NULL ,
    [user_email] [varchar] (255) NULL ,
    [user_icq] [varchar] (15) NULL ,
    [user_website] [varchar] (100) NULL ,
    [user_occ] [varchar] (100) NULL ,
    [user_from] [varchar] (100) NULL ,
    [user_sig] [text] NULL ,
    [user_sig_bbcode_uid] [char] (10) NULL ,
    [user_aim] [varchar] (255) NULL ,
    [user_yim] [varchar] (255) NULL ,
    [user_msnm] [varchar] (255) NULL ,
    [user_interests] [varchar] (255) NULL ,
    [user_actkey] [varchar] (32) NULL ,
    [user_newpasswd] [varchar] (32) NULL ,
    [user_notify] [smallint] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

CREATE TABLE [phpbb_vote_desc] (
    [vote_id] [int] IDENTITY (1, 1) NOT NULL ,
    [topic_id] [int] NOT NULL ,
    [vote_text] [varchar] (255) NOT NULL ,
    [vote_start] [int] NOT NULL ,
    [vote_length] [int] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_vote_results] (
    [vote_id] [int] NOT NULL ,
    [vote_option_id] [int] NOT NULL ,
    [vote_option_text] [varchar] (255) NOT NULL ,
    [vote_result] [int] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_vote_voters] (
    [vote_id] [int] NOT NULL ,
    [vote_user_id] [int] NOT NULL ,
    [vote_user_ip] [char] (8) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_words] (
    [word_id] [int] IDENTITY (1, 1) NOT NULL ,
    [word] [varchar] (255) NOT NULL ,
    [replacement] [varchar] (255) NOT NULL
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_banlist] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_banlist] PRIMARY KEY  CLUSTERED
    (
        [ban_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_categories] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_categories] PRIMARY KEY  CLUSTERED
    (
        [cat_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_confirm] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_confirm] PRIMARY KEY  CLUSTERED
    (
        [session_id],[confirm_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_disallow] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_disallow] PRIMARY KEY  CLUSTERED
    (
        [disallow_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_forum_prune] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_forum_prune] PRIMARY KEY  CLUSTERED
    (
        [prune_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_forums] PRIMARY KEY  CLUSTERED
    (
        [forum_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_groups] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_groups] PRIMARY KEY  CLUSTERED
    (
        [group_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_posts] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_posts] PRIMARY KEY  CLUSTERED
    (
        [post_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_instmsgs] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_instmsgs] PRIMARY KEY  CLUSTERED
    (
        [instmsgs_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_instmsgs_text] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_instmsgs_text] PRIMARY KEY  CLUSTERED
    (
        [instmsgs_text_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_ranks] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_ranks] PRIMARY KEY  CLUSTERED
    (
        [rank_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_search_results] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_search_results] PRIMARY KEY  CLUSTERED
    (
        [search_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_search_wordlist] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_search_wordlist] PRIMARY KEY  CLUSTERED
    (
        [word_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_smilies] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_smilies] PRIMARY KEY  CLUSTERED
    (
        [smilies_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_themes] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_themes] PRIMARY KEY  CLUSTERED
    (
        [themes_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_themes_name] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_themes_name] PRIMARY KEY  CLUSTERED
    (
        [themes_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_topics] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_topics] PRIMARY KEY  CLUSTERED
    (
        [topic_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_users] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_users] PRIMARY KEY  CLUSTERED
    (
        [user_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_vote_desc] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_vote_desc] PRIMARY KEY  CLUSTERED
    (
        [vote_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_words] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_words] PRIMARY KEY  CLUSTERED
    (
        [word_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_auth_access] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_auth_access_auth_view] DEFAULT (0) FOR [auth_view],
    CONSTRAINT [DF_phpbb_auth_access_auth_read] DEFAULT (0) FOR [auth_read],
    CONSTRAINT [DF_phpbb_auth_access_auth_post] DEFAULT (0) FOR [auth_post],
    CONSTRAINT [DF_phpbb_auth_access_auth_reply] DEFAULT (0) FOR [auth_reply],
    CONSTRAINT [DF_phpbb_auth_access_auth_edit] DEFAULT (0) FOR [auth_edit],
    CONSTRAINT [DF_phpbb_auth_access_auth_delete] DEFAULT (0) FOR [auth_delete],
    CONSTRAINT [DF_phpbb_auth_access_auth_sticky] DEFAULT (0) FOR [auth_sticky],
    CONSTRAINT [DF_phpbb_auth_access_auth_announce] DEFAULT (0) FOR [auth_announce],
    CONSTRAINT [DF_phpbb_auth_access_auth_vote] DEFAULT (0) FOR [auth_vote],
    CONSTRAINT [DF_phpbb_auth_access_auth_pollcreate] DEFAULT (0) FOR [auth_pollcreate],
    CONSTRAINT [DF_phpbb_auth_access_auth_attachments] DEFAULT (0) FOR [auth_attachments],
    CONSTRAINT [DF_phpbb_auth_access_auth_mod] DEFAULT (0) FOR [auth_mod]
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_forums_forum_posts] DEFAULT (0) FOR [forum_posts],
    CONSTRAINT [DF_phpbb_forums_forum_topics] DEFAULT (0) FOR [forum_topics],
    CONSTRAINT [DF_phpbb_forums_forum_last_post_id] DEFAULT (0) FOR [forum_last_post_id],
    CONSTRAINT [DF_phpbb_forums_prune_enable] DEFAULT (0) FOR [prune_enable],
    CONSTRAINT [DF_phpbb_forums_auth_view] DEFAULT (0) FOR [auth_view],
    CONSTRAINT [DF_phpbb_forums_auth_read] DEFAULT (0) FOR [auth_read],
    CONSTRAINT [DF_phpbb_forums_auth_post] DEFAULT (0) FOR [auth_post],
    CONSTRAINT [DF_phpbb_forums_auth_reply] DEFAULT (0) FOR [auth_reply],
    CONSTRAINT [DF_phpbb_forums_auth_edit] DEFAULT (0) FOR [auth_edit],
    CONSTRAINT [DF_phpbb_forums_auth_delete] DEFAULT (0) FOR [auth_delete],
    CONSTRAINT [DF_phpbb_forums_auth_sticky] DEFAULT (0) FOR [auth_sticky],
    CONSTRAINT [DF_phpbb_forums_auth_announce] DEFAULT (0) FOR [auth_announce],
    CONSTRAINT [DF_phpbb_forums_auth_vote] DEFAULT (0) FOR [auth_vote],
    CONSTRAINT [DF_phpbb_forums_auth_pollcreate] DEFAULT (0) FOR [auth_pollcreate],
    CONSTRAINT [DF_phpbb_forums_auth_attachments] DEFAULT (0) FOR [auth_attachments]
GO

ALTER TABLE [phpbb_confirm] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_confirm_confirm_id] DEFAULT ('') FOR [confirm_id],
    CONSTRAINT [DF_phpbb_confirm_session_id] DEFAULT ('') FOR [session_id],
    CONSTRAINT [DF_phpbb_confirm_code] DEFAULT ('') FOR [code]
GO

ALTER TABLE [phpbb_posts] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_posts_enable_bbcode] DEFAULT (1) FOR [enable_bbcode],
    CONSTRAINT [DF_phpbb_posts_enable_html] DEFAULT (0) FOR [enable_html],
    CONSTRAINT [DF_phpbb_posts_enable_smilies] DEFAULT (1) FOR [enable_smilies],
    CONSTRAINT [DF_phpbb_posts_enable_sig] DEFAULT (1) FOR [enable_sig],
    CONSTRAINT [DF_phpbb_posts_post_edit_count] DEFAULT (0) FOR [post_edit_count]
GO

ALTER TABLE [phpbb_search_wordlist] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_search_wordlist_word_common] DEFAULT (0) FOR [word_common]
GO

ALTER TABLE [phpbb_topics] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_topics_topic_views] DEFAULT (0) FOR [topic_views],
    CONSTRAINT [DF_phpbb_topics_topic_replies] DEFAULT (0) FOR [topic_replies],
    CONSTRAINT [DF_phpbb_topics_topic_status] DEFAULT (0) FOR [topic_status],
    CONSTRAINT [DF_phpbb_topics_topic_type] DEFAULT (0) FOR [topic_type],
    CONSTRAINT [DF_phpbb_topics_topic_vote] DEFAULT (0) FOR [topic_vote],
    CONSTRAINT [DF_phpbb_topics_topic_moved_id] DEFAULT (0) FOR topic_moved_id
GO

ALTER TABLE [phpbb_users] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_users_user_level] DEFAULT (0) FOR [user_level],
    CONSTRAINT [DF_phpbb_users_user_posts] DEFAULT (0) FOR [user_posts],
    CONSTRAINT [DF_phpbb_users_user_session_time] DEFAULT (0) FOR [user_session_time],
    CONSTRAINT [DF_phpbb_users_user_session_page] DEFAULT (0) FOR [user_session_page],
    CONSTRAINT [DF_phpbb_users_user_lastvisit] DEFAULT (0) FOR [user_lastvisit],
    CONSTRAINT [DF_phpbb_users_user_new_privmsg] DEFAULT (0) FOR [user_new_privmsg],
    CONSTRAINT [DF_phpbb_users_user_unread_privmsg] DEFAULT (0) FOR [user_unread_privmsg],
    CONSTRAINT [DF_phpbb_users_user_last_privmsg] DEFAULT (0) FOR [user_last_privmsg],
    CONSTRAINT [DF_phpbb_users_user_emailtime] DEFAULT (0) FOR [user_emailtime],
    CONSTRAINT [DF_phpbb_users_user_viewemail] DEFAULT (1) FOR [user_viewemail],
    CONSTRAINT [DF_phpbb_users_user_attachsig] DEFAULT (1) FOR [user_attachsig],
    CONSTRAINT [DF_phpbb_users_user_allowhtml] DEFAULT (0) FOR [user_allowhtml],
    CONSTRAINT [DF_phpbb_users_user_allowbbcode] DEFAULT (1) FOR [user_allowbbcode],
    CONSTRAINT [DF_phpbb_users_user_allowsmile] DEFAULT (1) FOR [user_allowsmile],
    CONSTRAINT [DF_phpbb_users_user_allowavatar] DEFAULT (1) FOR [user_allowavatar],
    CONSTRAINT [DF_phpbb_users_user_allow_pm] DEFAULT (1) FOR [user_allow_pm],
    CONSTRAINT [DF_phpbb_users_user_allow_viewonline] DEFAULT (1) FOR [user_allow_viewonline],
    CONSTRAINT [DF_phpbb_users_user_notify_pm] DEFAULT (0) FOR [user_notify_pm],
    CONSTRAINT [DF_phpbb_users_user_popup_pm] DEFAULT (1) FOR [user_popup_pm],
    CONSTRAINT [DF_phpbb_users_user_avatar_type] DEFAULT (0) FOR [user_avatar_type]
GO

ALTER TABLE phpbb_users ADD user_custom_title varchar(255) NULL;
ALTER TABLE phpbb_users ADD user_custom_title_status tinyint NOT NULL,
    CONSTRAINT [DF_phpbb_users_user_custom_title_status] DEFAULT (0) FOR [user_custom_title_status];
GO

 CREATE  INDEX [IX_phpbb_auth_access] ON [phpbb_auth_access]([group_id], [forum_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_banlist] ON [phpbb_banlist]([ban_userid], [ban_ip]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_categories] ON [phpbb_categories]([cat_order]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_forum_prune] ON [phpbb_forum_prune]([forum_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_forums] ON [phpbb_forums]([cat_id], [forum_order], [forum_last_post_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_groups] ON [phpbb_groups]([group_single_user]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_posts] ON [phpbb_posts]([topic_id], [forum_id], [poster_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_posts_text] ON [phpbb_posts_text]([post_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_instmsgs] ON [phpbb_instmsgs]([instmsgs_from_userid], [instmsgs_to_userid]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_ranks] ON [phpbb_ranks]([rank_min], [rank_special]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_search_results] ON [phpbb_search_results]([session_id]) ON [PRIMARY]
GO

 CREATE  UNIQUE  INDEX [IX_phpbb_search_wordlist] ON [phpbb_search_wordlist]([word_text]) WITH  IGNORE_DUP_KEY  ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_search_wordlist_1] ON [phpbb_search_wordlist]([word_common]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_search_wordmatch] ON [phpbb_search_wordmatch]([post_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_search_wordmatch_1] ON [phpbb_search_wordmatch]([word_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_sessions] ON [phpbb_sessions]([session_id], [session_user_id], [session_ip], [session_logged_in]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_topics] ON [phpbb_topics]([forum_id], [topic_type], [topic_first_post_id], [topic_last_post_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_topics_watch] ON [phpbb_topics_watch]([topic_id], [user_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_users] ON [phpbb_users]([user_session_time]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_user_group] ON [phpbb_user_group]([group_id], [user_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_vote_desc] ON [phpbb_vote_desc]([topic_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_vote_results] ON [phpbb_vote_results]([vote_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_vote_results_1] ON [phpbb_vote_results]([vote_option_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_vote_voters] ON [phpbb_vote_voters]([vote_id]) ON [PRIMARY]
GO

 CREATE  INDEX [IX_phpbb_vote_voters_1] ON [phpbb_vote_voters]([vote_user_id]) ON [PRIMARY]
GO

/*
 phpBB2 - attach_mod schema - mssql

 $Id: mssql_schema.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $

*/

CREATE TABLE [phpbb_attachments_config] (
    [config_name] [varchar] (100) NOT NULL ,
    [config_value] [varchar] (100) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_forbidden_extensions] (
    [ext_id] [int] IDENTITY (1, 1) NOT NULL ,
    [extension] [char] (100) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_extension_groups] (
    [group_id] [int] IDENTITY (1, 1) NOT NULL ,
    [group_name] [char] (20) NOT NULL ,
    [cat_id] [tinyint] NOT NULL ,
    [allow_group] [tinyint] NOT NULL ,
    [download_mode] [tinyint] NOT NULL ,
        [upload_icon] [varchar] (100) NOT NULL ,
        [max_filesize] [int] NOT NULL ,
    [forum_permissions] [varchar] (255) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_extensions] (
    [ext_id] [int] IDENTITY (1, 1) NOT NULL ,
    [group_id] [int] NOT NULL ,
    [extension] [varchar] (100) NOT NULL ,
    [comment] [varchar] (100) NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_attachments_desc] (
    [attach_id] [int] IDENTITY (1, 1) NOT NULL ,
    [physical_filename] [varchar] (100) NOT NULL ,
    [real_filename] [varchar] (100) NOT NULL ,
    [download_count] [int] NOT NULL ,
    [comment] [varchar] (100) NULL ,
    [extension] [varchar] (100) NULL ,
    [mimetype] [varchar] (50) NULL ,
    [filesize] [int] NOT NULL ,
    [filetime] [int] NOT NULL ,
    [thumbnail] [tinyint] NOT NULL
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_attachments] (
    [attach_id] [int] NOT NULL ,
    [post_id] [int] NOT NULL ,
    [instmsgs_id] [int] NOT NULL ,
    [user_id_1] [int] NOT NULL,
    [user_id_2] [int] NOT NULL
)
GO

CREATE TABLE [phpbb_quota_limits] (
  [quota_limit_id] [int] IDENTITY (1, 1) NOT NULL ,
  [quota_desc] [varchar] (20) NOT NULL,
  [quota_limit] [bigint] NOT NULL
) ON [PRIMARY];
GO

CREATE TABLE [phpbb_attach_quota] (
  [user_id] [int] NOT NULL,
  [group_id] [int] NOT NULL,
  [quota_type] [tinyint] NOT NULL,
  [quota_limit_id] [int] NOT NULL
);
GO

ALTER TABLE [phpbb_attachments_config] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_attachments_config] PRIMARY KEY CLUSTERED
    (
        [config_name]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_forbidden_extensions] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_forbidden_extensions] PRIMARY KEY CLUSTERED
    (
        [ext_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_extension_groups] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_extension_groups] PRIMARY KEY  CLUSTERED
    (
        [group_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_extension_groups] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_extension_groups_cat_id] DEFAULT (0) FOR [cat_id],
    CONSTRAINT [DF_phpbb_extension_groups_allow_group] DEFAULT (0) FOR [allow_group],
    CONSTRAINT [DF_phpbb_extension_groups_download_mode] DEFAULT (1) FOR [download_mode],
    CONSTRAINT [DF_phpbb_extension_groups_max_filesize] DEFAULT (0) FOR [max_filesize]
GO

ALTER TABLE [phpbb_extensions] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_extensions] PRIMARY KEY  CLUSTERED
    (
        [ext_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_extensions] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_extensions_group_id] DEFAULT (0) FOR [group_id]
GO

ALTER TABLE [phpbb_attachments_desc] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_attachments_desc] PRIMARY KEY  CLUSTERED
    (
        [attach_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_attachments_desc] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_attachments_desc_download_count] DEFAULT (0) FOR [download_count],
    CONSTRAINT [DF_phpbb_attachments_desc_thumbnail] DEFAULT (0) FOR [thumbnail],
    CONSTRAINT [DF_phpbb_attachments_desc_filetime] DEFAULT (0) FOR [filetime]
GO


ALTER TABLE [phpbb_attachments] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_attachments_attach_id] DEFAULT (0) FOR [attach_id],
    CONSTRAINT [DF_phpbb_attachments_post_id] DEFAULT (0) FOR [post_id],
    CONSTRAINT [DF_phpbb_attachments_instmsgs_id] DEFAULT (0) FOR [instmsgs_id]
GO

ALTER TABLE [phpbb_quota_limits] WITH NOCHECK ADD
    CONSTRAINT [PK_phpbb_quota_limits] PRIMARY KEY  CLUSTERED
    (
        [quota_limit_id]
    )  ON [PRIMARY]
GO

ALTER TABLE [phpbb_quota_limits] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_quota_limits_quota_limit] DEFAULT (0) FOR [quota_limit]
GO

ALTER TABLE [phpbb_attach_quota] WITH NOCHECK ADD
    CONSTRAINT [DF_phpbb_attach_quota_user_id] DEFAULT (0) FOR [user_id],
    CONSTRAINT [DF_phpbb_attach_quota_group_id] DEFAULT (0) FOR [group_id],
    CONSTRAINT [DF_phpbb_attach_quota_quota_type] DEFAULT (0) FOR [quota_type],
    CONSTRAINT [DF_phpbb_attach_quota_quota_limit_id] DEFAULT (0) FOR [quota_limit_id]
GO

ALTER TABLE [phpbb_forums] WITH NOCHECK ADD
    [auth_download] [int] NOT NULL,
    CONSTRAINT [DF_phpbb_forums_auth_download] DEFAULT (0) FOR [auth_download]
GO

ALTER TABLE [phpbb_auth_access] WITH NOCHECK ADD
    [auth_download] [int] NOT NULL,
    CONSTRAINT [DF_phpbb_auth_access_auth_download] DEFAULT (0) FOR [auth_download]
GO

ALTER TABLE [phpbb_posts] WITH NOCHECK ADD
    [post_attachment] [int] NOT NULL,
    CONSTRAINT [DF_phpbb_posts_post_attachment] DEFAULT (0) FOR [post_attachment]
GO

ALTER TABLE [phpbb_topics] WITH NOCHECK ADD
    [topic_attachment] [int] NOT NULL,
    CONSTRAINT [DF_phpbb_topics_topic_attachment] DEFAULT (0) FOR [topic_attachment]
GO

CREATE TABLE [phpbb_im_prefs] (
[user_id] [int] NOT NULL ,
[user_allow_ims] [int] NOT NULL ,
[user_allow_shout] [int] NOT NULL ,
[user_allow_chat] [int] NOT NULL ,
[user_allow_network] [int] NOT NULL ,
[admin_allow_ims] [int] NOT NULL ,
[admin_allow_shout] [int] NOT NULL ,
[admin_allow_chat] [int] NOT NULL ,
[admin_allow_network] [int] NOT NULL ,
[new_ims] [int] NOT NULL ,
[unread_ims] [int] NOT NULL ,
[read_ims] [int] NOT NULL ,
[total_sent] [int] NOT NULL ,
[attach_sig] [int] NOT NULL ,
[refresh_rate] [int] NOT NULL ,
[success_close] [int] NOT NULL ,
[refresh_method] [int] NOT NULL ,
[auto_launch] [int] NOT NULL ,
[popup_ims] [int] NOT NULL ,
[list_ims] [int] NOT NULL ,
[show_controls] [int] NOT NULL ,
[list_all_online] [int] NOT NULL ,
[default_mode] [int] NOT NULL ,
[current_mode] [int] NOT NULL ,
[mode1_height] [varchar] (4) NOT NULL ,
[mode1_width] [varchar] (4) NOT NULL ,
[mode2_height] [varchar] (4) NOT NULL ,
[mode2_width] [varchar] (4) NOT NULL ,
[mode3_height] [varchar] (4) NOT NULL ,
[mode3_width] [varchar] (4) NOT NULL ,
[prefs_height] [varchar] (4) NOT NULL ,
[prefs_width] [varchar] (4) NOT NULL ,
[read_height] [varchar] (4) NOT NULL ,
[read_width] [varchar] (4) NOT NULL ,
[send_height] [varchar] (4) NOT NULL ,
[send_width] [varchar] (4) NOT NULL ,
[play_sound] [int] NOT NULL ,
[default_sound] [int] NOT NULL ,
[sound_name] [varchar] (255) default NULL,
[themes_id] [int] NOT NULL,
[network_user_list] [int] NOT NULL ,
[open_pms] [int] NOT NULL ,
[auto_delete] [int] NOT NULL ,
[use_frames] [int] NOT NULL ,
[user_override] [int] NOT NULL
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_im_prefs] WITH NOCHECK ADD
CONSTRAINT [PK_phpbb_im_prefs] PRIMARY KEY CLUSTERED
(
[user_id]
) ON [PRIMARY]
GO

CREATE TABLE [phpbb_im_sites] (
[site_id] [int] IDENTITY (1, 1) NOT NULL ,
[site_name] [varchar] (50) NOT NULL ,
[site_url] [varchar] (100) NOT NULL ,
[site_phpex] [varchar] (4) NOT NULL ,
[site_profile] [varchar] (50) NOT NULL
PRIMARY KEY (site_id)
) ON [PRIMARY]
GO


CREATE TABLE [phpbb_im_sessions] (
[session_user_id] [int] NOT NULL ,
[session_id] [char] (32) NOT NULL ,
[session_time] [int] NOT NULL ,
[session_popup] [int] NOT NULL
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_im_sessions] WITH NOCHECK ADD
CONSTRAINT [PK_phpbb_im_sessions] PRIMARY KEY CLUSTERED
(
[session_id]
) ON [PRIMARY]
GO

ALTER TABLE [phpbb_im_sessions] WITH NOCHECK ADD
CONSTRAINT [DF_phpbb_im_sessions_session_popup] DEFAULT (0) FOR [session_popup]
GO

CREATE INDEX [IX_phpbb_im_sessions] ON [phpbb_im_sessions]([session_user_id]) ON [PRIMARY]
GO

/*
 Alterations to pre-existing tables for Prillian Messenger

 This file is completely untested - use at your own risk
*/
ALTER TABLE [phpbb_instmsgs] ADD
[instmsgs_from_username] [varchar] (25) NOT NULL ,
CONSTRAINT [DF_phpbb_instmsgs_instmsgs_from_username] DEFAULT ('') FOR [instmsgs_from_username],
[instmsgs_to_username] [varchar] (25) NOT NULL ,
CONSTRAINT [DF_phpbb_instmsgs_instmsgs_to_username] DEFAULT ('') FOR [instmsgs_to_username],
[site_id] [int] NOT NULL ,
CONSTRAINT [DF_phpbb_instmsgs_site_id] DEFAULT (0) FOR [site_id]
GO

CREATE INDEX [IX_phpbb_instmsgs_2] ON [phpbb_instmsgs]([site_id]) ON [PRIMARY]
GO

COMMIT
GO