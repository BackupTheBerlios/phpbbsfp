<!-- $Id: board_config_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post"><table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
    <tr>
      <th class="thHead" colspan="2">{L_GENERAL_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_SERVER_NAME}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="40" name="server_name" value="{SERVER_NAME}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SERVER_PORT}<br /><span class="gensmall">{L_SERVER_PORT_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="server_port" value="{SERVER_PORT}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SCRIPT_PATH}<br /><span class="gensmall">{L_SCRIPT_PATH_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" name="script_path" value="{SCRIPT_PATH}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SITE_NAME}<br /><span class="gensmall">{L_SITE_NAME_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="25" maxlength="100" name="sitename" value="{SITENAME}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SITE_DESCRIPTION}</td>
        <td class="row2"><input class="post" type="text" size="40" maxlength="255" name="site_desc" value="{SITE_DESCRIPTION}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_REG}<br /><span class="gensmall">{L_DISABLE_REG_EXPLAIN}</span></td>
        <td class="row2">
            <input type="radio" name="disable_reg" value="1" {S_DISABLE_REG_YES} />{L_YES}
            <input type="radio" name="disable_reg" value="0" {S_DISABLE_REG_NO} />{L_NO}
        </td>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_REG_MSG}<br /><span class="gensmall">{L_DISABLE_REG_MSG_EXPLAIN}</span></td>
        <td class="row2">
            <input type="text" class="post" size="40" maxlength="255" name="disable_reg_msg" value="{DISABLE_REG_MSG}"/>
        </td>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_BOARD}<br /><span class="gensmall">{L_DISABLE_BOARD_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="board_disable" value="1" {S_DISABLE_BOARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_disable" value="0" {S_DISABLE_BOARD_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_BOARD_MSG}<br /><span class="gensmall">{L_DISABLE_BOARD_MSG_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="40" name="board_disable_msg" value="{DISABLE_BOARD_MSG}" /></td></td>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_BOARD_ADMINVIEW}<br /><span class="gensmall">{L_DISABLE_BOARD_ADMINVIEW_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="board_disable_adminview" value="1" {S_DISABLE_BOARD_ADMINVIEW_YES} /> {L_YES}  <input type="radio" name="board_disable_adminview" value="0" {S_DISABLE_BOARD_ADMINVIEW_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_ACCT_ACTIVATION}</td>
        <td class="row2"><input type="radio" name="require_activation" value="{ACTIVATION_NONE}" {ACTIVATION_NONE_CHECKED} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_USER}" {ACTIVATION_USER_CHECKED} />{L_USER}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_ADMIN}" {ACTIVATION_ADMIN_CHECKED} />{L_ADMIN}</td>
    </tr>
    <tr>
        <td class="row1">{L_BOARD_EMAIL_FORM}<br /><span class="gensmall">{L_BOARD_EMAIL_FORM_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="board_email_form" value="1" {BOARD_EMAIL_FORM_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="board_email_form" value="0" {BOARD_EMAIL_FORM_DISABLE} /> {L_DISABLED}</td>
    </tr>

<!-- REMOVED TO AVOID DUPLICATION IN Configuration +
    <tr>
        <td class="row1">{L_DEFAULT_STYLE}</td>
        <td class="row2">{STYLE_SELECT}</td>
    </tr>
    <tr>
        <td class="row1">{L_OVERRIDE_STYLE}<br /><span class="gensmall">{L_OVERRIDE_STYLE_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="override_user_style" value="1" {OVERRIDE_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_user_style" value="0" {OVERRIDE_STYLE_NO} /> {L_NO}</td>
    </tr>

    <tr>
        <td class="row1">{L_DEFAULT_LANGUAGE}</td>
        <td class="row2">{LANG_SELECT}</td>
    </tr>
    <tr>
        <td class="row1">{L_DATE_FORMAT}<br /><span class="gensmall">{L_DATE_FORMAT_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" name="default_dateformat" value="{DEFAULT_DATEFORMAT}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SYSTEM_TIMEZONE}</td>
        <td class="row2">{TIMEZONE_SELECT}</td>
    </tr>
-->
    <tr>
        <td class="row1">{L_ENABLE_GZIP}</td>
		<td class="row2">
			<!-- IF S_ZLIB_LOADED -->
		        <input type="radio" name="gzip_compress" value="1" {GZIP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gzip_compress" value="0" {GZIP_NO} /> {L_NO}
			<!-- ELSE -->
				<span class="genmed">{L_NOT_AVAILABLE}</span>
			<!-- ENDIF -->
		</td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_AUTH_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_GUEST}</td>
        <td class="row2"><input type="radio" name="disable_guest" value="1" {DISABLE_GUEST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_guest" value="0" {DISABLE_GUEST_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUTH_MODE}</td>
        <td class="row2">{AUTH_MODE_SELECT}</td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_HOST}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="40" name="ldap_host" value="{LDAP_HOST}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_PORT}</td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="ldap_port" value="{LDAP_PORT}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_HOST2}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="40" name="ldap_host2" value="{LDAP_HOST2}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_PORT2}</td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="ldap_port2" value="{LDAP_PORT2}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_DN}<br /><span class="gensmall">{L_LDAP_DN_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="60" name="ldap_dn" value="{LDAP_DN}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_UID}<br/><span class="gensmall">{L_LDAP_UID_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="60" name="ldap_uid" value="{LDAP_UID}" /></td>
    </tr>

    <tr>
        <td class="row1">{L_LDAP_GROUP_SYNC}</td>
        <td class="row2"><input type="radio" name="ldap_group_sync" value="1" {ENABLE_LDAP_GROUP_SYNC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ldap_group_sync" value="0" {ENABLE_LDAP_GROUP_SYNC_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_GID}<br/><span class="gensmall">{L_LDAP_GID_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="60" name="ldap_gid" value="{LDAP_GID}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_EMAIL}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="25" name="ldap_email" value="{LDAP_EMAIL}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_WEB}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="25" name="ldap_web" value="{LDAP_WEB}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_LOCATION}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="25" name="ldap_location" value="{LDAP_LOCATION}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_OCCUPATION}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="25" name="ldap_occupation" value="{LDAP_OCCUPATION}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_SIGNATURE}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="25" name="ldap_signature" value="{LDAP_SIGNATURE}" /></td>
    </tr>

    <tr>
        <td class="row1">{L_LDAP_PROXY_DN}<br /><span class="gensmall">{L_LDAP_PROXY_DN_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="255" size="60" name="ldap_proxy_dn" value="{LDAP_PROXY_DN}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_PROXY_DN_PASS}<br /><span class="gensmall">{L_LDAP_PROXY_DN_PASS_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="password" maxlength="255" size="60" name="ldap_proxy_dn_pass" value="{LDAP_PROXY_DN_PASS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_LDAP_START_TLS}</td>
        <td class="row2"><input type="radio" name="ldap_start_tls" value="1" {TLS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="ldap_start_tls" value="0" {TLS_NO} /> {L_NO}</td>
    </tr>

    <tr>
        <th class="thHead" colspan="2">{L_META_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row2" colspan="2"><span class="gensmall">{L_META_SETTINGS_EXPLAIN}</span></td>
    </tr>
    <tr>
        <td class="row1">{L_META_KEYWORDS}<br /><span class="gensmall">{L_META_KEYWORDS_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_keywords" value="{META_KEYWORDS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_DESCRIPTION}<br /><span class="gensmall">{L_META_DESCRIPTION_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_description" value="{META_DESCRIPTION}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_REVISIT}<br /><span class="gensmall">{L_META_REVISIT_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_revisit" value="{META_REVISIT}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_AUTHOR}<br /><span class="gensmall">{L_META_AUTHOR_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_author" value="{META_AUTHOR}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_DISTRIBUTION}<br /><span class="gensmall">{L_META_DISTRIBUTION_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_distribution" value="{META_DISTRIBUTION}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_OWNER}<br /><span class="gensmall">{L_META_OWNER_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_owner" value="{META_OWNER}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_ROBOTS}<br /><span class="gensmall">{L_META_ROBOTS_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_robots" value="{META_ROBOTS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_META_ABSTRACT}<br /><span class="gensmall">{L_META_ABSTRACT_EXPLAIN}</span></td>
        <td class="row2"><input type="text" maxlength="255" name="meta_abstract" value="{META_ABSTRACT}" /></td>
    </tr>

    <tr>
        <th class="thHead" colspan="2">{L_COOKIE_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row2" colspan="2"><span class="gensmall">{L_COOKIE_SETTINGS_EXPLAIN}</span></td>
    </tr>
    <tr>
        <td class="row1">{L_COOKIE_DOMAIN}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" name="cookie_domain" value="{COOKIE_DOMAIN}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_COOKIE_NAME}</td>
        <td class="row2"><input class="post" type="text" maxlength="16" name="cookie_name" value="{COOKIE_NAME}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_COOKIE_PATH}</td>
        <td class="row2"><input class="post" type="text" maxlength="255" name="cookie_path" value="{COOKIE_PATH}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_COOKIE_SECURE}<br /><span class="gensmall">{L_COOKIE_SECURE_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="cookie_secure" value="0" {S_COOKIE_SECURE_DISABLED} />{L_DISABLED}&nbsp; &nbsp;<input type="radio" name="cookie_secure" value="1" {S_COOKIE_SECURE_ENABLED} />{L_ENABLED}</td>
    </tr>
    <tr>
        <td class="row1">{L_SESSION_LENGTH}</td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="session_length" value="{SESSION_LENGTH}" /></td>
    </tr>
    <tr>
        <th class="thHead" colspan="2">{L_FORUM_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_ENABLE_PRUNE}</td>
        <td class="row2"><input type="radio" name="prune_enable" value="1" {PRUNE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="prune_enable" value="0" {PRUNE_NO} /> {L_NO}</td>
    </tr>
    <tr> 
        <td class="row1">{L_BLUECARD_LIMIT_2}<br /><span class="gensmall">{L_BLUECARD_LIMIT_2_EXPLAIN}</span></td> 
        <td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit_2" value="{BLUECARD_LIMIT_2}" /></td> 
    </tr> 
    <tr> 
        <td class="row1">{L_BLUECARD_LIMIT}<br /><span class="gensmall">{L_BLUECARD_LIMIT_EXPLAIN}</span></td> 
        <td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit" value="{BLUECARD_LIMIT}" /></td> 
    </tr> 
    <tr> 
        <td class="row1">{L_MAX_USER_BANCARD}<br /><span class="gensmall">{L_MAX_USER_BANCARD_EXPLAIN}</span></td> 
        <td class="row2"><input class="post" type="text" size="4" maxlength="4" name="max_user_bancard" value="{MAX_USER_BANCARD}" /></td> 
    </tr> 
    <tr> 
        <td class="row1">{L_REPORT_FORUM}<br /><span class="gensmall">{L_REPORT_FORUM_EXPLAIN}</span></td> 
        <td class="row2">{S_REPORT_FORUM}</td> 
    </tr>
    <tr>
        <td class="row1">{L_FLOOD_INTERVAL} <br /><span class="gensmall">{L_FLOOD_INTERVAL_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="3" maxlength="4" name="flood_interval" value="{FLOOD_INTERVAL}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_TOPICS_PER_PAGE}</td>
        <td class="row2"><input class="post" type="text" name="topics_per_page" size="3" maxlength="4" value="{TOPICS_PER_PAGE}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_POSTS_PER_PAGE}</td>
        <td class="row2"><input class="post" type="text" name="posts_per_page" size="3" maxlength="4" value="{POSTS_PER_PAGE}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_HOT_THRESHOLD}</td>
        <td class="row2"><input class="post" type="text" name="hot_threshold" size="3" maxlength="4" value="{HOT_TOPIC}" /></td>
    </tr>


    <tr>
        <th class="thHead" colspan="2">{L_PRIVATE_MESSAGING}</th>
    </tr>
    <tr>
        <td class="row1">{L_DISABLE_PRIVATE_MESSAGING}</td>
        <td class="row2"><input type="radio" name="privmsg_disable" value="0" {S_PRIVMSG_ENABLED} />{L_ENABLED}&nbsp; &nbsp;<input type="radio" name="privmsg_disable" value="1" {S_PRIVMSG_DISABLED} />{L_DISABLED}</td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_ABILITIES_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_MAX_POLL_OPTIONS}</td>
        <td class="row2"><input class="post" type="text" name="max_poll_options" size="4" maxlength="4" value="{MAX_POLL_OPTIONS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_HTML}</td>
        <td class="row2"><input type="radio" name="allow_html" value="1" {HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {HTML_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOWED_TAGS}<br /><span class="gensmall">{L_ALLOWED_TAGS_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="30" maxlength="255" name="allow_html_tags" value="{HTML_TAGS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_BBCODE}</td>
        <td class="row2"><input type="radio" name="allow_bbcode" value="1" {BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {BBCODE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_SMILIES}</td>
        <td class="row2"><input type="radio" name="allow_smilies" value="1" {SMILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {SMILE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_SMILIES_PATH} <br /><span class="gensmall">{L_SMILIES_PATH_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="20" maxlength="255" name="smilies_path" value="{SMILIES_PATH}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_SIG}</td>
        <td class="row2"><input type="radio" name="allow_sig" value="1" {SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_sig" value="0" {SIG_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_MAX_SIG_LENGTH}<br /><span class="gensmall">{L_MAX_SIG_LENGTH_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="5" maxlength="4" name="max_sig_chars" value="{SIG_SIZE}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_NAME_CHANGE}</td>
        <td class="row2"><input type="radio" name="allow_namechange" value="1" {NAMECHANGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_namechange" value="0" {NAMECHANGE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_CUSTOM_TITLE_MODE}<br /><span class="gensmall">{L_CUSTOM_TITLE_MODE_EXPLAIN}</span></td>
        <td class="row2" nowrap="nowrap">
            <input type="radio" name="custom_title_mode" value="0" {CUSTOM_TITLE_MODE_INDEPENDENT} />{L_CUSTOM_TITLE_MODE_INDEPENDENT}&nbsp;
            <input type="radio" name="custom_title_mode" value="1" {CUSTOM_TITLE_MODE_REPLACE_RANK} />{L_CUSTOM_TITLE_MODE_REPLACE_RANK}&nbsp;
            <input type="radio" name="custom_title_mode" value="2" {CUSTOM_TITLE_MODE_REPLACE_BOTH} />{L_CUSTOM_TITLE_MODE_REPLACE_BOTH}
        </td>
    </tr>
    <tr>
        <td class="row1">{L_CUSTOM_TITLE_DAYS}</td>
        <td class="row2"><input type="text" name="custom_title_days" value="{CUSTOM_TITLE_DAYS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_CUSTOM_TITLE_POSTS}</td>
        <td class="row2"><input type="text" name="custom_title_posts" value="{CUSTOM_TITLE_POSTS}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_CUSTOM_TITLE_MAXLENGTH}<br /><span class="gensmall">{L_CUSTOM_TITLE_MAXLENGTH_EXPLAIN}</span></td>
        <td class="row2"><input type="text" name="custom_title_maxlength" value="{CUSTOM_TITLE_MAXLENGTH}" /></td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_AVATAR_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_LOCAL}</td>
        <td class="row2"><input type="radio" name="allow_avatar_local" value="1" {AVATARS_LOCAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_local" value="0" {AVATARS_LOCAL_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_REMOTE} <br /><span class="gensmall">{L_ALLOW_REMOTE_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="allow_avatar_remote" value="1" {AVATARS_REMOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_remote" value="0" {AVATARS_REMOTE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_ALLOW_UPLOAD}</td>
        <td class="row2"><input type="radio" name="allow_avatar_upload" value="1" {AVATARS_UPLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_upload" value="0" {AVATARS_UPLOAD_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_MAX_FILESIZE}<br /><span class="gensmall">{L_MAX_FILESIZE_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="4" maxlength="10" name="avatar_filesize" value="{AVATAR_FILESIZE}" /> Bytes</td>
    </tr>
    <tr>
        <td class="row1">{L_MAX_AVATAR_SIZE} <br />
            <span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span>
        </td>
        <td class="row2"><input class="post" type="text" size="3" maxlength="4" name="avatar_max_height" value="{AVATAR_MAX_HEIGHT}" /> x <input class="post" type="text" size="3" maxlength="4" name="avatar_max_width" value="{AVATAR_MAX_WIDTH}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AVATAR_STORAGE_PATH} <br /><span class="gensmall">{L_AVATAR_STORAGE_PATH_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="20" maxlength="255" name="avatar_path" value="{AVATAR_PATH}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_AVATAR_GALLERY_PATH} <br /><span class="gensmall">{L_AVATAR_GALLERY_PATH_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" size="20" maxlength="255" name="avatar_gallery_path" value="{AVATAR_GALLERY_PATH}" /></td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_COPPA_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_COPPA_FAX}</td>
        <td class="row2"><input class="post" type="text" size="25" maxlength="100" name="coppa_fax" value="{COPPA_FAX}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_COPPA_MAIL}<br /><span class="gensmall">{L_COPPA_MAIL_EXPLAIN}</span></td>
        <td class="row2"><textarea name="coppa_mail" rows="5" cols="30">{COPPA_MAIL}</textarea></td>
    </tr>

    <tr>
      <th class="thHead" colspan="2">{L_EMAIL_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1">{L_ADMIN_EMAIL}</td>
        <td class="row2"><input class="post" type="text" size="25" maxlength="100" name="board_email" value="{EMAIL_FROM}" /></td>
    </tr>
    <tr>
        <td class="row1">{L_EMAIL_SIG}<br /><span class="gensmall">{L_EMAIL_SIG_EXPLAIN}</span></td>
        <td class="row2"><textarea name="board_email_sig" rows="5" cols="30">{EMAIL_SIG}</textarea></td>
    </tr>
    <tr>
        <td class="row1">{L_USE_SMTP}<br /><span class="gensmall">{L_USE_SMTP_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="smtp_delivery" value="1" {SMTP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smtp_delivery" value="0" {SMTP_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_SMTP_SERVER}</td>
        <td class="row2"><input class="post" type="text" name="smtp_host" value="{SMTP_HOST}" size="25" maxlength="50" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SMTP_USERNAME}<br /><span class="gensmall">{L_SMTP_USERNAME_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" name="smtp_username" value="{SMTP_USERNAME}" size="25" maxlength="255" /></td>
    </tr>
    <tr>
        <td class="row1">{L_SMTP_PASSWORD}<br /><span class="gensmall">{L_SMTP_PASSWORD_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="password" name="smtp_password" value="{SMTP_PASSWORD}" size="25" maxlength="255" /></td>
    </tr>
    <tr>
        <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
        </td>
    </tr>
</table></form>

<br clear="all" />
