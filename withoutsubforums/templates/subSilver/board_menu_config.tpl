<!-- $Id: board_menu_config.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<h1 align="center">{L_WELCOME}</h1>
<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
    <tr>
        <th align="center" width="100%" colspan="2">{L_MANAGER_EXPLAIN}</th>
    </tr>

    <tr>
        <td class="row1" align="right" width="50%"><span class="gen">{L_BL_SEPERATOR}</span></td>
        <td class="row2" align="left" width="50%"><span class="gen"><input type="checkbox" name="bl_seperator" value="1" {BL_SEPERATOR} /></span></td>
    </tr>
    <tr>
        <td class="row1" align="right" width="50%"><span class="gen">{L_BL_SEPERATOR_CONTENT}</span></td>
        <td class="row2" align="left" width="50%"><span class="gen"><input type="text" name="bl_seperator_content" size="50" maxlength="200" value="{BL_SEPERATOR_CONTENT}" /></span></td>
    </tr>
    <tr>
        <td class="row1" align="right" width="50%"><span class="gen">{L_BL_BREAK}</span></td>
        <td class="row2" align="left" width="50%"><span class="gen"><input type="text" name="bl_break" value="{BL_BREAK}" size="5" maxlength="5" /></span></td>
    </tr>
    <tr>
        <td class="catBottom" align="center" width="100%" colspan="2"><span class="mainoption">{U_CLOSE_WINDOW}</span></td>
    </tr>
</table>
</form>