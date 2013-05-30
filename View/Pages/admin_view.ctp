
<div class="pages view">

	<h2><?php echo __('Page');?></h2>
	<dl>

		<dt><?php __('Id'); ?></dt>
		<dd><?php echo h($page['Page']['id']); ?>&nbsp;</dd>

		<dt><?php __('Title'); ?></dt>
		<dd><?php echo h($page['Page']['title']); ?>&nbsp;</dd>

		<dt><?php __('Slug'); ?></dt>
		<dd><?php echo $this->Html->link($page['Page']['slug'], array('admin' => false, 'prefix' => false, 'controller' => 'pages', 'action' => 'display', $page['Page']['slug'])); ?>&nbsp;</dd>

		<dt><?php __('Copy'); ?></dt>
		<dd><?php echo $page['Page']['copy']; ?>&nbsp;</dd>

		<dt><?php __('Created'); ?></dt>
		<dd><?php echo h($page['Page']['created']); ?>&nbsp;</dd>

		<dt><?php __('Modified'); ?></dt>
		<dd><?php echo h($page['Page']['modified']); ?>&nbsp;</dd>

		<dt><?php __('Active'); ?></dt>
		<dd><?php echo ucfirst(Set::enum((int) $page['Page']['active'])); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Page'), array('action' => 'edit', $page['Page']['id'])); ?> </li>
		<?php if ($allow_add_delete) { ?>
		<li><?php echo $this->Form->postLink(__('Delete Page'), array('action' => 'delete', $page['Page']['id']), array('class' => 'delete'), __('Are you sure you want to delete Page #%s?', $page['Page']['id'])); ?> </li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Pages'), array('action' => 'index')); ?> </li>
		<?php if ($allow_add_delete) { ?>
		<li><?php echo $this->Html->link(__('New Page'), array('action' => 'add')); ?> </li>
		<?php } ?>
	</ul>
</div>

