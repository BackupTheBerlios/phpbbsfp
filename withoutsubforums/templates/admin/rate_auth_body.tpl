<!-- $Id: rate_auth_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
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
	  <th class="thHead" colspan="2">{L_AUTH_DESCRIPTION}</th>
	</tr>
<!-- BEGIN descrow -->
	<tr>
	<td class="{CLASS_1}"><span class="genmed">{descrow.L_AUTH_TYPE}</span></td>
	<td class="{CLASS_2}"><span class="genmed">{descrow.L_AUTH_DESC}</span></td>
	</tr>
<!-- END descrow -->
</table>
<br />
<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_OPTIONS}</th>
	</tr>
<!-- BEGIN optionrow -->
	<tr>
	<td class="{CLASS_1}"><span class="genmed">{optionrow.L_OPT_TYPE}&nbsp;&nbsp;{optionrow.S_OPT_PART}</span></td>
	<td class="{CLASS_2}"><span class="genmed">{optionrow.L_OPT_DESC}</span></td>
	</tr>
<!-- END optionrow -->
	<tr>
		<td class="catBottom" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
<br />
<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
	  <th colspan="3" class="thHead">{L_PAGE_NAME}</th>
	</tr>
	<tr>
		<td class="catBottom" colspan="1" align="center"><span class="gen">{L_FORUM}</span></td>
		<td class="catBottom" colspan="1" align="center"><span class="gen">{L_PERMISSIONS}</span></td>
	</tr>
	<!-- BEGIN forums_row -->
	<tr>
	  <td class="{CLASS_1}"><span class="genmed">{forums_row.FORUM_NAME}</span></td>
	  <td class="{CLASS_2}" align="center"><span class="genmed">{forums_row.S_FORUM_AUTH}&nbsp;&nbsp;</span>
	  </td>
	</tr>
	<!-- END forums_row -->
	<tr>
		<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>

</form>