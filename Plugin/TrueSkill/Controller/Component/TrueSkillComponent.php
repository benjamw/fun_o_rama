<?php

$path = App::path('Vendor', 'TrueSkill');

require_once $path[0].'TrueSkill/TrueSkill/TwoTeamTrueSkillCalculator.php';
require_once $path[0].'TrueSkill/Teams.php';
require_once $path[0].'Combinations.php';

use Moserware\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use Moserware\Skills\GameInfo;
use Moserware\Skills\Player;
use Moserware\Skills\Rating;
use Moserware\Skills\Team;
use Moserware\Skills\Teams;
use Moserware\Skills\SkillCalculator;
use Moserware\Skills\Combinations;

class TrueSkillComponent extends Component {

	private $GameInfo;
	private $Calculator;

	public function initialize(Controller $controller) {
		parent::initialize($controller);

		$this->GameInfo = new GameInfo( );
		$this->Calculator = new TwoTeamTrueSkillCalculator( );
	}


	/**
	 * Update the given player rankings based on the outcome
	 *
	 *	$teams = array(
	 *		array(
	 *			array(
	 *				'id' => [player_id],
	 *				'mean' => [player_ranking_mean],
	 *				'std_dev' => [player_ranking_standard_deviation],
	 *			),
	 *			...
	 *		),
	 *		array(
	 *			array(
	 *				'id' => [player_id],
	 *				'mean' => [player_ranking_mean],
	 *				'std_dev' => [player_ranking_standard_deviation],
	 *			),
	 *			...
	 *		),
	 *	);
	 *
	 * @param array teams of Player data including PlayerRanking data
	 * @param int winning team (1 = first team, 2 = second team, 0 = draw)
	 * @return array player data including new ranking data or false on failure
	 */
	public function updatePlayerRankings($in_teams, $outcome) {
		if ((2 !== count($in_teams)) || (count($in_teams[0]) !== count($in_teams[1]))) {
			return false;
		}

		$team = array( );
		for ($i = 0; $i <= 1; ++$i) {
			$team[$i] = new Team( );
			foreach ($in_teams[$i] as $player) {
				$team[$i]->addPlayer(new Player($player['id']), new Rating($player['mean'], $player['std_dev']));
			}
		}

		$teams = Teams::concat($team[0], $team[1]);

		switch ((int) $outcome) {
			case 1:
				$result = array(1, 2);
				break;

			case 2:
				$result = array(2, 1);
				break;

			case 0:
				$result = array(1, 1);
				break;

			default:
				return false;
		}

		$new_ratings = $this->Calculator->calculateNewRatings($this->GameInfo, $teams, $result);

		$return = array( );
		foreach ($teams as $team) {
			foreach ($team->getAllPlayers( ) as $player) {
				$rating = $new_ratings->getRating($player);

				$return[] = array(
					'id' => $player->getId( ),
					'mean' => $rating->getMean( ),
					'std_dev' => $rating->getStandardDeviation( ),
				);
			}
		}

		return $return;
	}


	/**
	 * Calculate the best possible matchup with the given players
	 *
	 *	$players = array(
	 *		array(
	 *			'id' => [player_id],
	 *			'mean' => [player_ranking_mean],
	 *			'std_dev' => [player_ranking_standard_deviation],
	 *		),
	 *		...
	 *	);
	 *
	 *	Odd count players will be handled, but are not recommended
	 *
	 * @param array player data including rank
	 * @return array team arrays of player ids
	 */
	public function calculateBestMatch($players) {
		if ( ! $players) {
			return false;
		}

		$player_spots = array_keys($players);

		$team_quality = array( );
		foreach (new Combinations(implode('', $player_spots), (int) ceil(count($player_spots) / 2)) as $team1_spots) {
			$spots = $team = array( );

			$spots[0] = str_split($team1_spots);
			$spots[1] = array_diff($player_spots, $spots[0]);

			$team[0] = new Team( );
			$team[1] = new Team( );

			for ($i = 0; $i <= 1; ++$i) {
				foreach ($spots[$i] as $spot) {
					$team[$i]->addPlayer(new Player($players[$spot]['id']), new Rating($players[$spot]['mean'], $players[$spot]['std_dev']));
				}
			}

			$teams = Teams::concat($team[0], $team[1]);

			$quality = (string) $this->Calculator->calculateMatchQuality($this->GameInfo, $teams);
			$quality = number_format($quality, 10);

			if ( ! isset($team_quality[$quality])) {
				$team_quality[$quality] = array( );
			}

			$team_quality[$quality][] = $teams;
		}

		krsort($team_quality);
		reset($team_quality);

		$first = each($team_quality);

		$top_quality = $first['key'];
		$top_teams = $first['value'];

		shuffle($top_teams);
		shuffle($top_teams);
		shuffle($top_teams);

		return array(reset($top_teams), $top_quality);
	}

	public function getDefaultMean( ) {
		return $this->GameInfo->getDefaultRating( )->getMean( );
	}

	public function getDefaultStandardDeviation( ) {
		return $this->GameInfo->getDefaultRating( )->getStandardDeviation( );
	}

}

