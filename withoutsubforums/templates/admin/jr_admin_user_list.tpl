<!-- $Id: jr_admin_user_list.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<!-- BEGIN statusrow -->
<table width="100%" cellspacing="2" cellpadding="2" border="1" align="center">
	<tr>
		<td align="center"><span class="gen">{L_STATUS}<br /></span><span class="genmed"><b>{I_STATUS_MESSAGE}</b></span></td>
	</tr>
</table>
<br />
<!-- END statusrow -->

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left"><span class="maintitle">{L_PAGE_NAME}</span><br /><span class="genmed">{L_PAGE_DESC}</span></td>
	</tr>
</table>
<br />

<form action="{S_ACTION}" name="user_list_form" method="post">
<table border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			{LETTER_HEADING}
		</td>
	</tr>
</table>
<br />

<table  border="0" cellpadding="3" cellspacing="1" width="96%" class="forumline" align="center">
	<tr>
		<td class="row3" align="center"><a href="{S_USERNAME}" class="cattitle">{L_USERNAME}</a>&nbsp;{IMG_USERNAME}</td>
		<td class="row3" align="center"><a href="{S_RANK}" class="cattitle">{L_RANK}</a>&nbsp;{IMG_RANK}</td>
		<td class="row3" align="center"><a href="{S_ACTIVE}" class="cattitle">{L_ACTIVE}</a>&nbsp;{IMG_ACTIVE}</td>
		<td class="row2" align="center">&nbsp;</td>
	</tr>
	<!-- BEGIN userrow -->
	<tr>
		<td class="{userrow.ROW_CLASS}"><span class="gensmall">{userrow.BOOKMARK}{userrow.NAME}{userrow.BOOKMARK_END}</span>&nbsp;&nbsp;<span class="gensmall">{userrow.MODULE_COUNT}</span></td>
		<td class="{userrow.ROW_CLASS}" align="center">{userrow.RANK_LIST}</td>
		<td class="{userrow.ROW_CLASS}" align="center"><input type="checkbox" name="active_user_{userrow.ID}" {userrow.ACTIVE}></td>
		<td class="{userrow.ROW_CLASS}" align="center"><input type="submit" name="edit_user_{userrow.ID}" value="{L_EDIT_LIST}" class="liteoption"></td>
	</tr>
	<!-- END userrow -->
	<tr>
		<td class="catBottom" colspan="4" align="center"><input type="hidden" name="mode" value="{S_MODE}">&nbsp;</td>
	</tr>
</table>
</form>