
<div class="badgesPlayers index">
	<h2><?php echo __('Badges Players'); ?> <?php echo $this->Html->link(__('New Badges Player'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('badge_id'); ?></th>
			<th><?php echo $this->Paginator->sort('player_id'); ?></th>
			<th><?php echo $this->Paginator->sort('count'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($badgesPlayers as $badgesPlayer) { ?>
		<tr class="table-hover">
			<td><?php echo h($badgesPlayer['BadgesPlayer']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($badgesPlayer['Badge']['name'], array('controller' => 'badges', 'action' => 'view', $badgesPlayer['Badge']['id'])); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($badgesPlayer['Player']['name'], array('controller' => 'players', 'action' => 'view', $badgesPlayer['Player']['id'])); ?>&nbsp;</td>
			<td><?php echo h($badgesPlayer['BadgesPlayer']['count']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $badgesPlayer['BadgesPlayer']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $badgesPlayer['BadgesPlayer']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $badgesPlayer['BadgesPlayer']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Badges Player #%s?', $badgesPlayer['BadgesPlayer']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Badges Player'), array('action' => 'add')); ?></li>
	</ul>
</div>

