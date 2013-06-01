
<div class="matches form">
	<?php echo $this->Form->create('Match'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Match'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('game_id');
				echo $this->Form->input('winning_team_id');
				echo $this->Form->input('sat_out', array('type' => 'select', 'options' => $satOutPlayers, 'empty' => true));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Match.id')), array('class' => 'delete'), __('Are you sure you want to delete Match #%s?', $this->Form->value('Match.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Matches'), array('action' => 'index')); ?></li>
	</ul>
</div>

