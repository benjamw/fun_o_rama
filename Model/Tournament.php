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
				// each one of these needs a related function below to generate the matches
				'round_robin',
				'single_elimination',
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
		$data['min_team_size'] = (int) $data['min_team_size'];

		if (empty($data['min_team_size']) || ($data['min_team_size'] > (int) $data['game']['GameType']['max_team_size'])) {
			$data['min_team_size'] = (int) $data['game']['GameType']['max_team_size'];
		}

		if (empty($data['team_size'])) {
			// find the largest teams with even players and the fewest sitting out
			$player_count = count($data['player_id']);
			$team_size = (int) floor($player_count / 2);

			if ($team_size > (int) $data['game']['GameType']['max_team_size']) {
				$team_size = (int) $data['game']['GameType']['max_team_size'];
			}

			$sitting_out = array( );
			do {
				$num_teams = (int) floor($player_count / $team_size);
				$sitting_out[$player_count - ($num_teams * $team_size)][] = $team_size;
			} while ($data['min_team_size'] !== $team_size--);

			ksort($sitting_out);
			list($sitting_out, $team_sizes) = each($sitting_out);

			rsort($team_sizes);
			$data['team_size'] = reset($team_sizes);
		}

		if ($data['team_size'] > (int) $data['game']['GameType']['max_team_size']) {
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

		list($data['teams'], $data['quality'], $data['seed']) = $this->create_teams($data);

		$tourny['Tournament']['quality'] = $data['quality'] * 100;

		// don't shuffle the teams here, because then the seed keys will no longer match
		// teams get shuffled in create_teams

		foreach ($data['teams'] as $t => $team) {
			shuffle($team);

			$tourny['Team'][$t] = array(
				'seed' => $data['seed'][$t] + 1,
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

		// allow a little more time for team generation
		ini_set('max_execution_time', 30); // like the server
		ini_set('memory_limit', '64M'); // like the server

		list($teams, $quality) = $this->calculateBestMatch($calc_players, $data['team_size']);

		// shuffle the teams here because the
		// seed keys need to match the team keys
		shuffle($teams);

		// calculate the team seed
		$player_rankings = Set::combine($players, '/Player/id', '/PlayerRanking/mean');

		$seed = array( );
		foreach ($teams as $t => $team) {
			$ranking = 0;
			foreach ($team as $pl_id) {
				$ranking += $player_rankings[$data['player_id'][$pl_id]];
			}

			$seed[$t] = $ranking;
		}

		arsort($seed);
		$seed = array_flip(array_keys($seed));

		return array($teams, $quality, $seed);
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

		$matches = iterator_to_array($this->combinations($team_ids, 2), false);

		shuffle($matches);

		foreach ($matches as $teams) {
			shuffle($teams);

			$this_match = $match;
			$this_match['Team']['Team'] = $teams;

			$calc_teams = array( );
			foreach ($teams as $team_id) {
				foreach ($this->data['Team'] as $team) {
					if ((int) $team['id'] !== (int) $team_id) {
						continue;
					}

					$calc_team = array( );

					foreach ($team['Player'] as $player) {
						$calc_team[] = array(
							'id' => $player['id'],
							'mean' => $player['PlayerRanking'][0]['mean'],
							'std_dev' => $player['PlayerRanking'][0]['std_deviation'],
						);
					}
				}

				$calc_teams[] = $calc_team;
			}

			$this_match['Match']['quality'] = $this->getQuality($calc_teams) * 100;

			$this->Match->create( );
			$this->Match->saveAssociated($this_match, array('deep' => true));
		}
	}


	protected function single_elimination( ) {
		// generate the first round matches based on number of teams and seed value
		// additional matches will be generated as the rounds are finished
		$team_ids = Set::combine($this->data, '/Team/seed', '/Team/id');

		$match = array(
			'Match' => array(
				'tournament_id' => $this->id,
			),
			'Team' => array(
				'Team' => array( ),
			),
		);

		ksort($team_ids);

		for ($i = 0, $count = count($team_ids); pow(2, $i) < $count; ++$i) {
			// do nothing
			// pow(2, $i) is the result
		}
		$root = pow(2, $i);

		// if the team count is not divisible by 2^n, remove the highest seeded teams for later
		for ($i = $root - $count; $i > 0; $i -= 1) {
			array_shift($team_ids);
		}

		while ($team_ids) {
			// pull the higest and lowest seed and put them together
			$teams = array( );
			$teams[] = array_shift($team_ids);
			$teams[] = array_pop($team_ids);

			$this_match = $match;
			$this_match['Team']['Team'] = $teams;

			$calc_teams = array( );
			foreach ($teams as $team_id) {
				foreach ($this->data['Team'] as $team) {
					if ((int) $team['id'] !== (int) $team_id) {
						continue;
					}

					$calc_team = array( );

					foreach ($team['Player'] as $player) {
						$calc_team[] = array(
							'id' => $player['id'],
							'mean' => $player['PlayerRanking'][0]['mean'],
							'std_dev' => $player['PlayerRanking'][0]['std_deviation'],
						);
					}
				}

				$calc_teams[] = $calc_team;
			}

			$this_match['Match']['quality'] = $this->getQuality($calc_teams) * 100;

			$this->Match->create( );
			$this->Match->saveAssociated($this_match, array('deep' => true));
		}
	}


	/*
	 *	$team_ids = array(
	 *		[seed] = [team_id],
	 *		[seed] = [team_id],
	 *		[seed] = [team_id],
	 *	);
	 */
	function single_elimination_round($team_ids) {
		// find any unfinished matches
		$uninished = $this->Match->find('count', array(
			'conditions' => array(
				'Match.tournament_id' => $this->id,
				'Match.winning_team_id IS NULL',
			),
		));

		if ($unfinished) {
			return false;
		}

		// pull teams that have not played yet, as well as teams that have not lost a match
		$never_played = $this->Team->find('all', array(
			'joins' => array(
				array(
					'table' => 'matches_teams',
					'alias' => 'MatchesTeam',
					'type' => 'left',
					'conditions' => array(
						'MatchesTeam.team_id = Team.id'
					),
				),
			),
			'conditions' => array(
				'Team.tournament_id' => $this->id,
				'MatchesTeam.match_id IS NULL',
			),
			'group' => array(
				'Team.id',
			),
			'order' => array(
				'Team.seed' => 'ASC',
			),
		));

		$never_lost = $this->Team->find('all', array(
			'joins' => array(
				array(
					'table' => 'matches_teams',
					'alias' => 'MatchesTeam',
					'type' => 'left',
					'conditions' => array(
						'MatchesTeam.team_id = Team.id'
					),
				),
				array(
					'table' => 'matches',
					'alias' => 'Matches',
					'type' => 'left',
					'conditions' => array(
						'Matches.id = MatchesTeam.match_id',
						'Matches.winning_team_id',
					),
				),
			),
			'conditions' => array(
				'Team.tournament_id' => $this->id,
			),
			'group' => array(
				'Team.id',
			),
			'order' => array(
				'Team.seed' => 'ASC',
			),
		));

		// seed here does not mean actual seed, but rather the highest seeded team in the match
		// that was previously played
		// e.g. in the match 4 v 5 the seed would be 4 regardless of who won
		//		in the match 13 v 20 the seed would be 13, even if 20 won

		// the seed will get updated as the matches are played to reflect the outcome
		// e.g.- if in 13 v 20, 20 wins, then the team that was seed 20 is now 13
		//		and the team that was seed 13 is now 20
		// this will also help with finding out who won

		// if a team hasn't played yet, then seed really is seed, but the end results are the same

		ksort($team_ids);

		for ($rounds = 0, $count = count($team_ids); ($root = pow(2, $rounds)) < $count; ++$rounds) {
			// do nothing
			// $root = pow(2, $rounds) is the result
		}

		// if the team count is not divisible by 2^n, remove the highest seeded teams for later
		for ($i = $root - $count; $i > 0; $i -= 1) {
			array_shift($team_ids);
		}

		$round = array( );
		while ($team_ids) {
			// pull the higest and lowest seed and put them together
			$teams = array( );
			$teams[] = array_shift($team_ids);
			$teams[] = array_pop($team_ids);

			$round[] = $teams;
		}

		return $round;
	}


	protected function double_elimination( ) {
		// double elimination starts out just like single elimination
		// the difference comes after matches have been played
		$this->single_elimination( );
	}

}

