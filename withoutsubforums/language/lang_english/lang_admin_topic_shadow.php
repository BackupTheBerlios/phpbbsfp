<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_admin_topic_shadow.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_admin_topic_shadow.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Nivisec
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

/* General */
$lang['Del_Before_Date'] = 'Deleted all Shadow Topics before %s<br />'; // %s = insertion of date
$lang['Deleted_Topic'] = 'Deleted Shadow Topic %s<br />'; // %s = topic name
$lang['Affected_Rows'] = '%d known entries were affected<br />'; // %d = affected rows (not avail with all databases!)
$lang['Delete_From_Date'] = 'All Shadow Topics that were created before the entered date will be removed.';
$lang['Delete_Before_Date_Button'] = 'Delete All Before Date';
$lang['No_Shadow_Topics'] = 'No Shadow Topics were found.';
$lang['Topic_Shadow'] = 'Prune Topic Shadows';
$lang['TS_Desc'] = 'Allows the removal of shadow topics without the deletion of the actual message.  Shadow topics are created when you move a post to another forum and choose to leave behind a link in the original forum to the new post.';
$lang['Month'] = 'Month';
$lang['Day'] = 'Day';
$lang['Year'] = 'Year';
$lang['Clear'] = 'Clear';
$lang['Resync_Ran_On'] = 'Resync Ran On %s<br />'; // %s = insertion of forum name
$lang['All_Forums'] = 'All Forums';
$lang['Version'] = 'Version';

$lang['Title'] = 'Title';
$lang['Moved_To'] = 'Moved To';
$lang['Moved_From'] = 'Moved From';
$lang['Delete'] = 'Delete';

/* Modes */
$lang['topic_time'] = 'Topic Time';
$lang['topic_title'] = 'Topic Title';

/* Errors */
$lang['Error_Month'] = 'Your input month must be between 1 and 12';
$lang['Error_Day'] = 'Your input day must be between 1 and 31';
$lang['Error_Year'] = 'Your input year must be between 1970 and 2038';
$lang['Error_Topics_Table'] = 'Error accessing topics table';

//Special Cases, Do not change for another language
$lang['ASC'] = $lang['Sort_Ascending'];
$lang['DESC'] = $lang['Sort_Descending'];
$lang['Nivisec_Com'] = 'Nivisec.com';



?>