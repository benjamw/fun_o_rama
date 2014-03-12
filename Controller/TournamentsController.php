<?php

App::uses('AppController', 'Controller');

class TournamentsController extends AppController {

	public function index( ) {
		// grab any curently running tournaments
		$active = $this->Tournament->find('all', array(
			'contain' => array(
				'Game' => array(
					'GameType',
				),
				'Match' => array(
					'Team',
				),
				'Team' => array(
					'Player' => array(
						'PlayerRanking',
					),
				),
			),
			'joins' => array(
				array(
					'table' => 'matches',
					'alias' => 'ActiveMatch',
					'type' => 'LEFT',
					'conditions' => array(
						'ActiveMatch.tournament_id = Tournament.id',
						'ActiveMatch.winning_team_id IS NULL',
					),
				),
			),
			'conditions' => array(
				'ActiveMatch.id IS NOT NULL',
			),
			'group' => array(
				'Tournament.id',
			),
			'order' => array(
				'Tournament.created' => 'DESC',
			),
		));

		foreach ($active as & $tourny) { // mind the reference
			if ('round_robin' === $tourny['Tournament']['tournament_type']) {
				$tourny['Results'] = $this->Tournament->get_round_robin_results($tourny['Tournament']['id']);
			}
		}
		unset($tourny); // kill the reference

		$this->set('active', $active);

		// grab the 5 most recently completed tournaments and their results
		$completed = $this->Tournament->find('all', array(
			'contain' => array(
				'Game' => array(
					'GameType',
				),
				'Match' => array(
					'Team',
				),
				'Team' => array(
					'Player',
				),
			),
			'joins' => array(
				array(
					'table' => 'matches',
					'alias' => 'ActiveMatch',
					'type' => 'LEFT',
					'conditions' => array(
						'ActiveMatch.tournament_id = Tournament.id',
						'ActiveMatch.winning_team_id IS NULL',
					),
				),
				array(
					'table' => 'matches',
					'alias' => 'LastMatch',
					'type' => 'INNER',
					'conditions' => array(
						'LastMatch.tournament_id = Tournament.id',
						'LastMatch.winning_team_id IS NOT NULL',
						'LastMatch.created = (
							SELECT matches.created
							FROM matches
							WHERE tournament_id = Tournament.id
							ORDER BY matches.created
							LIMIT 1
						)',
					),
				),
			),
			'conditions' => array(
				'ActiveMatch.id IS NULL',
			),
			'group' => array(
				'Tournament.id',
			),
			'order' => array(
				'LastMatch.created' => 'DESC',
			),
			'limit' => 5,
		));

		foreach ($completed as & $tourny) { // mind the reference
			if ('round_robin' === $tourny['Tournament']['tournament_type']) {
				$tourny['Results'] = $this->Tournament->get_round_robin_results($tourny['Tournament']['id']);
			}
		}
		unset($tourny); // kill the reference

		$this->set('completed', $completed);
	}


	public function start( ) {
		try {
			$id = $this->Tournament->start($this->request->data['Tournament']);
			$this->Session->setFlash('Tournament Created Successfully!', 'flash_success');
			$this->redirect(array('controller' => 'tournaments', 'action' => 'adjust', $id));
		}
		catch (CakeException $e) {
			$this->Session->setFlash($e->getMessage( ).'. Please try again', 'flash_error');
			$this->redirect(array('controller' => 'home'));
		}
	}


	public function adjust($id) {
		$tournament = $this->Tournament->find('first', array(
			'contain' => array(
				'Game' => array(
					'GameType',
				),
				'Match' => array(
					'Team',
				),
				'Team' => array(
					'Player' => array(
						'PlayerRanking',
					),
				),
				'SittingOut' => array(
					'Player',
				),
			),
			'conditions' => array(
				'Tournament.id' => $id,
			),
		));

		// store this version in session so it can be compared when edited
		$this->Session->write('Tourny', $tournament);

		$player_ids = array( );
		foreach ($tournament['Team'] as & $team) { // mind the reference
			foreach ($team['Player'] as & $player) { // mind the reference
				$player_ids[] = $player['id'];
			}
			unset($player); // kill the reference
		}
		unset($team); // kill the reference

		foreach ($tournament['SittingOut'] as & $sitting_out) {
			$player_ids[] = $sitting_out['Player']['id'];
		}
		unset($sitting_out);

		if ( ! empty($player_ids)) {
			$the_rest = $this->Tournament->Team->Player->find('all', array(
				'contain' => array(
					'PlayerRanking',
				),
				'conditions' => array(
					'Player.id <>' => $player_ids,
				),
				'order' => array(
					'Player.name' => 'ASC',
				),
			));
			$this->set('the_rest', $the_rest);
		}

		$this->set('tournament', $tournament);
		$this->set('adjusting', true);
		$this->_setSelects( );
	}


	public function update( ) {
		if ($this->request->isAjax( ) && $this->request->is('post')) {
			if ( ! empty($this->request->data['pk'])) {
				// updating the tournament game
				$data = array('Tournament' => array(
					'id' => (int) $this->request->data['pk'],
					$this->request->data['name'] => (int) $this->request->data['value'],
				));

				$response = $this->Tournament->save($data, false);
			}
			elseif (isset($this->request->data['winner'])) {
				// updating the match winner
				$winner = $this->request->data['winner'];
				if ('null' === $winner) {
					$this->Tournament->delete((int) $this->request->data['match']);
					echo 'OK';
					exit;
				}

				$response = $this->Tournament->finish_match((int) $this->request->data['match'], (int) $this->request->data['winner']);

				// grab the next batch of matches if there are any
				$current_matches = $this->Tournament->Match->find('all', array(
					'contain' => array(
						'Team' => array(
							'Player',
						),
					),
					'joins' => array(
						array(
							'table' => 'matches',
							'alias' => 'Finished',
							'type' => 'INNER',
							'conditions' => array(
								'Finished.id' => (int) $this->request->data['match'],
								'Match.tournament_id = Finished.tournament_id',
							),
						),
					),
					'conditions' => array(
						'Match.winning_team_id IS NULL',
					),
					'order' => array(
						'Match.id',
					),
				));
				$this->set('current_matches', $current_matches);
			}
			elseif (isset($this->request->data['rename'])) {
				$data = array(
					'Team' => array(
						'id' => $this->request->data['rename'],
						'name' => $this->Tournament->Team->generate_name(true),
					),
				);

				$this->Tournament->Team->save($data, false);

				echo $data['Team']['name'];
				exit;
			}
			elseif (isset($this->request->data['tournament_id'])) {
				if ($this->Session->check('Tourny')) {
					$orig_data = $this->Session->read('Tourny');
				}
				else {
					$orig_data = $this->Tournament->find('first', array(
						'contain' => array(
							'SittingOut' => array(
								'Player',
							),
							'Team' => array(
								'Player',
							),
						),
						'conditions' => array(
							'Tournament.id' => $this->request->data['tournament_id'],
						),
					));
				}

				$orig_teams = Set::combine($orig_data, '/Team/id', '/Team/.');

				foreach ($orig_teams as & $team) {
					$team = Set::extract($team, '/Player/id');
				}
				unset($team);

				$teams = array( );
				foreach ($this->request->data as $key => $value) {
					$key = explode('_', $key);
					if (('team' !== $key[0]) || ! is_numeric($key[1])) {
						continue;
					}

					foreach ($value as & $val) { // mind the reference
						if (false !== strpos($val, '_')) {
							$val = explode('_', $val);
							$val = $val[1];
						}
					}
					unset($val); // kill the reference

					$value = array_filter($value);

					if (empty($teams[$key[1]])) {
						$teams[$key[1]] = array( );
					}

					$teams[$key[1]][] = $value;
				}

				// clean out any unedited teams
				foreach ($teams as $team_id => $team_group) {
					foreach ($team_group as $idx => $value) {
						// check the difference both ways
						$diff = array_diff($value, $orig_teams[$team_id]) || array_diff($orig_teams[$team_id], $value);

						if ( ! $diff) {
							unset($teams[$team_id][$idx]);
						}
					}

					if (empty($teams[$team_id])) {
						unset($teams[$team_id]);
					}
				}

				// only use the first edited team
				foreach ($teams as $team_id => $team_group) {
					$teams[$team_id] = reset($team_group);
				}

				if ( ! empty($teams)) {
					$data = array(
						'Tournament' => array(
							'id' => $this->request->data['tournament_id'],
						),
						'Team' => array( ),
					);

					// update edited teams
					foreach ($teams as $team_id => $value) {
						$data['Team'][] = array(
							'Team' => array(
								'id' => $team_id,
							),
							'Player' => array(
								'Player' => $value,
							),
						);
					}

					$response = $this->Tournament->saveAll($data, array('validate' => false));
				}

				// remove all current sitting out entries for this tournament
				$this->Tournament->SittingOut->deleteAll(array(
					'SittingOut.tournament_id' => $this->request->data['tournament_id'],
				));

				$sitting_out = array_filter($this->request->data['team_out']);

				// add back any sitting out entries that were changed
				foreach ($sitting_out as $value) {
					$val = explode('_', $value);
					$val = $val[1];

					$this->Tournament->SittingOut->create( );
					$this->Tournament->SittingOut->save(array('SittingOut' => array(
						'tournament_id' => $this->request->data['tournament_id'],
						'player_id' => $val,
					)), false);
				}
			}
			else {
				throw new ForbiddenException( );
			}

			if ($response) {
				echo 'OK';

				if ( ! empty($current_matches)) {
					$this->render('current_matches');
					return;
				}
			}
			else {
				throw new InternalErrorException( );
			}
		}

		exit;
	}


	public function admin_index( ) {
		$this->paginate = array(
			'order' => array(
				'created' => 'DESC',
			),
		);

		parent::admin_index( );
	}


	protected function _setSelects($active_only = false) {
		parent::_setSelects($active_only);

		$this->set($this->Tournament->enumValues( ));

		if (empty($this->viewVars['the_rest'])) {
			$this->set('the_rest', array( ));
		}

		if (empty($this->viewVars['adjusting'])) {
			$this->set('adjusting', false);
		}
	}

}

