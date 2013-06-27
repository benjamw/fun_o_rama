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
		$players = $this->PlayerStat->Player->find('all', array(
			'contain' => array(
				'PlayerStat',
			),
		));
		$players = Set::combine($players, '/Player/id', '/');
g($players);

		foreach ($players as & $player) { // mind the reference
			$player['PlayerStat'] = Set::combine($player['PlayerStat'], '/game_id', '/');
		}
		unset($player); // kill the reference
g($players);

		$matches = $this->PlayerStat->Game->Match->find('all', array(
			'contain' => array(
				'Game',
				'Team' => array(
					'Player.id',
				),
			),
			'order' => array(
				'created' => 'ASC',
			),
		));
g($matches);

		foreach ($matches as $match) {
			if (empty($match['Team'])) {
				continue;
			}
// TODO: build this
		}

g($players);
		foreach ($players as $player) {
			foreach ($player['PlayerStat'] as $stats) {
				$data = array('PlayerStat' => $stats);
g($data);
				$this->PlayerStat->save($data);
			}
		}
	}

}

