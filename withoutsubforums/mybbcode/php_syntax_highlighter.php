<?php
/***************************************************************************
 *						php_syntax_highlighter.php
 *						-----------------------------
 *	begin			: 7/12/2004
 *	copyright		: Fierce Recon
 *	email			: infected2506@hotmail.com
 *
 *	version			: 0.0.2 - 7/16/2004
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
if (!defined('IN_PHPBB'))
{
	die("Hacking attempt");
}

function MyBBCode_php_first_pass($text, $uid)
{
		global $bbcode_parse;
	return $bbcode_parse->bbencode_first_pass_pda($text, $uid, '[php]', '[/php]', '', true, '');
}

function MyBBCode_php_second_pass($text, $uid)
{
	global $lang;
	$templates = load_bbcode_template();
	
	/* Below code taken from bbencode_second_pass() */
	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$code_start_html = str_replace('{L_CODE}', 'PHP ' . $lang['Code'], $templates['code_open']);;
	$code_end_html =  $templates['code_close'];

	// First, do all the 1st-level matches. These need an htmlspecialchars() run,
	// so they have to be handled differently.
	$match_count = preg_match_all("#\[php:1:$uid\](.*?)\[/php:1:$uid\]#si", $text, $matches);

	for ($i = 0; $i < $match_count; $i++)
	{
		$before_replace = $matches[1][$i];
		$after_replace = $matches[1][$i];

		// Replace 2 spaces with "&nbsp; " so non-tabbed code indents without making huge long lines.
		$after_replace = str_replace("  ", "&nbsp; ", MyBBCode_Highlight_PHP_string($after_replace));
		// now Replace 2 spaces with " &nbsp;" to catch odd #s of spaces.
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);

		// Replace tabs with "&nbsp; &nbsp;" so tabbed code indents sorta right without making huge long lines.
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);

		// now Replace space occurring at the beginning of a line
		$after_replace = preg_replace("/^ {1}/m", '&nbsp;', $after_replace);

		$str_to_match = "[php:1:$uid]" . $before_replace . "[/php:1:$uid]";
		
		$replacement = $code_start_html;
		$replacement .= $after_replace;
		$replacement .= $code_end_html;

		$text = str_replace($str_to_match, $replacement, $text);
	}

	preg_match_all("/\[php:$uid\](.*)\[\\/php:$uid\]/si", $text, $matches);
	for ($i = 0; $i < count($matches[1]); $i++)
	{
		$highlight = MyBBCode_Highlight_PHP_string($matches[1][$i]);
		$text = str_replace("[php:$uid]" . $matches[1][$i] . "[/php:$uid]",  $code_start_html . $highlight . $code_end_html, $text);
	}
	return $text;
}

function MyBBCode_Highlight_PHP_string($string)
{
	$string = MyBBCode_unhtmlentities($string);
	$phpver = phpversion();
	if ($phpver[0] == '3')
	{
		return preg_replace('#(\r|\n)#', '', $string);	//We can't do anything...
	}
	if (!function_exists('version_compare') || version_compare($phpver, '4.2.0', '<'))
	{
		@ob_end_clean();
		@ob_start();
		highlight_string(trim($string));
		$highlight = @ob_get_contents();
		@ob_end_clean();
	}
	else
	{
		$highlight = highlight_string(trim($string), true);
	}
	return preg_replace('#(\r|\n)#', '', $highlight);
}

function MyBBCode_unhtmlentities($string)  
{
	$code_entities_replace = array('<', '>', '"', ':', '[', ']', '(', ')', '{', '}');
	$code_entities_match = array('&lt;', '&gt;', '&quot;', '&#58;', '&#91;', '&#93;', '&#40;', '&#41;', '&#123;', '&#125;');
	for ($i = 0; $i < count($code_entities_match); $i++)
	{
		$string = str_replace($code_entities_match[$i], $code_entities_replace[$i], $string);
	}
	return $string;
} 
?>