<!-- $Id: privmsga_folders_edit_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<form name="privmsg" method="post" action="{U_ACTION}">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
    <td valign="top">{FOLDERS_BOX}</td>
    <td width="5"><span class="gensmall">&nbsp;</span></td>
    <td valign="top" width="100%">
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
        <tr>
            <th colspan="2">{L_TITLE}</th>
        </tr>
        <tr>
            <td class="row1" width="40%"><span class="gen">{L_NAME}</span><span class="gensmall"><br />{L_NAME_EXPLAIN}</span></td>
            <td class="row2" width="60%"><span class="gen">&nbsp;<input type="text" class="post" name="folder_name" value="{FOLDER_NAME}" size="45"/></span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_MAIN}</span><span class="gensmall"><br />{L_MAIN_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<select name="folder_main">{S_FOLDERS}</select></span></td>
        </td>
        <tr>
            <td class="catBottom" colspan="2" align="center" valign="middle">
                <span class="gen">
                    <input type="submit" name="submit_folder" value="{L_SUBMIT}" class="mainoption" />
                    <input type="submit" name="delete_folder" value="{L_DELETE}" class="liteoption" />
                    <input type="submit" name="cancel_folder" value="{L_CANCEL}" class="liteoption" />
                </span>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}