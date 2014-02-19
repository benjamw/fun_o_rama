<?php $this->Html->scriptblock('
	var T = "'.$this->params['pass'][0].'",
		SONG = '.json_encode($song['Song']).';
', array('block' => 'script')); ?>
<?php $this->Html->script('play.js', array('block' => 'scriptBottom')); ?>

<div id="title"><?php echo $song['Song']['title']; ?></div>
<div id="time"></div>
<div>
	<button id="toggle">Play</button>
	<button id="skip">Skip</button>
</div>

<audio id="song" src="<?php echo $this->Html->url($song['Song']['__file']); ?>"></audio>

