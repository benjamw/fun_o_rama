
<div class="playerRankings view">

	<h2><?php echo __('Player Ranking'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($playerRanking['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerRanking['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Game Type'); ?></dt>
		<dd><?php echo $this->Html->link($playerRanking['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $playerRanking['GameType']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Mean'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['mean']); ?>&nbsp;</dd>

		<dt><?php echo __('Std Deviation'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['std_deviation']); ?>&nbsp;</dd>

		<dt><?php echo __('Games Played'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['games_played']); ?>&nbsp;</dd>

		<dt><?php echo __('Max Mean'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['max_mean']); ?>&nbsp;</dd>

		<dt><?php echo __('Min Mean'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['min_mean']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Player Ranking'), array('action' => 'edit', $playerRanking['PlayerRanking']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Player Ranking'), array('action' => 'delete', $playerRanking['PlayerRanking']['id']), array('class' => 'delete'), __('Are you sure you want to delete Player Ranking #%s?', $playerRanking['PlayerRanking']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Player Rankings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Player Ranking'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Rank History'); ?></h3>

<?php if ( ! empty($playerRanking['RankHistory'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Mean (&mu;)'); ?></th>
			<th><?php echo __('Std Dev. (&sigma;)'); ?></th>
			<th><?php echo __('Created'); ?></th>
		</tr>

	<?php foreach ($playerRanking['RankHistory'] as $rankHistory) { ?>
		<tr class="table-hover">
			<td><?php echo $rankHistory['id']; ?>&nbsp;</td>
			<td><?php echo $rankHistory['mean']; ?>&nbsp;</td>
			<td><?php echo $rankHistory['std_deviation']; ?>&nbsp;</td>
			<td><?php echo $rankHistory['created']; ?>&nbsp;</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>
</div>

