<!-- $Id: index_box.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<!-- BEGIN catrow -->
<!-- BEGIN tablehead -->
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
<tr>
    <th colspan="{catrow.tablehead.INC_SPAN}" width="100%" nowrap="nowrap">&nbsp;{catrow.tablehead.L_FORUM}&nbsp;</th>
    <th width="50" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
    <th width="50" nowrap="nowrap">&nbsp;{L_POSTS}&nbsp;</th>
    <th width="150" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
</tr>
<!-- END tablehead -->
<!-- BEGIN cathead -->
<tr>
    <!-- BEGIN inc -->
    <td width="46" class="{catrow.cathead.inc.INC_CLASS}"><img src="{SPACER}" width="46" height="0" /></td>
    <!-- END inc -->
    <td class="{catrow.cathead.CLASS_CAT}" width="100%" colspan="{catrow.cathead.INC_SPAN}"><span class="cattitle"><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle" title="{catrow.cathead.CAT_DESC}">{catrow.cathead.CAT_TITLE}</a></span></td>
    <td class="{catrow.cathead.CLASS_ROWPIC}" colspan="3" align="right">&nbsp;</td>
</tr>
<!-- END cathead -->
<!-- BEGIN forumrow -->
<tr>
    <!-- BEGIN inc -->
    <td width="46" class="{catrow.forumrow.inc.INC_CLASS}"><img src="{SPACER}" width="46" height="0" /></td>
    <!-- END inc -->
    <td class="{catrow.forumrow.INC_CLASS}" align="center" valign="middle" height="50"><img src="{catrow.forumrow.FORUM_FOLDER_IMG}" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}" /></td>
    <td class="row1" width="100%" height="50" colspan="{catrow.forumrow.INC_SPAN}" valign="top">
        <!-- BEGIN forum_icon -->
        <table cellpadding="2" cellspacing="0" border="0" width="100%" height="47">
        <tr>
            <td width="46" align="center"><a href="{catrow.forumrow.U_VIEWFORUM}" {catrow.forumrow.U_TARGET}><img src="{catrow.forumrow.ICON_IMG}" border="0" /></a></td>
            <td>
        <!-- END forum_icon -->
        <span class="forumlink"><a href="{catrow.forumrow.U_VIEWFORUM}" {catrow.forumrow.U_TARGET} class="forumlink">{catrow.forumrow.FORUM_NAME}</a><br /></span>
        <span class="genmed">{catrow.forumrow.FORUM_DESC}</span>
        <span class="gensmall">{catrow.forumrow.L_MODERATOR}{catrow.forumrow.MODERATORS}{catrow.forumrow.L_LINKS}{catrow.forumrow.LINKS}</span>
        <!-- BEGIN forum_icon -->
            </td>
        </tr>
        </table>
        <!-- END forum_icon -->
    </td>
    <!-- BEGIN forum_link_no -->
    <td class="row3" align="center" valign="middle" height="50"><span class="gensmall">{catrow.forumrow.TOPICS}</span></td>
    <td class="row2" align="center" valign="middle" height="50"><span class="gensmall">{catrow.forumrow.POSTS}</span></td>
    <td class="row3" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{catrow.forumrow.LAST_POST}</span></td>
    <!-- END forum_link_no -->
    <!-- BEGIN forum_link -->
    <td class="row3" align="center" valign="middle" height="50" colspan="3"><span class="gensmall">{catrow.forumrow.forum_link.HIT_COUNT}</span></td>
    <!-- END forum_link -->
</tr>
<!-- END forumrow -->
<!-- BEGIN catfoot -->
<tr>
    <!-- BEGIN inc -->
    <td width="46" class="{catrow.catfoot.inc.INC_CLASS}"><img src="{SPACER}" width="46" height="0" /></td>
    <!-- END inc -->
    <td colspan="{catrow.catfoot.INC_SPAN}" height="1" class="spaceRow"><img src="{SPACER}" alt="" width="1" height="1" /></td>
</tr>
<!-- END catfoot -->
<!-- BEGIN tablefoot -->
</table>
<br class="gensmall" />
<!-- END tablefoot -->
<!-- END catrow -->