<?php
	$single = ((1 === count($tournament['Match'])) && (2 === count($tournament['Team'])));

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

<h3><strong><?php echo $tournament['Game']['name']; ?></strong> started on <span class="date"><?php echo date('F j, Y @ h:ia', strtotime($tournament['Tournament']['created'])); ?></span></h3>

<?php if ($single) { ?>
<div class="alert alert-block alert-info">
	<?php if ($geobootstrap) { ?><marquee behavior="alternate"><?php } ?>
	Feel free to move players around as needed to balance the teams a little better.
	<?php if ($geobootstrap) { ?></marquee><?php } ?>
</div>
<?php } else { ?>
<h4>Teams</h4>
<?php if ( ! empty($tournament['Tournament']['quality'])) { ?>
<h6>Tournament Quality: <?php echo number_format($tournament['Tournament']['quality'], 2); ?>%</h6>
<?php } ?>
<div class="teams well">
	<?php foreach ($tournament['Team'] as $team) { ?>
		<?php echo $this->element('team', array('team' => $team, 'swap' => true, 'link' => true)); ?>
	<?php } ?>
</div>
<?php } ?>

<?php if ($adjusting && ! empty($the_rest)) { ?>
<div class="well" id="the_rest">
	<h5>Other Players:</h5>
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

<?php if ( ! $single) { ?>
<h4>Current Matches</h4>
<div class="matches well">
<?php } ?>
	<?php foreach ($tournament['Match'] as $match) { ?>
	<div class="match well">
		<?php if ( ! empty($match['quality'])) { ?>
		<h6>Match Quality: <?php echo number_format($match['quality'], 2); ?>%</h6>
		<?php } ?>

		<div class="row">
		<?php foreach ($match['Team'] as $team_num => $team) { ?>
			<?php echo $this->element('team', array('team' => $team, 'swap' => $single, 'link' => $single, 'span' => 'span5')); ?>
		<?php } ?>
		</div>
	</div>
	<?php } ?>
<?php if ( ! $single) { ?>
</div>
<?php } ?>

<?php if (false && ($adjusting || ! empty($sitting_out))) { ?>
<div class="well" id="team_out">
	<h5>Sitting Out:</h5>
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


<?php $this->Html->scriptblock('
	var TOURNAMENT_URL = "'.$this->Html->url(array('controller' => 'tournaments', 'action' => 'update')).'",
		pkid = '.$tournament['Tournament']['id'].',
		games = '.json_encode($games_var).';
', array('block' => 'script')); ?>
<?php $this->Html->css('bootstrap-editable.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('bootstrap-editable.min.js', array('block' => 'scriptBottom')); ?>
<?php $this->Html->script('start.js', array('block' => 'scriptBottom')); ?>

