<?php
// -------------------------------------------------------------
//
// $Id: message_parser.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : message_parser.php
// STARTED   : Fri Feb 28, 2003
// COPYRIGHT : © 2003 phpBB Group
// WWW       : http://www.phpbb.com/
// LICENCE   : GPL vs2.0 [ see /docs/COPYING ] 
// 
// -------------------------------------------------------------

/*
	TODO list for M-3:
	- add other languages to syntax highlighter
	- better (and unified, wrt other pages such as registration) validation for urls, emails, etc...
	- need size limit checks on img/flash tags ... probably warrants some discussion
*/

// case-insensitive strpos() - needed for some functions
if (!function_exists('stripos'))
{
	function stripos($haystack, $needle)
	{
		if (preg_match('#' . preg_quote($needle, '#') . '#i', $haystack, $m))
		{
			return strpos($haystack, $m[0]);
		}

		return false;
	}
}

// Main message parser for posting, pm, etc. takes raw message
// and parses it for attachments, html, bbcode and smilies
class parse_message
{
	var $message = '';
	var $warn_msg = array();

	var $bbcodes = array();
	var $bbcode_uid = '';
	var $bbcode_bitfield = 0;

	var $attachment_data = array();
	var $filename_data = array();

	var $smilies = '';

	function parse_message($message = '')
	{
		// Init BBCode UID
		$this->bbcode_uid = substr(md5(time()), 0, BBCODE_UID_LEN);

		if ($message)
		{
			$this->message = $message;
		}
	}

	function parse($html, $bbcode, $url, $smilies, $allow_img = true, $allow_flash = true, $allow_quote = true)
	{
		global $config, $db, $user;

		// Do some general 'cleanup' first before processing message,
		// e.g. remove excessive newlines(?), smilies(?)
		// Transform \r\n and \r into \n
		$match = array('#\r\n?#', '#sid=[a-z0-9]*?&amp;?#', "#([\n][\s]+){3,}#");
		$replace = array("\n", '', "\n\n");
		$this->message = preg_replace($match, $replace, $this->message);

		// Message length check
		if (!strlen($this->message) || ($config['max_post_chars'] && strlen($this->message) > $config['max_post_chars']))
		{
			$this->warn_msg[] = (!strlen($this->message)) ? $user->lang['TOO_FEW_CHARS'] : $user->lang['TOO_MANY_CHARS'];
			return $this->warn_msg;
		}

		// Parse HTML
		$this->html($html);

		// Parse BBCode
		if ($bbcode && strpos($this->message, '[') !== false)
		{
			$this->bbcode_init();
			$disallow = array('allow_img', 'allow_flash', 'allow_quote');
			foreach ($disallow as $bool)
			{
				if (!${$bool})
				{
					$this->bbcodes[str_replace('allow_', '', $bool)]['disabled'] = true;
				}
			}
			$this->bbcode();
		}

		// Parse Emoticons
		$this->emoticons($smilies);

		// Parse URL's
		$this->magic_url($url);
		
		return implode('<br />', $this->warn_msg);
	}

	// Parse HTML
	function html($html)
	{
		global $board_config;

		if ($html && $board_config['allow_html_tags'])
		{
			// If $html is true then "allowed_tags" are converted back from entity
			// form, others remain
			$allowed_tags = split(',', $config['allow_html_tags']);
			
			if (sizeof($allowed_tags))
			{
				$this->message = preg_replace('#&lt;(\/?)(' . str_replace('*', '.*?', implode('|', $allowed_tags)) . ')&gt;#is', '<$1$2>', $this->message);
			}
		}
	}

	// Replace magic urls of form http://xxx.xxx., www.xxx. and xxx@xxx.xxx.
	// Cuts down displayed size of link if over 50 chars, turns absolute links
	// into relative versions when the server/script path matches the link
	function magic_url($url)
	{
		global $config, $bbcode_parse;

		if ($url)
		{
			$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';

			$match = $replace = array();

			// relative urls for this board
			$match[] = '#((^|[\n ])' . $server_protocol . trim($config['server_name']) . $server_port . preg_replace('/^\/?(.*?)(\/)?$/', '$1', trim($config['script_path'])) . '(?:/[^ \t\n\r<"\']*)?)#i';
			$replace[] = '<a href="$1" target="_blank">$1</a>';

			// matches a xxxx://aaaaa.bbb.cccc. ...
			$match[] = '#(^|[\n ])([\w]+?://.*?[^ \t\n\r<"\']*)#ie';
			$replace[] = "'\$1<a href=\"\$2\" target=\"_blank\">' . ((strlen('\$2') > 55) ? substr('\$2', 0, 39) . ' ... ' . substr('\$2', -10) : '\$2') . '</a>'";

			// matches a "www.xxxx.yyyy[/zzzz]" kinda lazy URL thing
			$match[] = '#(^|[\n ])(www\.[\w\-]+\.[\w\-.\~]+(?:/[^ \t\n\r<"\']*)?)#ie';
			$replace[] = "'\$1<a href=\"http://\$2\" target=\"_blank\">' . ((strlen('\$2') > 55) ? substr(str_replace(' ', '%20', '\$2'), 0, 39) . ' ... ' . substr('\$2', -10) : '\$2') . '</a>'";

			// matches an email@domain type address at the start of a line, or after a space.
			$match[] = '#(^|[\n ])([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)#ie';
			$replace[] = "'\$1<a href=\"mailto:\$2\">' . ((strlen('\$2') > 55) ? substr('\$2', 0, 39) . ' ... ' . substr('\$2', -10) : '\$2') . '</a><'";

			$this->message = preg_replace($match, $replace, $this->message);
		}
	}

	function emoticons($smilie)
	{
		global $db, $user, $phpbb_root_path, $config, $cache;

		if (!$smilie)
		{
			return;
		}
		if ($cache->exists('smilies')) {
			$smilies = $cache->get('smilies');
			$num_smilies = count($smilies);
		}
		else
		{
			$sql = 'SELECT * 
				FROM ' . SMILIES_TABLE;
			$result = $db->sql_query($sql);

			// TEMP - maybe easier regular expression processing... at the moment two newlines prevents smilie substitution.
			$this->message = str_replace("\n", "\\n", $this->message);
	
			if ($row = $db->sql_fetchrow($result))
			{
				$match = $replace = array();
	
				do
				{
					$match[] = "#(?<=.\W|\W.|\W)" . preg_quote($row['code'], '#') . "(?=.\W|\W.|\W$)#";
					$replace[] = '<!-- s' . $row['code'] . ' --><img src="{SMILE_PATH}/' . $row['smile_url'] . '" border="0" alt="' . $row['emoticon'] . '" title="' . $row['emoticon'] . '" /><!-- s' . $row['code'] . ' -->';
				}
				while ($row = $db->sql_fetchrow($result));
				$smilies = $row;
				$cache->put('smilies', $smilies);

				$this->message = trim(preg_replace($match, $replace, ' ' . $this->message . ' '));
				$this->message = str_replace("\\n", "\n", $this->message);
			}
		}
	}

	// Parse BBCode
	function bbcode()
	{
		if (!$this->bbcodes)
		{
			$this->bbcode_init();
		}

		global $user;
		$this->bbcode_bitfield = 0;

		$size = strlen($this->message);
		foreach ($this->bbcodes as $bbcode_name => $bbcode_data)
		{
			if ($bbcode_data['disabled'])
			{
				foreach ($bbcode_data['regexp'] as $regexp => $replacement)
				{
					if (preg_match($regexp, $this->message))
					{
						$this->warn_msg[] = $user->lang['UNAUTHORISED_BBCODE'] . '[' . $bbcode_name . ']';
						continue;
					}
				}
			}
			else
			{
				foreach ($bbcode_data['regexp'] as $regexp => $replacement)
				{
					$this->message = preg_replace($regexp, $replacement, $this->message);
				}
			}

			// Because we add bbcode_uid to all tags, the message length
			// will increase whenever a tag is found
			$new_size = strlen($this->message);
			if ($size != $new_size)
			{
				$this->bbcode_bitfield |= (1 << $bbcode_data['bbcode_id']);
				$size = $new_size;
			}
		}
	}

	function bbcode_init()
	{
		static $rowset;

		// This array holds all bbcode data. BBCodes will be processed in this
		// order, so it is important to keep [code] in first position and
		// [quote] in second position.
		$this->bbcodes = array(
			'code'		=>	array('bbcode_id' => 8, 'regexp' => array('#\[code(?:=([a-z]+))?\](.+\[/code\])#ise' => "\$this->bbcode_code('\$1', '\$2')")),
			'quote'		=>	array('bbcode_id' => 0, 'regexp' => array('#\[quote(?:=&quot;(.*?)&quot;)?\](.+)\[/quote\]#ise' => "\$this->bbcode_quote('\$0')")),
			'b'			=>	array('bbcode_id' => 1, 'regexp' => array('#\[b\](.*?)\[/b\]#is' => '[b:' . $this->bbcode_uid . ']$1[/b:' . $this->bbcode_uid . ']')),
			'i'			=>	array('bbcode_id' => 2, 'regexp' => array('#\[i\](.*?)\[/i\]#is' => '[i:' . $this->bbcode_uid . ']$1[/i:' . $this->bbcode_uid . ']')),
			'url'		=>	array('bbcode_id' => 3, 'regexp' => array('#\[url=?(.*?)?\](.*?)\[/url\]#ise' => "\$this->validate_url('\$1', '\$2')")),
			'img'		=>	array('bbcode_id' => 4, 'regexp' => array('#\[img\](https?://)([a-z0-9\-\.,\?!%\*_:;~\\&$@/=\+]+)\[/img\]#i' => '[img:' . $this->bbcode_uid . ']$1$2[/img:' . $this->bbcode_uid . ']')),
			'size'		=>	array('bbcode_id' => 5, 'regexp' => array('#\[size=([\-\+]?[1-2]?[0-9])\](.*?)\[/size\]#is' => '[size=$1:' . $this->bbcode_uid . ']$2[/size:' . $this->bbcode_uid . ']')),
			'color'		=>	array('bbcode_id' => 6, 'regexp' => array('!\[color=(#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]!is' => '[color=$1:' . $this->bbcode_uid . ']$2[/color:' . $this->bbcode_uid . ']')),
			'u'			=>	array('bbcode_id' => 7, 'regexp' => array('#\[u\](.*?)\[/u\]#is' => '[u:' . $this->bbcode_uid . ']$1[/u:' . $this->bbcode_uid . ']')),
			'list'		=>	array('bbcode_id' => 9, 'regexp' => array('#\[list(=[a-z|0-9|(?:disc|circle|square))]+)?\].*\[/list\]#ise' => "\$this->bbcode_list('\$0')"))
		);

		if (!isset($rowset))
		{
			global $db;
			$rowset = array();

			$sql = 'SELECT bbcode_id, bbcode_tag, first_pass_match, first_pass_replace
				FROM ' . BBCODES_TABLE;

			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$rowset[] = $row;
			}
		}
		foreach ($rowset as $row)
		{
			$this->bbcodes[$row['bbcode_tag']] = array(
				'bbcode_id'	=>	intval($row['bbcode_id']),
				'regexp'	=>	array($row['first_pass_match'] => str_replace('$uid', $this->bbcode_uid, $row['first_pass_replace']))
			);
		}
	}

	// Hardcode inline attachments [ia]
	function bbcode_attachment($stx, $in)
	{
		$out = '[attachment=' . $stx . ':' . $this->bbcode_uid . ']<!-- ia' . $stx . ' -->' . $in . '<!-- ia' . $stx . ' -->[/attachment:' . $this->bbcode_uid . ']';
		return $out;
	}

	// Expects the argument to start right after the opening [code] tag and to end with [/code]
	function bbcode_code($stx, $in)
	{
		// when using the /e modifier, preg_replace slashes double-quotes but does not
		// seem to slash anything else
		$in = str_replace("\r\n", "\n", str_replace('\"', '"', $in));
		$out = '';

		do
		{
			$pos = stripos($in, '[/code]') + 7;
			$code = substr($in, 0, $pos);
			$in = substr($in, $pos);

			// $code contains everything that was between code tags (including the ending tag) but we're trying to grab as much extra text as possible, as long as it does not contain open [code] tags
			while ($in)
			{
				$pos = stripos($in, '[/code]') + 7;
				$buffer = substr($in, 0, $pos);

				if (preg_match('#\[code(?:=([a-z]+))?\]#i', $buffer))
				{
					break;
				}
				else
				{
					$in = substr($in, $pos);
					$code .= $buffer;
				}
			}

			$code = substr($code, 0, -7);
			$code = preg_replace('#^[\r\n]*(.*?)[\n\r\s\t]*$#s', '$1', $code);

			switch (strtolower($stx))
			{
				case 'php':
					$remove_tags = false;
					$str_from = array('&lt;', '&gt;');
					$str_to = array('<', '>');

					$code = str_replace($str_from, $str_to, $code);
					if (!preg_match('/^\<\?.*?\?\>/is', $code))
					{
						$remove_tags = true;
						$code = "<?php $code ?>";
					}

					$conf = array('highlight.bg', 'highlight.comment', 'highlight.default', 'highlight.html', 'highlight.keyword', 'highlight.string');
					foreach ($conf as $ini_var)
					{
						ini_set($ini_var, str_replace('highlight.', 'syntax', $ini_var));
					}

					ob_start();
					highlight_string($code);
					$code = ob_get_contents();
					ob_end_clean();

					$str_from = array('<font color="syntax', '</font>', '<code>', '</code>','[', ']', '.');
					$str_to = array('<span class="syntax', '</span>', '', '', '&#91;', '&#93;', '&#46;');

					if ($remove_tags)
					{
						$str_from[] = '<span class="syntaxdefault">&lt;?php&nbsp;</span>';
						$str_to[] = '';
						$str_from[] = '<span class="syntaxdefault">&lt;?php&nbsp;';
						$str_to[] = '<span class="syntaxdefault">';
					}

					$code = str_replace($str_from, $str_to, $code);
					$code = preg_replace('#^(<span class="[a-z_]+">)\n?(.*?)\n?(</span>)$#is', '$1$2$3', $code);

					if ($remove_tags)
					{
						$code = preg_replace('#(<span class="[a-z]+">)?\?&gt;</span>#', '', $code);
					}

					$code = preg_replace('#^<span class="[a-z]+"><span class="([a-z]+)">(.*)</span></span>#s', '<span class="$1">$2</span>', $code);
					$code = preg_replace('#(?:[\n\r\s\t]|&nbsp;)*</span>$#', '</span>', $code);

					$out .= "[code=$stx:" . $this->bbcode_uid . ']' . trim($code) . '[/code:' . $this->bbcode_uid . ']';
				break;

				default:
					$str_from = array('<', '>', '[', ']', '.');
					$str_to = array('&lt;', '&gt;', '&#91;', '&#93;', '&#46;');

					$out .= '[code:' . $this->bbcode_uid . ']' . trim(str_replace($str_from, $str_to, $code)) . '[/code:' . $this->bbcode_uid . ']';
			}

			if (preg_match('#(.*?)\[code(?:=[a-z]+)?\](.+)#is', $in, $m))
			{
				$out .= $m[1];
				$in = $m[2];
			}
		}
		while ($in);

		return $out;
	}

	// Expects the argument to start with a tag
	function bbcode_list($in)
	{
		// $tok holds characters to stop at. Since the string starts with a '[' we'll get everything up to the first ']' which should be the opening [list] tag
		$tok = ']';
		$out = '[';


		$in = substr(str_replace('\"', '"', $in), 1);
		$list_end_tags = $item_end_tags = array();

		do
		{
			$pos = strlen($in);
			for ($i = 0; $i < strlen($tok); ++$i)
			{
				$tmp_pos = strpos($in, $tok{$i});
				if ($tmp_pos !== false && $tmp_pos < $pos)
				{
					$pos = $tmp_pos;
				}
			}

			$buffer = substr($in, 0, $pos);
			$tok = $in{$pos};
			$in = substr($in, $pos + 1);

			if ($tok == ']')
			{
				// if $tok is ']' the buffer holds a tag

				if ($buffer == '/list' && count($list_end_tags))
				{
					// valid [/list] tag
					if (count($item_end_tags))
					{
						// current li tag has not been closed
						$out = preg_replace('/(\n)?\[$/', '[', $out) . array_pop($item_end_tags) . '][';
					}

					$out .= array_pop($list_end_tags) . ']';
					$tok = '[';
				}
				elseif (preg_match('#list(=?(?:[0-9]|[a-z]|))#i', $buffer, $m))
				{
					// sub-list, add a closing tag
					if (!$m[1] || preg_match('/^(disc|square|circle)$/i', $m[1]))
					{
						array_push($list_end_tags, '/list:u:' . $this->bbcode_uid);
					}
					else
					{
						array_push($list_end_tags, '/list:o:' . $this->bbcode_uid);
					}
					$out .= $buffer . ':' . $this->bbcode_uid . ']';
					$tok = '[';
				}
				else
				{
					if ($buffer == '*' && count($list_end_tags))
					{
						// the buffer holds a bullet tag and we have a [list] tag open
						if (count($item_end_tags) >= count($list_end_tags))
						{
							// current li tag has not been closed
							if (preg_match('/\n\[$/', $out, $m))
							{
								$out = preg_replace('/\n\[$/', '[', $out);
								$buffer = array_pop($item_end_tags) . "]\n[*:" . $this->bbcode_uid;
							}
							else
							{
								$buffer = array_pop($item_end_tags) . '][*:' . $this->bbcode_uid;
							}
						}
						else
						{
							$buffer = '*:' . $this->bbcode_uid;
						}

						$item_end_tags[] = '/*:m:' . $this->bbcode_uid;
					}
					elseif ($buffer == '/*')
					{
						array_pop($item_end_tags);
						$buffer = '/*:' . $this->bbcode_uid;
					}

					$out .= $buffer . $tok;
					$tok = '[]';
				}
			}
			else
			{
				// Not within a tag, just add buffer to the return string

				$out .= $buffer . $tok;
				$tok = ($tok == '[') ? ']' : '[]';
			}
		}
		while ($in);

		// do we have some tags open? close them now
		if (count($item_end_tags))
		{
			$out .= '[' . implode('][', $item_end_tags) . ']';
		}
		if (count($list_end_tags))
		{
			$out .= '[' . implode('][', $list_end_tags) . ']';
		}

		return $out;
	}

	// Expects the argument to start with a tag
	function bbcode_quote($in)
	{
		global $config, $user;

		$tok = ']';
		$out = '[';

		$in = substr(str_replace('\"', '"', $in), 1);
		$close_tags = $error_ary = array();
		$buffer = '';

		do
		{
			$pos = strlen($in);
			for ($i = 0; $i < strlen($tok); ++$i)
			{
				$tmp_pos = strpos($in, $tok{$i});
				if ($tmp_pos !== false && $tmp_pos < $pos)
				{
					$pos = $tmp_pos;
				}
			}

			$buffer .= substr($in, 0, $pos);
			$tok = $in{$pos};
			$in = substr($in, $pos + 1);

			if ($tok == ']')
			{
				if ($buffer == '/quote' && count($close_tags))
				{
					// we have found a closing tag

					$out .= array_pop($close_tags) . ']';
					$tok = '[';
					$buffer = '';
				}
				elseif (preg_match('#^quote(?:=&quot;(.*?)&quot;)?$#is', $buffer, $m))
				{
					// the buffer holds a valid opening tag
					if ($config['max_quote_depth'] && count($close_tags) >= $config['max_quote_depth'])
					{
						// there are too many nested quotes
						$error_ary['quote_depth'] = sprintf($user->lang['QUOTE_DEPTH_EXCEEDED'], $config['max_quote_depth']);

						$out .= $buffer . $tok;
						$tok = '[]';
						$buffer = '';

						continue;
					}

					array_push($close_tags, '/quote:' . $this->bbcode_uid);

					if ($m[1])
					{
						$username = preg_replace('#\[(?!b|i|u|color|url|email|/b|/i|/u|/color|/url|/email)#iU', '&#91;$1', $m[1]);
						$end_tags = array();
						$error = false;

						preg_match_all('#\[((?:/)?(?:[a-z]+))#i', $username, $tags);
						foreach ($tags[1] as $tag)
						{
							if ($tag{0} != '/')
							{
								$end_tags[] = '/' . $tag;
							}
							else
							{
								$end_tag = array_pop($end_tags);
								if ($end_tag != $tag)
								{
									$error = true;
								}
								else
								{
									$error = false;
								}
							}
						}
						if ($error)
						{
							$username = str_replace('[', '&#91;', str_replace(']', '&#93;', $m[1]));
						}

						$out .= 'quote=&quot;' . $username . '&quot;:' . $this->bbcode_uid . ']';
					}
					else
					{
						$out .= 'quote:' . $this->bbcode_uid . ']';
					}

					$tok = '[';
					$buffer = '';
				}
				elseif (preg_match('#^quote=&quot;(.*?)#is', $buffer, $m))
				{
					// the buffer holds an invalid opening tag
					$buffer .= ']';
				}
				else
				{
					$out .= $buffer . $tok;
					$tok = '[]';
					$buffer = '';
				}
			}
			else
			{
				$out .= $buffer . $tok;
				$tok = ($tok == '[') ? ']' : '[]';
				$buffer = '';
			}
		}
		while ($in);

		if (count($close_tags))
		{
			$out .= '[' . implode('][', $close_tags) . ']';
		}

		foreach ($error_ary as $error_msg)
		{
			$this->warn_msg[] = $error_msg;
		}

		return $out;
	}

	function validate_email($var1, $var2)
	{
		$txt = stripslashes($var2);
		$email = ($var1) ? stripslashes($var1) : stripslashes($var2);

		$validated = true;

		if (!preg_match('!([a-z0-9]+[a-z0-9\-\._]*@(?:(?:[0-9]{1,3}\.){3,5}[0-9]{1,3}|[a-z0-9]+[a-z0-9\-\._]*\.[a-z]+))!i', $email))
		{
			$validated = false;
		}

		if (!$validated)
		{
			return '[email' . (($var1) ? "=$var1" : '') . ']' . $var2 . '[/email]';
		}

		if ($var1)
		{
			$retval = '[email=' . $email . ':' . $this->bbcode_uid . ']' . $txt . '[/email:' . $this->bbcode_uid . ']';
		}
		else
		{
			$retval = '[email:' . $this->bbcode_uid . ']' . $email . '[/email:' . $this->bbcode_uid . ']';
		}
		return $retval;
	}

	function validate_url($var1, $var2)
	{
		global $config;

		$url = ($var1) ? stripslashes($var1) : stripslashes($var2);
		$valid = false;

		$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';

		// relative urls for this board
		if (preg_match('#' . $server_protocol . trim($config['server_name']) . $server_port . preg_replace('/^\/?(.*?)(\/)?$/', '$1', trim($config['script_path'])) . '/([^ \t\n\r<"\']+)#i', $url) ||
			preg_match('#([\w]+?://.*?[^ \t\n\r<"\']*)#i', $url) ||
			preg_match('#(www\.[\w\-]+\.[\w\-.\~]+(?:/[^ \t\n\r<"\']*)?)#i', $url))
		{
			$valid = true;
		}

		if ($valid)
		{
			if (!preg_match('#^[\w]+?://.*?#i', $url))
			{
				$url = 'http://' . $url;
			}

			return ($var1) ? '[url=' . $url . ':' . $this->bbcode_uid . ']' . stripslashes($var2) . '[/url:' . $this->bbcode_uid . ']' : '[url:' . $this->bbcode_uid . ']' . $url . '[/url:' . $this->bbcode_uid . ']'; 
		}

		return '[url' . (($var1) ? '=' . stripslashes($var1) : '') . ']' . stripslashes($var2) . '[/url]';
	}
}
$message_parser = new parse_message;
?>