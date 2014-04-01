
<div class="playerRankings index">
	<h2><?php echo __('Player Rankings'); ?> <?php echo $this->Html->link(__('New Player Ranking'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('player_id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_type_id'); ?></th>
			<th><?php echo $this->Paginator->sort('mean', 'Mean (&mu;)', array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('std_deviation', 'Std Dev. (&sigma;)', array('escape' => false)); ?></th>
			<th><?php echo $this->Paginator->sort('games_played'); ?></th>
			<th><?php echo $this->Paginator->sort('max_mean'); ?></th>
			<th><?php echo $this->Paginator->sort('min_mean'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($playerRankings as $playerRanking) { ?>
		<tr class="table-hover">
			<td><?php echo h($playerRanking['PlayerRanking']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerRanking['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerRanking['Player']['id'])); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerRanking['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $playerRanking['GameType']['id'])); ?>&nbsp;</td>
			<td><?php echo h($playerRanking['PlayerRanking']['mean']); ?>&nbsp;</td>
			<td><?php echo h($playerRanking['PlayerRanking']['std_deviation']); ?>&nbsp;</td>
			<td><?php echo h($playerRanking['PlayerRanking']['games_played']); ?>&nbsp;</td>
			<td><?php echo h($playerRanking['PlayerRanking']['max_mean']); ?>&nbsp;</td>
			<td><?php echo h($playerRanking['PlayerRanking']['min_mean']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $playerRanking['PlayerRanking']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $playerRanking['PlayerRanking']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $playerRanking['PlayerRanking']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player Ranking #%s?', $playerRanking['PlayerRanking']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Player Ranking'), array('action' => 'add')); ?></li>
	</ul>
</div>

