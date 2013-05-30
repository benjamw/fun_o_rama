
<div class="users view">

	<h2><?php  echo __('User');?></h2>
	<dl>

		<dt><?php __('Id'); ?></dt>
		<dd><?php echo h($user['User']['id']); ?>&nbsp;</dd>

		<dt><?php __('Group'); ?></dt>
		<dd><?php echo h($user['Group']['name']); ?>&nbsp;</dd>

		<dt><?php __('First Name'); ?></dt>
		<dd><?php echo h($user['User']['first_name']); ?>&nbsp;</dd>

		<dt><?php __('Last Name'); ?></dt>
		<dd><?php echo h($user['User']['last_name']); ?>&nbsp;</dd>

		<dt><?php __('Username'); ?></dt>
		<dd><?php echo h($user['User']['username']); ?>&nbsp;</dd>

		<dt><?php __('Email'); ?></dt>
		<dd><?php echo h($user['User']['email']); ?>&nbsp;</dd>

		<dt><?php __('Created'); ?></dt>
		<dd><?php echo h($user['User']['created']); ?>&nbsp;</dd>

		<dt><?php __('Modified'); ?></dt>
		<dd><?php echo h($user['User']['modified']); ?>&nbsp;</dd>

		<dt><?php __('Active'); ?></dt>
		<dd><?php echo ucfirst(Set::enum((int) $user['User']['active'])); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), array('class' => 'delete'), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
	</ul>
</div>

