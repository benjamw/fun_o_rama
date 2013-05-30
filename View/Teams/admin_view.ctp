
<div class="teams view">
	<h2><?php echo __('Team'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($team['Team']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Match'); ?></dt>
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
			<td><?php echo $player['id']; ?></td>
			<td><?php echo $player['name']; ?></td>
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
			<li><?php echo $this->Html->link(__('List Player'), array('controller' => 'players', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Player'), array('controller' => 'players', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

