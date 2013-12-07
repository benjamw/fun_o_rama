<?php
/**
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
 * @package       cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// run the names through our custom functions
include 'functions.php';
$pluralHumanName = replace_tlis($pluralHumanName);
$singularHumanName = replace_tlis($singularHumanName);
$controller = Inflector::tableize($modelClass);

?>

<div class="<?php echo $pluralVar; ?> index">
	<h2><?php echo "<?php echo __('{$pluralHumanName}'); ?> <?php echo \$this->Html->link(__('New {$singularHumanName}'), array('controller' => '{$controller}', 'action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?>"; ?></h2>

	<div class="clearfix">
		<?php echo "<?php echo \$this->element('admin_filter'); ?>\n"; ?>
	</div>

	<?php echo "<?php echo \$this->element('bootstrap_pagination'); ?>\n"; ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
<?php
			foreach ($fields as $field) {
				echo "\t\t\t<th><?php echo \$this->Paginator->sort('{$field}'); ?></th>\n";
			}
?>
			<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
		</tr>

<?php
		echo "\t<?php foreach (\${$pluralVar} as \${$singularVar}) { ?>\n";
		echo "\t\t<tr class=\"table-hover\">\n";

		foreach ($fields as $field) {
			$isKey = false;

			if ( ! empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>&nbsp;</td>\n";
						break;
					}
				}
			}

			if (true !== $isKey) {
				if ('active' != $field) {
					echo "\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				}
				else {
					echo "\t\t\t<td><?php echo ucfirst(Set::enum((int) \${$singularVar}['{$modelClass}']['{$field}'])); ?>&nbsp;</td>\n";
				}
			}
		}

		echo "\t\t\t<td class=\"actions\">\n";
		echo "\t\t\t\t<div class=\"btn-group\">\n";
		echo "\t\t\t\t\t<?php echo \$this->Html->link(__('View'), array('controller' => '{$controller}', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-small')); ?>\n";
	 	echo "\t\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('controller' => '{$controller}', 'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-small')); ?>\n";
	 	echo "\t\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$controller}', 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t\t\t</div>\n";
		echo "\t\t\t</td>\n";

		echo "\t\t</tr>\n";

		echo "\t<?php } ?>\n";
?>

	</table>

	<?php echo "<?php echo \$this->element('bootstrap_pagination'); ?>\n"; ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('controller' => '{$controller}', 'action' => 'add')); ?>"; ?></li>
	</ul>
</div>

