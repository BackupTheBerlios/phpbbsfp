<h1>MyBBCode</h1>

<p>{L_EXPLAIN_EDITADD}</p>

<form action="{S_ACTION}" method="POST"><table class="forumline" cellpadding="4" cellspacing="1" border="0" align="center">
	<tr>
		<th class="thTop" colspan="2">MyBBCode -> {L_OP} BBCode</th>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_NAME}*:</span></td>
		<td class="row2"><input class="post" type="text" name="name" size="40" maxlength="20" value="{NAME}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_OPEN_TAG}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_OPEN_TAG}</span></td>
		<td class="row2"><input class="post" type="text" name="open_tag" size="40" maxlength="100" value="{OPEN_TAG}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_CLOSE_TAG}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_CLOSE_TAG}</span></td>
		<td class="row2"><input class="post" type="text" name="close_tag" size="40" maxlength="100" value="{CLOSE_TAG}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_TAG_OPEN}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_TAG_OPEN}</span></td>
		<td class="row2"><textarea name="tag_open" rows="5" cols="45" wrap="virtual" class="post">{TAG_OPEN}</textarea></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_TAG_CLOSE}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_TAG_CLOSE}</span></td>
		<td class="row2"><textarea name="tag_close" rows="5" cols="45" wrap="virtual" class="post">{TAG_CLOSE}</textarea></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_ATTR_CHARS}:</span><br />
		<span class="gensmall">{L_EXPLAIN_ATTR_CHARS}</span></td>
		<td class="row2"><input class="post" type="text" name="attr_chars" size="40" maxlength="50" value="{ATTR_CHARS}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_ATTR_CHARS_PURE}:</span></td>
		<td class="row2"><input class="post" type="checkbox" name="attr_chars_pure" {ATTR_CHARS_PURE}/>{L_YES}</td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_CONTENT_CHARS}:</span><br />
		<span class="gensmall">{L_EXPLAIN_CONTENT_CHARS}</span></td>
		<td class="row2"><input class="post" type="text" name="content_chars" size="40" maxlength="50" value="{CONTENT_CHARS}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_CONTENT_CHARS_PURE}:</span></td>
		<td class="row2"><input class="post" type="checkbox" name="content_chars_pure" {CONTENT_CHARS_PURE}/>{L_YES}</td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_INCLUDE_FILE}:</span></td>
		<td class="row2"><input class="post" type="text" name="include_file" size="40" maxlength="100" value="{INCLUDE_FILE}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_PARSE_FUNC_1}:</span></td>
		<td class="row2"><input class="post" type="text" name="parse_func_1" size="40" maxlength="100" value="{PARSE_FUNC_1}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_PARSE_FUNC_2}:</span></td>
		<td class="row2"><input class="post" type="text" name="parse_func_2" size="40" maxlength="100" value="{PARSE_FUNC_2}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_HELP}:</span><br />
		<span class="gensmall">{L_EXPLAIN_HELP}</td>
		<td class="row2"><textarea name="help" rows="5" cols="45" wrap="virtual" class="post">{HELP}</textarea></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_SHORTCUT_KEY}:</span><br />
		<span class="gensmall">{L_EXPLAIN_SHORTCUT_KEY}</td>
		<td class="row2"><input class="post" type="text" name="shortcut_key" size="1" maxlength="1" value="{SHORTCUT_KEY}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_QUICKTIP}*:</span><br />
		<span class="gensmall">{L_EXPLAIN_QUICKTIP}</td>
		<td class="row2"><input class="post" type="text" name="quicktip" size="40" maxlength="100" value="{QUICKTIP}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_STYLE}:</span><br />
		<span class="gensmall">{L_EXPLAIN_STYLE}</td>
		<td class="row2"><input class="post" type="text" name="style" size="60" maxlength="255" value="{STYLE}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_SHOW_BUTTON}:</span><br />
		<span class="gensmall">{L_EXPLAIN_SHOW_BUTTON}</td>
		<td class="row2"><input class="post" type="checkbox" name="show_button" {SHOW_BUTTON}/></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
{S_HIDDEN_FIELDS}</form>
