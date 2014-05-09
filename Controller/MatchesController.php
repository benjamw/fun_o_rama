<?php

App::uses('AppController', 'Controller');

class MatchesController extends AppController {

	public function admin_index( ) {
		$this->paginate = array(
			'order' => array(
				'created' => 'DESC',
			),
		);

		parent::admin_index( );
	}


	public function admin_undo($id = null) {
		if ( ! $this->request->is('post')) {
			throw new MethodNotAllowedException( );
		}

		$this->Match->id = $id;

		if ( ! $this->Match->exists( )) {
			throw new NotFoundException(__('Invalid Match'));
		}

		$this_match = $this->Match->find('first', array(
			'contain' => array(
				'Tournament' => array(
					'Game',
				),
			),
			'conditions' => array(
				'Match.id' => $this->Match->id,
			),
		));

		$last_match = $this->Match->find('first', array(
			'joins' => array(
				array(
					'table' => 'tournaments',
					'alias' => 'Tournament',
					'type' => 'LEFT',
					'conditions' => array(
						'Match.tournament_id = Tournament.id',
					),
				),
				array(
					'table' => 'games',
					'alias' => 'Game',
					'type' => 'LEFT',
					'conditions' => array(
						'Tournament.game_id = Game.id',
					),
				),
			),
			'conditions' => array(
				'Game.game_type_id' => $this_match['Tournament']['Game']['game_type_id'],
				'Match.winning_team_id IS NOT NULL',
			),
			'order' => array(
				'Match.created' => 'DESC',
			),
		));

		if ((int) $last_match['Match']['id'] !== (int) $this->Match->id) {
			$this->Session->setFlash(__('Match was not undone.  It was not the last match played.'), 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->Match->undo( )) {
			$this->Session->setFlash(__('Match undone'), 'flash_success');
			return $this->redirect(array('action' => 'index'));
		}

		$this->Session->setFlash(__('Match was not undone'), 'flash_error');
		return $this->redirect(array('action' => 'index'));
	}


	public function _setSelects($active = true) {
		if (false !== strpos($this->request->params['action'], 'edit')) {
			// the winning teams can only be selected from the teams that actually played
			$winningTeams = $this->Match->Team->find('list', array(
				'joins' => array(
					array(
						'table' => 'matches_teams',
						'alias' => 'MatchesTeam',
						'type' => 'INNER',
						'conditions' => array(
							'MatchesTeam.team_id = Team.id',
						),
					),
				),
				'conditions' => array(
					'MatchesTeam.match_id' => $this->request->params['pass'][0],
				),
				'order' => array(
					'Team.id' => 'ASC',
				),
			));

			// add in the "TIE" option
			$winningTeams = array('-- Tie --') + $winningTeams;

			$this->set('winningTeams', $winningTeams);
		}

		parent::_setSelects($active);
	}

}

