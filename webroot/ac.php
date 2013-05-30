<?php

$debug = false;

if (empty($_REQUEST['term']) || empty($_REQUEST['cont'])) {
	exit;
}

$_R = array( );
foreach ($_REQUEST as $key => $value) {
	$_R[$key] = addslashes($value);
}

// load in the database config
require_once '../config/bootstrap.php';
require_once '../config/database.php';
$db = new DATABASE_CONFIG;

switch ($_REQUEST['cont']) {
	case 'searches' :
		$term = explode(',', $_R['term']);
		array_trim($term);

		$AND = '';
		if ( ! empty($term[1])) {
			$AND = "
				AND `State` LIKE '{$term[1]}%'
			";
		}

		$query = "
			SELECT DISTINCT CONCAT(`CityAliasMixedCase`, ', ', `State`) AS `name`
			FROM `zips`
			WHERE `CityAliasName` LIKE '%{$term[0]}%'
				{$AND}
			ORDER BY `CityAliasName` LIKE '{$term[0]}%' DESC
				, `CityAliasName` ASC
			LIMIT 30
		";
		break;

	default :
		$AND = '';
		if ( ! empty($_R['state'])) {
			$AND = "
				AND (
					`state_id` = '".(int) $_R['state']."'
					OR `state_id` = '0'
					OR `state_id` IS NULL
				)
			";
		}

		$query = "
			SELECT `name`
			FROM `{$_R['cont']}`
			WHERE `name` LIKE '%{$_R['term']}%'
				{$AND}
			ORDER BY `name` LIKE '{$_R['term']}%' DESC
				, `name` ASC
			LIMIT 10
		";
		break;
}

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

echo json_encode($results);

