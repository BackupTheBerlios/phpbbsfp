<!-- $Id: edit_data_list.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_XS_EDIT_STYLES_DATA}</h1>

<p>{L_XS_EDITDATA_EXPLAIN}</p>

<table cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
<tr>
	<th class="thHead" colspan="3">{L_XS_EDIT_STYLES_DATA}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="gen">{L_XS_TEMPLATE}</span></td>
	<td class="cat" align="center"><span class="gen">{L_XS_STYLES}</span></td>
	<td class="catRight" align="center"><span class="gen">&nbsp;</span></td>
</tr>
<!-- BEGIN styles -->
<tr>
	<td class="{styles.ROW_CLASS}" align="left" valign="middle"><span class="gen">{styles.TPL}</span></td>
	<td class="{styles.ROW_CLASS}" align="left" valign="middle"><span class="gen">{styles.STYLE}</span></td>
	<td class="{styles.ROW_CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{styles.U_EDIT}">{L_XS_EDIT_LC}</a></span></td>
</tr>
<!-- END styles -->
</table>
<br />