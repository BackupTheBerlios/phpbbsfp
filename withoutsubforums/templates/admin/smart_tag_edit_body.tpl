<!-- $Id: smart_tag_edit_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_SMART_TITLE}</div>
<br />
<div class="genmed">{L_SMART_TEXT}</div>
<br />
<form method="post" action="{S_SMART_ACTION}">
<table cellspacing="1" cellpadding="3" border="0" align="center" class="forumline">
    <tr>
        <th colspan="2">{L_SMART_EDIT}</th>
    </tr>
    <tr>
        <td align="right" class="row1">{L_SMART}:</td>
        <td class="row2"><input type="text" name="smart" value="{SMART}" class="post" maxlength="80" /></td>
    </tr>
    <tr>
        <td align="right" class="row1">{L_URL}:</td>
        <td class="row2"><input type="text" name="url" value="{URL}" class="post" maxlength="255"/></td>
    </tr>
    <tr>
        <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td>
    </tr>
</table>
</form>