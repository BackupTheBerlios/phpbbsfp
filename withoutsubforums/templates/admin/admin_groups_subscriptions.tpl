<!-- $Id: admin_groups_subscriptions.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
	<th class="thHead" colspan="9" height="25" valign="middle">{L_GROUP_SUBSCRIPTIONS}</th>
	</tr>
	<tr>
	<td class="row2" colspan="9"><span class="gensmall">{L_GROUP_SUBSCRIPTIONS_EXPLAIN}</span></td>
	</tr>
</table>
<br />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
	<th class="thHead" colspan="3" height="25" valign="middle">{L_GROUP_SUBSCRIPTIONS_PAYPAL}</th>
	</tr>
	<form method="post" name="post" action="{S_GROUP_SUBSCRIPTIONS_ACTION}">
<input type=hidden name="update_groups_subscriptions_paypal" value="true">
<tr>
	<td class="row1" align=center><span class="gensmall">{L_GROUP_SUBSCRIPTIONS_PAYPAL_EXPLAIN} <a href="https://www.paypal.com/us/mrb/pal=LD8CAZ4UCJUJG" target=blank><u>Click Here</u></a></span></td>
	<td class="row1" align=center><span class="gensmall"><input type="text" name ="paypal_email" value="{GROUP_SUBSCRIPTIONS_LIST_PAYPAL}"></span></td>
	<td class="row1" align=center><span class="gensmall"></span></td>
</tr>
<tr>
	<td class="row2" align=center><span class="gensmall">{L_GROUP_SUBSCRIPTIONS_PAYPAL_BG}</a></span></td>
	<td class="row2" align=center><span class="gensmall"><select name=paypal_bgcolor>
	<option value="{L_GROUP_SUBSCRIPTIONS_COLOR_CURRENT}" SELECTED>{L_GROUP_SUBSCRIPTIONS_COLOR_CURRENT}</option>
	<option value="W">{L_GROUP_SUBSCRIPTIONS_WHITE}</option>
	<option value="B">{L_GROUP_SUBSCRIPTIONS_BLACK}</option>
	</select></td>
	<td class="row2" align=center></td>
</tr>
<tr>
	<td class="row1" align=center><span class="gensmall">{L_GROUP_SUBSCRIPTIONS_CURRENCY_QUESTION}</a></span></td>
	<td class="row1" align=center><span class="gensmall"><select name=paypal_currency>
	<option value="{L_GROUP_SUBSCRIPTIONS_CURRENCY_CURRENT}" SELECTED>{L_GROUP_SUBSCRIPTIONS_CURRENCY_DISPLAY}</option>
	<option value="{L_GROUP_SUBSCRIPTIONS_CURRENCY_CURRENT}"></option>
	<option value="USD">{L_GROUP_SUBSCRIPTIONS_CURRENCY_USD}</option>
	<option value="GBP">{L_GROUP_SUBSCRIPTIONS_CURRENCY_GBP}</option>
	<option value="EUR">{L_GROUP_SUBSCRIPTIONS_CURRENCY_EUR}</option>
	<option value="CAD">{L_GROUP_SUBSCRIPTIONS_CURRENCY_CAD}</option>
	<option value="JPY">{L_GROUP_SUBSCRIPTIONS_CURRENCY_JPY}</option>
	</select></td>
	<td class="row1" align=center><span class="gensmall"><input type="submit" value="{L_GROUP_SUBSCRIPTIONS_FIELD_6}"></span></td>
</tr>
</form>
</table>
<br />
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
	<th class="thHead" colspan="6" height="25" valign="middle">{L_GROUP_SUBSCRIPTIONS_SETTINGS}</th>
	</tr>
	<tr>
	<td class="row3" colspan="6"><span class="gensmall">
	{L_GROUP_SUBSCRIPTIONS_EXPLAIN_SETTINGS}
	</span></td>
	</tr>
<tr>
	<td class="row1" align=center width=0 nowrap><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_1}</b></span></td>
	<td class="row1" align=center><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_2}</b></span></td>
	<td class="row1" align=center nowrap><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_3}</b></span></td>
	<td class="row1" align=center><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_4}</b></span></td>
	<td class="row1" align=center><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_5}</b></span></td>
	<td class="row1" align=center><span class="gensmall"><b>{L_GROUP_SUBSCRIPTIONS_FIELD_6}</b></span></td>
</tr>
<form action="">
<tr>
	<td class="row2" align=center><span class="gensmall"><i>{L_GROUP_SUBSCRIPTIONS_DEMO_NAME}</i></span></td>
	<td class="row2"><span class="gensmall"><i>{L_GROUP_SUBSCRIPTIONS_DEMO_DESCRIPTION}</span></td>
	<td class="row2" align=center><span class="gensmall"><input name="" type="text" disabled="true" size=5 value="5.00"></span></td>
	<td class="row2" align=center nowrap><span class="gensmall"><SELECT name="p2" disabled="true">
	<OPTION>--</OPTION>
	<OPTION VALUE="1">1</OPTION>
	<OPTION VALUE="2" selected>2</OPTION>
	<OPTION VALUE="3">3</OPTION>
	<OPTION VALUE="4">4</OPTION>
	<OPTION VALUE="5">5</OPTION>
	<OPTION VALUE="6">6</OPTION>
	<OPTION VALUE="7">7</OPTION>
	<OPTION VALUE="8">8</OPTION>
	<OPTION VALUE="9">9</OPTION>
	<OPTION VALUE="10">10</OPTION>
	<OPTION VALUE="11">11</OPTION>
	<OPTION VALUE="12">12</OPTION>
	<OPTION VALUE="13">13</OPTION>
	<OPTION VALUE="14">14</OPTION>
	<OPTION VALUE="15">15</OPTION>
	<OPTION VALUE="16">16</OPTION>
	<OPTION VALUE="17">17</OPTION>
	<OPTION VALUE="18">18</OPTION>
	<OPTION VALUE="19">19</OPTION>
	<OPTION VALUE="20">20</OPTION>
	<OPTION VALUE="21">21</OPTION>
	<OPTION VALUE="22">22</OPTION>
	<OPTION VALUE="23">23</OPTION>
	<OPTION VALUE="24">24</OPTION>
	<OPTION VALUE="25">25</OPTION>
	<OPTION VALUE="26">26</OPTION>
	<OPTION VALUE="27">27</OPTION>
	<OPTION VALUE="28">28</OPTION>
	<OPTION VALUE="29">29</OPTION>
	<OPTION VALUE="30">30</OPTION>
</SELECT> <select name="t2" disabled="true">
	<option value="D">{L_GROUP_DAYS}</option>
	<option value="W" SELECTED>{L_GROUP_WEEKS}</option>
	<option value="M">{L_GROUP_MONTHS}</option>
	<option value="Y">{L_GROUP_YEARS}</option>
</select></td>
	<td class="row2" align=center><span class="gensmall"><input name="" type="checkbox" disabled="true" value="" checked></span></td>
	<td class="row2" align=center><span class="gensmall"><input name="" type="button" disabled="true" value="{L_GROUP_SUBSCRIPTIONS_FIELD_6}"></span></td>
</tr>
</form>
	<!-- BEGIN groups -->
	<form method="post" name="post" action="{S_GROUP_SUBSCRIPTIONS_ACTION}">
	<input type=hidden name="update_groups_subscriptions" value="true">
	<input type=hidden name="group_id" value="{groups.GROUP_LIST_ID}">
	<tr>
<td class="{groups.ROW_CLASS}" align=center><span class="gensmall">{groups.GROUP_NAME}</span></td>
<td class="{groups.ROW_CLASS}"><span class="gensmall">{groups.GROUP_DESCRIPTION}</span></td>
<td class="{groups.ROW_CLASS}" align=center><input name="subscription_cost" type="text" size=5 value="{groups.SUBSCRIPTION_COST}"></td>
<td class="{groups.ROW_CLASS}" align=center><SELECT name="p2">
	<OPTION VALUE="{groups.GROUP_P2}">{groups.GROUP_P2}</OPTION>
	<OPTION VALUE="1">1</OPTION>
	<OPTION VALUE="2">2</OPTION>
	<OPTION VALUE="3">3</OPTION>
	<OPTION VALUE="4">4</OPTION>
	<OPTION VALUE="5">5</OPTION>
	<OPTION VALUE="6">6</OPTION>
	<OPTION VALUE="7">7</OPTION>
	<OPTION VALUE="8">8</OPTION>
	<OPTION VALUE="9">9</OPTION>
	<OPTION VALUE="10">10</OPTION>
	<OPTION VALUE="11">11</OPTION>
	<OPTION VALUE="12">12</OPTION>
	<OPTION VALUE="13">13</OPTION>
	<OPTION VALUE="14">14</OPTION>
	<OPTION VALUE="15">15</OPTION>
	<OPTION VALUE="16">16</OPTION>
	<OPTION VALUE="17">17</OPTION>
	<OPTION VALUE="18">18</OPTION>
	<OPTION VALUE="19">19</OPTION>
	<OPTION VALUE="20">20</OPTION>
	<OPTION VALUE="21">21</OPTION>
	<OPTION VALUE="22">22</OPTION>
	<OPTION VALUE="23">23</OPTION>
	<OPTION VALUE="24">24</OPTION>
	<OPTION VALUE="25">25</OPTION>
	<OPTION VALUE="26">26</OPTION>
	<OPTION VALUE="27">27</OPTION>
	<OPTION VALUE="28">28</OPTION>
	<OPTION VALUE="29">29</OPTION>
	<OPTION VALUE="30">30</OPTION>
</SELECT> <select name="t2">
	<option value="{groups.GROUP_T2}">{groups.GROUP_T2_DISPLAY}</option>
	<option value="D">{groups.GROUP_DAYS}</option>
	<option value="W">{groups.GROUP_WEEKS}</option>
	<option value="M">{groups.GROUP_MONTHS}</option>
	<option value="Y">{groups.GROUP_YEARS}</option>
</select></td>
<td class="{groups.ROW_CLASS}" align=center><input name="subscription_enabled" type="checkbox" value="checked" {groups.SUBSCRIPTION_ENABLED}></td>
<td class="{groups.ROW_CLASS}" align=center><input name="" type="submit" value="{L_GROUP_SUBSCRIPTIONS_FIELD_6}"></td>
	</tr>
</form>
	<!-- END groups -->
</table>
<br />
