<!DOCTYPE html>
<html>
<head>

	<title><?php echo $title_for_layout; ?> : Administration</title>

	<?php

		echo $this->Html->charset( );
		echo $this->Html->meta('icon');
		echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));
		echo $this->fetch('meta');

		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('bootstrap-between.css');
		echo $this->Html->css('bootstrap-responsive.min.css');
		echo $this->fetch('css');

	?>

</head>
<body>

	<header class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<span class="brand">Admin</span>
			<ul class="nav">
				<li><?php echo $this->Html->link('Home', '/'); ?></li>
				<?php echo $this->Menu->menu($admin); ?>
			</ul>
		</div>
	</header>

	<div class="container">
		<div class="row">
			<div id="content" class="span12">
				<?php echo $this->Session->flash( ); ?>
				<?php echo $this->Session->flash('auth'); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	</div>

	<?php

		echo $this->Html->scriptblock('var ROOT_URL = "'.$this->Html->url('/').'";');
		echo $this->Html->script('//code.jquery.com/jquery-2.0.1.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->fetch('script');

		echo $this->fetch('scriptBottom');

	?>

</body>
</html>
