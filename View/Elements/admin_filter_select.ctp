<?php
// should be identical to filter_select.ctp

	foreach ($filter_selects as $value => $option) {
		echo '<option value="'.$value.'">'.$option.'</option>';
	}

