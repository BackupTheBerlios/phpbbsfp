<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META_TAG}
{META}
{NAV_LINKS}
<title>{SITENAME}<!-- IF PAGE_TITLE != '' --> :: {PAGE_TITLE}<!-- ENDIF --></title>
<link rel="stylesheet" href="{TEMPLATE}{T_HEAD_STYLESHEET}" type="text/css" />

<style type="text/css">
/*<![CDATA[*/
/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("{TEMPLATE}formIE.css");
/*]]>*/
</style>

<!-- IF S_USER_LOGGED_IN -->

	<!-- Prillian - Begin Code Additions -->

	<!-- BEGIN buddy_alert -->
	<script language="Javascript" type="text/javascript">
	//<![CDATA[
	    if ( {buddy_alert.BUDDY_ALERT} )
	    {
	        window.open('{buddy_alert.U_BUDDY_ALERT}', '_minervabuddy', 'HEIGHT=225,resizable=yes,WIDTH=400');
	    }
	//]]>
	</script>
	<!-- END buddy_alert -->

	<script language="JavaScript" type="text/javascript">
	//<![CDATA[
	    function prill_launch(url, w, h)
	    {
	        window.name = 'phpbbmain';
	        prillian = window.open(url, 'prillian', 'height=' + h + ', width=' + w + ', innerWidth=' + w + ', innerHeight=' + h + ', resizable, scrollbars');
	    }

	    if ( {IM_AUTO_POPUP} )
	    {
	        prill_launch('{U_IM_LAUNCH}', '{IM_WIDTH}', '{IM_HEIGHT}');
	    }

	//]]>
	</script>
	<!-- Prillian - End Code Additions -->

	<!-- IF S_PM_POPUP -->
	<script language="Javascript" type="text/javascript">
	//<![CDATA[
	    if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	    {
	        window.open('{U_PRIVATEMSGS_POPUP}', '_minervaprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	    }
	//]]>
	</script>
	<!-- ENDIF -->

	<!-- BEGIN birthday_popup -->
	<script language="Javascript" type="text/javascript">
	//<![CDATA[
	    window.open('{birthday_popup.U_BIRTHDAY_POPUP}', '_minervabirthday', 'HEIGHT=225,resizable=yes,WIDTH=400');
	//]]>
	</script>
	<!-- END birthday_popup -->

<!-- ENDIF -->

<script language="Javascript" type="text/javascript">
//<![CDATA[
function setCheckboxes(theForm, elementName, isChecked)
{
    var chkboxes = document.forms[theForm].elements[elementName];
    var count = chkboxes.length;

    if (count)
    {
        for (var i = 0; i < count; i++)
        {
            chkboxes[i].checked = isChecked;
        }
    }
    else
    {
        chkboxes.checked = isChecked;
    }

    return true;
}
//]]>
</script>

<script language="Javascript" type="text/javascript">
//<![CDATA[

function selectAll(elementId) {
  var element = document.getElementById(elementId);
  if ( document.selection ) {
    var range = document.body.createTextRange();
    range.moveToElementText(element);
    range.select();
  }
  if ( window.getSelection ) {
    var range = document.createRange();
    range.selectNodeContents(element);
    var blockSelection = window.getSelection();
    blockSelection.removeAllRanges();
    blockSelection.addRange(range);
  }
}

function resizeLayer(layerId, newHeight) {
  var myLayer = document.getElementById(layerId);
  myLayer.style.height = newHeight + 'px';
}

function codeDivStart() {
  var randomId = Math.floor(Math.random() * 2000);
  var imgSrc = '{TEMPLATE}images/';
  document.write('<img src="' + imgSrc + 'nav_expand.gif" width="14" height="10" title="View More of this Code" onclick="resizeLayer(' + randomId + ', 200)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_expand_more.gif" width="14" height="10" title="View Even More of this Code" onclick="resizeLayer(' + randomId + ', 500)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_contract.gif" width="14" height="10" title="View Less of this Code" onclick="resizeLayer(' + randomId + ', 50)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_select_all.gif" width="14" height="10" title="Select All of this Code" onclick="selectAll(' + randomId + ')" onmouseover="this.style.cursor = \'pointer\'" /><\/div><div class="codediv" id="' + randomId + '">');
}
//]]>
</script>

</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">
<!-- $Id: overall_header.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<a name="top"></a>

<table width="100%" cellspacing="0" cellpadding="6" border="0" align="center">
    <tr>
        <td class="bodyline">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>
                        <a href="{U_INDEX}"><img src="{TEMPLATE}images/logo_minerva.gif" alt="{L_INDEX}" vspace="1" /></a>
                    </td>
                    <td align="center" width="100%" valign="middle">
                        <span class="maintitle">{SITENAME}</span><br /><span class="gen">{SITE_DESCRIPTION}<br />&nbsp;</span>
                    </td>
                </tr>
            </table>
            <br />

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>

                    <!-- IF S_LEFT_BLOCKS -->
                        {LEFT_BLOCKS}
                    <!-- ENDIF -->

                    <td valign="top">

                        <!-- IF S_SITE_DISABLED -->
                        <div align="center"><span class="gen"><b>{L_BOARD_CURRENTLY_DISABLED}</b></span></div>
                        <br />
                        <!-- ENDIF -->

                        <!-- IF S_TOP_BLOCKS -->
                            {TOP_BLOCKS}
                        <!-- ENDIF -->