<!-- $Id: layout_edit_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<div class="maintitle">{L_LAYOUT_TITLE}</div>
<br />
<div class="genmed">{L_LAYOUT_TEXT}</div>
<br />
<form method="post" action="{S_LAYOUT_ACTION}">
<table cellspacing="1" cellpadding="3" border="0" align="center" class="forumline">
    <tr>
        <th colspan="2">{L_EDIT_LAYOUT}</th>
    </tr>

    <tr>
        <td align="right" class="row1">{L_LAYOUT_NAME}:</td>
        <td class="row2"><select name="name" class="post">{NAME_SELECT}</select></td>
    </tr>

    <tr>
        <td align="right" class="row1">{L_LAYOUT_VIEW}:</td>
        <td class="row2">
            <select name="view" class="post">{VIEW}</select>
        </td>
    </tr>

    <tr>
        <td align="right" class="row1">{L_LAYOUT_GROUPS}:</td>
        <td class="row2">
            {GROUPS}
        </td>
    </tr>

    <tr>
        <td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td>
    </tr>
</table>
</form>
<br />