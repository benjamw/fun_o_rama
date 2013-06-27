<?php

App::uses('AppModel', 'Model');

class Tournament extends AppModel {

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
		'tournament_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'team_size' => array(
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

	public $actsAs = array(
		'Enum.Enum' => array(
			'tournament_type' => array(
				'round_robin',
//				'single_elimination',
//				'double_elimination',
			),
		),
		'TrueSkill.TrueSkill',
	);

	public $belongsTo = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public $hasMany = array(
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'tournament_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'tournament_id',
			'dependent' => false,
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


	public function start($data) {
		// grab the max team size from the game type
		$data['game'] = $this->Game->find('first', array(
			'contain' => array(
				'GameType',
			),
			'conditions' => array(
				'Game.id' => (int) $data['game_id'],
			),
		));

		if ( ! $data['game']) {
			throw new CakeException('Invalid Game Type');
		}

		$data['team_size'] = (int) $data['team_size'];
		if (empty($data['team_size']) || ($data['team_size'] > (int) $game['GameType']['max_team_size'])) {
			$data['team_size'] = (int) $data['game']['GameType']['max_team_size'];
		}

		$data['num_teams'] = (int) floor(count($data['player_id']) / $data['team_size']);

		$data['num_byes'] = count($data['player_id']) - ($data['num_teams'] * $data['team_size']);

// do this until a bye handler is built
if ($data['num_byes']) {
	throw new CakeException('Teams are not even.  Teams must be even until I get a Bye system built');
}

		$tourny = array(
			'Tournament' => array(
				'game_id' => $data['game']['Game']['id'],
				'tournament_type' => $data['tournament_type'],
				'team_size' => $data['team_size'],
			),
			'Team' => array( ),
			'Bye' => array( ),
		);

		$data['byes'] = $this->create_byes($data);

		list($data['teams'], $data['quality']) = $this->create_teams($data);

		shuffle($data['teams']);

		foreach ($data['teams'] as $t => $team) {
			shuffle($team);

			$tourny['Team'][$t] = array(
				'Player' => array(
					'Player' => array( ),
				),
			);

			foreach ($team as $p) {
				$tourny['Team'][$t]['Player']['Player'][] = $data['player_id'][$p];
			}
		}

		$this->create( );
		if ( ! $this->saveAssociated($tourny, array('deep' => true))) {
			return false;
		}

		$this->contain(array(
			'Team' => array(
				'Player' => array(
					'PlayerRanking' => array(
						'conditions' => array(
							'game_type_id' => $data['game']['GameType']['id'],
						),
					),
				),
			),
		));
		$this->read(null, $this->id);

		// now create the matches based on the tournament type
		$this->{$data['tournament_type']}( );

		return $this->id;
	}

	protected function create_teams($data) {
		// pull all the player data
		$players = $this->Team->Player->find('all', array(
			'fields' => array(
				'Player.*',
				'PlayerRanking.*',
			),
			'joins' => array(
				array(
					'table' => 'player_rankings',
					'alias' => 'PlayerRanking',
					'type' => 'LEFT',
					'conditions' => array(
						'Player.id = PlayerRanking.player_id',
						'PlayerRanking.game_type_id' => $data['game']['GameType']['id'],
					),
				),
			),
			'conditions' => array(
				'Player.id' => $data['player_id'],
			),
		));

		$calc_players = array( );
		foreach ($players as $player) {
			$calc_players[] = array(
				'id' => $player['Player']['id'],
				'mean' => ife($player['PlayerRanking']['mean'], 25),
				'std_dev' => ife($player['PlayerRanking']['std_deviation'], 8.333333333333),
			);
		}

		return $this->calculateBestMatch($calc_players, $data['team_size']);
	}

	protected function create_byes($data) {
		if ( ! $data['num_byes']) {
			return array( );
		}

		// TODO: build this
	}


	protected function round_robin( ) {
		// create all possible matches up front
		// every team plays one game against every other team
		$team_ids = Set::extract('/Team/id', $this->data);

		$match = array(
			'Match' => array(
				'tournament_id' => $this->id,
			),
			'Team' => array(
				'Team' => array( ),
			),
		);

		foreach ($this->combinations($team_ids, 2) as $teams) {
			$this_match = $match;
			$this_match['Team']['Team'] = $teams;

			$this->Match->create( );
			$this->Match->saveAssociated($this_match, array('deep' => true));
		}
	}


	protected function single_elimination( ) {
		// TODO: build this
	}

}

