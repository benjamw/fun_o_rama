
<div class="games index">
	<h2><?php echo __('Games'); ?> <?php echo $this->Html->link(__('New Game'), array('action' => 'add'), array('class' => 'btn btn-small btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_type_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($games as $game) { ?>
		<tr class="table-hover">
			<td><?php echo h($game['Game']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($game['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $game['GameType']['id'])); ?>&nbsp;</td>
			<td><?php echo h($game['Game']['name']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $game['Game']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Game #%s?', $game['Game']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?></li>
	</ul>
</div>

