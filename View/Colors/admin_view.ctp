
<div class="colors view">

	<h2><?php echo __('Color'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($color['Color']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($color['Color']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Color'), array('action' => 'edit', $color['Color']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Color'), array('action' => 'delete', $color['Color']['id']), array('class' => 'delete'), __('Are you sure you want to delete Color #%s?', $color['Color']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Colors'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Color'), array('action' => 'add')); ?> </li>
	</ul>
</div>

