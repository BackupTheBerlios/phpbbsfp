<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_selects.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_selects.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

//
// Pick a auth mode
//
function auth_select($default, $select_name = 'auth_mode')
{
    $selected = '';

    $auth_mode_select = '<select name="' . $select_name . '">';
    $auth_mode_select .= '<option value="phpbb"' . (($default == 'phpbb')? 'selected' : '') . '>phpBB</option>';
    $auth_mode_select .= '<option value="ldap"' .  (($default == 'ldap')? 'selected' : '') . '>LDAP</option>';
    //$auth_mode_select .= '<option value="ldap_phpbb"' . (($default == 'ldap_phpbb')? 'selected' : '') . '>LDAP and phpBB</option>';
    $auth_mode_select .= '</select>';

    return $auth_mode_select;
}

//
// Pick a user auth type
//
function user_type_select($default, $select_name = 'usertype')
{
    $type_select = '';

    $type_select = '<select name="' . $select_name . '">';
    $type_select .= '<option value=' . User_Type_Both . (($default == User_Type_Both)? ' selected' : '') . '>LDAP or phpBB</option>';
    $type_select .= '<option value=' .  User_Type_LDAP . (($default == User_Type_LDAP)? ' selected' : '') . '>LDAP Only</option>';
    $type_select .= '<option value=' . User_Type_phpBB . (($default == User_Type_phpBB)? ' selected' : '') . '>phpBB Only</option>';
    $type_select .= '</select>';

    return $type_select;
}

//
// Pick a language, any language ...
//
function language_select($default, $select_name = "language", $dirname="language")
{
    global $phpEx, $phpbb_root_path;

    $dir = opendir($phpbb_root_path . $dirname);

    $lang = array();
    while ( $file = readdir($dir) )
    {
        if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)))
        {
            $filename = trim(str_replace("lang_", "", $file));
            $displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
            $displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
            $lang[$displayname] = $filename;
        }
    }

    closedir($dir);

    @asort($lang);
    @reset($lang);

    $lang_select = '<select name="' . $select_name . '">';
    while ( list($displayname, $filename) = @each($lang) )
    {
        $selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
        $lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
    }
    $lang_select .= '</select>';

    return $lang_select;
}

//
// Pick a template/theme combo,
//
function style_select($default_style, $select_name = "style", $dirname = "templates")
{
    global $db;

    $sql = "SELECT themes_id, style_name
        FROM " . THEMES_TABLE . "
        ORDER BY template_name, themes_id";
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
    }

    $style_select = '<select name="' . $select_name . '">';
    while ( $row = $db->sql_fetchrow($result) )
    {
        $selected = ( $row['themes_id'] == $default_style ) ? ' selected="selected"' : '';

        $style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . $row['style_name'] . '</option>';
    }
    $style_select .= "</select>";

    return $style_select;
}

//
// Pick a timezone
//
function tz_select($default, $select_name = 'timezone')
{
    global $sys_timezone, $lang;

    if ( !isset($default) )
    {
        $default == $sys_timezone;
    }
    $tz_select = '<select name="' . $select_name . '">';

    while( list($offset, $zone) = @each($lang['tz']) )
    {
        $selected = ( $offset == $default ) ? ' selected="selected"' : '';
        $tz_select .= '<option value="' . $offset . '"' . $selected . '>' . $zone . '</option>';
    }
    $tz_select .= '</select>';

    return $tz_select;
}

?>
