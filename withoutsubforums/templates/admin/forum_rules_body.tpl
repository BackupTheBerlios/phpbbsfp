<!-- $Id: forum_rules_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_RULES}</h1>

<p>{L_RULES_EXPLAIN}</p>

<!-- IF S_MODE == 'modify' || S_MODE == 'create' -->

<form method="post"	action="{S_RULE_ACTION}">
  <table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead">{L_RULES}<!-- IF S_MODE == 'modify' --> - {L_LAST_MODIFIED}: {RULE_DATE}<!-- ENDIF --></th>
	</tr>

	<tr>
		<td class="row1">
			<textarea name="rule_text" rows="30" cols="90">{S_RULE_TEXT}</textarea>
		</td>
	</tr>

	<tr>
	  <td class="catBottom" align="center" colspan="2">
		<input type="submit" name="dorules" value="{L_DO}" class="mainoption">
	  </td>
	</tr>
  </table>
</form>

<!-- ELSEIF S_MODE == 'list' || S_MODE == '' -->

  <table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
	  <th colspan="2" class="thHead">{L_RULES}</th>
	</tr>
	<tr>
		<td class="row1">{L_SITE}</td>
		<td class="row1">{U_SITE}</td>
	</tr>
	<tr>
		<td class="row1">{L_PRIVACY}</td>
		<td class="row1">{U_PRIVACY}</td>
	</tr>
	<tr>
		<td class="row1">{L_TERMS}</td>
		<td class="row1">{U_TERMS}</td>
	</tr>
	<tr>
		<td class="row1">{L_COPY}</td>
		<td class="row1">{U_COPY}</td>
	</tr>
	<!-- BEGIN rules_list -->
	<tr>
		<td class="row1">{rules_list.FORUM_NAME}</td>
		<td class="row1">{rules_list.U_ACTION}</td>
	</tr>
	<!-- END rules_list -->
	<tr>
	  <td class="catBottom" align="center" colspan="2">

	  </td>
	</tr>
  </table>

<!-- ENDIF -->
<br />

<!-- IF 'x' == 'y' -->
<!-- DONT SHOW!! -->
<div align="center">
	<span class="copyright">
		<a href="{U_NOTVIEW_RULES}">{L_NOTVIEW_RULES}</a><br /><br /><br />
	</span>
</div> <!-- ENDIF -->