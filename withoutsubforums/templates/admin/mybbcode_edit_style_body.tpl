<h1>MyBBCode</h1>

<p>{L_EXPLAIN}</p>

<form action="{S_ACTION}" method="POST"><table class="forumline" cellpadding="4" cellspacing="1" border="0" align="center">
	<tr>
		<th class="thTop" colspan="2">MyBBCode -> {L_OP} BBCode {L_STYLE}</th>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_TAG}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_TAG}</span></td>
		<td class="row2"><input class="post" type="text" name="tag" size="40" maxlength="20" value="{TAG}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_TAG_OPEN}*:</span></td>
		<td class="row2"><textarea name="tag_open" rows="5" cols="45" wrap="virtual" class="post">{TAG_OPEN}</textarea></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_TAG_CLOSE}*:</span></td>
		<td class="row2"><textarea name="tag_close" rows="5" cols="45" wrap="virtual" class="post">{TAG_CLOSE}</textarea></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">BBCode*:</span></td>
		<td class="row2"><select name="bbcode_id">{BBCODE_LIST}</select></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_STYLE}*:</span></td>
		<td class="row2"><select name="style_id">{STYLE_LIST}</select></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
{S_HIDDEN_FIELDS}</form>
