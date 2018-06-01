
<div class="sittingOuts index">
	<h3><?php echo __('Sitting Outs'); ?> <?php echo $this->Html->link(__('New Sitting Out'), array('controller' => 'sitting_outs', 'action' => 'add'), array('class' => 'btn btn-xs btn-info')); ?></h3>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('tournament_id'); ?></th>
				<th><?php echo $this->Paginator->sort('player_id'); ?></th>
				<th><?php echo $this->Paginator->sort('created'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($sittingOuts as $sittingOut) { ?>
			<tr class="table-hover">
				<td><?php echo h($sittingOut['SittingOut']['id']); ?>&nbsp;</td>
				<td><?php echo $this->Html->link($sittingOut['Tournament']['created'], array('controller' => 'tournaments', 'action' => 'view', $sittingOut['Tournament']['id'])); ?>&nbsp;</td>
				<td><?php echo $this->Html->link($sittingOut['Player']['name'], array('controller' => 'players', 'action' => 'view', $sittingOut['Player']['id'])); ?>&nbsp;</td>
				<td><?php echo h($sittingOut['SittingOut']['created']); ?>&nbsp;</td>
				<td class="actions">
					<div class="btn-group"><?php
						echo $this->Html->link(__('View'), array('controller' => 'sitting_outs', 'action' => 'view', $sittingOut['SittingOut']['id']), array('class' => 'btn btn-xs btn-default'));
						echo $this->Html->link(__('Edit'), array('controller' => 'sitting_outs', 'action' => 'edit', $sittingOut['SittingOut']['id']), array('class' => 'btn btn-xs btn-default'));
						echo $this->Form->postLink(__('Delete'), array('controller' => 'sitting_outs', 'action' => 'delete', $sittingOut['SittingOut']['id']), array('class' => 'btn btn-xs btn-warning'), __('Are you sure you want to delete Sitting Out #%s?', $sittingOut['SittingOut']['id']));
					?></div>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Sitting Out'), array('controller' => 'sitting_outs', 'action' => 'add')); ?></li>
	</ul>
</div>

