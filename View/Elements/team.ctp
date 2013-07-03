<?php

	$unique = substr(md5(uniqid(microtime(true), true)), 0, 5);
	$swap = my_ife($swap, false);
	$link = my_ife($link, false);
	$span = my_ife($span, '');

?>

	<div class="team well <?php echo $span; ?> team_<?php echo $team['id']; ?>" id="team_<?php echo $team['id'].'_'.$unique; ?>">
		<h5 class="name"><span class="clickable"><?php echo (('Unnamed Team' === $team['name']) ? 'Team '.($team_num + 1) : $team['name']); ?></span><?php
			if ( ! empty($team['seed'])) { echo ' (#'.$team['seed'].')'; }
		?></h5>
		<ul class="<?php if ($swap) { echo 'swappable'; } ?>">
		<?php foreach ($team['Player'] as $player) { $pl = $player; ?>
			<li id="pl_<?php echo $pl['id'].'_'.$unique;; ?>"><?php
				if ($link) {
					echo $this->Html->link($pl['name'], array('controller' => 'players', 'action' => 'view', $pl['id']));
				}
				else {
					echo $pl['name'];
				}

				if ( ! empty($pl['PlayerRanking'][0]['mean'])) {
					echo ' ('.number_format($pl['PlayerRanking'][0]['mean'], 2).')';
				}
			?></li>
		<?php } ?>
		</ul>
	</div>

