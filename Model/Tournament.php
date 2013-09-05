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
		'Game',
	);

	public $hasMany = array(
		'Match' => array(
			'dependent' => true,
		),
		'Team' => array(
			'dependent' => true,
		),
	);


	public function start($data) {
		// sort the player ids to prevent issues later
		sort($data['player_id']);

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
				--$team_size;
			} while ($data['min_team_size'] <= $team_size);

			ksort($sitting_out);
			list($sitting_out, $team_sizes) = each($sitting_out);

			rsort($team_sizes);
			$data['team_size'] = reset($team_sizes);
		}

		if ($data['team_size'] > (int) $data['game']['GameType']['max_team_size']) {
			$data['team_size'] = (int) $data['game']['GameType']['max_team_size'];
		}

		$data['num_teams'] = (int) floor(count($data['player_id']) / $data['team_size']);

		$data['num_sitting_out'] = count($data['player_id']) - ($data['num_teams'] * $data['team_size']);

// do this until a sitting out handler is built
if ($data['num_sitting_out']) {
	throw new CakeException('Teams are not even.  Teams must be even until I get a Sitting Out system built');
}

		$tourny = array(
			'Tournament' => array(
				'game_id' => $data['game']['Game']['id'],
				'tournament_type' => $data['tournament_type'],
				'team_size' => $data['team_size'],
			),
			'Team' => array( ),
			'SittingOut' => array( ),
		);

		$data['sitting_out'] = $this->create_sitting_outs($data);

		list($data['teams'], $data['quality'], $data['seed']) = $this->create_teams($data);

		$tourny['Tournament']['quality'] = $data['quality'] * 100;

		// don't shuffle the teams here, because then the seed keys will no longer match
		// teams get shuffled in create_teams

		foreach ($data['teams'] as $t => $team) {
			shuffle($team);

			$tourny['Team'][$t] = array(
				'start_seed' => $data['seed'][$t] + 1,
				'seed' => $data['seed'][$t] + 1,
				'Player' => array(
					'Player' => array( ),
				),
			);

			foreach ($team as $p) {
				$tourny['Team'][$t]['Player']['Player'][] = $p;
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
				'mean' => ife($player['PlayerRanking']['mean'], $this->getDefaultMean( )),
				'std_dev' => ife($player['PlayerRanking']['std_deviation'], $this->getDefaultStandardDeviation( )),
			);
		}

		// calculate the number of combinations before this function is run
		// as it may run a loooong time
		$num_combos = 1;
		$num_players = count($calc_players);
		while ($num_players) {
			$num_combos *= nCk($num_players, $data['team_size']);
			$num_players -= $data['team_size'];
		}

		// if this function is going to be running for a while
		// just skip it and manually create the teams
		if (150000 >= $num_combos) {
			list($teams, $quality) = $this->calculateBestMatch($calc_players, $data['team_size']);
		}
		else {
			// sort the players by ranking and pull them out in
			// a way that makes the teams as fair as possible
			$player_mean = Set::extract('/mean', $calc_players);
			arsort($player_mean);

			$player_mean = array_keys($player_mean);

			$odd = (1 === ($data['team_size'] % 2));

			$i = 0;
			$teams = array( );
			while (count($player_mean)) {
				$team_size = $data['team_size'];
				$j = 0;

				// if the team size is odd, pull a player from the middle now
				if ($odd) {
					$mid_idx = (int) floor(count($player_mean) / 2);
					$middle = array_splice($player_mean, $mid_idx, 1);

					$teams[$i][$j] = $middle[0];

					--$team_size;
					++$j;
				}

				while (0 < $team_size) {
					// take a player from the top of the array
					$top = array_shift($player_mean);
					$teams[$i][$j] = $top;

					--$team_size;
					++$j;

					// take a player from the bottom of the array
					$bottom = array_pop($player_mean);
					$teams[$i][$j] = $bottom;

					--$team_size;
					++$j;
				}

				++$i;
			}

			$calc_teams = array( );
			foreach ($teams as $t => $team) {
				foreach ($team as $p => $player) {
					$calc_teams[$t][$p] = $calc_players[$player];
				}
			}

			$quality = $this->getQuality($calc_teams);
		}

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

		// convert team indexes to team ids
		foreach ($teams as & $team) { // mind the reference
			foreach ($team as & $player) { // mind the reference
				$player = (int) $calc_players[$player]['id'];
			}
			unset($player); // kill the reference
		}
		unset($team); // kill the reference

		return array($teams, $quality, $seed);
	}


	protected function create_sitting_outs($data) {
		if ( ! $data['num_sitting_out']) {
			return array( );
		}

		// TODO: build this
	}


	protected function round_robin( ) {
		// find any unfinished matches
		$unfinished = $this->Match->find('count', array(
			'conditions' => array(
				'Match.tournament_id' => $this->id,
				'Match.winning_team_id IS NULL',
			),
		));

		if ($unfinished) {
			return true;
		}

		$rankings = $this->get_round_robin_results($this->id);
		if ($rankings) {
			// sort the rankings by wins
			$wins = Set::combine($rankings, '/id', '/wins');

			arsort($wins);

			$team_ids = array( );
			$max_win = 0;
			foreach ($wins as $id => $count) {
				if ($count < $max_win) {
					break;
				}

				$max_win = $count;
				$team_ids[] = $id;
			}

			// there aren't enough teams to create a match
			if (2 > count($team_ids)) {
				return true;
			}

			// grab the last round match
			$last = $this->Match->find('first', array(
				'fields' => array(
					'Match.name',
				),
				'conditions' => array(
					'Match.tournament_id' => $this->id,
				),
				'order' => array(
					'Match.name' => 'DESC',
				),
			));

			if ($last && preg_match('%^Round (\d+):%i', $last['Match']['name'], $round_num)) {
				$round_num = (int) $round_num[1];
				$round_num += 1;
			}
			else {
				throw new CakeException('Previous round name not found');
			}

			shuffle($team_ids);
		}
		else {
			// create all possible matches up front
			// every team plays one game against every other team
			$team_ids = Set::extract('/Team/id', $this->data);

			$round_num = 1;
		}

		$matches = iterator_to_array($this->combinations($team_ids, 2), false);

		shuffle($matches);

		return $this->create_matches($round_num, $matches);
	}


	protected function single_elimination( ) {
		// find any unfinished matches
		$unfinished = $this->Match->find('count', array(
			'conditions' => array(
				'Match.tournament_id' => $this->id,
				'Match.winning_team_id IS NULL',
			),
		));

		if ($unfinished) {
			return true;
		}

		// pull teams that have not played yet, as well as teams that have not lost a match
		// this set of queries does both, split out due to multiple joins issues
		$matches_played = $this->Team->find('all', array(
			'fields' => array(
				'Team.id',
				'IFNULL(COUNT(MatchesPlayed.id), 0) AS matches_played',
			),
			'joins' => array(
				array(
					'table' => 'matches_teams',
					'alias' => 'MatchesPlayed',
					'type' => 'LEFT',
					'conditions' => array(
						'MatchesPlayed.team_id = Team.id'
					),
				),
			),
			'conditions' => array(
				'Team.tournament_id' => $this->id,
			),
			'group' => array(
				'Team.id',
			),
		));
		$matches_played = Set::combine($matches_played, '/Team/id', '/0/matches_played');

		$matches_won = $this->Team->find('all', array(
			'fields' => array(
				'Team.id',
				'IFNULL(COUNT(MatchesWon.id), 0) AS matches_won',
			),
			'joins' => array(
				array(
					'table' => 'matches',
					'alias' => 'MatchesWon',
					'type' => 'LEFT',
					'conditions' => array(
						'MatchesWon.winning_team_id = Team.id',
					),
				),
			),
			'conditions' => array(
				'Team.tournament_id' => $this->id,
			),
			'group' => array(
				'Team.id',
			),
		));
		$matches_won = Set::combine($matches_won, '/Team/id', '/0/matches_won');

		// find all the teams who have either not played, or not lost
		$teams = array( );
		foreach ($matches_played as $team_id => $played) {
			if ($matches_won[$team_id] === $played) {
				$teams[] = $team_id;
			}
		}

		// if there's only one team left, that's our winner
		if (1 >= count($teams)) {
			return true;
		}

		$team_ids = array( );
		foreach ($this->data['Team'] as $team) {
			if (in_array($team['id'], $teams)) {
				$team_ids[$team['seed']] = $team['id'];
			}
		}

		// seed here does not mean starting seed, but rather the highest seeded team in the match
		// that was previously played
		// e.g. in the match 4 v 5 the seed would be 4 regardless of who won
		//		in the match 13 v 20 the seed would be 13, even if 20 won

		// the seed will get updated as the matches are played to reflect the outcome
		// e.g.- if in 13 v 20, 20 wins, then the team that was seed 20 is now 13
		//		and the team that was seed 13 is now 20
		// this will also help with finding out who won

		// if a team hasn't played yet, then seed really is seed, but the end results are the same

		ksort($team_ids);

		$total_team_count = count($this->data['Team']);
		$total_round_count = (int) ceil(log($total_team_count, 2));

		$current_team_count = count($team_ids);
		$current_round_count = (int) ceil(log($current_team_count, 2));

		$round_num = $total_round_count - ($current_round_count - 1);

		$root = pow(2, $current_round_count);

		// if the team count is not divisible by 2^n, remove the highest seeded teams for later
		for ($i = $root - $current_team_count; $i > 0; $i -= 1) {
			array_shift($team_ids);
		}

		$slice = 1;
		$bracket_list = array_values($team_ids);
		while ($slice < (count($bracket_list) / 2)) {
			$temp = $bracket_list;
			$bracket_list = array( );

			while (0 < count($temp)) {
				$bracket_list = array_merge($bracket_list, array_splice($temp, 0, $slice));
				$bracket_list = array_merge($bracket_list, array_splice($temp, -$slice, $slice));
			}

			$slice *= 2;
		}

		$round = array( );
		for ($i = 0, $len = count($bracket_list); $i < $len; $i += 2) {
			$round[] = array(
				$bracket_list[$i],
				$bracket_list[$i + 1],
			);
		}

		return $this->create_matches($round_num, $round);
	}


	protected function create_matches($round_num, $round_teams) {
		$match = array(
			'Match' => array(
				'tournament_id' => $this->id,
			),
			'Team' => array(
				'Team' => array( ),
			),
		);

		foreach ($round_teams as $round_team) {
			$this_match = $match;
			$this_match['Team']['Team'] = $round_team;

			$first_seed = $second_seed = 0;

			$calc_teams = array( );
			foreach ($round_team as $team_id) {
				foreach ($this->data['Team'] as $team) {
					if ((int) $team['id'] !== (int) $team_id) {
						continue;
					}

					if (empty($first_seed)) {
						$first_seed = $team['seed'];
					}
					else {
						$second_seed = $team['seed'];
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
			$this_match['Match']['name'] = 'Round '.$round_num.': #'.$first_seed.' vs #'.$second_seed.'';

			shuffle($this_match['Team']['Team']);

			$this->Match->create( );
			if ( ! $this->Match->saveAssociated($this_match, array('deep' => true))) {
				return false;
			}
		}

		return true;
	}


	public function get_round_robin_results($id = null) {
		if ( ! $id) {
			$id = $this->id;
		}

		$this->contain(array(
			'Game' => array(
				'GameType',
			),
			'Match' => array(
				'Team',
			),
			'Team' => array(
				'Player' => array(
					'PlayerRanking',
				),
			),
		));
		$this->read(null, $id);

		if ('round_robin' !== $this->data['Tournament']['tournament_type']) {
			return false;
		}

		if ( ! $this->data['Match']) {
			return false;
		}

		$outcome = Set::combine($this->data['Team'], '/id', '/');
		foreach ($outcome as & $team) { // mind the reference
			$team['wins'] = 0;
			$team['losses'] = 0;
			$team['draws'] = 0;
			$team['remaining_matches'] = 0;
			unset($team['Player']);
		}
		unset($team); // kill the reference

		// take a look at the rounds and calculate the wins and losses
		foreach ($this->data['Match'] as $match) {
			if (is_null($match['winning_team_id'])) {
				foreach ($match['Team'] as $team) {
					$outcome[$team['id']]['remaining_matches'] += 1;
				}
			}
			elseif (0 === (int) $match['winning_team_id']) {
				foreach ($match['Team'] as $team) {
					$outcome[$team['id']]['draws'] += 1;
				}
			}
			else {
				foreach ($match['Team'] as $team) {
					if ((int) $match['winning_team_id'] === (int) $team['id']) {
						$outcome[$team['id']]['wins'] += 1;
					}
					else {
						$outcome[$team['id']]['losses'] += 1;
					}
				}
			}
		}

		return $outcome;
	}


	public function finish_match($match_id, $winning_team_id) {
		$match = $this->Match->find('first', array(
			'contain' => array(
				'Team',
			),
			'conditions' => array(
				'Match.id' => $match_id,
			),
		));

		$data = array(
			'Match' => array(
				'id' => $match_id,
				'winning_team_id' => $winning_team_id,
			),
		);

		if ( ! $this->Match->save($data)) {
			return false;
		}

		$this->Match->update_rank($match_id);
		$this->Match->update_stats($match_id);

		// swap the team seed if the lower ranked team won
		$team_seed = array( );
		foreach ($match['Team'] as $team) {
			$team_seed[$team['id']] = $team['seed'];
		}

		asort($team_seed);
		$team_ids = array_keys($team_seed);

		if ($team_ids[0] !== $winning_team_id) {
			$team_seed = array_combine(array_keys($team_seed), array_reverse(array_values($team_seed)));

			foreach ($team_seed as $team => $seed) {
				$this->Team->save(array(
					'Team' => array(
						'id' => $team,
						'seed' => $seed,
					),
				), false);
			}
		}

		// grab the tournament now that everything is updated
		$this->contain(array(
			'Game' => array(
				'GameType',
			),
			'Match' => array(
				'Team',
			),
			'Team' => array(
				'Player' => array(
					'PlayerRanking',
				),
			),
		));
		$tournament = $this->read(null, $match['Match']['tournament_id']);

		return $this->{$this->data['Tournament']['tournament_type']}( );
	}

}

