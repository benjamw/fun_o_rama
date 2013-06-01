<div class="users login">
<?php echo $this->Form->create('User', array('action' => 'login'));?>
	<fieldset>
		<legend><?php echo __('Login');?></legend>
	<?php

		$forgot_link = '';
		if ($auth_forgot) {
			$forgot_link = ' '.$this->Html->link('Forgot Password', array('admin' => false, 'prefix' => false, 'controller' => 'forgot', 'action' => 'index'), array('class' => 'btn btn-small'));
		}

		echo $this->Form->input($auth_fields['username'], array('tabindex' => 1));
		echo $this->Form->input($auth_fields['password'], array('value' => '', 'tabindex' => 2));
		if ($auth_remember) {
			echo $this->Form->input('remember_me', array('type' => 'checkbox', 'tabindex' => 3));
		}
	?>
	</fieldset>

	<?php echo $this->Form->submit('Login', array('class' => 'btn btn-primary', 'div' => false)); ?>
	<?php echo $forgot_link; ?>
<?php echo $this->Form->end( );?>
</div>
