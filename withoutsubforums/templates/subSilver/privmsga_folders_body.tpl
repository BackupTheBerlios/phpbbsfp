<!-- $Id: privmsga_folders_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<form name="privmsg" method="post" action="{U_ACTION}">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
    <td valign="top">{FOLDERS_BOX}</td>
    <td width="5"><span class="gensmall">&nbsp;</span></td>
    <td valign="top" width="100%">
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
        <tr>
            <th colspan="{SPAN_ALL}">{L_TITLE}</th>
        </tr>
        <!-- BEGIN rules_row -->
        <tr>
            <td class="{rules_row.COLOR}" width="100%" height="25"><span class="forumlink">&nbsp;<a href="{rules_row.U_NAME}" class="forumlink">{rules_row.L_NAME}</a>&nbsp;</span></td>
            <td class="{rules_row.COLOR}" align="center" nowrap="nowrap"><span class="genmed">&nbsp;<a href="{rules_row.U_COPY}" class="genmed">{L_COPY}</a>&nbsp;</span></td>
        </tr>
        <!-- END rules_row -->
        <!-- BEGIN rules_empty -->
        <tr>
            <td class="row1" colspan="{SPAN_ALL}" align="center" height="25"><span class="gen"><i>{L_EMPTY}</i></span></td>
        </tr>
        <!-- END rules_empty -->
        <tr>
            <td class="catBottom" colspan="{SPAN_ALL}" align="center" valign="middle">
                <span class="genmed">
                    <input type="submit" name="add_rules" value="{L_ADD_RULES}" class="mainoption" />
                    <input type="submit" name="return_main" value="{L_CANCEL}" class="liteoption" />
                </span>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}