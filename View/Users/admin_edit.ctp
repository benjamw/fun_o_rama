
<div class="users form">
	<?php echo $this->Form->create('User', array('action' => $this->action)); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' User'); ?></legend>

			<?php
				echo $this->Form->input('group_id');
				echo $this->Form->input('first_name');
				echo $this->Form->input('last_name');
				echo $this->Form->input('username');
				echo $this->Form->input('email');

				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
					echo '<div>Leave passwords blank to keep current password</div>';
				}

				echo $this->Form->input('password', array('type' => 'password', 'value' => ''));
				echo $this->Form->input('confirm', array('type' => 'password', 'value' => ''));
				echo $this->Form->input('active', array('type' => 'checkbox'));
			?>

		</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<ul>
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), array('class' => 'delete'), __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index'));?></li>
	</ul>
</div>

