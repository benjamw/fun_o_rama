
<div class="gameTypes index">
	<h2><?php echo __('Game Types'); ?> <?php echo $this->Html->link(__('New Game Type'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('max_team_size'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($gameTypes as $gameType) { ?>
		<tr class="table-hover">
			<td><?php echo h($gameType['GameType']['id']); ?>&nbsp;</td>
			<td><?php echo h($gameType['GameType']['name']); ?>&nbsp;</td>
			<td><?php echo h($gameType['GameType']['max_team_size']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $gameType['GameType']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $gameType['GameType']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $gameType['GameType']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Game Type #%s?', $gameType['GameType']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Game Type'), array('action' => 'add')); ?></li>
	</ul>
</div>

