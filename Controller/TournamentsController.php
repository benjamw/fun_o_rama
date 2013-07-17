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
			),
			'conditions' => array(
				'Tournament.id' => $id,
			),
		));

		$player_ids = array( );
		foreach ($tournament['Team'] as & $team) { // mind the reference
			foreach ($team['Player'] as & $player) { // mind the reference
				$player_ids[] = $player['id'];
			}
			unset($player); // kill the reference
		}
		unset($team); // kill the reference

//		if ( ! empty($match['Match']['sat_out'])) {
//			$player_ids[] = $match['Match']['sat_out'];
//
//			$sitting_out = $this->Match->Team->Player->find('first', array(
//				'conditions' => array(
//					'id' => $match['Match']['sat_out'],
//				),
//			));
//			$this->set('sitting_out', $sitting_out);
//		}

		if ( ! empty($player_ids)) {
			$the_rest = $this->Tournament->Team->Player->find('all', array(
				'contain' => array(
					'PlayerRanking',
				),
				'conditions' => array(
					'id <>' => $player_ids,
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
				$data = array(
					'Tournament' => array(
						'id' => $this->request->data['tournament_id'],
					),
					'Team' => array( ),
				);

				foreach ($this->request->data as $key => $value) {
					$key = explode('_', $key);
					if ('team' !== $key[0]) {
						continue;
					}

					foreach ($value as & $val) { // mind the reference
						$val = explode('_', $val);
						$val = $val[1];
					}
					unset($val); // kill the reference

					$data['Team'][] = array(
						'Team' => array(
							'id' => $key[1],
						),
						'Player' => array(
							'Player' => $value,
						),
					);
				}

				$response = $this->Tournament->saveAll($data, array('validate' => false));
			}
			else {
				throw new ForbiddenException( );
			}

			if ($response) {
				echo 'OK';
			}
			else {
				throw new InternalErrorException( );
			}
		}

		exit;
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

