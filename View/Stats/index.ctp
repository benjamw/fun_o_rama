<?php $this->Html->css('tablesorter.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('jquery.tablesorter.js', array('block' => 'script')); ?>
<?php $this->Html->script('stats.js', array('block' => 'scriptBottom')); ?>

<h2>Win-Loss Stats</h2>
<table class="table table-striped table-bordered table-hover table-condensed tablesorter" id="win_loss">
	<thead>
		<tr>
			<th rowspan="3">Player</th>
			<?php foreach ($game_types as $game_type) { ?>
			<th colspan="<?php echo ((count($game_type['Game']) * 2) + 3); ?>"><?php echo $game_type['GameType']['name']; ?></th>
			<?php } ?>
		<tr>
		<?php foreach ($game_types as $game_type) { ?>
			<th rowspan="2">Rank</th>
			<th rowspan="2">Std Dev</th>
			<?php foreach ($game_type['Game'] as $game) { ?>
			<th colspan="2"><?php echo $game['name']; ?></th>
			<?php } ?>
			<th rowspan="2">Total</th>
		<?php } ?>
		</tr>
		<tr>
			<?php foreach ($games as $game) { ?>
			<th title="Wins">W</th>
			<th title="Losses">L</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($win_loss as $player) { ?>
		<tr>
			<td class="name"><span class="badge badge-warning"><?php echo $badges[$player['Player']['id']]; ?></span> <?php echo $this->Html->link($player['Player']['name'], array('controller' => 'players', 'action' => 'view', $player['Player']['id'])); ?></td>

			<?php
				$n = 0;
				foreach ($game_types as $game_type) {
					$rank = $player_rankings[$player['Player']['id']][$game_type['GameType']['id']]['PlayerRanking'];

					echo '<td class="num">'.number_format($rank['mean'], 2).'</th>';
					echo '<td class="num">'.number_format($rank['std_deviation'], 3).'</th>';

					for ($i = $n, $len = $n + count($game_type['Game']); $i < $len; ++$i, ++$n) {
						$game = $player['Game'][$i];

						echo '<td class="num">'.$game['wins'].'</td>';
						echo '<td class="num">'.$game['losses'].'</td>';
					}

					echo '<td class="num">'.$rank['games_played'].'</th>';
				}
			?>

		</tr>
		<?php } ?>
	</tbody>
</table>

