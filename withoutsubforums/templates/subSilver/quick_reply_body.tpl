<!-- $Id: quick_reply_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language='javascript' type='text/javascript'>
function checkForm()
{
	formErrors = false;
	if (document.post.message.value.length < 2)
	{
		formErrors = "{L_EMPTY_MESSAGE}";
	}
	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}
</script>

<br />
<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
<form action="{S_QUICK_REPLY_ACTION}" method="post" name="post" onsubmit="return checkForm(this)">
	<tr>
        <th
        	<!-- BEGIN switch_user_logged_out -->
        		colspan="2"
			<!-- END switch_user_logged_out -->
        >{L_QUICK_REPLY}</th>
    </tr>
    <tr>
    	<!-- BEGIN switch_user_logged_out -->
    	<td class="row1" valign="top" nowrap="nowrap" width="150">
			<span class="genmed"><b>{L_USERNAME}:</b></span><br />
			<span class="genmed"><b>{L_QUICK_REPLY}:</b></span>
		</td>
		<!-- END switch_user_logged_out -->
		<td class="row2" valign="top">
			<!-- BEGIN switch_user_logged_out -->
			<input type="text" class="text" name="username" size="24" maxlength="25" value="" /><br />
			<!-- END switch_user_logged_out -->
			<textarea class="post" name="message" rows="3" style="width:100%" wrap="virtual"></textarea>
		</td>
	</tr>
	<tr>
		<td class="catBottom"
			<!-- BEGIN switch_user_logged_out -->
				colspan="2"
			<!-- END switch_user_logged_out -->
		align="center">{QUICK_REPLY_FORM}<input type="submit" class="mainoption" name="preview" value="{L_PREVIEW}" />&nbsp;&nbsp;<input type="submit" class="mainoption" name="post" value="{L_SUBMIT}" />

		</td>
	</tr>
</form>
</table>
