<!-- $Id: privmsga_box.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
<tr>
    <th width="5%" nowrap="nowrap">{L_FLAG}</th>
    <th nowrap="nowrap" colspan="{SPAN_SUBJECT}">{L_SUBJECT}</th>
    <th width="15%" nowrap="nowrap">{L_FROM_OR_TO}</th>
    <th width="15%" nowrap="nowrap">{L_DATE}</th>
    <!-- BEGIN privmsg_select -->
    <th width="5%" nowrap="nowrap">&nbsp;<input type="checkbox" name="all_mark" value="{L_SELECT}" {CHECKED} onClick="check_uncheck_all();">&nbsp;</th>
    <!-- END privmsg_select -->
</tr>
<!-- BEGIN pm_row -->
<tr>
    <td class="{pm_row.COLOR}" align="center"><img src="{pm_row.FOLDER_IMG}" border="0" alt="{pm_row.L_FOLDER_ALT}" title="{pm_row.L_FOLDER_ALT}" /></td>
    <!-- BEGIN switch_icon -->
    <td class="{pm_row.COLOR}" align="center" width="1%">{pm_row.ICON}</td>
    <!-- END switch_icon -->
    <td class="{pm_row.COLOR}">
        <span class="topictitle"><a href="{pm_row.U_SUBJECT}" class="topictitle">{pm_row.SUBJECT}</a></span>
        <!-- BEGIN detailed -->
        <span class="gensmall">
            <!-- BEGIN sub -->
            <br />[&nbsp;<a href="{pm_row.U_FOLDER}" alt="{pm_row.L_FOLDER}" title="{pm_row.L_FOLDER}">{pm_row.L_FOLDER}</a>{NAV_SEPARATOR}<a href="{pm_row.U_SUBFOLDER}" alt="{pm_row.L_SUBFOLDER}" title="{pm_row.L_SUBFOLDER}">{pm_row.L_SUBFOLDER}</a>&nbsp;]
            <!-- END sub -->
            <!-- BEGIN no_sub -->
            <br />[&nbsp;<a href="{pm_row.U_FOLDER}" alt="{pm_row.L_FOLDER}" title="{pm_row.L_FOLDER}">{pm_row.L_FOLDER}</a>&nbsp;]
            <!-- END no_sub -->
        </span>
        <!-- END detailed -->
    </td>
    <td class="{pm_row.COLOR}" align="center" nowrap="nowrap"><span class="name">{pm_row.S_USERNAME}</span></td>
    <td class="{pm_row.COLOR}" align="center" nowrap="nowrap"><span class="postdetails">{pm_row.DATE}</span></td>
    <!-- BEGIN privmsg_select -->
    <td class="{pm_row.COLOR}" align="center" nowrap="nowrap"><span class="postdetails"><input type="checkbox" name="mark_ids[]" value="{pm_row.S_MARK_ID}" {pm_row.CHECKED} onClick="javascript:check_uncheck_main();" /></span></td>
    <!-- END privmsg_select -->
</tr>
<!-- END pm_row -->
<!-- BEGIN pm_empty -->
<tr>
    <td class="row1" colspan="{SPAN_ALL}" height="30" align="center"><span class="gen">{L_NO_MESSAGES}</span></td>
</tr>
<!-- END pm_empty -->
<!-- BEGIN privmsg_select -->
<tr>
    <td width="100%" nowrap="nowrap" align="right" colspan="{SPAN_ALL}" class="row3">
        <span class="genmed">
            &nbsp;
            <!-- BEGIN switch_delete -->
            <input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />
            <!-- END switch_delete -->
            <!-- BEGIN switch_savetomail -->
            <input type="submit" name="savemails" value="{L_SAVE_TO_MAIL}" class="liteoption" />
            <!-- END switch_savetomail -->
            <!-- BEGIN switch_move -->
            <input type="submit" name="move" value="{L_MOVE_MARKED}" class="mainoption" /><select name="to_folder">{S_SELECT_MOVE}</select>
            <!-- END switch_move -->
        </span>
    </td>
</tr>
<!-- END privmsg_select -->
<tr>
    <td class="catBottom" colspan="{SPAN_ALL}" align="center">
        <span class="genmed">
            <!-- BEGIN switch_cancel -->
            <input type="submit" name="cancel" value="{L_CANCEL}" class="mainoption" />
            <!-- END switch_cancel -->
            <!-- BEGIN privmsg_no_select -->
            &nbsp;
            <!-- END privmsg_no_select -->
            <!-- BEGIN privmsg_select -->
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr valign="middle">
                <td align="center">
                    <span class="gensmall">
                        {L_DISPLAY_MESSAGES}:&nbsp;
                        <select name="msgdays" onChange="this.form.submit();">{S_SELECT_MSG_DAYS}</select>
                        <input type="submit" value="{L_GO}" name="submit_msgdays" class="liteoption" />
                    </span>
                </td>
            </tr>
            </table>
            <!-- END privmsg_select -->
        </span>
    </td>
</tr>
</table>