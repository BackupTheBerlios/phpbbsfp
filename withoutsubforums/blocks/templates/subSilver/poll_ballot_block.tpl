<tr>
	<td class="row1" colspan="2"><table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr>
			<td class="row1" align="center"><span class="gensmall"><b>{POLL_QUESTION}</b></span></td>
		</tr>
		<tr>
			<td align="center"><table cellspacing="0" cellpadding="2" border="0">
				<!-- BEGIN portal_poll_option -->
				<tr>
					<td><input type="radio" name="vote_id" value="{portal_poll_option.POLL_OPTION_ID}" />&nbsp;</td>
					<td><span class="gensmall">{portal_poll_option.POLL_OPTION_CAPTION}</span></td>
				</tr>
				<!-- END portal_poll_option -->
			</table></td>
		</tr>
		<tr>
			<td  class="row1" align="center">
			 <!-- BEGIN switch_user_logged_in -->
			 <input type="submit" name="submit" value="{L_SUBMIT_VOTE}" class="liteoption" />
			 <!-- END switch_user_logged_in -->		
			 <!-- BEGIN switch_user_logged_out -->
			 <span class="gensmall">{LOGIN_TO_VOTE}<span>
			 <!-- END switch_user_logged_out -->
			</td>
		</tr>
	{S_HIDDEN_FIELDS}</table></td>
</tr>