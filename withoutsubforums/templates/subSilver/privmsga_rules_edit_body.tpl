<!-- $Id: privmsga_rules_edit_body.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<script language="Javascript" type="text/javascript">
//<![CDATA[
   function display_type(selected)
   {
      document.getElementById("group_display").style.display='none';
      document.getElementById("user_display").style.display='none';
      document.getElementById("sysuser_display").style.display='none';
      document.getElementById("word_display").style.display='none';
      if (selected==1)
      {
         document.getElementById("group_display").style.display='';
      }
      if (selected==2)
      {
         document.getElementById("user_display").style.display='';
      }
      if (selected==3)
      {
         document.getElementById("sysuser_display").style.display='';
      }
      if (selected==4)
      {
         document.getElementById("word_display").style.display='';
      }
   }
//]]>
</script>

<form name="post" method="post" action="{U_ACTION}">{EXTERNAL_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
    <td valign="top">{FOLDERS_BOX}</td>
    <td width="5"><span class="gensmall">&nbsp;</span></td>
    <td valign="top" width="100%">
        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="forumline">
        <tr>
            <th colspan="2">{L_TITLE}</th>
        </tr>
        <tr>
            <td class="row1" width="40%"><span class="gen">{L_NAME}</span><span class="gensmall"><br />{L_NAME_EXPLAIN}</span></td>
            <td class="row2" width="60%"><span class="gen">&nbsp;<input type="text" class="post" name="rules_name" value="{RULES_NAME}" size="45"/></span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_RFOLDER}</span><span class="gensmall"><br />{L_RFOLDER_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<select name="rules_folder_id">{S_FOLDERS}</select></span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_RULE_TYPE}</span><span class="gensmall"><br />{L_RULE_TYPE_EXPLAIN}</span></td>
            <td class="row2">
                <span class="genmed">
                    &nbsp;<input type="radio" name="rules_type" value="1" {RULES_TYPE_GROUP} onClick="javascript: display_type(1);" />{L_RULE_TYPE_GROUP}<br />
                    &nbsp;<input type="radio" name="rules_type" value="2" {RULES_TYPE_USER} onClick="javascript: display_type(2);" />{L_RULE_TYPE_USER}<br />
                    &nbsp;<input type="radio" name="rules_type" value="3" {RULES_TYPE_SYSUSER} onClick="javascript: display_type(3);" />{L_RULE_TYPE_SYSUSER}<br />
                    &nbsp;<input type="radio" name="rules_type" value="4" {RULES_TYPE_WORD} onClick="javascript: display_type(4);" />{L_RULE_TYPE_WORD}<br />
                </span>
            </td>
        </tr>
        <tbody id="group_display" style="display:{GROUP_DISPLAY}">
        <tr>
            <td class="cat" align="center" colspan="2"><span class="cattitle">{L_RULE_TYPE_GROUP}</span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_GROUP}</span><span class="gensmall"><br />{L_GROUP_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<select name="rules_group_id">{S_GROUPS}</select></span></td>
        </tr>
        </tbody>
        <tbody id="user_display" style="display:{USER_DISPLAY}">
        <tr>
            <td class="cat" align="center" colspan="2"><span class="cattitle">{L_RULE_TYPE_USER}</span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_USERNAME}</span><span class="gensmall"><br />{L_USERNAME_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<input type="text" class="post" name="username" maxlength="25" size="25" value="{RULES_USERNAME}" />&nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></span></td>
        </tr>
        </tbody>
        <tbody id="sysuser_display" style="display:{SYSUSER_DISPLAY}">
        <tr>
            <td class="cat" align="center" colspan="2"><span class="cattitle">{L_RULE_TYPE_SYSUSER}</span></td>
        </tr>
        <tr>
            <td class="row1" colspan="2" align="center"><span class="genmed">{L_SYSUSER_EXPLAIN}</span></td>
        </tr>
        </tbody>
        <tbody id="word_display" style="display:{WORD_DISPLAY}">
        <tr>
            <td class="cat" align="center" colspan="2"><span class="cattitle">{L_RULE_TYPE_WORD}</span></td>
        </tr>
        <tr>
            <td class="row1"><span class="gen">{L_WORD}</span><span class="gensmall"><br />{L_WORD_EXPLAIN}</span></td>
            <td class="row2"><span class="gen">&nbsp;<input type="text" class="post" name="rules_word" value="{RULES_WORD}" size="45"/></span></td>
        </tr>
        </tbody>
        <tr>
            <td class="catBottom" colspan="2" align="center" valign="middle">
                <span class="gen">
                    <input type="submit" name="submit_rules" value="{L_SUBMIT}" class="mainoption" />
                    <input type="submit" name="delete_rules" value="{L_DELETE}" class="liteoption" />
                    <input type="submit" name="cancel_rules" value="{L_CANCEL}" class="liteoption" />
                </span>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>{EXTERNAL_FOOTER}