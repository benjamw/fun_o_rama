
<div class="songs view">

	<h2><?php echo __('Song'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($song['Song']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($song['Player']['name'], array('controller' => 'players', 'action' => 'view', $song['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Title'); ?></dt>
		<dd><?php echo h($song['Song']['title']); ?>&nbsp;</dd>

		<dt><?php echo __('File'); ?></dt>
		<dd><?php echo h($song['Song']['file']); ?>&nbsp;</dd>

		<dt><?php echo __('Created'); ?></dt>
		<dd><?php echo h($song['Song']['created']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Song'), array('controller' => 'songs', 'action' => 'edit', $song['Song']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Song'), array('controller' => 'songs', 'action' => 'delete', $song['Song']['id']), array('class' => 'delete'), __('Are you sure you want to delete Song #%s?', $song['Song']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Song'), array('controller' => 'songs', 'action' => 'add')); ?> </li>
	</ul>
</div>

