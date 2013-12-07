
<div class="games view">

	<h2><?php echo __('Game'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($game['Game']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Game Type'); ?></dt>
		<dd><?php echo $this->Html->link($game['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $game['GameType']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($game['Game']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Game'), array('action' => 'edit', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game'), array('action' => 'delete', $game['Game']['id']), array('class' => 'delete'), __('Are you sure you want to delete Game #%s?', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Player Stats'); ?></h3>

<?php if ( ! empty($game['PlayerStat'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Player'); ?></th>
			<th><?php echo __('Wins'); ?></th>
			<th><?php echo __('Draws'); ?></th>
			<th><?php echo __('Losses'); ?></th>
			<th><?php echo __('Streak'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($game['PlayerStat'] as $playerStat) { ?>
		<tr class="table-hover">
			<td><?php echo $playerStat['id']; ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerStat['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerStat['Player']['id'])); ?>&nbsp;</td>
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
	<h3><?php echo __('Related Tournaments'); ?></h3>

<?php if ( ! empty($game['Tournament'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Tournament Type'); ?></th>
			<th><?php echo __('Team Size'); ?></th>
			<th><?php echo __('Quality'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($game['Tournament'] as $tournament) { ?>
		<tr class="table-hover">
			<td><?php echo $tournament['id']; ?>&nbsp;</td>
			<td><?php echo Inflector::humanize($tournament['tournament_type']); ?>&nbsp;</td>
			<td><?php echo $tournament['team_size']; ?>&nbsp;</td>
			<td><?php echo $tournament['quality']; ?>&nbsp;</td>
			<td><?php echo $tournament['created']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'tournaments', 'action' => 'view', $tournament['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'tournaments', 'action' => 'edit', $tournament['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'tournaments', 'action' => 'delete', $tournament['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Tournament #%s?', $tournament['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Tournaments'), array('controller' => 'tournaments', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Tournament'), array('controller' => 'tournaments', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

