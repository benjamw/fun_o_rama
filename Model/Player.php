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

	public $actsAs = array(
		'Image' => array(
			'fields' => array(
				'avatar' => array(
					'resize' => null,
					'thumbnail' => null,
					'versions' => array(
						array(
							'prefix' => 'main',
							'width' => 50,
							'height' => 50,
							'aspect' => true,
							'crop' => true,
							'allow_enlarge' => true,
						),
					),
				),
			),
		),
	);

	public $hasMany = array(
		'PlayerRanking' => array(
			'className' => 'PlayerRanking',
			'foreignKey' => 'player_id',
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

	public $hasAndBelongsToMany = array(
		'Badge' => array(
			'className' => 'Badge',
			'joinTable' => 'badges_players',
			'foreignKey' => 'player_id',
			'associationForeignKey' => 'badge_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
		),
		'Team' => array(
			'className' => 'Team',
			'joinTable' => 'players_teams',
			'foreignKey' => 'player_id',
			'associationForeignKey' => 'team_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
		),
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
		}
	}

}

