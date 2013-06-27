
<div class="tournaments view">

	<h2><?php echo __('Tournament'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($tournament['Tournament']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Game'); ?></dt>
		<dd><?php echo $this->Html->link($tournament['Game']['name'], array('controller' => 'games', 'action' => 'view', $tournament['Game']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Tournament Type'); ?></dt>
		<dd><?php echo h($tournament['Tournament']['tournament_type']); ?>&nbsp;</dd>

		<dt><?php echo __('Team Size'); ?></dt>
		<dd><?php echo h($tournament['Tournament']['team_size']); ?>&nbsp;</dd>

		<dt><?php echo __('Created'); ?></dt>
		<dd><?php echo h($tournament['Tournament']['created']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Tournament'), array('action' => 'edit', $tournament['Tournament']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tournament'), array('action' => 'delete', $tournament['Tournament']['id']), array('class' => 'delete'), __('Are you sure you want to delete Tournament #%s?', $tournament['Tournament']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tournaments'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tournament'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Teams'); ?></h3>

<?php if ( ! empty($tournament['Team'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th><?php echo __('Players'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($tournament['Team'] as $team) { ?>
		<tr class="table-hover">
			<td><?php echo $team['id']; ?>&nbsp;</td>
			<td><?php echo $team['name']; ?>&nbsp;</td>
			<td>
				<?php
					$players = array( );
					foreach ($team['Player'] as $player) {
						$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
					}
					echo implode(', ', $players);
				?>&nbsp;
			</td>
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

