<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: page_header_admin.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : page_header_admin.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

define('HEADER_INC', true);

//
// gzip_compression
//
$do_gzip_compress =	FALSE;
if ( $board_config['gzip_compress'] && !headers_sent() && defined('ZLIB_LOADED') )
{
	$phpver	= phpversion();

	$accept_encoding = ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) ? strtolower($_SERVER['HTTP_ACCEPT_ENCODING']) : '';

	if ( strpos($accept_encoding, 'gzip') !== FALSE )
	{
		if ( $phpver >=	'4.0.4pl1' && strpos($accept_encoding, 'deflate') !== FALSE )
		{
			//
			// Here	we updated the gzip function.
			// With	this method we can get the server up
			// to 10% faster
			//
			ob_start('ob_gzhandler');
		}
		elseif ( $phpver > '4.0' )
		{
			$do_gzip_compress = TRUE;
			ob_start();
			ob_implicit_flush(0);
			header('Content-Encoding: gzip');
		}
	}
}

$template->set_filenames(array(
    'header' => 'page_header.tpl')
);

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

//
// The following assigns all _common_ variables that may be used at any point
// in a template. Note that all URL's should be wrapped in append_sid, as
// should all S_x_ACTIONS for forms.
//
$template->assign_vars(array(
    'SITENAME' => $board_config['sitename'],
    'PAGE_TITLE' => $page_title,

    'L_ADMIN' => $lang['Admin'],
    'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
    'L_FAQ' => $lang['FAQ'],

    'U_INDEX' => append_sid('../index.'.$phpEx),

    'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
    'S_LOGIN_ACTION' => append_sid('../login.'.$phpEx),
    'S_JUMPBOX_ACTION' => append_sid('../viewforum.'.$phpEx),
    'S_CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
    'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
    'S_CONTENT_ENCODING' => $lang['ENCODING'],
    'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
    'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],

    'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
    'T_BODY_BACKGROUND' => $theme['body_background'],
    'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
    'T_BODY_TEXT' => '#'.$theme['body_text'],
    'T_BODY_LINK' => '#'.$theme['body_link'],
    'T_BODY_VLINK' => '#'.$theme['body_vlink'],
    'T_BODY_ALINK' => '#'.$theme['body_alink'],
    'T_BODY_HLINK' => '#'.$theme['body_hlink'],
    'T_TR_COLOR1' => '#'.$theme['tr_color1'],
    'T_TR_COLOR2' => '#'.$theme['tr_color2'],
    'T_TR_COLOR3' => '#'.$theme['tr_color3'],
    'T_TR_CLASS1' => $theme['tr_class1'],
    'T_TR_CLASS2' => $theme['tr_class2'],
    'T_TR_CLASS3' => $theme['tr_class3'],
    'T_TH_COLOR1' => '#'.$theme['th_color1'],
    'T_TH_COLOR2' => '#'.$theme['th_color2'],
    'T_TH_COLOR3' => '#'.$theme['th_color3'],
    'T_TH_CLASS1' => $theme['th_class1'],
    'T_TH_CLASS2' => $theme['th_class2'],
    'T_TH_CLASS3' => $theme['th_class3'],
    'T_TD_COLOR1' => '#'.$theme['td_color1'],
    'T_TD_COLOR2' => '#'.$theme['td_color2'],
    'T_TD_COLOR3' => '#'.$theme['td_color3'],
    'T_TD_CLASS1' => $theme['td_class1'],
    'T_TD_CLASS2' => $theme['td_class2'],
    'T_TD_CLASS3' => $theme['td_class3'],
    'T_FONTFACE1' => $theme['fontface1'],
    'T_FONTFACE2' => $theme['fontface2'],
    'T_FONTFACE3' => $theme['fontface3'],
    'T_FONTSIZE1' => $theme['fontsize1'],
    'T_FONTSIZE2' => $theme['fontsize2'],
    'T_FONTSIZE3' => $theme['fontsize3'],
    'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
    'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
    'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
    'T_SPAN_CLASS1' => $theme['span_class1'],
    'T_SPAN_CLASS2' => $theme['span_class2'],
    'T_SPAN_CLASS3' => $theme['span_class3'])
);


$template->pparse('header');

?>
