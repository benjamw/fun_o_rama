<?php

App::uses('AppController', 'Controller');

class PlayersController extends AppController {

	public function view($id) {
		$this->Player->id = $id;

		if ( ! $this->Player->exists( )) {
			throw new NotFoundException(__('Invalid Player'));
		}

		$this->set('player', $this->Player->find('first', array(
			'contain' => array(
				'Badge',
				'PlayerRanking' => array(
					'GameType',
					'RankHistory' => array(
						'order' => array(
							'RankHistory.created' => 'ASC',
							'RankHistory.id' => 'ASC',
						),
					),
				),
				'PlayerStat' => array(
					'Game',
				),
			),
			'conditions' => array(
				'Player.id' => $id,
			),
		)));

		$this->set('badges', $this->Player->Badge->find('all'));
	}

	public function admin_badges($id) {
		$this->Player->id = $id;

		if ( ! $this->Player->exists( )) {
			throw new NotFoundException(__('Invalid Player'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Player->save($this->request->data)) {
				$this->Session->setFlash(__('The Player\'s Badges have been updated'), 'flash_success');
				if ( ! $this->prevent_redirect) {
					$this->redirect(array('action' => 'index'));
				}
				else {
					return true;
				}
			}
			else {
				$this->request->data = $this->Player->data;
				$this->Session->setFlash(__('The Player\'s Badges could not be updated. Please, try again.'), 'flash_error');
			}
		}
		else {
			$this->Player->recursive = 1;
			$this->request->data = $this->Player->read(null, $id);
		}

		$badges = $this->Player->Badge->find('all', array(
			'order' => array(
				'Badge.name',
			),
		));
		$badges = Set::combine($badges, '/Badge/id', array('<strong>%s</strong> - %s', '/Badge/name', '/Badge/description'));
		$this->set('badges', $badges);

		$this->_setSelects( );
	}

}

