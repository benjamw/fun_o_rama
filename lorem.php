<?php

// lorem generator

$type = v($_GET['type'], 'basic');

switch ($type) {
	case 'mptt' :
		$min = (int) v($_GET['mn_i'], 3);
		$max = (int) v($_GET['mx_i'], 5);
		$lvl = (int) v($_GET['lvl'], 4);
		$st_id = (int) v($_GET['st_id'], 1);
		$table = v($_GET['tb'], 'TABLENAME');

		$response = make_mptt($min, $max, $lvl, $st_id);
		echo queryize($response, $table);

		break;

	case 'teams' :
		make_teams( );
		break;

	case 'basic' :
	default :
		$table = v($_GET['tb'], 'TABLENAME');
		$func = 'make_'.$type;

		$response = $func( );
		echo queryize($response, $table);
}


// ------ FUNCTIONS -------------------------

function queryize($data, $table = 'TABLENAME') {
	$output = '';

	if ( ! $data) {
		return 'NO DATA GIVEN';
	}

	foreach ($data as $row) {
		$fields = '`'.implode('`, `', array_keys($row)).'`';
		$values = valueize($row);
		$output .= "INSERT INTO `{$table}` ({$fields}) VALUES ({$values}) ;\n";
	}

	return $output;
}

function valueize($data) {
	foreach ($data as & $value) { // careful with the reference
		$value = addslashes($value);
	}
	unset($value); // kill the reference

	return '"'.implode('", "', $data).'"';
}

function v( & $var, $default = null) {
	return (isset($var) ? $var : $default);
}

function ve( & $var, $default = null) {
	return ( ! empty($var) ? $var : $default);
}

function make_mptt($min, $max, $lvl = 1, $start_id = 1, $parent_name = '', $parent_id = 0, $parent_lft = 0) {
debug('=====================================');
debug(__FUNCTION__);
debug($min);
debug($max);
debug($lvl);
debug($start_id);
debug($parent_name);
debug($parent_id);
debug($parent_lft);
	$min = (int) $min;
	$max = (int) $max;
	$lvl = (int) $lvl;
	$start_id = (int) $start_id;
	$parent_id = (int) $parent_id;
	$parent_lft = (int) $parent_lft;

	$orig_start_id = $start_id;

	$mptt = array( );

	if (0 >= $lvl) {
		return $mptt;
	}

	if ($max < $min) {
		$max ^= $min;
		$min ^= $max;
		$max ^= $min;
	}

	$num = mt_rand($min, $max);

	$i = 0;
	$name = 'Z';
	for ($num; $num > 0; $num -= 1) {
		$name++;

		$lft = $parent_lft + 1 + (2 * $i);
		if (isset($parent) && is_array($parent)) {
//			$lft = $parent['rght'] + 1;
		}

		$parent = array(
			'id' => $start_id,
			'image_id' => 1,
			'user_id' => ($i + 1) * 10,
			'comment' => (empty($parent_name) ? $name : $parent_name.'-'.$name),
			'comment_id' => $parent_id,
//			'lft' => $lft,
//			'rght' => 'X',
//			'sort' => ($i + 1) * 10,
			'created' => date('Y-m-d H:i:s'),
		);
debug($parent);

		$children = make_mptt($min, $max, $lvl - 1, $start_id + 1, $parent['comment'], $parent['id']/*, $parent['lft']*/);
debug($children);

//		$parent['rght'] = $parent['lft'] + (count($children) * 2) + 1;

		$mptt = array_merge($mptt, array($parent), $children);
debug($mptt);

		$start_id = $orig_start_id + count($mptt);
		$i += 1;
	}

	return $mptt;
}

function make_basic( ) {
debug('=====================================');
debug(__FUNCTION__);
debug($_GET);

	$rows = 100;
	foreach ($_GET as $key => $value) {
		if ('type' === $key) {
			continue;
		}

		if ('tb' === $key) {
			continue;
		}

		if ('rows' === $key) {
			$rows = $value;
			continue;
		}

		$fields[$key] = $value;
	}

	if (empty($fields)) {
		return false;
	}

	$data = array( );
	for ($i = 0; $i < $rows; ++$i) {
		$datum = array( );
		foreach ($fields as $key => $value) {
			$datum[$key] = parse_value($value);
		}

		$data[] = $datum;
	}

	return $data;
}

function make_teams( ) {
	for ($i = 1; $i <= 100; ++$i) {
		$match = $team = $player_team = array( );

		$match['game_id'] = parse_value('r:1-13');
		$match['date'] = parse_value('dt:rand');

		$team_size = mt_rand(2, 5);
		for ($j = ($i * 2) - 1; $j <= ($i * 2); ++$j) {
			$team = array( );

			$team['match_id'] = $i;
			echo queryize(array($team), 'teams');

			$used = array( );
			for ($k = 0; $k < $team_size; ++$k) {
				$player_team = array( );

				do {
					$player_id = mt_rand(1, 11);
				} while (in_array($player_id, $used));
				$used[] = $player_id;

				$player_team['player_id'] = $player_id;
				$player_team['team_id'] = $j;
				echo queryize(array($player_team), 'players_teams');
			}
		}

		$match['winning_team_id'] = (mt_rand(0, 1) ? (mt_rand(0, 1) ? ($i * 2) - 1 : ($i * 2)) : 0);
		echo queryize(array($match), 'matches');
	}
}

$per_count = 0;
$last_per = false;
function parse_value($value) {
	global $per_count, $last_per;

	if (false === strpos($value, ':')) {
		return $value;
	}

	$value = explode(':', $value);
	switch (strtolower($value[0])) {
		case 'random' : // no break
		case 'rand' : // no break
		case 'rnd' : // no break
		case 'r' :
			if (false !== strpos($value[1], ',')) {
				$return = explode(',', $value[1]);
				shuffle($return);
				shuffle($return);
				shuffle($return);
				return reset($return);
			}
			elseif (false !== strpos($value[1], '-')) {
				$value[1] = explode('-', $value[1]);
				return mt_rand($value[1][0], $value[1][1]);
			}
			else {
				// not sure...
			}
			break;

/*
		case 'per' :
			$per = $value[1];

			if (false !== strpos($value[2], ',')) {
				$return = explode(',', $value[2]);
				return reset($return);
			}
			elseif (false !== strpos($value[2], '-')) {
				$value[2] = explode('-', $value[2]);
				return mt_rand($value[2][0], $value[2][1]);
			}
			else {
				return $value[2];
			}
			break;
*/

		case 'name' :
			$words = 2;
			if ( ! empty($value[1])) {
				$words = $value[1];
			}

			return create_name($words);
			break;

		case 'username' : // no break
		case 'user' : // no break
		case 'un' : // no break
			return create_name(1, false);
			break;

		case 'datetime' : // no break
		case 'dt' :
			if ('now' === strtolower($value[1])) {
				return date('Y-m-d H:i:s');
			}
			elseif ('rand' === strtolower($value[1])) {
				$date = time( ) - mt_rand(0, 31556926); // between now and 1 year ago
				return date('Y-m-d H:i:s', $date);
			}
			else {
				return date($value[1]);
			}
			break;

		case 'date' : // no break
		case 'd' :
			if ('now' === strtolower($value[1])) {
				return date('Y-m-d');
			}
			elseif ('rand' === strtolower($value[1])) {
				$date = time( ) - mt_rand(0, 31556926); // between now and 1 year ago
				return date('Y-m-d', $date);
			}
			else {
				return date($value[1]);
			}
			break;

		default :
			// not sure...
			break;
	}

	return $value;
}

function create_name($words = 2, $capitalized = true) {
	$consonants = str_split('bcdfghjklmnpqrstvwxz');
	$vowels = str_split('aeiouy');

	$names = array( );
	for ($i = 0; $i < (int) $words; ++$i) {
		$name = '';
		for ($j = 0; $j < mt_rand(3, 8); ++$j) {
			$letters = ( 0 === (int) floor( ( ( $j + 1 ) % 3 ) / 2 ) ) ? $consonants : $vowels; // 1, 0, 1, 1, 0, 1, 1, 0, ...
			shuffle($letters);

			$name .= $letters[0];
		}

		$names[] = $name;
	}

	$name = implode(' ', $names);

	if ($capitalized) {
		$name = ucwords($name);
	}

	return $name;
}

function debug($var) {
	if (1) {
		echo '<pre>'; print_r($var); echo '</pre>';
	}
}

