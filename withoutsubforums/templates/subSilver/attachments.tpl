<!-- $Id: attachments.tpl,v 1.1 2004/08/30 21:30:08 dmaj007 Exp $ -->

	<!-- Attachmod CSS is not in here yet. -->

<!-- BEGIN attachrow -->

	<!-- IF attachrow.L_DENIED != '' -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" class="attachheader" align="center"><b><span class="gen">{attachrow.L_DENIED}</span></b></td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>

    <!-- ELSEIF attachrow.TYPE == 'stream' -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{attachrow.DOWNLOAD_NAME}</span></b></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_DESCRIPTION}:</span></td>
	        <td width="75%" class="attachrow">
	            <table width="100%" border="0" cellpadding="0" cellspacing="4" align="center">
	            <tr>
	                <td class="attachrow"><span class="genmed">{attachrow.COMMENT}</span></td>
	            </tr>
	            </table>
	        </td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILESIZE}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.FILESIZE} {attachrow.SIZE_VAR}</td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOAD_COUNT}</span></td>
	    </tr>
	    <tr>
	        <td colspan="2" align="center"><br />
	        <object id="wmp" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
	        <param name="FileName" value="{attachrow.U_DOWNLOAD_LINK}">
	        <param name="ShowControls" value="1">
	        <param name="ShowDisplay" value="0">
	        <param name="ShowStatusBar" value="1">
	        <param name="AutoSize" value="1">
	        <param name="AutoStart" value="0">
	        <param name="Visible" value="1">
	        <param name="AnimationStart" value="0">
	        <param name="Loop" value="0">
	        <embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="{attachrow.U_DOWNLOAD_LINK}" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 autostart=0 visible=1 animationatstart=0 loop=0></embed>
	        </object> <br /><br />
	        </td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>

    <!-- ELSEIF attachrow.TYPE == 'swf' -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{attachrow.DOWNLOAD_NAME}</span></b></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_DESCRIPTION}:</span></td>
	        <td width="75%" class="attachrow">
	            <table width="100%" border="0" cellpadding="0" cellspacing="4" align="center">
	            <tr>
	                <td class="attachrow"><span class="genmed">{attachrow.COMMENT}</span></td>
	            </tr>
	            </table>
	        </td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILESIZE}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.FILESIZE} {attachrow.SIZE_VAR}</td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOAD_COUNT}</span></td>
	    </tr>
	    <tr>
	        <td colspan="2" align="center"><br />
	        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="{attachrow.WIDTH}" height="{attachrow.HEIGHT}">
	        <param name=movie value="{attachrow.U_DOWNLOAD_LINK}">
	        <param name=loop value=1>
	        <param name=quality value=high>
	        <param name=scale value=noborder>
	        <param name=wmode value=transparent>
	        <param name=bgcolor value=#000000>
	        <embed src="{attachrow.U_DOWNLOAD_LINK}" loop=1 quality=high scale=noborder wmode=transparent bgcolor=#000000  width="{attachrow.WIDTH}" height="{attachrow.HEIGHT}" type="application/x-shockwave-flash" pluginspace="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
	        </object><br /><br />
	        </td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>

	<!-- ELSEIF attachrow.TYPE == 'image' -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{attachrow.DOWNLOAD_NAME}</span></b></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_DESCRIPTION}:</span></td>
	        <td width="75%" class="attachrow">
	            <table width="100%" border="0" cellpadding="0" cellspacing="4" align="center">
	            <tr>
	                <td class="attachrow"><span class="genmed">{attachrow.COMMENT}</span></td>
	            </tr>
	            </table>
	        </td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILESIZE}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.FILESIZE} {attachrow.SIZE_VAR}</td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOAD_COUNT}</span></td>
	    </tr>
	    <tr>
	        <td colspan="2" align="center"><br /><img src="{attachrow.IMG_SRC}" alt="{attachrow.DOWNLOAD_NAME}" border="0" /><br /><br /></td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>

	<!-- ELSEIF attachrow.TYPE == 'thumbnail' -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" colspan="2" class="attachheader" align="center"><b><span class="gen">{attachrow.DOWNLOAD_NAME}</span></b></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_DESCRIPTION}:</span></td>
	        <td width="75%" class="attachrow">
	            <table width="100%" border="0" cellpadding="0" cellspacing="4" align="center">
	            <tr>
	                <td class="attachrow"><span class="genmed">{attachrow.COMMENT}</span></td>
	            </tr>
	            </table>
	        </td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILESIZE}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.FILESIZE} {attachrow.SIZE_VAR}</td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOAD_COUNT}</span></td>
	    </tr>
	    <tr>
	        <td colspan="2" align="center"><br /><a href="{attachrow.IMG_SRC}" target="_blank"><img src="{attachrow.IMG_THUMB_SRC}" alt="{attachrow.DOWNLOAD_NAME}" border="0" /></a><br /><br /></td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>
	
    <!-- ELSE -->

	    <div align="center"><hr width="95%" /></div>
	    <table width="95%" border="1" cellpadding="2" cellspacing="0" class="attachtable" align="center">
	    <tr>
	        <td width="100%" colspan="3" class="attachheader" align="center"><b><span class="gen">{attachrow.DOWNLOAD_NAME}</span></b></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_DESCRIPTION}:</span></td>
	        <td width="75%" class="attachrow">
	            <table width="100%" border="0" cellpadding="0" cellspacing="4" align="center">
	            <tr>
	                <td class="attachrow"><span class="genmed">{attachrow.COMMENT}</span></td>
	            </tr>
	            </table>
	        </td>
	        <td rowspan="4" align="center" width="10%" class="attachrow">{attachrow.S_UPLOAD_IMAGE}<br /><a href="{attachrow.U_DOWNLOAD_LINK}" {attachrow.TARGET_BLANK} class="genmed"><b>{L_DOWNLOAD}</b></a></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILENAME}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.DOWNLOAD_NAME}</span></td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{L_FILESIZE}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.FILESIZE} {attachrow.SIZE_VAR}</td>
	    </tr>
	    <tr>
	        <td width="15%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	        <td width="75%" class="attachrow"><span class="genmed">&nbsp;{attachrow.L_DOWNLOAD_COUNT}</span></td>
	    </tr>
	    </table>
	    <div align="center"><hr width="95%" /></div>

	<!-- ENDIF -->

<!-- END attachrow -->

	