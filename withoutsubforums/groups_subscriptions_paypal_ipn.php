<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: groups_subscriptions_paypal_ipn.php,v 1.1 2004/08/30 21:30:05 dmaj007 Exp $
//
// FILENAME  : groups_subscriptions_paypal_ipn.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team
//		     : © 2001, 2003 The phpBB Group
//           : © 2004       Marcus Cicero and Damien A
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'),	1);
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

//
// Start session management
//
//$userdata = session_pagestart($user_ip, PAGE_GROUPCP);
//init_userprefs($userdata);
//
// End session management
//

$current_time =	time();

// We will now authenticate	the	transaction	beyond PayPals basic authentication.
// Lets	match the group	id with	the	total the user paid.
// Just	added: We will also	match the subscription period so the member	cant
// turn	his	once-a-week	billing	period into	a once-a-year billing period.
// If the user didnt pay what they were	supposed to, their membership will not be updated.
// They	must contact the admin because it was their	fault for trying to	cheat the system!!

// Set our flag. If	the	items do not match the database	then they are probably cheating!
// Select all groups names

$params = array('txn_type' => 'type' , 'item_number', 'mc_amount3' => 'amount', 'period3' => 'period', 'option_selection2' => 'paypal_user');

foreach ($params as $param => $var_name)
{
	$param = ( $param == '' ) ? $var_name : $param;

	if ( isset($_GET[$param]) )
	{
		$$var_name = $_GET[$param];
	}
	elseif ( isset($_POST[$param]) )
	{
		$$var_name = $_POST[$param];
	}
	else
	{
		$$var_name = '';
	}
}

$item_number = 3;
$amount = 0.01;
$period = 20;
$paypal_user = 3;
$type = 'subscr_payment';

if ( $paypal_user !== $userdata['user_id'] )
{
	// User that payed and the current user is not correct!
	//die;
}


$sql = 'SELECT * FROM (' . GROUPS_TABLE . ' g
	LEFT JOIN ' . GROUPS_SUBSCRIPTIONS_TABLE . ' gs ON gs.subscription_id = g.subscription_id)
	WHERE g.group_id = "' . $item_number . '"
	AND gs.subscription_cost = "' . $amount . '"
	AND gs.subscription_length = "' . $period . '"';


if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain group information', '', __LINE__, __FILE__, $sql);
}

$is_authed = FALSE;

// Loop	through	groups
while( $row	= $db->sql_fetchrow($result) )
{
	$is_authed = TRUE;
}

// Lets	bypass the security	flag for people	trying to unsubscribe or make a	payment.
// We dont need	to manually	auth them because PayPal already has.
// Plus	we need	to let them	pass the auth incase we	update the cost	of the subscription.
// If they tried to	make a payment or cancel and the subscription cost has changed since they
// subscribed, they	will not pass the flags.  This way we will only	auth them on signup.

if ($txn_type == 'subscr_cancel' || $txn_type == 'subscr_failed' || $txn_type == 'subscr_eot' || $txn_type == 'subscr_payment' || $txn_type == 'subscr_modify')
{
	$is_authed = TRUE;
}
else
{
	$is_authed = FALSE;
}
die;


if ( !$is_authed )
{
	// Lets	keep track of people trying	to cheat the system!!


	// Now that	we have	all	their info,	lets log them.
	$sql = "INSERT INTO `{$table_prefix}groups_subscriptions_log`
	(
	`groups_transaction_id`,
	`groups_purchase_date`,
	`groups_paid`,
	`groups_id_number`,
	`groups_user_ip`,
	`groups_phpbb_id`,
	`groups_phpbb_username`,
	`groups_gross_amount`,
	`groups_action`
	) VALUES (
	'',
	'{$current_time}',
	'0',
	'{$item_number}',
	'{$user_ip}',
	'{$paypal_user}',
	'''',
	'$amount',
	'CHEATER'
	);";
	$result	= $db->sql_query($sql);

	message_die(CRITICAL_ERROR,	'Quit trying to	cheat the system. Your username	"<b><font color=red>'.''.'</font></b>" and IP "<b><font color=red>'.$user_ip.'</font></b>" have been recorded.');
}


// IPN validation modes, choose: 1 or 2

$postmode=1;

		   //* 1 = Live	Via	PayPal Network
		   //* 2 = Test	Via	EliteWeaver	UK



// Debugger, 1 = on	and	0 =	off

$debugger=1;


// No ipn post means this script does not exist

if (!$type])
{
	@header("Status: 404 Not Found");
	exit;
}
else
{
	@header("Status: 200 OK");	// Prevents	ipn	reposts	on some	servers
}


// Add "cmd" to	prepare	for	post back validation
// Read	the	ipn	post from paypal or	eliteweaver	uk
// Fix issue with php magic	quotes enabled on gpc
// Apply variable antidote (replaces array filter)
// Destroy the original	ipn	post (security reason)
// Reconstruct the ipn string ready	for	the	post


	$postipn = 'cmd=_notify-validate'; // Notify validate

	foreach	($_POST	as $ipnkey => $ipnval)
	{
		if (get_magic_quotes_gpc())
		{
			$ipnval = stripslashes ($ipnval); // Fix issue with magic quotes
		}

		if (!eregi("^[_0-9a-z-]{1,30}$", $ipnkey) || !strcasecmp ($ipnkey, 'cmd'))
		{
			// ^ Antidote to potential variable injection and poisoning.
			unset ($ipnkey);
			unset ($ipnval);
		}	// Eliminate the above

		if ($ipnkey != '')
		{
			// Remove empty keys (not values)
			$_PAYPAL[$ipnkey] = $ipnval; // Assign	data to	new	global array
			$_POST = array(); // Destroy the original ipn post array, sniff...
			$postipn.='&'.$ipnkey.'='.urlencode($ipnval);
		}
	} // Notify string

	$error=0; // No	errors let's hope it's going to	stays like this!



// IPN validation mode 1: Live Via PayPal Network

	if ($postmode == 1)
	{
		$domain	= "www.paypal.com";
	}

// IPN validation mode 2: Test Via EliteWeaver UK

	elseif ($postmode == 2)
	{
		$domain	= "www.eliteweaver.co.uk";
	}

// IPN validation mode was not set to 1	or 2

	else
	{
		$error=1;
		$bmode=1;
		if ($debugger)
		{
			debugInfo();
		}
	}


	@set_time_limit(60); //	Attempt	to double default time limit incase	we switch to Get



// Post	back the reconstructed instant payment notification

		$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
		$script_name = (( $script_name != ''	) ?	$script_name . '/' : '') . basename(__FILE__);
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure']	) ?	'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80	) ?	':'	. trim($board_config['server_port']) . '/' : '/';
		$server_url	= $server_protocol . $server_name .	$server_port . $script_name . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');

		$socket	= @fsockopen($domain, 80, $errno, $errstr, 30);
		$header	= "POST	/cgi-bin/webscr	HTTP/1.0\r\n";
		$header.= 'User-Agent: PHP/'.phpversion()."\r\n";
		$header.= 'Referer:	'.$server_url."\r\n";
		$header.= "Server: ".$_SERVER['SERVER_SOFTWARE']."\r\n";
		$header.= "Host: ".$domain.":80\r\n";
		$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header.= "Content-Length: ".strlen($postipn)."\r\n";
		$header.= "Accept: */*\r\n\r\n";

//* Note: "Connection: Close" is not required using HTTP/1.0



// Problem: Now is this your firewall or your ports?

		if (!$socket &&	!$error)
		{

// Switch to a Get request for a last ditch attempt!

			$getrq=1;

			if (phpversion() >=	'4.3.0' && function_exists('file_get_contents'))
			{
				// Checking for a new function
			}
			else
			{
				// No? We'll create it instead

				function file_get_contents($ipnget)
				{
					$ipnget	= @file($ipnget);
					return $ipnget[0];
				}
			}

			$response = @file_get_contents('http://'.$domain.':80/cgi-bin/webscr?'.$postipn);

			if (!$response)
			{
				$error = 1;
				$getrq = 0;

				if ($debugger)
				{
					debugInfo();

				}
				// If this is as far as you get then you need a new web host!
			}

			// If no problems have occured then	we proceed with	the	processing

		else
		{
			@fputs ($socket,$header.$postipn."\r\n\r\n"); // Required on some environments
			while (!feof($socket))
			{
				$response =	fgets ($socket,1024);
			}
		}

		$response =	trim ($response); // Also required on some environments



// uncomment '#' to	assign posted variables	to local variables
#extract($_PAYPAL);	// if globals is on	they are already local

// and/or >>>

// refer to	each ipn variable by reference (recommended)
// $_PAYPAL['receiver_id'];	etc... (see: ipnvars.txt)



// IPN was confirmed as	both genuine and VERIFIED

if (!strcmp	($response,	"VERIFIED"))
{

/////////////////////////////////////////////////////////////////////////////////////////
// Lets	get	the	party started!!
/////////////////////////////////////////////////////////////////////////////////////////



// Begin body code
// Lets	execute!


// Begin IF	payment_status
if(variableAudit('txn_type','subscr_signup'))
{
	$sql = "SELECT username	FROM ' . USERS_TABLE . '
		 WHERE user_id = '{$option_selection2}'";

	$result	= $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$subscription_cop =	$row['username'];

	// Now that	we have	all	their info,	lets log them when they	subscribe.
	$sql=" INSERT INTO `{$table_prefix}groups_subscriptions_log`
	(
	`groups_transaction_id`,
	`groups_purchase_date`,
	`groups_paid`,
	`groups_id_number`,
	`groups_user_ip`,
	`groups_phpbb_id`,
	`groups_phpbb_username`,
	`groups_gross_amount`,
	`groups_action`
	)
	VALUES (
	'',
	'{$current_time}',
	'1',
	'{$item_number}',
	'PayPal',
	'{$option_selection2}',
	'{$subscription_cop}',
	'$mc_amount3',
	'$txn_type'
	);";
	$result	= $db->sql_query($sql);


	// Now that	we logged them and they	completed the signup, lets now add them	to a group!
	$sql=" INSERT INTO `{$table_prefix}user_group`
	(
	`group_id`,
	`user_id`,
	`user_pending`
	)
	VALUES (
	'{$item_number}',
	'{$option_selection2}',
	'0'
	);";
	$result	= $db->sql_query($sql);


}
// Lets	log	them if	they made a	payment	or modified	their subscription
elseif(variableAudit('txn_type','subscr_payment') || variableAudit('txn_type','subscr_modify'))
{
	$sql = "SELECT username
			FROM {$table_prefix}users WHERE	user_id	= '{$option_selection2}'";
	$result	= $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
$subscription_cop =	''.$row['username'].'';

// Now that	we have	all	their info,	lets log them when they	cancel.
$sql=" INSERT INTO `{$table_prefix}groups_subscriptions_log`
(
`groups_transaction_id`,
`groups_purchase_date`,
`groups_paid`,
`groups_id_number`,
`groups_user_ip`,
`groups_phpbb_id`,
`groups_phpbb_username`,
`groups_gross_amount`,
`groups_action`
)
VALUES (
'',
'{$current_time}',
'1',
'{$item_number}',
'PayPal',
'{$option_selection2}',
'{$subscription_cop}',
'$mc_amount3',
'$txn_type'
);";
	$result	= $db->sql_query($sql);



// Since they canceled,	we need	to remove them from	the	group right?
$sql="DELETE FROM {$table_prefix}user_group
WHERE group_id = '{$item_number}' AND user_id =	'{$option_selection2}'";
	$result	= $db->sql_query($sql);


}
elseif(variableAudit('txn_type','subscr_cancel') ||	variableAudit('txn_type','subscr_failed') || variableAudit('txn_type','subscr_eot'))
{
	$sql = "SELECT username
			FROM {$table_prefix}users WHERE	user_id	= '{$option_selection2}'";
	$result	= $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
$subscription_cop =	''.$row['username'].'';

// Now that	we have	all	their info,	lets log them when they	cancel.
$sql=" INSERT INTO `{$table_prefix}groups_subscriptions_log`
(
`groups_transaction_id`,
`groups_purchase_date`,
`groups_paid`,
`groups_id_number`,
`groups_user_ip`,
`groups_phpbb_id`,
`groups_phpbb_username`,
`groups_gross_amount`,
`groups_action`
)
VALUES (
'',
'{$current_time}',
'1',
'{$item_number}',
'PayPal',
'{$option_selection2}',
'{$subscription_cop}',
'$mc_amount3',
'$txn_type'
);";
	$result	= $db->sql_query($sql);



// Since they canceled,	we need	to remove them from	the	group right?
$sql="DELETE FROM {$table_prefix}user_group
WHERE group_id = '{$item_number}' AND user_id =	'{$option_selection2}'";
	$result	= $db->sql_query($sql);


}
else
{
//dont do anything
}
// End IF payment_status






// Well?  The users	points should have been	updated	by now.
// End body	code



////////////////////////



// Check that the "payment_status" variable	is:	Completed
// If it is	Pending	you	may	want to	inform your	customer?
// Check your db to	ensure this	"txn_id" is	not	a duplicate
// You may want	to check "payment_gross" or	"mc_gross" matches listed prices?
// You definately want to check	the	"receiver_email", "receiver_id"	or "business" is yours
// Update your db and process this payment accordingly

//***************************************************************//
//*	Tip: Use the internal auditing function	to do some of this!	*//
//*	**************************************************************************************//
//*	Help: if(variableAudit('mc_gross','0.01') &&										 *//
//*			 variableAudit('receiver_email','paypal@domain.com') &&						 *//
//*			 variableAudit('payment_status','Completed')){ $do_this; } else	{ do_that; } *//
//****************************************************************************************//
			}



// IPN was not validated as	genuine	and	is INVALID

	elseif (!strcmp	($response,	"INVALID"))
	{

// Check your code for any post	back validation	problems
// Investigate the fact	that this could	be a spoofed IPN
// If updating your	db,	ensure this	"txn_id" is	not	a duplicate
			}



	else
	{ // Just incase something serious should happen!
			}}

	if ($debugger) debugInfo();



#########################################################
#	  Inernal Functions	: variableAudit	& debugInfo		#
#########################################################



// Function: variableAudit
// Easy	LOCAL to IPN variable comparison
// Returns 1 for match or 0	for	mismatch

function variableAudit($v,$c)
{
	global	$_PAYPAL;
	if (!strcasecmp($_PAYPAL[$v],$c))
	{ return 1;	} else { return	0; }
}



// Function: debugInfo
// Displays	debug info
// Set $debugger to	1

function debugInfo()
{
	global	$_PAYPAL,
		$postmode,
		$socket,
		$error,
		$postipn,
		$getrq,
		$response;

		$ipnc =	strlen($postipn)-21;
		$ipnv =	count($_PAYPAL)+1;

	@flush();
	@header('Cache-control:	private'."\r\n");
	@header('Content-Type: text/plain'."\r\n");
	@header('Content-Disposition: inline; filename=debug.txt'."\r\n");
	@header('Content-transfer-encoding:	ascii'."\r\n");
	@header('Pragma: no-cache'."\r\n");
	@header('Expires: 0'."\r\n\r\n");
	echo '#########################################################'."\r\n";
	echo '#	<--	PayPal IPN Variable	Output & Status	Debugger! --> #'."\r\n";
	echo '#########################################################'."\r\n\r\n";
	if (phpversion() >=	'4.3.0'	&& $socket)
	{
	echo 'Socket Status: '."\r\n\r\n";
	print_r	(socket_get_status($socket));
	echo "\r\n\r\n"; }
	echo 'PayPal IPN: '."\r\n\r\n";
	print_r($_PAYPAL);
	echo "\r\n\r\n".'Validation	String:	'."\r\n\r\n".wordwrap($postipn,	64,	"\r\n",	1);
	echo "\r\n\r\n\r\n".'Validation	Info: '."\r\n";
	echo "\r\n\t".'PayPal IPN String Length	Incoming =>	'.$ipnc."\r\n";
	echo "\t".'PayPal IPN String Length	Outgoing =>	'.strlen($postipn)."\r\n";
	echo "\t".'PayPal IPN Variable Count Incoming => ';
	print_r(count($_PAYPAL));
	echo "\r\n\t".'PayPal IPN Variable Count Outgoing => '.$ipnv."\r\n";
	if ($postmode == 1)
	{
	echo "\r\n\t".'IPN Validation Mode => Live -> PayPal, Inc.'; }
	elseif ($postmode == 2)
	{
	echo "\r\n\t".'IPN Validation Mode => Test -> EliteWeaver.'; }
	else
	{
	echo "\r\n\t".'IPN Validation Mode => Incorrect	Mode Set!';	}
	echo "\r\n\r\n\t\t".'IPN Validate Response => '.$response;
	if (!$getrq	&& !$error)
	{
	echo "\r\n\t\t".'IPN Validate Method =>	POST (success)'."\r\n\r\n";	}
	elseif ($getrq && !$error)
	{
	echo "\r\n\t\t".'IPN Validate Method =>	GET	(success)'."\r\n\r\n"; }
	elseif ($bmode)
	{
	echo "\r\n\t\t".'IPN Validate Method =>	NONE (stupid)'."\r\n\r\n"; }
	elseif ($error)
	{
	echo "\r\n\t\t".'IPN Validate Method =>	BOTH (failed)'."\r\n\r\n"; }
	else
	{
	echo "\r\n\t\t".'IPN Validate Method =>	BOTH (unknown)'."\r\n\r\n";	}
	echo '#########################################################'."\r\n";
	echo '#	   THIS	SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE!	  #'."\r\n";
	echo '#########################################################'."\r\n\r\n";
	@flush();

}


// Terminate the socket	connection (if open) and exit

	@fclose	($socket); exit;

?>