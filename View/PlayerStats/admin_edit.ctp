
<div class="playerStats form">
	<?php echo $this->Form->create('PlayerStat'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Player Stat'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('player_id');
				echo $this->Form->input('game_id');
				echo $this->Form->input('wins');
				echo $this->Form->input('draws');
				echo $this->Form->input('losses');
				echo $this->Form->input('streak');
				echo $this->Form->input('global_wins');
				echo $this->Form->input('global_draws');
				echo $this->Form->input('global_losses');
				echo $this->Form->input('max_streak');
				echo $this->Form->input('min_streak');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PlayerStat.id')), array('class' => 'delete'), __('Are you sure you want to delete Player Stat #%s?', $this->Form->value('PlayerStat.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Player Stats'), array('action' => 'index')); ?></li>
	</ul>
</div>

