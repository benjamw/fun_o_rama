<?php

App::uses('AppController', 'Controller');

class HomeController extends AppController {

	public $uses = array('Tournament');
	public $password = 'password'; // change this, obviously

	public function index( ) {
		// grab any in progress tournaments
		$in_progress = $this->Tournament->find('all', array(
			'contain' => array(
				'Game',
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
			$tourny['Tournament']['match_count'] = $this->Tournament->Match->find('count', array(
				'conditions' => array(
					'Match.tournament_id' => $tourny['Tournament']['id'],
				),
			));

			$tourny['Tournament']['team_count'] = $this->Tournament->Team->find('count', array(
				'conditions' => array(
					'Team.tournament_id' => $tourny['Tournament']['id'],
				),
			));
		}
		unset($tourny); // kill the reference

		$this->set('in_progress', $in_progress);

		$this->_setSelects(true);
	}

	public function login( ) {
		if ($this->request->is('post')) {
			if ($this->password === $this->request->data['Home']['password']) {
				$this->Session->write('ALLOWED', true);
				$referer = $this->Session->read('LOGIN.referer');
				$this->Session->delete('LOGIN');
				$this->redirect($referer);
			}
			else {
				sleep(5);
				$failed = $this->Session->check('LOGIN.failed') ? $this->Session->read('LOGIN.failed') : 0;

				++$failed;

				if (3 <= $failed) {
					$this->Session->write('LOGIN.blocked', true);
					echo 'Access Denied';
					exit;
				}

				$this->Session->write('LOGIN.failed', $failed);
				$this->Session->setFlash('Login Failed', 'flash_error');
			}
		}
	}

	public function _setSelects($active_only = false) {
		$this->set($this->Tournament->enumValues( ));
		$this->set('players', $this->Tournament->Team->Player->find('list', array('order' => 'name')));

		parent::_setSelects($active_only);
	}

}

