<!-- $Id: privmsga_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language="Javascript" type="text/javascript">
//<![CDATA[
	function check_uncheck_main()
	{
		var all_checked = true;
		for (i = 0; (i < document.post.elements.length) && all_checked; i++)
		{
			if (document.post.elements[i].name == 'mark_ids[]')
			{
				all_checked =  document.post.elements[i].checked;
			}
		}
		document.post.all_mark.checked = all_checked;
	}

	function check_uncheck_all()
	{
		for (i = 0; i < document.post.length; i++)
		{
			if (document.post.elements[i].name == 'mark_ids[]')
			{
				document.post.elements[i].checked = document.post.all_mark.checked;
			}
		}
	}
//]]>
</script>

<form name="post" method="post" action="{U_ACTION}">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
	<td valign="top">{FOLDERS_BOX}</td>
	<td width="5"><span class="gensmall">&nbsp;</span></td>
	<td valign="top" width="100%">
		{PRIVMSGA_BOX}
		<table cellpadding="2" cellspacing="0" border="0" width="100%" align="center">
		<tr>
			<td><a href="{U_POST_NEW_PM}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_PM}" title="{L_POST_NEW_PM}" /></a></td>
			<td	width="100%">
				<span class="nav">
					<!-- BEGIN switch_nav_sentence -->
					<a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_SEPARATOR}<a href="{U_FOLDER}" class="nav">{L_FOLDER}</a>
					<!-- BEGIN switch_subfolder_custom -->
					{NAV_SEPARATOR}<a href="{U_SUBFOLDER}" class="nav">{L_SUBFOLDER}</a>
					<!-- END switch_subfolder_custom -->
					<!-- END switch_nav_sentence -->
				</span>
			</td>
			<td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}<br /></span></td>
		</tr>
		</table>
	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}