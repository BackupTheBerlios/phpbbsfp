<!-- $Id: public_base_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<table cellpadding="0" cellspacing="10" border="0" width="100%">
<tr>
	<td valign="top" align="center">
		<table cellpadding="2" cellspacing="1" border="0" class="forumline" width="100%">
		<tr>
			<th valign="middle">{L_PUBLIC_TITLE}</th>
		</tr>
		<tr>
			<td class="row3" valign="top">
				<table cellpadding="2" cellspacing="4" border="0" width="100%">
				<tr>
					<!-- BEGIN col -->
					<td valign="top">
						<!-- BEGIN panel -->
						<!-- BEGIN linefeed -->
						<br style="font-size: 4 px;" />
						<!-- END linefeed -->
						<table cellpadding="4" cellspacing="0" border="0" class="bodyline" width="100%">
						<tr>
							<td class="cat" align="center" colspan="{col.panel.SPAN}"><span class="cattitle">{col.panel.TITLE}</span></td>
						</tr>
						<!-- BEGIN row -->
						<tr>
							<!-- BEGIN linefeed -->
							<td class="row2" colspan="{col.panel.SPAN}"><span style="font-size: 2px">&nbsp;</span></td>
							<!-- END linefeed -->
							<!-- BEGIN cell -->
							<td class="{col.panel.row.cell.CLASS}" align="{col.panel.row.cell.ALIGN}" width="{col.panel.row.cell.WIDTH}" {col.panel.row.cell.WRAP}><span class="gen">{col.panel.row.cell.VALUE}</span></td>
							<!-- END cell -->
							<!-- BEGIN cellfeed -->
							<td class="row2"></td>
							<!-- END cellfeed -->
						</tr>
						<!-- END row -->
						</table>
						<!-- END panel -->
					<!-- END col -->
					{CASH}</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>