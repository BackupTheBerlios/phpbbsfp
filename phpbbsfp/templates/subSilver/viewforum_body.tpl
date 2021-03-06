	<!-- IF S_FORUM_RULES -->
	<div class="forumrules">
		<!-- IF U_FORUM_RULES -->
			<a href="{U_FORUM_RULES}">{L_FORUM_RULES}</a>
		<!-- ELSE -->
			<h3 style="color:red">{L_FORUM_RULES}</h3><br />
			{FORUM_RULES}
		<!-- ENDIF -->
	</div>

	<br clear="all" />
	<!-- ENDIF -->

	<!-- IF S_DISPLAY_ACTIVE -->
	<table class="tablebg" width="100%" cellspacing="1">
		<tr>
			<td class="cat" colspan="<!-- IF S_TOPIC_ICONS -->7<!-- ELSE -->6<!-- ENDIF -->"><span class="nav">{L_ACTIVE_TOPICS}</span></td>
		</tr>

		<tr>
			<!-- IF S_TOPIC_ICONS -->
			<th colspan="3">&nbsp;{L_TOPICS}&nbsp;</th>
			<!-- ELSE -->
			<th colspan="2">&nbsp;{L_TOPICS}&nbsp;</th>
			<!-- ENDIF -->
			<th>&nbsp;{L_AUTHOR}&nbsp;</th>
			<th>&nbsp;{L_REPLIES}&nbsp;</th>
			<th>&nbsp;{L_VIEWS}&nbsp;</th>
			<th>&nbsp;{L_LAST_POST}&nbsp;</th>
		</tr>

		<!-- BEGIN topicrow -->

		<tr>
			<td class="row1" width="25" align="center">{topicrow.TOPIC_FOLDER_IMG}</td>
			<!-- IF S_TOPIC_ICONS -->
			<td class="row1" width="25" align="center">{topicrow.TOPIC_ICON_IMG}</td>
			<!-- ENDIF -->
			<td class="row1">
				<!-- IF topicrow.S_TOPIC_UNAPPROVED -->
					<a href="{topicrow.U_MCP_QUEUE}">{UNAPPROVED_IMG}</a>&nbsp;
				<!-- ENDIF -->
				<!-- IF topicrow.S_TOPIC_REPORTED -->
					<a href="{topicrow.U_MCP_REPORT}">{REPORTED_IMG}</a>&nbsp;
				<!-- ENDIF -->
				<p class="topictitle">{topicrow.NEWEST_POST_IMG} {topicrow.ATTACH_ICON_IMG} <a href="{topicrow.U_VIEW_TOPIC}">{topicrow.TOPIC_TITLE}</a></p><p class="gensmall">{topicrow.GOTO_PAGE}</p></td>
			<td class="row2" width="100" align="center"><p class="topicauthor">{topicrow.TOPIC_AUTHOR}</p></td>
			<td class="row1" width="50" align="center"><p class="topicdetails">{topicrow.REPLIES}</p></td>
			<td class="row2" width="50" align="center"><p class="topicdetails">{topicrow.VIEWS}</p></td>
			<td class="row1" width="120" align="center">
				<p class="topicdetails">{topicrow.LAST_POST_TIME}</p>
				<p class="topicdetails"><!-- IF topicrow.U_LAST_POST_AUTHOR --><a href="{topicrow.U_LAST_POST_AUTHOR}">{topicrow.LAST_POST_AUTHOR}</a><!-- ELSE -->{topicrow.LAST_POST_AUTHOR}<!-- ENDIF -->
					<a href="{topicrow.U_LAST_POST}">{topicrow.LAST_POST_IMG}</a>
				</p>
			</td>
		</tr>

		<!-- BEGINELSE -->

		<tr>
			<!-- IF S_TOPIC_ICONS -->
			<td class="row1" colspan="7" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
			<!-- ELSE -->
			<td class="row1" colspan="6" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
			<!-- ENDIF -->
		</tr>
		<!-- END topicrow -->

		<tr align="center">
			<td class="cat" colspan="<!-- IF S_TOPIC_ICONS -->8<!-- ELSE -->7<!-- ENDIF -->">&nbsp;</td>
		</tr>
	</table>

	<br clear="all" />
	<!-- ENDIF -->


	<!-- IF S_HAS_SUBFORUM -->
	<!-- INCLUDE viewforum_subforum.tpl -->
	<!-- ENDIF -->

	<!-- IF S_IS_POSTABLE -->
	<div id="pageheader">
		<h2><a class="titles" href="{U_VIEW_FORUM}">{FORUM_NAME}</a></h2>

		<!-- IF MODERATORS -->
			<p class="moderators">{L_MODERATORS}: {MODERATORS}</p>
		<!-- ENDIF -->
		<!-- IF U_MCP -->
			<p class="linkmcp">[ <a href="{U_MCP}">{L_MCP}</a> ]</p>
		<!-- ENDIF -->
	</div>

	<br clear="all" /><br />
	<!-- ENDIF -->

	<div id="pagecontent">

		<!-- IF S_IS_POSTABLE or TOTAL_TOPICS -->
		<table width="100%" cellspacing="1">
			<tr>
				<!-- IF S_IS_POSTABLE -->
				<td align="left" valign="middle"><a href="{U_POST_NEW_TOPIC}">{POST_IMG}</a></td>
				<!-- ENDIF -->
				<!-- IF TOTAL_TOPICS -->
				<td class="nav" valign="middle" nowrap="nowrap">&nbsp;{PAGE_NUMBER}<br /></td>
				<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_TOPICS} ]&nbsp;</td>
				<td class="gensmall" width="100%" align="right" nowrap="nowrap"><b>{PAGINATION}</b></td>
				<!-- ENDIF -->
			</tr>
		</table>
		<!-- ENDIF -->

		<!-- IF S_IS_POSTABLE -->
		<table class="tablebg" width="100%" cellspacing="1">
			<tr>
				<td class="cat" colspan="<!-- IF S_TOPIC_ICONS -->7<!-- ELSE -->6<!-- ENDIF -->"><table width="100%" cellspacing="0">
					<tr class="nav">
						<td valign="middle">&nbsp;<!-- IF S_WATCH_FORUM_LINK --><a href="{S_WATCH_FORUM_LINK}">{S_WATCH_FORUM_TITLE}</a><!-- ENDIF --></td>
						<td align="right" valign="middle"><a href="{U_MARK_TOPICS}">{L_MARK_TOPICS_READ}</a>&nbsp;</td>
					</tr>
				</table></td>
			</tr>

			<tr>
				<!-- IF S_TOPIC_ICONS -->
				<th colspan="3">&nbsp;{L_TOPICS}&nbsp;</th>
				<!-- ELSE -->
				<th colspan="2">&nbsp;{L_TOPICS}&nbsp;</th>
				<!-- ENDIF -->
				<th>&nbsp;{L_AUTHOR}&nbsp;</th>
				<th>&nbsp;{L_REPLIES}&nbsp;</th>
				<th>&nbsp;{L_VIEWS}&nbsp;</th>
				<th>&nbsp;{L_LAST_POST}&nbsp;</th>
			</tr>

			<!-- BEGIN topicrow -->

			<!-- IF topicrow.S_TOPIC_TYPE_SWITCH eq 1 -->
			<tr>
				<td class="row3" colspan="<!-- IF S_TOPIC_ICONS -->7<!-- ELSE -->6<!-- ENDIF -->"><b class="gensmall">{L_ANNOUNCEMENTS}</b></td>
			</tr>
			<!-- ELSEIF topicrow.S_TOPIC_TYPE_SWITCH eq 0 -->
			<tr>
				<td class="row3" colspan="<!-- IF S_TOPIC_ICONS -->7<!-- ELSE -->6<!-- ENDIF -->"><b class="gensmall">{L_TOPICS}</b></td>
			</tr>
			<!-- ENDIF -->

			<tr>
				<td class="row1" width="25" align="center">{topicrow.TOPIC_FOLDER_IMG}</td>
				<!-- IF S_TOPIC_ICONS -->
				<td class="row1" width="25" align="center">{topicrow.TOPIC_ICON_IMG}</td>
				<!-- ENDIF -->
				<td class="row1">
					<!-- IF topicrow.S_TOPIC_UNAPPROVED -->
						<a href="{topicrow.U_MCP_QUEUE}">{UNAPPROVED_IMG}</a>&nbsp;
					<!-- ENDIF -->
					<!-- IF topicrow.S_TOPIC_REPORTED -->
						<a href="{topicrow.U_MCP_REPORT}">{REPORTED_IMG}</a>&nbsp;
					<!-- ENDIF -->
					<p class="topictitle">{topicrow.NEWEST_POST_IMG} {topicrow.ATTACH_ICON_IMG} <a href="{topicrow.U_VIEW_TOPIC}">{topicrow.TOPIC_TITLE}</a></p><p class="gensmall">{topicrow.GOTO_PAGE}</p></td>
				<td class="row2" width="100" align="center"><p class="topicauthor">{topicrow.TOPIC_AUTHOR}</p></td>
				<td class="row1" width="50" align="center"><p class="topicdetails">{topicrow.REPLIES}</p></td>
				<td class="row2" width="50" align="center"><p class="topicdetails">{topicrow.VIEWS}</p></td>
				<td class="row1" width="120" align="center">
					<p class="topicdetails">{topicrow.LAST_POST_TIME}</p>
					<p class="topicdetails"><!-- IF topicrow.U_LAST_POST_AUTHOR --><a href="{topicrow.U_LAST_POST_AUTHOR}">{topicrow.LAST_POST_AUTHOR}</a><!-- ELSE -->{topicrow.LAST_POST_AUTHOR}<!-- ENDIF -->
						<a href="{topicrow.U_LAST_POST}">{topicrow.LAST_POST_IMG}</a>
					</p>
				</td>
			</tr>

			<!-- BEGINELSE -->

			<tr>
				<!-- IF S_TOPIC_ICONS -->
				<td class="row1" colspan="7" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
				<!-- ELSE -->
				<td class="row1" colspan="6" height="30" align="center" valign="middle"><span class="gen">{L_NO_TOPICS}</span></td>
				<!-- ENDIF -->
			</tr>
			<!-- END topicrow -->

			<tr align="center">
				<!-- IF S_TOPIC_ICONS -->
					<td class="cat" colspan="8">
				<!-- ELSE -->
					<td class="cat" colspan="7" >
				<!-- ENDIF -->
				<form method="post" action="{S_TOPIC_ACTION}"><span class="gensmall">{L_DISPLAY_TOPICS}:</span>&nbsp;{S_SELECT_SORT_DAYS}&nbsp;<span class="gensmall">{L_SORT_BY}</span> {S_SELECT_SORT_KEY} {S_SELECT_SORT_DIR}&nbsp;<input class="btnlite" type="submit" name="sort" value="{L_GO}" /></form></td>
			</tr>
		</table>

		<table width="100%" cellspacing="1">
			<tr>
				<td align="left" valign="middle"><a href="{U_POST_NEW_TOPIC}">{POST_IMG}</a></td>
				<td class="nav" nowrap="nowrap">&nbsp;{PAGE_NUMBER}</td>
				<td class="gensmall" nowrap="nowrap">&nbsp;[ {TOTAL_TOPICS} ]</td>
				<td class="nav" width="100%" align="right" nowrap="nowrap">{PAGINATION}</td>
			</tr>
		</table>

		<br clear="all" />
		<!-- ENDIF -->

	</div>

	<table style="background-color: #A9B8C2;" width="100%" cellspacing="1" cellpadding="0">
		<tr>
			<td class="row1">
				<p style="margin: 0px; float: left; color: black; font-size: 60%; font-weight: bold; white-space: normal;"><a href="{U_INDEX}">{L_INDEX}</a><!-- BEGIN navlinks --> &#187; <a href="{navlinks.U_VIEW_FORUM}">{navlinks.FORUM_NAME}</a><!-- END navlinks --></p>
				<p style="margin: 0px; float: right; font-size: 60%; white-space: nowrap;">{S_TIMEZONE}</p>
			</td>
		</tr>
	</table>

	<!-- IF S_DISPLAY_ONLINE_LIST -->
	<br clear="all" />

	<table class="tablebg" width="100%" cellspacing="1">
		<tr>
			<td class="cat"><h4>{L_WHO_IS_ONLINE}</h4></td>
		</tr>
		<tr>
			<td class="row1"><p class="gensmall">{LOGGED_IN_USER_LIST}</p></td>
		</tr>
	</table>
	<!-- ENDIF -->

	<!-- IF S_IS_POSTABLE -->
	<br clear="all" />

	<table width="100%" cellspacing="0">
		<tr>
			<td align="left" valign="top"><table cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td width="20" align="left">{FOLDER_NEW_IMG}</td>
					<td class="gensmall">{L_NEW_POSTS}</td>
					<td>&nbsp;&nbsp;</td>
					<td width="20" align="center">{FOLDER_IMG}</td>
					<td class="gensmall">{L_NO_NEW_POSTS}</td>
					<td>&nbsp;&nbsp;</td>
					<td width="20" align="center">{FOLDER_ANNOUNCE_IMG}</td>
					<td class="gensmall">{L_ICON_ANNOUNCEMENT}</td>
				</tr>
				<tr>
					<td width="20" align="center">{FOLDER_HOT_NEW_IMG}</td>
					<td class="gensmall">{L_NEW_POSTS_HOT}</td>
					<td>&nbsp;&nbsp;</td>
					<td width="20" align="center">{FOLDER_HOT_IMG}</td>
					<td class="gensmall">{L_NO_NEW_POSTS_HOT}</td>
					<td>&nbsp;&nbsp;</td>
					<td width="20" align="center">{FOLDER_STICKY_IMG}</td>
					<td class="gensmall">{L_ICON_STICKY}</td>
				</tr>
				<tr>
					<td class="gensmall">{FOLDER_LOCKED_NEW_IMG}</td>
					<td class="gensmall">{L_NEW_POSTS_LOCKED}</td>
					<td>&nbsp;&nbsp;</td>
					<td class="gensmall">{FOLDER_LOCKED_IMG}</td>
					<td class="gensmall">{L_NO_NEW_POSTS_LOCKED}</td>
					<td>&nbsp;&nbsp;</td>
					<td width="20" align="center">{FOLDER_MOVED_IMG}</td>
					<td class="gensmall">{L_MOVED_TOPIC}</td>
				</tr>
			</table></td>
			<td align="right"><span class="gensmall"><!-- BEGIN rules -->{rules.RULE}<br /><!-- END rules --></span></td>
		</tr>
	</table>
	<!-- ENDIF -->

	<br clear="all" />

	<table width="100%" cellspacing="0">
		<tr>
			<td><!-- IF S_DISPLAY_SEARCHBOX --><!-- INCLUDE searchbox.tpl --><!-- ENDIF --></td>
			<td align="right"><!-- INCLUDE jumpbox.tpl --></td>
		</tr>
	</table>