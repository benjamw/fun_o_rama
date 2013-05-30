<?php

$filter_items       = ife($filter_items, array( ));
$filter_item        = ife($filter_item, false);
$filter_comparisons = ife($filter_comparisons, array( ));
$filter_compare     = ife($filter_compare, false);
$filter_selects     = ife($filter_selects, array('Loading...'));
$filter_select      = ife($filter_select, false);
$filter_value       = ife($filter_value, false);

$this->Html->scriptblock('var FILTER_ROOT_URL = "'.$this->Html->url(array('controller' => $this->request->params['controller'])).'";', array('inline' => false));
$this->Html->script('admin_filter.js', array('block' => 'scriptBottom'));

?>

<div id="admin_filter" class="pull-right">
	<?php echo $this->Form->create('AdminFilter', array('url' => array('controller' => $this->request->params['controller'], 'action' => 'index'), 'class' => 'form-inline')); ?>
		<?php echo $this->Form->select('item', $filter_items, array('value' => $filter_item, 'escape' => false)); ?>
		<?php echo $this->Form->select('compare', $filter_comparisons, array('value' => $filter_compare, 'escape' => false)); ?>
		<?php echo $this->Form->select('select', $filter_selects, array('value' => $filter_select, 'style' => 'width:200px;', 'escape' => false)); ?>
		<?php echo $this->Form->text('value', array('value' => $filter_value)); ?>
		<?php echo $this->Form->submit('Filter', array('div' => false, 'class' => 'btn btn-info')); ?>
	<?php echo $this->Form->end( ); ?>
</div><!-- #filter -->

