<?php

App::uses('AppModel', 'Model');

class PlayerRanking extends AppModel {

	public $displayField = 'mean';

	public $validate = array(
		'player_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
		'mean' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'std_deviation' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'games_played' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'max_mean' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'min_mean' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $belongsTo = array(
		'Player',
		'GameType',
	);

	public $hasMany = array(
		'RankHistory' => array(
			'dependent' => true,
		),
	);

	public function beforeSave($options = array( )) {
		// update the global values if needed
		if (array_key_exists('mean', $this->data['PlayerRanking'])) {
			if ($this->id) {
				if (empty($cur_data)) {
					$cur_data = $this->findById($this->id);
				}

				if ( ! array_key_exists('max_mean', $this->data['PlayerRanking']) && ($this->data['PlayerRanking']['mean'] > $cur_data['PlayerRanking']['max_mean'])) {
					$this->data['PlayerRanking']['max_mean'] = $this->data['PlayerRanking']['mean'];
				}
				elseif ( ! array_key_exists('min_mean', $this->data['PlayerRanking']) && ($this->data['PlayerRanking']['mean'] < $cur_data['PlayerRanking']['min_mean'])) {
					$this->data['PlayerRanking']['min_mean'] = $this->data['PlayerRanking']['mean'];
				}
			}
			else {
				if ( ! array_key_exists('max_mean', $this->data['PlayerRanking'])) {
					$this->data['PlayerRanking']['max_mean'] = $this->data['PlayerRanking']['mean'];
				}

				if ( ! array_key_exists('min_mean', $this->data['PlayerRanking'])) {
					$this->data['PlayerRanking']['min_mean'] = $this->data['PlayerRanking']['mean'];
				}
			}
		}

		return parent::beforeSave($options);
	}

}

