<!-- $Id: admin_referers.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_HTTP_REFERERS_TITLE}</h1>

<p>{L_HTTP_REFERERS_EXPLAIN}</p>

<p><form action="{U_SHOW_URLS_ACTION}" method="post"><input type="submit" value="{L_DO_SHOW_URLS}" class="mainoption"></form></p>

<form action="{U_LIST_ACTION}" method="post">
<p>{L_SELECT_SORT_METHOD}
<select name="sort">
<option value="referer_host" class="genmed" {REFERER_SELECTED} >{L_REFERER}</option>
<option value="referer_hits" class="genmed" {HITS_SELECTED} >{L_HITS}</option>
<option value="referer_firstvisit" class="genmed" {FIRSTVISIT_SELECTED} >{L_FIRSTVISIT}</option>
<option value="referer_lastvisit" class="genmed" {LASTVISIT_SELECTED} >{L_LASTVISIT}</option>
</select>
 {L_ORDER}
<select name="order">
<option value="" {ASC_SELECTED} >{L_SORT_ASCENDING}</option>
<option value="DESC" {DESC_SELECTED}>{L_SORT_DESCENDING}</option>
</select>
<!-- BEGIN switch_show_ref_urls -->
<input type="hidden" name="mode" value="showurls">
<!-- END switch_show_ref_urls -->
<input type="submit" value="{L_SORT}" class="mainoption"> <input type="submit" onclick="return (confirm('{L_CONFIRM_DELETE_REFERERS}'));" value="{L_DELETE}" name="delete" class="liteoption">
</form>

<table width="100%" cellpadding="6" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thCornerL" height="25" valign="middle" nowrap="nowrap">{L_REFERER}</th>
		<!-- BEGIN switch_show_ref_urls -->
		<th class="thTop" height="25" valign="middle" nowrap="nowrap">{L_REFERER_URL}</th>
		<th class="thTop" height="25" valign="middle" nowrap="nowrap">{L_REFERER_IP}</th>
		<!-- END switch_show_ref_urls -->
		<th class="thTop" height="25" valign="middle" nowrap="nowrap">{L_HITS}</th>
		<th class="thTop" height="25" valign="middle" nowrap="nowrap">{L_FIRSTVISIT}</th>
		<th class="thTop" height="25" valign="middle" nowrap="nowrap">{L_LASTVISIT}</th>
		<th class="thCornerR" height="25" valign="middle" nowrap="nowrap">{L_ACTION}</th>
	</tr>
	<!-- BEGIN refererrow_with_ref_urls -->
	<tr>
		<td class="{refererrow_with_ref_urls.COLOR}" align="left" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_REFERER}" target="_blank">{refererrow_with_ref_urls.REFERER}</a></span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_URL}"{refererrow_with_ref_urls.URL_TITLE} target="_blank">{refererrow_with_ref_urls.URL}</a></span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a href="{refererrow_with_ref_urls.U_IP}">{refererrow_with_ref_urls.L_IP}</a></span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow_with_ref_urls.HITS}</span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow_with_ref_urls.FIRSTVISIT}</span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow_with_ref_urls.LASTVISIT}</span></td>
		<td class="{refererrow_with_ref_urls.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a onclick="return (confirm('{L_CONFIRM_DELETE_REFERER}'));" href="{refererrow_with_ref_urls.U_DELETE}">{L_DELETE}</a></span></td>
	</tr>
	<!-- END refererrow_with_ref_urls -->
	<!-- BEGIN refererrow -->
	<tr>
		<td class="{refererrow.COLOR}" align="left" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a href="{refererrow.U_REFERER}" target="_blank">{refererrow.REFERER}</a></span></td>
		<td class="{refererrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow.HITS}</span></td>
		<td class="{refererrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow.FIRSTVISIT}</span></td>
		<td class="{refererrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall">{refererrow.LASTVISIT}</span></td>
		<td class="{refererrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="gensmall"><a onclick="return (confirm('{L_CONFIRM_DELETE_REFERER}'));" href="{refererrow.U_DELETE}">{L_DELETE}</a></span></td>
	</tr>
	<!-- END refererrow -->
	<tr>
		<!-- BEGIN switch_show_ref_urls -->
		<td class="catBottom" height="28" align="center" valign="middle" colspan="7">
		<!-- END switch_show_ref_urls -->
		<!-- BEGIN switch_dont_show_ref_urls -->
		<td class="catBottom" height="28" align="center" valign="middle" colspan="5">
		<!-- END switch_dont_show_ref_urls -->
		</td>
	</tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" valign="middle" nowrap="nowrap"><span class="nav">{PAGE_NUMBER}</span></td>
		<td align="right" valign="middle"><span class="nav">{PAGINATION}</span></td>
	</tr>
</table>
<br />