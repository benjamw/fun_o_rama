<?php
/**
 * Model template file.
 *
 * Used by bake to create new Model files.
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
echo "App::uses('{$plugin}AppModel', '{$pluginPath}Model');\n\n";
?>
class <?php echo $name ?> extends <?php echo $plugin; ?>AppModel {
<?php if ('default' != $useDbConfig) { ?>

	public $useDbConfig = '<?php echo $useDbConfig; ?>';
<?php } ?>
<?php if ($useTable && (Inflector::tableize($name) !== $useTable)) {
	$table = "'{$useTable}'";
	echo "\tpublic \$useTable = {$table};\n";
}

if ($primaryKey !== 'id') { ?>

	public $primaryKey = '<?php echo $primaryKey; ?>';
<?php }

if ($displayField) { ?>

	public $displayField = '<?php echo $displayField; ?>';
<?php }

if ( ! empty($validate)) {
	echo "\n\tpublic \$validate = array(\n";

	foreach ($validate as $field => $validations) {
		echo "\t\t'{$field}' => array(\n";

		foreach ($validations as $key => $validator) {
			echo "\t\t\t'{$key}' => array(\n";
			echo "\t\t\t\t'rule' => array('{$validator}'),\n";
			echo "\t\t\t\t//'message' => 'Your custom message here',\n";
			echo "\t\t\t\t//'allowEmpty' => false,\n";
			echo "\t\t\t\t//'required' => false,\n";
			echo "\t\t\t\t//'last' => false, // Stop validation after this rule\n";
			echo "\t\t\t\t//'on' => 'create', // Limit validation to 'create' or 'update' operations\n";
			echo "\t\t\t),\n";
		}

		echo "\t\t),\n";
	}

	echo "\t);\n";
}

foreach (array('hasOne', 'belongsTo') as $assocType) {
	if ( ! empty($associations[$assocType])) {
		$typeCount = count($associations[$assocType]);

		echo "\n\tpublic \${$assocType} = array(";

		foreach ($associations[$assocType] as $i => $relation) {
			$out = "\n\t\t'{$relation['alias']}' => array(\n";
			$out .= "\t\t\t'className' => '{$relation['className']}',\n";
			$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
			$out .= "\t\t\t'conditions' => '',\n";
			$out .= "\t\t\t'fields' => '',\n";
			$out .= "\t\t\t'order' => '',\n";
			$out .= "\t\t),";
			echo $out;
		}

		echo "\n\t);\n";
	}
}

if ( ! empty($associations['hasMany'])) {
	$belongsToCount = count($associations['hasMany']);

	echo "\n\tpublic \$hasMany = array(";

	foreach ($associations['hasMany'] as $i => $relation) {
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'dependent' => false,\n";
		$out .= "\t\t\t'conditions' => '',\n";
		$out .= "\t\t\t'fields' => '',\n";
		$out .= "\t\t\t'order' => '',\n";
		$out .= "\t\t\t'limit' => '',\n";
		$out .= "\t\t\t'offset' => '',\n";
		$out .= "\t\t\t'exclusive' => '',\n";
		$out .= "\t\t\t'finderQuery' => '',\n";
		$out .= "\t\t\t'counterQuery' => '',\n";
		$out .= "\t\t),";
		echo $out;
	}

	echo "\n\t);\n";
}

if ( ! empty($associations['hasAndBelongsToMany'])) {
	$habtmCount = count($associations['hasAndBelongsToMany']);

	echo "\n\tpublic \$hasAndBelongsToMany = array(";

	foreach ($associations['hasAndBelongsToMany'] as $i => $relation) {
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
		$out .= "\t\t\t'unique' => true,\n";
		$out .= "\t\t\t'conditions' => '',\n";
		$out .= "\t\t\t'fields' => '',\n";
		$out .= "\t\t\t'order' => '',\n";
		$out .= "\t\t\t'limit' => '',\n";
		$out .= "\t\t\t'offset' => '',\n";
		$out .= "\t\t\t'finderQuery' => '',\n";
		$out .= "\t\t\t'deleteQuery' => '',\n";
		$out .= "\t\t\t'insertQuery' => '',\n";
		$out .= "\t\t),";
		echo $out;
	}

	echo "\n\t);\n";
}

?>

}

