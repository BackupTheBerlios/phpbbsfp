<!-- $Id: layout_list_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_LAYOUT_TITLE}</div>
<br />
<div class="genmed">{L_LAYOUT_TEXT}</div>
<br />
<form method="post" action="{S_LAYOUT_ACTION}">
<table width="100%" cellspacing="1" cellpadding="3" border="0" align="center" class="forumline">
    <tr>
        <th>{L_LAYOUT_NAME}</th>
        <th>{L_LAYOUT_PAGE}</th>
        <th>{L_LAYOUT_VIEW}</th>
		<th>{L_LAYOUT_GROUPS}</th>
        <th colspan="3">{L_ACTION}</th>
    </tr>
    <!-- BEGIN layout -->
    <tr>
        <td class="{layout.ROW_CLASS}" align="center">{layout.NAME}</td>
        <td class="{layout.ROW_CLASS}" align="center">{layout.PAGE}</td>
        <td class="{layout.ROW_CLASS}" align="center">{layout.VIEW}</td>
		<td class="{layout.ROW_CLASS}" align="center">{layout.GROUPS}</td>
        <td class="{layout.ROW_CLASS}" align="center">&nbsp;<a href="{layout.U_LAYOUT_EDIT}">{L_EDIT}</a>&nbsp;</td>
        <td class="{layout.ROW_CLASS}" align="center">&nbsp;{layout.L_LAYOUT_DELETE}&nbsp;</td>
        <td class="{layout.ROW_CLASS}" align="center">&nbsp;{layout.L_DEFAULT}</a>&nbsp;</td>
    </tr>
    <!-- END layout -->
    <tr>
        <td colspan="7" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_LAYOUT_ADD}" class="mainoption" /></td>
    </tr>
</table>
</form>
<br />