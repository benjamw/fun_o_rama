<?php
	$this->layout = 'ajax';

	foreach ($current_matches as $match) {
		echo $this->element('match', compact('match'));
	}

