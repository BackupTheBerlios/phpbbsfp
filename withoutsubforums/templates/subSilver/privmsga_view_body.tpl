<!-- $Id: privmsga_view_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<form action="{S_ACTION}" method="post" name="post">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
    <td valign="top">{FOLDERS_BOX}</td>
    <td width="5"><span class="gensmall">&nbsp;</span></td>
    <td valign="top" width="100%">
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
        <tr>
            <th width="100%" colspan="2">{BOX_NAME}</th>
        </tr>
        <tr>
            <td class="row2" nowrap="nowrap" width="150"><span class="gen">{L_FROM}:&nbsp;</span></td>
            <td class="row1" width="100%"><span class="name">{MESSAGE_FROM}</span></td>
        </tr>
        <tr>
            <td class="row2" nowrap="nowrap"><span class="gen">{L_TO}:&nbsp;</span></td>
            <td class="row1"><span class="name">{MESSAGE_TO}</span></td>
        </tr>
        <tr>
            <td class="cat" align="center" colspan="2"><span class="cattitle">{L_MESSAGE}</span></td>
        </tr>
        <tr>
            <td class="row1" height="28" colspan="2">
                <table cellpadding="2" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="100%"><span class="gen"><b>{L_SUBJECT}:&nbsp;</b>{POST_SUBJECT}</span>
                    <td nowrap="nowrap"><span class="postdetails"><b>{L_POSTED}:&nbsp;</b>{POST_DATE}</span>
                </tr>
                <tr>
                    <td colspan="2"><span class="postbody"><hr />{MESSAGE}{SIGNATURE}</span></td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="row1" nowrap="nowrap" valign="bottom" height="28" colspan="2">
                <table cellspacing="0" cellpadding="0" border="0" height="18" width="100%">
                <tr>
                    <!-- BEGIN switch_not_in_pcp -->
                    <td valign="middle" nowrap="nowrap">{PROFILE_IMG} {PM_IMG} {EMAIL_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG}</td>
                    <td>&nbsp;</td>
                    <td valign="top" nowrap="nowrap"><script language="JavaScript" type="text/javascript"><!--
                if ( navigator.userAgent.toLowerCase().indexOf('mozilla') != -1 && navigator.userAgent.indexOf('5.') == -1 && navigator.userAgent.indexOf('6.') == -1 )
                    document.write('{ICQ_IMG}');
                else
                    document.write('<div style="position:relative"><div style="position:absolute">{ICQ_IMG}</div><div style="position:absolute;left:3px">{ICQ_STATUS_IMG}</div></div>');
                //--></script><noscript>{ICQ_IMG}</noscript>
                    </td>
                    <!-- END switch_not_in_pcp -->
                    <!-- BEGIN switch_in_pcp -->
                    <td valign="middle" nowrap="nowrap">{BUTTONS_PANEL}</td>
                    <!-- END switch_in_pcp -->
                    <td width="100%">&nbsp;</td>
                    <td valign="middle" nowrap="nowrap">{FORWARD_PM_IMG} {QUOTE_PM_IMG} {EDIT_PM_IMG} {DELETE_PM_IMG} {SAVEMAIL_PM_IMG}</td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="catBottom" colspan="2" align="center">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td align="left" width="50%"><span class="genmed">&nbsp;</span></td>
                    <td align="center"><span class="genmed"><input type="submit" name="return_main" value="{L_CANCEL}" class="mainoption" /></span></td></td>
                    <td align="rigth" width="50%"><table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="right" nowrap="nowrap"><span class="genmed" align="right">&nbsp;<input type="submit" name="move" value="{L_MOVE_MSG}" class="liteoption" /><select name="to_folder">{S_MOVE_FOLDER}</select></span></td></tr></table></td>
                </tr>
                </table>
            </td>
        </tr>
        </table>

        <table cellpadding="2" cellspacing="0" border="0" width="100%">
            <tr>
                <td valign="bottom" width="100%">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <!-- BEGIN switch_new_post -->
                                <a href="{U_POST_NEW_PM}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_PM}" title="{L_POST_NEW_PM}" /></a>&nbsp;
                                <!-- END switch_new_post -->
                                <!-- BEGIN switch_reply -->
                                <a href="{U_REPLY_PM}"><img src="{REPLY_IMG}" border="0" alt="{L_REPLY_PM}" title="{L_REPLY_PM}" /></a>&nbsp;
                                <!-- END switch_reply -->
                            </td>
                            <td>
                                <!-- BEGIN switch_nav_sentence -->
                                <span class="nav">
                                <a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_SEPARATOR}<a href="{U_FOLDER}" class="nav">{L_FOLDER}</a>
                                <!-- BEGIN switch_subfolder -->
                                {NAV_SEPARATOR}<a href="{U_SUBFOLDER}" class="nav">{L_SUBFOLDER}</a>
                                <!-- END switch_subfolder -->
                                </span>
                                <!-- END switch_nav_sentence -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </td>
</tr>
</table>




{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}