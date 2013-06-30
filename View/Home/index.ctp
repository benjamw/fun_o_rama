
<?php if ($in_progress) { ?>
<div class="well in_progress">
	<div class="alert alert-block">
		There are unfinished matches.  Please enter the outcome of those matches.
	</div>

	<?php foreach ($in_progress as $tourny) { ?>
	<?php ?>
	<div class="match well">
		<strong><?php echo $match['Game']['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['Match']['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Adjust', array('controller' => 'matches', 'action' => 'adjust', $match['Match']['id']), array('class' => 'btn btn-mini btn-info')); ?>
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][0])); ?>" id="res_<?php echo $match['Match']['id'].'_'.$match['Team'][0]['id']; ?>"><?php echo $match['Team'][0]['name']; ?> (Team 1)</button>
			<button class="btn btn-mini btn-success" title="<?php echo implode(', ', Set::extract('/Player/name', $match['Team'][1])); ?>" id="res_<?php echo $match['Match']['id'].'_'.$match['Team'][1]['id']; ?>"><?php echo $match['Team'][1]['name']; ?> (Team 2)</button>
			<button class="btn btn-mini btn-warning" id="res_<?php echo $match['Match']['id'].'_0'; ?>">Tie</button>
			<button class="btn btn-mini btn-danger" id="res_<?php echo $match['Match']['id'].'_null'; ?>">Didn't Play</button>
		</div>
	</div>
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
					<?php echo $this->Form->input('team_size', array('type' => 'select', 'empty' => 'Even Split', 'options' => array_combine(range(1, 4), range(1, 4)))); ?>
				</div>

				<?php echo $this->Form->input('player_id', array('label' => 'Players', 'type' => 'select', 'multiple' => 'checkbox', 'div' => array('class' => 'checkboxes'))); ?>
			</div>
		</fieldset>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
		<?php echo $this->Form->submit(__('Create Teams'), array('class' => 'btn btn-primary', 'div' => ! $geobootstrap)); ?>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
	<?php echo $this->Form->end( ); ?>
</div>

<?php $this->Html->scriptblock('var MATCH_URL = "'.$this->Html->url(array('controller' => 'matches', 'action' => 'update'), true).'";', array('block' => 'script')); ?>
<?php $this->Html->script('index.js', array('block' => 'scriptBottom')); ?>

