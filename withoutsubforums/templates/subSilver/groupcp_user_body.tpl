<!-- $Id: groupcp_user_body.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr>
    <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
  <!-- BEGIN switch_groups_joined -->
  <tr>
    <th colspan="2" align="center" class="thHead">{L_GROUP_MEMBERSHIP_DETAILS}</th>
  </tr>
  <!-- BEGIN switch_groups_member -->
  <tr>
    <td class="row1"><span class="gen">{L_YOU_BELONG_GROUPS}</span></td>
    <td class="row2" align="right">
      <table width="90%" cellspacing="0" cellpadding="0" border="0">
        <tr><form method="get" action="{S_USERGROUP_ACTION}">
            <td width="40%"><span class="gensmall">{GROUP_MEMBER_SELECT}</span></td>
            <td align="center" width="30%">
              <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
            </td>
        </form></tr>
      </table>
    </td>
  </tr>
  <!-- END switch_groups_member -->
  <!-- BEGIN switch_groups_pending -->
  <tr>
    <td class="row1"><span class="gen">{L_PENDING_GROUPS}</span></td>
    <td class="row2" align="right">
      <table width="90%" cellspacing="0" cellpadding="0" border="0">
        <tr><form method="get" action="{S_USERGROUP_ACTION}">
            <td width="40%"><span class="gensmall">{GROUP_PENDING_SELECT}</span></td>
            <td align="center" width="30%">
              <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
            </td>
        </form></tr>
      </table>
    </td>
  </tr>
  <!-- END switch_groups_pending -->
  <!-- END switch_groups_joined -->
  <!-- BEGIN switch_groups_remaining -->
  <tr>
    <th colspan="2" align="center" class="thHead">{L_JOIN_A_GROUP}</th>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_SELECT_A_GROUP}</span></td>
    <td class="row2" align="right">
      <table width="90%" cellspacing="0" cellpadding="0" border="0">
        <tr><form method="get" action="{S_USERGROUP_ACTION}">
            <td width="40%"><span class="gensmall">{GROUP_LIST_SELECT}</span></td>
            <td align="center" width="30%">
              <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
            </td>
        </form></tr>
      </table>
    </td>
  </tr>
  <!-- END switch_groups_remaining -->

  <!-- BEGIN switch_groups_subscriptions -->
  <tr>
    <th colspan="2" align="center" class="thHead" height="25">{L_GROUP_SUBSCRIPTIONS_USER_SUBSCRIBE}</th>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_GROUP_SUBSCRIPTIONS_USER_SUBSCRIBE_SELECTION}</span></td>
    <td class="row2" align="right">
        <table width="90%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <form method="get" action="{S_USERGROUP_ACTION}">
                    <td width="40%"><span class="gensmall">{GROUP_LIST_SELECT2}</span></td>
                    <td align="center" width="30%">
                        <input type="submit" value="{L_VIEW_INFORMATION}" class="liteoption" />{S_HIDDEN_FIELDS}
                    </td>
                </form>
            </tr>
        </table>
    </td>
  </tr>
  <!-- END switch_groups_subscriptions -->
</table>

<br clear="all" />