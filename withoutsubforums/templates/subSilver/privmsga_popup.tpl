<!-- $Id: privmsga_popup.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language="javascript" type="text/javascript">
//<![CDATA[
function jump_to_inbox()
{
    opener.document.location.href = "{U_PRIVATEMSGS}";
    window.close();
}
//]]>
</script>

  <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="1" cellpadding="4" class="forumline">
          <tr>
            <td valign="top" class="row1" align="center"><br /><span class="gen">{L_MESSAGE}</span><br /><br /><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span><br /><br /></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
