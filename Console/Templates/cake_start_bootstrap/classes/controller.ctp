<?php

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

