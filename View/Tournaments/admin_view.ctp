
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
	<h3><?php echo __('Related Matches'); ?></h3>

<?php if ( ! empty($tournament['Match'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Winning Team ID'); ?></th>
			<th><?php echo __('Sat Out'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($tournament['Match'] as $match) { ?>
		<tr class="table-hover">
			<td><?php echo $match['id']; ?>&nbsp;</td>
			<td><?php echo $match['created']; ?>&nbsp;</td>
			<td><?php echo $match['winning_team_id']; ?>&nbsp;</td>
			<td><?php echo $match['sat_out']; ?>&nbsp;</td>
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

<?php if ( ! empty($tournament['Player'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($tournament['Player'] as $player) { ?>
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

