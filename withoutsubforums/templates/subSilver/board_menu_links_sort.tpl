<!-- $Id: board_menu_links_sort.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<form action="{S_ACTION}" method="post" name="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
  <tr>
    <th width="40%">{L_BL_LINK}</th>
    <th width="60%" colspan="2">&nbsp;</th>
  </tr>
<!-- BEGIN sort_links_row -->
  <tr>
    <td class="row1" align="center" width="40%"><span class="gen">{sort_links_row.BL_LINK}</span></td>
    <td class="row2" align="center" width="30%"><span class="gensmall">{sort_links_row.BL_UP}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;{sort_links_row.BL_DOWN}</span></td>
    <td class="row2" align="center" width="30%"><span class="gensmall">{sort_links_row.BL_FIRST}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;{sort_links_row.BL_LAST}</span></td>
  </tr>
<!-- END sort_links_row -->
  <tr>
    <td class="catBottom" align="center" width="100%" colspan="3"><span class="gen">{U_CLOSE_WINDOW}&nbsp;&nbsp;&nbsp;{U_SORT_DEFAULT}</span></td>
  </tr>
</table>
</form>