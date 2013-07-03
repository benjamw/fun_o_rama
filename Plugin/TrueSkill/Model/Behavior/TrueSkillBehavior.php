<?php

$path = App::path('Vendor', 'TrueSkill');

require_once $path[0].'TrueSkill/TrueSkill/FactorGraphTrueSkillCalculator.php';
require_once $path[0].'TrueSkill/TrueSkill/TwoTeamTrueSkillCalculator.php';
require_once $path[0].'TrueSkill/Teams.php';
require_once $path[0].'Combinations.php';
require_once $path[0].'TeamCombinations.php';

use Moserware\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use Moserware\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use Moserware\Skills\GameInfo;
use Moserware\Skills\Player;
use Moserware\Skills\Rating;
use Moserware\Skills\Team;
use Moserware\Skills\Teams;
use Moserware\Skills\SkillCalculator;
use Moserware\Skills\Combinations;
use Moserware\Skills\TeamCombinations;

class TrueSkillBehavior extends ModelBehavior {

	private $GameInfo;
	private $TwoTeamCalculator;
	private $MultiTeamCalculator;


	/**
	 * Setup the behavior
	 *
	 * @param Model linked model (not used)
	 * @param array optional configuration data
	 * @return void
	 */
	public function setup(Model $model, $config = array( )) {
		parent::setup($model, $config);

		$this->GameInfo = new GameInfo( );
		$this->TwoTeamCalculator = new TwoTeamTrueSkillCalculator( );
		$this->MultiTeamCalculator = new FactorGraphTrueSkillCalculator( );
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
	 * @param Model linked model (not used)
	 * @param array teams of Player data including PlayerRanking data
	 * @param int winning team (1 = first team, 2 = second team, 0 = draw)
	 * @return array player data including new ranking data or false on failure
	 */
	public function updatePlayerRankings(Model $model, $in_teams, $outcome) {
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

		$new_ratings = $this->TwoTeamCalculator->calculateNewRatings($this->GameInfo, $teams, $result);

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
	 * @param Model linked model (not used)
	 * @param array player data including rank
	 * @param int optional number of players per team
	 * @return array team arrays of player ids
	 */
	public function calculateBestMatch(Model $model, $players, $team_size = 2) {
		if ( ! $players) {
			return false;
		}

		$num_teams = count($players) / $team_size;

		if ((int) $num_teams != $num_teams) {
			return false;
		}

		$calculator = ((2 === $num_teams) ? $this->TwoTeamCalculator : $this->MultiTeamCalculator);

// TODO: this whole thing is running out of memory
// need to find a way to iterate through all possible matches
// while keeping the memory footprint small and also running as fast as possible

		$team_quality = array( );
		$highest_quality = 0;
		foreach (new TeamCombinations(range(0, count($players) - 1), $team_size) as $teams) {
			$calc_teams = array( );
			foreach ($teams as $player_spots) {
				$team = new Team( );

				foreach ($player_spots as $idx) {
					$team->addPlayer(new Player($players[$idx]['id']), new Rating($players[$idx]['mean'], $players[$idx]['std_dev']));
				}

				$calc_teams[] = $team;
			}

			$quality = $calculator->calculateMatchQuality($this->GameInfo, $calc_teams);

			if ($quality < $highest_quality) {
				continue;
			}
			elseif ($quality > $highest_quality) {
				unset($team_quality);
				$team_quality = array( );
			}

			$highest_quality = $quality;

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

		return array(reset($top_teams), $top_quality);
	}


	/**
	 * Calculate the match quality with the given teams
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
	 * @param Model linked model (not used)
	 * @param array teams of Player data including PlayerRanking data
	 * @return float match quality
	 */
	public function getQuality(Model $model, $teams) {
		$num_teams = count($teams);

		$calculator = ((2 === $num_teams) ? $this->TwoTeamCalculator : $this->MultiTeamCalculator);

		foreach ($teams as $players) {
			$team = new Team( );

			foreach ($players as $player) {
				$team->addPlayer(new Player($player['id']), new Rating($player['mean'], $player['std_dev']));
			}

			$calc_teams[] = $team;
		}

		return $calculator->calculateMatchQuality($this->GameInfo, $calc_teams);
	}


	/**
	 * Getter for the default mean value
	 *
	 * @param Model linked model (not used)
	 * @return float default mean
	 */
	public function getDefaultMean(Model $model) {
		return $this->GameInfo->getDefaultRating( )->getMean( );
	}


	/**
	 * Getter for the default standard deviation value
	 *
	 * @param Model linked model (not used)
	 * @return float default standard deviation
	 */
	public function getDefaultStandardDeviation(Model $model) {
		return $this->GameInfo->getDefaultRating( )->getStandardDeviation( );
	}


	/**
	 * Getter for the Combinations Iterator
	 *
	 * @param Model linked model (not used)
	 * @param array|string set S of elements
	 * @param int k number of elements to choose
	 * @return Combinations Iterator
	 */
	public function combinations(Model $model, $s, $k) {
		return new Combinations($s, $k);
	}

}

