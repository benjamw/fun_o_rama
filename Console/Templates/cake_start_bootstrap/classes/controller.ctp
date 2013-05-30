<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.actions
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "<?php\n\n";
echo "App::uses('{$plugin}AppController', '{$pluginPath}Controller');\n\n";
?>
class <?php echo $controllerName; ?>Controller extends <?php echo $plugin; ?>AppController {
<?php if ($isScaffold) { ?>

	public $scaffold;

<?php } else {

	if (count($helpers)) {
		echo "\n\tpublic \$helpers = array(\n";

		for ($i = 0, $len = count($helpers); $i < $len; $i++) {
			echo "\t\t'" . Inflector::camelize($helpers[$i]) . "',\n";
		}

		echo "\t);\n";

		if ( ! count($components) && ('' !== trim($actions))) {
			echo "\n";
		}
	}

	if (count($components)) {
		echo "\n\tpublic \$components = array(\n";

		for ($i = 0, $len = count($components); $i < $len; $i++) {
			echo "\t\t'" . Inflector::camelize($components[$i]) . "',\n";
		}

		echo "\t);\n";

		if ('' !== trim($actions)) {
			echo "\n";
		}
	}

	if ('' !== trim($actions)) {
		echo "\n\t".trim($actions)."\n";
	}

} ?>

}

