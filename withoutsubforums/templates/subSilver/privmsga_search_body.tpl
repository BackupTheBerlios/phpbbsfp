<!-- $Id: privmsga_search_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<form action="{S_ACTION}" method="post" name="post">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
    <td valign="top">{FOLDERS_BOX}</td>
    <td width="5"><span class="gensmall">&nbsp;</span></td>
    <td valign="top" width="100%">

        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
        <tr>
            <th width="100%" colspan="2">{L_TITLE}</th>
        </tr>
        <tr>
            <td class="row1" width="40%"><span class="gen">{L_SEARCH_FOLDER}</span><span class="gensmall"><br />{L_SEARCH_FOLDER_EXPLAIN}</span></td>
            <td class="row2" width="60%"><span class="gen">&nbsp;<select name="search_folder">{S_FOLDERS}</select></span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_SEARCH_AUTHOR}</span><span class="gensmall"><br />{L_SEARCH_AUTHOR_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<input type="text" class="post" name="username" maxlength="25" size="25" tabindex="1" value="{USERNAME}" />&nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_SEARCH_WORDS} </span><span class="gensmall"><br />{L_SEARCH_WORDS_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<input type="text" class="post" name="words" size="60" value="{WORDS}" /></span></td>
        </tr>
        <tr>
            <td class="catBottom" colspan="2" align="center">
                <span class="genmed">
                    <input type="submit" class="mainoption" name="submit_search" value="{L_SUBMIT}" />
                    <input type="submit" name="return_main" value="{L_CANCEL}" class="liteoption" />
                </span>
            </td>
        </tr>
        </table>

        <table cellpadding="2" cellspacing="0" border="0" width="100%" align="center">
        <tr>
            <td>
                <!-- BEGIN switch_new_post -->
                <a href="{U_POST_NEW_PM}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_PM}" title="{L_POST_NEW_PM}" /></a>&nbsp;
                <!-- END switch_new_post -->
                <!-- BEGIN switch_reply -->
                <a href="{U_REPLY_PM}"><img src="{REPLY_IMG}" border="0" alt="{L_REPLY_PM}" title="{L_REPLY_PM}" /></a>&nbsp;
                <!-- END switch_reply -->
            </td>
            <td width="100%">
                <span class="nav">
                    <!-- BEGIN switch_nav_sentence -->
                    <a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_SEPARATOR}<a href="{U_FOLDER}" class="nav">{L_FOLDER}</a>
                    <!-- BEGIN switch_subfolder_custom -->
                    {NAV_SEPARATOR}<a href="{U_SUBFOLDER}" class="nav">{L_SUBFOLDER}</a>
                    <!-- END switch_subfolder_custom -->
                    <!-- END switch_nav_sentence -->
                </span>
            </td>
            <td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}<br /></span></td>
        </tr>
        </table>

    </td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}