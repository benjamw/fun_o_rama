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

}

