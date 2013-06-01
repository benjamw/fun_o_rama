<?php

App::uses('AppController', 'Controller');

class PlayerRankingsController extends AppController {

	const KILL_SWITCH = false;

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
		$players = $this->PlayerRanking->Player->find('all', array(
			'contain' => array(
				'PlayerRanking',
			),
		));
		$players = Set::combine($players, '/Player/id', '/');

		foreach ($players as & $player) { // mind the reference
			$player['PlayerRanking'] = Set::combine($player['PlayerRanking'], '/game_type_id', '/');
		}
		unset($player); // kill the reference

		$matches = $this->PlayerRanking->GameType->Game->Match->find('all', array(
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

		foreach ($matches as $match) {
			if (empty($match['Team'])) {
				continue;
			}

			$teams = $team_ids = array( );

			$i = 1;

			// convert to a format that is usable by the plugin
			foreach ($match['Team'] as $match_team) {
				if (empty($match_team['Player'])) {
					continue 2;
				}

				$team_ids[] = $match_team['id'];

				$team = array( );
				foreach ($match_team['Player'] as $player) {
					if (empty($players[$player['id']]['PlayerRanking'])) {
						continue 3;
					}

					foreach ($players[$player['id']]['PlayerRanking'] as $ranking) {
						if ((int) $match['Game']['game_type_id'] === (int) $ranking['game_type_id']) {
							// $ranking has the data we need
							break;
						}
					}

					$team[] = array(
						'id' => $player['id'],
						'mean' => $ranking['mean'],
						'std_dev' => $ranking['std_deviation'],
					);
				}

				$teams[] = $team;

				if ((int) $match_team['id'] === (int) $match['Match']['winning_team_id']) {
					$outcome = $i;
				}

				++$i;
			}
g($teams);

			$new_rankings = $this->TrueSkill->updatePlayerRankings($teams, $outcome);
g($new_rankings);

			foreach ($new_rankings as $player) {
				$players[$player['id']]['PlayerRanking'][$match['Game']['game_type_id']]['mean'] = $player['mean'];
				$players[$player['id']]['PlayerRanking'][$match['Game']['game_type_id']]['std_deviation'] = $player['std_dev'];
				$players[$player['id']]['PlayerRanking'][$match['Game']['game_type_id']]['games_played'] += 1;
			}
		}
g($players);

		foreach ($players as $player) {
			foreach ($player['PlayerRanking'] as $ranking) {
				$data = array('PlayerRanking' => $ranking);
g($data);
				$this->PlayerRanking->save($data);
			}
		}
	}

}

