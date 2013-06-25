<?php

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {

	// this function allows adding scripts multiple times
	// even if they are already included in the layout
	// while still keeping them in the proper order
	// scripts added in the layout MUST have array('inline' => false)
	// for this to work
	public function processScripts( ) {
		if ('HtmlHelper' !== $this->toString( )) {
			return false;
		}

		$view =& ClassRegistry::getObject('view');

		echo implode("\n\t", $view->__scripts);
	}

}

