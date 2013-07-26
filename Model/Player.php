<?php

App::uses('AppModel', 'Model');

class Player extends AppModel {

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $hasMany = array(
		'PlayerRanking' => array(
			'dependent' => true,
		),
		'PlayerStat' => array(
			'dependent' => true,
		),
	);

	public $hasAndBelongsToMany = array(
		'Badge' => array(
			'with' => 'BadgesPlayer',
		),
		'Team',
	);

	public function afterSave($created) {
		parent::afterSave($created);

		if ($created) {
			// create an entry in the PlayerRanking table for this player
			// for every game type.  the values are defaulted in the table
			$game_types = array_keys($this->PlayerRanking->GameType->find('list'));

			foreach ($game_types as $game_type) {
				$this->PlayerRanking->create( );
				$this->PlayerRanking->save(array('PlayerRanking' => array(
					'player_id' => $this->id,
					'game_type_id' => $game_type,
				)), false);
			}

			// create an entry in the PlayerStat table for this player
			// for every game.  the values are defaulted in the table
			$games = array_keys($this->PlayerStat->Game->find('list'));

			foreach ($games as $game) {
				$this->PlayerStat->create( );
				$this->PlayerStat->save(array('PlayerStat' => array(
					'player_id' => $this->id,
					'game_id' => $game,
				)), false);
			}
		}
	}

}

