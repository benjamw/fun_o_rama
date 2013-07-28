
<div class="settings index">
	<h2><?php echo __('Settings'); ?> <?php echo $this->Html->link(__('New Setting'), array('action' => 'add'), array('class' => 'btn btn-small btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('default'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($settings as $setting) { ?>
		<tr class="table-hover">
			<td><?php echo h($setting['Setting']['id']); ?>&nbsp;</td>
			<td><?php echo h($setting['Setting']['name']); ?>&nbsp;</td>
			<td><?php echo h($setting['Setting']['value']); ?>&nbsp;</td>
			<td><?php echo h($setting['Setting']['type']); ?>&nbsp;</td>
			<td><?php echo h($setting['Setting']['default']); ?>&nbsp;</td>
			<td><?php echo h($setting['Setting']['modified']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $setting['Setting']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $setting['Setting']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $setting['Setting']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Setting #%s?', $setting['Setting']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Setting'), array('action' => 'add')); ?></li>
	</ul>
</div>

