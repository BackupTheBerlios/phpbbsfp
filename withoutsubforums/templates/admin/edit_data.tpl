<!-- $Id: edit_data.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_XS_EDIT_STYLES_DATA}</h1>

<p>{L_XS_EDITDATA_EXPLAIN}</p>

<form action="{U_ACTION}" method="post">{S_HIDDEN_FIELDS}<input type="hidden" name="edit" value="{ID}" />
<table cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
<tr>
	<th class="thHead" colspan="3">{L_XS_EDIT_STYLES_DATA}</th>
</tr>
<tr>
		<td class="catLeft" align="center"><span class="gen">{L_XS_EDITDATA_VAR}</span></td>
	<td class="cat" align="center"><span class="gen">{L_XS_EDITDATA_VALUE}</span></td>
	<td class="catRight" align="center"><span class="gen">{L_XS_EDITDATA_COMMENT}</span></td>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.ROW_CLASS}" align="left" nowrap="nowrap"><span class="gen">{row.TEXT}:</span></td>
	<td class="{row.ROW_CLASS}" align="left"><input type="text" class="post" name="edit_{row.VAR}" maxlength="{row.LEN}" size="{row.SIZE}" value="{row.VALUE}" /></td>
	<!-- BEGIN name -->
	<td class="{row.ROW_CLASS}" align="left"><input type="text" class="post" name="name_{row.VAR}" maxlength="50" value="{row.name.DATA}" size="50" title="{row.name.DATA}" /></td>
	<!-- END name -->
	<!-- BEGIN noname -->
	<td class="{row.ROW_CLASS}"><span class="gen">&nbsp;</span></td>
	<!-- END noname -->
</tr>
<!-- END row -->
<tr>
	<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /> <input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
</form>
<br />