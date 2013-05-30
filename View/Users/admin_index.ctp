
<div class="users index">
	<h2><?php echo __('Users');?></h2>

	<?php echo $this->element('paginator_crumbs'); ?>

	<table>
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('Group', 'Group.id');?></th>
			<th><?php echo $this->Paginator->sort('first_name'); ?></th>
			<th><?php echo $this->Paginator->sort('last_name'); ?></th>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('active'); ?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>

	<?php foreach ($users as $user) { ?>
		<tr>
			<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
			<td><?php echo h($user['Group']['name']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['last_name']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['modified']); ?>&nbsp;</td>
			<td><?php echo ucfirst(Set::enum((int) $user['User']['active'])); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), array('class' => 'delete'), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('paginator_crumbs'); ?>

</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
	</ul>
</div>

