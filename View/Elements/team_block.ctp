<?php

	$unique = my_ife($unique, substr(md5(uniqid(microtime(true), true)), 0, 5));
	$game_type_id = my_ife($game_type_id, 0);
	$swap = my_ife($swap, false);
	$link = my_ife($link, false);
	$span = my_ife($span, '');

?>

	<div class="team well <?php echo $span; ?> team_<?php echo $team['id']; ?>" id="team_<?php echo $team['id'].'_'.$unique; ?>">
		<h5 class="name"><span class="clickable"><?php echo (('Unnamed Team' === $team['name']) ? 'Team '.($team_num + 1) : $team['name']); ?></span><?php
			if ( ! empty($team['seed'])) { echo ' (#'.$team['seed'].')'; }
		?></h5>
		<ul class="<?php if ($swap) { echo 'swappable'; } ?>">
		<?php
			foreach ($team['Player'] as $player) {
				echo $this->element('player_li', compact('player', 'game_type_id', 'unique', 'link'));
			}
		?>
		</ul>
	</div>

