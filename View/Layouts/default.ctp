<!DOCTYPE html>
<html>
<head>

	<title>DAZ Fun-☺-rama ME&reg; :: <?php echo $title_for_layout; ?></title>

	<?php

		echo $this->Html->charset('UTF-8');
		echo $this->Html->meta('icon');
		echo $this->Html->meta(array('name' => 'X-UA-Compatible', 'content' => 'IE=edge'));
		echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'));
		echo $this->fetch('meta');

		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('geocities/bootstrap.min.css');
		echo $this->Html->css('bootstrap-between.css');
		echo $this->Html->css('bootstrap-responsive.min.css');
		echo $this->Html->css('//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
		echo $this->fetch('css');

	?>

</head>
<body>

	<header class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<span class="brand"><?php echo $this->Html->image('Daz3D_23016.gif', array('class' => 'geobootstrap')); ?><span class="not_geobootstrap">DAZ</span> Fun-☺-rama <?php echo $this->Html->image('me.jpg', array('class' => 'geobootstrap')); ?><span class="not_geobootstrap">ME</span>&reg;</span>
			<?php
				$menu = array(
					array('Home', array('controller' => 'home', 'action' => 'index')),
					array('Tournaments', array('controller' => 'tournaments', 'action' => 'index')),
//					array('Players', array('controller' => 'players', 'action' => 'index')),
//					array('Games', array('controller' => 'games', 'action' => 'index')),
					array('Stats', array('controller' => 'stats', 'action' => 'index')),
					array('Tunes', array('admin' => true, 'prefix' => 'admin', 'controller' => 'songs', 'action' => 'index')),
				);
			?>
			<ul class="nav">
				<?php echo $this->Menu->menu($menu); ?>
			</ul>
		</div>
	</header>

	<div class="container">
		<?php echo $this->Html->image('Winking.gif', array('class' => 'geobootstrap')); ?>
		<?php echo $this->Html->image('http://www.textfiles.com/underconstruction/mamagnolia_acresunderconstruction.gif', array('class' => 'geobootstrap')); ?>
		<?php echo $this->Html->image('http://2.bp.blogspot.com/_Ze5Xm5fW-4o/TUnUBt6ADUI/AAAAAAAABJA/2WGSLTNK1K4/s1600/broken-link-image-gif.jpg', array('class' => 'geobootstrap', 'width' => 100, 'height' => 100)); ?>
		<?php echo $this->Html->image('NakedGrayChick.gif', array('class' => 'geobootstrap')); ?>

		<div class="row">
			<div id="content" class="span12">
				<?php echo $this->Session->flash( ); ?>
				<?php echo $this->Session->flash('auth'); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	</div>

	<footer class="geobootstrap">
		<?php echo $this->Html->image('flames.gif'); ?>
		<?php echo $this->Html->image('flames.gif'); ?>
		<br />
		Hits since November 13th, 1996: <?php echo $this->Html->image('counter.gif'); ?>
		<?php echo $this->Html->image('dancingbaby2.gif'); ?>
		Best Viewed on: <?php echo $this->Html->image('netscape_anim.gif'); ?> @ 800px x 600px
		<?php echo $this->Html->image('g3103.gif'); ?>
		Built with: <?php echo $this->Html->image('logo-powerpoint.gif'); ?>
		<a href="/guestbook.asp">Sign our GuestBook!</a>
		¡Hasta Mañana!
	</footer>

	<?php

		echo $this->Html->scriptblock('var ROOT_URL = "'.$this->Html->url('/').'";');
		echo $this->Html->script('//code.jquery.com/jquery-2.0.3.min.js');
		echo $this->Html->script('//code.jquery.com/ui/1.10.4/jquery-ui.js');
		echo $this->Html->script('bootstrap.min.js'); // bootstrap breaks ui.button( )
		echo $this->fetch('script');

		echo $this->fetch('scriptBottom');
		echo $this->Html->script('jquery.konami.js');

	?>

</body>
</html>
