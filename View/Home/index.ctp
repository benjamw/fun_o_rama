
<?php if ($in_progress) { ?>
<div class="well in_progress">
	<div class="alert alert-block">
		There are unfinished matches.  Please enter the outcome of those matches.
	</div>

	<?php foreach ($in_progress as $tourny) { ?>
		<?php if ((1 === $tourny['Tournament']['match_count']) && (2 === $tourny['Tournament']['team_count'])) { ?>
			<?php $match = $tourny['Match'][0]; ?>
			<?php $match_id = $match['id']; ?>

	<div class="tourny match well">
		<strong><?php echo $tourny['Game']['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Tunes', array('controller' => 'songs', 'action' => 'play', 't'.$tourny['Tournament']['id']), array('class' => 'btn btn-mini btn-inverse')); ?>
			<?php echo $this->Html->link('Adjust', array('controller' => 'tournaments', 'action' => 'adjust', $tourny['Tournament']['id']), array('class' => 'btn btn-mini btn-info')); ?>
			<?php foreach ($match['Team'] as $team_num => $team) { ?>

				<?php echo $this->element('team_button', compact('team', 'team_num', 'match_id')); ?>

			<?php } ?>
			<button class="btn btn-mini btn-warning" id="res_<?php echo $match['id'].'_0'; ?>">Tie</button>
			<button class="btn btn-mini btn-danger" id="res_<?php echo $tourny['Tournament']['id'].'_null'; ?>">Didn't Play</button>
		</div>
	</div>

		<?php } else { ?>

	<div class="tourny well">
		<strong><?php echo Inflector::humanize($tourny['Tournament']['tournament_type']).' '.$tourny['Game']['name']; ?> Tournament</strong> started on <?php echo date('F j, Y @ h:ia', strtotime($tourny['Tournament']['created'])); ?>
		<div class="pull-right">
			<?php echo $this->Html->link('Adjust', array('controller' => 'tournaments', 'action' => 'adjust', $tourny['Tournament']['id']), array('class' => 'btn btn-mini btn-info')); ?>
			<button class="btn btn-mini btn-danger" id="res_<?php echo $tourny['Tournament']['id'].'_null'; ?>">Didn't Play</button>
		</div>
		<br><br>
	<?php foreach ($tourny['Match'] as $match) { ?>

		<?php echo $this->element('match', compact('match')); ?>

	<?php } ?>
	</div>

		<?php } ?>
	<?php } ?>
</div>
<?php } ?>

<hr />

<marquee behavior="alternate" class="geobootstrap">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</marquee>
<div class="info not_geobootstrap">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</div>

<div class="home form">
	<?php echo $this->Form->create('Tournament', array('url' => array('controller' => 'tournaments', 'action' => 'start'))); ?>
		<fieldset>
			<legend>Start a match / tournament</legend>

			<div class="row">
				<div class="span3">
					<?php echo $this->Form->input('game_id'); ?>
					<?php echo $this->Form->input('tournament_type', array('value' => 'single_elimination')); ?>
					<?php echo $this->Form->input('ranked', array('type' => 'checkbox', 'checked' => 'checked')); ?>
					<?php echo $this->Form->input('team_size', array('type' => 'select', 'empty' => array('even' => 'Even Split'), 'options' => array_combine(range(4, 1), range(4, 1)))); ?>
					<?php echo $this->Form->input('min_team_size', array('type' => 'select', 'empty' => array('max' => 'Maximum'), 'options' => array_combine(range(4, 1), range(4, 1)))); ?>
					<?php echo $this->Form->input('build_method', array('type' => 'radio', 'options' => array('best' => 'Best Match', 'random' => 'Random Teams'), 'value' => 'best', 'legend' => false)); ?>
				</div>

				<?php echo $this->Form->input('player_id', array('label' => 'Players', 'type' => 'select', 'multiple' => 'checkbox', 'div' => array('class' => 'checkboxes span9'))); ?>
			</div>
		</fieldset>
		<?php echo $this->Html->image('hot.gif', array('class' => 'geobootstrap')); ?>
		<?php echo $this->Form->submit(__('Create Teams'), array('class' => 'btn btn-primary', 'div' => false)); ?>
		<?php echo $this->Html->image('hot.gif', array('class' => 'geobootstrap')); ?>
	<?php echo $this->Form->end( ); ?>
</div>

<?php $this->Html->scriptblock('var TOURNAMENT_URL = "'.$this->Html->url(array('controller' => 'tournaments', 'action' => 'update'), true).'";', array('block' => 'script')); ?>
<?php $this->Html->script('home.js', array('block' => 'scriptBottom')); ?>

