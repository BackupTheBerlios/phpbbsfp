<!-- $Id: posting_preview.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
    <tr>
        <th class="thHead">{L_PREVIEW}</th>
    </tr>
    <tr>
        <td class="row1"><img src="{TEMPLATE}images/icon_minipost.gif" alt="{L_POST}" /><span class="postdetails">{L_POSTED}: {POST_DATE} &nbsp;&nbsp;&nbsp; {L_POST_SUBJECT}: {POST_SUBJECT}</span></td>
    </tr>
    <tr>
        <td class="row1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span class="postbody">{MESSAGE}</span>
                    <!-- BEGIN postrow -->
                    {ATTACHMENTS}
                    <!-- END postrow -->
                </td>
            </tr>
        </table></td>
    </tr>
    <tr>
        <td class="spaceRow" height="1"><img src="{TEMPLATE}images/spacer.gif" width="1" height="1" /></td>
    </tr>
</table>

<br clear="all" />
