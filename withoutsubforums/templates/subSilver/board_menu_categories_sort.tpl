<!-- $Id: board_menu_categories_sort.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
  <tr>
    <th class="thtop" width="60%"><span class="cattitle">{L_CAT_NAME}</span></th>
    <th class="thtop" width="40%">&nbsp;</th>
  </tr>
<!-- BEGIN sort_cat_row -->
  <tr>
    <td class="row1" align="center" width="60%"><span class="gen">{sort_cat_row.CAT_NAME}</span></td>
    <td class="row2" align="center" width="40%"><span class="gensmall">{sort_cat_row.CAT_UP}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;{sort_cat_row.CAT_DOWN}</span></td>
  </tr>
<!-- END sort_cat_row -->
  <tr>
    <td class="row2" align="center" width="100%" colspan="2"><span class="gen">{U_CLOSE_WINDOW}</span></td>
  </tr>
</table>
</form>