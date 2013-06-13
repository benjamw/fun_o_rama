
<div class="badgesPlayers view">

	<h2><?php echo __('Badges Player'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($badgesPlayer['BadgesPlayer']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Badge'); ?></dt>
		<dd><?php echo $this->Html->link($badgesPlayer['Badge']['name'], array('controller' => 'badges', 'action' => 'view', $badgesPlayer['Badge']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($badgesPlayer['Player']['name'], array('controller' => 'players', 'action' => 'view', $badgesPlayer['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Count'); ?></dt>
		<dd><?php echo h($badgesPlayer['BadgesPlayer']['count']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Badges Player'), array('action' => 'edit', $badgesPlayer['BadgesPlayer']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Badges Player'), array('action' => 'delete', $badgesPlayer['BadgesPlayer']['id']), array('class' => 'delete'), __('Are you sure you want to delete Badges Player #%s?', $badgesPlayer['BadgesPlayer']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Badges Players'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Badges Player'), array('action' => 'add')); ?> </li>
	</ul>
</div>

