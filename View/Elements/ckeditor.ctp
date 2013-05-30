<?php

	$this->Html->scriptblock('
		var ROOT_URL = "'.$this->Html->url('/').'";
	', array('inline' => false));

	// only include jquery if we are on an admin page
	if ( ! empty($this->request->admin)) {
		$this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js', array('inline' => false));
	}
	$this->Html->script('ckeditor/ckeditor.js', array('inline' => false));
	$this->Html->script('ckeditor/adapters/jquery.js', array('inline' => false));
	$this->Html->scriptblock('
		jQuery(document).ready( function($) {
			$(".ckeditor_slim").ckeditor({
				toolbar : "Slim"
			});
			$(".ckeditor_slim_image").ckeditor({
				toolbar : "SImage"
			});
			$(".ckeditor_basic").ckeditor({
				toolbar : "Basic"
			});
			$(".ckeditor_basic_image").ckeditor({
				toolbar : "BImage"
			});
			$(".ckeditor_slim_no_p").ckeditor({
				toolbar : "Slim",
				enterMode : 2
			});
			$(".ckeditor_basic_no_p").ckeditor({
				toolbar : "Basic",
				enterMode : 2
			});
			$(".ckeditor_full_no_p").ckeditor({
				toolbar : "Full",
				enterMode : 2
			});
		});
	', array('inline' => false));

