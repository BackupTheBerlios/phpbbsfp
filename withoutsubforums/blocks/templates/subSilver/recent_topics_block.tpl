<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tr>
		<td class="row1" align="left"><span class="gensmall">
		<!-- BEGIN scroll -->
		<marquee id="recent_topics" behavior="scroll" direction="up" height="200" scrolldelay="100" scrollamount="2">
			<!-- BEGIN recent_topic_row -->
			&raquo; <a href="{scroll.recent_topic_row.U_TITLE}" onMouseOver="document.all.recent_topics.stop()" onMouseOut="document.all.recent_topics.start()"><b>{scroll.recent_topic_row.L_TITLE}</b></a><br />
			by <a href="{scroll.recent_topic_row.U_POSTER}" onMouseOver="document.all.recent_topics.stop()" onMouseOut="document.all.recent_topics.start()">{scroll.recent_topic_row.S_POSTER}</a> on {scroll.recent_topic_row.S_POSTTIME}<br /><br />
			<!-- END recent_topic_row -->
		</marquee>
		<!-- END scroll -->
		<!-- BEGIN static -->
			<!-- BEGIN recent_topic_row -->
			&raquo; <a href="{static.recent_topic_row.U_TITLE}"><b>{static.recent_topic_row.L_TITLE}</b></a><br />
			by <a href="{static.recent_topic_row.U_POSTER}">{static.recent_topic_row.S_POSTER}</a> on {static.recent_topic_row.S_POSTTIME}<br /><br />
			<!-- END recent_topic_row -->
		<!-- END static -->
		</span></td>
	</tr>
</table>