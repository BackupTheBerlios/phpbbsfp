<!-- $Id: board_menu_links_admin.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
    <tr>
        <th align="center" colspan="4" width="100%">{L_MANAGER_EXPLAIN}</th>
    </tr>
<!-- BEGIN menulinkrow -->
  <tr>

    <td class="row1" align="center" width="40%"><span class="gen">{menulinkrow.BL_MENU_LINK}</span></td>
    <td class="row1" align="center" width="20%"><span class="gen">{menulinkrow.BL_LEVEL}</span></td>
    <td class="row2" align="center" width="20%"><span class="gen">{menulinkrow.BL_EDIT}</span></td>
    <td class="row2" align="center" width="20%"><span class="gen">{menulinkrow.BL_DELETE}</span></td>
  </tr>
<!-- END menulinkrow -->
</table>

<br />

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_BL_NAME}</span></td>
    <td class="row2" align="left" width="80%"><span class="gen">{BLNAME}</span></td>
  </tr>
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_BL_LEVEL}</span></td>
    <td class="row2" align="left" width="80%"><span class="gen">{BLLEVEL}</span></td>
  </tr>
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_BL_IMG}</span></td>
    <td class="row2" align="left" width="80%"><span class="gen">{BLIMG}</span></td>
  </tr>
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_BL_LINK}</span></td>
    <td class="row2" align="left" width="80%"><span class="gen">{BLLINK}</span><br /><span class="gensmall">{L_BL_LINK_EXPLAIN}</span></td>
  </tr>
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_BL_PARAMETER}</span></td>
    <td class="row2" align="left" width="80%"><span class="gen">{BLPARAMETER}</span><br /><span class="gensmall">{L_BL_PARAMETER_EXPLAIN}</span></td>
  </tr>
  <tr>
    <td class="catBottom" align="center" width="100%" colspan="2"><span class="gen">{U_CLOSE_WINDOW}</span></td>
  </tr>
</table>
</form>