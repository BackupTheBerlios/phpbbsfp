<!-- $Id: modules_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<h1>{L_MODULES_TITLE}</h1>

<p>{L_MODULES_EXPLAIN}</p>


<table width="100%" cellpadding="6" cellspacing="1" border="0" class="forumline">
    <tr>
        <th class="thCornerL" align="center" nowrap="nowrap">{L_MODULE_ID}</th>
        <th class="thTop" align="center" nowrap="nowrap">{L_MODULE_NAME}</th>
        <th class="thTop" align="center" nowrap="nowrap">{L_MODULE_STATE}</th>
        <th class="thCornerR" align="center" nowrap="nowrap">{L_MODULE_COMMAND}</th>
    </tr>
    <!-- BEGIN modules -->
    <tr>
        <td class="row2" align="center">
            <span class="gen">{modules.ID}</span>
        </td>
        <td class="row1" align="left">
            <span class="gen"><a href="{modules.U_NAME}" target="_new">{modules.L_NAME}</a></span> <span class="gensmall">({modules.VERSION})</span><br/>
            <span class="gensmall">{modules.DESCRIPTION}</span>
        </td>
        <td class="row2" align="center">
            <span class="genmed">{modules.STATE}</span>
        </td>
        <td class="row1" align="center" nowrap="nowrap">
            <span class="gen"><a href="{modules.U_COMMAND1}">{modules.L_COMMAND1}</a><br/>
            <a href="{modules.U_COMMAND2}">{modules.L_COMMAND2}</a><br/>
            <a href="{modules.U_COMMAND3}">{modules.L_COMMAND3}</a></span>
        </td>
    </tr>
    <!-- END modules -->
{SCRIPT}
</table>

<br />
