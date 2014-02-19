<?php
	if ( ! empty($match['Match'])) {
		if ( ! empty($match['Team'])) {
			$match['Match']['Team'] = $match['Team'];
		}

		$match = $match['Match'];
	}

	$match_id = $match['id'];

?>

	<div class="match well well-bright">
		<strong><?php echo $match['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Tunes', array('controller' => 'songs', 'action' => 'play', $match['id']), array('class' => 'btn btn-mini btn-inverse')); ?>

		<?php foreach ($match['Team'] as $team_num => $team) { ?>

			<?php echo $this->element('team_button', compact('team', 'team_num', 'match_id')); ?>

		<?php } ?>

			<button class="btn btn-mini btn-warning" id="res_<?php echo $match['id'].'_0'; ?>">Tie</button>
		</div>
	</div>
