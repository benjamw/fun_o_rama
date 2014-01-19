<?php

App::uses('AppController', 'Controller');

class PlayerRankingsController extends AppController {

	const KILL_SWITCH = true;

	public $components = array('TrueSkill.TrueSkill');

	public function admin_edit($id = null) {
		if ( ! isset($this->prevent_redirect)) {
			$this->prevent_redirect = false;
		}

		$this->{$this->modelClass}->id = $id;

		if ( ! $this->{$this->modelClass}->exists( )) {
			throw new NotFoundException(__('Invalid '.Inflector::humanize(Inflector::underscore($this->modelClass))));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			// pull the original value and store it in the history table
			$orig = $this->{$this->modelClass}->findById($id);

			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->PlayerRanking->RankHistory->create( );
				$this->PlayerRanking->RankHistory->save(array('RankHistory' => array(
					'player_ranking_id' => $id,
					'mean' => $orig['PlayerRanking']['mean'],
					'std_deviation' => $orig['PlayerRanking']['std_deviation'],
				)));

				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' has been saved'), 'flash_success');
				if ( ! $this->prevent_redirect) {
					$this->redirect(array('action' => 'index'));
				}
				else {
					return true;
				}
			}
			else {
				$this->request->data = $this->{$this->modelClass}->data;
				$this->Session->setFlash(__('The '.Inflector::humanize(Inflector::underscore($this->modelClass)).' could not be saved. Please, try again.'), 'flash_error');
			}
		}
		else {
			$this->{$this->modelClass}->recursive = 1;
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}

		$this->_setSelects( );
	}

	public function refresh_values( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('EMPTYING TABLE `player_rankings`...');
		$this->PlayerRanking->query(
			'TRUNCATE TABLE `player_rankings`'
		);

		$rows = $this->PlayerRanking->find('count');
g('ROW COUNT `player_rankings` = '.$rows);

g('EMPTYING TABLE `rank_history`...');
		$this->PlayerRanking->RankHistory->query(
			'TRUNCATE TABLE `rank_history`'
		);

		$rows = $this->PlayerRanking->RankHistory->find('count');
g('ROW COUNT `rank_history` = '.$rows);

g('STARTING');
		$this->fill_values( );
		$this->play_games( );
g('DONE');
	}

	public function fill_values( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('FILLING VALUES');
		$players = array_keys($this->PlayerRanking->Player->find('list'));
		$game_types = array_keys($this->PlayerRanking->GameType->find('list'));

		foreach ($game_types as $game_type_id) {
			foreach ($players as $player_id) {
				$this->PlayerRanking->create( );
				$result = $this->PlayerRanking->save(array('PlayerRanking' => array(
					'player_id' => $player_id,
					'game_type_id' => $game_type_id,
				)));
g($result);
			}
		}
	}

	public function play_games( ) {
		$this->autoRender = false;

		if (self::KILL_SWITCH) {
			exit;
		}

g('PLAYING GAMES');
		$matches = array_values($this->PlayerRanking->Player->Team->Match->find('all', array(
			'conditions' => array(
				'winning_team_id IS NOT NULL',
			),
			'order' => array(
				'created' => 'asc',
			),
		)));

		foreach ($matches as $match) {
g($match);
			$this->PlayerRanking->Player->Team->Match->update_rank($match['Match']['id']);
		}
	}

}

