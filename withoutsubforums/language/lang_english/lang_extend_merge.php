<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_merge.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_extend_merge.php
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
	$lang['Lang_extend_merge'] = 'Simply Merge Threads';
}

$lang['Refresh'] = 'Refresh';
$lang['Merge_topics'] = 'Merge topics';
$lang['Merge_title'] = 'New topic title';
$lang['Merge_title_explain'] = 'This will be the new title of the final topic. Let it blank if you want the system to use the title of the destination topic';
$lang['Merge_topic_from'] = 'Topic to merge';
$lang['Merge_topic_from_explain'] = 'This topic will be merge to the other topic. You can input the topic id, the url of the topic, or the url of a post in this topic';
$lang['Merge_topic_to'] = 'Destination topic';
$lang['Merge_topic_to_explain'] = 'This topic will get all the posts of the precedent topic. You can input the topic id, the url of the topic, or the url of a post in this topic';
$lang['Merge_from_not_found'] = 'The topic to merge hasn\'t been found';
$lang['Merge_to_not_found'] = 'The destination topic hasn\'t been found';
$lang['Merge_topics_equals'] = 'You can\'t merge a topic with itself';
$lang['Merge_from_not_authorized'] = 'You are not a authorized to moderate topics coming from the forum of the topic to merge';
$lang['Merge_to_not_authorized'] =  'You are not a authorized to moderate topics coming from the forum of the destination topic';
$lang['Merge_poll_from'] = 'There is a poll on the topic to merge. It will be copied to the destination topic';
$lang['Merge_poll_from_and_to'] = 'The destination topic already has got a poll. The poll of the topic to merge will be deleted';
$lang['Merge_confirm_process'] = 'Are you sure you want to merge <br />"<b>%s</b>"<br />to<br />"<b>%s</b>"';
$lang['Merge_topic_done'] = 'The topics have been successfully merged.';

?>