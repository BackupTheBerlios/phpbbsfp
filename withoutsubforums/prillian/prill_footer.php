<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: prill_footer.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : prill_footer.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Thoul
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

//
// Unload the Cache.
//
$cache->unload();

// Close the database connection.
$db->sql_close();

if( $full_footer )
{
    // Add page footer.
    $template->set_filenames(array(
        'footer' => 'prillian/prill_footer.tpl')
    );

    $template->assign_vars(array(
        'PHPBB_VERSION' => '3' . $board_config['version'],
        'TRANSLATION_INFO' => ( isset($lang['TRANSLATION_INFO']) ) ? $lang['TRANSLATION_INFO'] : ''
    ));
    $template->pparse('footer');
}

//
// Compress buffered output if required and send to browser
//
if ( $do_gzip_compress )
{
    //
    // Borrowed from php.net!
    //
    $gzip_contents = ob_get_contents();
    ob_end_clean();

    $gzip_size = strlen($gzip_contents);
    $gzip_crc = crc32($gzip_contents);

    $gzip_contents = gzcompress($gzip_contents, 9);
    $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

    echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
    echo $gzip_contents;
    echo pack('V', $gzip_crc);
    echo pack('V', $gzip_size);
}

exit;

?>