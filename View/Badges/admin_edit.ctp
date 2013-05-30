
<div class="badges form">
	<?php echo $this->Form->create('Badge', array('type' => 'file')); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Badge'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('name');
				echo $this->Form->input('description');
				echo $this->Form->input('icon', array('type' => 'file'));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Badge.id')), array('class' => 'delete'), __('Are you sure you want to delete Badge #%s?', $this->Form->value('Badge.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Badges'), array('action' => 'index')); ?></li>
	</ul>
</div>

