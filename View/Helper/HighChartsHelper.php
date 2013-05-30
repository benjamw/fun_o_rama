<?php
/* SVN FILE: $Id$ */
/**
 * High Charts helper library.
 *
 * Methods to make using High Charts easier.
 *
 */
class HighChartsHelper extends AppHelper {

	var $helpers = array('Html');

	var $default = array( );

	// include the required scripts in the <head>
	function script($highcharts, $jquery = false) {
		if ($jquery) {
			$this->Html->script($jquery, false);
		}

		$this->Html->script($highcharts, false);
		$hc_dir = dirname($highcharts);

		$out = '<!--[if IE]>';
		$out .= $this->Html->script($hc_dir.'excanvas.compiled.js');
		$out .= '<![endif]-->';

		$view =& ClassRegistry::getObject('view');
		$view->addScript($out);
	}

	function options($options = array( )) {
		$this->default = array_merge($this->default, $options);
	}

	// generate a high charts pie graph
	function pie_chart($data, $options = array( )) {

	}

}

