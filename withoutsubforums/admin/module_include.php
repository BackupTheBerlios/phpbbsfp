<?php
define('MODULE_HEADER_START', 'module_01<mv>');
define('MODULE_HEADER_END', '</mv>');
define('MODULE_HEADER_VERSION', '1');
define('MODULE_EXTENSION', '.mvmd');
define('TAR_HEADER_PACK', 'a100A8A8A8A12A12A8A1A100A6A2A32A32A8A8a155a12');
define('TAR_HEADER_UNPACK', 'a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor/a155prefix/a12extra');
define('MV_MAX_ITEMS_PER_MODULE', 32);
define('MV_TEMP_DIR', '../cache/');
define('MODULE_FTP_LOCAL', 'no_ftp');
define('MV_UPDATE_MODULES', 1);
define('MV_UPDATE_MOD', 2);
define('MV_UPDATE_PHPBB', 3);
define('MV_MODULE_PATH', '../../templates/subSilver/admin/');
define('MV_BACKUP_PREFIX', 'backup.');
define('MV_BACKUP_EXT', '.backup');
function get_module_header($filename, $str = '')
{
    /*
    header format (v0.01):
    - header
    - header size (4 bytes)
    - file size (4 bytes)
    - number of entries (1 byte)
    - entries sizes (number_of_entries bytes)
    - entries
    - footer
    - gzcompressed tar of style (no crc check in tar)

    entries:
      - template name
      - comment
      - style names
    */
    global $xs_header_error, $lang;
    $xs_header_error = '';
    if(!$str)
    {
        $f = @fopen($filename, 'rb');
        if(!$f)
        {
            $xs_header_error = $lang['xs_style_header_error_file'];
            return false;
        }
        $str = fread($f, 10240);
        fclose($f);
    }
    if(substr($str, 0, strlen(MODULE_HEADER_START)) !== MODULE_HEADER_END)
    {
        if(substr($str, 0, 7) === 'error: ')
        {
            $xs_header_error = '<br /><br />' . $lang['xs_style_header_error_server'] . substr($str, 7);
        }
        else
        {
            $xs_header_error = $lang['xs_style_header_error_invalid'];
        }
        return false;
    }
    $start = strlen(MODULE_HEADER_START);
    $str1 = substr($str, $start, 8);
    $data = unpack('Nvar1/Nvar2', $str1);
    $start += 8;
    $header_size = $data['var1'];
    $filesize = $data['var2'];
    $total = ord($str{$start});
    $start ++;
    if($total < 3)
    {
        $xs_header_error = $lang['xs_style_header_error_invalid'];
        return false;
    }
    $items_len = array();
    for($i=0; $i<$total; $i++)
    {
        $items_len[$i] = ord($str{$i+$start});
    }
    $start += $total;
    $items = array();
    $tpl = '';
    for($i=0; $i<$total; $i++)
    {
        $str1 = substr($str, $start, $items_len[$i]);
        if($i == 0) $tpl = $str1;
        elseif($i == 1) $comment = $str1;
        else    $items[] = $str1;
        $start += $items_len[$i];
    }
    if(substr($str, $start, strlen(MODULE_HEADER_START)) !== MODULE_HEADER_END)
    {
        $xs_header_error = $lang['xs_style_header_error_invalid'];
        return false;
    }
    return array(
        'template'  => $tpl,
        'styles'    => $items,
        'date'      => @filemtime($filename),
        'comment'   => $comment,
        'offset'    => $header_size,
        'filename'  => $filename,
        'filesize'  => $filesize,
        );
}
function module_ftp_connect($action, $post = array(), $allow_local = false)
{
    global $ftp, $board_config, $HTTP_POST_VARS, $phpEx, $lang, $template;
    $HTTP_POST_VARS['get_ftp_config'] = '';
    if($allow_local && !empty($board_config['module_ftp_local']))
    {
        $ftp = module_ftp_LOCAL;
        return true;
    }
    $ftp = @ftp_connect($board_config['module_ftp_host']);
    if(!$ftp)
    {
        get_ftp_config($action, $post, $allow_local, str_replace('{HOST}', $board_config['module_ftp_host'], $lang['module_ftp_error_connect']));
    }
    $res = @ftp_login($ftp, $board_config['module_ftp_login'], $board_config['module_ftp_pass']);
    if(!$res)
    {
        get_ftp_config($action, $post, $allow_local, $lang['module_ftp_error_login']);
    }
    $res = @ftp_chdir($ftp, $board_config['module_ftp_path']);
    if(!$res)
    {
        get_ftp_config($action, $post, $allow_local, str_replace('{DIR}', $board_config['module_ftp_path'], $lang['module_ftp_error_chdir']));
    }
    // check current directory
    $current_dir = @ftp_pwd($ftp);
    $list = @ftp_nlist($ftp, $current_dir);
    for($i=0; $i<count($list); $i++)
    {
        $list[$i] = strtolower(basename($list[$i]));
    }
    // check few files
    $check = array('extension.inc', 'templates', 'xs_mod');
    $found = array(false, false, false);
    for($i=0; $i<count($list); $i++)
    {
        for($j=0; $j<count($check); $j++)
        {
            if($list[$i] === $check[$j])
            {
                $found[$j] = true;
            }
        }
    }
    $error = false;
    for($i=0; $i<count($check); $i++)
    {
        if(!$found[$i])
        {
            $error = true;
        }
    }
    if($error)
    {
        get_ftp_config($action, $post, $allow_local, $lang['module_ftp_error_nonphpbbdir']);
    }
    $HTTP_POST_VARS['get_ftp_config'] = '1';
}
function module_name($name)
{
    return str_replace(array('\\', '/', "'", '"'), array('','','',''), $name);
}
function module_dir_writable($dir)
{
    $filename = 'tmp_' . time();
    $f = @fopen($dir . $filename, 'wb');
    if($f)
    {
        fclose($f);
        @unlink($dir . $filename);
        return true;
    }
    return false;
}
function module_sql($sql, $stip = false)
{
    if($strip)
    {
        $sql = stripslashes($sql);
    }
    return str_replace("'", "''", $sql);
}
function get_ftp_config($action, $post = array(), $allow_local = false, $show_error = '')
{
    global $template, $board_config, $db, $lang, $HTTP_POST_VARS, $HTTP_SERVER_VARS;
    $board_config['module_ftp_local'] = false;
    // check if ftp can be used
    if(!@function_exists('ftp_connect'))
    {
        if($allow_local && module_dir_writable('../templates/'))
        {
            $board_config['module_ftp_local'] = true;
            return true;
        }
        message_die(GENERAL_ERROR, $lang['module_ftp_error_fatal']);
    }
    // check if we have configuration
    if(!empty($HTTP_POST_VARS['get_ftp_config']))
    {
        $vars = array('module_ftp_host', 'module_ftp_login', 'module_ftp_path');
        for($i=0; $i<count($vars); $i++)
        {
            $var = $vars[$i];
            if($board_config[$var] !== $HTTP_POST_VARS[$var])
            {
                $board_config[$var] = stripslashes($HTTP_POST_VARS[$var]);
                $sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . module_sql($board_config[$var]) . "' WHERE config_name = '{$var}'";
                $db->sql_query($sql);
            }
        }
        $board_config['module_ftp_pass'] = stripslashes($HTTP_POST_VARS['module_ftp_pass']);
        $board_config['module_ftp_local'] = empty($HTTP_POST_VARS['module_ftp_local']) ? false : true;
        return true;
    }
    // check ftp configuration
    $module_ftp_host = $board_config['module_ftp_host'];
    if(empty($module_ftp_host))
    {
        $str = $HTTP_SERVER_VARS['HTTP_HOST'];
        $template->assign_vars(array(
            'HOST_GUESS' => str_replace(array('{HOST}', '{CLICK}'), array($str, 'document.ftp.module_ftp_host.value=\''.$str.'\''), $lang['module_ftp_host_guess'])
            ));
    }
    $dir = getcwd();
    $module_ftp_login = $board_config['module_ftp_login'];
    if(empty($module_ftp_login))
    {
        if(substr($dir, 0, 6) === '/home/')
        {
            $str = substr($dir, 6);
            $pos = strpos($str, '/');
            if($pos)
            {
                $str = substr($str, 0, $pos);
                $template->assign_vars(array(
                    'LOGIN_GUESS' => str_replace(array('{LOGIN}', '{CLICK}'), array($str, 'document.ftp.module_ftp_login.value=\''.$str.'\''), $lang['module_ftp_login_guess'])
                ));
            }
        }
    }
    $module_ftp_path = $board_config['module_ftp_path'];
    if(empty($module_ftp_path))
    {
        if(substr($dir, 0, 6) === '/home/');
        $str = substr($dir, 6);
        $pos = strpos($str, '/');
        if($pos)
        {
            $str = substr($str, $pos + 1);
            $pos = strrpos($str, 'admin');
            if($pos)
            {
                $str = substr($str, 0, $pos-1);
                $template->assign_vars(array(
                    'PATH_GUESS' => str_replace(array('{PATH}', '{CLICK}'), array($str, 'document.ftp.module_ftp_path.value=\''.$str.'\''), $lang['module_ftp_path_guess'])
                ));
            }
        }
    }
    if($allow_local && module_dir_writable('../modules/'))
    {
        $template->assign_block_vars('module_ftp_local', array());
    }
    else
    {
        $template->assign_block_vars('module_ftp_nolocal', array());
    }
    $str = '<input type="hidden" name="get_ftp_config" value="1" />';
    foreach($post as $var => $value)
    {
        $str .= '<input type="hidden" name="' . htmlspecialchars($var) . '" value="' . htmlspecialchars($value) . '" />';
    }
    $template->assign_vars(array(
            'FORM_ACTION'       => $action,
            'S_EXTRA_FIELDS'    => $str,
            'module_ftp_HOST'       => $module_ftp_host,
            'module_ftp_LOGIN'      => $module_ftp_login,
            'module_ftp_PATH'       => $module_ftp_path,
        ));
    if($show_error)
    {
        $template->assign_block_vars('error', array('MSG' => $show_error));
    }
    $template->set_filenames(array('config' => 'module_config_ftp.tpl'));
    $template->pparse('config');
    return false;
}
?>
