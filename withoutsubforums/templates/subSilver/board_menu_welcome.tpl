<!-- $Id: board_menu_welcome.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<script language="JavaScript" type="text/javascript">
<!--
function close_manager() {
 window.open("index.php", "_parent");
}
//-->
</script>

<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
    <th align="center" width="100%">{L_USER_OPTIONS}</th>
  </tr>
  <tr>
    <td class="row1" align="center" width="100%">{U_SET_BOARD_LINKS}</td>
  </tr>
<!-- BEGIN switch_sorting_on -->
  <tr>
    <td class="row1" align="center" width="100%">{U_SORT_BOARD_LINKS}</td>
  </tr>
<!-- END switch_sorting_on -->
</table>

<br />

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<!-- BEGIN admin_options -->
  <tr>
    <th align="center" width="100%">{L_ADMINISTRATOR_OPTIONS}</th>
  </tr>
  <tr>
    <td class="row1" align="center" width="100%">{admin_options.U_MANAGE_BOARD_LINKS}</td>
  </tr>
   <tr>
    <td class="row1" align="center" width="100%">{admin_options.U_DEFAULT_SORT_LINKS}</td>
  </tr>
 <tr>
    <td class="row1" align="center" width="100%">{admin_options.U_CONFIG_BOARD_LINKS}</td>
  </tr>
<!-- END admin_options -->
  <tr>
    <td class="catBottom" align="center" width="100%">{U_CLOSE_WINDOW}</td>
  </tr>
</table>
</form>