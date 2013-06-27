
<div class="teams view">

	<h2><?php echo __('Team'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($team['Team']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Tournament'); ?></dt>
		<dd><?php echo $this->Html->link($team['Match']['Game']['name'], array('controller' => 'games', 'action' => 'view', $team['Match']['Game']['id'])).' @ '.$this->Html->link($team['Match']['created'], array('controller' => 'matches', 'action' => 'view', $team['Match']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($team['Team']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Team'), array('action' => 'edit', $team['Team']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Team'), array('action' => 'delete', $team['Team']['id']), array('class' => 'delete'), __('Are you sure you want to delete Team #%s?', $team['Team']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Teams'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Team'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Matches'); ?></h3>

<?php if ( ! empty($team['Match'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Game ID'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Winning Team ID'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($team['Match'] as $match) { ?>
		<tr class="table-hover">
			<td><?php echo $match['id']; ?>&nbsp;</td>
			<td><?php echo $match['game_id']; ?>&nbsp;</td>
			<td><?php echo $match['created']; ?>&nbsp;</td>
			<td><?php echo $match['winning_team_id']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'matches', 'action' => 'view', $match['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'matches', 'action' => 'edit', $match['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'matches', 'action' => 'delete', $match['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Match #%s?', $match['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Matches'), array('controller' => 'matches', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Match'), array('controller' => 'matches', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

<div class="related well">
	<h3><?php echo __('Related Players'); ?></h3>

<?php if ( ! empty($team['Player'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($team['Player'] as $player) { ?>
		<tr class="table-hover">
			<td><?php echo $player['id']; ?>&nbsp;</td>
			<td><?php echo $player['name']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'players', 'action' => 'view', $player['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'players', 'action' => 'edit', $player['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'players', 'action' => 'delete', $player['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player #%s?', $player['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Players'), array('controller' => 'players', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Player'), array('controller' => 'players', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

