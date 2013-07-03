
<?php if ($in_progress) { ?>
<div class="well in_progress">
	<div class="alert alert-block">
		There are unfinished matches.  Please enter the outcome of those matches.
	</div>

	<?php foreach ($in_progress as $tourny) { ?>
		<?php if ((1 === $tourny['Tournament']['match_count']) && (2 === $tourny['Tournament']['team_count'])) { ?>
			<?php $match = $tourny['Match'][0]; ?>

	<div class="match well">
		<strong><?php echo $tourny['Game']['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Adjust', array('controller' => 'tournaments', 'action' => 'adjust', $tourny['Tournament']['id']), array('class' => 'btn btn-mini btn-info')); ?>
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][0])); ?>" id="res_<?php echo $match['id'].'_'.$match['Team'][0]['id']; ?>"><?php
				echo $match['Team'][0]['name'].' (Team 1)';
			?></button>
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][1])); ?>" id="res_<?php echo $match['id'].'_'.$match['Team'][1]['id']; ?>"><?php
				echo $match['Team'][1]['name'].' (Team 2)';
			?></button>
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

		<div class="match well well-bright">
			<strong>Match</strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
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

	<?php } ?>
	</div>

		<?php } ?>
	<?php } ?>
</div>
<?php } ?>

<hr />

<?php if ($geobootstrap) { ?>
<marquee behavior="alternate">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</marquee>
<?php } else { ?>
<div class="info">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</div>
<?php } ?>

<div class="home form">
	<?php echo $this->Form->create('Tournament', array('action' => 'start')); ?>
		<fieldset>
			<legend>Start a match / tournament</legend>

			<div class="row">
				<div class="span3">
					<?php echo $this->Form->input('game_id'); ?>
					<?php echo $this->Form->input('tournament_type'); ?>
					<?php echo $this->Form->input('team_size', array('type' => 'select', 'empty' => 'Even Split', 'options' => array_combine(range(4, 1), range(4, 1)))); ?>
					<?php echo $this->Form->input('min_team_size', array('type' => 'select', 'empty' => 'Maximum', 'options' => array_combine(range(4, 1), range(4, 1)))); ?>
				</div>

				<?php echo $this->Form->input('player_id', array('label' => 'Players', 'type' => 'select', 'multiple' => 'checkbox', 'div' => array('class' => 'checkboxes'))); ?>
			</div>
		</fieldset>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
		<?php echo $this->Form->submit(__('Create Teams'), array('class' => 'btn btn-primary', 'div' => ! $geobootstrap)); ?>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
	<?php echo $this->Form->end( ); ?>
</div>

<?php $this->Html->scriptblock('var TOURNAMENT_URL = "'.$this->Html->url(array('controller' => 'tournaments', 'action' => 'update'), true).'";', array('block' => 'script')); ?>
<?php $this->Html->script('home.js', array('block' => 'scriptBottom')); ?>

