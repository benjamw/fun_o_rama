<?php $this->Html->css('bracket.css', null, array('block' => 'css')); ?>
<?php $this->Html->css('tablesorter.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('jquery.tablesorter.js', array('block' => 'script')); ?>
<?php $this->Html->script('stats.js', array('block' => 'scriptBottom')); ?>

<h2>Win-Loss Stats</h2>
<table class="table table-striped table-bordered table-hover table-condensed tablesorter" id="win_loss">
	<thead>
		<tr>
			<th rowspan="3" class="name">Player</th>
		<?php foreach ($game_types as $game_type) { ?>
			<?php if ( ! $has_data[$game_type['GameType']['id']]) { continue; } ?>
			<th colspan="<?php echo ((count($game_type['Game']) * 3) + 3); ?>" class="type"><?php echo $game_type['GameType']['name']; ?></th>
		<?php } ?>
		</tr>
		<tr>
		<?php foreach ($game_types as $game_type) { ?>
			<?php if ( ! $has_data[$game_type['GameType']['id']]) { continue; } ?>
			<th rowspan="2" class="rank">Rank</th>
			<th rowspan="2">Std Dev</th>
			<?php foreach ($game_type['Game'] as $game) { ?>
			<th colspan="3" class="game"><?php echo $game['name']; ?></th>
			<?php } ?>
			<th rowspan="2" class="total">Total</th>
		<?php } ?>
		</tr>
		<tr>
		<?php foreach ($game_types as $game_type) { ?>
			<?php if ( ! $has_data[$game_type['GameType']['id']]) { continue; } ?>
			<?php foreach ($game_type['Game'] as $game) { ?>
			<th title="Wins" class="win">W</th>
			<th title="Losses" class="loss">L</th>
			<th title="Streak" class="streak">S</th>
			<?php } ?>
		<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($players as $player) { ?>
			<?php
				$player_data = false;
				foreach ($game_types as $game_type) {
					$player_data = $player_data || ! is_null($player_rankings[$player['Player']['id']][$game_type['GameType']['id']]['PlayerRanking']['mean']);
				}

				if ( ! $player_data) { continue; }

				if ('Guest' === $player['Player']['name']) { continue; }
			?>
		<tr>
			<td class="name"><span class="badge badge-warning"><?php echo $badges[$player['Player']['id']]; ?></span> <?php echo $this->Html->link($player['Player']['name'], array('controller' => 'players', 'action' => 'view', $player['Player']['id'])); ?></td>

			<?php
				foreach ($game_types as $game_type) {
					if ( ! $has_data[$game_type['GameType']['id']]) { continue; }

					$rank = $player_rankings[$player['Player']['id']][$game_type['GameType']['id']]['PlayerRanking'];

					if ( ! is_null($rank['mean'])) {
						echo '<td class="num rank">'.number_format($rank['mean'], 2).'</td>';
						echo '<td class="num">'.number_format($rank['std_deviation'], 3).'</td>';
					}
					else {
						echo '<td class="num rank empty"></td>';
						echo '<td class="num empty"></td>';
					}

					foreach ($game_type['Game'] as $game) {
						echo '<td class="num win">'.$player_stats[$player['Player']['id']][$game['id']]['PlayerStat']['wins'].'</td>';
						echo '<td class="num loss">'.$player_stats[$player['Player']['id']][$game['id']]['PlayerStat']['losses'].'</td>';
						echo '<td class="num streak">'.$player_stats[$player['Player']['id']][$game['id']]['PlayerStat']['streak'].'</td>';
					}

					echo '<td class="num total">'.$rank['games_played'].'</td>';
				}
			?>

		</tr>
		<?php } ?>
	</tbody>
</table>

<div class="well">
<?php foreach ($game_types as $game_type) { ?>
	<?php if ( ! $has_data[$game_type['GameType']['id']]) { continue; } ?>
	<h3><?php echo $game_type['GameType']['name']; ?></h3>

	<div class="well">
		<?php echo $this->element('full_rank_chart', array('rankings' => $player_rankings, 'players' => $players, 'game_type' => $game_type)); ?>
	</div>
<?php } ?>
</div>

