<?php

namespace Moserware\Skills\TrueSkill;

require 'PHPSkills/Skills/TrueSkill/TwoTeamTrueSkillCalculator.php';
require 'PHPSkills/Skills/Teams.php';

use Moserware\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use Moserware\Skills\GameInfo;
use Moserware\Skills\Player;
use Moserware\Skills\Rating;
use Moserware\Skills\Team;
use Moserware\Skills\Teams;
use Moserware\Skills\SkillCalculator;

$default_game = new GameInfo( );
$default_rating = $default_game->getDefaultRating( );

$players = array_fill(1, 8, array($default_rating->getMean( ), $default_rating->getStandardDeviation( )));
$player_wins = array_fill(1, 8, 0);

$calculator = new TwoTeamTrueSkillCalculator( );

for ($game = 0; $game < 50; $game += 1) {
	$gameInfo = new GameInfo( );
	$teams = array( );
	$player_order = range(1, 8, 1);

	shuffle($player_order);
	reset($player_order);

	$i = 1;
	for ($team = 1; $team <= 2; $team += 1) {
		$teams[$team] = new Team( );

		for ($player = 1; $player <= 4; $player += 1) {
			$id = each($player_order);
			$id = $id[1];
			$ranking = $players[$id];

			$game_players[$i] = new Player($id);
			$teams[$team]->addPlayer($game_players[$i], new Rating($ranking[0], $ranking[1]));

			++$i;
		}
	}

	$teams = Teams::concat($teams[1], $teams[2]);

	$quality = $calculator->calculateMatchQuality($gameInfo, $teams);

	$player_teams = array_chunk($player_order, 4);

	if (0 === mt_rand(0, 4)) {
		// 20% chance of tie
		$result = array(1, 1);
		$win = false;
	}
	elseif (0 === mt_rand(0, 1)) {
		// 50% chance team 1 wins
		$result = array(1, 2);
		$win = 0;
	}
	else {
		// 50% chance team 2 wins
		$result = array(2, 1);
		$win = 1;
	}

	if (false !== $win) {
		foreach ($player_teams[$win] as $player_id) {
			$player_wins[$player_id]++;
		}
	}

	$newRatingsWinLose = $calculator->calculateNewRatings($gameInfo, $teams, $result);

	foreach ($game_players as $game_player) {
		$rating = $newRatingsWinLose->getRating($game_player);
		$players[$game_player->getId( )] = array($rating->getMean( ), $rating->getStandardDeviation( ));
	}

	$quality = $calculator->calculateMatchQuality($gameInfo, $teams);
}

arsort($player_wins);
g($player_wins);
g($players);

$perm_scores = array( );
$perm_teams = array( );
/*
pc_permute(array_keys($players));

arsort($perm_scores);

$team_ids = array( );
$highest_score = current($perm_scores);
foreach ($perm_scores as $team_id => $score) {
	if ($score !== $highest_score) {
		break;
	}

	$team_ids[] = $team_id;
}

shuffle($team_ids);
shuffle($team_ids);
shuffle($team_ids);
g($team_ids);

g($perm_scores);
//g($perm_teams);

g($perm_teams[$team_ids[0]]);
g( );
*/

function pc_permute($items, $perms = array( )) {
	global $calculator, $gameInfo,
		$players, $perm_scores, $perm_teams;
	if (empty($items)) {
//		print join(' ', $perms) . "\n";

		// here is where to run the match quality calculation on the current team permutation
		// and store it in a couple arrays, one that is just quality score, and another that is
		// the team permutation, so the score one can be sorted, and then the kay pulled and
		// the team permutation found easily
		$teams = null;
		$team = array( );
		$i = 0;
		for ($t = 1; $t <= 2; ++$t) {
			$team[$t] = new Team( );

			for ($player = 1, $len = (int) floor(count($perms) / 2); $player <= $len; $player += 1) {
				list($null, $id) = each($perms);
				$ranking = $players[$id];

				$team[$t]->addPlayer(new Player($id), new Rating($ranking[0], $ranking[1]));

				++$i;
			}
		}

		$teams = Teams::concat($team[1], $team[2]);

		$perm_teams[] = $teams;
		$perm_scores[] = $calculator->calculateMatchQuality($gameInfo, $teams);
	}
	else {
		for ($i = count($items) - 1; $i >= 0; --$i) {
			 $newitems = $items;
			 $newperms = $perms;
			 list($foo) = array_splice($newitems, $i, 1);
			 array_unshift($newperms, $foo);
			 pc_permute($newitems, $newperms);
		 }
	}
}


class Combinations implements \Iterator
{
	protected $c = null;
	protected $s = null;
	protected $n = 0;
	protected $k = 0;
	protected $pos = 0;

	function __construct($s, $k) {
		if(is_array($s)) {
			$this->s = array_values($s);
			$this->n = count($this->s);
		} else {
			$this->s = (string) $s;
			$this->n = strlen($this->s);
		}
		$this->k = $k;
		$this->rewind();
	}
	function key() {
		return $this->pos;
	}
	function current() {
		$r = array();
		for($i = 0; $i < $this->k; $i++)
			$r[] = $this->s[$this->c[$i]];
		return is_array($this->s) ? $r : implode('', $r);
	}
	function next() {
		if($this->_next())
			$this->pos++;
		else
			$this->pos = -1;
	}
	function rewind() {
		$this->c = range(0, $this->k);
		$this->pos = 0;
	}
	function valid() {
		return $this->pos >= 0;
	}

	protected function _next() {
		$i = $this->k - 1;
		while ($i >= 0 && $this->c[$i] == $this->n - $this->k + $i)
			$i--;
		if($i < 0)
			return false;
		$this->c[$i]++;
		while($i++ < $this->k - 1)
			$this->c[$i] = $this->c[$i - 1] + 1;
		return true;
	}
}


foreach(new Combinations("12345678", 4) as $substring)
	echo $substring, ' ';
