<?php

App::uses('AppController', 'Controller');

class TournamentsController extends AppController {

	public function start( ) {
		try {
			$id = $this->Tournament->start($this->request->data['Tournament']);

			$this->set('tournament', $this->Tournament->find('first', array(
				'contain' => array(
					'Game' => array(
						'GameType',
					),
					'Match' => array(
						'Team' => array(
							'Player' => array(
								'PlayerRanking',
							),
						),
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
			)));

			$this->set('games', $this->Tournament->Game->find('list'));
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
					'Team' => array(
						'Player' => array(
							'PlayerRanking',
						),
					),
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

//		$player_ids = array( );
//		foreach ($match['Team'] as & $team) { // mind the reference
//			foreach ($team['Player'] as & $player) { // mind the reference
//				$player_ids[] = $player['id'];
//			}
//			unset($player); // kill the reference
//		}
//		unset($team); // kill the reference
//
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
//
//		if ( ! empty($player_ids)) {
//			$the_rest = $this->Match->Team->Player->find('all', array(
//				'conditions' => array(
//					'id <>' => $player_ids,
//				),
//			));
//			$this->set('the_rest', $the_rest);
//		}

		$this->set('tournament', $tournament);
		$this->set('adjusting', true);
		$this->_setSelects( );

		$this->render('start');
	}


	public function update( ) {
		$update_rank = false;

		if ($this->request->isAjax( ) && $this->request->is('post')) {
			if ( ! empty($this->request->data['pk'])) {
				// updating the tournament game
				$data = array('Tournament' => array(
					'id' => (int) $this->request->data['pk'],
					$this->request->data['name'] => (int) $this->request->data['value'],
				));
			}
			elseif (isset($this->request->data['winner'])) {
				// updating the match winner
				$winner = $this->request->data['winner'];
				if ('null' === $winner) {
					$this->Tournament->delete((int) $this->request->data['match']);
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
			}
			else {
				throw new ForbiddenException( );
			}

			if ($this->Tournament->saveAll($data, array('validate' => false))) {
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

