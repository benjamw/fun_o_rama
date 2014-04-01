<?php $this->Html->css('bracket.css', null, array('block' => 'css')); ?>

<div class="players view">

	<h2><?php
		if ( ! empty($player['Player']['avatar']['main'])) {
			echo $this->Html->image($player['Player']['avatar']['main']).' ';
		}
		else {
			echo $this->Identicon->create($player['Player']['id']).' ';
		}

		echo $player['Player']['name']; ?>'s Amazing Stuff!!</h2>

	<div class="well">
		<h3 class="badges" data-href="<?php echo $this->Html->url(array('admin' => true, 'prefix' => 'admin', 'controller' => 'players', 'action' => 'badges', $player['Player']['id'])); ?>">
			Badges
			<small><?php echo count($player['Badge']).' out of '.count($badges); ?></small>
		</h3>

		<ul class="badges">
		<?php foreach ($player['Badge'] as $badge) { ?>
			<li><?php
				$popover = array(
					'data-animation' => 'true',
					'data-placement' => 'bottom',
					'data-trigger' => 'hover',
					'data-title' => $badge['name'],
					'data-content' => $badge['description'],
					'data-delay' => 250,
				);

				if ( ! empty($badge['icon']['main'])) {
					echo $this->Html->image($badge['icon']['main'], array_merge($popover, array('alt' => $badge['name'])));
				}
				else {

					echo '<span class="badge badge-inverse" '.implode_full($popover, ' ').'>'.$badge['name'].'</span>';
				}
			?></li>
		<?php } ?>
		</ul>

		<div style="clear:both;">&nbsp;</div>
	</div>

	<div class="well">
		<h3>Stats</h3>
		<table class="table table-striped table-bordered table-hover table-condensed win_loss">
			<thead>
				<tr>
					<th rowspan="2" class="name">Range</th>
				<?php foreach ($player['PlayerStat'] as $stat) { ?>
					<th colspan="4" class="game"><?php echo $stat['Game']['name']; ?></th>
				<?php } ?>
				</tr>
				<tr>
				<?php foreach ($player['PlayerStat'] as $stat) { ?>
					<th title="Wins" class="win">W</th>
					<th title="Losses" class="loss">L</th>
					<th title="Win-Loss Ratio" class="ratio">R</th>
					<th title="Streak" class="streak">S</th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Current</td>
				<?php foreach ($player['PlayerStat'] as $stat) { ?>
					<td class="num win"><?php echo $stat['wins']; ?></td>
					<td class="num loss"><?php echo $stat['losses']; ?></td>
					<td class="num ratio"><?php echo number_format($stat['wins'] / max($stat['losses'], 1), 2); ?></td>
					<td class="num streak"><?php echo $stat['streak']; ?></td>
				<?php } ?>
				</tr>
				<tr>
					<td>Global</td>
				<?php foreach ($player['PlayerStat'] as $stat) { ?>
					<td class="num win"><?php echo $stat['global_wins']; ?></td>
					<td class="num loss"><?php echo $stat['global_losses']; ?></td>
					<td class="num ratio"><?php echo number_format($stat['global_wins'] / max($stat['global_losses'], 1), 2); ?></td>
					<td class="num streak"><?php echo $stat['min_streak'].'/'.$stat['max_streak']; ?></td>
				<?php } ?>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="well">
		<h3>Ranking</h3>

		<?php foreach ($player['PlayerRanking'] as $ranking) { ?>
			<?php if (empty($ranking['RankHistory'])) { continue; } ?>
		<div class="well">
			<p class="pull-right">(Global Max: <?php echo number_format($ranking['max_mean'], 2); ?> &mdash; Min: <?php echo number_format($ranking['min_mean'], 2); ?>)</p>
			<p><?php echo $ranking['GameType']['name'].' &mdash; Mean (Rank): <strong>'.number_format($ranking['mean'], 4).'</strong> &mdash; Std.Dev. (Accuracy): '.number_format($ranking['std_deviation'], 6); ?></p>
			<?php echo $this->element('rank_chart', compact('ranking')); ?>
		</div>
		<?php } ?>
	</div>

</div>

<?php $this->Html->scriptblock('
	jQuery("ul.badges li").find("img, span").popover( );
	jQuery("h3.badges").on("click", function( ) { window.location = jQuery(this).data("href"); });
', array('block' => 'scriptBottom')); ?>
