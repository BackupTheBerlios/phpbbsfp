<!-- $Id: email_topic_body.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<form action="{S_EMAIL_ACTION}" method="post" name="emailtopic">
  <table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
	  <td align="left" valign="bottom" nowrap="nowrap"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	</tr>
  </table>
  <table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25"><b>{L_TITLE}</b></th>
	</tr>
	<tr>
		<td class="row1"><span class="gen"><b>{L_FRIEND_NAME}</b></span></td>
		<td class="row2"><span class="genmed"><input type="text" class="post" tabindex="1" name="friend_name" size="25" maxlength="100" /></span></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen"><b>{L_FRIEND_EMAIL}</b></span></td>
		<td class="row2"><span class="genmed"><input type="text" class="post" name="friend_email" maxlength="100" size="25" tabindex="2" /></span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="gen"><b>{L_SUBJECT}</b></span></td>
	  <td class="row2"><span class="genmed">{TOPIC_TITLE}</span></td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" accesskey="s" tabindex="3" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
	</tr>
  </table>
</form>

<table width="100%" cellspacing="2" border="0" align="center">
  <tr>
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
