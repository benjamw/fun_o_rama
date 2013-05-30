<?php
	if (empty($adjusting)) {
		$adjusting = false;
	}

	$games_var = array( );
	foreach ($games as $game_id => $game_name) {
		$games_var[] = array(
			'value' => $game_id,
			'text' => $game_name,
		);
	}
?>

<?php $this->Html->scriptblock('
	var MATCH_UPDATE_URL = "'.$this->Html->url(array('controller' => 'matches', 'action' => 'update')).'",
		pkid = '.$match['Match']['id'].',
		games = '.json_encode($games_var).';
', array('block' => 'script')); ?>
<?php $this->Html->css('bootstrap-editable.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('bootstrap-editable.min.js', array('block' => 'scriptBottom')); ?>
<?php $this->Html->script('start.js', array('block' => 'scriptBottom')); ?>

<h1><strong><?php echo $match['Game']['name']; ?></strong> started on <span class="date"><?php echo date('F j, Y @ h:ia', strtotime($match['Match']['created'])); ?></span></h1>

<div class="alert alert-block alert-info">
	<?php if ($geobootstrap) { ?><marquee behavior="alternate"><?php } ?>
	Feel free to move players around as needed to balance the teams a little better.
	<?php if ($geobootstrap) { ?></marquee><?php } ?>
</div>

<?php if ( ! empty($quality)) { ?>
<h4>Game Quality: <?php echo number_format($quality * 100, 2); ?>%</h4>
<?php } ?>

<div class="row">
<?php foreach ($match['Team'] as $team_num => $team) { $tm = ife($team['Player'], array( )); ?>
	<div class="span6">
		<div class="well" id="team_<?php echo ($team_num + 1); ?>">
			<h2 class="name"><span class="clickable"><?php echo (('Unnamed Team' === $team['name']) ? 'Team '.($team_num + 1) : $team['name']); ?></span>
			<br>
			<small>(Team <?php echo ($team_num + 1); ?>)</small></h2>
			<ul class="swappable" id="team_<?php echo $team['id']; ?>">
			<?php foreach ($tm as $player) { $pl = $player; ?>
				<li id="pl_<?php echo $pl['id']; ?>"><?php
					echo $this->Html->link($pl['name'], array('controller' => 'players', 'action' => 'view', $pl['id']));
					if ( ! empty($pl['PlayerRanking'][0]['mean'])) {
						echo ' ('.number_format($pl['PlayerRanking'][0]['mean'], 2).')';
					}
				?></li>
			<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>
</div>

<?php if ($adjusting || ! empty($sitting_out)) { ?>
<div class="well" id="team_out">
	<h4>Sitting Out:</h4>
	<ul class="swappable">
	<?php if ( ! empty($sitting_out)) { $pl = $sitting_out['Player']; ?>
		<li id="pl_<?php echo $pl['id']; ?>"><?php
			echo $this->Html->link($pl['name'], array('controller' => 'players', 'action' => 'view', $pl['id']));
			if ( ! empty($pl['PlayerRanking'][0]['mean'])) {
				echo ' ('.number_format($pl['PlayerRanking'][0]['mean'], 2).')';
			}
		?></li>
	<?php } ?>
	</ul>
</div>
<?php } ?>

<?php if ($adjusting || ! empty($the_rest)) { ?>
<div class="well" id="the_rest">
	<h4>Other Players:</h4>
	<ul class="swappable">
	<?php foreach ($the_rest as $player) { $pl = $player['Player']; ?>
		<li id="pl_<?php echo $pl['id']; ?>"><?php
			echo $this->Html->link($pl['name'], array('controller' => 'players', 'action' => 'view', $pl['id']));
			if ( ! empty($pl['PlayerRanking'][0]['mean'])) {
				echo ' ('.number_format($pl['PlayerRanking'][0]['mean'], 2).')';
			}
		?></li>
	<?php } ?>
	</ul>
</div>
<?php } ?>

