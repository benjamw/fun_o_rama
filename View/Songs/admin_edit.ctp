
<div class="songs form">
	<?php echo $this->Form->create('Song', array('type' => 'file')); ?>
		<fieldset>
			<legend><?php echo __(Inflector::humanize(substr($this->action, 6)).' Song'); ?></legend>

			<?php
				if (false !== strpos($this->action, 'edit')) {
					echo $this->Form->input('id');
				}
				echo $this->Form->input('player_id');
				echo $this->Form->input('title', array('after' => '<span class="help-block">Song title will be automatically filled if left blank</span>'));
				echo $this->Form->input('file', array('type' => 'file'));
				echo $this->Form->input('file_dir', array('type' => 'hidden'));
			?>

		</fieldset>

		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end( ); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<?php if (false !== strpos($this->action, 'edit')) { ?>
		<li><?php echo $this->Form->postLink(__('Delete'), array('controller' => 'songs', 'action' => 'delete', $this->Form->value('Song.id')), array('class' => 'delete'), __('Are you sure you want to delete Song #%s?', $this->Form->value('Song.id'))); ?></li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('List Songs'), array('controller' => 'songs', 'action' => 'index')); ?></li>
	</ul>
</div>

