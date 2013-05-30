<?php
// should be identical to admin_filter_select.ctp

	foreach ($filter_selects as $value => $option) {
		echo '<option value="'.$value.'">'.$option.'</option>';
	}

