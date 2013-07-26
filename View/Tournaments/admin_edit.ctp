
<div class="tournaments form">
	<?php echo $this->Form->create('Tournament'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Tournament'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('game_id');
				echo $this->Form->input('tournament_type');
				echo $this->Form->input('team_size', array('min' => 1, 'max' => 4, 'value' => ife($this->request->data['Tournament']['team_size'], 2)));
				echo $this->Form->input('quality', array('min' => 0, 'max' => 100, 'value' => ife($this->request->data['Tournament']['quality'], 0)));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Tournament.id')), array('class' => 'delete'), __('Are you sure you want to delete Tournament #%s?', $this->Form->value('Tournament.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Tournaments'), array('action' => 'index')); ?></li>
	</ul>
</div>

