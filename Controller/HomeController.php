<?php

App::uses('AppController', 'Controller');

class HomeController extends AppController {

	public $uses = array('Tournament');

	public function index( ) {
		// grab any in progress tournaments
		$in_progress = $this->Tournament->find('all', array(
			'fields' => array(
				'Tournament.*',
				'COUNT(CMatch.id ) AS `match_count`',
			),
			'contain' => array(
				'Match' => array(
					'conditions' => array(
						'Match.winning_team_id IS NULL',
					),
					'Team' => array(
						'Player',
					),
				),
			),
			'joins' => array(
				array(
					'table' => 'matches',
					'alias' => 'JMatch',
					'type' => 'LEFT',
					'conditions' => array(
						'JMatch.tournament_id = Tournament.id',
						'JMatch.winning_team_id IS NULL',
					),
				),
				array(
					'table' => 'matches',
					'alias' => 'CMatch',
					'type' => 'LEFT',
					'conditions' => array(
						'CMatch.tournament_id = Tournament.id',
					),
				),
			),
			'conditions' => array(
				'JMatch.id IS NOT NULL',
			),
			'group' => array(
				'Tournament.id',
			),
			'order' => array(
				'Tournament.created' => 'ASC',
			),
		));

		foreach ($in_progress as & $tourny) { // mind the reference
			$tourny['Tournament']['match_count'] = $tourny[0]['match_count'];
			unset($tourny[0]);
		}
		unset($tourny); // kill the reference

		$this->set('in_progress', $in_progress);

		$this->_setSelects(true);
	}

	public function _setSelects($active_only = false) {
		$this->set($this->Tournament->enumValues( ));
		$this->set('players', $this->Tournament->Team->Player->find('list', array('order' => 'name')));

		parent::_setSelects($active_only);
	}

}

