<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: attachment_mod.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : attachment_mod.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2002 Meik Sievertsen
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
    exit;
}

include($phpbb_root_path . 'attach/includes/functions_includes.'.$phpEx);
include($phpbb_root_path . 'attach/includes/functions_attach.'.$phpEx);
include($phpbb_root_path . 'attach/includes/functions_delete.'.$phpEx);
include($phpbb_root_path . 'attach/includes/functions_thumbs.'.$phpEx);
include($phpbb_root_path . 'attach/includes/functions_filetypes.'.$phpEx);

function include_attach_lang()
{
    global $phpbb_root_path, $phpEx, $lang, $board_config, $attach_config;

    //
    // Include Language
    //
    $language = $board_config['default_lang'];

    if ( !@file_exists(@realpath($phpbb_root_path . 'language/lang_' . $language . '/lang_main_attach.'.$phpEx)) )
    {
        $language = $attach_config['board_lang'];
    }

    include($phpbb_root_path . 'language/lang_' . $language . '/lang_main_attach.' . $phpEx);

    if ( defined('IN_ADMIN') )
    {
        if( !@file_exists(@realpath($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_attach.'.$phpEx)) )
        {
            $language = $attach_config['board_lang'];
        }

        include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_attach.' . $phpEx);
    }

}

function get_attach_config()
{
    global $db, $board_config;

    $attach_config = array();

    $sql = 'SELECT *
    FROM ' . ATTACH_CONFIG_TABLE;

    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not query attachment information', '', __LINE__, __FILE__, $sql);
    }

    while ($row = $db->sql_fetchrow($result))
    {
        $attach_config[$row['config_name']] = trim($row['config_value']);
    }

    $attach_config['board_lang'] = trim($board_config['default_lang']);

    return ($attach_config);
}

//
// Get Attachment Config
//

$attach_config = array();

if ($cache->exists('attach.config'))
{
	$attach_config = $cache->get('attach.config');
}
else
{
    $attach_config = get_attach_config();

	$cache->put('attach.config', $attach_config);
}


// Please do not change the include-order, it is valuable for proper execution.
// Functions for displaying Attachment Things
include($phpbb_root_path . 'attach/displaying.'.$phpEx);
// Posting Attachments Class (HAVE TO BE BEFORE PM)
/*
include($phpbb_root_path . 'attach/posting_attachments.'.$phpEx);
// PM Attachments Class
include($phpbb_root_path . 'attach/pm_attachments.'.$phpEx);
*/

if (!intval($attach_config['allow_ftp_upload']))
{
    $upload_dir = $attach_config['upload_dir'];
}
else
{
    $upload_dir = $attach_config['download_path'];
}

?>