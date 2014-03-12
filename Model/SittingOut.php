<?php

App::uses('AppModel', 'Model');

class SittingOut extends AppModel {

	public $validate = array(
		'tournament_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
	);

	public $belongsTo = array(
		'Tournament' => array(
			'className' => 'Tournament',
			'foreignKey' => 'tournament_id',
			//'conditions' => '',
			//'fields' => '',
			//'order' => '',
		),
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'player_id',
			//'conditions' => '',
			//'fields' => '',
			//'order' => '',
		),
	);


	public function find_sitting_outs($data) {
		if ( ! $data['num_sitting_out']) {
			return array( );
		}

		// list the players who have not sat out yet
		$never_sat_out = $this->Player->find('list', array(
			'fields' => array(
				'Player.id',
				'Player.id',
			),
			'joins' => array(
				array(
					'table' => 'sitting_outs',
					'alias' => 'SittingOut',
					'type' => 'LEFT',
					'conditions' => array(
						'Player.id = SittingOut.player_id',
					),
				),
			),
			'conditions' => array(
				'Player.id' => $data['player_id'],
				'SittingOut.id IS NULL',
			),
		));

		if ( ! empty($never_sat_out)) {
			shuffle($never_sat_out);
			$never_sat_out = array_chunk($never_sat_out, $data['num_sitting_out']);
			$sitting_out = $never_sat_out[0];
		}
		else {
			// pull the least recent sat outs from the list of given players
			$sitting_out = $this->find('list', array(
				'fields' => array(
					'SittingOut.player_id',
					'SittingOut.player_id',
				),
				'conditions' => array(
					'SittingOut.player_id' => $data['player_id'],
				),
				'order' => array(
					'SittingOut.created' => 'ASC',
				),
				'limit' => $data['num_sitting_out'],
			));
		}

		return array_trim($sitting_out, 'int');
	}

}

