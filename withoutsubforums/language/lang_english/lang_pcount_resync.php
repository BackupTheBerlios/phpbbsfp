<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_pcount_resync.php,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $
//
// FILENAME  : lang_pcount_resync.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2001, 2003 The phpBB Group
//           : © 2003       Adam Alkins
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

$lang['Resync_page_title'] = 'Resync User Post Counts';
$lang['Resync_page_desc_simple'] = 'Welcome the the User Post Count Resyncer. You can Press the resync button to resync all the post counts to their true figure by recounting the amound of actual posts they made.<br /><br />Batch Mode allows you to resync accounts in steps, and is useful for large boards. Batch Mode also provides progress updates while resyncing, therefore if the script dies before finishing (Memory limit reached or timeout), you can resume at that position by entering the batch number (Leave blank to start at the beginning. The Resyncs per batch specifies how many Resyncs it will run per batch. If Batch is set as on and amount is left blank, it will default to 50 per batch.';
$lang['Resync_page_desc_adv'] = 'Welcome the the User Post Count Resyncer. You can select which Forums you would like to Resync either a specific user or all users in the defined forums. Press the resync button to resync the post counts by recounting the amound of actual posts they made based on your criteria.<br /><br />Batch Mode allows you to resync accounts in steps, and is useful for large boards. Batch Mode also provides progress updates while resyncing, therefore if the script dies before finishing (Memory limit reached or timeout), you can resume at that position by entering the batch number (Leave blank to start at the beginning. The Resyncs per batch specifies how many Resyncs it will run per batch. If Batch is set as on and amount is left blank, it will default to 50 per batch.';

$lang['Resync_batch_mode'] = 'Batch Mode';
$lang['Resync_batch_number'] = 'Batch Number';
$lang['Resync_batch_amount'] = 'Resyncs per Batch';
$lang['Resync_finished'] = 'Finished';

$lang['Resync_completed'] = 'Resync Successfully Completed';

$lang['Resync_question'] = 'Resync?';

$lang['Resync_check_all'] = 'Check box to Resync all Users:';

$lang['Resync_do'] = 'Do Resync';

$lang['Resync_redirect'] = '<br /><br />Return to the <a href="%s">Post Count Resyncing Tool</a><br /><br />Return to the <a href="%s">Admin Index</a>.';
$lang['Resync_invalid'] = 'Invalid Settings - No users to Resync';

?>
