<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: functions_jr_admin.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : functions_jnr_admin.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2003       Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('EXPLODE_SEPERATOR_CHAR', '|');
define('JR_ADMIN_DIR', 'admin/');

if (!function_exists('find_lang_file_nivisec'))
{
    /**
    * @return boolean
    * @param filename string
    * @desc Tries to locate and include the specified language file.  Do not include the .php extension!
    */
    function find_lang_file_nivisec($filename)
    {
        global $lang, $phpbb_root_path, $board_config, $phpEx;

        if (file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/$filename.$phpEx"))
        {
            include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/$filename.$phpEx");
        }
        elseif (file_exists($phpbb_root_path . "language/lang_english/$filename.$phpEx"))
        {
            include_once($phpbb_root_path . "language/lang_english/$filename.$phpEx");
        }
        else
        {
            message_die(GENERAL_ERROR, "Unable to find a suitable language file for $filename!", '');
        }
        return true;
    }
}

// Removed config_update_nivisec() !!
// Removed set_filename_nivisec() !!
// Removed sql_query_nivisec() !!

function jr_admin_check_file_hashes($file)
{
    global $phpbb_root_path, $phpEx, $userdata;

    //Include the file to get the module list
    $setmodules = 1;
    include($phpbb_root_path.JR_ADMIN_DIR.$file);
    unset($setmodules);

    $jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

    $user_modules = explode(EXPLODE_SEPERATOR_CHAR, $jr_admin_userdata['user_jr_admin']);

    foreach($module as $cat => $module_data)
    {
        foreach($module_data as $module_name => $module_file)
        {
            //Remove sid if we find one
            $module_file = preg_replace("/(\?|&|&amp;)sid=[A-Z,a-z,0-9]{32}/", '', $module_file);
            //Make our unique ID
            $file_hash = md5($cat.$module_name.$module_file);
            //See if it is in the array
            if (in_array($file_hash, $user_modules))
            {
                return true;
            }
        }
    }

    //If we get this far, the user has no business with the module filename
    return false;
}

function jr_admin_get_module_list($user_module_list = false)
{
    global $db, $phpbb_root_path, $lang, $phpEx, $board_config, $userdata, $mvModules, $table_prefix;

    /* Debugging for this function. Debugging in this function causes changes to the way ADMIN users
    are interpreted.  You are warned */
    $debug = false;
    /* Even more debug info! */
    $verbose = false;

    //Read all the modules
    $setmodules = 1;
    $dir = @opendir($phpbb_root_path.JR_ADMIN_DIR);
    $pattern = "/^admin_.+\.$phpEx$/";
    while (($file = @readdir($dir)) !== false)
    {
        if (preg_match($pattern, $file))
        {
            include($phpbb_root_path.JR_ADMIN_DIR.$file);
        }
    }
    @closedir($dir);

    reset($mvModules);
    foreach ($mvModules as $name => $value)
    {
        if ($value['state'] != 1 && $value['state'] != 5)
            continue;
        reset($value['admin']);
        foreach ($value['admin'] as $n => $file)
        {
            include($phpbb_root_path . 'modules/' . $name . '/admin/' . $file);
        }
    }

    unset($setmodules);

    @ksort($module);
    if ($debug && $verbose)
    {
        print "<pre><font color=\"green\"><span class=\"gensmall\">DEBUG - Module List Non Cache - <br />";
        print_r($module);
        print "</span></font><br /></pre>";
    }


    //Get the cache list we have and find non-existing and new items
    foreach ($module as $cat => $item_array)
    {
        foreach ($item_array as $module_name => $filename)
        {
            //Remove sid in case some retarted person appended it early *(cough admin_disallow.php cough)*
            $filename = preg_replace("/(\?|&|&amp;)sid=[A-Z,a-z,0-9]{32}/", '', $filename);

            if ($debug && $verbose) print "<span class=\"gensmall\"><font color=\"red\">DEBUG - filename = $filename</font></span><br />";
            //Note the md5 function compilation here to make a unique id
            $file_hash = md5($cat.$module_name.$filename);
            if ($debug && $verbose) print "<span class=\"gensmall\"><font color=\"red\">DEBUG - hash = $file_hash</font></span><br />";

            //Wee a 3-D array of our info!
            if ($user_module_list && ($userdata['user_level'] != ADMIN || $debug))
            {
                //If we were passed a list of valid modules, make sure we are sending the correct list back
                $user_modules = explode(EXPLODE_SEPERATOR_CHAR, $user_module_list);
                if (in_array($file_hash, $user_modules))
                {
                    $module_list[$cat][$module_name]['filename'] = $filename;
                    $module_list[$cat][$module_name]['file_hash'] = $file_hash;
                }
            }
            else
            {
                //No list sent?  Send back all of them because we should be an ADMIN!
                $module_list[$cat][$module_name]['filename'] = $filename;
                $module_list[$cat][$module_name]['file_hash'] = $file_hash;
            }
        }
    }

    return $module_list;

}

function jr_admin_secure($file)
{
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $phpEx, $db, $lang, $userdata;

    /* Debugging in this function causes changes to the way ADMIN users
    are interpreted.  You are warned */
    $debug = false;

    $jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

    if ($debug)
    {
        if (!preg_match("/^index.$phpEx/", $file))
        {
            print '<pre><span class="gen"><font color="red">DEBUG - File Accessed - ';
            print $file;
            print '</pre></font></span><br />';
        }
    }
    if ($userdata['user_level'] == ADMIN && !$debug)
    {
        //Admin always has access
        return true;
    }
    elseif (empty($jr_admin_userdata['user_jr_admin']))
    {
        //This user has no modules and no business being here
        return false;
    }
    elseif (preg_match("/^index.$phpEx/", $file))
    {
        //We are at the index file, which is already secure pretty much
        return true;
    }
    elseif (isset($HTTP_GET_VARS['module_md5']) && in_array($HTTP_GET_VARS['module_md5'], explode(EXPLODE_SEPERATOR_CHAR, $jr_admin_userdata['user_jr_admin'])))
    {
        //The user has access for sure by module_id security from GET vars only
        return true;
    }
    elseif (!isset($HTTP_GET_VARS['module_md5']) && count($HTTP_POST_VARS))
    {
        //This user likely entered a post form, so let's use some checking logic
        //to make sure they are doing it from where they should be!

        //Get the filename without any arguments
        $file = preg_replace("/\?.+=.*$/", '', $file);
        //Return the check to make sure the user has access to what they are submitting
        return jr_admin_check_file_hashes($file);
    }
    elseif (!isset($HTTP_GET_VARS['module_md5']) && isset($HTTP_GET_VARS['sid']))
    {
        //This user has clicked on a url that specified items
        if ($HTTP_GET_VARS['sid'] != $userdata['session_id'])
        {
            return false;
        }
        else
        {
            //Get the filename without any arguments
            $file = preg_replace("/\?.+=.*$/", '', $file);
            //Return the check to make sure the user has access to what they are submitting
            return jr_admin_check_file_hashes($file);
        }
    }
    else
    {
        //Something came up that shouldn't have!
        return false;
    }
}

function jr_admin_include_all_lang_files()
{
    global $lang, $phpbb_root_path, $board_config, $phpEx;

    $dir = @opendir($phpbb_root_path.'language/lang_'.$board_config['default_lang']);
    $pattern = "/^lang.+\.$phpEx$/";
    while (($file = @readdir($dir)) !== false)
    {
        if (preg_match($pattern, $file))
        {
            include_once($phpbb_root_path.'language/lang_'.$board_config['default_lang'].'/'.$file);
        }
    }
    @closedir($dir);
}

function jr_admin_make_info_box()
{
    global $template, $lang, $module, $userdata, $board_config;

    /* Debug?  Changes the status stnading of ADMIN!!!  You are warned */
    $debug = false;

    if ($userdata['user_level'] != ADMIN || $debug)
    {
        find_lang_file_nivisec('lang_jr_admin');

        $jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

        $template->set_filenames(array('JR_ADMIN_INFO' => 'admin/jr_admin_user_info_header.tpl'));

        $template->assign_vars(array(
        'JR_ADMIN_START_DATE' => create_date($board_config['default_dateformat'], $jr_admin_userdata['start_date'], $board_config['board_timezone']),
        'JR_ADMIN_UPDATE_DATE' => create_date($board_config['default_dateformat'], $jr_admin_userdata['update_date'], $board_config['board_timezone']),
        'JR_ADMIN_ADMIN_NOTES' => $jr_admin_userdata['admin_notes'],
        'L_VERSION' => $lang['Version'],
        'L_JR_ADMIN_TITLE' => $lang['Junior_Admin_Info'],
        'VERSION' => MOD_VERSION,
        'L_MODULE_COUNT' => $lang['Module_Count'],
        'L_NOTES' => $lang['Notes'],
        'L_ALLOW_VIEW' => $lang['Allow_View'],
        'L_START_DATE' => $lang['Start_Date'],
        'L_UPDATE_DATE' => $lang['Update_Date'],
        'L_ADMIN_NOTES' => $lang['Admin_Notes']
        ));

        //Switch the info area if allowed to view it
        if ($jr_admin_userdata['notes_view'])
        {
            $template->assign_block_vars('jr_admin_info_switch', array());
        }

        $template->assign_var_from_handle('JR_ADMIN_INFO_TABLE', 'JR_ADMIN_INFO');
    }
}

function jr_admin_get_user_info($user_id)
{
    global $lang, $db;
    //Do the query and get the results, return the user row as well.

	$sql = 'SELECT * FROM ' . JR_ADMIN_TABLE . "
		WHERE user_id = $user_id";

	if ( !($result = $db->sql_query($sql)) )
	{
		messsage_die (GENERAL_ERROR, sprintf($lang['Error_Table'], JR_ADMIN_TABLE), '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$db->sql_freeresult($result);

	return $row;
}

function jr_admin_make_admin_link()
{
    global $lang, $userdata, $phpEx;

	if ( $userdata['user_level'] == ADMIN )
	{
		return '<a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />';
	}
	elseif ( $userdata['user_id'] == ANONYMOUS )
	{
		return '';
	}
	else
	{
	    $jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

	    if (!empty($jr_admin_userdata['user_jr_admin']))
	    {
	        return '<a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />';
	    }
	    else
	    {
	        return '';
	    }
	}
}
?>