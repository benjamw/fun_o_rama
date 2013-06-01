
<div class="settings form">
	<?php echo $this->Form->create('Setting'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Setting'); ?></legend>

			<?php
				echo $this->Form->input('name');
				echo $this->Form->input('value');
				echo $this->Form->input('type');
				echo $this->Form->input('default');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('List Settings'), array('action' => 'index')); ?></li>
	</ul>
</div>

