<!-- $Id: blocks_left.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<td width="{LEFT_WIDTH}" valign="top">
    <!-- BEGIN left_blocks_row -->
    <table width="100%" cellpadding="3" cellspacing="1" border="0"

        <!-- BEGIN border -->
        class="forumline"
        <!-- END border -->
    >

        <!-- BEGIN title -->
        <tr>
            <th>{left_blocks_row.title.TITLE}</th>
        </tr>
        <!-- END title -->

        <tr>
            <td
            <!-- BEGIN background -->
            class="row1"
            <!-- END background -->
            >
            {left_blocks_row.OUTPUT}</td>
        </tr>
    </table>
    <br />
    <!-- END left_blocks_row -->
</td>

<td width="8"><img src="{TEMPLATE}images/spacer.gif" alt="" width="8" height="16" /></td>