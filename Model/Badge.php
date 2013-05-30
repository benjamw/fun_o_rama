<?php

App::uses('AppModel', 'Model');

class Badge extends AppModel {

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
		'description' => array(
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
				'icon' => array(
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

	public $hasAndBelongsToMany = array(
		'Player' => array(
			'className' => 'Player',
			'joinTable' => 'badges_players',
			'foreignKey' => 'badge_id',
			'associationForeignKey' => 'player_id',
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

	public function beforeSave($options = array( )) {
		if ( ! empty($this->data['Badge']['name'])) {
			$this->data['Badge']['name'] = strip_tags($this->data['Badge']['name']);
		}

		if ( ! empty($this->data['Badge']['name'])) {
			$this->data['Badge']['name'] = strip_tags($this->data['Badge']['name']);
		}

		return parent::beforeSave($options);
	}

}

