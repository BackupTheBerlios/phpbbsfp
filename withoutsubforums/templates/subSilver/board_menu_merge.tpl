<!-- $Id: board_menu_merge.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
<!-- BEGIN linkrow -->
  <tr>
    <td class="row1" align="center" width="60%"><span class="gen">{linkrow.BL_LINK}</span></td>
    <td class="row2" align="center" width="20%">{linkrow.BL_FIX}</span></td>
    <td class="row2" align="center" width="20%">{linkrow.BL_REMOVE}</span></td>
  </tr>
<!-- END linkrow -->
</table>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="10" cellspacing="10" border="0" class="forumline">
<!-- BEGIN bl_names_on -->
  <tr>
    <td class="row1" align="right" width="25%"><span class="gen">{L_BL_NAME}</span></td>
    <td class="row2" align="left" width="75%">{BL_NAME}</span></td>
  </tr>
<!-- END bl_names_on -->
  <tr>
    <td class="row1" align="center" width="100%" colspan="2"><span class="gen">{U_CLOSE_WINDOW}</span></td>
  </tr>
</table>
</form>