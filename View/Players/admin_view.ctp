
<div class="players view">

	<h2><?php echo __('Player'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($player['Player']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($player['Player']['name']); ?>&nbsp;</dd>

		<dt><?php echo __('Avatar'); ?></dt>
		<dd><?php if ( ! empty($player['Player']['avatar']['main'])) echo $this->Html->image($player['Player']['avatar']['main']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Player'), array('action' => 'edit', $player['Player']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Edit Player\'s Badges'), array('action' => 'badges', $player['Player']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Player'), array('action' => 'delete', $player['Player']['id']), array('class' => 'delete'), __('Are you sure you want to delete Player #%s?', $player['Player']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Players'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Player'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Player Rankings'); ?></h3>

<?php if ( ! empty($player['PlayerRanking'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Game Type'); ?></th>
			<th><?php echo __('Mean (&mu;)'); ?></th>
			<th><?php echo __('Std Dev. (&sigma;)'); ?></th>
			<th><?php echo __('Games Played'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($player['PlayerRanking'] as $playerRanking) { ?>
		<tr class="table-hover">
			<td><?php echo $playerRanking['id']; ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerRanking['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $playerRanking['GameType']['id'])); ?>&nbsp;</td>
			<td><?php echo $playerRanking['mean']; ?>&nbsp;</td>
			<td><?php echo $playerRanking['std_deviation']; ?>&nbsp;</td>
			<td><?php echo $playerRanking['games_played']; ?>&nbsp;</td>
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

<div class="related well">
	<h3><?php echo __('Related Player Stats'); ?></h3>

<?php if ( ! empty($player['PlayerStat'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Game'); ?></th>
			<th><?php echo __('Wins'); ?></th>
			<th><?php echo __('Draws'); ?></th>
			<th><?php echo __('Losses'); ?></th>
			<th><?php echo __('Streak'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($player['PlayerStat'] as $playerStat) { ?>
		<tr class="table-hover">
			<td><?php echo $playerStat['id']; ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerStat['Game']['name'], array('controller' => 'games', 'action' => 'view', $playerStat['game_id'])); ?>&nbsp;</td>
			<td><?php echo $playerStat['wins']; ?>&nbsp;</td>
			<td><?php echo $playerStat['draws']; ?>&nbsp;</td>
			<td><?php echo $playerStat['losses']; ?>&nbsp;</td>
			<td><?php echo $playerStat['streak']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'player_stats', 'action' => 'view', $playerStat['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'player_stats', 'action' => 'edit', $playerStat['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'player_stats', 'action' => 'delete', $playerStat['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player Stat #%s?', $playerStat['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Player Stats'), array('controller' => 'player_stats', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Player Stat'), array('controller' => 'player_stats', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

<div class="related well">
	<h3><?php echo __('Related Badges'); ?></h3>

<?php if ( ! empty($player['Badge'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th><?php echo __('Description'); ?></th>
			<th><?php echo __('Icon'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($player['Badge'] as $badge) { ?>
		<tr class="table-hover">
			<td><?php echo $badge['id']; ?>&nbsp;</td>
			<td><?php echo h($badge['name']); ?>&nbsp;</td>
			<td><?php echo h($badge['description']); ?>&nbsp;</td>
			<td><?php if ( ! empty($badge['icon']['main'])) { echo $this->Html->image($badge['icon']['main']); } ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'badges', 'action' => 'view', $badge['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'badges', 'action' => 'edit', $badge['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'badges', 'action' => 'delete', $badge['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Badge #%s?', $badge['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Badges'), array('controller' => 'badges', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Badge'), array('controller' => 'badges', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

<div class="related well">
	<h3><?php echo __('Related Teams'); ?></h3>

<?php if ( ! empty($player['Team'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Tournament'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($player['Team'] as $team) { ?>
		<tr class="table-hover">
			<td><?php echo $team['id']; ?>&nbsp;</td>
			<td><?php echo $this->Html->link($team['Tournament']['Game']['name'], array('controller' => 'games', 'action' => 'view', $team['Tournament']['Game']['id'])).' @ '.$this->Html->link($team['Tournament']['created'], array('controller' => 'tournaments', 'action' => 'view', $team['Tournament']['id'])); ?>&nbsp;</td>
			<td><?php echo $team['name']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'teams', 'action' => 'view', $team['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'teams', 'action' => 'edit', $team['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'teams', 'action' => 'delete', $team['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Team #%s?', $team['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Teams'), array('controller' => 'teams', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Team'), array('controller' => 'teams', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

