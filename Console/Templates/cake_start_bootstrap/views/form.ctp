<?php

// run the names through our custom functions
include 'functions.php';
$pluralHumanName = replace_tlis($pluralHumanName);
$singularHumanName = replace_tlis($singularHumanName);
$controller = Inflector::tableize($modelClass);

$admin = Configure::read('Routing.prefixes.0');
if ($admin) {
	$admin .= '_';
}

$ad_len = strlen($admin);

// search for a field like the following and add the file type if found
$file_types = array(
	'image',
	'file',
	'upload',
);

$file_type = '';
if (array_intersect($file_types, $fields)) {
	$file_type = ", 'type' => 'file'";
}

?>

<div class="<?php echo $pluralVar; ?> form">
	<?php echo "<?php echo \$this->Form->create('{$modelClass}', array('class' => 'form-horizontal'". $file_type .")); ?>\n"; ?>
		<fieldset>
			<legend><?php echo "<?php echo __(Inflector::humanize(substr(\$this->action, {$ad_len})).' {$singularHumanName}'); ?>"; ?></legend>

<?php
			echo "\t\t\t<?php\n";

			foreach ($fields as $field) {
				if ((false !== strpos($action, 'add')) && ($field == $primaryKey)) {
					continue;
				}
				elseif ($field == $primaryKey) {
					echo "\t\t\t\tif (false !== strpos(\$this->action, 'edit')) {\n";
					echo "\t\t\t\t\techo \$this->Form->input('{$field}');\n";
					echo "\t\t\t\t}\n";
				}
				elseif (in_array($field, $file_types)) {
					echo "\t\t\t\techo \$this->Form->input('{$field}', array('type' => 'file'));\n";
				}
				elseif ('sort' == $field) {
					echo "\t\t\t\techo \$this->Form->input('{$field}', array('value' => isset(\$this->request->data['{$modelClass}']['sort']) ? \$this->request->data['{$modelClass}']['sort'] : 99999));\n";
				}
				elseif ('active' == $field) {
					echo "\t\t\t\techo \$this->Form->input('{$field}', array('type' => 'checkbox'));\n";
				}
				elseif ( ! in_array($field, array('created', 'modified', 'updated'))) {
					echo "\t\t\t\techo \$this->Form->input('{$field}');\n";
				}
			}

			if ( ! empty($associations['hasAndBelongsToMany'])) {
				foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
					echo "\t\t\t\techo \$this->Form->input('{$assocName}');\n";
				}
			}

			echo "\t\t\t?>\n";

			echo "\n\t\t\t<?php echo \$this->Form->submit(__('Submit')); ?>\n";
			echo "\n\t\t</fieldset>\n";
			echo "\t<?php echo \$this->Form->end( ); ?>\n";
?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
<?php if (strpos($action, 'add') === false) { ?>
		<?php echo "<?php if (false !== strpos(\$this->action, 'edit')) { ?>\n"; ?>
		<li><?php echo "<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$controller}', 'action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('class' => 'delete'), __('Are you sure you want to delete {$singularHumanName} #%s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>"; ?></li>
		<?php echo "<?php } ?>\n"; ?>
<?php } ?>
		<li><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('controller' => '{$controller}', 'action' => 'index')); ?>"; ?></li>
	</ul>
</div>

