
	<!-- $Id: viewforum_subforum.tpl,v 1.1 2004/09/01 20:50:28 dmaj007 Exp $ -->

	<table class="tablebg" width="100%" cellspacing="1">
		<tr>
			<td class="cat" colspan="5" align="right"><a class="nav" href="{U_MARK_FORUMS}">{L_MARK_FORUMS_READ}</a>&nbsp;</td>
		</tr>
		<tr>
			<th colspan="2" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
			<th width="50" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
			<th width="50" nowrap="nowrap">&nbsp;{L_POSTS}&nbsp;</th>
			<th nowrap="nowrap">&nbsp;{L_LAST_POST}&nbsp;</th>
		</tr>
		<!-- BEGIN forumrow -->
		<!-- IF forumrow.S_IS_CAT -->
		<tr>
			<td class="cat" colspan="2"><a class="cattitle" href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a></td>
			<td class="catdiv" colspan="3" align="right">&nbsp;</td>
		</tr>
		<!-- ELSEIF forumrow.S_IS_LINK -->
		<tr>
			<td class="row1" width="50" height="50" align="center" valign="middle">{forumrow.FORUM_FOLDER_IMG}</td>
			<td class="row1" height="50"><a class="forumlink" href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a><br />
				<table cellspacing="5" cellpadding="0" border="0">
					<tr>
						<td><span class="gensmall">{forumrow.FORUM_DESC}</span></td>
					</tr>
				</table></td>
			<td class="row2" colspan="3" align="center" valign="middle" height="50"><!-- IF forumrow.CLICKS --><span class="gensmall">{L_REDIRECTS}: {forumrow.CLICKS}</span><!-- ENDIF --></td>
		</tr>
		<!-- ELSE -->
		<tr>
			<td class="row1" width="50" height="50" align="center" valign="middle">{forumrow.FORUM_FOLDER_IMG}</td>
			<td class="row1" width="100%" height="50" valign="top"><a class="forumlink"  href="{forumrow.U_VIEWFORUM}">{forumrow.FORUM_NAME}</a><br />
				<table cellspacing="5" cellpadding="0" border="0">
					<tr>
						<td><span class="gensmall">{forumrow.FORUM_DESC}</span></td>
					</tr>
				</table><span class="gensmall"><!-- IF forumrow.MODERATORS --><b>{forumrow.L_MODERATOR_STR}:</b> {forumrow.MODERATORS}<br /><!-- ENDIF --><!-- IF forumrow.SUBFORUMS --><br /><b>{forumrow.L_SUBFORUM_STR}</b> {forumrow.SUBFORUMS}<!-- ENDIF --></span></td>
			<td class="row2" align="center" valign="middle" height="50"><span class="gensmall">{forumrow.TOPICS}</span></td>
			<td class="row2" align="center" valign="middle" height="50"><span class="gensmall">{forumrow.POSTS}</span></td>
			<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"><span class="gensmall"><!-- IF forumrow.LAST_POST_TIME -->{forumrow.LAST_POST_TIME}<br /><!-- IF forumrow.U_LAST_POSTER --><a href="{forumrow.U_LAST_POSTER}">{forumrow.LAST_POSTER}</a><!-- ELSE -->{forumrow.LAST_POSTER}<!-- ENDIF --> <a href="{forumrow.U_LAST_POST}">{forumrow.LAST_POST_IMG}</a><!-- ELSE -->{L_NO_POSTS}<!-- ENDIF --></span></td>
		</tr>
		<!-- ENDIF -->
		<!-- END forumrow -->
	</table>

	<br clear="all" />
