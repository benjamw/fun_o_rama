
<div class="players index">
	<h2><?php echo __('Players'); ?> <?php echo $this->Html->link(__('New Player'), array('action' => 'add'), array('class' => 'btn btn-small btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo __('Avatar'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($players as $player) { ?>
		<tr class="table-hover">
			<td><?php echo h($player['Player']['id']); ?>&nbsp;</td>
			<td><?php echo h($player['Player']['name']); ?>&nbsp;</td>
			<td><?php
				if ( ! empty($player['Player']['avatar']['main'])) {
					echo $this->Html->image($player['Player']['avatar']['main']);
				}
				else {
					echo $this->Identicon->create($player['Player']['id']);
				}
			?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $player['Player']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $player['Player']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Badges'), array('action' => 'badges', $player['Player']['id']), array('class' => 'btn btn-small btn-info')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $player['Player']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player #%s?', $player['Player']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Player'), array('action' => 'add')); ?></li>
	</ul>
</div>

