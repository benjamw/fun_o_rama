<?php

// don't include jQuery in default layout
if ( ! empty($this->request->admin)) {
	$this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js', array('inline' => false));
}

$this->Html->css('smoothness/jquery-ui-1.8.16.custom.css', null, array('inline' => false));
$this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', array('inline' => false));

$this->Html->script('json.js', array('inline' => false));
$this->Html->script('inflector.js', array('inline' => false));

$this->Html->scriptblock('

	var use_fast = true;

	var jqxhr;
	var AC_ROOT_URL = "'.$this->Html->url('/', true).'";

	jQuery(document).ready( function($) {
		$("input.autocomplete").autocomplete({
			minLength: 2,
			source: function(request, response) {
				// abort any previous ajax calls
				if ("undefined" != typeof jqhxr) {
					jqxhr.abort( );
				}

				// set a default class name
				var class_name = this.element[0].id.slice(0, -1);

				// find the first index in the form name
				var myregexp = /data\[([^\]]+)\]/im;
				var match = myregexp.exec(this.element[0].name);
				if (match != null) {
					class_name = match[1];
				}

				var controller = inflector.tableize(class_name);

				var xmit = {term : request.term};

				var url = AC_ROOT_URL + controller + "/ac";
				if (use_fast) {
					url = AC_ROOT_URL + "ac.php";
					xmit["cont"] = controller;
				}

				jqxhr = $.ajax({
					type: "POST",
					url: url,
					data: xmit,
					success: function(msg) {
						if (msg[0] != "[") {
							response(["ERROR"]);
						}
						else {
							response(JSON.parse(msg));
						}
					},
					error: function(jqHXR, textStatus, errorThrown) {
						response(["ERROR - "+errorThrown]);
					}
				});
			}
		});
	});

', array('inline' => false));

