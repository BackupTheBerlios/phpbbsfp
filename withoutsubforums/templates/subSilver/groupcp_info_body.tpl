<!-- $Id: groupcp_info_body.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->
<!-- IF S_IS_PAYPAL_GROUP -->
	<!-- TESTER http://www.eliteweaver.co.uk/cgi-bin/webscr -->
	<form action="http://paypal.com/cgi-bin/webscr" method="post" target="_blank">
<!-- ELSE -->
	<form action="{S_GROUPCP_ACTION}" method="post">
<!-- ENDIF -->

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr>
        <td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
    </tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
    <tr>
        <th class="thHead" colspan="7">{L_GROUP_INFORMATION}</th>
    </tr>
    <tr>
        <td class="row1" width="20%"><span class="gen">{L_GROUP_NAME}:</span></td>
        <td class="row2"><span class="gen"><b>{GROUP_NAME}</b></span></td>
    </tr>
    <tr>
        <td class="row1" width="20%"><span class="gen">{L_GROUP_DESC}:</span></td>
        <td class="row2"><span class="gen">{GROUP_DESC}</span></td>
    </tr>
	<tr>
        <td class="row1" width="20%"><span class="gen">{L_GROUP_MEMBERSHIP}:</span></td>

        <td class="row2"><span class="gen">

        {GROUP_DETAILS}

		<!-- IF S_SUBSCRIBE -->
			<!-- IF S_IS_PAYPAL_GROUP -->
	            <input type="hidden" name="item_number" value="{GROUP_ID}" />
	            <input type="hidden" name="item_name" value="{GROUP_NAME} Group" />
	            <input type="hidden" name="cmd" value="_xclick-subscriptions" />
	            <input type="hidden" name="business" value="{PAYPAL_EMAIL}" />
	            <input type="hidden" name="no_shipping" value="1" />
	            <input type="hidden" name="return" value="{PAYPAL_RETURN_URL}" />
	            <input type="hidden" name="notify_url" value="{PAYPAL_NOTIFY_URL}" />
	            <input type="hidden" name="no_note" value="1" />
	            <input type="hidden" name="currency_code" value="{PAYPAL_CURRENCY}" />
	            <input type="hidden" name="a3" value="{PAYPAL_COST}" />
	            <input type="hidden" name="on0" value="{L_GROUP_NAME}" />
	            <input type="hidden" name="os0" value="{GROUP_NAME}" />
	            <input type="hidden" name="on1" value="{L_GROUP_SUBSCRIPTIONS_USER_FOR_USER_ID}" />
	            <input type="hidden" name="os1" value="{PAYPAL_USER_ID}" />
	            <input type="hidden" name="p3" value="{PAYPAL_LENGTH}" />
	            <input type="hidden" name="t3" value="{PAYPAL_UNIT}" />
	            {PAYPAL_BG_COLOR}
	            <input type="hidden" name="src" value="1" />
	            <input type="hidden" name="sra" value="1" />
	            <input class="mainoption" type="submit" name="joingroup" value="PayPal Subscribe" />
			<!-- ELSE -->
	        	<input class="mainoption" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
			<!-- ENDIF -->
		<!-- ELSEIF S_UNSUBSCRIBE -->
			<!-- IF S_IS_PAYPAL_GROUP --> 
				<input type=hidden name="cmd" value="_subscr-find">
	            <input type=hidden name="alias" value="{GROUP_DETAILS_PAYPAL_UNSUBSCRIBE}">
	            <input type=submit value="Unsubscribe PayPal" class="mainoption"><br />
	            {L_GROUP_SUBSCRIPTIONS_WARNING}
			<!-- ELSE -->
        		<input class="mainoption" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
			<!-- ENDIF -->
        <!-- ENDIF -->
        </span></td>
    </tr>
    <!-- BEGIN switch_mod_option -->
    <tr>
        <td class="row1" width="20%"><span class="gen">{L_GROUP_TYPE}:</span></td>
        <td class="row2"><span class="gen"><span class="gen"><input type="radio" name="group_type" value="{S_GROUP_OPEN_TYPE}" {S_GROUP_OPEN_CHECKED} /> {L_GROUP_OPEN} &nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_CLOSED_TYPE}" {S_GROUP_CLOSED_CHECKED} /> {L_GROUP_CLOSED} &nbsp;&nbsp;<input type="radio" name="group_type" value="{S_GROUP_HIDDEN_TYPE}" {S_GROUP_HIDDEN_CHECKED} />    {L_GROUP_HIDDEN} &nbsp;&nbsp;  <input type="radio" name="group_type" value="{S_GROUP_AUTO_TYPE}" {S_GROUP_AUTO_CHECKED} /> {L_GROUP_AUTO} &nbsp;&nbsp;<input class="mainoption" type="submit" name="groupstatus" value="{L_UPDATE}" /></span></td>
    </tr>
    <!-- END switch_mod_option -->
</table>

{S_HIDDEN_FIELDS}

</form>

<form action="{S_GROUPCP_ACTION}" method="post" name="post">
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
    <tr>
      <th class="thCornerL">{L_PM}</th>
      <th class="thTop">{L_USERNAME}</th>
      <th class="thTop">{L_POSTS}</th>
      <th class="thTop">{L_FROM}</th>
      <th class="thTop">{L_EMAIL}</th>
      <th class="thTop">{L_WEBSITE}</th>
      <th class="thCornerR">{L_SELECT}</th>
    </tr>
    <tr>
      <td class="catSides" colspan="8"><span class="cattitle">{L_GROUP_OWNER}</span></td>
    </tr>
    <tr>
      <td class="row1" align="center"> {MOD_PM_IMG} </td>
      <td class="row1" align="center"><span class="gen"><a href="{U_MOD_VIEWPROFILE}" class="gen">{MOD_USERNAME}</a></span></td>
      <td class="row1" align="center" valign="middle"><span class="gen">{MOD_POSTS}</span></td>
      <td class="row1" align="center" valign="middle"><span class="gen">{MOD_FROM}</span></td>
      <td class="row1" align="center" valign="middle"><span class="gen">{MOD_EMAIL_IMG}</span></td>
      <td class="row1" align="center">{MOD_WWW_IMG}</td>
      <td class="row1" align="center"> &nbsp; </td>
    </tr>

    <!-- BEGIN member_type -->
    <tr>
      <td class="catSides" colspan="8"><span class="cattitle">{member_type.L_TYPE}</span></td>
    </tr>
    <!-- BEGIN member_row -->
    <tr>
      <td class="{member_type.member_row.ROW_CLASS}" align="center"> {member_type.member_row.PM_IMG} </td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center"><span class="gen"><a href="{member_type.member_row.U_VIEWPROFILE}" class="gen">{member_type.member_row.USERNAME}</a></span></td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center"><span class="gen">{member_type.member_row.POSTS}</span></td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center"><span class="gen"> {member_type.member_row.FROM} </span></td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{member_type.member_row.EMAIL_IMG}</span></td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center"> {member_type.member_row.WWW_IMG}</td>
      <td class="{member_type.member_row.ROW_CLASS}" align="center">
      <!-- BEGIN switch_mod_option -->
      <input type="checkbox" name="members[]" value="{member_type.member_row.USER_ID}" />
      <!-- END switch_mod_option -->
      </td>
    </tr>
    <!-- END member_row -->
    <!-- END member_type -->

    <!-- BEGIN switch_no_members -->
    <tr>
      <td class="row1" colspan="7" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
    </tr>
    <!-- END switch_no_members -->

    <!-- BEGIN switch_hidden_group -->
    <tr>
      <td class="row1" colspan="7" align="center"><span class="gen">{L_HIDDEN_MEMBERS}</span></td>
    </tr>
    <!-- END switch_hidden_group -->

    <!-- BEGIN switch_mod_option -->
    <tr>
        <td class="catBottom" colspan="8" align="right"><span class="cattitle">
            <input type="submit" name="remove" value="{L_REMOVE_SELECTED}" class="mainoption" />
            <!-- BEGIN switch_owner_option -->
            <input type="submit" name="grant_ungrant" value="{L_GRANT_UNGRANT_SELECTED}" class="liteoption" />
            <!-- END switch_owner_option -->
        </td>
    </tr>
    <!-- END switch_mod_option -->
</table>

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
    <tr>
        <td align="left" valign="top">
        <!-- BEGIN switch_mod_option -->
        <span class="genmed"><input type="text" class="post" name="username" maxlength="50" size="20" /> <input type="submit" name="add" value="{L_ADD_MEMBER}" class="mainoption" /> <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_minervasearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></span><br /><br />
        <!-- END switch_mod_option -->
        <span class="nav">{PAGE_NUMBER}</span></td>
        <td align="right" valign="top"><span class="nav">{PAGINATION}</span></td>
    </tr>
</table>

{PENDING_USER_BOX}

{S_HIDDEN_FIELDS}</form>