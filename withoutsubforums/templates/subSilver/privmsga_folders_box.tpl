<!-- $Id: privmsga_folders_box.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<!-- BEGIN folders_box -->
<table cellpadding="2" cellspacing="1" border="0" class="forumline" width="200">
<tr>
    <th>{L_FOLDERS}</th>
</tr>
<tr>
    <td class="row3" align="center"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="bodyline"><tr>
    <td align="center">
        <!-- BEGIN main -->
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="bodyline">
        <tr>
            <td class="{folders_box.main.COLOR}" align="left" width="100%">
                <table cellpadding="2" cellspacing = "0" border="0" width="100%">
                <tr>
                    <td width="100%">
                        <!-- BEGIN no_manage -->
                        <img src="{folders_box.main.IMG_FOLDER}" border="0" hspace="2" title="{folders_box.main.COUNT_UNREAD}" />
                        <!-- END no_manage -->
                        <a href="{folders_box.main.U_FOLDER}" class="genmed">{folders_box.main.L_FOLDER}</a>
                    </td>
                    <!-- BEGIN no_manage -->
                    <td nowrap="nowrap"><span class="gensmall">{folders_box.main.COUNT}</span></td>
                    <!-- END no_manage -->
                </tr>
                </table>
                <!-- BEGIN sub_exist -->
                <hr class="gensmall" />
                <!-- END sub_exist -->
                <table cellpadding="2" cellspacing = "0" border="0" width="100%">
                <!-- BEGIN sub -->
                <tr>
                    <td class="row1"><span class="gensmall">&raquo;</span></td>
                    <td class="row1" width="100%">
                        <!-- BEGIN no_manage -->
                        <img src="{folders_box.main.sub.IMG_FOLDER}" title="{folders_box.main.sub.COUNT_UNREAD}" border="0" hspace="2" />
                        <!-- END no_manage -->
                        <a href="{folders_box.main.sub.U_FOLDER}" class="genmed">{folders_box.main.sub.L_FOLDER}</a>
                    </td>
                    <!-- BEGIN no_manage -->
                    <td class="row1" nowrap="nowrap"><span class="gensmall">{folders_box.main.sub.COUNT}</span></td>
                    <!-- END no_manage -->
                    <!-- BEGIN manage -->
                    <td nowrap="nowrap" align="center"><a href="{folders_box.main.sub.U_EDIT}" title="{L_EDIT}" class="gensmall">{L_EDIT}</a><br /><a href="{folders_box.main.sub.U_DELETE}" class="gensmall">{L_DELETE}</a></td>
                    <td nowrap="nowrap" align="center"><a href="{folders_box.main.sub.U_MOVEUP}" title="{L_MOVEUP}" class="gensmall"><img src="{IMG_MOVEUP}" border="0" vspace="1" alt="{L_MOVEUP}" title="{L_MOVEUP}" /></a><br /><a href="{folders_box.main.sub.U_MOVEDOWN}" title="{L_MOVEDOWN}" class="gensmall"><img src="{IMG_MOVEDOWN}" border="0" vspace="1" alt="{L_MOVEDOWN}" title="{L_MOVEDOWN}" /></a></td>
                    <!-- END manage -->
                </tr>
                <!-- END sub -->
                </table>
            </td>
        </tr>
        </table>
        <!-- END main -->
    </td>
    </tr></table>
</tr>
<tr>
    <!-- BEGIN switch_size_notice -->
    <td class="cat" valign="middle" align="center">
    <!-- END switch_size_notice -->
    <!-- BEGIN switch_size_notice_no -->
    <td class="catBottom" valign="middle" align="center">
    <!-- END switch_size_notice_no -->
    <!-- BEGIN switch_manage -->
    <td class="catBottom" valign="middle" align="center">
    <!-- END switch_manage -->
        <!-- BEGIN switch_search -->
        <a href="{folders_box.U_SEARCH}" alt="{folders_box.L_SEARCH}" title="{folders_box.L_SEARCH}"><img src="{folders_box.IMG_SEARCH}" border="0" alt="{folders_box.L_SEARCH}" title="{folders_box.L_SEARCH}" /></a>&nbsp;
        <a href="{folders_box.U_EDIT}" alt="{folders_box.L_EDIT}" title="{folders_box.L_EDIT}"><img src="{folders_box.IMG_EDIT}" border="0" alt="{folders_box.L_EDIT}" title="{folders_box.L_EDIT}" /></a>
        <!-- END switch_search -->
        <!-- BEGIN switch_manage -->
        <a href="{U_ADD_FOLDER}" alt="{L_ADD_FOLDER}" title="{L_ADD_FOLDER}"><img src="{IMG_ADD_FOLDER}" border="0" alt="{L_ADD_FOLDER}" title="{L_ADD_FOLDER}" /></a>
        <!-- END switch_manage -->
    </td>
</tr>
<!-- BEGIN switch_size_notice -->
<tr>
    <td class="row3"><table width="100%" cellpadding="0" cellspacing="1" border="0" class="bodyline"><tr>
    <td align="center">
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="bodyline">
        <tr>
            <td class="row1" nowrap="nowrap" align="center">
                <table cellspacing="0" cellpadding="1" border="0" width="100%">
                <tr><td align="center"><span class="gensmall">{BOX_STATUS}</span></td></tr>
                <tr>
                    <td align="center">
                        <table cellspacing="0" cellpadding="0" border="1" class="bodyline" width="100%">
                        <tr>
                            <td class="row2">
                                <table cellspacing="0" cellpadding="1" border="1" width="{BOX_PERCENT}%">
                                <tr>
                                    <!-- BEGIN switch_not_empty -->
                                    <td class="bodyline"><img src="{SPACER}" height="8" alt="{BOX_PERCENT}" /></td>
                                    <!-- END switch_not_empty -->
                                    <!-- BEGIN switch_empty -->
                                    <td class="bodyline"><img src="{SPACER}" height="8" width="1" alt="{BOX_PERCENT}" /></td>
                                    <!-- END switch_empty -->
                                </tr>
                                </table>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        </table>
    </td>
    </tr></table>
</tr>
<!-- END switch_size_notice -->
</table>
<!-- END folders_box -->