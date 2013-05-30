<?php

// run through each setting and see if any of them are files
// or if any of them need tiny
$file = array( );
$wysiwyg = false;
foreach ($this->request->data as $key => $settings) {
	$this->request->data[$key]['Setting']['class'] = '';
	if ('wysiwyg' == $settings['Setting']['type']) {
		$wysiwyg = true;
		$this->request->data[$key]['Setting']['class'] = 'ckeditor';
		$this->request->data[$key]['Setting']['type'] = 'textarea';
	}

	if ('file' == $settings['Setting']['type']) {
		$file = array('type' => 'file');
	}
}

if ($wysiwyg) {
	$this->element('ckeditor');
}

?>

<div class="settings form">
	<?php echo $this->Form->create('Setting', $file);?>
		<fieldset>
			<legend><?php echo __('Edit Settings'); ?></legend>

		<?php
			foreach ($this->request->data as $key => $setting) {
				$options = array(
					'label' => Inflector::humanize($setting['Setting']['name']),
					'value' => $setting['Setting']['value'],
					'type' => $setting['Setting']['type'],
				);

				if ( ! empty($setting['Setting']['class'])) {
					$options['class'] = $setting['Setting']['class'];
				}

				if (('checkbox' == $setting['Setting']['type']) && $setting['Setting']['value']) {
					$options['checked'] = 'checked';
				}

				echo $this->Form->input('Setting.'.$key.'.id', array('value' => $setting['Setting']['id']));
				echo $this->Form->input('Setting.'.$key.'.value', $options);
			}
		?>

		</fieldset>
	<?php echo $this->Form->end(__('Submit'));?>
</div>

