
<div class="badges index">
	<h2><?php echo __('Badges'); ?> <?php echo $this->Html->link(__('New Badge'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo __('Icon'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($badges as $badge) { ?>
		<tr class="table-hover">
			<td><?php echo h($badge['Badge']['id']); ?>&nbsp;</td>
			<td><?php echo h($badge['Badge']['name']); ?>&nbsp;</td>
			<td><?php echo h($badge['Badge']['description']); ?>&nbsp;</td>
			<td><?php if ( ! empty($badge['Badge']['icon']['main'])) echo $this->Html->image($badge['Badge']['icon']['main']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $badge['Badge']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $badge['Badge']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $badge['Badge']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Badge #%s?', $badge['Badge']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Badge'), array('action' => 'add')); ?></li>
	</ul>
</div>

