<!-- $Id: rate_header.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<!--
# Advanced Users will probably want to customize their template table
# Below is a list of other items you can add between the <--! BEGIN ratingrow - -> and END of it
# to have display for each topic.
#
#	{ratingrow.CLASS} = an alternating variable for each topic that specifies row1 or row2
#	{ratingrow.RANK} = the topic's rank from one to max in admin panel
#	{ratingrow.LAST_RATER} = last person to rate this topc
#	{ratingrow.LAST_RATER_TIME} = the date/time the last person rated it
#	{ratingrow.FORUM} = the forum containing the topic
#	{ratingrow.RATING} = the topics average rating
#	{ratingrow.MIN} = miniumum rating given
#	{ratingrow.MAX} = maximum rating given
#	{ratingrow.L_VIEW_DETAILS} = if the admin option is on, this gives a little "View details" area link
#	{ratingrow.NUMBER_OF_RATES} = # of times the topic has been rated
-->

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr>
		<td class="catHead" colspan="2" height="28"><span class="cattitle">{L_TOP_RATED}</span></td>
	</tr>
	<!-- BEGIN hnotopics -->
		<td class="row1" align="left" valign="top"><span class="gensmall">{hnotopics.MESSAGE}</span></td>
	<!-- END hnotopics -->
	<!-- BEGIN hratingrow -->
	<tr>
		<td class="row1" align="left" valign="top"><span class="gensmall">{hratingrow.RATING} : <a href="{hratingrow.URL}">{hratingrow.TITLE}</a></span></td>
	</tr>
	<!-- END hratingrow -->
</table>