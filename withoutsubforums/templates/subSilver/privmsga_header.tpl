<!-- $Id: privmsga_header.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<!-- BEGIN switch_in_pcp -->
<table cellpadding="0" cellspacing="10" border="0" width="100%">
    <tr>
        <td>
<!-- END switch_in_pcp -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td align="center" width="100%">
            <table cellpadding="4" cellspacing="2" border="0">
            <tr>
                    <!-- BEGIN folder_row -->
                    <td><span class="nav"><a href="{folder_row.U_FOLDER}"><img src="{folder_row.FOLDER_IMG}" border="0" alt="{folder_row.L_FOLDER}" title="{folder_row.L_FOLDER}" /></a></span></td>
                    <td><span class="nav"><a href="{folder_row.U_FOLDER}" title="{folder_row.L_FOLDER}" class="nav">{folder_row.L_FOLDER}</a></span></td>
                    <!-- END folder_row -->
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- BEGIN switch_not_in_pcp -->
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
<!-- END switch_not_in_pcp -->