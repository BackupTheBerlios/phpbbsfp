<!-- $Id: faq_body.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr>
        <td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
    </tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
    <!-- MOD: Custom FAQs Page Links -->
    <!-- BEGIN other_faqs -->
    <tr>
        <th class="thHead">{other_faqs.L_OTHER_FAQ_TITLE}</th>
    <tr>
        <td class="bodyline">
            <!-- BEGIN other_faqs_link -->
            {other_faqs.other_faqs_link.DIVIDER}<a href="{other_faqs.other_faqs_link.U_OTHER_FAQ}" class="nav">{other_faqs.other_faqs_link.OTHER_FAQ_NAME}</a>
            <!-- END other_faqs_link -->
        </td>
    </tr>
    </tr>
    <!-- END other_faqs -->
    <!-- MOD: -END- -->

    <tr>
        <th class="thHead">{L_FAQ_TITLE}</th>
    </tr>
    <tr>
        <td class="row1">
            <!-- BEGIN faq_block_link -->
            <span class="gen"><b>{faq_block_link.BLOCK_TITLE}</b></span><br />
            <!-- BEGIN faq_row_link -->
            <span class="gen"><a href="{faq_block_link.faq_row_link.U_FAQ_LINK}" class="postlink">{faq_block_link.faq_row_link.FAQ_LINK}</a></span><br />
            <!-- END faq_row_link -->
            <br />
            <!-- END faq_block_link -->
        </td>
    </tr>
    <tr>
        <td class="catBottom">&nbsp;</td>
    </tr>
</table>

<br clear="all" />

<!-- BEGIN faq_block -->
<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
    <tr>
        <td class="catHead" align="center"><span class="cattitle">{faq_block.BLOCK_TITLE}</span></td>
    </tr>
    <!-- BEGIN faq_row -->
    <tr>
        <td class="{faq_block.faq_row.ROW_CLASS}" align="left" valign="top"><span class="postbody"><a name="{faq_block.faq_row.U_FAQ_ID}"></a><b>{faq_block.faq_row.FAQ_QUESTION}</b></span><br /><span class="postbody">{faq_block.faq_row.FAQ_ANSWER}<br /><a class="postlink" href="#Top">{L_BACK_TO_TOP}</a></span></td>
    </tr>
    <tr>
        <td class="spaceRow" height="1"><img src="{TEMPLATE}images/spacer.gif" alt="" width="1" height="1" /></td>
    </tr>
    <!-- END faq_row -->
</table>

<br clear="all" />
<!-- END faq_block -->