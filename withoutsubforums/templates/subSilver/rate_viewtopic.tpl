<!-- $Id: rate_viewtopic.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
    <tr>
    	<td class="row1" colspan="2">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
  				<tr>
    				<td valign="top" align="left">
    					<span class="postdetails">{RATE_TOPIC_STATS}&nbsp;{FULL_STATS_URL}</span>
    				</td>
					<td valign="top" align="right">
						<span class="postdetails">
	      				<!-- BEGIN rerate -->
		    			<form method="post" action="{rerate.S_RATE_ACTION}">
		    				{rerate.S_HIDDEN_FIELDS}{rerate.L_CHANGE_RATING}:&nbsp;{rerate.S_RATE_SELECT}
		    				<input type="submit" name="submit" value="{rerate.L_RATE}" class="liteoption" />{rerate.RATE_TOPIC_USER}
	      				<!-- END rerate -->

						<!-- BEGIN rate -->
		    			<form method="post" action="{rate.S_RATE_ACTION}">
		    				{rate.S_HIDDEN_FIELDS}{rate.L_CHOOSE_RATING}:&nbsp;{rate.S_RATE_SELECT}
		    				<input type="submit" name="submit" value="{rate.L_RATE}" class="liteoption" />
		    				{rate.RATE_TOPIC_USER}
						<!-- END rate -->

						<!-- BEGIN rated -->
		    			&nbsp;&nbsp;{rated.RATE_TOPIC_USER}&nbsp;&nbsp;{L_RATE_TOPIC_USER_ANON}
						<!-- END rated -->

						<!-- BEGIN noauth -->
		    			{noauth.RATE_TOPIC_USER}
						<!-- END noauth -->

						<!-- BEGIN rerate -->
						</form>
						<!-- END rerate -->

						<!-- BEGIN rate -->
						</form>
						<!-- END rate -->
		  				</span>
					</td>
  				</tr>
			</table>
		</td>
	</tr>