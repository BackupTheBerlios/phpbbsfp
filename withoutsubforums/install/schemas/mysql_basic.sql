#
# Basic DB data for phpBB2 devel
#
# $Id: mysql_basic.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $

# -- Config
INSERT INTO phpbb_config (config_name, config_value) VALUES ('config_id','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_disable','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sitename','yourdomain.com');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('site_desc','A _little_ text to describe your forum');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_name','phpbb2mysql');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_path','/');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_domain','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_secure','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('session_length','3600');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html_tags','b,i,u,pre');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_bbcode','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_smilies','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_sig','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_namechange','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_theme_create','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_local','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_remote','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_upload','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('enable_confirm', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('override_user_style','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('posts_per_page','15');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('topics_per_page','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('hot_threshold','25');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_poll_options','10');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sig_chars','255');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_inbox_privmsgs','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sentbox_privmsgs','25');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_savebox_privmsgs','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_sig','Thanks, The Management');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email','youraddress@yourdomain.com');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_delivery','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_host','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_username','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_password','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sendmail_fix','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('require_activation','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('flood_interval','15');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_form','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_filesize','6144');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_width','80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_height','80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_path','files/avatars');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_gallery_path','files/avatars/gallery');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smilies_path','smiles');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_style','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_dateformat','D M d, Y g:i a');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_timezone','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('prune_enable','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('privmsg_disable','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('gzip_compress','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_fax', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_mail', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_name', 'www.myserver.tld');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_port', '80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('script_path', '/minerva/');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('version', '.0.0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('auth_mode', 'phpbb');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_host', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_port', '389');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_host2', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_port2', '389');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_dn', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_proxy_dn', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_proxy_dn_pass', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_start_tls', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_uid', 'uid');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_gid', 'memberof');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_group_sync', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_email', 'email');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_web', 'web');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_location', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_occupation', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('ldap_signature', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES('disable_guest', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_disable',0);
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_display_after_posts',1);
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_post_message','You earned %s for that post');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_disable_spam_num',10);
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_disable_spam_time',24);
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_disable_spam_message','You have exceeded the alloted amount of posts and will not earn anything for your post');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_installed','yes');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_version','2.2.1');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_adminbig','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('cash_adminnavbar','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES('points_name','Points');
INSERT INTO phpbb_config (config_name, config_value) VALUES('disable_reg', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('disable_reg_msg', 'Registration has been disabled.');
INSERT INTO phpbb_config (config_name, config_value) VALUES('topic_action_log_admin', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_keywords', 'minerva, portal, phpbb, blocks, modules' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_description', 'Minerva is a modular portal based on phpBB 2.0.x' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_revisit', '14' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_author', 'Minerva' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_owner', 'Minerva' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_distribution', 'global' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_robots', 'index,follow' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('meta_abstract', 'Modular Portal' );
INSERT INTO phpbb_config (config_name, config_value) VALUES('board_disable_adminview', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES('board_disable_msg', 'This site is currently offline for maintainence.');
INSERT INTO phpbb_config (config_name, config_value) VALUES('custom_title_days', 0);
INSERT INTO phpbb_config (config_name, config_value) VALUES('custom_title_posts', 0);
INSERT INTO phpbb_config (config_name, config_value) VALUES('custom_title_mode', 0);
INSERT INTO phpbb_config (config_name, config_value) VALUES('custom_title_maxlength', 45);
INSERT INTO phpbb_config (config_name, config_value) VALUES('bl_seperator', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES('bl_seperator_content', 'SPACE');
INSERT INTO phpbb_config (config_name, config_value) VALUES('bl_break', '5');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cache_gc', '7200');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cron_enable', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cron_every', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('show_begin_for', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('show_not_optimized', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ("bluecard_limit", "3");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("bluecard_limit_2", "1");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("max_user_bancard", "10");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("report_forum", "0");
INSERT INTO phpbb_config (config_name, config_value) VALUES ('index_rating_return','10');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('min_rates_number','5');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('rating_max','10');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_ext_rating','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('large_rating_return_limit','30');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('check_anon_ip_when_rating','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_rerate','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('header_rating_return_limit','3');
# -- Config (Dynamic)
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('record_online_users', '0', 1);
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('record_online_date', '0', 1);
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('cache_last_gc', '0', 1);
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('cron_next', '0', 1);
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('cron_count', '0', 1);
INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('cron_lock', '0', 1);


# -- Categories
INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) VALUES (1, 'Test category 1', 10);


# -- Forums
INSERT INTO phpbb_forums (forum_id, forum_name, forum_desc, cat_id, forum_order, forum_posts, forum_topics, forum_last_post_id, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_announce, auth_sticky, auth_pollcreate, auth_vote, auth_attachments, auth_global_announce) VALUES (1, 'Test Forum 1', 'This is just a test forum.', 1, 10, 1, 1, 1, 0, 0, 0, 0, 1, 1, 3, 3, 1, 1, 3, 5);


# -- Users
INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( -1, 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 0, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0);

# -- username: admin    password: admin (change this or remove it once everything is working!)
INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_popup_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( 2, 'Admin', 1, 0, '21232f297a57a5a743894a0e4a801fc3', 'admin@yourdomain.com', '', '', '', '', '', '', 1, 1, '', '', '', 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, '', 'english', 0, 'd M Y h:i a', '', '', 0, 1);


# -- Ranks
INSERT INTO phpbb_ranks (rank_id, rank_title, rank_min, rank_special, rank_image) VALUES ( 1, 'Site Admin', -1, 1, NULL);


# -- Groups
INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (1, 'Anonymous', 'Personal User', 1);
INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (2, 'Admin', 'Personal User', 1);

INSERT INTO phpbb_groups_subscriptions (subscription_id, paypal_email, paypal_bgcolor, subscription_currency) VALUES (0, 'paypal@yoursite.com', 'W', 'USD');

# -- User -> Group
INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (1, -1, 0);
INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (2, 2, 0);

# -- Demo Topic
INSERT INTO phpbb_topics (topic_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, forum_id, topic_status, topic_type, topic_vote, topic_first_post_id, topic_last_post_id) VALUES (1, 'Welcome to Minerva R3', 2, '972086460', 0, 0, 1, 0, 0, 0, 1, 1);


# -- Demo Post
INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, post_username, poster_ip) VALUES (1, 1, 1, 2, 972086460, NULL, '7F000001');
INSERT INTO phpbb_posts_text (post_id, post_subject, post_text) VALUES (1, NULL, 'This is an example post in your Minerva R3 installation. You may delete this post, this topic and even this forum if you like since everything seems to be working!');


# -- Themes
INSERT INTO phpbb_themes (themes_id, template_name, style_name, head_stylesheet, body_background, body_bgcolor, body_text, body_link, body_vlink, body_alink, body_hlink, tr_color1, tr_color2, tr_color3, tr_class1, tr_class2, tr_class3, th_color1, th_color2, th_color3, th_class1, th_class2, th_class3, td_color1, td_color2, td_color3, td_class1, td_class2, td_class3, fontface1, fontface2, fontface3, fontsize1, fontsize2, fontsize3, fontcolor1, fontcolor2, fontcolor3, span_class1, span_class2, span_class3) VALUES (1, 'subSilver', 'subSilver', 'subSilver.css', '', 'E5E5E5', '000000', '666666', '888888', '', '999999', 'EFEFEF', 'E7E7E7', 'D7D7D7', '', '', '', 'AAAAAA', '666666', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, \'Courier New\', sans-serif', 10, 11, 12, '444444', '006600', 'FFA34F', '', '', '');

INSERT INTO phpbb_themes_name (themes_id, tr_color1_name, tr_color2_name, tr_color3_name, tr_class1_name, tr_class2_name, tr_class3_name, th_color1_name, th_color2_name, th_color3_name, th_class1_name, th_class2_name, th_class3_name, td_color1_name, td_color2_name, td_color3_name, td_class1_name, td_class2_name, td_class3_name, fontface1_name, fontface2_name, fontface3_name, fontsize1_name, fontsize2_name, fontsize3_name, fontcolor1_name, fontcolor2_name, fontcolor3_name, span_class1_name, span_class2_name, span_class3_name) VALUES (1, 'The lightest row colour', 'The medium row color', 'The darkest row colour', '', '', '', 'Border round the whole page', 'Outer table border', 'Inner table border', 'Silver gradient picture', 'Blue gradient picture', 'Fade-out gradient on index', 'Background for quote boxes', 'All white areas', '', 'Background for topic posts', '2nd background for topic posts', '', 'Main fonts', 'Additional topic title font', 'Form fonts', 'Smallest font size', 'Medium font size', 'Normal font size (post body etc)', 'Quote & copyright text', 'Code text colour', 'Main table header text colour', '', '', '');


# -- Smilies
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 1, ':D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 2, ':-D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 3, ':grin:', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 4, ':)', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 5, ':-)', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 6, ':smile:', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 7, ':(', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 8, ':-(', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 9, ':sad:', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 10, ':o', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 11, ':-o', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 12, ':eek:', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 13, ':shock:', 'icon_eek.gif', 'Shocked');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 14, ':?', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 15, ':-?', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 16, ':???:', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 17, '8)', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 18, '8-)', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 19, ':cool:', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 20, ':lol:', 'icon_lol.gif', 'Laughing');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 21, ':x', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 22, ':-x', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 23, ':mad:', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 24, ':P', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 25, ':-P', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 26, ':razz:', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 27, ':oops:', 'icon_redface.gif', 'Embarassed');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 28, ':cry:', 'icon_cry.gif', 'Crying or Very sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 32, ':wink:', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 33, ';)', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 34, ';-)', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 35, ':!:', 'icon_exclaim.gif', 'Exclamation');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 36, ':?:', 'icon_question.gif', 'Question');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 37, ':idea:', 'icon_idea.gif', 'Idea');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 38, ':arrow:', 'icon_arrow.gif', 'Arrow');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 39, ':|', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 40, ':-|', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 41, ':neutral:', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green');


# -- wordlist
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 1, 'example', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 2, 'post', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 3, 'phpbb', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 4, 'installation', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 5, 'delete', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 6, 'topic', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 7, 'forum', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 8, 'since', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 9, 'everything', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 10, 'seems', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 11, 'working', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 12, 'welcome', 0 );


# -- wordmatch
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 1, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 2, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 4, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 5, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 6, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 7, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 8, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 9, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 10, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 11, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 12, 1, 1 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 1 );

#
# Basic DB data for Attachment Mod
#
# $Id: mysql_basic.sql,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
#

# -- attachments_config
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('upload_dir','files/attachments');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('upload_img','icon_clip.gif');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('topic_icon','icon_clip.gif');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('display_order','0');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_filesize','262144');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attachment_quota','52428800');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_filesize_pm','262144');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_attachments','3');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_attachments_pm','1');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('disable_mod','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('allow_pm_attach','1');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attachment_topic_review','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('allow_ftp_upload','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('show_apcp','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attach_version','2.3.9');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('default_upload_quota', '0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('default_pm_quota', '0');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_server','');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_path','');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('download_path','');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_user','');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_pass','');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_pasv_mode','1');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_display_inlined','1');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_max_width','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_max_height','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_link_width','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_link_height','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_create_thumbnail','0');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_min_thumb_filesize','12000');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_imagick', '');
INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('use_gd2','0');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('wma_autoplay','0');

INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('flash_autoplay','0');

# -- forbidden_extensions
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (1,'php');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (2,'php3');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (3,'php4');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (4,'phtml');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (5,'pl');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (6,'asp');
INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (7,'cgi');

# -- extension_groups
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (1,'Images',1,1,1,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (2,'Archives',0,1,1,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (3,'Plain Text',0,0,1,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (4,'Documents',0,0,1,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (5,'Real Media',0,0,2,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (6,'Streams',2,0,1,'',0,'');
INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (7,'Flash Files',3,0,1,'',0,'');

# -- extensions
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (1, 1,'gif', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (2, 1,'png', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (3, 1,'jpeg', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (4, 1,'jpg', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (5, 1,'tif', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (6, 1,'tga', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (7, 2,'gtar', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (8, 2,'gz', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (9, 2,'tar', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (10, 2,'zip', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (11, 2,'rar', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (12, 2,'ace', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (13, 3,'txt', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (14, 3,'c', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (15, 3,'h', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (16, 3,'cpp', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (17, 3,'hpp', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (18, 3,'diz', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (19, 4,'xls', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (20, 4,'doc', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (21, 4,'dot', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (22, 4,'pdf', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (23, 4,'ai', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (24, 4,'ps', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (25, 4,'ppt', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (26, 5,'rm', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (27, 6,'wma', '');
INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (28, 7,'swf', '');

# -- default quota limits
INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (1, 'Low', 262144);
INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (2, 'Medium', 2097152);
INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (3, 'High', 5242880);

INSERT INTO phpbb_cash VALUES (1, 1, 3313, 'user_cash', 'Credit', 0, 2, '', 1, 2500, 200, 2500, 7500, 0, 2000, 0, 0, 2, 0, '');

INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','afghanistan','afghanistan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','albania','albania.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','algeria','algeria.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','andorra','andorra.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','angola','angola.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','antigua and barbuda','antiguabarbuda.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','argentina','argentina.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','armenia','armenia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','australia','australia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','austria','austria.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','azerbaijan','azerbaijan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bahamas','bahamas.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bahrain','bahrain.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bangladesh','bangladesh.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','barbados','barbados.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','belarus','belarus.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','belgium','belgium.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','belize','belize.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','benin','benin.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bhutan','bhutan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bolivia','bolivia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bosnia herzegovina','bosnia_herzegovina.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','botswana','botswana.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','brazil','brazil.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','brunei','brunei.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','bulgaria','bulgaria.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','burkinafaso','burkinafaso.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','burma','burma.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','burundi','burundi.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','cambodia','cambodia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','cameroon','cameroon.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','canada','canada.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','central african rep','centralafricanrep.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','chad','chad.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','chile','chile.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','china','china.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','columbia','columbia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','comoros','comoros.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','congo','congo.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','costarica','costarica.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','croatia','croatia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','cuba','cuba.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','cyprus','cyprus.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','czech republic','czechrepublic.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','demrepcongo','demrepcongo.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','denmark','denmark.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','djibouti','djibouti.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','dominica','dominica.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','dominican rep','dominicanrep.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ecuador','ecuador.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','egypt','egypt.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','elsalvador','elsalvador.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','england','england.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','eq guinea','eq_guinea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','eritrea','eritrea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','estonia','estonia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ethiopia','ethiopia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','europe','europe.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','fiji','fiji.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','finland','finland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','france','france.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','gabon','gabon.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','gambia','gambia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','georgia','georgia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','germany','germany.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ghana','ghana.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','greece','greece.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','grenada','grenada.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','grenadines','grenadines.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','guatemala','guatemala.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','guinea','guinea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','guineabissau','guineabissau.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','guyana','guyana.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','haiti','haiti.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','honduras','honduras.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','hong kong','hong_kong.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','hungary','hungary.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','iceland','iceland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','india','india.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','indonesia','indonesia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','iran','iran.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','iraq','iraq.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ireland','ireland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','israel','israel.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','italy','italy.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ivory coast','ivorycoast.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','jamaica','jamaica.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','japan','japan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','jordan','jordan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','kazakhstan','kazakhstan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','kenya','kenya.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','kiribati','kiribati.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','kuwait','kuwait.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','kyrgyzstan','kyrgyzstan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','laos','laos.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','latvia','latvia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','lebanon','lebanon.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','liberia','liberia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','libya','libya.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','liechtenstein','liechtenstein.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','lithuania','lithuania.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','luxembourg','luxembourg.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','macadonia','macadonia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','macau','macau.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','madagascar','madagascar.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','malawi','malawi.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','malaysia','malaysia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','maldives','maldives.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mali','mali.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','malta','malta.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mauritania','mauritania.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mauritius','mauritius.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mexico','mexico.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','micronesia','micronesia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','moldova','moldova.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','monaco','monaco.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mongolia','mongolia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','morocco','morocco.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','mozambique','mozambique.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','namibia','namibia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','nauru','nauru.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','nepal','nepal.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','neth antilles','neth_antilles.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','netherlands','netherlands.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','new zealand','newzealand.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','nicaragua','nicaragua.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','niger','niger.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','nigeria','nigeria.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','north korea','north_korea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','norway','norway.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','oman','oman.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','pakistan','pakistan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','palestine','palestine.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','panama','panama.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','papua newguinea','papuanewguinea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','paraguay','paraguay.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','peru','peru.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','philippines','philippines.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','poland','poland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','portugal','portugal.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','puertorico','puertorico.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','qatar','qatar.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','quebec','quebec.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','rawanda','rawanda.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','romania','romania.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','russia','russia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','sao tome','sao_tome.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','saudiarabia','saudiarabia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','scotland','scotland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','senegal','senegal.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','serbia','serbia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','seychelles','seychelles.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','sierraleone','sierraleone.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','singapore','singapore.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','slovakia','slovakia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','slovenia','slovenia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','solomon islands','solomon_islands.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','somalia','somalia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','south_korea','south_korea.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','south africa','southafrica.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','spain','spain.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','srilanka','srilanka.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','stkitts nevis','stkitts_nevis.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','stlucia','stlucia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','sudan','sudan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','suriname','suriname.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','sweden','sweden.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','switzerland','switzerland.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','syria','syria.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','taiwan','taiwan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','tajikistan','tajikistan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','tanzania','tanzania.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','thailand','thailand.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','togo','togo.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','tonga','tonga.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','trinidad and tobago','trinidadandtobago.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','tunisia','tunisia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','turkey','turkey.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','turkmenistan','turkmenistan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','tuvala','tuvala.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','uae','uae.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','uganda','uganda.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','uk','uk.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ukraine','ukraine.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','uruguay','uruguay.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','usa','usa.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','ussr','ussr.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','uzbekistan','uzbekistan.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','vanuatu','vanuatu.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','venezuela','venezuela.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','vietnam','vietnam.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','wales','wales.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','western samoa','western_samoa.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','yemen','yemen.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','yugoslavia','yugoslavia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','zaire','zaire.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','zambia','zambia.gif');
INSERT INTO phpbb_flags (flag_id, flag_name, flag_image) VALUES ('','zimbabwe','zimbabwe.gif');

#
# Dumping data for table phpbb_block_config
#

INSERT INTO phpbb_block_config VALUES (1, 'default_layout', '1');
INSERT INTO phpbb_block_config VALUES (2, 'left_width', '150');
INSERT INTO phpbb_block_config VALUES (3, 'right_width', '150');
INSERT INTO phpbb_block_config VALUES (4, 'md_poll_bar_length', '40');
INSERT INTO phpbb_block_config VALUES (5, 'md_poll_forum_id', '1');
INSERT INTO phpbb_block_config VALUES (6, 'md_num_recent_topics', '10');
INSERT INTO phpbb_block_config VALUES (7, 'md_approve_mod_installed', '0');
INSERT INTO phpbb_block_config VALUES (8, 'md_recent_topics_style', '0');
INSERT INTO phpbb_block_config VALUES (9, 'md_search_option_text', 'Forum');

#
# Dumping data for table phpbb_block_layout
#

INSERT INTO phpbb_block_layout VALUES (1, 'Default', 0, '');
INSERT INTO phpbb_block_layout VALUES (2, 'Profile', 0, '');
INSERT INTO phpbb_block_layout VALUES (3, 'Forum', 0, '');

#
# Dumping data for table phpbb_block_position
#

INSERT INTO phpbb_block_position VALUES (1, 'top', 't', 0);
INSERT INTO phpbb_block_position VALUES (2, 'left', 'l', 0);
INSERT INTO phpbb_block_position VALUES (3, 'right', 'r', 0);
INSERT INTO phpbb_block_position VALUES (4, 'bottom', 'b', 0);

#
# Dumping data for table phpbb_block_variable
#
INSERT INTO phpbb_block_variable VALUES (1, 'Left width', 'Width of forum-wide left column in pixels', 'left_width', '', '', 1, '@Portal Config');
INSERT INTO phpbb_block_variable VALUES (2, 'Right width', 'Width of forum-wide right column in pixels', 'right_width', '', '', 1, '@Portal Config');
INSERT INTO phpbb_block_variable VALUES (3, 'Poll Bar Length', 'decrease/increase the value for 1 vote bar length', 'md_poll_bar_length', '', '', 1, 'poll');
INSERT INTO phpbb_block_variable VALUES (4, 'Poll Forum ID(s)', 'comma delimited', 'md_poll_forum_id', '', '', 1, 'poll');
INSERT INTO phpbb_block_variable VALUES (5, 'Number of recent topics', 'number of topics displayed', 'md_num_recent_topics', '', '', 1, 'recent_topics');
INSERT INTO phpbb_block_variable VALUES (6, 'Approve MOD installed?', 'tick if Approve MOD is installed', 'md_approve_mod_installed', '', '', 4, 'recent_topics');
INSERT INTO phpbb_block_variable VALUES (7, 'Recent Topics Style', 'choose static display or scrolling display', 'md_recent_topics_style', 'Scroll,Static', '1,0', 3, 'recent_topics');
INSERT INTO phpbb_block_variable VALUES (8, 'Search option text', 'Text displayed as the default option', 'md_search_option_text', '', '', 1, 'search');

#
# Dumping data for table phpbb_blocks
#

INSERT INTO phpbb_blocks VALUES (1, 'Navigation', '', 'l', 1, 1, 'blocks_imp_menu', 0, 1, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (2, 'Statistics', '', 'l', 2, 1, 'blocks_imp_statistics', 0, 1, '', 1, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (3, 'Poll', '', 'l', 3, 1, 'blocks_imp_poll', 0, 1, '', 1, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (4, 'Welcome to Minerva R3', '<table cellspacing="0" cellpadding="0" border="0" width="100%">\r\n<tr>\r\n<td class="row1" align="center"><span class="gen">\r\nWelcome to Minerva R3<br /><br />For questions, comments and suggestions, please visit <a href="http://www.project-minerva.org">Project Minerva</a>\r\n<br />\r\n<br />\r\n</span>\r\n</td>\r\n</tr>\r\n</table>', 't', 1, 1, '', 0, 1, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (5, 'Profile', '', 'r', 1, 1, 'blocks_imp_user_block', 0, 1, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (6, 'Who Is Online', '', 'r', 2, 1, 'blocks_imp_online_users', 0, 1, 1, 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (7, 'Links', '<table width="100%" cellpadding="0" cellspacing="1" border="0">\r\n<tr>\r\n<td class="row1" align="center"><a href="http://www.project-minerva.org" target="_blank" class="gen">Project Minerva</a></td>\r\n</tr>\r\n</table>', 'r', 3, 1, '', 0, 1, '', 0, 1, 1, 1, 0, '');

INSERT INTO phpbb_blocks VALUES (8, 'Navigation', '', 'l', 1, 1, 'blocks_imp_menu', 0, 2, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (9, 'Profile', '', 'l', 2, 1, 'blocks_imp_user_block', 0, 2, '', 0, 1, 1, 1, 0, '');

INSERT INTO phpbb_blocks VALUES (10, 'Navigation', '', 'l', 1, 1, 'blocks_imp_menu', 0, 3, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (11, 'Recent Posts', '', 'l', 2, 1, 'blocks_imp_recent_topics', 0, 3, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (12, 'Search', '', 'l', 3, 1, 'blocks_imp_search', 0, 3, '', 0, 1, 1, 1, 0, '');
INSERT INTO phpbb_blocks VALUES (13, 'Poll', '', 'l', 4, 1, 'blocks_imp_poll', 0, 3, '', 0, 1, 1, 1, 0, '');

INSERT INTO phpbb_menu_links VALUES (1, 'icon_mini_home.gif', 'profilcp_index_shortcut', '', 'index', -1, 10);
INSERT INTO phpbb_menu_links VALUES (2, 'icon_mini_forums.gif', 'Forum', '', 'forum', -1, 20);
INSERT INTO phpbb_menu_links VALUES (3, 'icon_mini_search.gif', 'Search', '', 'search', -1, 30);
INSERT INTO phpbb_menu_links VALUES (4, 'icon_mini_profile.gif', 'profilcp_profil_shortcut', 'mode=editprofile', 'profile', 0, 40);
INSERT INTO phpbb_menu_links VALUES (5, 'icon_minilink.gif', 'Prillian', '', 'javascript:prill_launch(\'imclient.php?mode=4&amp;mode2=1&amp;mode_switch=1\', \'256\', \'448\')', 0, 50);
INSERT INTO phpbb_menu_links VALUES (6, 'icon_mini_members.gif', 'Rules', '', 'rules', -1, 60);
INSERT INTO phpbb_menu_links VALUES (7, 'icon_mini_faq.gif', 'FAQ', '', 'faq', -1, 70);
INSERT INTO phpbb_menu_links VALUES (8, 'icon_mini_members.gif', 'Memberlist', '', 'memberlist', -1, 80);
INSERT INTO phpbb_menu_links VALUES (9, 'icon_mini_groups.gif', 'Usergroups', '', 'groupcp', -1, 90);
INSERT INTO phpbb_menu_links VALUES (10, 'icon_mini_admin.gif', 'profilcp_admin_shortcut', '', 'admin/index', 1, 100);
INSERT INTO phpbb_menu_links VALUES (11, 'icon_mini_login.gif', 'Logout', 'logout=true', 'login', 0, 110);

#
# Dumping data for table `phpbb_im_sites`
#
INSERT INTO phpbb_im_sites (site_id, site_name, site_url, site_phpex, site_profile, site_enable) VALUES (1, 'DarkMods', 'http://darkmods.sourceforge.net/mb/', 'php', 'profile', 1);

#
# Dumping data for table `phpbb_im_config`
#
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('refresh_rate', '60');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('flood_interval', '15');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('success_close', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('refresh_method', '2');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('auto_launch', '0');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('popup_ims', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('list_ims', '0');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('mode1_height', '400');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('mode1_width', '225');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('read_height', '300');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('read_width', '400');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('send_height', '365');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('send_width', '460');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('list_all_online', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('show_controls', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('allow_ims', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('allow_shout', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('allow_chat', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('override_users', '0');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('enable_flood', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('box_limit', '25');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('refresh_drop', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('play_sound', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('sound_name', '');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('default_sound', '0');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('themes_allow', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('themes_id', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('allow_network', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('session_length', '120');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('enable_im_limit', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('auto_delete', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('network_user_list', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('default_mode', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('prefs_height', '350');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('prefs_width', '500');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('use_frames', '1');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('network_profile', 'profile');
INSERT INTO phpbb_im_config (config_name, config_value) VALUES ('version', '0.7.0');

INSERT INTO phpbb_privmsga_folders VALUES (1, 0, 0, 'Inbox', 10);
INSERT INTO phpbb_privmsga_folders VALUES (2, 0, 0, 'Outbox', 20);
INSERT INTO phpbb_privmsga_folders VALUES (3, 0, 0, 'Sentbox', 30);
INSERT INTO phpbb_privmsga_folders VALUES (4, 0, 0, 'Savebox', 40);

INSERT INTO phpbb_rules VALUES (-2, 'Please read our Terms of Use and Privacy Policy for futher information.', 1089390238, 1);
INSERT INTO phpbb_rules VALUES (-3, '<h1>Terms of Use</h1>\r\n\r\n<h2>1. Acceptance of Terms of Use and Amendments.</h2>\r\n<p>Each time you use or cause access to this web site, you agree to be bound by these Terms of Use, and as amended from time to time with or without notice to you. In addition, if you are using a particular service on or through this web site, you will be subject to any rules or guidelines applicable to those services and they shall be incorporated by reference into these Terms of Use. Please see our Privacy Policy, which is incorporated into these Terms of Use by reference.</p>\r\n\r\n<h2>2. Our Service.</h2>\r\n<p>Our web site and services provided to you on and through our web site on an "AS IS" basis. You agree that the owners of this web site exclusively reserve the right and may, at any time and without notice and any liability to you, modify or discontinue this web site and its services\r\nor delete the data you provide, whether temporarily or permanently. We shall have no responsibilty or liability for the timeliness, deletion, failure to store, inaccuracy, or improper delivery of any data or information.</p>\r\n\r\n<h2>3. Your Responsibilities and Registration Obligations.</h2>\r\n<p>In order to use this web site, you must register on our site, agree to provide truthful information when requested. When registering, you explicitly agree to our Terms of Use and as may be modified by us from time to time and available here.</p>\r\n\r\n<h2>4. Privacy Policy.</h2>\r\n<p>Registration data and other personally identifiable information that we may collect is subject to the terms of our Privacy Policy.</p>\r\n\r\n<h2>5. Registration and Password.</h2>\r\n<p>You are responsible to maintain the confidentiality of your password and shall be responsible for all uses via your registration and/or login, whether authorized or unauthorized by you. You agree to immediately notify us of any unauthorized use or your registration, user account or password.</p>\r\n\r\n<h2>6. Your Conduct.</h2>\r\n<p>You agree that all information or data of any kind, whether text, software, code, music or sound, photographs or graphics, video or other materials ("Content"), publicly or privately provided, shall be the sole responsibility of the person providing the Content or the person whose user account is used. You agree that our web site may expose you to Content that may be objectionable or offensive. We shall not be responsible to you in any way for the Content that appears on this web site nor for any error or omission.</p>\r\n\r\n<p>You explicitly agree, in using this web site or any service provided, that you shall not:\r\n<br />(a) provide any Content or perform any conduct that may be unlawful, illegal, threatening, harmful, abusive, harassing, stalking, tortious, defamatory, libelous, vulgar, obscene, offensive, objectionable, pornographic, designed to or does interfere or interrupt this web site or any service provided, infected with a virus or other destructive or malicious programming routine, give rise to civil or criminal liability, or which may violate an applicable local, national or international law;\r\n<br />(b) impersonate or misrepresent your association with any person or entity, or forge or otherwise seek to conceal or misrepresent the origin of any Content provided by you;\r\n<br />(c) collect or harvest any data about other users;\r\n<br />(d) provide or use this web site and any Content or service in any commercial manner or in any manner that would involve junk mail, spam, chain letters, pyramid schemes, or any other form of unauthorized advertising without our prior written consent;\r\n<br />(e) provide any Content that may give rise to our civil or criminal liability or which may consititue or be considered a violation of any local, national or international law, including but not limited to laws relating to copyright, trademark, patent, or trade secrets.</p>\r\n\r\n<h2>7. Submission of Content on this Web Site.</h2>\r\n<p>By providing any Content to our web site:\r\n<br />(a) you agree to grant
to us a worldwide, royalty-free, perpetual, non-exclusive right and license (including any moral rights or other necessary rights) to use, display, reproduce, modify, adapt, publish, distribute, perform, promote, archive, translate, and to create derivative works and compilations, in whole or in part. Such license will apply with respect to any form, media, technology known or later developed;\r\n<br />(b) you warrant and represent that you have all legal, moral, and other rights that may be necessary to grant us with the license set forth in this Section 7;\r\n<br />(c) you acknowledge and agree that we shall have the right (but not obligation), in our sole discretion, to refuse to publish or to remove or block access to any Content you provide at any time and for any reason, with or without notice.</p>\r\n\r\n<h2>8. Third Party Services.</h2>\r\n<p>Goods and services of third parties may be advertised and/or made available on or through this web site. Representations made regarding products and services provided by third parties are governed by the policies and representations made by these third parties. We shall not be liable for or responsible in any manner for any of your dealings or interaction with third parties.</p>\r\n\r\n<h2>9. Indemnification.</h2>\r\n<p>You agree to indemnify and hold us harmless, our subsidiaries, affiliates, related parties, officers, directors, employees, agents, independent contractors, advertisers, partners, and co-branders from any claim or demand, including reasonable attorney\'s fees, that may be made by any third party, that is due to or arising out of your conduct or connection with this web site or service, your provision of Content, your violation of this Terms of Use or any other violation of the rights of another person or party.</p>\r\n\r\n<p><b>10. DISCLAIMER OF WARRANTIES. YOU UNDERSTAND AND AGREE THAT YOUR USE OF THIS WEB SITE AND ANY SERVICES OR CONTENT PROVIDED (THE "SERVICE") IS MADE AVAILABLE AND PROVIDED TO YOU AT YOUR OWN RISK. IT IS PROVIDED TO YOU "AS IS" AND WE EXPRESSLY DISCLAIM ALL WARRANTIES OF ANY KIND, IMPLIED OR EXPRESS, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.</b></p>\r\n\r\n<p><b>WE MAKE NO WARRANTY, IMPLIED OR EXPRESS, THAT ANY PART OF THE SERVICE WILL BE UNINTERRUPTED, ERROR-FREE, VIRUS-FREE, TIMELY, SECURE, ACCURATE, RELIABLE, OF ANY QUALITY, NOR THAT ANY CONTENT IS SAFE IN ANY MANNER FOR DOWNLOAD. YOU UNDERSTAND AND AGREE THAT NEITHER US NOR ANY PARTICIPANT IN THE SERVICE PROVIDES PROFESSIONAL ADVICE OF ANY KIND AND THAT USE OF SUCH ADVICE OR ANY OTHER INFORMATION IS SOLELY AT YOUR OWN RISK AND WITHOUT OUR LIABILITY OF ANY KIND.</b></p>\r\n\r\n<p>Some jurisdictions may not allow disclaimers of implied warranties and the above disclaimer may not apply to you only as it relates to implied warranties.</p>\r\n\r\n<p><b>11. LIMITATION OF LIABILITY. YOU EXPRESSLY UNDERSTAND AND AGREE THAT WE SHALL NOT BE LIABLE FOR ANY DIRECT, INDIRECT, SPECIAL, INDICENTAL, CONSEQUENTIAL OR EXEMPLARY DAMAGES, INCLUDING BUT NOT LIMITED TO, DAMAGES FOR LOSS OF PROFITS, GOODWILL, USE, DATA OR OTHER INTANGIBLE LOSS (EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES), RESULTING FROM OR ARISING OUT OF (I) THE USE OF OR THE INABILITY TO USE THE SERVICE, (II) THE COST TO OBTAIN SUBSTITUTE GOODS AND/OR SERVICES RESULTING FROM ANY TRANSACTION ENTERED INTO ON THROUGH THE SERVICE, (III) UNAUTHORIZED ACCESS TO OR ALTERATION OF YOUR DATA TRANSMISSIONS, (IV) STATEMENTS OR CONDUCT OF ANY THIRD PARTY ON THE SERVICE, OR (V) ANY OTHER MATTER RELATING TO THE SERVICE.</b></p>\r\n\r\n<p>In some jurisdictions, it is not permitted to limit liability and therefore such limitations may not apply to you.</p>\r\n\r\n<h2>12. Reservation of Rights.</h2>\r\n<p>We reserve all of our rights, including but not limited to any and all copyrights, trademarks, patents, trade secrets, and any other proprietary right that we may have in our web site, its content, and the goods and services that may be provided. The use of
our rights and property requires our prior written consent. We are not providing you with any implied or express licenses or rights by making services available to you and you will have no rights to make any commercial uses of our web site or service without our prior written consent.</p>\r\n\r\n<h2>13. Notification of Copyright Infringement.</h2>\r\n<p>If you believe that your property has been used in any way that would be considered copyright infringement or a violation of your intellectual property rights, please feel free to contact our webmaster.</p>\r\n\r\n<h2>14. Applicable Law.</h2>\r\n<p>You agree that this Terms of Use and any dispute arising out of your use of this web site or our products or services shall be governed by and construed in accordance with local laws where the headquarters of the owner of this web site is located, without regard to its conflict of law provisions. By registering or using this web site and service you consent and submit to the exclusive jurisdiction and venue of the county or city where the headquarters of the owner of this web site is located.</p>\r\n\r\n<h2>15. Miscellaneous Information.</h2>\r\n<p>(i) In the event that this Terms of Use conflicts with any law under which any provision may be held invalid by a court with jurisdiction over the parties, such provision will be interpreted to reflect the original intentions of the parties in accordance with applicable law, and the remainder of this Terms of Use will remain valid and intact; (ii) The failure of either party to assert any right under this Terms of Use shall not be considered a waiver of any that party\'s right and that right will remain in full force and effect; (iii) You agree that without regard to any statue or contrary law that any claim or cause arising out of this web site or its services must be filed within one (1) year after such claim or cause arose or the claim shall be forever barred;\r\n(iv) We may assign our rights and obligations under this Terms of Use and we shall be relieved of any further obligation.</p>', 1091813043, 1);
INSERT INTO phpbb_rules VALUES (-4, '<h1>Privacy Policy</h1>\r\n\r\n<h2>Introduction.</h2>\r\n<p>We take your right to privacy seriously and want you to feel comfortable using our web site. This Privacy Policy deals with personally identifiable information (referred to as "Data" below) that may be collected by us on our site. This Policy does not apply to other entities that we do not own or control or persons that are not our employees, agents or within our control. Please take time to read our Terms of Use.</p>\r\n\r\n<h2>1. Collection of Data.</h2>\r\n<p>Our registration process requires only a valid e-mail address and a unique user ID and password. Providing us with other information is at your option. Please take note that your user name, e-mail address, or other submissions that you make on this site may contain your real name or other personally identifiable information and, as a result, may appear on this site. Like many web sites, we may also automatically receive general information that is contained in our server log files such as your IP address and cookie information. Information about how advertising may be served on this site (if at all) is set forth below.</p>\r\n\r\n<h2>2. Use of Data.</h2>\r\n<p>We may use Data to customize and improve your user experience on this site. We will make efforts so that your Data will not be provided to third parties unless (i) provided for otherwise in this Privacy Policy; (ii) we obtain your consent, such as when you choose to opt-in or opt-out to the sharing of Data; (iii) a service provided on our site requires the interaction with or is provided by a third party, by way of example an Application Service Provider; (iv) pursuant to legal process or law enforcement; (v) we find that your use of this site violates our this Policy, Terms of Service, other usage guidelines or as deemed reasonably necessary by us to protect our legal rights and/or property; (vi) or (vi) this site is purchased by a third party whereby that third party will be able to use the Data in the same manner as we can as set\r\nforth in this Policy. In the event you choose to use links that appear on our site to visit other web sites, you are advised to read the privacy policies that appear on those sites.</p>\r\n\r\n<h2>3. Cookies.</h2>\r\n<p>Like many web sites, we set and use cookies to enhance your user experience, such as retaining your personal settings. Advertisements may appear on our web site and, if so, may set and access cookies on your computer and is subject to the privacy policy of those parties providing the advertisement. However, the companies serving the advertising do not have access to our cookies. These companies usually use non-personally identifiable or anonymous codes to obtain information about your visits to our site.</p>\r\n\r\n<h2>4. Editing or Deleting Your Account Information.</h2>\r\n<p>We provide you with the ability to edit the information in your user account information that you provided to us in registration by using your profile configuration area. You may request the deletion of your user account through this page or by contacting our webmaster. Content or other data that you may have provided to us and that is not contained within your user account, such as posts that may appear within our forums, may continue to remain on our site at our discretion even though your user account is deleted. Please see our Terms of Use for more information.</p>\r\n\r\n<h2>5. Changes to this Privacy Policy.</h2>\r\n<p>We may make changes to this Policy from time to time. We will notify you of substantial changes to this Policy either by posting a prominent announcement on our site and/or by sending a message to the e-mail address you have provided to us that is contained within your user settings.</p>\r\n\r\n<h2>6. NO GUARANTEES.</h2>\r\n<p>While this Privacy Policy states our standards for maintenance of Data and we will make efforts to meet them, we are not in a position to guarantee these standards. There may be factors beyond our control that may result in disclosure of data. As
a consequence, we disclaim any warranties or representations relating to maintenance or nondisclosure of Data.</p>\r\n\r\n<h2>7. Contact Information.</h2>\r\n<p>If you have any questions about this Policy or our web site, please feel free to contact our webmaster.</p>', 1091813065, 1);
INSERT INTO phpbb_rules VALUES (-5, 'All logos and trademarks are the property of their respective owners. Unless otherwise stated all content is under copyright of this site, except posts and comments which are property of their posters.', 1089390192, 1);