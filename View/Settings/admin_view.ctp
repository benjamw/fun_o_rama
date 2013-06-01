
<div class="settings view">

	<h2><?php echo __('Setting'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($setting['Setting']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($setting['Setting']['name']); ?>&nbsp;</dd>

		<dt><?php echo __('Value'); ?></dt>
		<dd><?php echo h($setting['Setting']['value']); ?>&nbsp;</dd>

		<dt><?php echo __('Type'); ?></dt>
		<dd><?php echo h($setting['Setting']['type']); ?>&nbsp;</dd>

		<dt><?php echo __('Default'); ?></dt>
		<dd><?php echo h($setting['Setting']['default']); ?>&nbsp;</dd>

		<dt><?php echo __('Modified'); ?></dt>
		<dd><?php echo h($setting['Setting']['modified']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Setting'), array('action' => 'edit', $setting['Setting']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Setting'), array('action' => 'delete', $setting['Setting']['id']), array('class' => 'delete'), __('Are you sure you want to delete Setting #%s?', $setting['Setting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Settings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Setting'), array('action' => 'add')); ?> </li>
	</ul>
</div>

