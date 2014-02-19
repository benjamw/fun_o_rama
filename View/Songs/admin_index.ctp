
<div class="songs index">
	<h2><?php echo __('Songs'); ?> <?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('player_id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('file'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('played'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($songs as $song) { ?>
		<tr class="table-hover">
			<td><?php echo h($song['Song']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($song['Player']['name'], array('controller' => 'players', 'action' => 'view', $song['Player']['id'])); ?>&nbsp;</td>
			<td><?php echo h($song['Song']['title']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link(excerpt($song['Song']['file'], 50, true), $song['Song']['__file'], array('target' => '_blank', 'title' => $song['Song']['file'])); ?>&nbsp;</td>
			<td><?php echo h($song['Song']['created']); ?>&nbsp;</td>
			<td><?php echo h($song['Song']['played']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'songs', 'action' => 'view', $song['Song']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'songs', 'action' => 'edit', $song['Song']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'songs', 'action' => 'delete', $song['Song']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Song #%s?', $song['Song']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?></li>
	</ul>
</div>

