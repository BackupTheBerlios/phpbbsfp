<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: xs_include_import.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : xs_include_import.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003		CyberAlien
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/***************************************************************************
 *
 *   version              : 2.0.0.rc5
 *
 *   file revision        : 10
 *   project revision     : 31
 *   last modified        : 17 Jul 2004  14:55:05
 *
 ***************************************************************************/

if (!defined('IN_PHPBB') || !defined('IN_XS'))
{
	die("Hacking attempt");
}


function generate_actions_files($dir)
{
	global $items;
	// remove trailing /
	$pos = strrpos($dir, '/');
	$dir = $pos === strlen($dir) - 1 ? substr($dir, 0, $pos) : $dir;
	$arr = array('processing: '.$dir);
	for($i=0; $i<count($items); $i++)
	{
		if($items[$i]['dir'] === $dir)
		{
			$arr[] = array(
				'command'	=> 'upload',
				'local'		=> $items[$i]['tmp'],
				'remote'	=> $items[$i]['file']
				);
		}
	}
	return $arr;
}

function generate_actions_dirs($dir = '')
{
	global $dirs;
	$arr = array();
	if($dir && substr($dir, strlen($dir) - 1) !== '/')
	{
		$dir .= '/';
	}
	if($dir)
	{
		// remove trailing /
		$pos = strrpos($dir, '/');
		$str = $pos === strlen($dir) - 1 ? substr($dir, 0, $pos) : $dir;
		// get last directory name
		$pos = strrpos($str, '/');
		$str = $pos ? substr($str, $pos + 1) : $str;
		$arr[] = array(
			'command'	=> 'mkdir',
			'dir'		=> $str,
			'ignore'	=> true
			);
		$arr[] = array(
			'command'	=> 'chdir',
			'dir'		=> $str
			);
	}
	$arr[] = array(
		'command'	=> 'exec',
		'list'		=> generate_actions_files($dir)
		);
	// create subdirectories
	$len = strlen($dir);
	for($i=0; $i<count($dirs); $i++)
	{
		$str = $dirs[$i];
		if(substr($str, 0, $len) === $dir)
		{
			if($len)
			{
				$str = substr($str, $len + 1);
			}
			$pos = strpos($str, '/');
			if($pos == strlen($str) - 1)
			{
				$arr[] = array(
						'command'	=> 'exec',
						'list'		=> generate_actions_dirs($dirs[$i])
					);
			}
		}
	}
	return $arr;
}

function generate_style_name($str)
{
	$str = 'style_' . $str . '_%02d' . STYLE_EXTENSION;
	$num = 0;
	$found = true;
	while($found)
	{
		$filename = sprintf($str, $num);
		$found = @file_exists(XS_TEMP_DIR.$filename);
		$num ++;
	}
	return $filename;
}

?>
