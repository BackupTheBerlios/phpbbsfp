<!-- $Id: confirm_body.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr>
        <td class="nav" align="left"><a class="nav" href="{U_INDEX}">{L_INDEX}</a></td>
    </tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
    <tr>
        <th class="thHead" valign="middle"><span class="tableTitle">{MESSAGE_TITLE}</span></th>
    </tr>
    <tr>
        <td class="row1" align="center"><form action="{S_CONFIRM_ACTION}" method="post"><span class="gen"><br />{MESSAGE_TEXT}<br /><br />{S_HIDDEN_FIELDS}<input type="submit" name="confirm" value="{L_YES}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancel" value="{L_NO}" class="liteoption" /></span></form></td>
    </tr>
</table>

<br clear="all" />
