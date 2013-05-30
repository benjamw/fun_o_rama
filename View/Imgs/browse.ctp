<?php $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js', array('inline' => false)); ?>
<?php $this->Html->scriptblock('
	jQuery(document).ready( function($) {
		$("li a").click( function( ) {
			window.opener.CKEDITOR.tools.callFunction('.$callback.', $(this).attr("href"));
			window.close( );
			return false;
		});
	});
', array('inline' => false)); ?>

<ul>

	<?php foreach ($filelist as $href => $file) { ?>

	<li><a href="<?php echo $href; ?>"><?php echo $file; ?></a></li>

	<?php } ?>

</ul>