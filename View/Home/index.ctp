
<?php if ($in_progress) { ?>
<div class="well in_progress">
	<div class="alert alert-block">
		There are unfinished matches.  Please enter the outcome of those matches.
	</div>

	<?php foreach ($in_progress as $tourny) { ?>
		<?php if ((1 === $tourny['Tournament']['match_count']) && (2 === $tourny['Tournament']['team_count'])) { ?>
			<?php $match = $tourny['Match'][0]; ?>

	<div class="tourny match well">
		<strong><?php echo $tourny['Game']['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Adjust', array('controller' => 'tournaments', 'action' => 'adjust', $tourny['Tournament']['id']), array('class' => 'btn btn-mini btn-info')); ?>
			<?php
				foreach ($match['Team'] as $team_num => $team) {
					$team_players = '<div class="player_popover">';
					foreach ($team['Player'] as $player) {
						if ( ! empty($player['avatar']['main'])) {
							$image = $this->Html->image($player['avatar']['main'], array('alt' => $player['name']));
						}
						else {
							$image = $this->Identicon->create($player['id']);
						}

						$team_players .= '<figure>'.$image.'<figcaption>'.$player['name'].'</figcaption></figure>';
					}
					$team_players .= '</div>';

					echo $this->Form->button($team['name'].'  (Team '.($team_num + 1).')', array(
						'type' => 'button',
						'class' => 'btn btn-mini btn-success teams',
						'id' => 'res_'.$match['Match']['id'].'_'.$team['id'],
//						'title' => implode(', ', Set::extract('/Player/name', $team)),
						'data-html' => 'true',
						'data-placement' => 'top',
						'data-trigger' => 'hover',
						'data-title' => $team['name'].'  (Team '.($team_num + 1).')',
						'data-content' => str_replace('"', "'", $team_players),
					));
				}
			?>
			<button class="btn btn-mini btn-warning" id="res_<?php echo $match['Match']['id'].'_0'; ?>">Tie</button>
			<button class="btn btn-mini btn-danger" id="res_<?php echo $match['Match']['id'].'_null'; ?>">Didn't Play</button>
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

		<?php echo $this->element('match', array('match' => $match)); ?>

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
		<?php echo $this->Html->image('hot.gif', array('class' => 'geobootstrap')); ?>
		<?php echo $this->Form->submit(__('Create Teams'), array('class' => 'btn btn-primary', 'div' => false)); ?>
		<?php echo $this->Html->image('hot.gif', array('class' => 'geobootstrap')); ?>
	<?php echo $this->Form->end( ); ?>
</div>

<?php $this->Html->scriptblock('var TOURNAMENT_URL = "'.$this->Html->url(array('controller' => 'tournaments', 'action' => 'update'), true).'";', array('block' => 'script')); ?>
<?php $this->Html->script('home.js', array('block' => 'scriptBottom')); ?>

