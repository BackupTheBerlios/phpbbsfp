<!-- $Id: viewtopic_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language="JavaScript" type="text/javascript">
<!--
function ref(object)
{
    if (document.getElementById)
    {
        return document.getElementById(object);
    }
    else if (document.all)
    {
        return eval('document.all.' + object);
    }
    else
    {
        return false;
    }
}

function toggle(pobject)
{
    object = ref('post_' + pobject);

    if( !object.style )
    {
        return false;
    }

    if( object.style.display == 'none' )
    {
        object.style.display = '';
    }
    else
    {
        object.style.display = 'none';
    }
}
//-->
</script>

<script language="Javascript" type="text/javascript">
<!--
    function open_postreview(ref)
    {
        height = screen.height / 3;
        width = screen.width / 2;
        window.open(ref, '_minervapostreview', 'HEIGHT=' + height + ',WIDTH=' + width + ',resizable=yes,scrollbars=yes');
        return;
    }
//-->
</script>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr>
<td align="left" valign="bottom" colspan="2"><a class="maintitle" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a><br />
       <span class="gensmall"><b>{PAGINATION}</b><br /> 	 
       &nbsp; </span></td
	</tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		     <td align="left" valign="bottom" nowrap="nowrap"><span class="nav"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}"   alt="{L_POST_NEW_TOPIC}" align="middle"></a>&nbsp;&nbsp;&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}"   alt="{L_POST_REPLY_TOPIC}" align="middle"></a></span></td>
		<td align="left" valign="middle" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_FORUM}" class="nav">{L_FORUM}</a>       -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
		<td valign="bottom" nowrap="nowrap">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="{U_VIEW_OLDER_TOPIC}"><img src="{VIEW_PREVIOUS_TOPIC_IMG}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" /></a></td>
					<td nowrap="nowrap">{S_EMAIL_TOPIC_IMG}{S_DOWNLOAD_TOPIC_IMG}<a href="{U_PRINTER_TOPIC}" target="_blank"><img src="{PRINTER_TOPIC_IMG}" alt="{L_PRINTER_TOPIC}" title="{L_PRINTER_TOPIC}" /></a>{S_WATCH_TOPIC_IMG}<a href="{U_PRIVATEMSGS}"><img src="{PRIVMSG_IMG}" border="0" alt="{PRIVATE_MESSAGE_INFO}" title="{PRIVATE_MESSAGE_INFO}" /></a>{S_VIEWED_TOPIC_IMG}<a href="#bot"><img src="{NS_PAGE_BOTTOM_IMG}" alt="{L_PAGE_BOTTOM}" title="{L_PAGE_BOTTOM}" /></a></td>
					<td><a href="{U_VIEW_NEWER_TOPIC}"><img src="{VIEW_NEXT_TOPIC_IMG}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" /></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
    <!-- IF POLL_RESULT -->
    <!-- INCLUDE viewtopic_poll_result.tpl -->
    <!-- ELSEIF POLL_BALLOT -->
    <!-- INCLUDE viewtopic_poll_ballot.tpl -->
    <!-- ENDIF -->
    <tr>
        <th class="thLeft" width="150" nowrap="nowrap">{L_AUTHOR}</th>
        <th class="thRight" nowrap="nowrap">{L_MESSAGE}</th>
    </tr>
    <!-- BEGIN postrow -->

    <!-- BEGIN switch_buddy_ignore -->
    <tbody id="post_{postrow.POST_ID}" style="display:none">
    <!-- END switch_buddy_ignore -->

    <tr>
        <td width="150" align="left" valign="top" class="{postrow.ROW_CLASS}"><a name="{postrow.U_POST_ID}"></a>{postrow.AUTHOR_PANEL}<div class="gensmall">{postrow.CASH}</div></td>
        <td class="{postrow.ROW_CLASS}" width="100%" height="100%" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
					<td valign="top" width="100%"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" ></a><span class="postdetails">{L_POSTED}:&nbsp;{postrow.POST_DATE}&nbsp;&nbsp;{L_POST_SUBJECT}:&nbsp;{postrow.POST_SUBJECT}&nbsp;{postrow.S_DOWNLOAD_POST}</span></td>
					<td valign="top" nowrap="nowrap"><form method="post" action="{postrow.S_CARD}"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG_MINI}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}"/></a>{postrow.QUOTE_IMG}{postrow.EDIT_IMG}{postrow.DELETE_IMG}{postrow.U_R_CARD}{postrow.U_Y_CARD}{postrow.U_G_CARD}{postrow.U_B_CARD}{postrow.CARD_EXTRA_SPACE}{postrow.CARD_HIDDEN_FIELDS}</form></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2" height="100%" valign="top"><span class="postbody">{postrow.MESSAGE}</span>
					<!-- IF postrow.S_HAS_ATTACHMENT -->
						{postrow.ATTACHMENTS}
					<!-- ENDIF -->
				</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="postbody">{postrow.SIGNATURE}</span><span class="gensmall">{postrow.EDITED_MESSAGE}</span></td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- BEGIN switch_no_buddy_ignore -->
    <tr>
        <td class="{postrow.ROW_CLASS}" width="150" align="left" valign="middle"><span class="nav"><a href="#top" class="nav">{L_BACK_TO_TOP}</a></span></td>
        <td class="{postrow.ROW_CLASS}" width="100%" valign="bottom" nowrap="nowrap"><table cellspacing="0" cellpadding="0" border="0" height="18" width="18"><tr>{postrow.BUTTONS_PANEL}</tr></table></td>
    </tr>
    <!-- END switch_no_buddy_ignore -->
    <!-- BEGIN switch_buddy_ignore -->
    </tbody>
    <!-- END switch_buddy_ignore -->
    <!-- BEGIN switch_buddy_ignore -->
    <tr>
        <td class="{postrow.ROW_CLASS}" width="150" align="left" valign="middle"><span class="nav"><a href="#top" class="nav">{L_BACK_TO_TOP}</a></span></td>
        <td class="{postrow.ROW_CLASS}" width="100%">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td width="100%"><a href="#" onClick="toggle('{postrow.POST_ID}'); return false;" class="postdetails">{L_IGNORE_CHOOSEN} </a></td>
                	<td align="rigth">{postrow.IGNORE_IMG}</td>
            	</tr>
            </table>
        </td>
    </tr>
    <!-- END switch_buddy_ignore -->

    <tr>
        <td class="spaceRow" colspan="2" height="1"><img src="{TEMPLATE}images/spacer.gif" alt="" width="1" height="1"></td>
    </tr>
    <!-- END postrow -->

    {RATING_VIEWTOPIC}

	<tr>
        <td class="catBottom" colspan="2">
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
            	<tr>
                	<td align="center">
                		<span class="gensmall"><form method="post" action="{S_POST_DAYS_ACTION}">{L_DISPLAY_POSTS}:&nbsp;{S_SELECT_POST_DAYS}&nbsp;{S_SELECT_POST_ORDER}&nbsp;<input type="submit" value="{L_GO}" class="liteoption" name="submit"></span></form>
                	</td>
            	</tr>
        	</table>
        </td>
    </tr>
</table>

{QUICK_REPLY_BODY}

<table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td nowrap="nowrap"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" hspace="8" border="0" title="{L_POST_REPLY_TOPIC}" /></a></td>
		<td align="left" valign="middle" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_FORUM}" class="nav">{L_FORUM}</a>       -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
		<td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}</span></td>
		<td valign="top" nowrap="nowrap">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="{U_VIEW_OLDER_TOPIC}"><img src="{VIEW_PREVIOUS_TOPIC_IMG}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" /></a></td>
					<td nowrap="nowrap">{S_EMAIL_TOPIC_IMG}{S_DOWNLOAD_TOPIC_IMG}<a href="{U_PRINTER_TOPIC}" target="_blank"><img src="{PRINTER_TOPIC_IMG}" alt="{L_PRINTER_TOPIC}" title="{L_PRINTER_TOPIC}" /></a>{S_WATCH_TOPIC_IMG}<a href="{U_PRIVATEMSGS}"><img src="{PRIVMSG_IMG}" alt="{PRIVATE_MESSAGE_INFO}" title="{PRIVATE_MESSAGE_INFO}" /></a>{S_VIEWED_TOPIC_IMG}<a href="#top"><img src="{NS_PAGE_TOP_IMG}" alt="{L_PAGE_TOP}" title="{L_PAGE_TOP}" /></a></td>
					<td><a href="{U_VIEW_NEWER_TOPIC}"><img src="{VIEW_NEXT_TOPIC_IMG}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" /></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td class="nav" valign="top">{PAGINATION}</td>
		<td class="gensmall" align="right" valign="top" rowspan="3">{S_AUTH_LIST}<br /></td>
	</tr>
	<tr>
		<td class="nav" valign="top" colspan="2">{S_TOPIC_ADMIN}</td>
	</tr>
	<tr>
		<td class="nav" valign="top" colspan="2">{JUMPBOX}</td>
	</tr>
</table>