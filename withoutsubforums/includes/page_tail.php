<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: page_tail.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : page_tail.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

global $cache, $db, $template, $userdata, $board_config;
global $do_gzip_compress, $gen_simple_header, $mvModuleCopyright, $starttime;

//
// Show the overall footer.
//

// Jnr. Admin
include_once($phpbb_root_path . 'includes/functions_jr_admin.' . $phpEx);
$admin_link = jr_admin_make_admin_link();

$page_gen = '';
$legal = '';

$tpl_name = 'overall_footer.tpl';

if ( isset($gen_simple_header) && !empty($gen_simple_header) )
{
	$tpl_name = 'simple_footer.tpl';
}
else
{
	if ( $userdata['session_logged_in'] && $userdata['user_level'] == ADMIN )
	{
		$gzip_text = ($board_config['gzip_compress']) ? 'GZIP : On' : 'GZIP : Off';
		
		$debug_text = (DEBUG == 1) ? 'Debug : On' : 'Debug : Off';
		
		$excuted_queries = $db->num_queries;
		
		$endtime = explode(' ', microtime());
		$gentime = round((($endtime[1] + $endtime[0]) - $starttime), 4);
		$sql_time = round($db->sql_time, 4);
		
		$sql_part = round($sql_time / $gentime * 100);
		$php_part = 100 - $sql_part;
		
		$page_gen = 'Page Generation : '. $gentime .'s (PHP: '. $php_part .'% - SQL: '. $sql_part .'%) - SQL queries: '. $excuted_queries .' - '. $gzip_text .' - '. $debug_text;
	}
	$temp_url = '<a href="' . append_sid($phpbb_root_path . "rules.$phpEx?id=%s") . '">%s</a>';

	$copyright = get_rule(COPY_ID, 'text');
	$terms = ( get_rule(TERMS_ID, 'text') ) ? sprintf($temp_url, TERMS_ID, $lang['Terms_of_Use']) : '';
	$privacy = ( get_rule(PRIVACY_ID, 'text') ) ? sprintf($temp_url, PRIVACY_ID, $lang['Privacy_Policy']) : '';
	$legal .= ( $copyright ) ? $copyright . '<br />' : '';
	$legal .= ( $terms xor $privacy ) ? ($terms . $privacy) : (( $terms && $privacy ) ? ($terms . ' | ' . $privacy) : '');
}


$template->set_filenames(array(
	'overall_footer' => $tpl_name)
);

$template->assign_vars(array(
	'MINERVA_VERSION'	=> '3' . $board_config['version'],
	'TRANSLATION_INFO'	=> ( isset($lang['TRANSLATION_INFO']) ) ? trim($lang['TRANSLATION_INFO']) : '',
	'ADMIN_LINK'		=> $admin_link,
	'PAGE_GENERATION'	=> $page_gen,
	'LEGAL_INFO'		=> $legal,
	'MODULE_INFO'		=> trim($mvModuleCopyright . ' ' . (( isset($lang['MODULE_TRANSLATION_INFO']) ) ? $lang['MODULE_TRANSLATION_INFO'] : '')),
));

$template->pparse('overall_footer');

//
// Unload the Cache.
//
$cache->unload();

//
// Close our DB connection.
//
$db->sql_close();

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