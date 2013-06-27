<?php

App::uses('AppModel', 'Model');

class GameType extends AppModel {

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
		'max_team_size' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $hasMany = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_type_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		),
		'PlayerRanking' => array(
			'className' => 'PlayerRanking',
			'foreignKey' => 'game_type_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		),
	);

	public function afterSave($created) {
		parent::afterSave($created);

		if ($created) {
			// create an entry in the PlayerRanking table for this game type
			// for every player.  the values are defaulted in the table
			$players = array_keys($this->PlayerRanking->Player->find('list'));

			foreach ($players as $player) {
				$this->PlayerRanking->create( );
				$this->PlayerRanking->save(array('PlayerRanking' => array(
					'player_id' => $player,
					'game_type_id' => $this->id,
				)), false);
			}
		}
	}

}

