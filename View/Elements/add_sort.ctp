<?php $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js', array('inline' => false)); ?>
<?php $this->Html->script('add_sort.js', array('inline' => false)); ?>

<?php $var_name = Inflector::variable(Inflector::pluralize($model)); ?>
<div class="select add">
	<label><?php echo $model; ?></label>
	<div class="holder">
		<div class="clone">
			<?php echo $this->Form->select($model.'.NNN.id', ${$var_name}, null, array('class' => 'narrow')); ?>
			<?php echo $this->Form->text($model.'.NNN.sort', array('value' => 99999)); ?>
			<?php echo $this->Html->image('delete.png', array('class' => 'delete', 'title' => 'Delete this '.$model, 'alt' => 'Delete this '.$model)); ?>
		</div>

	<?php if (isset($this->request->data[$model]) && count($this->request->data[$model])) { ?>

		<?php $this->Html->scriptblock('var add_sort_start = '.(count($this->request->data[$model]) + 1).';', array('inline' => false)); ?>

		<?php foreach ($this->request->data[$model] as $key => $entry) { ?>

		<div>
			<?php echo $this->Form->select($model.'.'.$key.'.id', ${$var_name}, $entry[$model_join][strtolower($model).'_id'], array('class' => 'narrow')); ?>
			<?php echo $this->Form->text($model.'.'.$key.'.sort', array('value' => (int) $entry[$model_join]['sort'])); ?>
			<?php echo $this->Html->image('delete.png', array('class' => 'delete', 'title' => 'Delete this '.$model, 'alt' => 'Delete this '.$model)); ?>
		</div>

		<?php } ?>

		<div>
			<?php echo $this->Form->select($model.'.'.($key + 1).'.id', ${$var_name}, null, array('class' => 'narrow')); ?>
			<?php echo $this->Form->text($model.'.'.($key + 1).'.sort', array('value' => 99999)); ?>
			<?php echo $this->Html->image('delete.png', array('class' => 'delete', 'title' => 'Delete this '.$model, 'alt' => 'Delete this '.$model)); ?>
		</div>

	<?php } else { ?>

		<?php $this->Html->scriptblock('var add_sort_start = 1;', array('inline' => false)); ?>

		<div>
			<?php echo $this->Form->select($model.'.0.id', ${$var_name}, null, array('class' => 'narrow')); ?>
			<?php echo $this->Form->text($model.'.0.sort', array('value' => 99999)); ?>
			<?php echo $this->Html->image('delete.png', array('class' => 'delete', 'title' => 'Delete this '.$model, 'alt' => 'Delete this '.$model)); ?>
		</div>

	<?php } ?>

	</div>

	<?php echo $this->Html->image('add.png', array('class' => 'add', 'title' => 'Add New '.$model, 'alt' => 'Add New '.$model)); ?>
</div>

