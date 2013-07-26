<?php

App::uses('AppController', 'Controller');

class PlayerRankingsController extends AppController {

	const KILL_SWITCH = true;

	public $components = array('TrueSkill.TrueSkill');

	public function refresh_values( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('EMPTYING TABLE...');
		$this->PlayerRanking->query(
			'TRUNCATE TABLE `player_rankings`'
		);

		$rows = $this->PlayerRanking->find('count');
g('ROW COUNT = '.$rows);

g('STARTING');
		$this->fill_values( );
		$this->play_games( );
g('DONE');
	}

	public function fill_values( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('FILLING VALUES');
		$players = array_keys($this->PlayerRanking->Player->find('list'));
		$game_types = array_keys($this->PlayerRanking->GameType->find('list'));

		$default_mean = $this->TrueSkill->getDefaultMean( );
		$default_std_dev = $this->TrueSkill->getDefaultStandardDeviation( );

		foreach ($game_types as $game_type_id) {
			foreach ($players as $player_id) {
				$this->PlayerRanking->create( );
				$result = $this->PlayerRanking->save(array('PlayerRanking' => array(
					'player_id' => $player_id,
					'game_type_id' => $game_type_id,
					'mean' => $default_mean,
					'std_deviation' => $default_std_dev,
					'games_played' => 0,
				)));
g($result);
			}
		}
	}

	public function play_games( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('PLAYING GAMES');
		$matches = $this->PlayerRanking->Player->Team->Match->find('all', array(
			'conditions' => array(
				'winning_team_id IS NOT NULL',
			),
		));

		foreach ($matches as $match) {
g($match);
			$this->PlayerRanking->Player->Team->Match->update_rank($match['Match']['id']);
		}
	}

}

