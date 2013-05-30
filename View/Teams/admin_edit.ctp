
<div class="teams form">
	<?php echo $this->Form->create('Team'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Team'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('match_id');
				echo $this->Form->input('name');
				echo $this->Form->input('Player', array('multiple' => 'checkbox'));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Team.id')), array('class' => 'delete'), __('Are you sure you want to delete Team #%s?', $this->Form->value('Team.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Teams'), array('action' => 'index')); ?></li>
	</ul>
</div>

