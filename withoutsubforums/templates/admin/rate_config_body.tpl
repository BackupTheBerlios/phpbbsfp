<!-- $Id: rate_config_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<form method="post" action="{S_MODE_ACTION}">
<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
	  <th class="thHead">{L_STATUS}</th>
	</tr>
	<tr>
	<td align="center" class="{CLASS_1}" colspan="2"><span class="genmed">{ADMIN_MESSAGE}<br /><br /></span></td>
	</tr>
</table>
<br />
<br />
<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
	  <th colspan="2" class="thHead">Topic Rating {L_PAGE_NAME}</th>
	</tr>
	<!-- BEGIN config_row -->
	<tr>
	  <td class="{CLASS_1}"><span class="genmed">{config_row.L_CONFIG}:</span></td>
	  <td class="{CLASS_2}"><span class="genmed">{config_row.S_CONFIG}</span></td>
	</tr>
	<!-- END config_row -->
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>

</form>