<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_bbcodes.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_bbcodes.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

//-----------------------------------
//
//	Send bbCodes def to template
//
//-----------------------------------
function bbcodes_posting()
{
	global $template, $lang, $images, $bbcode_parse;

	$template->assign_vars(array(
		'L_BBCODE_B_HELP' => $lang['bbcode_b_help'],
		'L_BBCODE_I_HELP' => $lang['bbcode_i_help'],
		'L_BBCODE_U_HELP' => $lang['bbcode_u_help'],
		'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'],
		'L_BBCODE_C_HELP' => $lang['bbcode_c_help'],
		'L_BBCODE_L_HELP' => $lang['bbcode_l_help'],
		'L_BBCODE_O_HELP' => $lang['bbcode_o_help'],
		'L_BBCODE_P_HELP' => $lang['bbcode_p_help'],
		'L_BBCODE_W_HELP' => $lang['bbcode_w_help'],
		'L_BBCODE_A_HELP' => $lang['bbcode_a_help'],
		'L_BBCODE_S_HELP' => $lang['bbcode_s_help'],
		'L_BBCODE_F_HELP' => $lang['bbcode_f_help'],
		'L_EMPTY_MESSAGE' => $lang['Empty_message'],

		'L_FONT_COLOR' => $lang['Font_color'],
		'L_COLOR_DEFAULT' => $lang['color_default'],
		'L_COLOR_DARK_RED' => $lang['color_dark_red'],
		'L_COLOR_RED' => $lang['color_red'],
		'L_COLOR_ORANGE' => $lang['color_orange'],
		'L_COLOR_BROWN' => $lang['color_brown'],
		'L_COLOR_YELLOW' => $lang['color_yellow'],
		'L_COLOR_GREEN' => $lang['color_green'],
		'L_COLOR_OLIVE' => $lang['color_olive'],
		'L_COLOR_CYAN' => $lang['color_cyan'],
		'L_COLOR_BLUE' => $lang['color_blue'],
		'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
		'L_COLOR_INDIGO' => $lang['color_indigo'],
		'L_COLOR_VIOLET' => $lang['color_violet'],
		'L_COLOR_WHITE' => $lang['color_white'],
		'L_COLOR_BLACK' => $lang['color_black'],

		'L_FONT_SIZE' => $lang['Font_size'],
		'L_FONT_TINY' => $lang['font_tiny'],
		'L_FONT_SMALL' => $lang['font_small'],
		'L_FONT_NORMAL' => $lang['font_normal'],
		'L_FONT_LARGE' => $lang['font_large'],
		'L_FONT_HUGE' => $lang['font_huge'],

		'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
		'L_STYLES_TIP' => $lang['Styles_tip'],
		)
	);
}
?>