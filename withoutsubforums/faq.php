<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: faq.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME	 : faq.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team and © 2001, 2003 The phpBB	Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'),	1);

include($phpbb_root_path . 'common.'.$phpEx);

$layout	= $core_layout[LAYOUT_FAQ];

//
// Start session management
//
$userdata =	session_pagestart($user_ip,	PAGE_FAQ);
init_userprefs($userdata);
//
// End session management
//

// Set vars	to prevent naughtiness
$faq = array();	
$allmodes =	array();

//--------------------------------------------------------------------------------
// Prillian	- Begin	Code Addition
//

include_once(PRILL_PATH	. 'prill_common.' .	$phpEx);

//
// Prillian	- End Code Addition
//--------------------------------------------------------------------------------

//
// MOD:	Custom FAQs	Page Links
//

//
// Prepare FAQ names, file names, and modes
//

$allmodes['faq'] = array('lang_file' =>	'lang_faq',
						'l_title' => $lang['Forum']	. '	' .	$lang['FAQ'],
						'mode' => 'faq');

$allmodes['bbcode']	= array('lang_file'	=> 'lang_bbcode',
						'l_title' => $lang['BBCode_guide'],
						'mode' => 'bbcode');

$allmodes['attach']	= array('lang_file'	=> 'lang_faq_attach',
						'l_title' => $lang['Attach_guide'],
						'mode' => 'attach');

$allmodes['prill'] = array('lang_file' => 'lang_prillian_faq',
						'l_title' => $lang['Prillian_FAQ'],
						'mode' => 'prill');
/*
To add more	FAQs:
Copy the block below and paste it above	the	"/*" line.	You'll also	need to	fill
in some	information.  This list	should explain that:
MODE_NAME -	The	mode used to access	the	file in	a url, like	faq.php?mode=MODE_NAME
FILE_NAME -	The	filename of	your new lang_faq.php style	file, without the file
			extension. For example,	if the file	is lang_cool.php, use 'lang_cool'
LANG_FAQ_TITLE - The title of the FAQ and the name used	in the link. This needs	to
			be the same	as the variable	you'll add to lang_main.php	later.
Copy the block for each	new	FAQ	and	be sure	to leave the single	quotes in.
$lang['Cool_Stuff']	is good, $lang[Cool_Stuff] is bad.


$allmodes['MODE_NAME'] = array('lang_file' => 'FILE_NAME',
						'l_title' => $lang['LANG_FAQ_TITLE'],
						'mode' => 'MODE_NAME');
*/

//
// MOD:	-END-
//

//
// Load	the	appropriate	faq	file
//
if(	isset($HTTP_GET_VARS['mode']) )
{
	switch(	$HTTP_GET_VARS['mode'] )
	{
		case 'prill':
			$lang_file = $allmodes[$HTTP_GET_VARS['mode']]['lang_file'];
			$l_title = $allmodes[$HTTP_GET_VARS['mode']]['l_title'];
			$mode =	$allmodes[$HTTP_GET_VARS['mode']]['mode'];
			break;
		case 'attach':
			$lang_file = $allmodes[$HTTP_GET_VARS['mode']]['lang_file'];
			$l_title = $allmodes[$HTTP_GET_VARS['mode']]['l_title'];
			$mode =	$allmodes[$HTTP_GET_VARS['mode']]['mode'];
			break;
		case 'bbcode':
			$lang_file = $allmodes[$HTTP_GET_VARS['mode']]['lang_file'];
			$l_title = $allmodes[$HTTP_GET_VARS['mode']]['l_title'];
			$mode =	$allmodes[$HTTP_GET_VARS['mode']]['mode'];
			break;
		case 'faq':
		default:
			$lang_file = $allmodes['faq']['lang_file'];
			$l_title = $allmodes['faq']['l_title'];
			$mode =	$allmodes['faq']['mode'];
			break;
	}
}
else
{
	$lang_file = $allmodes['faq']['lang_file'];
	$l_title = $allmodes['faq']['l_title'];
	$mode =	$allmodes['faq']['mode'];
}

include($phpbb_root_path . 'language/lang_'	. $board_config['default_lang']	. '/' .	$lang_file . '.' . $phpEx);

attach_faq_include($lang_file);

//
// Pull	the	array data from	the	lang pack
//
$j = 0;
$counter = 0;
$counter_2 = 0;
$faq_block = array();
$faq_block_titles =	array();

for($i = 0,	$faq_count = count($faq); $i < $faq_count; $i++)
{
	if(	$faq[$i][0]	!= '--'	)
	{
		$faq_block[$j][$counter]['id'] = $counter_2;
		$faq_block[$j][$counter]['question'] = $faq[$i][0];
		$faq_block[$j][$counter]['answer'] = $faq[$i][1];

		$counter++;
		$counter_2++;
	}
	else
	{
		$j = ( $counter	!= 0 ) ? $j	+ 1	: 0;

		$faq_block_titles[$j] =	$faq[$i][1];

		$counter = 0;
	}
}

//
// Lets	build a	page ...
//
$page_title	= $l_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'faq_body.tpl')
);

//
// MOD:	Custom FAQs	Page Links
//

$template->assign_block_vars('other_faqs', array(
	'L_OTHER_FAQ_TITLE'	=> $lang['Other_FAQs'])
);

$allmodes_first_pass = TRUE;

//reset($allmodes);
foreach ( $allmodes as $key => $val )
//while (	list( $key,	$val) =	each( $allmodes	) )
{
	if(	$allmodes_first_pass )
	{
		$divider  =	'';
	}
	else
	{
		$divider = ' | ';
	}

	if(	$mode != $key )
	{
		$allmodes_first_pass = FALSE;

		$template->assign_block_vars('other_faqs.other_faqs_link', array(
			'U_OTHER_FAQ' => append_sid("faq.$phpEx?mode=" . $val['mode'], true),
			'OTHER_FAQ_NAME' =>	$val['l_title'],
			'DIVIDER' => $divider)
		);
	}
}

//
// MOD:	-END-
//

$template->assign_vars(array(
	'L_FAQ_TITLE' => $l_title,
	'L_BACK_TO_TOP'	=> $lang['Back_to_top'])
);

for($i = 0,	$faq_block_count = count($faq_block); $i < $faq_block_count; $i++)
{
	$max = count($faq_block[$i]);

	if(	$max )
	{
		$template->assign_block_vars('faq_block', array(
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);
		$template->assign_block_vars('faq_block_link', array(
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);

		for($j = 0;	$j < $max; $j++)
		{
			$row_color = ( !($j	% 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($j	% 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('faq_block.faq_row', array(
				'ROW_COLOR'	=> '#' . $row_color,
				'ROW_CLASS'	=> $row_class,
				'FAQ_QUESTION' => $faq_block[$i][$j]['question'],
				'FAQ_ANSWER' =>	$faq_block[$i][$j]['answer'],

				'U_FAQ_ID' => $faq_block[$i][$j]['id'])
			);

			$template->assign_block_vars('faq_block_link.faq_row_link',	array(
				'ROW_COLOR'	=> '#' . $row_color,
				'ROW_CLASS'	=> $row_class,
				'FAQ_LINK' => $faq_block[$i][$j]['question'],

				'U_FAQ_LINK' =>	'#'	. $faq_block[$i][$j]['id'])
			);
		}
	}
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>