<?php $this->element('ckeditor'); ?>

<div class="pages form">
	<?php echo $this->Form->create('Page'); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Page'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}

				$options = array( );
				if ( ! $allow_slug_edit) {
					$options = array(
						'readonly' => 'readonly',
					);
				}

				echo $this->Form->input('title', $options);
				echo $this->Form->input('slug', $options);
				echo $this->Form->input('copy', array('class' => 'ckeditor'));
				echo $this->Form->input('active', array('type' => 'checkbox'));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if ($allow_add_delete && (false !== strpos($this->action, 'edit'))) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('controller' => 'pages', 'action' => 'delete', $this->Form->value('Page.id')), array('class' => 'delete'), __('Are you sure you want to delete Page #%s?', $this->Form->value('Page.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Pages'), array('controller' => 'pages', 'action' => 'index')); ?></li>
	</ul>
</div>

