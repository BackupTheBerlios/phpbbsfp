<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_sys.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_sys.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003 		Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function _dump($message, $function='', $line='', $file='')
{
	echo '<div class="gen"><pre>' . ( empty($function) ? '' : "<u><b>location:</b> $function</u>" . ( empty($line) ? '' : " line: $line" ) . ( empty($file) ? '' : " file: $file" ) . '<br />') . str_replace("\t", '&nbsp;&nbsp;', htmlspecialchars($message)) . '</pre></div>';
}

//
//	functions
//
function _lang($key)
{
	global $lang;
	return isset($lang[$key]) ? $lang[$key] : $key;
}

function _images($key)
{
	global $images, $phpbb_root_path;

	// image key ?
	if ( isset($images[$key]) )
	{
		return $images[$key];
	}

	// external link ?
	$url = strtolower(substr($key, 0, 4));
	if ( ($url != 'http') && ($url != 'ftp:') )
	{
		return $key;
	}

	// internal link ?
	if ( file_exists($key) )
	{
		return $key;
	}

	// none : return the spacer
	return $phpbb_root_path . './images/spacer.gif';
}

function _error($key)
{
	global $lang;
	global $error, $error_msg;

	if ( !$error )
	{
		$error_msg = '';
	}

	$error = true;
	$error_msg .= ( empty($error_msg) ? '' : '<br /><br />' ) . _lang($key);
}

function _pcp_message_return($msg, $url_name, $return_path)
{
	global $template, $phpbb_root_path, $phpEx, $userdata;
	global $cur_subopt;

	if ( !defined('HEADER_INC') )
	{
		global $db, $board_config, $theme, $lang, $nav_links, $gen_simple_header, $images;
		global $user_ip, $session_length;
		global $starttime;
		global $admin_level, $level_prior;

		include($phpbb_root_path . './includes/page_header.' . $phpEx);
	}

	$msg = _lang($msg) . '<br /><br />' . sprintf( _lang($url_name), '<a href="' . $return_path . '">', '</a>' ) . '<br /><br />' . sprintf(_lang('Click_return_index'), '<a href="' . append_sid("./index.$phpEx") . '">', '</a>');

	// template name
	$template->set_filenames(array(
		'body' => 'message_body.tpl')
	);
	$template->assign_vars(array(
		'L_INDEX' => '',
		'MESSAGE_TITLE' => _lang('Information'),
		'MESSAGE_TEXT' => _lang($msg),
		)
	);

	// footer
	$template->pparse('body');
	$template->set_filenames(array(
		'profilcp_footer' => 'profilcp/profilcp_footer.tpl')
	);

	// sub-menu
	if ( $cur_subopt >= 0 )
	{
		$template->assign_block_vars('sub_menu_b', array());
	}
	$template->pparse('profilcp_footer');
	include($phpbb_root_path . './includes/page_tail.' . $phpEx);
	exit;
}

function _message_return($msg, $url_name, $return_path)
{
	global $template, $phpbb_root_path, $phpEx;

	if ( defined('IN_PCP') )
	{
		_pcp_message_return($msg, $url_name, $return_path);
		return;
	}

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . $return_path . '">')
	);
	$msg = _lang($msg) . '<br /><br />' . sprintf( _lang($url_name), '<a href="' . $return_path . '">', '</a>' ) . '<br /><br />' . sprintf(_lang('Click_return_index'), '<a href="' . append_sid("./index.$phpEx") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $msg);
}

function _hidden_init()
{
	global $s_hidden_fields;
	$s_hidden_fields = '';
}

function _hide($key, $value)
{
	global $s_hidden_fields;
	$s_hidden_fields .= '<input type="hidden" name="' . addslashes($key) . '" value="' . addslashes($value) . '" />';
}

function _hidden_get()
{
	global $s_hidden_fields;
	return $s_hidden_fields;
}

function _read_var($post_var, $type=0, $dft='', $get_var='')
{
	global $HTTP_POST_VARS, $HTTP_GET_VARS;

	if ( empty($get_var) )
	{
		$get_var = $post_var;
	}
	$res = $dft;
	if ( isset($HTTP_POST_VARS[$post_var]) || isset($HTTP_GET_VARS[$get_var]) )
	{
		$res = isset($HTTP_POST_VARS[$post_var]) ? $HTTP_POST_VARS[$post_var] : $HTTP_GET_VARS[$get_var];
	}
	switch( $type )
	{
		case 1: // numeric
			$res = intval($res);
			break;
		case 11: // alpha protected againt HTML injection
			$res = htmlspecialchars(trim($res));
			break;
		case 12: // alpha protected and cleaned from slashes
			$res = htmlspecialchars(trim(stripslashes($res)));
			break;
		case 21: // array numeric
			$tmp = $res;
			$res = array();
			if ( is_array($tmp) )
			{
				for ( $i = 0; $i < count($tmp); $i++ )
				{
					if ( intval($tmp[$i]) > 0 )
					{
						$res[] = intval($tmp[$i]);
					}
				}
			}
			else
			{
				$res = $dft;
			}
		default:
			break;
	}
	return $res;
}

function _button_var($post_var, $get_var='')
{
	global $HTTP_POST_VARS, $HTTP_GET_VARS;
	if ( empty($get_var) )
	{
		$get_var = $post_var;
	}
	return ( isset($HTTP_POST_VARS[$post_var]) || ( isset($HTTP_GET_VARS[$get_var]) && intval($HTTP_GET_VARS[$get_var]) ) );
}

function _sql_statements(&$fields, &$sql_fields, &$sql_values, &$sql_update, $fields_except=array())
{
	// fix excepted fields format
	if ( !empty($fields_except) && !is_array($fields_except) )
	{
		$fields_except = array($fields_except);
	}

	// init result
	$sql_fields = '';
	$sql_values = '';
	$sql_update = '';

	// process
	@reset($fields);
	while ( list($field, $value) = @each($fields) )
	{
		if ( empty($fields_except) || !in_array($field, $fields_except) )
		{
			$sql_fields .= ( empty($sql_fields) ? '' : ', ' ) . $field;
			$sql_values .= ( empty($sql_values) ? '' : ', ' ) . $value;
			$sql_update .= ( empty($sql_update) ? '' : ', ' ) . "$field = $value";
		}
	}
}

?>