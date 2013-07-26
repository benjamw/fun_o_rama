
<div class="tournaments index">
	<h2><?php echo __('Tournaments'); ?> <?php echo $this->Html->link(__('New Tournament'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tournament_type'); ?></th>
			<th><?php echo $this->Paginator->sort('team_size'); ?></th>
			<th><?php echo $this->Paginator->sort('quality'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($tournaments as $tournament) { ?>
		<tr class="table-hover">
			<td><?php echo h($tournament['Tournament']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($tournament['Game']['name'], array('controller' => 'games', 'action' => 'view', $tournament['Game']['id'])); ?>&nbsp;</td>
			<td><?php echo h(Inflector::humanize($tournament['Tournament']['tournament_type'])); ?>&nbsp;</td>
			<td><?php echo h($tournament['Tournament']['team_size']); ?>&nbsp;</td>
			<td><?php echo h($tournament['Tournament']['quality']); ?>&nbsp;</td>
			<td><?php echo h($tournament['Tournament']['created']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $tournament['Tournament']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tournament['Tournament']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tournament['Tournament']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Tournament #%s?', $tournament['Tournament']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Tournament'), array('action' => 'add')); ?></li>
	</ul>
</div>

