<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" valign="bottom"><span class="gensmall">
	<!-- BEGIN switch_user_logged_in -->
	{LAST_VISIT_DATE}<br />
	<!-- END switch_user_logged_in -->
	{CURRENT_TIME}<br /></span><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td align="right" valign="bottom" class="gensmall">
		<!-- BEGIN switch_user_logged_in -->
		<a href="{U_SEARCH_NEW}" class="gensmall">{L_SEARCH_NEW}</a><br /><a href="{U_SEARCH_SELF}" class="gensmall">{L_SEARCH_SELF}</a><br />
		<!-- END switch_user_logged_in -->
		<a href="{U_SEARCH_UNANSWERED}" class="gensmall">{L_SEARCH_UNANSWERED}</a></td>
  </tr>
</table>
<table class="forumline" cellspacing="1" width="100%">
	<tr>
		<td class="cat" colspan="5" align="right"><a class="nav" href="{U_MARK_FORUMS}">{L_MARK_FORUMS_READ}</a>&nbsp;</td>
	</tr>
	<tr>
		<th colspan="2" class="thCornerL" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
		<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
		<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_POSTS}&nbsp;</th>
		<th class="thCornerR" nowrap="nowrap">&nbsp;{L_LAST_POST}&nbsp;</th>
	</tr>
	<!-- BEGIN forumrow -->
	<!-- IF forumrow.S_IS_CAT -->
	<tr>
		<td class="catLeft" colspan="2"><span class="cattitle"><a href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a></span></td>
		<td class="rowpic" colspan="3">&nbsp;</td>
	</tr>
	<!-- ELSEIF forumrow.S_IS_LINK -->
	<tr>
		<td class="row1" width="50" align="center">{forumrow.FORUM_FOLDER_IMG}</td>
		<!-- IF forumrow.CLICKS -->
		<td class="row1">
		<!-- ELSE -->
		<td class="row1" colspan="4">
		<!-- ENDIF -->
			<a class="forumlink" href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a>
			<p class="forumdesc">{forumrow.FORUM_DESC}</p></td>
		<!-- IF forumrow.CLICKS -->
		<td class="row2" colspan="3" align="center"><span class="gensmall">{L_REDIRECTS}: {forumrow.CLICKS}</span></td>
		<!-- ENDIF -->
	</tr>
	<!-- ELSE -->
	<tr>
		<td class="row1" width="50" align="center">{forumrow.FORUM_FOLDER_IMG}</td>
		<td class="row1" width="100%">
			<a class="forumlink" href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a>
			<p class="genmed">{forumrow.FORUM_DESC}</p>
			<!-- IF forumrow.MODERATORS -->
				<p class="gensmall">{forumrow.L_MODERATOR_STR}: {forumrow.MODERATORS}</p>
			<!-- ENDIF -->
			<!-- IF forumrow.SUBFORUMS -->
				<p class="genmed">{forumrow.L_SUBFORUM_STR} {forumrow.SUBFORUMS}</p>
			<!-- ENDIF -->
		</td>
		<td class="row2" align="center"><p class="gensmall">{forumrow.TOPICS}</p></td>
		<td class="row2" align="center"><p class="gensmall">{forumrow.POSTS}</p></td>
		<td class="row2" align="center" nowrap="nowrap">
			<!-- IF forumrow.LAST_POST_TIME -->
				<p class="gensmall">{forumrow.LAST_POST_TIME}</p>
				<p class="gensmall">
				<!-- IF forumrow.U_LAST_POSTER -->
					<a href="{forumrow.U_LAST_POSTER}">{forumrow.LAST_POSTER}</a>
				<!-- ELSE -->
					{forumrow.LAST_POSTER}
				<!-- ENDIF -->
				<a href="{forumrow.U_LAST_POST}">{forumrow.LAST_POST_IMG}</a>
				</p>
			<!-- ELSE -->
				<p class="gensmall">{L_NO_POSTS}</p>
			<!-- ENDIF -->
		</td>
	</tr>
	<!-- ENDIF -->
	<!-- BEGINELSE -->
	<tr>
		<td class="row1" colspan="5" align="center"><p class="gensmall">{L_NO_FORUMS}</p></td>
	</tr>
	<!-- END forumrow -->
</table>
<table width="100%" cellspacing="0" border="0" align="center" cellpadding="2">
  <tr> 
	<td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
  </tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr> 
	<td class="catHead" colspan="2" height="28"><span class="cattitle"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></span></td>
  </tr>
  <tr> 
	<td class="row1" align="center" valign="middle" rowspan="2"><img src="templates/subSilver/images/whosonline.gif" alt="{L_WHO_IS_ONLINE}" /></td>
	<td class="row1" align="left" width="100%"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}</span>
	</td>
  </tr>
  <tr> 
	<td class="row1" align="left"><span class="gensmall">{TOTAL_USERS_ONLINE} &nbsp; [ {L_WHOSONLINE_ADMIN} ] &nbsp; [ {L_WHOSONLINE_MOD} ]<br />{RECORD_USERS}<br />{LOGGED_IN_USER_LIST}</span></td>
  </tr>
</table>

<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{L_ONLINE_EXPLAIN}</span></td>
</tr>
</table>

<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="catHead" height="28"><a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span></td>
	</tr>
	<tr> 
	  <td class="row1" align="center" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
		<input class="post" type="text" name="username" size="10" />
		&nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
		<input class="post" type="password" name="password" size="10" maxlength="32" />
		&nbsp;&nbsp; &nbsp;&nbsp;{L_AUTO_LOGIN} 
		<input class="text" type="checkbox" name="autologin" />
		&nbsp;&nbsp;&nbsp; 
		<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
		</span> </td>
	</tr>
  </table>
</form>
<!-- END switch_user_logged_out -->

<br clear="all" />
