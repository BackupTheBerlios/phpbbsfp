<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_sub_template.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME  : lang_extend_sub_template.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
    $lang['Lang_extend_sub_template']   = 'Sub-template';

    $lang['Subtemplate']                = 'Sub-templates';
    $lang['Subtemplate_explain']        = 'Here you can attach sub-templates to a category or a forum';
    $lang['Choose_main_style']          = 'Choose a main style';
    $lang['main_style']                 = 'Main style';
    $lang['subtpl_name']                = 'Sub-template name';
    $lang['subtpl_dir']                 = 'Sub-template directory';
    $lang['subtpl_imagefile']           = 'Images config file';
    $lang['subtpl_create']              = 'Add a new sub-template';
    $lang['subtpl_usage']               = 'Sub-templates used in';
    $lang['Select_dir']                 = 'Select a directory';

    $lang['subtpl_error_name_missing']  = 'The sub-template name is missing';
    $lang['subtpl_error_dir_missing']   = 'The sub-template dir is missing';
    $lang['subtpl_error_no_selection']  = 'There nothing selected to apply this sub-template';

    $lang['subtpl_click_return']        = 'Update done. Click %sHere%s to return to the sub-template administration';
}

?>