<!-- $Id: rate_main.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<table><tr>
<td align="left" valign="middle" class="nav" width="100%"><span   class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{REQUEST_URI}">{PAGE_NAME}</a></span></td>
</tr></table><hr>
<form method="post" action="{S_MODE_ACTION}">
<table width="100%"><tr><td align="right">
&nbsp;<span class="gen">{L_BY_FORUM}&nbsp;&nbsp;{S_FORUMS}&nbsp;&nbsp;{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_VIEW}" class="lightoption" /></span></td>
</tr></table>
</form>
<br />
  <table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
	  <th colspan="1" align="center" height="25" class="thCornerL">&nbsp;{L_TOPICS}&nbsp;{L_FOR_FORUM}&nbsp;</th>
	  <th width="50" align="center" class="thTop">&nbsp;{L_RATES}&nbsp;</th>
	  <th width="100" align="center" class="thTop">&nbsp;&nbsp;{L_RATING}&nbsp;&nbsp;</th>
	  <th width="50" align="center" class="thTop">&nbsp;{L_MIN}&nbsp;</th>
	  <th width="50" align="center" class="thTop">&nbsp;{L_MAX}&nbsp;</th>
	  <th align="center"  nowrap="nowrap" class="thCornerR">&nbsp;{L_LAST_RATED}&nbsp;</th>
	</tr>
	<!-- BEGIN notopics -->
	  <td class="row3" align="center" valign="middle" colspan="6"><span class="postdetails"><br /><br />{notopics.MESSAGE}<br /><br /></span></td>
	<!-- END notopics -->
	<!-- BEGIN topicrow -->
	<tr>
	  <td class="row1" width="100%">

	<table width="100%">
		<tr><td align="left">
	  <span class="topictitle"><a href="{topicrow.URL}" class="topictitle">{topicrow.TITLE}</a></span>
	  	</td><td align="right">
	  <span class="nav">{topicrow.L_VIEW_DETAILS}</span>
	  	</td></tr>
	</table>

	  </td>
	  <td class="row3" align="center" valign="middle"><span class="postdetails">{topicrow.NUMBER_OF_RATES}</span></td>
	  <td class="row2" align="center" valign="middle"><span class="name">{topicrow.RATING}</span></td>
	  <td class="row3" align="center" valign="middle"><span class="postdetails">{topicrow.MIN}</span></td>
	  <td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.MAX}</span></td>
	  <td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{topicrow.LAST_RATER_TIME}<br />{topicrow.LAST_RATER}</span></td>
	</tr>
	<!-- END topicrow -->
  </table>
<br />
<br />
