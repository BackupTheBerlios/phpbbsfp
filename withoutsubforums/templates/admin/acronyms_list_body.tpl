<!-- $Id: acronyms_list_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_ACRONYMS_TITLE}</div>
<br />
<div class="genmed">{L_ACRONYMS_TEXT}</div>
<br />
<form method="post" action="{S_ACRONYMS_ACTION}">
<table cellspacing="1" cellpadding="3" border="0" align="center" class="forumline">
    <tr>
        <th>{L_ACRONYM}</th>
        <th>{L_DESCRIPTION}</th>
        <th colspan="2">{L_ACTION}</th>
    </tr>
    <!-- BEGIN acronyms -->
    <tr>
        <td class="{acronyms.ROW_CLASS}">{acronyms.ACRONYM}</td>
        <td class="{acronyms.ROW_CLASS}">{acronyms.DESCRIPTION}</td>
        <td class="{acronyms.ROW_CLASS}"><a href="{acronyms.U_ACRONYM_EDIT}">{L_EDIT}</a></td>
        <td class="{acronyms.ROW_CLASS}"><a href="{acronyms.U_ACRONYM_DELETE}">{L_DELETE}</a></td>
    </tr>
    <!-- END acronyms -->
    <tr>
        <td colspan="5" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_ADD_ACRONYM}" class="mainoption" /></td>
    </tr>
</table>
</form>