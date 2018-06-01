
<div class="sittingOuts form">
	<?php echo $this->Form->create('SittingOut', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Sitting Out'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('tournament_id');
				echo $this->Form->input('player_id');
			?>

			<?php echo $this->Form->submit(__('Submit')); ?>

		</fieldset>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('controller' => 'sitting_outs', 'action' => 'delete', $this->Form->value('SittingOut.id')), array('class' => 'delete'), __('Are you sure you want to delete Sitting Out #%s?', $this->Form->value('SittingOut.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Sitting Outs'), array('controller' => 'sitting_outs', 'action' => 'index')); ?></li>
	</ul>
</div>

