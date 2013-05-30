
<div class="gameTypes view">

	<h2><?php echo __('Game Type'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($gameType['GameType']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($gameType['GameType']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Game Type'), array('action' => 'edit', $gameType['GameType']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game Type'), array('action' => 'delete', $gameType['GameType']['id']), array('class' => 'delete'), __('Are you sure you want to delete Game Type #%s?', $gameType['GameType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Type'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Games'); ?></h3>

<?php if ( ! empty($gameType['Game'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($gameType['Game'] as $game) { ?>
		<tr class="table-hover">
			<td><?php echo $game['id']; ?></td>
			<td><?php echo $game['name']; ?></td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'games', 'action' => 'view', $game['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'games', 'action' => 'edit', $game['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'games', 'action' => 'delete', $game['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Game #%s?', $game['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

<div class="related well">
	<h3><?php echo __('Related Player Rankings'); ?></h3>

<?php if ( ! empty($gameType['PlayerRanking'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Player'); ?></th>
			<th><?php echo __('Mean (&mu;)'); ?></th>
			<th><?php echo __('Std Dev. (&sigma;)'); ?></th>
			<th><?php echo __('Games Played'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($gameType['PlayerRanking'] as $playerRanking) { ?>
		<tr class="table-hover">
			<td><?php echo $playerRanking['id']; ?></td>
			<td><?php echo $this->Html->link($playerRanking['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerRanking['Player']['id'])); ?></td>
			<td><?php echo $playerRanking['mean']; ?></td>
			<td><?php echo $playerRanking['std_deviation']; ?></td>
			<td><?php echo $playerRanking['games_played']; ?></td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'player_rankings', 'action' => 'view', $playerRanking['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'player_rankings', 'action' => 'edit', $playerRanking['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'player_rankings', 'action' => 'delete', $playerRanking['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player Ranking #%s?', $playerRanking['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Player Rankings'), array('controller' => 'player_rankings', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Player Ranking'), array('controller' => 'player_rankings', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

