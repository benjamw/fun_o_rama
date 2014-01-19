<?php

App::uses('AppModel', 'Model');

class Game extends AppModel {

	public $validate = array(
		'game_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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

	public $belongsTo = array(
		'GameType',
	);

	public $hasMany = array(
		'PlayerStat' => array(
			'dependent' => true,
		),
		'Tournament' => array(
			'dependent' => true,
		),
	);

	public function afterSave($created, $options = array( )) {
		parent::afterSave($created, $options);

		if ($created) {
			// create an entry in the PlayerStat table for this game
			// for every player.  the values are defaulted in the table
			$players = array_keys($this->PlayerStat->Player->find('list'));

			foreach ($players as $player) {
				$this->PlayerStat->create( );
				$this->PlayerStat->save(array('PlayerStat' => array(
					'player_id' => $player,
					'game_id' => $this->id,
				)), false);
			}
		}
	}

}

