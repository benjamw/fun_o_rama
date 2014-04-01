<?php

App::uses('AppModel', 'Model');

class PlayerStat extends AppModel {

	public $displayField = 'player_id';

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
		'wins' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'draws' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'losses' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'streak' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'global_wins' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'global_draws' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'global_losses' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'max_streak' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'min_streak' => array(
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
		'Player',
		'Game',
	);

	public function beforeSave($options = array( )) {
		// update the global values if needed
		if (array_key_exists('streak', $this->data['PlayerStat'])) {
			if ($this->id) {
				if (empty($cur_data)) {
					$cur_data = $this->findById($this->id);
				}

				if ($this->data['PlayerStat']['streak'] > $cur_data['PlayerStat']['max_streak']) {
					$this->data['PlayerStat']['max_streak'] = $this->data['PlayerStat']['streak'];
				}
				elseif ($this->data['PlayerStat']['streak'] < $cur_data['PlayerStat']['min_streak']) {
					$this->data['PlayerStat']['min_streak'] = $this->data['PlayerStat']['streak'];
				}
			}
			else {
				if ($this->data['PlayerStat']['streak'] > 0) {
					$this->data['PlayerStat']['max_streak'] = $this->data['PlayerStat']['streak'];
				}
				else {
					$this->data['PlayerStat']['min_streak'] = $this->data['PlayerStat']['streak'];
				}
			}
		}

		foreach (array('wins', 'draws', 'losses') as $type) {
			if (array_key_exists($type, $this->data['PlayerStat'])) {
				if ($this->id) {
					if (empty($cur_data)) {
						$cur_data = $this->findById($this->id);
					}

					$difference = $this->data['PlayerStat'][$type] - $cur_data['PlayerStat'][$type];
					$this->data['PlayerStat']['global_'.$type] = $cur_data['PlayerStat']['global_'.$type] + $difference;
				}
				else {
					$this->data['PlayerStat']['global_'.$type] = $this->data['PlayerStat'][$type];
				}
			}
		}

		return parent::beforeSave($options);
	}

}

