<?php

// run the names through our custom functions
include 'functions.php';
$pluralHumanName = replace_tlis($pluralHumanName);
$singularHumanName = replace_tlis($singularHumanName);
$controller = Inflector::tableize($modelClass);

?>

<div class="<?php echo $pluralVar; ?> view">

	<h3><?php echo "<?php echo __('{$singularHumanName}'); ?>"; ?></h3>
	<dl class="dl-horizontal">

<?php
	foreach ($fields as $field) {
		$isKey = false;

		if ( ! empty($associations['belongsTo'])) {
			foreach ($associations['belongsTo'] as $alias => $details) {
				if ($field === $details['foreignKey']) {
					$isKey = true;
					echo "\t\t<dt><?php echo __('" . replace_tlis(Inflector::humanize(Inflector::underscore($alias))) . "'); ?></dt>\n";
					echo "\t\t<dd><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>&nbsp;</dd>\n\n";
					break;
				}
			}
		}

		if ($isKey !== true) {
			echo "\t\t<dt><?php echo __('" . replace_tlis(Inflector::humanize($field)) . "'); ?></dt>\n";

			if ('active' != $field) {
				echo "\t\t<dd><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</dd>\n\n";
			}
			else {
				echo "\t\t<dd><?php echo ucfirst(Set::enum((int) \${$singularVar}['{$modelClass}']['{$field}'])); ?>&nbsp;</dd>\n\n";
			}
		}
	}
?>
	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
<?php
	echo "\t\t<li><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."'), array('controller' => '{$controller}', 'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('controller' => '{$controller}', 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'delete'), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('controller' => '{$controller}', 'action' => 'index')); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('controller' => '{$controller}', 'action' => 'add')); ?> </li>\n";
?>
	</ul>
</div>

<?php

if ( ! empty($associations['hasOne'])) {
	foreach ($associations['hasOne'] as $alias => $details) { ?>
<div class="related well">
	<h3><?php echo "<?php echo __('Related " . replace_tlis(Inflector::humanize($details['controller'])) . "'); ?>"; ?></h3>

<?php echo "\t<?php if ( ! empty(\${$singularVar}['{$alias}'])) { ?>\n"; ?>
	<dl>

<?php
	foreach ($details['fields'] as $field) {
		if ($field == $details['foreignKey']) {
			continue;
		}

		echo "\t\t<dt><?php echo __('" . replace_tlis(Inflector::humanize($field)) . "'); ?></dt>\n";
		if ('active' != $field) {
			echo "\t\t<dd><?php echo \${$singularVar}['{$alias}']['{$field}']; ?>&nbsp;</dd>\n\n";
		}
		else {
			echo "\t\t<dd><?php echo ucfirst(Set::enum((int) \${$singularVar}['{$alias}']['{$field}'])); ?>&nbsp;</dd>\n\n";
		}
	}
?>
	</dl>
<?php echo "\t<?php } ?>\n"; ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo "<?php echo \$this->Html->link(__('Edit " . replace_tlis(Inflector::humanize(Inflector::underscore($alias))) . "'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>"; ?></li>
		</ul>
	</div>
</div>

<?php

	}
}

if (empty($associations['hasMany'])) {
	$associations['hasMany'] = array( );
}

if (empty($associations['hasAndBelongsToMany'])) {
	$associations['hasAndBelongsToMany'] = array( );
}

$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
foreach ($relations as $alias => $details) {
	$otherSingularVar = replace_tlis(Inflector::variable($alias));
	$otherPluralHumanName = replace_tlis(Inflector::humanize($details['controller']));
	$otherSingularHumanName = replace_tlis(Inflector::humanize(Inflector::underscore(Inflector::singularize($details['controller']))));
	?>
<div class="related well">
	<h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>

<?php echo "<?php if ( ! empty(\${$singularVar}['{$alias}'])) { ?>\n"; ?>
	<table class="table table-striped table-condensed">
		<tr>
<?php
	foreach ($details['fields'] as $field) {
		if ($field == $details['foreignKey']) {
			continue;
		}

		echo "\t\t\t<th><?php echo __('" . replace_tlis(Inflector::humanize($field)) . "'); ?></th>\n";
	}
?>
			<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
		</tr>

<?php
	echo "\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}) { ?>\n";
	echo "\t\t<tr class=\"table-hover\">\n";

	foreach ($details['fields'] as $field) {
		if ($field == $details['foreignKey']) {
			continue;
		}

		if ('active' != $field) {
			echo "\t\t\t<td><?php echo h(\${$otherSingularVar}['{$field}']); ?>&nbsp;</td>\n";
		}
		else {
			echo "\t\t\t<td><?php echo ucfirst(Set::enum((int) \${$otherSingularVar}['{$field}'])); ?>&nbsp;</td>\n";
		}
	}

	echo "\t\t\t<td class=\"actions\">\n";
	echo "\t\t\t\t<div class=\"btn-group\">\n";
	echo "\t\t\t\t\t<?php echo \$this->Html->link(__('View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-xs btn-default')); ?>\n";
	echo "\t\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-xs btn-default')); ?>\n";
	echo "\t\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-xs btn-warning'), __('Are you sure you want to delete {$otherSingularHumanName} #%s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
	echo "\t\t\t\t</div>\n";
	echo "\t\t\t</td>\n";

	echo "\t\t</tr>\n";

	echo "\t<?php } ?>\n";
?>

	</table>
<?php echo "<?php } ?>\n"; ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo "<?php echo \$this->Html->link(__('List " . replace_tlis(Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias)))) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?>"; ?> </li>
			<li><?php echo "<?php echo \$this->Html->link(__('New " . replace_tlis(Inflector::humanize(Inflector::underscore($alias))) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?>"; ?> </li>
		</ul>
	</div>
</div>

<?php }

