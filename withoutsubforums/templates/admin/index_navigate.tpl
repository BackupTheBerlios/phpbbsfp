<!-- $Id: index_navigate.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center">
	<tr>
    	<td align="center" ><a href="{U_FORUM_INDEX}" target="_parent"><img src="{TEMPLATE}images/logo_minerva_med.gif" /></a></td>
  	</tr>
  	<tr>
    	<td align="center" >
    		<form name="open_close" method="get" action="index.{PHP}?pane=left&amp;open_close={OPEN_CLOSE}">
      		<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
        		<tr>
          			<th class="thHead"><b>{L_ADMIN}</b></th>
        		</tr>
        		<tr>
          			<td class="row1"><span class="genmed"><a href="{U_ADMIN_INDEX}" target="main" class="genmed">{L_ADMIN_INDEX}</a></span></td>
        		</tr>
        		<tr>
          			<td class="row1"><span class="genmed"><a href="{U_SITE_INDEX}" target="_parent" class="genmed">{L_SITE_INDEX}</a></span></td>
        		</tr>
        		<tr>
          			<td class="row1"><span class="genmed"><a href="{U_FORUM_INDEX}" target="_parent" class="genmed">{L_FORUM_INDEX}</a></span></td>
        		</tr>
        		<tr>
          			<td class="row1"><span class="genmed"><a href="{U_FORUM_INDEX}" target="main" class="genmed">{L_PREVIEW_FORUM}</a></span></td>
        		</tr>
        		<!-- BEGIN catrow -->
        		<tr>
          			<td class="catSides"><span class="genmed"><input type=hidden name=open_close value={OPEN_CLOSE}>{catrow.ADMIN_CATEGORY}</span></td>
        		</tr>
        		<!-- BEGIN modulerow -->
        		<tr>
          			<td class="row1"><span class="genmed"><a href="{catrow.modulerow.U_ADMIN_MODULE}"  target="main" class="genmed">{catrow.modulerow.ADMIN_MODULE}</a></span></td>
        		</tr>
        		<!-- END modulerow -->
        		<!-- END catrow -->
      		</table>
      		</form>
    	</td>
 	</tr>
</table>
<br />