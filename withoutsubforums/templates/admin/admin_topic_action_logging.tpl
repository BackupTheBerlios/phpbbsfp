<!-- $Id: admin_topic_action_logging.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<script language="JavaScript">
<!--
function toggle_check_all()
{
	for (var i=0; i < document.msgrow_values.elements.length; i++)
	{
		var checkbox_element = document.msgrow_values.elements[i];
		checkbox_element.checked = document.msgrow_values.check_del_all.checked;
	}
}
-->
</script>

<!-- BEGIN statusrow -->
<table width="100%" cellspacing="2" cellpadding="2" border="1" align="center">
	<tr>
	  <td align="center"><span class="gen">{L_STATUS}<br /></span>
	  <span class="genmed"><b>{I_STATUS_MESSAGE}</b></span><br /></td>
	</tr>
  </table>
<!-- END statusrow -->


<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left">
	  	<span class="maintitle">{L_PAGE_NAME}</span>
	  	<br />
	  	<span class="genmed">{L_PAGE_DESC}</td>
	</tr>
  </table>

<form method="post" action="{S_MODE_ACTION}" name="sort_and_order">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="right" nowrap="nowrap"><span class="genmed">{L_FILTER_BY_USER}:&nbsp;<input type="text" maxlength="255" name="filter_user" value="{S_USER_FILTER}" width="25" class="post">&nbsp;&nbsp;{L_FILTER_BY_TYPE}&nbsp;{S_TYPE_SELECT}</span></td>
		<td align="center" valign="middle" rowspan="2"><input type="submit" name="submit" value="{L_SORT}" class="liteoption"></td>
	</tr><tr>	  <input type="hidden" name="mode" value="{S_MODE}">
	<td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}
		</span></td>
	</tr>
  </table></form>
<form method="post" action="{S_MODE_ACTION}" name="msgrow_values">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
	  <th height="25" class="thCornerL">{L_DELETE}<br /><input type="checkbox" name="check_del_all" onclick="Javascript: toggle_check_all();"></th>
	  <th class="thTop">{L_USERNAME}</th>
	  <th class="thTop">{L_TOPIC_TITLE}</th>
	  <th class="thTop">{L_TOPIC_TEXT}</th>
	  <th class="thTop">{L_ACTION}</th>
	  <th class="thTop">{L_LOG_TIME}</th>
	  <th class="thCornerR">{L_TOPIC_STATUS}</th>
	</tr>
	<!-- BEGIN empty_switch -->
	<tr><td colspan="7" class="row1" align="center">{L_NO_ACTIONS}</td></tr>
	<!-- END empty_switch -->

	<!-- BEGIN msgrow -->
	<tr>
	  <td class="{msgrow.ROW_CLASS}" align="center" width="7%"><input type="checkbox" name="delete_id_{msgrow.LOG_ID}"></td>
	  <td class="{msgrow.ROW_CLASS}" align="center"><span class="genmed">{msgrow.USERNAME}&nbsp;</span></td>
	  <td class="{msgrow.ROW_CLASS}" align="left"><span class="gensmall">{msgrow.TOPIC_TITLE}</span></td>
	  <td class="{msgrow.ROW_CLASS}" align="left" valign="middle" width="35%"><span class="gensmall">{msgrow.TOPIC_TEXT}</span></td>
	  <td class="{msgrow.ROW_CLASS}" align="center" valign="middle"><span class="gen">{msgrow.ACTION}</span></td>
	  <td class="{msgrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{msgrow.LOG_TIME}</span></td>
	  <td class="{msgrow.ROW_CLASS}" align="center" valign="middle"><span class="gensmall">{msgrow.TOPIC_STATUS}</span></td>
	</tr>
	<!-- END msgrow -->

	<tr>
	  <td class="catbottom" colspan="1" height="28" align="center"><input type="hidden" name="mode" value="{S_MODE}">
	  <input type="hidden" name="delete_true" value="1">
	  <input type="submit" name="sub_del" value="{L_DELETE}" class="liteoption"></td>
	  <td class="catbottom" colspan="6" height="28" align="center">
	  &nbsp;</td>
	</tr>
  </table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
	<td><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="gensmall"></span><br /><span class="nav">{PAGINATION}&nbsp;</span></td>
  </tr>
</table></form>
