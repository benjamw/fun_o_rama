
<div class="teams index">
	<h2><?php echo __('Teams'); ?> <?php echo $this->Html->link(__('New Team'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('tournament_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('start_seed'); ?></th>
			<th><?php echo $this->Paginator->sort('seed'); ?></th>
			<th>Players</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($teams as $team) { ?>
		<tr class="table-hover">
			<td><?php echo h($team['Team']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($team['Tournament']['Game']['name'], array('controller' => 'games', 'action' => 'view', $team['Tournament']['Game']['id'])).' @ '.$this->Html->link($team['Tournament']['created'], array('controller' => 'tournaments', 'action' => 'view', $team['Tournament']['id'])); ?>&nbsp;</td>
			<td><?php echo h($team['Team']['name']); ?>&nbsp;</td>
			<td><?php echo h($team['Team']['start_seed']); ?>&nbsp;</td>
			<td><?php echo h($team['Team']['seed']); ?>&nbsp;</td>
			<td><?php
				$players = array( );
				foreach ($team['Player'] as $player) {
					$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
				}
				echo implode(', ', $players);
			?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $team['Team']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $team['Team']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $team['Team']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Team #%s?', $team['Team']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Team'), array('action' => 'add')); ?></li>
	</ul>
</div>

