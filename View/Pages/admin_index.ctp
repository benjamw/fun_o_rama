
<div class="pages index">
	<h2><?php echo __('Pages'); ?> <?php echo $this->Html->link(__('New Page'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('slug'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('active'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($pages as $page) { ?>
		<tr class="table-hover">
			<td><?php echo h($page['Page']['id']); ?>&nbsp;</td>
			<td><?php echo h($page['Page']['title']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($page['Page']['slug'], array('admin' => false, 'prefix' => false, 'controller' => 'pages', 'action' => 'display', $page['Page']['slug'])); ?>&nbsp;</td>
			<td><?php echo h($page['Page']['created']); ?>&nbsp;</td>
			<td><?php echo h($page['Page']['modified']); ?>&nbsp;</td>
			<td><?php echo ucfirst(Set::enum((int) $page['Page']['active'])); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $page['Page']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $page['Page']['id']), array('class' => 'btn btn-small')); ?>
					<?php if ($allow_add_delete) { ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $page['Page']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Page #%s?', $page['Page']['id'])); ?>
					<?php } ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<?php if ($allow_add_delete) { ?>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Page'), array('controller' => 'pages', 'action' => 'add')); ?></li>
	</ul>
</div>
<?php } ?>

