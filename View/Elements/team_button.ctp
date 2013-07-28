<?php $this->Html->script('team_pop.js', array('block' => 'scriptBottom')); ?>

<?php

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

	$seed = (( ! empty($team['start_seed'])) ? ' (#'.$team['start_seed'].')' : ' (Team '.($team_num + 1).')');

	echo $this->Form->button($team['name'].$seed, array(
		'type' => 'button',
		'class' => 'btn btn-mini btn-success teams',
		'id' => 'res_'.$match_id.'_'.$team['id'],
//		'title' => implode(', ', Set::extract('/Player/name', $team)),
		'data-html' => 'true',
		'data-placement' => 'top',
		'data-trigger' => 'hover',
		'data-title' => str_replace(' ', '&nbsp', $team['name'].$seed),
		'data-content' => str_replace('"', "'", $team_players),
	));

?>

