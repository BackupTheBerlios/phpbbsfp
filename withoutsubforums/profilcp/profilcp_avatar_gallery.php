<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: profilcp_avatar_gallery.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME	 : profilcp_avatar_gallery.php
// STARTED	 : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004	Project	Minerva	Team
//           : © 2001, 2003 The phpBB Group
// WWW		 : http://www.project-minerva.org/
// LICENCE	 : GPL v2.0	[ see /docs/COPYING	]
//
//--------------------------------------------------------------------------------------------------

if ( !empty($setmodules) || !isset($mode) || $mode != 'gallery' ) 
{
	return;
}

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

$nb_per_row = 4;

if (isset($HTTP_POST_VARS['avatargallery']))
{
	$category = trim(htmlspecialchars($HTTP_POST_VARS['avatarcategory']));
}

//
// set the page title and include the page header
//
$gen_simple_header = TRUE;
$page_title = $lang['Avatar_gallery'];
include ($phpbb_root_path . 'includes/page_header.'.$phpEx);
$template->set_filenames(array(
    'body' => 'profilcp/avatar_body.tpl')
);

// get the avatar categories
$dir = @opendir($phpbb_root_path . $board_config['avatar_gallery_path']);
$avatar_images = array();
while( ($file = @readdir($dir)) !== FALSE )
{
    if( $file != '.' && $file != '..' && !is_file($phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $file) && !is_link($phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $file) )
    {
        $sub_dir = @opendir($phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $file);
        $avatar_row_count = 0;
        $avatar_col_count = 0;
        while( ($sub_file = @readdir($sub_dir)) !== FALSE )
        {
            if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $sub_file) )
            {
                $avatar_images[$file][$avatar_row_count][$avatar_col_count] = $file . '/' . $sub_file;
                $avatar_name[$file][$avatar_row_count][$avatar_col_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $sub_file)));
                $avatar_col_count++;
                if( $avatar_col_count == $nb_per_row )
                {
                    $avatar_row_count++;
                    $avatar_col_count = 0;
                }
            }
        }
		@closedir($sub_dir);
    }
}
@closedir($dir);

@ksort($avatar_images);
@reset($avatar_images);
if( empty($category) ) 
{
	list($category, ) = each($avatar_images);
}

@reset($avatar_images);
$s_categories = '<select name="avatarcategory">';
foreach ( $avatar_images as $key => $void )
{
    $selected = ( $key == $category ) ? ' selected="selected"' : '';
    if( count($avatar_images[$key]) )
    {
        $s_categories .= '<option value="' . $key . '"' . $selected . '>' . ucfirst($key) . '</option>';
    }
}
$s_categories .= '</select>';

$s_colspan = 0;
for($i = 0, $i_count = count($avatar_images[$category]); $i < $i_count; $i++)
{
    $template->assign_block_vars('avatar_row', array());
    $s_colspan = max($s_colspan, count($avatar_images[$category][$i]));
    for($j = 0, $j_count = count($avatar_images[$category][$i]); $j < $j_count; $j++)
    {
        $template->assign_block_vars('avatar_row.avatar_column', array(
            'AVATAR_IMAGE' => $phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $avatar_images[$category][$i][$j],
            'AVATAR_NAME' => $avatar_name[$category][$i][$j],
            'AVATAR_FILE' => $avatar_images[$category][$i][$j],
            )
        );
    }
}

$s_hidden_vars = '<input type="hidden" name="sid" value="' . $session_id . '" />';
$template->assign_vars(array(
    'L_AVATAR_GALLERY' => $lang['Avatar_gallery'],
    'L_CATEGORY' => $lang['Select_category'],
    'L_GO' => $lang['Go'],
    'L_CLOSE_WINDOW' => $lang['Close_window'],

    'S_CATEGORY_SELECT' => $s_categories,
    'S_AVATAR_SELECT_ACTION' => append_sid("profile_avatar.$phpEx"),
    'S_HIDDEN_FIELDS' => $s_hidden_vars,
    )
);

//
// footer
//
$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>