<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: admin_spellcheck.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : admin_spellcheck.php
// STARTED   : Sat Jun 3, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//           : © 2003, 2004	Nathan Anderson
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !empty($setmodules) )
{
        $filename = basename(__FILE__);
        $module['General']['Spell_Check'] = $filename;
        return;
}
if (!isset($phpEx)) $phpEx = "php";
?><html>
<head>
</head>
<body bgcolor="#E5E5E5" text="#000000">
<script language="javascript">
<!--
document.location.href="../spelling/spell_admin<?php echo ".".$phpEx; ?>"
//-->
</script>
</body>
</html>
