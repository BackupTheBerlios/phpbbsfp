<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: blocks_imp_poll.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : blocks_imp_poll.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       Ronald John David
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die('Hacking attempt');
}

if(!function_exists('imp_poll_block_func'))
{
    function imp_poll_block_func()
    {
        global $template, $portal_config, $db, $userdata, $images, $lang, $phpEx;
		blocks_config_init(&$portal_config);
        $template->assign_block_vars('PORTAL_POLL', array());

        $sql = 'SELECT
              t.*, vd.*
            FROM
              ' . TOPICS_TABLE . ' AS t,
              ' . VOTE_DESC_TABLE . ' AS vd
            WHERE
              t.forum_id IN (' . $portal_config['md_poll_forum_id'] . ') AND
              t.topic_status <> 1 AND
              t.topic_status <> 2 AND
              t.topic_vote = 1 AND
              t.topic_id = vd.topic_id
            ORDER BY
              t.topic_time DESC
            LIMIT
              0,1';


        if(!$result = $db->sql_query($sql))
        {
            message_die(GENERAL_ERROR, "Couldn't obtain poll information.", "", __LINE__, __FILE__, $sql);
        }

        if(!$total_posts = $db->sql_numrows($result))
        {
        	$template->assign_vars(array(
        		"L_NO_POLL" => $lang['No_poll'],
				"NO_POLLS" => true
				)
            );
        }

        if($total_posts = $db->sql_numrows($result))
        {
        $pollrow = $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);

        $topic_id = $pollrow[0]['topic_id'] ;

            $sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
                FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
                WHERE vd.topic_id = $topic_id
                    AND vr.vote_id = vd.vote_id
                ORDER BY vr.vote_option_id ASC";
            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't obtain vote data for this topic", "", __LINE__, __FILE__, $sql);
            }

            if( $portal_vote_info = $db->sql_fetchrowset($result) )
            {
				$portal_vote_options =	count($portal_vote_info);
                $portal_vote_id = $portal_vote_info[0]['vote_id'];
                $portal_vote_title = $portal_vote_info[0]['vote_text'];
				if ($userdata['user_id'] ==	ANONYMOUS)
				{
					$guest_sql = " AND vote_user_ip	= '$user_ip'";
				}
				else
				{
					$guest_sql = '';
				}
                $sql = "SELECT vote_id
                    FROM " . VOTE_USERS_TABLE . "
                    WHERE vote_id = $portal_vote_id
                        AND vote_user_id = " . intval($userdata['user_id']) . $guest_sql;
                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't obtain user vote data for this topic", "", __LINE__, __FILE__, $sql);
                }

                $user_voted = ( $db->sql_numrows($result) ) ? TRUE : 0;

                if( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
                {
                    $view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] : $HTTP_POST_VARS['vote'] ) == "viewresult" ) ? TRUE : 0;
                }
                else
                {
                    $view_result = 0;
                }

                $poll_expired = ( $portal_vote_info[0]['vote_length'] ) ? ( ( $portal_vote_info[0]['vote_start'] + $portal_vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;

                if( $user_voted || $view_result || $poll_expired || $forum_row['topic_status'] == TOPIC_LOCKED )
                {

//                    $template->set_filenames(array(
//                       "pollbox" => "blocks/poll_block_result.tpl")
//                 );

                    $portal_vote_results_sum = 0;

                    for($i = 0; $i < $portal_vote_options; $i++)
                    {
                        $portal_vote_results_sum += $portal_vote_info[$i]['vote_result'];
                    }

                    $portal_vote_graphic = 0;
                    $portal_vote_graphic_max = count($images['voting_graphic']);

                    for($i = 0; $i < $portal_vote_options; $i++)
                    {
                        $portal_vote_percent = ( $portal_vote_results_sum > 0 ) ? $portal_vote_info[$i]['vote_result'] / $portal_vote_results_sum : 0;
                        $portal_vote_graphic_length = round($portal_vote_percent * $portal_config['md_poll_bar_length']);

                        $portal_vote_graphic_img = $images['voting_graphic'][$portal_vote_graphic];
                        $portal_vote_graphic_img_left = $images['voting_graphic_left'];
                        $portal_vote_graphic_img_right = $images['voting_graphic_right'];
                        $portal_vote_graphic = ($portal_vote_graphic < $portal_vote_graphic_max - 1) ? $portal_vote_graphic + 1 : 0;

                        if( count($orig_word) )
                        {
                            $portal_vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $portal_vote_info[$i]['vote_option_text']);
                        }

                        $template->assign_block_vars("portal_poll_option", array(
                            "POLL_OPTION_CAPTION" => $portal_vote_info[$i]['vote_option_text'],
                            "POLL_OPTION_RESULT" => $portal_vote_info[$i]['vote_result'],
                            "POLL_OPTION_PERCENT" => sprintf("%.1d%%", ($portal_vote_percent * 100)),

                            "POLL_OPTION_IMG" => $portal_vote_graphic_img,
                            "POLL_OPTION_IMG_WIDTH" => $portal_vote_graphic_length/1)
                        );
                    }

                    $template->assign_vars(array(
                        "L_TOTAL_VOTES" => $lang['Total_votes'],
                        "TOTAL_VOTES" => $portal_vote_results_sum,
                        "POLL_OPTION_IMG_L" => $portal_vote_graphic_img_left,
                        "PORTAL_POLL_OPTION_IMG_R" => $portal_vote_graphic_img_right,
                        "L_VIEW_RESULTS" => $lang['View_results'],
                        "U_VIEW_RESULTS" => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"),
						"POLL_RESULT_INCLUDE" => true
						)
                    );

                }
                else
                {
//                   $template->set_filenames(array(
//                      "pollbox" => "blocks/poll_block_ballot.tpl")
//                    );

                    for($i = 0; $i < $portal_vote_options; $i++)
                    {
                        if( count($orig_word) )
                        {
                            $portal_vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $portal_vote_info[$i]['vote_option_text']);
                        }

                        $template->assign_block_vars("portal_poll_option", array(
                            "POLL_OPTION_ID" => $portal_vote_info[$i]['vote_option_id'],
                            "POLL_OPTION_CAPTION" => $portal_vote_info[$i]['vote_option_text'])
                        );
                    }
                    $template->assign_vars(array(
                        "LOGIN_TO_VOTE" => '<b><a href="' . append_sid("login.$phpEx?redirect=index.$phpEx") . '">' . $lang['Login_to_vote'] . '</a><b>',
						'POLL_RESULT_INCLUDE' => false
						));

                    $s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
                }

                if( count($orig_word) )
                {
                    $portal_vote_title = preg_replace($orig_word, $replacement_word, $portal_vote_title);
                }

                $template->assign_vars(array(
                    "POLL_QUESTION" => $portal_vote_title,
                    "L_SUBMIT_VOTE" => $lang['Submit_vote'],
                    "S_HIDDEN_FIELDS" => ( !empty($s_hidden_fields) ) ? $s_hidden_fields : "",
                    "S_POLL_ACTION" => append_sid("posting.$phpEx?" . POST_TOPIC_URL . "=$topic_id"))
                );

                //$template->assign_var_from_handle("PORTAL_POLL", "pollbox");
            }
        }
    }
}

imp_poll_block_func();
?>