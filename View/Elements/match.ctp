<?php
	if ( ! empty($match['Match'])) {
		if ( ! empty($match['Team'])) {
			$match['Match']['Team'] = $match['Team'];
		}

		$match = $match['Match'];
	}
?>

	<div class="match well well-bright">
		<strong><?php echo $match['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
		<div class="outcomes pull-right">
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][0])); ?>" id="res_<?php echo $match['id'].'_'.$match['Team'][0]['id']; ?>"><?php
				echo $match['Team'][0]['name'];
				echo (( ! empty($match['Team'][0]['seed'])) ? ' (#'.$match['Team'][0]['seed'].')' : ' (Team 1)');
			?></button>
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][1])); ?>" id="res_<?php echo $match['id'].'_'.$match['Team'][1]['id']; ?>"><?php
				echo $match['Team'][1]['name'];
				echo (( ! empty($match['Team'][1]['seed'])) ? ' (#'.$match['Team'][1]['seed'].')' : ' (Team 1)');
			?></button>
			<button class="btn btn-mini btn-warning" id="res_<?php echo $match['id'].'_0'; ?>">Tie</button>
		</div>
	</div>
