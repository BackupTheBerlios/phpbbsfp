<?php

echo "<PRE>";
echo "--------------------\n";
echo "   NTLM Auth Check\n";
echo "--------------------\n\n";
echo "NTLM Type: " . $_SERVER['AUTH_TYPE'] . "\n";
if (ntlm_check())
{
	echo "NTLM Auth detect: OK\n";
	echo "NTLM User: ";
	$ntlm_user = $_SERVER['REMOTE_USER'];
	$Username = ntlm_get_user();
	if (strpos($ntlm_user,"\\") > 0 && strlen($Username) > 0)
		echo "OK";

	else
		echo "FAIL";
	echo " ( " . $ntlm_user . " ) " . $Username . "\n";
}
else
{
	echo "NTLM Auth detect: FAIL\n";
}

echo "NTLM Check DONE.\n";
echo "</pre>";

function ntlm_check() {
	global $board_config;
	if (isset($_SERVER['AUTH_TYPE']) && ($_SERVER['AUTH_TYPE'] == 'NTLM' || $_SERVER['AUTH_TYPE'] == 'Negotiate'))
	{
		return true;
	}
	else {
		return false;
	}
}

function ntlm_get_user() {
	if (ntlm_check())
	{
		$ntlm_user = $_SERVER['REMOTE_USER'];
		$strloc = strpos($ntlm_user,"\\");
		$strloc++;
		$strloc++;
		if ($strloc > 2)
			$username = substr($ntlm_user,$strloc);
		else
			$username = false;
		return $username;
	}
	else
		return false;
}

?>