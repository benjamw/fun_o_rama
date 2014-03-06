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

// ========================================================================

		// player rankings
		$rankings = $this->Player->PlayerRanking->find('all', array(
			'contain' => array(
				'RankHistory' => array(
					'order' => array(
						'RankHistory.created' => 'ASC',
						'RankHistory.id' => 'ASC',
					),
				),
			),
		));

		$player_rankings = array( );
		foreach ($rankings as $ranking) {
			if ( ! isset($player_rankings[$ranking['PlayerRanking']['player_id']])) {
				$player_rankings[$ranking['PlayerRanking']['player_id']] = array( );
			}

			$player_rankings[$ranking['PlayerRanking']['player_id']][$ranking['PlayerRanking']['game_type_id']] = $ranking;
		}

		$this->set('player_rankings', $player_rankings);

// ========================================================================

		// player stats
		$stats = $this->Player->PlayerStat->find('all');

		$player_stats = array( );
		foreach ($stats as $stat) {
			if ( ! isset($player_stats[$stat['PlayerStat']['player_id']])) {
				$player_stats[$stat['PlayerStat']['player_id']] = array( );
			}

			$player_stats[$stat['PlayerStat']['player_id']][$stat['PlayerStat']['game_id']] = $stat;
		}

		$this->set('player_stats', $player_stats);

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
						'table' => 'tournaments',
						'alias' => 'Tournament',
						'type' => 'LEFT',
						'conditions' => array(
							'Tournament.id = Team.tournament_id',
						),
					),
					array(
						'table' => 'matches',
						'alias' => 'Match',
						'type' => 'LEFT',
						'conditions' => array(
							'Match.tournament_id = Tournament.id',
						),
					),
					array(
						'table' => 'games',
						'alias' => 'Game',
						'type' => 'LEFT',
						'conditions' => array(
							'Game.id = Tournament.game_id',
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

