<div class="home login">
<?php echo $this->Form->create('Home', array('url' => array('controller' => 'home', 'action' => 'login'))); ?>
	<fieldset>
		<legend><?php echo __('Login');?></legend>
	<?php
		echo $this->Form->input('password', array('value' => '', 'tabindex' => 2));
	?>
	</fieldset>

	<?php echo $this->Form->submit('Login', array('class' => 'btn btn-primary', 'div' => false)); ?>
<?php echo $this->Form->end( );?>
</div>
