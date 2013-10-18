<?php

	$team_players = array( );
	foreach ($team['Player'] as $player) {
		$team_players[] = $player['name'];
	}

	$seed = (( ! empty($team['start_seed'])) ? ' (#'.$team['start_seed'].')' : ' (Team '.($team_num + 1).')');

	echo $this->Form->button($team['name'].' ['.implode(', ', $team_players).']'.$seed, array(
		'type' => 'button',
		'class' => 'btn btn-mini btn-success teams',
		'id' => 'res_'.$match_id.'_'.$team['id'],
//		'title' => implode(', ', Set::extract('/Player/name', $team)),
	));

?>

