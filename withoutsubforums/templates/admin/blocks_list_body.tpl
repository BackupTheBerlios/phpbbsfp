<!-- $Id: blocks_list_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_BLOCKS_TITLE}</div>
<br />
<div class="genmed">{L_BLOCKS_TEXT}</div>
<br />
{L_B_LAYOUT}: [ <b>{LAYOUT_NAME}</b> ]&nbsp;&nbsp;{L_B_PAGE}: [ <b>{PAGE}</b> ]
<br />
<br />
<form method="post" action="{S_BLOCKS_ACTION}">
<table cellspacing="1" cellpadding="3" border="0" align="center" width="100%" class="forumline">
    <tr>
        <th>{L_B_TITLE}</th>
        <th>{L_B_POSITION}</th>
        <th>{L_B_ACTIVE}</th>
        <th>{L_B_DISPLAY}</th>
        <th>{L_B_TYPE}</th>
        <th>{L_B_VIEW_BY}</th>
        <th>{L_B_BORDER}</th>
        <th>{L_B_TITLEBAR}</th>
        <th>{L_B_LOCAL}</th>
        <th>{L_B_BACKGROUND}</th>
        <th>{L_B_GROUPS}</th>
        <th colspan="4">{L_ACTION}</th>
    </tr>
    <!-- BEGIN blocks -->
    <tr>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.TITLE}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.POSITION}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.ACTIVE}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.CONTENT}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.TYPE}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.VIEW}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.BORDER}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.TITLEBAR}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.LOCAL}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.BACKGROUND}</td>
        <td class="{blocks.ROW_CLASS}" align="center">{blocks.GROUPS}</td>
        <td class="{blocks.ROW_CLASS}" align="center"><a href="{blocks.U_MOVE_UP}">{L_MOVE_UP}</a></td>
        <td class="{blocks.ROW_CLASS}" align="center"><a href="{blocks.U_MOVE_DOWN}">{L_MOVE_DOWN}</a></td>
        <td class="{blocks.ROW_CLASS}" align="center"><a href="{blocks.U_EDIT}">{L_EDIT}</a></td>
        <td class="{blocks.ROW_CLASS}" align="center"><a href="{blocks.U_DELETE}">{L_DELETE}</a></td>
    </tr>
    <!-- END blocks -->
    <tr>
        <td colspan="17" align="center" class="cat">{S_HIDDEN_FIELDS}
            <input type="submit" name="add" value="{L_BLOCKS_ADD}" class="mainoption" />
        </td>
    </tr>
</table>
</form>
<br />