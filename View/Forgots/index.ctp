<?php
	switch ($step) {
		case 'sent' :
			?>

			<p>A password reset request has been sent to your email address.</p>

			<?php

			break;

		case 'reset' :
			?>

			<p>A new password has been sent to your email address.</p>

			<?php

			break;

		case 'fail' :
			?>

			<p>There was an error sending the email, please try again.</p>

			<?php

			break;

		case 'form' :
		default :
			?>

			<p>Enter your registered email address and a password reset request will be sent to you.</p>
			<?php echo $this->Form->create('User', array('url' => array('controller' => 'forgots', 'action' => $this->action))); ?>
			<?php echo $this->Form->input('email', array('size' => 50)); ?>
			<?php echo $this->Form->end('Send'); ?>

			<?php

			break;
	}
?>

