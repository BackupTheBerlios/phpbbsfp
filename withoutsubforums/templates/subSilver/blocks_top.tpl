<!-- $Id: blocks_top.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
        <!-- BEGIN top_blocks_row -->
            <table width="100%" cellpadding="3" cellspacing="1" border="0"

                <!-- BEGIN border -->
                class="forumline"
                <!-- END border -->
            >

                <!-- BEGIN title -->
                <tr>
                    <th>{top_blocks_row.title.TITLE}</th>
                </tr>
                <!-- END title -->
                <tr>
                    <td
                    <!-- BEGIN background -->
                    class="row1"
                    <!-- END background -->
                    >
                    {top_blocks_row.OUTPUT}</td>
                </tr>
            </table>
            <br />
        <!-- END top_blocks_row -->
        </td>
    </tr>
</table>