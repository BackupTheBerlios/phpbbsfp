<!-- $Id: blocks_right.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<td width="8"><img src="{TEMPLATE}images/spacer.gif" alt="" width="8" height="16" /></td>

<td width="{RIGHT_WIDTH}" valign="top">
    <!-- BEGIN right_blocks_row -->
    <table width="100%" cellpadding="3" cellspacing="1" border="0"

        <!-- BEGIN border -->
        class="forumline"
        <!-- END border -->
    >

        <!-- BEGIN title -->
        <tr>
            <th>{right_blocks_row.title.TITLE}</th>
        </tr>
        <!-- END title -->
        <tr>
            <td
            <!-- BEGIN background -->
            class="row1"
            <!-- END background -->
            >
            {right_blocks_row.OUTPUT}</td>
        </tr>
    </table>
    <br />
    <!-- END right_blocks_row -->
</td>