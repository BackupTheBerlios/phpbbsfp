<!-- $Id: posting_topic_review.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language="JavaScript" type="text/javascript">
<!--

message = new Array();
<!-- BEGIN postrow -->
	message[{postrow.U_POST_ID}] = "[quote=\"{postrow.POSTER_NAME}\";p=\"{postrow.U_POST_ID}\"]\n{postrow.PLAIN_MESSAGE}\n[/quote]";
<!-- END postrow -->

function addquote(post_id) 
{
	window.parent.document.post.message.value += message[post_id];
	window.parent.document.post.message.focus();
	return;
}

//-->
</script>

<!-- BEGIN switch_inline_mode -->
<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
    <tr>
        <td class="catHead" align="center"><b><span class="cattitle">{L_TOPIC_REVIEW}</span></b></td>
    </tr>
    <tr>
        <td class="row1"><iframe width="100%" height="300" src="{U_REVIEW_TOPIC}" >
<!-- END switch_inline_mode -->
<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
    <tr>
        <th class="thCornerL" width="22%">{L_AUTHOR}</th>
        <th class="thCornerR">{L_MESSAGE}</th>
    </tr>
    <!-- BEGIN postrow -->
    <tr>
        <td width="22%" align="left" valign="top" class="{postrow.ROW_CLASS}"><span class="name"><a name="{postrow.U_POST_ID}"></a><b>{postrow.POSTER_NAME}</b></span></td>
        <td class="{postrow.ROW_CLASS}" valign="top">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100%"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /><span class="postdetails">{L_POSTED}: {postrow.POST_DATE}<span class="gen">&nbsp;</span>&nbsp;&nbsp;&nbsp;{L_POST_SUBJECT}: {postrow.POST_SUBJECT}</span></td>
                <td valign="top" align="right" nowrap="nowrap"><span class="genmed"><input type="button" class="button" name="addquote" value="Quote" style="width: 50px" onClick="addquote({postrow.U_POST_ID});" /></span></td>
            </tr>
            <tr>
                <td colspan="2"><hr /></td>
            </tr>
            <tr>
                <td colspan="2"><span class="postbody">{postrow.MESSAGE}</span>{postrow.ATTACHMENTS}</td>
            </tr>
          </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="1" class="spaceRow"><img src="{TEMPLATE}images/spacer.gif" alt="" width="1" height="1" /></td>
    </tr>
     <!-- END postrow -->
</table>
<!-- BEGIN switch_inline_mode -->
        </iframe></td>
    </tr>
</table>
<!-- END switch_inline_mode -->
