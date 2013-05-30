<?php

App::uses('AppController', 'Controller');

class MatchesController extends AppController {

	public $components = array('TrueSkill.TrueSkill');

	public function start( ) {
		if (empty($this->request->data['Match']['player_id'])) {
			$this->Session->setFlash(__('What are you trying to do?  Because you just failed!'), 'flash_error');
			$this->redirect('/');
		}

		$players = $this->request->data['Match']['player_id'];

		$game_type = $this->Match->Game->find('first', array(
			'conditions' => array(
				'Game.id' => $this->request->data['Match']['game_id'],
			),
		));
		$game_type = $game_type['Game']['game_type_id'];

		// if there is an odd number of players, have a player sit out
		// but find that player by grabbing the players who sat out most
		// recently and don't make them sit out again
		if (0 !== (count($players) % 2)) {
			$auto_in = $this->Match->find('all', array(
				'joins' => array(
					array(
						'table' => 'games',
						'alias' => 'Game',
						'type' => 'INNER',
						'conditions' => array(
							'Match.game_id = Game.id',
						),
					),
				),
				'conditions' => array(
//					'Game.game_type_id' => $game_type,
					'winning_team_id IS NOT NULL',
					'sat_out IS NOT NULL',
					'sat_out <>' => 0,
				),
				'order' => array(
					'created' => 'DESC',
				),
				'limit' => 20,
			));
			$auto_in = Set::extract($auto_in, '/Match/sat_out');

			// get the list of players who have not sat out recently
			$sit_outable = array_diff($players, $auto_in);

			// check to see if every player playing has sat out recently
			// otherwise the do...while below will infinite loop, and those are fun...
			if ( ! $sit_outable) {
				// some players may have sat out a long time ago as well as very recently
				// so parse through the auto_in array and reduce the players list until
				// only one remains.  that player sits out
				$remaining_players = $players;
				foreach ($auto_in as $sitting_out_id) {
					if (in_array($sitting_out_id, $remaining_players)) {
						unset($remaining_players[array_search($sitting_out_id, $remaining_players)]);
					}

					if (1 === count($remaining_players)) {
						$sitting_out_id = reset($remaining_players);
						break;
					}
				}
			}
			else {
				// randomly select a player to sit out
				do {
					shuffle($sit_outable);
					shuffle($sit_outable);
					shuffle($sit_outable);

					$sitting_out_id = reset($sit_outable);

					// unless that player sat out recently
				} while (in_array($sitting_out_id, $auto_in));
			}

			$players = array_diff($players, (array) $sitting_out_id);

			$sitting_out = $this->Match->Team->Player->find('first', array(
				'contain' => array(
					'PlayerRanking',
				),
				'conditions' => array(
					'Player.id' => $sitting_out_id,
				),
			));

			$this->set('sitting_out', $sitting_out);
		}

		$this->request->data['Match']['player_id'] = $players;

		// pull the data for the players in the given game
		$results = $this->Match->Team->Player->find('all', array(
			'contain' => array(
				'PlayerRanking' => array(
					'conditions' => array(
						'PlayerRanking.game_type_id' => $game_type,
					),
				),
			),
			'conditions' => array(
				'Player.id' => $players,
			),
		));

		$player_data = array( );
		foreach ($results as $row) {
			$player_data[] = array(
				'id' => $row['Player']['id'],
				'mean' => ife($row['PlayerRanking'][0]['mean'], 25),
				'std_dev' => ife($row['PlayerRanking'][0]['std_deviation'], 8.33333333333333333),
			);
		}

		list($teams, $quality) = $this->TrueSkill->calculateBestMatch($player_data);
		$this->set('quality', $quality);

		// convert the Team/Player objects to arrays of ids
		// and randomize the players in the teams and the teams
		foreach ($teams as & $team) { // mind the reference
			$team = $team->getAllPlayers( );

			foreach ($team as & $player) { // mind the reference
				$player = $player->getId( );
			}
			unset($player); // kill the reference

			shuffle($team);
			shuffle($team);
			shuffle($team);
		}
		unset($team); // kill the reference

		shuffle($teams);
		shuffle($teams);
		shuffle($teams);

		// create entries in the tables
		$match_data = array('Match' => array(
			'game_id' => (int) $this->request->data['Match']['game_id'],
		));

		if ( ! empty($sitting_out_id)) {
			$match_data['Match']['sat_out'] = $sitting_out_id;
		}

		$this->Match->create( );
		if ($this->Match->save($match_data)) {
			$match_id = $this->Match->id;

			foreach ($teams as $team) {
				$team_data = array('Team' => array(
					'match_id' => $match_id,
				));

				$this->Match->Team->create( );
				if ($this->Match->Team->save($team_data)) {
					$team_id = $this->Match->Team->id;

					foreach ($team as $player_id) {
						$players_team_data = array('PlayersTeam' => array(
							'team_id' => $team_id,
							'player_id' => $player_id,
						));

						$this->Match->Team->PlayersTeam->create( );
						if ( ! $this->Match->Team->PlayersTeam->save($players_team_data)) {
							$this->Match->delete($match_id);
							unset($match_id);
							break 2;
						}
					}
				}
				else {
					$this->Match->delete($match_id);
					unset($match_id);
					break;
				}
			}
		}

		if (empty($match_id)) {
			$this->Session->setFlash(__('Something borked...  Sorry, Try it again...'), 'flash_error');
			$this->redirect('/');
		}

		$this->Session->setFlash(__('Match was created successfully!'), 'flash_success');

		$match = $this->Match->find('first', array(
			'contain' => array(
				'Game',
				'Team' => array(
					'Player' => array(
						'PlayerRanking' => array(
							'conditions' => array(
								'PlayerRanking.game_type_id' => $game_type,
							),
						),
					),
				),
			),
			'conditions' => array(
				'Match.id' => $match_id,
			),
		));
		$this->set('match', $match);

		$this->_setSelects( );
	}

	public function adjust($id) {
		$match = $this->Match->find('first', array(
			'contain' => array(
				'Game',
				'Team' => array(
					'Player',
				),
			),
			'conditions' => array(
				'Match.id' => $id,
			),
		));

		$player_ids = array( );
		foreach ($match['Team'] as & $team) { // mind the reference
			foreach ($team['Player'] as & $player) { // mind the reference
				$player_ids[] = $player['id'];
			}
			unset($player); // kill the reference

			shuffle($team['Player']);
			shuffle($team['Player']);
			shuffle($team['Player']);
		}
		unset($team); // kill the reference

		if ( ! empty($match['Match']['sat_out'])) {
			$player_ids[] = $match['Match']['sat_out'];

			$sitting_out = $this->Match->Team->Player->find('first', array(
				'conditions' => array(
					'id' => $match['Match']['sat_out'],
				),
			));
			$this->set('sitting_out', $sitting_out);
		}

		if ( ! empty($player_ids)) {
			$the_rest = $this->Match->Team->Player->find('all', array(
				'conditions' => array(
					'id <>' => $player_ids,
				),
			));
			$this->set('the_rest', $the_rest);
		}

		$this->set('match', $match);
		$this->set('adjusting', true);
		$this->_setSelects( );

		$this->render('start');
	}

	public function create( ) {
		// grab the most played game
		$game = $this->Match->Game->find('first', array(
			'joins' => array(
				array(
					'table' => 'matches',
					'alias' => 'Match',
					'type' => 'LEFT',
					'conditions' => array(
						'Match.game_id = Game.id',
					),
				),
			),
			'group' => array(
				'Game.id',
			),
			'order' => array(
				'COUNT(Match.id)' => 'DESC',
			),
		));

		$match = array(
			'Match' => array(
				'id' => 0,
				'created' => date('Y-m-d H:i:s'),
				'game_id' => $game['Game']['id'],
			),
			'Game' => $game['Game'],
			'Team' => array(
				array(
					'id' => 'x',
				),
				array(
					'id' => 'y',
				),
			),
		);
		$this->set('match', $match);

		$the_rest = $this->Match->Team->Player->find('all', array(
			'order' => array(
				'Player.name' => 'ASC',
			),
		));
		$this->set('the_rest', $the_rest);

		$this->_setSelects( );

		$this->render('start');
	}

	public function update( ) {
		$update_rank = false;

		if ($this->request->isAjax( ) && $this->request->is('post')) {
			if ( ! empty($this->request->data['pk'])) {
				// updating the match game
				$data = array('Match' => array(
					'id' => (int) $this->request->data['pk'],
					$this->request->data['name'] => (int) $this->request->data['value'],
				));
			}
			elseif (isset($this->request->data['winner'])) {
				// updating the match winner
				$winner = $this->request->data['winner'];
				if ('null' === $winner) {
					$this->Match->delete((int) $this->request->data['match']);
					echo 'OK';
					exit;
				}

				$data = array('Match' => array(
					'id' => (int) $this->request->data['match'],
					'winning_team_id' => (int) $this->request->data['winner'],
				));

				$update_rank = true;
			}
			elseif (isset($this->request->data['rename'])) {
				$data = array(
					'Team' => array(
						'id' => $this->request->data['rename'],
						'name' => $this->Match->Team->generate_name( ),
					),
				);

				$this->Match->Team->save($data, false);

				echo $data['Team']['name'];
				exit;
			}
			elseif (isset($this->request->data['team1'])) {
				$data = array(
					'Match' => array(
						'id' => $this->request->data['match_id'],
						'sat_out' => reset($this->request->data['sat_out']),
					),
					'Team' => array(
						array(
							'Team' => array(
								'id' => $this->request->data['team1_id'],
							),
							'Player' => array(
								'Player' => $this->request->data['team1'],
							),
						),
						array(
							'Team' => array(
								'id' => $this->request->data['team2_id'],
							),
							'Player' => array(
								'Player' => $this->request->data['team2'],
							),
						),
					),
				);
			}
			else {
				throw new ForbiddenException( );
			}

			if ($this->Match->saveAll($data, array('validate' => false))) {
				echo 'OK';
			}
			else {
				throw new InternalErrorException( );
			}
		}

		// only update the rank after everything is up to date
		if ($update_rank) {
			$this->update_rank((int) $this->request->data['match']);
		}

		exit;
	}

	public function edit($id = null) {
		$result_teams = $this->Match->Team->find('all', array(
			'contain' => array(
				'Player',
			),
			'conditions' => array(
				'Team.match_id' => $id,
			),
		));

		$teams = array( );
		foreach ($result_teams as $team) {
			$players = array( );
			foreach ($team['Player'] as $player) {
				$players[] = $player['name'];
			}

			$teams[$team['Team']['id']] = implode(', ', $players);
		}

		$this->set('teams', $teams);

		parent::edit($id);
	}

	public function update_rank($match_id) {
		$match = $this->Match->find('first', array(
			'contain' => array(
				'Game',
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
					if ((int) $match['Game']['game_type_id'] === (int) $ranking['game_type_id']) {
						// $ranking has the data we need
						break;
					}
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

		$new_rankings = $this->TrueSkill->updatePlayerRankings($teams, $outcome);

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
			$this->Match->Team->Player->PlayerRanking->save($data);
		}
	}

	protected function _setSelects($active_only = false) {
		parent::_setSelects($active_only);

		if (empty($this->viewVars['sitting_out'])) {
			$this->set('sitting_out', false);
		}

		if (empty($this->viewVars['the_rest'])) {
			$this->set('the_rest', array( ));
		}

		if (empty($this->viewVars['adjusting'])) {
			$this->set('adjusting', false);
		}
	}

}

