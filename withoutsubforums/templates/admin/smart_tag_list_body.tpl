<!-- $Id: smart_tag_list_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_SMART_TITLE}</div>
<br />
<div class="genmed">{L_SMART_TEXT}</div>
<br />
<form method="post" action="{S_SMART_ACTION}">
<table cellspacing="1" cellpadding="3" border="0" align="center" class="forumline">
    <tr>
        <th>{L_SMART}</th>
        <th>{L_URL}</th>
        <th colspan="2">{L_ACTION}</th>
    </tr>
    <!-- BEGIN smart -->
    <tr>
        <td class="{smart.ROW_CLASS}">{smart.SMART}</td>
        <td class="{smart.ROW_CLASS}">{smart.URL}</td>
        <td class="{smart.ROW_CLASS}"><a href="{smart.U_SMART_EDIT}">{L_EDIT}</a></td>
        <td class="{smart.ROW_CLASS}"><a href="{smart.U_SMART_DELETE}">{L_DELETE}</a></td>
    </tr>
    <!-- END smart -->
    <tr>
        <td colspan="5" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_ADD_SMART}" class="mainoption" /></td>
    </tr>
</table>
</form>
<br />
