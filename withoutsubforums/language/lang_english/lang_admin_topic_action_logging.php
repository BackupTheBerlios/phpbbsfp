<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_admin_topic_action_logging.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_topic_action_logging.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/* General */
$lang['Action_Logging'] = 'Action Logging';
$lang['Logs'] = 'Logs';
$lang['Topic_Actions'] = 'Topic Actions';
$lang['Filter_By_User'] = 'Filter By Username';
$lang['Filter_By_Type'] = 'Filter By Type';
$lang['Sort'] = 'Sort';
$lang['No_Actions'] = 'No Actions found meeting your criteria.';
$lang['Log_Time'] = 'Log Date';
$lang['Action'] = 'Action';
$lang['Topic_Title'] = 'Topic Title';
$lang['Log_Page_Desc'] = 'You can view all the actions a moderator and admin has taken towards topics.';
$lang['Topic_Text'] = 'Topic Text';
$lang['Topic_Status'] = 'Topic Status';
$lang['Topic_Not_Exist'] = 'Topic appears to no longer exist.';
$lang['Topic_Exists'] = 'Topic exists in the %s forum.'; // %s = forum name
$lang['Version'] = 'Version';
$lang['Deleted_Num_Items'] = 'Deleted %d Logs';
$lang['Delete'] = 'Delete';

/* Errors */
$lang['Error_Logs_Table'] = 'Error querying Topic Logging Table.';
$lang['Error_No_User'] = 'The user "%s" doesn\'t seem to exist.<br />'; //%s = username
$sort_types = array('u.username', 'tl.log_time', 'tl.log_action', 'tl.topic_title');

/* Types Explanation */
$lang['LOG_-1'] = 'All';
$lang['LOG_0'] = 'Moved';
$lang['LOG_1'] = 'Locked';
$lang['LOG_2'] = 'Unlocked';
$lang['LOG_3'] = 'Deleted';
$lang['LOG_4'] = 'Split';
$lang['LOG_5'] = 'Stickied';
$lang['LOG_6'] = 'Announced';
$lang['LOG_7'] = 'Normalised';
$lang['LOG_8'] = 'Merged';
$lang['LOG_9'] = 'Editted';

/*Special Cases, Do not bother to change for another language */
$lang['ASC'] = $lang['Sort_Ascending'];
$lang['DESC'] = $lang['Sort_Descending'];
$lang['u.username'] = $lang['Username'];
$lang['tl.log_time'] = $lang['Log_Time'];
$lang['tl.log_action'] = $lang['Action'];
$lang['tl.topic_title'] = $lang['Topic_Title'];


?>