<?php

App::uses('AppController', 'Controller');

class StatsController extends AppController {

	public $uses = array('Game', 'Player', 'Match', 'Team');

	public function index( ) {
		$game_types = $this->Game->GameType->find('all', array(
			'contain' => array(
				'Game' => array(
					'order' => array(
						'Game.name',
					),
				),
			),
			'order' => array(
				'GameType.name' => 'ASC',
			),
		));

		$this->set('game_types', $game_types);

		// win-loss stats for each player for each game
		$games = $this->Game->find('list', array(
			'joins' => array(
				array(
					'table' => 'game_types',
					'alias' => 'GameType',
					'type' => 'INNER',
					'conditions' => array(
						'GameType.id = Game.game_type_id',
					),
				),
			),
			'order' => array(
				'GameType.name' => 'ASC',
				'Game.name' => 'ASC',
			),
		));

		$this->set('games', $games);

		// player rankings
		$rankings = $this->Player->PlayerRanking->find('all');

		$player_rankings = array( );
		foreach ($rankings as $ranking) {
			if ( ! isset($player_rankings[$ranking['PlayerRanking']['player_id']])) {
				$player_rankings[$ranking['PlayerRanking']['player_id']] = array( );
			}

			$player_rankings[$ranking['PlayerRanking']['player_id']][$ranking['PlayerRanking']['game_type_id']] = $ranking;
		}

		$this->set('player_rankings', $player_rankings);

		// the base query
		$query = array(
			'fields' => array(
				'Player.*',
			),
			'joins' => array(
				array(
					'table' => 'players_teams',
					'alias' => 'PlayerTeam',
					'type' => 'LEFT',
					'conditions' => array(
						'PlayerTeam.player_id = Player.id',
					),
				),
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type' => 'LEFT',
					'conditions' => array(
						'Team.id = PlayerTeam.team_id',
					),
				),
			),
			'conditions' => array( ),
			'group' => array(
				'Player.id',
			),
			'order' => array(
				'Player.name' => 'ASC',
			),
		);

		$i = 0;
		foreach ($games as $game_id => $game_name) {
			++$i;

			$query['fields'] = array_merge($query['fields'], array(
				'"'.$game_name.'" AS game_'.$i.'_name',
				'"'.$game_id.'" AS game_'.$i.'_id',
				'COUNT(Game'.$i.'WinMatches.id) AS game_'.$i.'_wins',
				'COUNT(Game'.$i.'LoseMatches.id) AS game_'.$i.'_losses',
			));

			$query['joins'][] = array(
				'table' => 'matches',
				'alias' => 'Game'.$i.'WinMatches',
				'type' => 'LEFT',
				'conditions' => array(
					'Game'.$i.'WinMatches.id = Team.match_id',
					'Game'.$i.'WinMatches.winning_team_id = Team.id',
					'Game'.$i.'WinMatches.game_id' => $game_id,
				),
			);

			$query['joins'][] = array(
				'table' => 'matches',
				'alias' => 'Game'.$i.'LoseMatches',
				'type' => 'LEFT',
				'conditions' => array(
					'Game'.$i.'LoseMatches.id = Team.match_id',
					'Game'.$i.'LoseMatches.winning_team_id <> Team.id',
					'Game'.$i.'LoseMatches.winning_team_id <>' => 0,
					'Game'.$i.'LoseMatches.game_id' => $game_id,
				),
			);
		}

		$win_loss = $this->Player->find('all', $query);

		$type_games = array( );
		foreach ($game_types as $game_type) {
			foreach ($game_type['Game'] as $game) {
				$type_games[$game['id']] = $game_type['GameType']['id'];
			}
		}

		foreach ($win_loss as & $player) { // mind the reference
			$games = $player[0];
			unset($player[0]);

			$player['Game'] = array( );
			foreach ($games as $key => $value) {
				$key = explode('_', $key);

				$player['Game'][$key[1]][$key[2]] = $value;
			}

			$player['Game'] = array_values($player['Game']);
		}
		unset($player); // kill the reference

		$this->set('win_loss', $win_loss);

// ========================================================================

		// player's favorite games
		$players = $this->Player->find('all', array(
			'order' => array(
				'Player.name' => 'ASC',
			),
		));

		foreach ($players as & $player) { // mind the reference
			$query = array(
				'fields' => array(
					'Game.*',
					'COUNT(Match.id) AS played',
				),
				'joins' => array(
					array(
						'table' => 'players_teams',
						'alias' => 'PlayerTeam',
						'type' => 'LEFT',
						'conditions' => array(
							'PlayerTeam.player_id = Player.id',
						),
					),
					array(
						'table' => 'teams',
						'alias' => 'Team',
						'type' => 'LEFT',
						'conditions' => array(
							'Team.id = PlayerTeam.team_id',
						),
					),
					array(
						'table' => 'matches',
						'alias' => 'Match',
						'type' => 'LEFT',
						'conditions' => array(
							'Match.id = Team.match_id',
						),
					),
					array(
						'table' => 'games',
						'alias' => 'Game',
						'type' => 'LEFT',
						'conditions' => array(
							'Game.id = Match.game_id',
						),
					),
				),
				'conditions' => array(
					'Player.id' => $player['Player']['id'],
					'Match.winning_team_id IS NOT NULL',
				),
				'group' => array(
					'Game.id',
				),
				'order' => array(
					'played' => 'DESC',
				),
			);

			$played = $this->Player->find('all', $query);

			foreach ($played as & $game) { // mind the reference
				$game['Game']['played'] = $game[0]['played'];
				unset($game[0]);
			}
			unset($game); // kill the reference

			$player['Player']['Played'] = $played;
		}
		unset($player); // kill the reference

		$this->set('players', $players);

// ========================================================================

		// badges
		$badges = $this->Player->find('all', array(
			'fields' => array(
				'Player.id',
				'IFNULL(COUNT(BadgesPlayer.id), 0) AS badges',
			),
			'joins' => array(
				array(
					'table' => 'badges_players',
					'alias' => 'BadgesPlayer',
					'type' => 'LEFT',
					'conditions' => array(
						'Player.id = BadgesPlayer.player_id',
					),
				),
			),
			'group' => array(
				'Player.id',
			),
		));
		$this->set('badges', Set::combine($badges, '/Player/id', '/0/badges'));
	}

}

