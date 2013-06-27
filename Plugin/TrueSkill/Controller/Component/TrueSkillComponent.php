<?php

$path = App::path('Vendor', 'TrueSkill');

require_once $path[0].'TrueSkill/TrueSkill/FactorGraphTrueSkillCalculator.php';
require_once $path[0].'TrueSkill/TrueSkill/TwoTeamTrueSkillCalculator.php';
require_once $path[0].'TrueSkill/Teams.php';
require_once $path[0].'Combinations.php';

use Moserware\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
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
	private $TwoTeamCalculator;
	private $MultiTeamCalculator;

	public function initialize(Controller $controller) {
		parent::initialize($controller);

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
	 * @param array player data including rank
	 * @param int optional number of players per team
	 * @return array team arrays of player ids
	 */
	public function calculateBestMatch($players, $team_size = 2) {
		if ( ! $players) {
			return false;
		}

		$num_teams = count($players) / $team_size;

		if ((int) $num_teams != $num_teams) {
			return false;
		}

		$calculator = ((2 === $num_teams) ? $this->TwoTeamCalculator : $this->MultiTeamCalculator);

		$matches = $this->getMatches(count($players), $team_size);

		if ( ! $matches) {
			return false;
		}

		$team_quality = array( );
		foreach ($matches as $teams) {
			$calc_teams = array( );
			foreach ($teams as $player_spots) {
				$team = new Team( );

				foreach ($player_spots as $idx) {
					$team->addPlayer(new Player($players[$idx]['id']), new Rating($players[$idx]['mean'], $players[$idx]['std_dev']));
				}

				$calc_teams[] = $team;
			}

			$quality = (string) $calculator->calculateMatchQuality($this->GameInfo, $calc_teams);
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

	protected function getMatches($num_players, $team_size) {
		$num_teams = $num_players / $team_size;
		if ((int) $num_teams != $num_teams) {
			// the number of players given do not fit into the given team size evenly
			return false;
		}

		// run the recursive function to get all possible teams
		list($matches, ) = $this->getTeams(array( ), range(0, $num_players - 1), (int) $num_teams);

		// flatten the output, but not too much
		while ( ! is_int($matches[0][0][0])) {
			$all_matches = array( );

			foreach ($matches as $match) {
				$all_matches = array_merge($all_matches, $match);
			}

			$matches = $all_matches;
		}

		return $matches;
	}

	// recursive function to get all possible team combinations
	protected function getTeams($teams, $player_spots, $num_teams) {
		sort($player_spots);

		if (1 === $num_teams) {
			$teams[] = $player_spots;
			return array($teams, $player_spots);
		}

		$return_teams = $first_team = $last_team = array( );
		foreach (new Combinations($player_spots, (int) ceil(count($player_spots) / $num_teams)) as $cur_team) {
			// store the first team that was created
			// so we can pass it up the tree
			if ( ! $first_team) {
				$first_team = $cur_team;
			}

			// if the current team has already been seen
			// then stop creating teams, it only repeats from here
			if ($cur_team == $last_team) {
				break;
			}

			$new_teams = $teams;
			$new_teams[] = $cur_team;

			list($return, $last_team) = $this->getTeams($new_teams, array_diff($player_spots, $cur_team), $num_teams - 1);

			$return_teams[] = $return;
		}

		return array($return_teams, $first_team);
	}

	public function getDefaultMean( ) {
		return $this->GameInfo->getDefaultRating( )->getMean( );
	}

	public function getDefaultStandardDeviation( ) {
		return $this->GameInfo->getDefaultRating( )->getStandardDeviation( );
	}

	public function combinations($s, $k) {
		return new Combinations($s, $k);
	}

}

