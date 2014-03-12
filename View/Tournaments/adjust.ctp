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

	$game_type_id = $tournament['Game']['game_type_id'];

?>

<h3><strong><?php echo $tournament['Game']['name']; ?></strong> started on <span class="date"><?php echo date('F j, Y @ h:ia', strtotime($tournament['Tournament']['created'])); ?></span></h3>

<div class="alert alert-block alert-info">
	<marquee behavior="alternate" class="geobootstrap">Feel free to move players around as needed to balance the teams a little better.</marquee>
	<span class="not_geobootstrap">Feel free to move players around as needed to balance the teams a little better.</span>
</div>

<h4>Teams</h4>
<?php if ( ! empty($tournament['Tournament']['quality'])) { ?>
<h6>Tournament Quality: <?php echo number_format($tournament['Tournament']['quality'], 2); ?>%</h6>
<?php } ?>
<div class="teams well">
	<?php
		$swap = $link = true;
		foreach ($tournament['Team'] as $team) {
			echo $this->element('team_block', compact('team', 'game_type_id', 'swap', 'link'));
		}
	?>
</div>

<?php if ($adjusting || ! empty($tournament['SittingOut'])) { ?>
<div class="well" id="team_out">
	<h5>Sitting Out:</h5>
	<ul class="swappable">
	<?php
		foreach ($tournament['SittingOut'] as $sitting_out) {
			$player = $sitting_out['Player'];
			echo $this->element('player_li', compact('player', 'game_type_id', 'link'));
		}
	?>
	</ul>
</div>
<?php } ?>

<?php if ($adjusting) { ?>
<div class="well" id="the_rest">
	<h5>Other Players:</h5>
	<ul class="swappable">
	<?php
		foreach ($the_rest as $player) {
			echo $this->element('player_li', compact('player', 'game_type_id', 'link'));
		}
	?>
	</ul>
</div>
<?php } ?>


<h4>Current Matches</h4>
<div class="matches well">

	<?php foreach ($tournament['Match'] as $match) { ?>
	<div class="match well">
		<?php if ( ! empty($match['quality'])) { ?>
		<h6>Match Quality: <?php echo number_format($match['quality'], 2); ?>%</h6>
		<?php } ?>

		<div class="row">
		<?php
			$span = 'span5';
			$swap = $link = $single;
			foreach ($match['Team'] as $team_num => $team) {
				echo $this->element('team_block', compact('team', 'game_type_id', 'swap', 'link', 'span'));
			}
		?>
		</div>
	</div>
	<?php } ?>
</div>


<?php $this->Html->scriptblock('
	var TOURNAMENT_URL = "'.$this->Html->url(array('controller' => 'tournaments', 'action' => 'update')).'",
		pkid = '.$tournament['Tournament']['id'].',
		games = '.json_encode($games_var).';
', array('block' => 'script')); ?>
<?php $this->Html->css('bootstrap-editable.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('bootstrap-editable.min.js', array('block' => 'scriptBottom')); ?>
<?php $this->Html->script('start.js', array('block' => 'scriptBottom')); ?>

