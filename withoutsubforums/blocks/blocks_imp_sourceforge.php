<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_sourceforge.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_sourceforge.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

if(!function_exists('imp_sourceforge_block_func'))
{
    function imp_sourceforge_block_func()
    {

        global $template, $portal_config;

        // Set variables
        if( $portal_config['md_sourceforge_group_id'] == '' )
        {
            $sf_group_id = '86366';
        }
        else
        {
            $sf_group_id = $portal_config['md_sourceforge_group_id'];
        }

        $template->assign_vars(array(
            'SOURCEFORGE_GROUP_ID' => $sf_group_id
            )
        );

    }
}

imp_sourceforge_block_func();
?>