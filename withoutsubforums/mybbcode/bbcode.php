<?php

if (!defined('IN_PHPBB'))
{
	die("Hacking attempt");
}
// global that holds loaded-and-prepared bbcode templates, so we only have to do
// that stuff once.
global $bbcode_tpl;
$bbcode_tpl = prepare_bbcode_template(load_bbcode_template());

/**
 * Loads bbcode templates from the bbcode.tpl file of the current template set.
 * Creates an array, keys are bbcode names like "b_open" or "url", values
 * are the associated template.
 * Probably pukes all over the place if there's something really screwed
 * with the bbcode.tpl file.
 *
 * Nathan Codding, Sept 26 2001.
 */
function load_bbcode_template()
{	
	$codes = MyBBCode_GetCodes();
	for ($i = 0; $i < count($codes); $i++)
	{
		$bbcode_tpl[(($codes[$i]['tag']) ? $codes[$i]['tag'] : $codes[$i]['open_tag']) . '_open'] = $codes[$i]['tag_open'];
		$bbcode_tpl[(($codes[$i]['tag']) ? $codes[$i]['tag'] : $codes[$i]['open_tag']) . '_close'] = $codes[$i]['tag_close'];
	}
	return $bbcode_tpl;
}


/**
 * Prepares the loaded bbcode templates for insertion into preg_replace()
 * or str_replace() calls in the bbencode_second_pass functions. This
 * means replacing template placeholders with the appropriate preg backrefs
 * or with language vars. NOTE: If you change how the regexps work in
 * bbencode_second_pass(), you MUST change this function.
 *
 * Nathan Codding, Sept 26 2001
 *
 */
function prepare_bbcode_template($bbcode_tpl)
{
	global $lang;

	$bbcode_tpl['olist_open'] = str_replace('{LIST_TYPE}', '\\1', $bbcode_tpl['olist_open']);

	$bbcode_tpl['color_open'] = str_replace('{COLOR}', '\\1', $bbcode_tpl['color_open']);

	$bbcode_tpl['size_open'] = str_replace('{SIZE}', '\\1', $bbcode_tpl['size_open']);

	$bbcode_tpl['quote_open'] = str_replace('{L_QUOTE}', $lang['Quote'], $bbcode_tpl['quote_open']);

	$bbcode_tpl['quote_username_open'] = str_replace('{L_QUOTE}', $lang['Quote'], $bbcode_tpl['quote_username_open']);
	$bbcode_tpl['quote_username_open'] = str_replace('{L_WROTE}', $lang['wrote'], $bbcode_tpl['quote_username_open']);
	$bbcode_tpl['quote_username_open'] = str_replace('{USERNAME}', '\\1', $bbcode_tpl['quote_username_open']);

	$bbcode_tpl['code_open'] = str_replace('{L_CODE}', $lang['Code'], $bbcode_tpl['code_open']);

	$bbcode_tpl['img'] = $bbcode_tpl['img_open'] . '\\1' . $bbcode_tpl['img_close'];

	// We do URLs in several different ways..
	$bbcode_tpl['url'] = $bbcode_tpl['url_open'] . '{DESCRIPTION}' . $bbcode_tpl['url_close'];
	
	$bbcode_tpl['url1'] = str_replace('{TAG_ATTR}', '\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url1'] = str_replace('{DESCRIPTION}', '\\1', $bbcode_tpl['url1']);

	$bbcode_tpl['url2'] = str_replace('{TAG_ATTR}', 'http://\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url2'] = str_replace('{DESCRIPTION}', '\\1', $bbcode_tpl['url2']);

	$bbcode_tpl['url3'] = str_replace('{TAG_ATTR}', '\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url3'] = str_replace('{DESCRIPTION}', '\\2', $bbcode_tpl['url3']);

	$bbcode_tpl['url4'] = str_replace('{TAG_ATTR}', 'http://\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url4'] = str_replace('{DESCRIPTION}', '\\3', $bbcode_tpl['url4']);
	
	$bbcode_tpl['email'] = str_replace('{TAG_ATTR}', '\\1', $bbcode_tpl['email_open']) . '\\1' . $bbcode_tpl['email_close'];

	$bbcode_tpl['listitem'] = $bbcode_tpl['listitem_open'];
	define("BBCODE_TPL_READY", true);

	return $bbcode_tpl;
}

function bbencode_code_first_pass($text, $uid)
{
			global $bbcode_parse;
	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	return $bbcode_parse->bbencode_first_pass_pda($text, $uid, '[code]', '[/code]', '', true, '');
}

function bbencode_quote_first_pass($text, $uid)
{
			global $bbcode_parse;
	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$text = $bbcode_parse->bbencode_first_pass_pda($text, $uid, '[quote]', '[/quote]', '', false, '');
	$text = $bbcode_parse->bbencode_first_pass_pda($text, $uid, '/\[quote=(\\\".*?\\\")\]/is', '[/quote]', '', false, '', "[quote:$uid=\\1]");
	return $text;
}
function bbencode_list_first_pass($text, $uid)
{
		global $bbcode_parse;
	// [list] and [list=x] for (un)ordered lists.
	$open_tag = array();
	$open_tag[0] = "[list]";

	// unordered..
	$text = $bbcode_parse->bbencode_first_pass_pda($text, $uid, $open_tag, "[/list]", "[/list:u]", false, 'replace_listitems');

	$open_tag[0] = "[list=1]";
	$open_tag[1] = "[list=a]";

	// ordered.
	$text = $bbcode_parse->bbencode_first_pass_pda($text, $uid, $open_tag, "[/list]", "[/list:o]",  false, 'replace_listitems');
	return $text;
}

function bbencode_img_first_pass($text, $uid)
{
	return preg_replace("#\[img\]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie", "'[img:$uid]\\1' . str_replace(' ', '%20', '\\3') . '[/img:$uid]'", $text);
}

function bbencode_url_first_pass($text, $uid)
{
	return $text;	// URL - the only tag that doesn't add :$uid
}

/**
 * Does second-pass bbencoding. This should be used before displaying the message in
 * a thread. Assumes the message is already first-pass encoded, and we are given the
 * correct UID as used in first-pass encoding.
 */
function bbencode_code_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;

	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$code_start_html = $bbcode_tpl['code_open'];
	$code_end_html =  $bbcode_tpl['code_close'];

	// First, do all the 1st-level matches. These need an htmlspecialchars() run,
	// so they have to be handled differently.
	$match_count = preg_match_all("#\[code:1:$uid\](.*?)\[/code:1:$uid\]#si", $text, $matches);

	for ($i = 0; $i < $match_count; $i++)
	{
		$before_replace = $matches[1][$i];
		$after_replace = $matches[1][$i];

		// Replace 2 spaces with "&nbsp; " so non-tabbed code indents without making huge long lines.
		$after_replace = str_replace("  ", "&nbsp; ", $after_replace);
		// now Replace 2 spaces with " &nbsp;" to catch odd #s of spaces.
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);

		// Replace tabs with "&nbsp; &nbsp;" so tabbed code indents sorta right without making huge long lines.
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);

		// now Replace space occurring at the beginning of a line
		$after_replace = preg_replace("/^ {1}/m", '&nbsp;', $after_replace);

		$str_to_match = "[code:1:$uid]" . $before_replace . "[/code:1:$uid]";

		$replacement = $code_start_html;
		$replacement .= $after_replace;
		$replacement .= $code_end_html;

		$text = str_replace($str_to_match, $replacement, $text);
	}

	// Now, do all the non-first-level matches. These are simple.
	$text = str_replace("[code:$uid]", $code_start_html, $text);
	$text = str_replace("[/code:$uid]", $code_end_html, $text);

	return $text;
}
function bbencode_quote_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$text = str_replace("[quote:$uid]", $bbcode_tpl['quote_open'], $text);
	$text = str_replace("[/quote:$uid]", $bbcode_tpl['quote_close'], $text);

	// New one liner to deal with opening quotes with usernames...
	// replaces the two line version that I had here before..
	$text = preg_replace("/\[quote:$uid=\"(.*?)\"\]/si", $bbcode_tpl['quote_username_open'], $text);
	return $text;
}

function bbencode_list_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;

	// [list] and [list=x] for (un)ordered lists.
	// unordered lists
	$text = str_replace("[list:$uid]", $bbcode_tpl['ulist_open'], $text);
	// li tags
	$text = str_replace("[*:$uid]", $bbcode_tpl['listitem'], $text);
	// ending tags
	$text = str_replace("[/list:u:$uid]", $bbcode_tpl['ulist_close'], $text);
	$text = str_replace("[/list:o:$uid]", $bbcode_tpl['olist_close'], $text);
	// Ordered lists
	$text = preg_replace("/\[list=([a1]):$uid\]/si", $bbcode_tpl['olist_open'], $text);
	return $text;
}

/*	// colours
	$text = preg_replace("/\[color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['color_open'], $text);
	$text = str_replace("[/color:$uid]", $bbcode_tpl['color_close'], $text);

	// size
	$text = preg_replace("/\[size=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['size_open'], $text);
	$text = str_replace("[/size:$uid]", $bbcode_tpl['size_close'], $text);

	// [b] and [/b] for bolding text.
	$text = str_replace("[b:$uid]", $bbcode_tpl['b_open'], $text);
	$text = str_replace("[/b:$uid]", $bbcode_tpl['b_close'], $text);

	// [u] and [/u] for underlining text.
	$text = str_replace("[u:$uid]", $bbcode_tpl['u_open'], $text);
	$text = str_replace("[/u:$uid]", $bbcode_tpl['u_close'], $text);

	// [i] and [/i] for italicizing text.
	$text = str_replace("[i:$uid]", $bbcode_tpl['i_open'], $text);
	$text = str_replace("[/i:$uid]", $bbcode_tpl['i_close'], $text);

	// Patterns and replacements for URL and email tags..
	*/
function bbencode_img_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;

	// [img]image_url_here[/img] code..
	// This one gets first-passed..
	return preg_replace("#\[img:$uid\](.*?)\[/img:$uid\]#si", $bbcode_tpl['img'], $text);
}

function bbencode_url_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;
	
	$patterns = array();
	$replacements = array();
	// matches a [url]xxxx://www.phpbb.com[/url] code..
	$patterns[] = "#\[url\]([\w]+?://[^ \"\n\r\t<]*?)\[/url\]#is";
	$replacements[] = $bbcode_tpl['url1'];

	// [url]www.phpbb.com[/url] code.. (no xxxx:// prefix).
	$patterns[] = "#\[url\]((www|ftp)\.[^ \"\n\r\t<]*?)\[/url\]#is";
	$replacements[] = $bbcode_tpl['url2'];

	// [url=xxxx://www.phpbb.com]phpBB[/url] code..
	$patterns[] = "#\[url=([\w]+?://[^ \"\n\r\t<]*?)\](.*?)\[/url\]#is";
	$replacements[] = $bbcode_tpl['url3'];

	// [url=www.phpbb.com]phpBB[/url] code.. (no xxxx:// prefix).
	$patterns[] = "#\[url=((www|ftp)\.[^ \"\n\r\t<]*?)\](.*?)\[/url\]#is";
	$replacements[] = $bbcode_tpl['url4'];
	return preg_replace($patterns, $replacements, $text);
}

function bbencode_email_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl;

	// [email]user@domain.tld[/email] code..
	return preg_replace("#\[email\]([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si", $bbcode_tpl['email'], $text);

} // bbencode_second_pass()
?>