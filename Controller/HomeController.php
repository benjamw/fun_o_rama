<?php

App::uses('AppController', 'Controller');

class HomeController extends AppController {

	public $uses = array('Game', 'Player', 'Match', 'Team');

	public function index( ) {
		// grab any in progress matches
		$this->set('in_progress', $this->Match->find('all', array(
			'contain' => array(
				'Game',
				'Team' => array(
					'Player',
				),
			),
			'conditions' => array(
				'winning_team_id IS NULL',
			),
		)));

		$this->_setSelects( );
	}

}

