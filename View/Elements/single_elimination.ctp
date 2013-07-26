<?php $this->Html->css('bracket.css', null, array('inline' => false)); ?>

<?php

	// setting this as global kills it, so store it elsewhere for a bit
	$temp = $tourny;

	// set some global vars for the recursive function
	global $tourny,
		$total_team_count, $total_round_count, $root,
		$team_id, $team_seed, $match_name,
		$rounds;

	$tourny = $temp;
	unset($temp);

	 // pull the teams in an easier to read format
	$team_id = Set::combine($tourny['Team'], '/id', '/');
	$team_seed = Set::combine($tourny['Team'], '/start_seed', '/');
	$match_name = Set::combine($tourny['Match'], '/name', '/');

	// figure out the bracket root
	$total_team_count = count($tourny['Team']);
	$total_round_count = (int) ceil(log($total_team_count, 2));

	$root = pow(2, $total_round_count);

	// create each round's bracket list and store in rounds array
	$rounds = array( );
	$round_count = $total_round_count;
	while ($round_count) {
		$team_ids = range(1, pow(2, $round_count));

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

		$round_count -= 1;
		$rounds[$total_round_count - $round_count] = $bracket_list;
	}

	$rounds[$total_round_count + 1][0] = 1;

	// remove seed placements for teams that do not exist
	foreach ($rounds as & $round) { // mind the reference
		foreach ($round as $idx => & $seed) { // mind the reference
			if (empty($team_seed[$seed])) {
				$seed = false;

				// remove the team pair as well
				if (1 === ($idx % 2)) {
					$round[$idx - 1] = false;
				}
			}
		}
		unset($seed); // kill the reference
	}
	unset($round); // kill the reference

	// this function works backwards, starting at round n and working
	// towards round 1, because that's how the HTML is built out
if ( ! function_exists('tourny_round')) {
	function tourny_round($round, $path = array( )) {
		// i know this is bad, but it's so much easier
		// i'm not editing anything in the globals list, just reading.
		// anything that gets edited or passed along gets passed as arguments
		global $tourny,
			$total_team_count, $total_round_count, $root,
			$team_id, $team_seed, $match_name,
			$rounds;

		if ( ! $round) {
			return false;
		}

		$empty = false;

		$idx = bindec(implode($path));

		$top_idx = bindec(implode($path).'0');
		$bot_idx = bindec(implode($path).'1');

		// create the match name for the previous match to pull the winner of that match
		$name = 'Round '.($round - 1).': #'.$rounds[$round - 1][$top_idx].' vs #'.$rounds[$round - 1][$bot_idx];

		// find the match that has that name
		if ( ! empty($match_name[$name])) {
			if (is_null($match_name[$name]['winning_team_id'])) {
				$winner = '';
			}
			else {
				// TODO: replace this with a more detailed team name with hover states and all that
				$winner = $team_id[$match_name[$name]['winning_team_id']]['name'];
			}
		}
// TODO: find a way to figure out if the round has been generated yet
// and don't show anything if it hasn't
// because the ELSE below will catch everything
		else {
			if (2 === $round) {
				$empty = empty($rounds[$round - 1][$top_idx]);

				if ($empty) {
					// this round should show a seed team
					// the winner is a higher seeded team that had a bye
					// drop down a round, and grab the team seeded in this spot
					$winner = '#'.$rounds[$round][$idx].'- ';
					// TODO: replace this with a more detailed team name with hover states and all that
					$winner .= $team_seed[$rounds[$round][$idx]]['name'];
				}
			}

			if ( ! isset($winner)) {
				// search the previous rounds, and see if they've been played yet
				$test_round = $round;
				$round_names = array_keys($match_name);
				$found = false;
				while ($test_round && ! $found) {
					foreach ($round_names as $round_name) {
						if (0 === strpos($round_name, 'Round '.$test_round)) {
							$found = true;

							// $test_round is what we are looking for
							break 2;
						}
					}
					$test_round -= 1;
				}

				if ($test_round === ($round - 1)) {
					// there are other games in this round, show the seeded team
					// the winner is a higher seeded team that had a bye
					// drop down a round, and grab the team seeded in this spot
					$winner = '#'.$rounds[$round - 1][$top_idx].'- ';
					// TODO: replace this with a more detailed team name with hover states and all that
					$winner .= $team_seed[$rounds[$round - 1][$top_idx]]['name'];
				}
				else {
					// this round has not been generated yet, don't show anything
					$winner = '';
				}
			}
		}

		?>

		<div class="round<?php echo $round; ?>-<?php echo (('0' === end($path)) ? 'top' : 'bottom'); ?><?php if (array( ) === $path) { echo ' winner'.$round; } ?>"><?php echo $winner; ?></div>
		<div class="round<?php echo ($round - 1); ?>-top<?php if (1 !== ($round - 1)) { echo 'wrap'; } ?><?php if ($empty) { echo ' empty'; } ?>">
			<?php $top_path = $path; ?>
			<?php $top_path[] = '0'; ?>
			<?php if (1 < ($round - 1)) { ?>
				<?php echo tourny_round($round - 1, $top_path); ?>
			<?php
				}
				elseif ( ! $empty) {
					// look in Round 1, for the given location
					echo '#'.$rounds[1][$top_idx].'- ';
					// TODO: replace this with a more detailed team name with hover states and all that
					echo $team_seed[$rounds[1][$top_idx]]['name'];
				}
			?>

		</div>
		<div class="round<?php echo ($round - 1); ?>-bottom<?php if (1 !== ($round - 1)) { echo 'wrap'; } ?><?php if ($empty) { echo ' empty'; } ?>">
			<?php $bottom_path = $path; ?>
			<?php $bottom_path[] = '1'; ?>
			<?php if (1 < ($round - 1)) { ?>
				<?php echo tourny_round($round - 1, $bottom_path); ?>
			<?php
				}
				elseif ( ! $empty) {
					// look in Round 1, for the given location
					echo '#'.$rounds[1][$bot_idx].'- ';
					// TODO: replace this with a more detailed team name with hover states and all that
					echo $team_seed[$rounds[1][$bot_idx]]['name'];
				}
			?>

		</div>
		<?php
	}
}

?>

<div class="well clearfix" id="tourny_<?php echo $tourny['Tournament']['id']; ?>">
	<div class="tournament<?php echo $root; ?>-wrap">
		<?php tourny_round($total_round_count + 1); ?>
	</div>
</div>

