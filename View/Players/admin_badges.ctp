
<div class="players form">
	<?php echo $this->Form->create('Player'); ?>
		<fieldset>
			<legend><?php echo __('Edit '.$this->data['Player']['name'].'\'s Badges'); ?></legend>

			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('Badge', array('multiple' => 'checkbox', 'escape' => false, 'label' => false));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('View Player'), array('action' => 'view', $this->data['Player']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('List Players'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Badges'), array('controller' => 'badges', 'action' => 'index')); ?></li>
	</ul>
</div>

