<!-- $Id: topic_view_users_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<form method="post" action="{S_MODE_ACTION}">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr>
      <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
      <td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
        <input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
        </span></td>
    </tr>
  </table>
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
    <tr>
      <th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
      <th class="thTop" nowrap="nowrap">{L_TOPIC_TIME}</th>
      <th class="thTop" nowrap="nowrap">{L_TOPIC_COUNT}</th>
    </tr>
    <!-- BEGIN memberrow -->
    <tr>
      <td class="{memberrow.ROW_CLASS}" align="center"><span class="gen"><a href="{memberrow.U_VIEWPROFILE}" class="gen">{memberrow.USERNAME}</a></span></td>
      <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{memberrow.TIME}</span></td>
      <td class="{memberrow.ROW_CLASS}" align="center" valign="middle"><span class="gen">{memberrow.COUNT}</span></td>
    </tr>
    <!-- END memberrow -->
    <tr>
      <td class="catBottom" colspan="3" align="center"><span class="gen">{U_TOPIC}</span></td>
    </tr>
  </table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td><span class="nav">{PAGE_NUMBER}</span></td>
    <td align="right"><span class="nav">{PAGINATION}</span></td>
  </tr>
</table></form>

<table width="100%" cellspacing="2" border="0" align="center">
  <tr>
    <td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
