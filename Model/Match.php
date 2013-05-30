<?php

App::uses('AppModel', 'Model');

class Match extends AppModel {

	public $displayField = 'created';

	public $validate = array(
		'game_id' => array(
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

	public $belongsTo = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'SatOutPlayer' => array(
			'className' => 'Player',
			'foreignKey' => 'sat_out',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'WinningTeam' => array(
			'className' => 'Team',
			'foreignKey' => 'winning_team_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public $hasMany = array(
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'match_id',
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

}

