<!-- $Id: rate_detailed.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<table><tr>
<td align="left" valign="middle" class="nav" width="100%"><span   class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{REQUEST_URI}">{PAGE_NAME}</a></span></td>
</tr></table><hr>

<table class="forumline" width="100%">
<tr><td class="catHead" align="center" colspan="4"><span class="cattitle">{L_TITLE}</td></tr>
<tr><td class="{DEFAULT_CLASS}" align="center"><span class="nav"><br /><br />{L_TOPIC_RETURN}<br /><br /><br /></span></td></tr>
</table>
<br>

<table border="0" cellpadding="4" cellspacing="1" class="forumline" width="100%">
  <tr>
    <th colspan="1" align="center" height="25" class="thCornerL" width="19%">&nbsp;{L_RANK}&nbsp;</th>
    <th width="19%" align="center" class="thTop">&nbsp;{L_USERNAME}&nbsp;</th>
    <th width="19%" align="center" class="thTop">&nbsp;{L_USER_RATED}&nbsp;</th>
    <th width="19%" align="center" class="thTop">&nbsp;&nbsp;{L_USER_MAX_RATE}&nbsp;&nbsp;</th>
    <th width="19%" align="center" class="thTop">&nbsp;{L_USER_RATE_DATE}&nbsp;</th>
  </tr>
  <!-- BEGIN user_rates_row -->
  <tr>
    <td class="row1" align="center"><span class="postdetails">{user_rates_row.RANK}</span></td>
    <td class="row2" align="center" valign="middle"><span class="postdetails"><a href="{user_rates_row.U_VIEWPROFILE}">{user_rates_row.USERNAME}</a></span></td>
    <td class="row3" align="center" valign="middle"><span class="postdetails">{user_rates_row.USER_RATE}</span></td>
    <td class="row2" align="center" valign="middle"><span class="postdetails">{user_rates_row.USER_MAX_RATE}</span></td>
    <td class="row3" align="center" valign="middle"><span class="postdetails">{user_rates_row.USER_RATE_DATE}</span></td>
  </tr>
  <!-- END user_rates_row -->
</table>

