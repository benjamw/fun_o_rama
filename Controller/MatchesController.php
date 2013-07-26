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


	function _setSelects($active = true) {
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

