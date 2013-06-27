<?php

App::uses('AppController', 'Controller');

class HomeController extends AppController {

	public $uses = array('Tournament');

	public function index( ) {
		// grab any in progress tournaments / matches
		// if the tournament only has two teams, it's not a
		// "tournament", it's just a match, so only show the match
		$this->set('in_progress', $this->Tournament->Match->find('all', array(
			'contain' => array(
				'Tournament' => array(
					'Team',
				),
				'Team' => array(
					'Player',
				),
			),
			'conditions' => array(
				'winning_team_id IS NULL',
			),
		)));

		$this->_setSelects(true);
	}

	public function _setSelects($active_only = false) {
		$this->set($this->Tournament->enumValues( ));
		$this->set('players', $this->Tournament->Team->Player->find('list', array('order' => 'name')));

		parent::_setSelects($active_only);
	}

}

