<?php
//-- mod : language settings -----------------------------------------------------------------------
//-- mod : mods settings ---------------------------------------------------------------------------
//-- mod : sub-template ----------------------------------------------------------------------------
//-- mod : profile cp ------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//
// $Id: functions.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------
function get_db_stat($mode)
{
    global $db;

    switch( $mode )
    {
        case 'usercount':
            $sql = "SELECT COUNT(user_id) AS total
                FROM " . USERS_TABLE . "
                WHERE user_id <> " . ANONYMOUS;
            break;

        case 'newestuser':
            $sql = "SELECT user_id, username
                FROM " . USERS_TABLE . "
                WHERE user_id <> " . ANONYMOUS . "
                ORDER BY user_id DESC
                LIMIT 1";
            break;

        case 'postcount':
        case 'topiccount':
            $sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total
                FROM " . FORUMS_TABLE;
            break;
    }

	    if ( !($result = $db->sql_query($sql)) )
	    {
	        return false;
	    }

	    $row = $db->sql_fetchrow($result);

    switch ( $mode )
    {
        case 'usercount':
            return $row['total'];
            break;
        case 'newestuser':
            return $row;
            break;
        case 'postcount':
            return $row['post_total'];
            break;
        case 'topiccount':
            return $row['topic_total'];
            break;
    }

    return false;
}

//
// Get Userdata, $user can be username or user_id. If force_str is true, the username will be forced.
//
function get_userdata($user, $force_str = false)
{
    global $db;

    if (intval($user) == 0 || $force_str)
    {
        $user = trim(htmlspecialchars($user));
        $user = substr(str_replace("\\'", "'", $user), 0, 25);
        $user = str_replace("'", "\\'", $user);
    }
    else
    {
        $user = intval($user);
    }

    $sql = "SELECT *
        FROM " . USERS_TABLE . "
        WHERE ";
    $sql .= ( ( is_integer($user) ) ? "user_id = $user" : "username = '" .  $user . "'" ) . " AND user_id <> " . ANONYMOUS;
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
    }

    return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}

function make_jumpbox($action, $match_forum_id = 0)
{
    global $template, $userdata, $lang, $db, $nav_links, $phpEx, $SID;

//  $is_auth = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

    $sql = "SELECT c.cat_id, c.cat_title, c.cat_order
        FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
        WHERE f.cat_id = c.cat_id
        GROUP BY c.cat_id, c.cat_title, c.cat_order
        ORDER BY c.cat_order";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, "Couldn't obtain category list.", "", __LINE__, __FILE__, $sql);
    }

    $category_rows = array();
    while ( $row = $db->sql_fetchrow($result) )
    {
        $category_rows[] = $row;
    }

    if ( $total_categories = count($category_rows) )
    {
        $sql = "SELECT *
            FROM " . FORUMS_TABLE . "
            ORDER BY cat_id, forum_order";
        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
        }

        $boxstring = '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"><option value="-1">' . $lang['Select_forum'] . '</option>';

        $forum_rows = array();
        while ( $row = $db->sql_fetchrow($result) )
        {
            $forum_rows[] = $row;
        }

        if ( $total_forums = count($forum_rows) )
        {
            for($i = 0; $i < $total_categories; $i++)
            {
                $boxstring_forums = '';
                for($j = 0; $j < $total_forums; $j++)
                {
                    if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $forum_rows[$j]['auth_view'] <= AUTH_REG )
                    {

//                  if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $is_auth[$forum_rows[$j]['forum_id']]['auth_view'] )
//                  {
                        $selected = ( $forum_rows[$j]['forum_id'] == $match_forum_id ) ? 'selected="selected"' : '';
                        $boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '"' . $selected . '>' . $forum_rows[$j]['forum_name'] . '</option>';

                        //
                        // Add an array to $nav_links for the Mozilla navigation bar.
                        // 'chapter' and 'forum' can create multiple items, therefore we are using a nested array.
                        //
                        $nav_links['chapter forum'][$forum_rows[$j]['forum_id']] = array (
                            'url' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=" . $forum_rows[$j]['forum_id']),
                            'title' => $forum_rows[$j]['forum_name']
                        );

                    }
                }

                if ( $boxstring_forums != '' )
                {
                    $boxstring .= '<option value="-1">&nbsp;</option>';
                    $boxstring .= '<option value="-1">' . $category_rows[$i]['cat_title'] . '</option>';
                    $boxstring .= '<option value="-1">----------------</option>';
                    $boxstring .= $boxstring_forums;
                }
            }
        }

        $boxstring .= '</select>';
    }
    else
    {
        $boxstring .= '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"></select>';
    }

    $boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '">';


    $template->set_filenames(array(
        'jumpbox' => 'jumpbox.tpl')
    );
    $template->assign_vars(array(
        'L_GO' => $lang['Go'],
        'L_JUMP_TO' => $lang['Jump_to'],
        'L_SELECT_FORUM' => $lang['Select_forum'],

        'S_JUMPBOX_SELECT' => $boxstring,
        'S_JUMPBOX_ACTION' => append_sid($action))
    );
    $template->assign_var_from_handle('JUMPBOX', 'jumpbox');

    return;
}

//
// Initialise user settings on page load
/*function init_userprefs(&$userdata)
{
    global $board_config, $theme, $images;
    global $template, $lang, $phpEx, $phpbb_root_path;
    global $nav_links;
    global $mvModules, $mvModuleName, $mvModule_root_path;

//-- mod : mods settings ---------------------------------------------------------------------------
//-- add
    global $db, $mods, $list_yes_no, $userdata;

    //  get all the mods settings
    $dir = @opendir($phpbb_root_path . 'includes/mods_settings');
    while( $file = @readdir($dir) )
    {
        if( preg_match("/^mod_.*?\." . $phpEx . "$/", $file) )
        {
            include_once($phpbb_root_path . 'includes/mods_settings/' . $file);
        }
    }
    @closedir($dir);
//-- fin mod : mods settings -----------------------------------------------------------------------

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
    global $admin_level, $level_prior, $level_desc;
    global $values_list, $tables_linked, $classes_fields, $user_maps, $user_fields;
    global $list_yes_no;

    include_once( $phpbb_root_path . './profilcp/functions_profile.' . $phpEx);
//-- fin mod : profile cp --------------------------------------------------------------------------
//-- mod : mods settings ---------------------------------------------------------------------------
//-- add
    global $db, $mods, $userdata;

    //  get all the mods settings
    $dir = @opendir($phpbb_root_path . 'includes/mods_settings');
    while( $file = @readdir($dir) )
    {
        if( preg_match("/^mod_.*?\." . $phpEx . "$/", $file) )
        {
            include_once($phpbb_root_path . 'includes/mods_settings/' . $file);
        }
    }
    @closedir($dir);
//-- fin mod : mods settings -----------------------------------------------------------------------

    if ( $userdata['user_id'] != ANONYMOUS )
    {
        if ( !empty($userdata['user_lang']))
        {
            $board_config['default_lang'] = $userdata['user_lang'];
        }

        if ( !empty($userdata['user_dateformat']) )
        {
            $board_config['default_dateformat'] = $userdata['user_dateformat'];
        }

        if ( isset($userdata['user_timezone']) )
        {
            $board_config['board_timezone'] = $userdata['user_timezone'];
        }
    }

    if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx)) )
    {
        $board_config['default_lang'] = 'english';
    }

    include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx);
    include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rate.' . $phpEx);

    if ( defined('IN_CASHMOD') )
    {
        if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cash.'.$phpEx)) )
        {
            $board_config['default_lang'] = 'english';
        }

		include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cash.' . $phpEx);
    }

    if ( defined('IN_ADMIN') )
    {
        if( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.'.$phpEx)) )
        {
            $board_config['default_lang'] = 'english';
        }

        include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx);
    }

    //reset($mvModules);
    foreach ($mvModules as $name => $value)
    {
        if ($value['state'] != 1 && $value['state'] != 5)
            continue;
        //reset($value['language']);
        foreach ($value['language'] as $n => $file)
        {
            $lang_file = $phpbb_root_path . 'modules/' .
                        $name . '/language/lang_' . $board_config['default_lang'] .
                        '/' . $file;
            if ($name == $mvModuleName || $file == "lang_main.$phpEx" || ($file == "lang_admin.$phpEx" && defined('IN_ADMIN')))
            {
                if (file_exists(@phpbb_realpath($lang_file)))
                {
                    include($lang_file);
                }
                else
                {
                    @include($phpbb_root_path . 'modules/' .
                        $name . '/language/lang_english/' . $file);
                }
            }
        }
    }

    include_attach_lang();

//-- mod : language settings -----------------------------------------------------------------------
//-- add
    include($phpbb_root_path . './includes/lang_extend_mac.' . $phpEx);
//-- fin mod : language settings -------------------------------------------------------------------

    //
    // Set up style
    //
    if ( !$board_config['override_user_style'] )
    {
        if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_style'] > 0 )
        {
            if ( $theme = setup_style($userdata['user_style']) )
            {
                return;
            }
        }
    }

    $theme = setup_style($board_config['default_style']);

    //
    // Mozilla navigation bar
    // Default items that should be valid on all pages.
    // Defined here to correctly assign the Language Variables
    // and be able to change the variables within code.
    //
    $nav_links['top'] = array (
        'url' => append_sid($phpbb_root_path . 'index.' . $phpEx),
        'title' => sprintf($lang['Forum_Index'], $board_config['sitename'])
    );
    $nav_links['search'] = array (
        'url' => append_sid($phpbb_root_path . 'search.' . $phpEx),
        'title' => $lang['Search']
    );
    $nav_links['help'] = array (
        'url' => append_sid($phpbb_root_path . 'faq.' . $phpEx),
        'title' => $lang['FAQ']
    );
    $nav_links['author'] = array (
        'url' => append_sid($phpbb_root_path . 'memberlist.' . $phpEx),
        'title' => $lang['Memberlist']
    );

    return;
}
*/
//
// Create calendar timestamp from timezone
//
function cal_date($gmepoch, $tz)
{
	global $board_config;

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
	global $userdata, $db;

	if ( !isset($board_config['summer_time']) )
	{
        if ( $sql = set_config('summer_time', '0') )
            message_die(GENERAL_ERROR, 'Could not add key summer_time in config table', '', __LINE__, __FILE__, $sql);
        $board_config['summer_time'] = false;
	}
	$switch_summer_time = ( $userdata['user_summer_time'] && $board_config['summer_time'] ) ? true : false;
	if ($switch_summer_time) $tz++;
//-- fin mod : profile cp --------------------------------------------------------------------------

	return (strtotime(gmdate('M d Y H:i:s', $gmepoch + (3600 * $tz))));
}
/*
function setup_style($style)
{
    global $db, $cache, $board_config, $template, $images, $phpbb_root_path;
    global $mvModule_root_path;

	if ( $cache->exists('themes.theme_id_' . $style) )
	{
		$row = $cache->get('themes.theme_id_' . $style);

	    if ( empty($row) )
	    {
			$cache->destroy('themes.');
	        message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
	    }
	}
	else
	{
		$sql = "SELECT *
			FROM " . THEMES_TABLE . "
			WHERE themes_id = $style";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, 'Could not query database for theme info');
		}

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
		}

		$cache->put('themes.theme_id_' . $style, $row);
	}

    $template_path = 'templates/';
    $template_name = $row['template_name'];

	if(defined('IN_ADMIN'))
	{
		$template_name = 'admin';
	}
    $template = new Template($phpbb_root_path . $template_path . $template_name);

    if ( $template )
    {
        $current_template_path = $template_path . $template_name;

        @include($phpbb_root_path . $template_path . $template_name . '/' . $template_name . '.cfg');
        if ($mvModule_root_path)
        {
            $current_template_images = $mvModule_root_path . $template_path . $template_name . '/images';
            @include($mvModule_root_path . $template_path . $template_name . '/' . $template_name . '.cfg');
        }
        if ( !defined('TEMPLATE_CONFIG') )
        {
            message_die(CRITICAL_ERROR, "Could not open $template_name template config file", '', __LINE__, __FILE__);
        }

        $img_lang = ( file_exists(@realpath($phpbb_root_path . $current_template_path . '/images/lang_' . $board_config['default_lang'])) ) ? $board_config['default_lang'] : 'english';
        //while ( list($key, $value) = @each($images) )
		foreach ( $images as $key => $value )
        {
            if ( !is_array($value) )
            {
                $images[$key] = str_replace('{LANG}', 'lang_' . $img_lang, $value);
            }
        }
    }
	if(defined('IN_ADMIN'))
	{
		$row = $admin[0];
	}
    return $row;
}
*/
function encode_ip($dotquad_ip)
{
    $ip_sep = explode('.', $dotquad_ip);
    return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{
    $hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
    return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

//
// Create date/time from format and timezone
//
function create_date($format, $gmepoch, $tz)
{
    global $board_config, $lang;
    static $translate;

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
    global $userdata, $db;

    if ( !isset($board_config['summer_time']) )
    {
        if ( $sql = set_config('summer_time', '0') )
            message_die(GENERAL_ERROR, 'Could not add key summer_time in config table', '', __LINE__, __FILE__, $sql);
        $board_config['summer_time'] = false;
    }
    $switch_summer_time = ( $userdata['user_summer_time'] && $board_config['summer_time'] ) ? true : false;
    if ($switch_summer_time) $tz++;
//-- fin mod : profile cp --------------------------------------------------------------------------

    if ( empty($translate) && $board_config['default_lang'] != 'english' )
    {
        //@reset($lang['datetime']);
        //while ( list($match, $replace) = @each($lang['datetime']) )
		foreach ( $lang['datetime'] as $match => $replace )
        {
            $translate[$match] = $replace;
        }
    }

    return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

//
// Pagination routine, generates
// page number sequence
//
//-- mod : profile cp ------------------------------------------------------------------------------
// here we added
//  , $start_field='start'
//-- modify
function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE, $start_field='start')
//-- fin mod : profile cp --------------------------------------------------------------------------
{
    
    $total_pages = ceil($num_items/$per_page);

    if ( $total_pages == 1 )
    {
        return '';
    }

	global $lang, $phpEx;

    $on_page = floor($start_item / $per_page) + 1;

    $page_string = '';
    if ( $total_pages > 10 )
    {
        $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

        for($i = 1; $i < $init_page_max + 1; $i++)
        {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
			
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "$start_field=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
//-- fin mod : profile cp --------------------------------------------------------------------------
            if ( $i <  $init_page_max )
            {
                $page_string .= ", ";
            }
        }

        if ( $total_pages > 3 )
        {
            if ( $on_page > 1  && $on_page < $total_pages )
            {
                $page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

                for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
                {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
                    $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "$start_field=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
//-- fin mod : profile cp --------------------------------------------------------------------------
                    if ( $i <  $init_page_max + 1 )
                    {
                        $page_string .= ', ';
                    }
                }

                $page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
            }
            else
            {
                $page_string .= ' ... ';
            }

            for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
            {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
                $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "$start_field=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
//-- fin mod : profile cp --------------------------------------------------------------------------
                if( $i <  $total_pages )
                {
                    $page_string .= ", ";
                }
            }
        }
    }
    else
    {
        for($i = 1; $i < $total_pages + 1; $i++)
        {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
            $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "$start_field=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
//-- fin mod : profile cp --------------------------------------------------------------------------
            if ( $i <  $total_pages )
            {
                $page_string .= ', ';
            }
        }
    }

    if ( $add_prevnext_text )
    {
        if ( $on_page > 1 )
        {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
            $page_string = ' <a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "&amp;$start_field=" . ( ( $on_page - 2 ) * $per_page ) ) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
//-- fin mod : profile cp --------------------------------------------------------------------------
        }

        if ( $on_page < $total_pages )
        {
//-- mod : profile cp ------------------------------------------------------------------------------
// here we replaced
//  start
// with
//  $start_field
//-- modify
            $page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . ( strpos($base_url, ".$phpEx?") === FALSE ? '?' : '&amp;') . "$start_field=" . ( $on_page * $per_page ) ) . '">' . $lang['Next'] . '</a>';
//-- fin mod : profile cp --------------------------------------------------------------------------
        }

    }

	if ( $page_string != '')
	{
		$page_string = $lang['Goto_page'] . ' ' . $page_string;
	}
	else
	{
		$page_string = '';
	}

    return $page_string;
}

//
// This does exactly what preg_quote() does in PHP 4-ish
// If you just need the 1-parameter preg_quote call, then don't bother using this.
//
function phpbb_preg_quote($str, $delimiter)
{
    $text = preg_quote($str);
    $text = str_replace($delimiter, '\\' . $delimiter, $text);

    return $text;
}

//
// Obtain list of naughty words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
function obtain_word_list(&$orig_word, &$replacement_word)
{
    global $db, $cache;

    if ($cache->exists('word_censors'))
    {
        $censors = $cache->get('word_censors');
        $orig_word = $censors['orig_word'];
        $replacement_word = $censors['replacement_word'];
        unset ($censors);
    }
    else
    {
        //
        // Define censored word matches
        //
        $sql = "SELECT word, replacement
            FROM  " . WORDS_TABLE;
        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
        }

        if ( $row = $db->sql_fetchrow($result) )
        {
            do
            {
				// Intellicensor © 2004 Jonathan Motta < phpbb@rusticweb.com >

				$ic_word = '';
				$ic_first = 0;
				
				$ic_chars = preg_split('//', $row['word'], -1, PREG_SPLIT_NO_EMPTY);
				
				foreach ($ic_chars as $char)
				{
					if ( ($ic_first == 1) && ($char != '*') )
					{
						$ic_word .= '_';
					}
					$ic_word .= $char;
					$ic_first = 1;
				}

				$ic_search = array('\*','s','a','b','l','i','o','p','_');
				$ic_replace = array('\w*?','(?:s|\$)','(?:a|\@)','(?:b|8|3)','(?:l|1|i|\!)','(?:i|1|l|\!)','(?:o|0)','(?:p|\?)','(?:_|\W)*');
				$orig_word[] = '#(?<=^|\W)(' . str_replace($ic_search, $ic_replace, phpbb_preg_quote($ic_word, '#')) . ')(?=\W|$)#i';

            //	$orig_word[] = '#\b(' . str_replace('\*', '\w*?', phpbb_preg_quote($row['word'], '#')) . ')\b#i';
                $replacement_word[] = $row['replacement'];
            }
            while ( $row = $db->sql_fetchrow($result) );
        }

        $db->sql_freeresult($result);

        $cache->put('word_censors', array(
			'orig_word'			=> $orig_word,
			'replacement_word'	=> $replacement_word
		));
    }
    return true;
}

//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the
// common.php include and session code, ie. most errors in
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
    global $db, $template, $board_config, $theme, $lang, $phpEx, $phpbb_root_path, $nav_links, $gen_simple_header, $images;
    global $userdata, $user_ip, $session_length;
    global $starttime;

//-- mod : sub-template ----------------------------------------------------------------------------
//-- add
//-- fix
    global $sub_template_key_image, $sub_templates;
//-- fin mod : sub-template ------------------------------------------------------------------------

//-- mod : profile cp ------------------------------------------------------------------------------
//-- add
    global $admin_level, $level_prior;
//-- fin mod : profile cp --------------------------------------------------------------------------

    if(defined('HAS_DIED'))
    {

       die("message_die() was called multiple times. This isn't supposed to happen. Was message_die() used in page_tail.php?");
    }

    define('HAS_DIED', 1);


    $sql_store = $sql;

    //
    // Get SQL error if we are debugging. Do this as soon as possible to prevent
    // subsequent queries from overwriting the status of sql_error()
    //
    if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
    {
		$debug_text = '';

		if ( isset($db) )
		{
        	$sql_error = $db->sql_error();
		}
		else
		{
			$sql_error['message'] = '';
		}
        

        if ( $sql_error['message'] != '' )
        {
            $debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
        }

        if ( $sql_store != '' )
        {
            $debug_text .= "<br /><br />$sql_store";
        }

        if ( $err_line != '' && $err_file != '' )
        {
            $debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
        }
    }

    if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
    {
        $userdata = session_pagestart($user_ip, PAGE_INDEX);
        init_userprefs($userdata);
    }

    //
    // If the header hasn't been output then do it
    //
    if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR )
    {
        if ( empty($lang) )
        {
            if ( !empty($board_config['default_lang']) )
            {
                include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx);
            }
            else
            {
                include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);
            }

//-- mod : language settings -----------------------------------------------------------------------
//-- add
            include($phpbb_root_path . './includes/lang_extend_mac.' . $phpEx);
//-- fin mod : language settings -------------------------------------------------------------------

        }

        if ( empty($template) )
        {
            $template = new Template($phpbb_root_path . 'templates/' . $board_config['board_template']);
        }
        if ( empty($theme) )
        {
            $theme = setup_styles($board_config['default_style']);
        }

        //
        // Load the Page Header
        //
        if ( !defined('IN_ADMIN') )
        {
            include($phpbb_root_path . 'includes/page_header.'.$phpEx);
        }
        else
        {
            include($phpbb_root_path . 'admin/page_header_admin.'.$phpEx);
        }
    }

    switch($msg_code)
    {
        case GENERAL_MESSAGE:
            if ( $msg_title == '' )
            {
                $msg_title = $lang['Information'];
            }
            break;

        case CRITICAL_MESSAGE:
            if ( $msg_title == '' )
            {
                $msg_title = $lang['Critical_Information'];
            }
            break;

        case GENERAL_ERROR:
            if ( $msg_text == '' )
            {
                $msg_text = $lang['An_error_occured'];
            }

            if ( $msg_title == '' )
            {
                $msg_title = $lang['General_Error'];
            }
            break;

        case CRITICAL_ERROR:
            //
            // Critical errors mean we cannot rely on _ANY_ DB information being
            // available so we're going to dump out a simple echo'd statement
            //
            include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);

            if ( $msg_text == '' )
            {
                $msg_text = $lang['A_critical_error'];
            }

            if ( $msg_title == '' )
            {
                $msg_title = 'Minerva : <b>' . $lang['Critical_Error'] . '</b>';
            }
            break;
    }

    //
    // Add on DEBUG info if we've enabled debug mode and this is an error. This
    // prevents debug info being output for general messages should DEBUG be
    // set TRUE by accident (preventing confusion for the end user!)
    //
    if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
    {
        if ( $debug_text != '' )
        {
            $msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text;
        }
    }

    if ( $msg_code != CRITICAL_ERROR )
    {
        if ( !empty($lang[$msg_text]) )
        {
            $msg_text = $lang[$msg_text];
        }

        if ( !defined('IN_ADMIN') )
        {
            $template->set_filenames(array(
                'message_body' => 'message_body.tpl')
            );
        }
        else
        {
            $template->set_filenames(array(
                'message_body' => 'admin_message_body.tpl')
            );
        }

        $template->assign_vars(array(
            'MESSAGE_TITLE' => $msg_title,
            'MESSAGE_TEXT' => $msg_text)
        );

//--------------------------------------------------------------------------------
// Prillian - Begin Code Addition
//
        if ( $gen_simple_header )
        {
            $template->assign_vars(array('U_INDEX' => '', 'L_INDEX' => ''));
        }
//
// Prillian - End Code Addition
//--------------------------------------------------------------------------------

        $template->pparse('message_body');

        if ( !defined('IN_ADMIN') )
        {
            include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
        }
        else
        {
            include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);
        }
    }
    else
    {
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>' . strip_tags($msg_title) . '</title>' . "\n";
  	    echo '<body><h1 style="font-family:Verdana,serif;font-size:18pt;font-weight:bold">' . $msg_title . '</h1><hr style="height:2px;border-style:dashed;color:black" /><p style="font-family:Verdana,serif;font-size:10pt">' . $msg_text . '</p><hr style="height:2px;border-style:dashed;color:black" /><p style="font-family:Verdana,serif;font-size:10pt">Contact the site administrator to report this failure</p></body></html>';
    }

    exit;
}

//
// This function is for compatibility with PHP 4.x's realpath()
// function.  In later versions of PHP, it needs to be called
// to do checks with some functions.  Older versions of PHP don't
// seem to need this, so we'll just return the original value.
// dougk_ff7 <October 5, 2002>
function phpbb_realpath($path)
{
    global $phpbb_root_path, $phpEx;

    return (!@function_exists('realpath') || !@realpath($phpbb_root_path . 'includes/functions.'.$phpEx)) ? $path : @realpath($path);
}

function redirect($url)
{
    global $db, $cache, $board_config;

    if (!empty($cache))
    {
        //
        // Unload the Cache.
        //
        $cache->unload();
    }

    if (!empty($db))
    {
        $db->sql_close();
    }

    if (strstr(urldecode($url), "\n") || strstr(urldecode($url), "\r"))
    {
        message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
    }

    $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
    $server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
    $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
    $script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
    $script_name = ($script_name == '') ? $script_name : '/' . $script_name;
    $url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));

    // Redirect via an HTML form for PITA webservers
    if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
    {
        header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
        exit;
    }

    // Behave as per HTTP/1.1 spec for others
    header('Location: ' . $server_protocol . $server_name . $server_port . $script_name . $url);
    exit;
}

// Automatically assign page id
function register_page_id($page_id, $page_url, $page_name)
{
    global $mvModuleName, $mvPages;

    if ($mvModuleName == '')
    {
        return;
    }
    define('MODULE_' . $mvModuleName . '_' . $page_id, PAGE_BASE - count($mvPages));
    define($page_id, $page_id);
    $page_id_array = array('url' => append_sid($page_url), 'name' => $page_name);
    $mvPages[] = $page_id_array;
}

function get_rule($rule_id = 0, $what = 'text')
{
    global $db, $cache;

    static $rules = '';

    if (!is_array($rules) )
    {
        if ( $cache->exists('rules') )
        {
            $rules = $cache->get('rules');
        }
        else
        {
            $sql = "SELECT * FROM " . RULES_TABLE . "
                ORDER BY rule_id";

            if( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not obtain the rules', '', __LINE__, __FILE__, $sql);
            }

            $rules = array();

            while ($row = $db->sql_fetchrow($result))
            {
                $rules[$row['rule_id']] = array(
                    'TEXT'  => $row['rule_text'],
                    'DATE'  => $row['rule_date']
                );
            }

			$db->sql_freeresult($result);

            $cache->put('rules', $rules);
        }
    }

    // Ah HA!
    if ( isset($rules[$rule_id]) )
    {
        $what = strtoupper($what);

        if ($what == 'TEXT' || $what == 'DATE')
        {
            return $rules[$rule_id][$what];
        }
        else
        {
            return $rules[$rule_id];
        }
    }

    return FALSE;
}

function server_path_info($return_array = TRUE, $script_name = '')
{
	global $board_config;

	$host = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$path = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));

	$url = array(
		'scheme'	=> ($board_config['cookie_secure'] ? 'https://' : 'http://'),
		'host'		=> $host,
		'port'		=> ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '',
		'slash1'	=> '/',
		'path'		=> $path,
		'slash2'	=> '/',
		'script'	=> $script_name
	);

	if ( $return_array == FALSE )
	{
		$url = implode('', $url);
	}

	return $url;
}

function record_referer($referer_url)
{
	global $db, $user_ip;

	// Taken from referers.php
	// © 2002 NKieTo

	$referer_host = substr($referer_url, strpos($referer_url, '//') + 2);
	$referer_host = (strpos($referer_host, '/') === false ? $referer_host . '/' : substr($referer_host, 0, strpos($referer_host, '/')));
	$time_now = time();

	$sql = 'SELECT * FROM ' . REFERERS_TABLE . " WHERE referer_url='$referer_url'";
	if (!($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not get referers information", "", __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$db->sql_freeresult($result);

	if (!$row)
	{
		$sql = 'INSERT INTO ' . REFERERS_TABLE . ' (referer_host, referer_url, referer_ip, referer_hits, referer_firstvisit, referer_lastvisit) VALUES ' . "('$referer_host', '$referer_url', '$user_ip', 1, $time_now, $time_now)";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't insert new referer", "", __LINE__, __FILE__, $sql);
		}

		$db->sql_freeresult($result);
	}
	else
	{
		$sql = 'UPDATE ' . REFERERS_TABLE . " SET referer_hits = referer_hits + 1, referer_lastvisit = $time_now, referer_ip = '$user_ip' WHERE referer_url='$referer_url'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't update referers information", "", __LINE__, __FILE__, $sql);
		}

		$db->sql_freeresult($result);
	}
}


//
// Functions below 'Borrowed' from or 'Inspired' By phpBB 2.1.x
// Thanks :-D
//

function set_var(&$result, $var, $type)
{
	settype($var, $type);
	$result = $var;

	if ($type == 'string')
	{
		$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", '\xFF'), array("\n", "\n", ' '), $result)));
		$result = preg_replace("#\n{3,}#", "\n\n", $result);
		$result = (STRIP) ? stripslashes($result) : $result;
	}
}

function request_var($var_name, $default)
{
	if (!isset($_REQUEST[$var_name]))
	{
		return $default;
	}
	else
	{
		$var = $_REQUEST[$var_name];
		$type = gettype($default);

		if (is_array($var))
		{
			foreach ($var as $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v as $_k => $_v)
					{
						set_var($var[$k][$_k], $_v, $type);
					}
				}
				else
				{
					set_var($var[$k], $v, $type);
				}
			}
		}
		else
		{
			set_var($var, $var, $type);
		}

		return $var;
	}
}

function set_config_arr ($config_names, $config_values, $is_dynamics = array())
{
    foreach ($config_names as $key => $config_name)
    {
        $is_dynamics[$key] = isset($is_dynamics[$key]) ? (intval($is_dynamics[$key]) ? 1 : 0) : 0;

        if ( $sql = set_config($config_names[$key], $config_values[$key], $is_dynamics[$key]) )
        {
            return $sql; // Error
        }
    }
    retrun;
}

function get_config_value($config_name)
{
    global $board_config;

    $config_value = FALSE;

    if (isset($board_config[$config_name]))
    {
        $config_value = $board_config[$config_name];

    }
    else
    {
        global $db;

        $sql = 'SELECT config_value FROM ' . CONFIG_TABLE . " WHERE config_name = '" . $db->sql_escape($config_name) . "'";

        if (!($result = $db->sql_query($sql)))
        {
            return FALSE;
        }

        if ($row = $db->sql_fetchrow($result))
        {
            $config_value = $row[$config_name];
        }

		$db->sql_freeresult($result);
    }
    return $config_value;
}

function set_config($config_name, $config_value, $is_dynamic = FALSE, $exists = NULL, $insert_only = FALSE)
{
    global $db, $cache, $board_config;

    if ($exists === NULL)
    {
        $exists = get_config_value($config_name);
    }

    $done = FALSE;

    if ($exists !== FALSE && $insert_only === FALSE)
    {
        $sql = 'UPDATE ' . CONFIG_TABLE . "
            SET config_value = '" . $db->sql_escape($config_value) . "',
            is_dynamic = '" . ($is_dynamic ? 1 : 0) . "'
            WHERE config_name = '" . $db->sql_escape($config_name) . "'";

        if (!$db->sql_query($sql))
        {
            return $sql;
        }

		$db->sql_freeresult();

        $done = TRUE;
    }
    elseif ($exists === FALSE)
    {
        $sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value, is_dynamic)
            VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "', '" . ($is_dynamic ? 1 : 0) . "')";

        if (!$db->sql_query($sql))
        {
            return $sql;
        }

		$db->sql_freeresult();

        $done = TRUE;
    }

    if ($done == TRUE)
    {
        $board_config[$config_name] = $config_value;

        if (!$is_dynamic)
        {
            $cache->destroy('board_config');
        }
    }
    return;
}

// Obtain ranks
function obtain_ranks(&$ranks)
{
    global $db, $cache;

    if ($cache->exists('ranks'))
    {
        $ranks = $cache->get('ranks');
    }
    else
    {
        $sql = 'SELECT *
            FROM ' . RANKS_TABLE . '
            ORDER BY rank_min DESC';
        $result = $db->sql_query($sql);

        $ranks = array();
        while ($row = $db->sql_fetchrow($result))
        {
            if ($row['rank_special'])
            {
                $ranks['special'][$row['rank_id']] = array(
                    'rank_title'    =>  $row['rank_title'],
                    'rank_image'    =>  $row['rank_image']
                );
            }
            else
            {
                $ranks['normal'][] = array(
                    'rank_title'    =>  $row['rank_title'],
                    'rank_min'      =>  $row['rank_min'],
                    'rank_image'    =>  $row['rank_image']
                );
            }
        }
        $db->sql_freeresult($result);

        $cache->put('ranks', $ranks);
    }
}

?>