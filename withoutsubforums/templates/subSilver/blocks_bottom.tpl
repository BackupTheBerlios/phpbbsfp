<!-- $Id: blocks_bottom.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
        <!-- BEGIN bottom_blocks_row -->
            <table width="100%" cellpadding="3" cellspacing="1" border="0"

                <!-- BEGIN border -->
                class="forumline"
                <!-- END border -->
            >

                <!-- BEGIN title -->
                <tr>
                    <th>{bottom_blocks_row.title.TITLE}</th>
                </tr>
                <!-- END title -->

                <tr>
                    <td
                    <!-- BEGIN background -->
                    class="row1"
                    <!-- END background -->
                    >
                    {bottom_blocks_row.OUTPUT}</td>
                </tr>
            </table>
            <br />
        <!-- END bottom_blocks_row -->
        </td>
    </tr>
</table>