<!-- $Id: update2.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_XS_UPDATES}</h1>

<p>{L_XS_UPDATES_COMMENT2}</p>


<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thCornerL" align="center" nowrap="nowrap">{L_XS_UPDATE_NAME}</th>
		<th class="thTop" align="center" nowrap="nowrap">{L_XS_UPDATE_TYPE}</th>
		<th class="thTop" align="center" nowrap="nowrap">{L_XS_UPDATE_CURRENT_VERSION}</th>
		<th class="thTop" align="center" nowrap="nowrap">{L_XS_UPDATE_LATEST_VERSION}</th>
		<th class="thTop" align="center" nowrap="nowrap">{L_XS_UPDATE_DOWNLOADINFO}</th>
		<th class="thCornerR" align="center" nowrap="nowrap">{L_XS_UPDATE_FILEINFO}</th>
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td class="{row.ROW_CLASS}" align="left" nowrap="nowrap"><span class="gen">{row.ITEM}</span></td>
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">{row.TYPE}</span></td>
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">{row.VERSION}</span></td>
		<!-- BEGIN update -->
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">{row.update.VERSION}</span></td>
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">
			<!-- BEGIN updated -->[<a href="{row.update.UPDATE}" target="_blank">{L_XS_DOWNLOAD_LC}</a>]<!-- END updated -->
			&nbsp;
			<!-- BEGIN import -->[<a href="{row.update.U_IMPORT}">{L_XS_IMPORT_LC}</a>]<!-- END import -->
		</span></td>
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall"><!-- BEGIN info -->[<a href="{row.update.INFO}" target="_blank">{L_XS_INFO_LC}</a>]<!-- END info --><!-- BEGIN noinfo -->&nbsp;<!-- END noinfo --></span></td>
		<!-- END update -->
		<!-- BEGIN noupdate -->
		<td colspan="2" class="{row.ROW_CLASS}" align="left" nowrap="nowrap"><span class="genmed">{row.noupdate.MESSAGE}</span></td>
		<td class="{row.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall"><!-- BEGIN info -->[<a href="{row.noupdate.INFO}" target="_blank">{L_XS_INFO_LC}</a>]<!-- END info --><!-- BEGIN noinfo -->&nbsp;<!-- END noinfo --></span></td>
		<!-- END noupdate -->
		<!-- BEGIN error -->
		<td colspan="2" class="{row.ROW_CLASS}" align="left" nowrap="nowrap"><span class="gen">{row.error.ERROR}</span></td>
		<td class="{row.ROW_CLASS}">&nbsp;</td>
		<!-- END error -->
	</tr>
	<!-- END row -->
	<tr>
		<td class="catBottom" colspan="6" align="center">{COUNT_TOTAL}, {COUNT_ERROR}, {COUNT_UPDATE}</td>
	</tr>
</table>

<br />
