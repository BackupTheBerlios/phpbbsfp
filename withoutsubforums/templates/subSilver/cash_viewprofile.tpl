<!-- $Id: cash_viewprofile.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<br style="font-size: 4 px;" />
<table class="bodyline" border="0" cellpadding="4" cellspacing="0" width="100%" algin="right">
<tr>
	<td class="cat" colspan="3" align="center"><span class="cattitle">Personal informations</span></td>
</tr>
<tr>
<td class="row2" colspan="2"><span style="font-size: 2px">&nbsp;</span></td>
</tr>
<!-- BEGIN cashrow -->
<tr>
  <td class="row2" align="right" nowrap="nowrap" width="40%"><span class="gen">{cashrow.CASH_NAME}:</span></td>
  <td class="row1" align="left" width="100%"><span class="gen"><b>{cashrow.CASH_AMOUNT}</b></span></td>
</tr>
<!-- END cashrow -->
<!-- BEGIN switch_cashlinkson -->
<tr>
	<td class="row2" colspan="2"><span style="font-size: 2px">&nbsp;</span></td>
</tr>
<tr>
  <td valign="middle" align="right" nowrap="nowrap" class="row2">
  <td class="row1"><span class="gen">
<!-- BEGIN cashlinks -->
	[ <a href="{switch_cashlinkson.cashlinks.U_LINK}" class="genmed">{switch_cashlinkson.cashlinks.L_NAME}</a> ]
<!-- END cashlinks -->
  </span></td>
</tr>
<!-- END switch_cashlinkson -->
<tr>
	<td class="row2" colspan="2"><span style="font-size: 2px">&nbsp;</span></td>
</tr>
</table>