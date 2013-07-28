<?php $this->Html->script('team_pop.js', array('block' => 'scriptBottom')); ?>

<?php

	if (empty($team) && ! empty($tourny)) {
		foreach ($tourny['Team'] as $team) {
			if ((int) $team['id'] === (int) $result['id']) {
				// $team is the result
				break;
			}
		}
	}

	$full_team = $team;
	if (array_key_exists('Team', $team)) {
		$team = $team['Team'];
	}

	if (array_key_exists('Player', $full_team)) {
		$team['Player'] = $full_team['Player'];
	}

	unset($full_team);

	$team_players = '<div class="player_popover">';

	foreach ($team['Player'] as $player) {
		if ( ! empty($player['avatar']['main'])) {
			$image = $this->Html->image($player['avatar']['main'], array('alt' => $player['name']));
		}
		else {
			$image = $this->Identicon->create($player['id']);
		}

		$team_players .= '<figure>'.$image.'<figcaption>'.$player['name'].'</figcaption></figure>';
	}

	$team_players .= '</div>';

	echo $this->Html->tag('span', $team['name'], array(
		'class' => 'teams',
//		'title' => implode(', ', Set::extract('/Player/name', $team)),
		'data-html' => 'true',
		'data-placement' => 'top',
		'data-trigger' => 'hover',
		'data-title' => str_replace(' ', '&nbsp', $team['name']),
		'data-content' => str_replace('"', "'", $team_players),
	));

?>

