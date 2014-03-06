<?php

	$this->layout = 'ajax';

	// find the match we clicked on
	foreach ($tourny['Match'] as $tourny_match) {
		if ($match['Match']['id'] === $tourny_match['id']) {
			// $tourny_match is correct
			break;
		}
	}

?>

<table class="table table-striped table-bordered table-condensed tablesorter details">
	<thead>
		<tr>
			<th class="header">Team</th>
			<th class="header">Player</th>
			<th class="header">Prev</th>
			<th class="header">Rank</th>
			<th class="header">Change</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ($tourny_match['Team'] as $team) {

			foreach ($tourny['Team'] as $this_team) {
				if ((int) $team['id'] !== (int) $this_team['id']) {
					continue;
				}

				foreach ($this_team['Player'] as $idx => $player) {
					?>

		<tr class="table-hover">
			<?php if (0 === $idx) { ?><td rowspan="<?php echo count($this_team['Player']); ?>"><?php echo $team['name']; ?></td><?php } ?>
			<td><?php echo $player['name']; ?></td>
			<td><?php echo number_format($rank_histories[$player['id']]['RankHistory']['prev_mean'], 2); ?></td>
			<td><?php echo number_format($rank_histories[$player['id']]['RankHistory']['mean'], 2); ?></td>
			<td><?php echo ((0 < $rank_histories[$player['id']]['RankHistory']['mean_diff']) ? '+' : '&minus;') . number_format(abs($rank_histories[$player['id']]['RankHistory']['mean_diff']), 2); ?></td>
		</tr>

					<?php
				}
			}
		}
	?>
	</tbody>
</table>

<?php echo $this->element($tourny['Tournament']['tournament_type'], compact('tourny')); ?>

