
<div class="gameTypes form">
	<?php echo $this->Form->create('GameType'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Game Type'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('name');
				echo $this->Form->input('max_team_size');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('GameType.id')), array('class' => 'delete'), __('Are you sure you want to delete Game Type #%s?', $this->Form->value('GameType.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Game Types'), array('action' => 'index')); ?></li>
	</ul>
</div>

