<?php

App::uses('AppController', 'Controller');

class SongsController extends AppController {

	public function play($match_id) {
		Configure::write('debug', 0);
		$this->layout = 'simple_mobile';

		$conds = array('Match.id' => $match_id);
		if ('t' === $match_id{0}) {
			$conds = array('Match.tournament_id' => substr($match_id, 1));
		}

		$match = m('Match')->find('first', array(
			'contain' => array(
				'Team' => array(
					'Player' => array(
						'Song' => array(
							'conditions' => array(
								'Song.active' => 1,
							),
						),
					),
				),
			),
			'conditions' => array_merge($conds, array(
				'Match.winning_team_id IS NULL',
			)),
		));

		if ( ! $match) {
			if ($this->request->is('ajax')) {
				echo json_encode('REFRESH');
				exit;
			}

			$this->Session->setFlash(__('The match is over, select a new match for music.'), 'flash_error');
			$this->redirect(array('controller' => 'home', 'action' => 'index'));
		}

		$song_ids = Set::extract($match, '/Team/Player/Song/id');

		// if the song count is less than 20, pull additional songs from the remaining pool
		if (20 > count($song_ids)) {
			$additional_songs = $this->Song->find('list', array(
				'fields' => array(
					'Song.id',
					'Song.id',
				),
				'conditions' => array(
					'Song.id <>' => $song_ids,
					'Song.active' => 1,
				),
				'order' => 'rand()',
				'limit' => 20 - count($song_ids),
			));

			$song_ids = array_merge($song_ids, $additional_songs);
		}

		// remove the songs that were played recently
		$played_songs = $this->Song->find('list', array(
			'fields' => array(
				'Song.id',
				'Song.id',
			),
			'conditions' => array(
				'Song.active' => 1,
				'Song.played IS NOT NULL',
				'Song.played > DATE_SUB(NOW( ), INTERVAL 3 HOUR)',
			),
			'order' => array(
				'Song.played' => 'DESC',
			),
			'limit' => ceil(count($song_ids) * 0.75), // 3/4 of total songs
		));

		$song_ids = array_diff($song_ids, $played_songs);

		shuffle($song_ids);

		$song = $this->Song->findById($song_ids[0]);

		if ($this->request->is('ajax')) {
			echo json_encode($song);
			exit;
		}

		$this->set('song', $song);
	}

	public function played($id) {
		$this->Song->read(null, $id);
		$this->Song->data['Song']['played'] = date('Y-m-d H:i:s');
		$this->Song->save(null, false);

		echo json_encode('OK');
		exit;
	}

	public function admin_index( ) {
		$this->Song->contain(array(
			'Player',
		));

		parent::admin_index( );
	}

}

