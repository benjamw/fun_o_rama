<?php

App::uses('AppModel', 'Model');

class Match extends AppModel {

	public $validate = array(
		'tournament_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notblank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $actsAs = array(
		'TrueSkill.TrueSkill',
	);

	public $belongsTo = array(
		'Tournament',
		'WinningTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'winning_team_id',
		),
	);

	public $hasAndBelongsToMany = array(
		'Team',
	);

	public function update_rank($match_id) {
		$match = $this->find('first', array(
			'contain' => array(
				'Tournament' => array(
					'Game',
				),
				'Team' => array(
					'Player' => array(
						'PlayerRanking',
					),
				),
			),
			'conditions' => array(
				'Match.id' => $match_id,
			),
		));

		if ( ! $match['Tournament']['ranked']) {
			return false;
		}

		$i = 1;
		$outcome = 0;
		$players = array( );

		// convert to a format that is usable by the plugin
		foreach ($match['Team'] as $match_team) {
			if (empty($match_team['Player'])) {
				return false;
			}

			$team_ids[] = $match_team['id'];

			$team = array( );
			foreach ($match_team['Player'] as $player) {
				if (empty($player['PlayerRanking'])) {
					continue 2;
				}

				foreach ($player['PlayerRanking'] as $ranking) {
					if ((int) $match['Tournament']['Game']['game_type_id'] === (int) $ranking['game_type_id']) {
						// $ranking has the data we need
						break;
					}
				}

				if ('0' === (string) $ranking['games_played']) {
					$ranking['mean'] = $this->getDefaultMean( );
					$ranking['std_deviation'] = $this->getDefaultStandardDeviation( );

					// create a history entry for the starting point
					$history_data = array('RankHistory' => array(
						'player_ranking_id' => $ranking['id'],
						'mean' => $ranking['mean'],
						'std_deviation' => $ranking['std_deviation'],
						'created' => $match['Match']['created'],
					));
					$this->Team->Player->PlayerRanking->RankHistory->create( );
					$this->Team->Player->PlayerRanking->RankHistory->save($history_data);
				}

				$players[$player['id']] = array(
					'id' => $player['id'],
					'mean' => $ranking['mean'],
					'std_dev' => $ranking['std_deviation'],
				);

				$team[] = $players[$player['id']];

				$players[$player['id']]['pr_id'] = $ranking['id'];
				$players[$player['id']]['games_played'] = $ranking['games_played'];
			}

			$teams[] = $team;

			if ((int) $match_team['id'] === (int) $match['Match']['winning_team_id']) {
				$outcome = $i;
			}

			++$i;
		}

		$new_rankings = $this->updatePlayerRankings($teams, $outcome);

		foreach ($new_rankings as $player) {
			$players[$player['id']]['mean'] = $player['mean'];
			$players[$player['id']]['std_deviation'] = $player['std_dev'];
			$players[$player['id']]['games_played'] += 1;
		}

		foreach ($players as $player) {
			$player['id'] = $player['pr_id'];
			unset($player['pr_id']);
			unset($player['std_dev']);

			$data = array('PlayerRanking' => $player);
			$this->Team->Player->PlayerRanking->save($data);

			// create a history entry
			$history_data = array('RankHistory' => array(
				'player_ranking_id' => $data['PlayerRanking']['id'],
				'mean' => $data['PlayerRanking']['mean'],
				'std_deviation' => $data['PlayerRanking']['std_deviation'],
				'created' => $match['Match']['created'],
			));
			$this->Team->Player->PlayerRanking->RankHistory->create( );
			$this->Team->Player->PlayerRanking->RankHistory->save($history_data);
		}
	}

	public function update_stats($match_id) {
		$schema = $this->schema( );

		$match = $this->find('first', array(
			'contain' => array(
				'Tournament' => array(
					'Game',
				),
				'Team' => array(
					'Player' => array(
						'PlayerStat',
					),
				),
			),
			'conditions' => array(
				'Match.id' => $match_id,
			),
		));

		foreach ($match['Team'] as $team) {
			$winner = ((int) $team['id'] === (int) $match['Match']['winning_team_id']);
			$draw = (0 === (int) $match['Match']['winning_team_id']);

			foreach ($team['Player'] as $player) {
				foreach ($player['PlayerStat'] as $stat) {
					if ((int) $match['Tournament']['Game']['id'] === (int) $stat['game_id']) {
						break;
					}
				}

				// remove the global stats
				// so they'll get updated properly
				unset($stat['global_wins']);
				unset($stat['global_draws']);
				unset($stat['global_losses']);
				unset($stat['max_streak']);
				unset($stat['min_streak']);

				if ($draw) {
					$stat['draws'] += 1;
					$stat['streak'] = 0;
				}
				elseif ($winner) {
					$stat['wins'] += 1;

					if (0 <= $stat['streak']) {
						$stat['streak'] += 1;
					}
					else {
						$stat['streak'] = 1;
					}
				}
				else {
					$stat['losses'] += 1;

					if (0 >= $stat['streak']) {
						$stat['streak'] -= 1;
					}
					else {
						$stat['streak'] = -1;
					}
				}

				$data = array('PlayerStat' => $stat);
				$this->Team->Player->PlayerStat->save($data);
			}
		}
	}

	public function undo($id = null) {
		if ( ! empty($id)) {
			$this->id = $id;
		}

		$id = $this->id;

		$this->contain(array(
			'Tournament' => array(
				'Game',
				'Team' => array(
					'Player',
				),
			),
			'WinningTeam' => array(
				'Player',
			),
		));
		$match = $this->read( );

		$players = Set::extract($match, '/Tournament/Team/Player/id');
		$winning_players = Set::extract($match, '/WinningTeam/Player/id');

		// revert the stats
		$stats = $this->Team->Player->PlayerStat->find('all', array(
			'conditions' => array(
				'PlayerStat.player_id' => $players,
				'PlayerStat.game_id' => $match['Tournament']['Game']['id'],
			),
		));

		foreach ($stats as $stat) {
			$stat = $stat['PlayerStat'];

			if (in_array($stat['player_id'], $winning_players)) {
				--$stat['wins'];
				--$stat['global_wins'];

				if ((0 !== (int) $stat['streak']) && ($stat['streak'] === $stat['max_streak'])) {
					--$stat['streak'];
					--$stat['max_streak'];
				}
			}
			else {
				--$stat['losses'];
				--$stat['global_losses'];

				if ((0 !== (int) $stat['streak']) && ($stat['streak'] === $stat['min_streak'])) {
					++$stat['streak'];
					++$stat['min_streak'];
				}
			}

			$this->Team->Player->PlayerStat->save(array('PlayerStat' => $stat), false);
		}

		// revert the rankings
		$ranks = $this->Team->Player->PlayerRanking->find('all', array(
			'conditions' => array(
				'PlayerRanking.player_id' => $players,
				'PlayerRanking.game_type_id' => $match['Tournament']['Game']['game_type_id'],
			),
		));

		foreach ($ranks as $rank) {
			$rank = $rank['PlayerRanking'];

			$rank_history = $this->Team->Player->PlayerRanking->RankHistory->find('all', array(
				'conditions' => array(
					'RankHistory.player_ranking_id' => $rank['id'],
				),
				'order' => array(
					'RankHistory.created' => 'DESC',
					'RankHistory.id' => 'DESC',
				),
				'limit' => 2,
			));

			if ($rank['max_mean'] === $rank['mean']) {
				$rank['max_mean'] = $rank_history[1]['RankHistory']['mean'];
			}
			elseif ($rank['min_mean'] === $rank['mean']) {
				$rank['min_mean'] = $rank_history[1]['RankHistory']['mean'];
			}

			$rank['mean'] = $rank_history[1]['RankHistory']['mean'];
			$rank['std_deviation'] = $rank_history[1]['RankHistory']['std_deviation'];
			--$rank['games_played'];

			$this->Team->Player->PlayerRanking->save(array('PlayerRanking' => $rank));

			// delete the latest history entry
			$this->Team->Player->PlayerRanking->RankHistory->delete($rank_history[0]['RankHistory']['id']);
		}

		// delete the match
		$return = $this->delete( );

		// if the tournament is empty, delete it as well
		$tourny_matches = $this->find('count', array(
			'conditions' => array(
				'Match.tournament_id' => $match['Tournament']['id'],
			),
		));
		if ( ! $tourny_matches) {
			$this->Tournament->delete($match['Tournament']['id']);
		}

		return $return;
	}

}

