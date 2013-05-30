
<div class="animals view">

	<h2><?php echo __('Animal'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($animal['Animal']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($animal['Animal']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Animal'), array('action' => 'edit', $animal['Animal']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Animal'), array('action' => 'delete', $animal['Animal']['id']), array('class' => 'delete'), __('Are you sure you want to delete Animal #%s?', $animal['Animal']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Animals'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Animal'), array('action' => 'add')); ?> </li>
	</ul>
</div>

