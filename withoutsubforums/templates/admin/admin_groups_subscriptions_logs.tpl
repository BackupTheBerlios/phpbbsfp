<!-- $Id: admin_groups_subscriptions_logs.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
<tr><td class=row2><table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=50% align=left>{PAGE_NUMBER}</td><td width=50% align=right>{PAGINATION}</td>
</tr></table></td></tr></table><br />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
	<th class="thHead" colspan="8" height="25" valign="middle">{L_GROUP_SUBSCRIPTIONS_LOGS_TITLE}</th>
	</tr>
		<tr>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_ID}">{L_GROUP_SUBSCRIPTIONS_LOGS_ID}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_DATE}">{L_GROUP_SUBSCRIPTIONS_LOGS_DATE}</a></b></span></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_PAID}">{L_GROUP_SUBSCRIPTIONS_LOGS_PAID}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_GROUP}">{L_GROUP_SUBSCRIPTIONS_LOGS_GROUP}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_USERNAME}">{L_GROUP_SUBSCRIPTIONS_LOGS_USERNAME}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_IP}">{L_GROUP_SUBSCRIPTIONS_LOGS_IP}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_AMOUNT}">{L_GROUP_SUBSCRIPTIONS_LOGS_AMOUNT}</a></b></span></td>
<td class="row2" align=center><span class="gensmall"><b><a href="{U_GROUP_SUBSCRIPTIONS_ACTION}">{L_GROUP_SUBSCRIPTIONS_LOGS_ACTION}</a></b></span></td>
	</tr>
	<!-- BEGIN groups -->
	<tr>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall">{groups.GROUP_TRANS_ID}</span></td>
<td class="{groups.ROW_CLASS}" nowrap=nowrap width=10%><span class="gensmall">{groups.GROUP_DATE}</span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall"><font color="{groups.GROUP_PAID_COLOR}"><b>{groups.GROUP_PAID}</b></font></span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall"><a href="{groups.U_GROUP_ID}" target="_blank">{groups.GROUP_GROUPS_ID_NUMBER}</a></span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall"><a href="{groups.U_USER_ID}" target="_blank">{groups.GROUP_USERNAME}</a></span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall">{groups.GROUP_USER_IP}</span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall">{L_GROUP_SUBSCRIPTIONS_SYMBOL}{groups.GROUP_GROSS_AMOUNT}</span></td>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall"><font color="{groups.GROUP_PAID_COLOR}"><b>{groups.GROUP_ACTION}</b></font></span></td>
	</tr>
</form>
	<!-- END groups -->
</table><br />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
<tr><td class=row2><table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=50% align=left>{PAGE_NUMBER}</td><td width=50% align=right>{PAGINATION}</td>
</tr></table></td></tr></table><br />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
	<th class="thHead" colspan="4" height="25" valign="middle">{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_TITLE}</th>
	</tr>
<tr>
<td class=row2 align=center><script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_INVALID}");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<a onclick="return confirmSubmit()"
href="{U_GROUP_SUBSCRIPTIONS_DELETE_ALL_INVALID}">
{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_INVALID_LINK}</a></td>
<td class=row2 align=center><script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_CANCELED}");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<a onclick="return confirmSubmit()"
href="{U_GROUP_SUBSCRIPTIONS_DELETE_ALL_CANCELED}">
{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_CANCELED_LINK}</a></td>
<td class=row2 align=center><script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_SIGNUPS}");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<a onclick="return confirmSubmit()"
href="{U_GROUP_SUBSCRIPTIONS_DELETE_ALL_SIGNUPS}">
{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_SIGNUPS_LINK}</a></td>
<td class=row2 align=center><script LANGUAGE="JavaScript">
<!--
function confirmSubmit()
{
var agree=confirm("{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL}");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<a onclick="return confirmSubmit()"
href="{U_GROUP_SUBSCRIPTIONS_TRUNCATE}">
{L_GROUP_SUBSCRIPTIONS_LOGS_DELETE_ALL_LINK}</a></td>
</tr></table><br /><br />
