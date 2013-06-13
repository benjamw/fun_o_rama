
<div class="badgesPlayers form">
	<?php echo $this->Form->create('BadgesPlayer'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Badges Player'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('badge_id');
				echo $this->Form->input('player_id');
				echo $this->Form->input('count');
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('BadgesPlayer.id')), array('class' => 'delete'), __('Are you sure you want to delete Badges Player #%s?', $this->Form->value('BadgesPlayer.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Badges Players'), array('action' => 'index')); ?></li>
	</ul>
</div>

