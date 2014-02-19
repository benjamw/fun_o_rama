<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=2.0" />
	<title>Tunes-o-Rama ME</title>
</head>
<body>

<?php echo $this->Session->flash( ); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->fetch('content'); ?>

<?php

	echo $this->Html->scriptblock('var ROOT_URL = "'.$this->Html->url('/').'";');
	echo $this->Html->script('//code.jquery.com/jquery-2.0.3.min.js');
	echo $this->fetch('script');

	echo $this->fetch('scriptBottom');

?>

</body>
</html>