<!-- $Id: board_menu_links_set.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<script language="Javascript" type="text/javascript">
    function select_board(status)
    {
        for (i = 0; i < document.set_board.length; i++)
        {
            document.set_board.elements[i].checked = status;
        }
    }
</script>

<h1 align="center">{L_WELCOME}</h1>
<p>{L_MANAGER_EXPLAIN}</p>

<form action="{S_ACTION}" method="post" name="set_board">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
    <tr>
        <th class="thtop" width="70%">{L_BL_LINK}</th>
        <th class="thtop" width="30%">{L_BL_SET}</th>
    </tr>
    <!-- BEGIN board_links_row -->
    <tr>
        <td class="row1" align="center" width="70%">{board_links_row.BL_MENU_LINKS}</td>
        <td class="row2" align="center" width="30%">{board_links_row.BL_CHECK}</td>
    </tr>
    <!-- END board_links_row -->

    <tr>
        <td class="row1" align="center" width="70%">&nbsp;</td>
        <td class="row2" align="center" width="30%"><a href="javascript:select_board(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_board(false);" class="gensmall">{L_UNMARK_ALL}</a><br /></td>
    </tr>
    <tr>
        <td class="catBottom" align="center" width="100%" colspan="2"><span class="gen">{U_CLOSE_WINDOW}</span></td>
    </tr>
</table>
</form>