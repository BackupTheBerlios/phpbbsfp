<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_profile.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : functions_profile.php
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

//-------------------------------------------
//
//  users administrators
//
//-------------------------------------------
if (!defined('BOARD_ADMIN')) define('BOARD_ADMIN', 98);
$admin_level = array(ADMIN_FOUNDER, ADMIN);
$level_prior = array(ADMIN_FOUNDER => 99, ADMIN => 9, MOD => 5, USER => 0);
$level_desc = array(ADMIN_FOUNDER => 'Admin_founder_online_color', ADMIN => 'Admin_online_color', MOD => 'Mod_online_color', USER => 'User_online_color');

function get_user_level($userdata)
{
    // fix a phpBB bug
    global $db;
    if ($userdata['user_level'] == MOD)
    {
        $sql = "SELECT * FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
                WHERE ug.user_id = " . $userdata['user_id'] . "
                    AND aa.group_id = ug.group_id
                    AND aa.auth_mod = 1
                    AND ug.user_pending = 0";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
        }
        if ($db->sql_numrows($result) <= 0)
        {
            $userdata['user_level'] = USER;
        }
    }

    $res = USER;
    if ( ($userdata['user_level'] == ADMIN) && ($userdata['user_id'] == 2) )
    {
        $res = ADMIN_FOUNDER;
    }
    else if ($userdata['user_level'] == ADMIN)
    {
        $res = ADMIN;
    }
    else if ($userdata['user_level'] == MOD)
    {
        $res = MOD;
    }
    else
    {
        $res = USER;
    }
    return $res;
}

function is_admin($userdata)
{
    global $admin_level;

    return in_array(get_user_level($userdata),$admin_level);
}

function get_user_level_class($user_level, $default='gen', $user=array())
{
    $ret = $default;
    if (!empty($user)) $user_level = get_user_level($user);
    switch( $user_level )
    {
            case ADMIN_FOUNDER:
                $ret = 'foundercolor';
                break;
            case ADMIN:
                $ret = 'admincolor';
                break;
            case MOD:
                $ret = 'modcolor';
                break;
            default:
                $ret = 'usercolor';
                break;
    }
    return $ret;
}

function get_users_online_color()
{
    global $lang;
    global $level_prior, $level_desc;

    $res = '';

    // read the defined levels
    @arsort($level_prior);
    @reset($level_prior);
    while ( list($key, $value) = @each($level_prior) )
    {
        if ( !empty($lang[ $level_desc[$key] ]) )
        {
            $res .= ( empty($res) ? '' : '&nbsp;&nbsp;' ) . sprintf($lang[ $level_desc[$key] ], '[&nbsp;<span class="' . get_user_level_class($key) . '">', '</span>&nbsp;]');
        }
    }
    return $res;
}

//-------------------------------------------
//
//  ranks management
//
//-------------------------------------------
global $cache;
if ($cache->exists('pcp_ranks'))
{
	$all_ranks = $cache->get('pcp_ranks');
}
else
{
	$all_ranks = array();
	init_ranks($all_ranks);
	$cache->put('pcp_ranks', $all_ranks);
}

function init_ranks(&$ranks)
{
    global $db;

    $sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special DESC, rank_min";
    if ( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain ranks information.', '', __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
        $ranks[] = $row;
    }
    $db->sql_freeresult($result);

    $rank_maxi = 99999999;
    for ($i=count($ranks)-1; $i >= 0; $i--)
    {
        if ( $row['rank_special'] ) $row['rank_mini'] = 0;
        $row['rank_maxi'] = $rank_maxi;
        if (!$row['rank_special'] )
        {
            $rank_maxi = $row['rank_mini'];
        }
        else $rank_maxi = 99999999;
    }
}

function get_user_rank($userrow)
{
    global $all_ranks, $images;

    $rank_title = '';
    $rank_image = '';
    if ($userrow['user_id'] != ANONYMOUS)
    {
        if ( $userrow['user_rank'] )
        {
            $found = false;
            for ($i = 0; ( ($i < count($all_ranks)) && !$found); $i++)
            {
                $found = ( ($userrow['user_rank'] == $all_ranks[$i]['rank_id']) && $all_ranks[$i]['rank_special']);
                if ($found)
                {
                    $ranks = explode( "|", $all_ranks[$i]['rank_title']);
                    $rank_title = ( isset($ranks[$userrow['user_gender']]) && ($ranks[$userrow['user_gender']] != '') ) ? $ranks[$userrow['user_gender']] : $ranks[0];
                    $rank_image = ( $all_ranks[$i]['rank_image'] ) ? '<img src="' . $images['rank'] . $all_ranks[$i]['rank_image'] . '" alt="' . $rank_title . '" title="' . $rank_title . '" border="0" />' : '';
                }
            }
        }
        else
        {
            for($i = 0; $i < count($all_ranks); $i++)
            {
                if ( $userrow['user_posts'] >= $all_ranks[$i]['rank_min'] && !$all_ranks[$i]['rank_special'] )
                {
                    $ranks = explode( "|", $all_ranks[$i]['rank_title']);
                    $rank_title = ( isset($ranks[$userrow['user_gender']]) && ($ranks[$userrow['user_gender']] != '') ) ? $ranks[$userrow['user_gender']] : $ranks[0];
                    $rank_image = ( $all_ranks[$i]['rank_image'] ) ? '<img src="' . $images['rank'] . $all_ranks[$i]['rank_image'] . '" alt="' . $rank_title . '" title="' . $rank_title . '" border="0" />' : '';
                }
            }
        }
    }

    // result
    $res = array();
    $res['rank_title'] = $rank_title;
    $res['rank_image'] = $rank_image;
    return $res;
}

//-------------------------------------------
//
//  others functions
//
//-------------------------------------------
function pcp_gen_rand_string($hash)
{

    $alphabet = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789';

    $max_chars = strlen($alphabet) - 1;
    srand( (double) microtime()*1000000);

    $rand_str = '';
    for($i = 0; $i < 8; $i++)
    {
        $rand_str = ( $i == 0 ) ? $alphabet[rand(0, $max_chars)] : $rand_str . $alphabet[rand(0, $max_chars)];
    }

    return ( $hash ) ? md5($rand_str) : $rand_str;
}

function prepare_signature( $signature, $view_userdata )
{
    global $board_config, $lang, $bbcode_parse;

    $preview_sig = ($signature != '') ? $signature : '';
    $user_sig_bbcode_uid = $view_userdata['user_sig_bbcode_uid'];

    // delete html tags
    if ( $preview_sig != '' && $view_userdata['user_allowhtml'] && !$board_config['allow_html'] )
    {
        $preview_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $preview_sig);
    }

    // parse bbcodes
    if ( $preview_sig != '' && $user_sig_bbcode_uid != '' && $board_config['allow_bbcode'])
    {
        $preview_sig = ( $board_config['allow_bbcode'] ) ? $bbcode_parse->bbencode_second_pass($preview_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $preview_sig);
    }

    // add links
    if ( $preview_sig != '' ) $preview_sig = $bbcode_parse->make_clickable($preview_sig);

    // parse smilies
    if ( $preview_sig != '' && $view_userdata['user_allowsmile'] && $board_config['allow_smilies'] )
    {
        $preview_sig = $bbcode_parse->smilies_pass($preview_sig);
    }

    // formate \n
    if ( $preview_sig != '' ) $preview_sig = str_replace("\n", "\n<br />\n", $preview_sig);

    return $preview_sig;
}

function pcp_check_image_type(&$type, &$error, &$error_msg)
{
    global $lang;

    switch( $type )
    {
        case 'jpeg':
        case 'pjpeg':
        case 'jpg':
            return '.jpg';
            break;
        case 'gif':
            return '.gif';
            break;
        case 'png':
            return '.png';
            break;
        default:
            $error = true;
            $error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
            break;
    }

    return false;
}

function pcp_user_avatar_delete($avatar_type, $avatar_file)
{
    global $board_config, $userdata;

    if ( $avatar_type == USER_AVATAR_UPLOAD && $avatar_file != '' )
    {
        if ( @file_exists(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $avatar_file)) )
        {
            @unlink('./' . $board_config['avatar_path'] . '/' . $avatar_file);
        }
    }

    return " user_avatar = '', user_avatar_type = " . USER_AVATAR_NONE;
}

function pcp_user_avatar_gallery(&$error, &$error_msg, $avatar_filename)
{
    global $board_config;
    if ( file_exists(@phpbb_realpath($board_config['avatar_gallery_path'] . '/' . $avatar_filename)) )
    {
        $return = " user_avatar = '" . str_replace("\'", "''", $avatar_filename) . "', user_avatar_type = " . USER_AVATAR_GALLERY;
    }
    else
    {
        $return = '';
    }
    return $return;
}

function pcp_user_avatar_url(&$error, &$error_msg, $avatar_filename)
{
    if ( !preg_match("#^((ht|f)tp://)#i", $avatar_filename) )
    {
        $avatar_filename = 'http://' . $avatar_filename;
    }
    if ( !preg_match("#^((ht|f)tp://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png))$)#is", $avatar_filename) )
    {
        $error = true;
        $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Wrong_remote_avatar_format'] : $lang['Wrong_remote_avatar_format'];
        return;
    }

    return " user_avatar = '" . str_replace("\'", "''", $avatar_filename) . "', user_avatar_type = " . USER_AVATAR_REMOTE;

}

function pcp_user_avatar_upload($avatar_mode, &$current_avatar, &$current_type, &$error, &$error_msg, $avatar_filename, $avatar_realname, $avatar_filesize, $avatar_filetype)
{
    global $board_config, $db, $lang;

    $ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

    if ( $avatar_mode == 'remote' && preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $avatar_filename, $url_ary) )
    {
        if ( empty($url_ary[4]) )
        {
            $error = true;
            $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Incomplete_URL'] : $lang['Incomplete_URL'];
            return;
        }

        $base_get = '/' . $url_ary[4];
        $port = ( !empty($url_ary[3]) ) ? $url_ary[3] : 80;

        if ( !($fsock = @fsockopen($url_ary[2], $port, $errno, $errstr)) )
        {
            $error = true;
            $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['No_connection_URL'] : $lang['No_connection_URL'];
            return;
        }

        @fputs($fsock, "GET $base_get HTTP/1.1\r\n");
        @fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
        @fputs($fsock, "Connection: close\r\n\r\n");

        unset($avatar_data);
        while( !@feof($fsock) )
        {
            $avatar_data .= @fread($fsock, $board_config['avatar_filesize']);
        }
        @fclose($fsock);

        if (!preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $file_data1) || !preg_match('#Content-Type\: image/[x\-]*([a-z]+)[\s]+#i', $avatar_data, $file_data2))
        {
            $error = true;
            $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['File_no_data'] : $lang['File_no_data'];
            return;
        }

        $avatar_filesize = $file_data1[1];
        $avatar_filetype = $file_data2[1];

        if ( !$error && $avatar_filesize > 0 && $avatar_filesize < $board_config['avatar_filesize'] )
        {
            $avatar_data = substr($avatar_data, strlen($avatar_data) - $avatar_filesize, $avatar_filesize);

            $tmp_path = ( !@$ini_val('safe_mode') ) ? '/tmp' : './' . $board_config['avatar_path'] . '/tmp';
            $tmp_filename = tempnam($tmp_path, uniqid(rand()) . '-');

            $fptr = @fopen($tmp_filename, 'wb');
            $bytes_written = @fwrite($fptr, $avatar_data, $avatar_filesize);
            @fclose($fptr);

            if ( $bytes_written != $avatar_filesize )
            {
                @unlink($tmp_filename);
                message_die(GENERAL_ERROR, 'Could not write avatar file to local storage. Please contact the board administrator with this message', '', __LINE__, __FILE__);
            }

            list($width, $height) = @getimagesize($tmp_filename);
        }
        else
        {
            $l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

            $error = true;
            $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
        }
    }
    else if ( ( file_exists(@phpbb_realpath($avatar_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $avatar_realname) )
    {
        if ( $avatar_filesize <= $board_config['avatar_filesize'] && $avatar_filesize > 0 )
        {
            preg_match('#image\/[x\-]*([a-z]+)#', $avatar_filetype, $avatar_filetype);
            $avatar_filetype = $avatar_filetype[1];
        }
        else
        {
            $l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

            $error = true;
            $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
            return;
        }

        list($width, $height) = @getimagesize($avatar_filename);
    }

    if ( !($imgtype = pcp_check_image_type($avatar_filetype, $error, $error_msg)) )
    {
        return;
    }

    if ( $width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'] )
    {
        $new_filename = uniqid(rand()) . $imgtype;

        if ( $current_type == USER_AVATAR_UPLOAD && $current_avatar != '' )
        {
            if ( file_exists(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $current_avatar)) )
            {
                @unlink('./' . $board_config['avatar_path'] . '/' . $current_avatar);
            }
        }

        if( $avatar_mode == 'remote' )
        {
            @copy($tmp_filename, './' . $board_config['avatar_path'] . "/$new_filename");
            @unlink($tmp_filename);
        }
        else
        {
            if ( @$ini_val('open_basedir') != '' )
            {
                if ( @phpversion() < '4.0.3' )
                {
                    message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
                }

                $move_file = 'move_uploaded_file';
            }
            else
            {
                $move_file = 'copy';
            }

            $move_file($avatar_filename, './' . $board_config['avatar_path'] . "/$new_filename");
        }

        @chmod('./' . $board_config['avatar_path'] . "/$new_filename", 0777);

        $avatar_sql = " user_avatar = '$new_filename', user_avatar_type = " . USER_AVATAR_UPLOAD;
    }
    else
    {
        $l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

        $error = true;
        $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
    }

    return $avatar_sql;
}

function create_birthday_date($format, $date, $timezone)
{
    global $board_config, $lang;
    static $translate;

    $birthday = '';
    if (intval($date) != 0)
    {
        // create a date on year 1971
        $day = intval(substr($date, 6, 2));
        $month = intval(substr($date, 4, 2));
        $year = substr($date, 0, 4);
        $temp_date = date($format, mktime( 0, 0, 1, $month, $day, 1972));
        $birthday = str_replace( '1972', $year, $temp_date );
        if ( empty($translate) && $board_config['default_lang'] != 'english' )
        {
            @reset($lang['datetime']);
            while ( list($match, $replace) = @each($lang['datetime']) )
            {
                $translate[$match] = $replace;
            }
        }
        if (!empty($translate))
        {
            $birthday = strtr($birthday, $translate);
        }
    }
    return $birthday;
}

//-------------------------------------------
//
//  fields definitions
//
//-------------------------------------------
if ( !defined('DEF_INCLUSION_DONE') )
{
    $dir = @opendir($phpbb_root_path . './profilcp/def');
    while( $file = @readdir($dir) )
    {
        if( preg_match("/^def_.*?\." . $phpEx . "$/", $file) )
        {
            include_once($phpbb_root_path . './profilcp/def/' . $file);
        }
    }
    @closedir($dir);
    define('DEF_INCLUSION_DONE', true);
}

//-------------------------------------------
//
//  menu service function
//
//-------------------------------------------
function pcp_set_menu($mode, $sort='', $url='', $shortcut='', $page_title='')
{
    global $lang;
    global $module;
    global $user_maps;

    // get the menu idx
    $idx = count($module['mode']);
    $new = false;
    $found = false;
    for ( $i = 0; $i < count($module['mode']); $i++ )
    {
        $found = ( $module['mode'][$i] == $mode );
        if ( $found )
        {
            $idx = $i;
            break;
        }
    }

    // init
    if ( !$found )
    {
        $module['sort'][$idx]           = '';
        $module['url'][$idx]            = '';
        $module['shortcut'][$idx]       = '';
        $module['page_title'][$idx]     = '';
        $module['sub'][$idx]            = array();
    }

    // add it
    $module['mode'][$idx]           = $mode;
    $module['sort'][$idx]           = empty($module['sort'][$idx]) ? $sort : $module['sort'][$idx];
    $module['url'][$idx]            = empty($module['url'][$idx]) ? basename($url) : $module['url'][$idx];
    $module['shortcut'][$idx]       = empty($module['shortcut'][$idx]) ? $lang[$shortcut] : $module['shortcut'][$idx];
    $module['page_title'][$idx]     = empty($module['page_title'][$idx]) ? $lang[$page_title] : $module['page_title'][$idx];

    if ( isset($user_maps['PCP.' . $mode]) )
    {
        $module['sort'][$idx] = $user_maps['PCP.' . $mode]['order'];
    }

    return $idx;
}

function pcp_set_sub_menu($mode, $sub_mode, $sub_sort='', $sub_url='', $sub_shortcut='', $sub_page_title='' )
{
    global $lang;
    global $module;
    global $user_maps;

    // ensure the main menu exists
    $idx = pcp_set_menu($mode);

    // check if the sub_menu exists
    $sub_idx = count($module['sub'][$idx]['mode']);
    $found = false;
    for ( $i = 0; $i < count($module['sub'][$idx]['mode']); $i++ )
    {
        $found = ( $module['sub'][$idx]['mode'][$i] == $sub_mode );
        if ( $found )
        {
            $sub_idx = $i;
            break;
        }
    }

    // init
    if ( !$found )
    {
        $module['sub'][$idx]['sort'][$sub_idx]          = '';
        $module['sub'][$idx]['url'][$sub_idx]           = '';
        $module['sub'][$idx]['shortcut'][$sub_idx]      = '';
        $module['sub'][$idx]['page_title'][$sub_idx]    = '';
    }

    // add it
    $module['sub'][$idx]['mode'][$sub_idx]          = $sub_mode;
    $module['sub'][$idx]['sort'][$sub_idx]          = empty($module['sub'][$idx]['sort'][$sub_idx]) ? $sub_sort : $module['sub'][$idx]['sort'][$sub_idx];
    $module['sub'][$idx]['url'][$sub_idx]           = empty($module['sub'][$idx]['url'][$sub_idx]) ? basename($sub_url) : $module['sub'][$idx]['url'][$sub_idx];
    $module['sub'][$idx]['shortcut'][$sub_idx]      = empty($module['sub'][$idx]['shortcut'][$sub_idx]) ? $lang[$sub_shortcut] : $module['sub'][$idx]['shortcut'][$sub_idx];
    $module['sub'][$idx]['page_title'][$sub_idx]    = empty($module['sub'][$idx]['page_title'][$sub_idx]) ? $lang[$sub_page_title] : $module['sub'][$idx]['page_title'][$sub_idx];

    if ( isset($user_maps['PCP.' . $mode . '.' . $sub_mode]) )
    {
        $module['sub'][$idx]['sort'][$sub_idx] = $user_maps['PCP.' . $mode . '.' . $sub_mode]['order'];
    }
}

?>