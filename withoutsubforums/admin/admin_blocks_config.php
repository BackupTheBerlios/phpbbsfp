<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_blocks_config.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_blocks_config.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2004		Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['Blocks']['Configuration'] = $file;
    return;
}

define('IN_PHPBB', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require('./pagestart.' . $phpEx);

$template->set_filenames(array(
    "body" => "portal_config_body.tpl")
);

//
// Get Blocks Variabled data
//
$sql = "SELECT * FROM " . BLOCKS_VARIABLE_TABLE . " ORDER BY bvid";
if(!$result = $db->sql_query($sql))
{
    message_die(CRITICAL_ERROR, "Could not query portal config information", "", __LINE__, __FILE__, $sql);
}

$var = array();
while( $row = $db->sql_fetchrow($result) )
{
    $var[$row['config_name']] = array();
    $var[$row['config_name']]['label'] = $row['label'];
    $var[$row['config_name']]['sub_label'] = $row['sub_label'];
    $var[$row['config_name']]['field_options'] = $row['field_options'];
    $var[$row['config_name']]['field_values'] = $row['field_values'];
    $var[$row['config_name']]['type'] = $row['type'];
    $var[$row['config_name']]['block'] = ereg_replace("_"," ",$row['block']);
}

$sql = "SELECT * FROM " . BLOCKS_LAYOUT_TABLE . " ORDER BY lid";
if(!$result = $db->sql_query($sql))
{
    message_die(CRITICAL_ERROR, "Could not query layout information", "", __LINE__, __FILE__, $sql);
}

$layout_options = '';
$layout_values = '';
$i=0;
while( $row = $db->sql_fetchrow($result) )
{
    if(!$i)
    {
        $layout_options .= $row['name'];
        $layout_values .= $row['lid'];
    }else
    {
        $layout_options .= ',' . $row['name'];
        $layout_values .= ',' . $row['lid'];
    }
    $i++;
}

//
// Pull all config data
//
$sql = "SELECT * FROM " . BLOCKS_VARIABLE_TABLE . " AS b
			RIGHT JOIN " . BLOCKS_CONFIG_TABLE . " AS p USING (config_name)
			WHERE b.config_name IS NULL OR b.config_name IS NOT NULL
			ORDER BY b.block, b.bvid, p.id";
if(!$result = $db->sql_query($sql))
{
    message_die(CRITICAL_ERROR, "Could not query portal config information", "", __LINE__, __FILE__, $sql);
}
else
{
    $controltype = array( '1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
    while( $row = $db->sql_fetchrow($result) )
    {
        $portal_name = $row['config_name'];
        $portal_value = $row['config_value'];
        if( $portal_name == 'default_layout' )
        {
            $var[$portal_name]['label'] = $lang['Default_Layout'];
            $var[$portal_name]['sub_label'] = $lang['Default_Layout_Explain'];
            $var[$portal_name]['field_options'] = $layout_options;
            $var[$portal_name]['field_values'] = $layout_values;
            $var[$portal_name]['type'] = '2';
            $var[$portal_name]['block'] = '@Portal Config';
        }

        switch($var[$portal_name]['type'])
        {
            case '1':
                $field = '<input type="text" maxlength="255" size="40" name="' . $portal_name . '" value="' . $portal_value . '" class="post" />';
                break;
            case '2':
                $options = explode("," , $var[$portal_name]['field_options']);
                $values = explode("," , $var[$portal_name]['field_values']);
                $field = '<select name = "' . $portal_name . '">';
                $i=0;
                while ($options[$i])
                {
                    $selected = ($portal_value == trim($values[$i])) ? 'selected' : '';
                    $field .= '<option value = "' . trim($values[$i]) . '" ' . $selected . '>' . trim($options[$i]);
                    $i++;
                }
                $field .= '</selected>';
                break;
            case '3':
                $options = explode("," , $var[$portal_name]['field_options']);
                $values = explode("," , $var[$portal_name]['field_values']);
                $field = '';
                $i=0;
                while ($options[$i])
                {
                    $checked = ($portal_value == trim($values[$i])) ? 'checked' : '';
                    $field .= '<input type="radio" name = "' . $portal_name . '" value = "' . trim($values[$i]) . '" ' . $checked . '>' . trim($options[$i]) . '&nbsp;&nbsp;';
                    $i++;
                }
                break;
            case '4':
                $checked = ($portal_value) ? 'checked' : '';
                $field = '<input type="checkbox" name="' . $portal_name . '" ' . $checked . '>';
                break;
            default:
                $field = '';
        }

        $default_layout[$portal_name] = $portal_value;

        if($var[$portal_name]['type'] == '4')
        {
            $new[$portal_name] = ( isset($HTTP_POST_VARS[$portal_name]) ) ? '1' : '0';
        }
        else
        {
            $new[$portal_name] = ( isset($HTTP_POST_VARS[$portal_name]) ) ? $HTTP_POST_VARS[$portal_name] : $default_layout[$portal_name];
        }

        if( isset($HTTP_POST_VARS['submit']) )
        {
            $sql = "UPDATE " . BLOCKS_CONFIG_TABLE . " SET
                config_value = '" . str_replace("\'", "''", $new[$portal_name]) . "'
                WHERE config_name = '$portal_name'";
            if( !$db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Failed to update configuration for $portal_name", "", __LINE__, __FILE__, $sql);
            }
        }
        else
        {
            $is_block = ($var[$portal_name]['block']!='@Portal Config') ? 'block ' : '';
            $template->assign_block_vars("portal", array(
                "L_FIELD_LABEL" => $var[$portal_name]['label'],
                "L_FIELD_SUBLABEL" => '<br /><br /><span class="gensmall">' . $var[$portal_name]['sub_label'] . ' [ ' . ereg_replace("@","",$var[$portal_name]['block']) . ' ' . $is_block . ']</span>',
                "FIELD" => $field
                )
            );
        }
    }

    if( isset($HTTP_POST_VARS['submit']) )
    {
		$cache->destroy('portal_config');

        $message = $lang['Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_blocks_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
    }
}

$template->assign_vars(array(
    "S_CONFIG_ACTION" => append_sid("admin_blocks_config.$phpEx"),
    "L_CONFIGURATION_TITLE" => $lang['Portal_Config'],
    "L_CONFIGURATION_EXPLAIN" => $lang['Portal_Explain'],
    "L_GENERAL_CONFIG" => $lang['Portal_General_Config'],
    "L_SUBMIT" => $lang['Submit'],
    "L_RESET" => $lang['Reset']
    )
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>

