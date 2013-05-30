<?php

$debug = false;

if (empty($_REQUEST['cont'])) {
	exit;
}

$_R = array( );
foreach ($_REQUEST as $key => $value) {
	$_R[$key] = addslashes($value);
}

// load in the database config
require_once '../config/database.php';
$db = new DATABASE_CONFIG;

$query = "
	SELECT `name`
	FROM `{$_R['cont']}`
	ORDER BY `name`
";

$results = null;
if ('mysqli' == $db->default['driver']) {
	if ( ! ($conn = mysqli_connect($db->default['host'], $db->default['login'], $db->default['password'], $db->default['database']))) {
		if ($debug) {
			echo "Connection Error: ({$mysqli->connect_errno}) {$mysql->connect_error}";
		}

		exit;
	}

	if (mysqli_real_query($conn, $query) && ($result = mysqli_use_result($conn))) {
		$results = array( );

		while ($row = mysqli_fetch_row($result)) {
			$results[] = $row[0];
		}
	}

	mysqli_free_result($result);
	mysqli_close($conn);
}
else { // run normal mysql
	if ( ! ($conn = mysql_connect($db->default['host'], $db->default['login'], $db->default['password'], ! $db->default['persistent']))) {
		if ($debug) {
			echo "Connection Error: ({$mysqli->connect_errno}) {$mysql->connect_error}";
		}

		exit;
	}

	mysql_select_db($db->default['database']);

	if ($result = mysql_query($query)) {
		$results = array( );

		while ($row = mysql_fetch_row($result)) {
			$results[] = $row[0];
		}
	}

	mysql_free_result($result);
	mysql_close($conn);
}

echo 'var '.$_R['cont'].'_list = '.json_encode($results).';';

