
<div class="adjectives view">

	<h2><?php echo __('Adjective'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($adjective['Adjective']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($adjective['Adjective']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Adjective'), array('action' => 'edit', $adjective['Adjective']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Adjective'), array('action' => 'delete', $adjective['Adjective']['id']), array('class' => 'delete'), __('Are you sure you want to delete Adjective #%s?', $adjective['Adjective']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Adjectives'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Adjective'), array('action' => 'add')); ?> </li>
	</ul>
</div>

