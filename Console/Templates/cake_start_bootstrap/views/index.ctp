<?php

// run the names through our custom functions
include 'functions.php';
$pluralHumanName = replace_tlis($pluralHumanName);
$singularHumanName = replace_tlis($singularHumanName);
$controller = Inflector::tableize($modelClass);

?>

<div class="<?php echo $pluralVar; ?> index">
	<h3><?php echo "<?php echo __('{$pluralHumanName}'); ?> <?php echo \$this->Html->link(__('New {$singularHumanName}'), array('controller' => '{$controller}', 'action' => 'add'), array('class' => 'btn btn-xs btn-info')); ?>"; ?></h3>

	<div class="clearfix">
		<?php echo "<?php echo \$this->element('admin_filter'); ?>\n"; ?>
	</div>

	<?php echo "<?php echo \$this->element('bootstrap_pagination'); ?>\n"; ?>

	<table class="table table-striped table-condensed">
		<thead>
			<tr>
<?php
			foreach ($fields as $field) {
				echo "\t\t\t\t<th><?php echo \$this->Paginator->sort('{$field}'); ?></th>\n";
			}
?>
				<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
			</tr>
		</thead>
		<tbody>
<?php
		echo "\t\t<?php foreach (\${$pluralVar} as \${$singularVar}) { ?>\n";
		echo "\t\t\t<tr class=\"table-hover\">\n";

		foreach ($fields as $field) {
			$isKey = false;

			if ( ! empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>&nbsp;</td>\n";
						break;
					}
				}
			}

			if (true !== $isKey) {
				if ('active' != $field) {
					echo "\t\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				}
				else {
					echo "\t\t\t\t<td><?php echo ucfirst(Set::enum((int) \${$singularVar}['{$modelClass}']['{$field}'])); ?>&nbsp;</td>\n";
				}
			}
		}

		echo "\t\t\t\t<td class=\"actions\">\n";
		echo "\t\t\t\t\t<div class=\"btn-group\"><?php\n";
		echo "\t\t\t\t\t\techo \$this->Html->link(__('View'), array('controller' => '{$controller}', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-xs btn-default'));\n";
	 	echo "\t\t\t\t\t\techo \$this->Html->link(__('Edit'), array('controller' => '{$controller}', 'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-xs btn-default'));\n";
	 	echo "\t\t\t\t\t\techo \$this->Form->postLink(__('Delete'), array('controller' => '{$controller}', 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-xs btn-warning'), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']));\n";
	 	echo "\t\t\t\t\t?></div>\n";
		echo "\t\t\t\t</td>\n";

		echo "\t\t\t</tr>\n";

		echo "\t\t<?php } ?>\n";
?>
		</tbody>
	</table>

	<?php echo "<?php echo \$this->element('bootstrap_pagination'); ?>\n"; ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('controller' => '{$controller}', 'action' => 'add')); ?>"; ?></li>
	</ul>
</div>

