<?php

App::uses('AppController', 'Controller');

class PlayerStatsController extends AppController {

	const KILL_SWITCH = true;

	public function refresh_values( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('EMPTYING TABLE...');
		$this->PlayerStat->query(
			'TRUNCATE TABLE `player_stats`'
		);

		$rows = $this->PlayerStat->find('count');
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
		$players = array_keys($this->PlayerStat->Player->find('list'));
		$games = array_keys($this->PlayerStat->Game->find('list'));

		foreach ($games as $game_id) {
			foreach ($players as $player_id) {
				$this->PlayerStat->create( );
				$result = $this->PlayerStat->save(array('PlayerStat' => array(
					'player_id' => $player_id,
					'game_id' => $game_id,
					'wins' => 0,
					'draws' => 0,
					'losses' => 0,
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
		$matches = $this->PlayerStat->Player->Team->Match->find('all', array(
			'conditions' => array(
				'winning_team_id IS NOT NULL',
			),
		));

		foreach ($matches as $match) {
g($match);
			$this->PlayerStat->Player->Team->Match->update_stats($match['Match']['id']);
		}
	}

}

