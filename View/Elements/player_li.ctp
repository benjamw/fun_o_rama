<?php

	$pl = $player;
	if (array_key_exists('Player', $pl)) {
		$pl = $pl['Player'];
	}

	$pr = false;
	if (array_key_exists('PlayerRanking', $player)) {
		$pr = $player['PlayerRanking'];
	}
	elseif (array_key_exists('PlayerRanking', $pl)) {
		$pr = $pl['PlayerRanking'];
	}

	$unique = my_ife($unique, substr(md5(uniqid(microtime(true), true)), 0, 5));
	$game_type_id = my_ife($game_type_id, 0);
	$link = my_ife($link, false);

?>

	<li id="pl_<?php echo $pl['id'].'_'.$unique; ?>"><?php
		if ($link) {
			echo $this->Html->link($pl['name'], array('controller' => 'players', 'action' => 'view', $pl['id']));
		}
		else {
			echo $pl['name'];
		}

		if ( ! empty($pr) && ! empty($game_type_id)) {
			foreach ($pr as $ranking) {
				if ($ranking['game_type_id'] === $game_type_id) {
					echo ' ('.number_format($ranking['mean'], 2).')';
					break;
				}
			}
		}
	?></li>

