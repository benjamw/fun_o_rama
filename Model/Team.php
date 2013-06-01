<?php

App::uses('AppModel', 'Model');

class Team extends AppModel {

	public $validate = array(
		'match_id' => array(
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
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public $hasAndBelongsToMany = array(
		'Player' => array(
			'className' => 'Player',
			'joinTable' => 'players_teams',
			'foreignKey' => 'team_id',
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
		if ( ! isset($this->data['Team']['name']) && ! isset($this->data['Team']['id'])) {
			$this->data['Team']['name'] = $this->generate_name( );
		}

		return true;
	}

	public function generate_name($alliteration = null) {
		if (true === $alliteration) {
			$alliteration = chr(mt_rand(65, 90));
		}

		$conds = array( );
		if ($alliteration) {
			$conds = array(
				'conditions' => array(
					'name LIKE' => strtolower($alliteration).'%',
					'name NOT LIKE' => '% %',
					'name NOT LIKE' => '%-%',
				),
			);
		}

		$adjectives = m('Adjective')->find('list', $conds);
		$colors = m('Color')->find('list', $conds);
		$animals = m('Animal')->find('list', $conds);

		shuffle($adjectives);
		shuffle($adjectives);
		shuffle($adjectives);

		shuffle($colors);
		shuffle($colors);
		shuffle($colors);

		shuffle($animals);
		shuffle($animals);
		shuffle($animals);

		$name = trim(reset($adjectives)).' '.trim(reset($colors)).' '.Inflector::pluralize(trim(reset($animals)));

		$hyphen_pos = strposall($name, '-');
		$name = str_replace('-', ' ', $name);

		$name = ucwords($name);

		if ($hyphen_pos) {
			foreach ($hyphen_pos as $pos) {
				$name[$pos] = '-';
			}
		}

		return $name;
	}

}
