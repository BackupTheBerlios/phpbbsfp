<?php
// -------------------------------------------------------------
//
// $Id: acm_file.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : acm_file.php
// STARTED   : Sat Feb 13, 2001
// COPYRIGHT : © 2003 phpBB Group
// WWW       : http://www.phpbb.com/
// LICENCE   : GPL vs2.0 [ see /docs/COPYING ]
//
// -------------------------------------------------------------

class acm
{
// --- Data Global File
	var $vars = '';
	var $var_expires = array();
	var $is_modified = FALSE;
// ---

// --- Grouped Data
	var $g_vars = array();
	var $g_var_expires = array();
	var $g_is_modified = array();

	var $sql_rowset = array();

	function acm()
	{
		global $phpbb_root_path;
		$this->cache_dir = $phpbb_root_path . 'cache/';
	}

	function load()
	{
		global $phpEx;
		if (file_exists($this->cache_dir . 'data_global.' . $phpEx))
		{
			@include($this->cache_dir . 'data_global.' . $phpEx);
		}
		else
		{
			return false;
		}
	}

	function load_grp($group_name)
	{
		global $phpEx;
		if (file_exists($this->cache_dir . 'data_grp_' . $group_name . ".$phpEx"))
		{
			@include($this->cache_dir . 'data_grp_' . $group_name . ".$phpEx");
			$this->g_is_modified[$group_name] = FALSE;
		}
		else
		{
			return false;
		}
	}

	function unload()
	{
		$this->save();
		$this->save_grp();
		$this->tidy();
		unset($this->vars);
		unset($this->var_expires);
		unset($this->g_vars);
		unset($this->g_var_expires);
		unset($this->g_is_modified);
		unset($this->sql_rowset);
	}

	function save() 
	{
		if (!$this->is_modified)
		{
			return;
		}

		global $phpEx;
		$file = '<?php $this->vars=' . $this->format_array($this->vars) . ";\n\$this->var_expires=" . $this->format_array($this->var_expires) . ' ?>';

		if ($fp = @fopen($this->cache_dir . 'data_global.' . $phpEx, 'wb'))
		{
			@flock($fp, LOCK_EX);
			fwrite($fp, $file);
			@flock($fp, LOCK_UN);
			fclose($fp);
		}

		$this->is_modified = FALSE;
	}

	function save_grp($group_name = '')
	{
		global $phpEx;

		if ( $group_name == '' )
		{
			foreach ( $this->g_vars as $grp_name => $data )
			{
				if (!$this->g_is_modified[$grp_name])
				{
					continue;
				}

				$file = "<?php\n\$this->g_vars['$grp_name'] = " . $this->format_array($this->g_vars[$grp_name]) . ";\n\$this->g_var_expires['$grp_name'] = " . $this->format_array($this->g_var_expires[$grp_name]) . ";\n?>";
				
				if ($fp = @fopen($this->cache_dir . 'data_grp_' . $grp_name . ".$phpEx", 'wb'))
				{
					@flock($fp, LOCK_EX);
					fwrite($fp, $file);
					@flock($fp, LOCK_UN);
					fclose($fp);
				}
				$this->g_is_modified[$grp_name] = FALSE;
			}
		}
		elseif ( $this->exists_grp($group_name) )
		{
			if (!$this->g_is_modified[$group_name])
			{
				return;
			}

			$file = "<?php\n\$this->g_vars['$group_name'] = " . $this->format_array($this->g_vars[$group_name]) . ";\n\$this->g_var_expires['$group_name'] = " . $this->format_array($this->g_var_expires[$group_name]) . ";\n?>";
			
			if ($fp = @fopen($this->cache_dir . 'data_grp_' . $group_name . ".$phpEx", 'wb'))
			{
				@flock($fp, LOCK_EX);
				fwrite($fp, $file);
				@flock($fp, LOCK_UN);
				fclose($fp);
			}

			$this->g_is_modified[$group_name] = FALSE;
		}
		else
		{
			return false;
		}
	}

	function tidy()
	{
		global $phpEx;

		$data_groups = array();

		$dir = opendir($this->cache_dir);
		while ($entry = readdir($dir))
		{
			$start = substr($entry, 0, 8);

			if ($start == 'data_grp')
			{
				$data_groups[] = $entry;
				continue;
			}
			elseif ($start != 'data_prv')
			{
				continue;
			}

			$expired = TRUE;
			include($this->cache_dir . $entry);
			if ($expired)
			{
				unlink($this->cache_dir . $entry);
			}
		}
		@closedir($dir);

		$phpEx_len = strlen(".$phpEx");

		foreach ($data_groups as $file_name)
		{
			$group_name = substr($file_name, 9);
			$group_name = substr($group_name, 0, strlen($group_name) - $phpEx_len);

			if (!isset($this->g_vars[$group_name]))
			{
				$this->load_grp($group_name);
			}

			foreach ($this->g_var_expires[$group_name] as $var_name => $expires)
			{
				if (time() > $expires)
				{
					$this->destroy("$group_name.$var_name");
				}
			}
		}

		unset($data_groups);

		if (file_exists($this->cache_dir . 'data_global.' . $phpEx))
		{
			if (!is_array($this->vars))
			{
				$this->load();
			}

			foreach ($this->var_expires as $var_name => $expires)
			{
				if (time() > $expires)
				{
					$this->destroy($var_name);
				}
			}
		}
	}

	function get($var_name)
	{
		if ($var_name{0} == '_')
		{
			global $phpEx;

			include($this->cache_dir . 'data_prv' . $var_name . ".$phpEx");
			return (isset($data)) ? $data : NULL;
		}
		elseif (substr_count($var_name, '.') == 1) // Group [group_name.var_name]
		{
			list($group_name, $var_name) = explode('.', $var_name);
			
			return ($this->exists($group_name .'.'. $var_name)) ? $this->g_vars[$group_name][$var_name] : NULL;
		}
		else
		{
			return ($this->exists($var_name)) ? $this->vars[$var_name] : NULL;
		}
	}

	function put($var_name, $var, $ttl = 31536000)
	{
		if ($var_name{0} == '_')
		{
			global $phpEx;

			if ($fp = @fopen($this->cache_dir . 'data_prv' . $var_name . ".$phpEx", 'wb'))
			{
				@flock($fp, LOCK_EX);
				fwrite($fp, "<?php\n\$expired = (time() > " . (time() + $ttl) . ") ? TRUE : FALSE;\nif (\$expired) { return; }\n\n\$data = unserialize('" . str_replace("'", "\\'", str_replace('\\', '\\\\', serialize($var))) . "');\n?>");
				@flock($fp, LOCK_UN);
				fclose($fp);
			}
		}
		elseif (substr_count($var_name, '.') == 1) // Group [group_name.var_name]
		{
			list($group_name, $var_name) = explode('.', $var_name);
			
			$this->g_vars[$group_name][$var_name] = $var;
			$this->g_var_expires[$group_name][$var_name] = time() + $ttl;
			$this->g_is_modified[$group_name] = TRUE;
		}
		else
		{
			$this->vars[$var_name] = $var;
			$this->var_expires[$var_name] = time() + $ttl;
			$this->is_modified = TRUE;
		}
	}

	function destroy($var_name, $table = '')
	{
		global $phpEx;

		if ($var_name == 'sql' && !empty($table))
		{
			$regex = '(' . ((is_array($table)) ? implode('|', $table) : $table) . ')';

			$dir = opendir($this->cache_dir);
			while ($entry = readdir($dir))
			{
				if (substr($entry, 0, 4) != 'sql_')
				{
					continue;
				}

				$fp = fopen($this->cache_dir . $entry, 'rb');
				$file = fread($fp, filesize($this->cache_dir . $entry));
				@fclose($fp);

				if (preg_match('#/\*.*?\W' . $regex . '\W.*?\*/#s', $file, $m))
				{
					unlink($this->cache_dir . $entry);
				}
			}
			@closedir($dir);
		}
		elseif ($var_name{0} == '_')
		{
			@unlink($this->cache_dir . 'data_prv' . $var_name . ".$phpEx");
		}
		elseif (substr_count($var_name, '.') == 1) // Group [group_name.var_name]
		{
			list($group_name, $var_name) = explode('.', $var_name);
			if ( isset($this->g_vars[$group_name]) )
			{
				$this->g_is_modified[$group_name] = TRUE;
	
				if ( $var_name != '' && isset($this->g_vars[$group_name][$var_name]) )
				{
					unset($this->g_vars[$group_name][$var_name]);
					unset($this->g_var_expires[$group_name][$var_name]);
				}
				else
				{
					unset($this->g_vars[$group_name]);
					unset($this->g_var_expires[$group_name]);
					@unlink($this->cache_dir . 'data_grp_' . $group_name . ".$phpEx");
				}
			}
		}
		elseif (isset($this->vars[$var_name]))
		{
			$this->is_modified = TRUE;
			unset($this->vars[$var_name]);
			unset($this->var_expires[$var_name]);
		}
	}

	function exists($var_name)
	{
		if ($var_name{0} == '_')
		{
			global $phpEx;
			return file_exists($this->cache_dir . 'data_prv' . $var_name . ".$phpEx");
		}
		elseif (substr_count($var_name, '.') == 1) // Group [group_name.var_name]
		{
			list($group_name, $var_name) = explode('.', $var_name);

			if (!isset($this->g_vars[$group_name]))
			{
				$this->load_grp($group_name);
			}

			if (!isset($this->g_var_expires[$group_name][$var_name]))
			{
				return false;
			}

			return (time() > $this->g_var_expires[$group_name][$var_name]) ? false : isset($this->g_vars[$group_name][$var_name]);
		}
		else
		{
			if (!is_array($this->vars))
			{
				$this->load();
			}

			if (!isset($this->var_expires[$var_name]))
			{
				return false;
			}

			return (time() > $this->var_expires[$var_name]) ? false : isset($this->vars[$var_name]);
		}
	}

	function format_array($array)
	{
		$lines = array();
		foreach ($array as $k => $v)
		{
			if (is_array($v))
			{
				$lines[] = "'$k'=>" . $this->format_array($v);
			}
			elseif (is_int($v))
			{
				$lines[] = "'$k'=>$v";
			}
			elseif (is_bool($v))
			{
				$lines[] = "'$k'=>" . (($v) ? 'TRUE' : 'FALSE');
			}
			else
			{
				$lines[] = "'$k'=>'" . str_replace("'", "\\'", str_replace('\\', '\\\\', $v)) . "'";
			}
		}
		return 'array(' . implode(',', $lines) . ')';
	}

	function sql_load($query)
	{
		global $phpEx;

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		$query_id = 'Cache id #' . count($this->sql_rowset);

		if (!file_exists($this->cache_dir . 'sql_' . md5($query) . ".$phpEx"))
		{
			return false;
		}

		@include($this->cache_dir . 'sql_' . md5($query) . ".$phpEx");

		if (!isset($expired))
		{
			return FALSE;
		}
		elseif ($expired)
		{
			unlink($this->cache_dir . 'sql_' . md5($query) . ".$phpEx");
			return FALSE;
		}

		return $query_id;
	}

	function sql_save($query, &$query_result, $ttl)
	{
		global $db, $phpEx;

		// Remove extra spaces and tabs
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);

		if ($fp = @fopen($this->cache_dir . 'sql_' . md5($query) . '.' . $phpEx, 'wb'))
		{
			@flock($fp, LOCK_EX);

			$lines = array();
			$query_id = 'Cache id #' . count($this->sql_rowset);
			$this->sql_rowset[$query_id] = array();

			while ($row = $db->sql_fetchrow($query_result))
			{
				$this->sql_rowset[$query_id][] = $row;

				$lines[] = "unserialize('" . str_replace("'", "\\'", str_replace('\\', '\\\\', serialize($row))) . "')";
			}
			$db->sql_freeresult($query_result);

			fwrite($fp, "<?php\n\n/*\n$query\n*/\n\n\$expired = (time() > " . (time() + $ttl) . ") ? TRUE : FALSE;\nif (\$expired) { return; }\n\n\$this->sql_rowset[\$query_id] = array(" . implode(',', $lines) . ') ?>');
			@flock($fp, LOCK_UN);
			fclose($fp);

			$query_result = $query_id;
		}
	}

	function sql_exists($query_id)
	{
		return isset($this->sql_rowset[$query_id]);
	}

	function sql_fetchrow($query_id)
	{
		return array_shift($this->sql_rowset[$query_id]);
	}
}
?>