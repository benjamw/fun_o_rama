
<div class="playerStats index">
	<h2><?php echo __('Player Stats'); ?> <?php echo $this->Html->link(__('New Player Stat'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('player_id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_id'); ?></th>
			<th><?php echo $this->Paginator->sort('wins'); ?></th>
			<th><?php echo $this->Paginator->sort('draws'); ?></th>
			<th><?php echo $this->Paginator->sort('losses'); ?></th>
			<th><?php echo $this->Paginator->sort('streak'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($playerStats as $playerStat) { ?>
		<tr class="table-hover">
			<td><?php echo h($playerStat['PlayerStat']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerStat['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerStat['Player']['id'])); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($playerStat['Game']['name'], array('controller' => 'games', 'action' => 'view', $playerStat['Game']['id'])); ?>&nbsp;</td>
			<td><?php echo h($playerStat['PlayerStat']['wins']); ?>&nbsp;</td>
			<td><?php echo h($playerStat['PlayerStat']['draws']); ?>&nbsp;</td>
			<td><?php echo h($playerStat['PlayerStat']['losses']); ?>&nbsp;</td>
			<td><?php echo h($playerStat['PlayerStat']['streak']); ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $playerStat['PlayerStat']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $playerStat['PlayerStat']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $playerStat['PlayerStat']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player Stat #%s?', $playerStat['PlayerStat']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Player Stat'), array('action' => 'add')); ?></li>
	</ul>
</div>

