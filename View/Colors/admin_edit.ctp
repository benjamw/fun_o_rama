
<div class="colors form">
	<?php echo $this->Form->create('Color'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Color'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('name');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Color.id')), array('class' => 'delete'), __('Are you sure you want to delete Color #%s?', $this->Form->value('Color.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Colors'), array('action' => 'index')); ?></li>
	</ul>
</div>

