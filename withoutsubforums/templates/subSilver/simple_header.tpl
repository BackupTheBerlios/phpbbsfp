<!-- $Id: simple_header.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
<!-- DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" -->
<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}"  />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META_TAG}
{META}
<title>{SITENAME} :: {PAGE_TITLE}</title>

<link rel="stylesheet" href="{TEMPLATE}{T_HEAD_STYLESHEET}" type="text/css">

<style type="text/css">
<!--
/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("{TEMPLATE}formIE.css");
-->
</style>

<script language="Javascript" type="text/javascript">
<!--

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
  document.write('<img src="' + imgSrc + 'nav_expand.gif" width="14" height="10" title="View More of this Code" onclick="resizeLayer(' + randomId + ', 200)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_expand_more.gif" width="14" height="10" title="View Even More of this Code" onclick="resizeLayer(' + randomId + ', 500)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_contract.gif" width="14" height="10" title="View Less of this Code" onclick="resizeLayer(' + randomId + ', 50)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_select_all.gif" width="14" height="10" title="Select All of this Code" onclick="selectAll(' + randomId + ')" onmouseover="this.style.cursor = \'pointer\'" /></div><div class="codediv" id="' + randomId + '">');
}
//-->
</script>

<!-- Prillian - Begin Code Additions -->
{IM_META}
{PREFS_TABS}
<link rel="stylesheet" href="templates/subSilver/prillian/layout.css" type="text/css">
<!-- BEGIN buddy_alert -->
<script language="Javascript" type="text/javascript">
    if ( {buddy_alert.BUDDY_ALERT} )
    {
        window.open('{buddy_alert.U_BUDDY_ALERT}', '_minervabuddy', 'HEIGHT=225,resizable=yes,WIDTH=400');
    }
</script>
<!-- END buddy_alert -->
<!-- Prillian - End Code Additions -->

</head>

<body {IM_RELOAD} bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">
<span class="gen"><a name="top"></a></span>
