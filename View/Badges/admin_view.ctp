
<div class="badges view">

	<h2><?php echo __('Badge'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($badge['Badge']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($badge['Badge']['name']); ?>&nbsp;</dd>

		<dt><?php echo __('Description'); ?></dt>
		<dd><?php echo h($badge['Badge']['description']); ?>&nbsp;</dd>

		<dt><?php echo __('Icon'); ?></dt>
		<dd><?php if ( ! empty($badge['Badge']['icon']['main'])) echo $this->Html->image($badge['Badge']['icon']['main']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Badge'), array('action' => 'edit', $badge['Badge']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Badge'), array('action' => 'delete', $badge['Badge']['id']), array('class' => 'delete'), __('Are you sure you want to delete Badge #%s?', $badge['Badge']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Badges'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Badge'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Players'); ?></h3>

<?php if ( ! empty($badge['Player'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($badge['Player'] as $player) { ?>
		<tr class="table-hover">
			<td><?php echo $player['id']; ?>&nbsp;</td>
			<td><?php echo $player['name']; ?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'players', 'action' => 'view', $player['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'players', 'action' => 'edit', $player['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'players', 'action' => 'delete', $player['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Player #%s?', $player['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Players'), array('controller' => 'players', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Player'), array('controller' => 'players', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

