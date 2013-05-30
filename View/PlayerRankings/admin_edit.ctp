
<div class="playerRankings form">
	<?php echo $this->Form->create('PlayerRanking'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Player Ranking'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('player_id');
				echo $this->Form->input('game_type_id');
				echo $this->Form->input('mean');
				echo $this->Form->input('std_deviation');
				echo $this->Form->input('games_played');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PlayerRanking.id')), array('class' => 'delete'), __('Are you sure you want to delete Player Ranking #%s?', $this->Form->value('PlayerRanking.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Player Rankings'), array('action' => 'index')); ?></li>
	</ul>
</div>

