<!-- $Id: board_menu_categories_admin.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
<!-- BEGIN menucatrow -->
  <tr>
    <td class="row1" align="center" width="40%">{menucatrow.BL_CAT}</td>
    <td class="row2" align="center" width="20%">{menucatrow.BL_MERGE}</td>
    <td class="row2" align="center" width="20%">{menucatrow.BL_EDIT}</td>
    <td class="row2" align="center" width="20%">{menucatrow.BL_DELETE}</td>
  </tr>
<!-- END menucatrow -->
</table>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
  <tr>
    <td class="row1" align="right" width="20%"><span class="gen">{L_CAT_NAME}</span></td>
    <td class="row2" align="left"><span class="gen">{CATNAME}</span></td>
    <td class="row2" align="left"><span class="gen">{SHOW_CATNAME}</span></td>
    <td class="row2" align="left"><span class="gen">{SHOW_SEPERATOR}</span></td>
  </tr>
  <tr>
    <td class="row1" align="center" width="100%" colspan="4"><span class="gen">{U_CLOSE_WINDOW}</span></td>
  </tr>
</table>
</form>