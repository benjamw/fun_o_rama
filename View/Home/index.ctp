
<?php if ($in_progress) { ?>
<div class="well in_progress">
	<div class="alert alert-block">
		There are unfinished matches.  Please enter the outcome of those matches.
	</div>

	<?php foreach ($in_progress as $match) { ?>
	<div class="match well">
		<strong><?php echo $match['Game']['name']; ?></strong> started on <?php echo date('F j, Y @ h:ia', strtotime($match['Match']['created'])); ?>
		<div class="outcomes pull-right">
			<?php echo $this->Html->link('Adjust', array('controller' => 'matches', 'action' => 'adjust', $match['Match']['id']), array('class' => 'btn btn-mini btn-info')); ?>
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
	<?php } ?>
</div>
<?php } ?>

<?php if ($geobootstrap) { ?>
<marquee behavior="alternate">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</marquee>
<?php } else { ?>
<div class="info">Please select the game and players below, then click on "Create Teams" to get your auto-generated teams!</div>
<?php } ?>

<div class="home form">
	<?php echo $this->Form->create('Match', array('action' => 'start')); ?>
		<fieldset>
			<legend>Start a match</legend>

			<?php echo $this->Form->input('game_id', array('label' => false)); ?>
			<?php echo $this->Form->input('player_id', array('label' => 'Players', 'type' => 'select', 'multiple' => 'checkbox')); ?>
		</fieldset>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
		<?php echo $this->Form->submit(__('Create Teams'), array('class' => 'btn btn-primary', 'div' => ! $geobootstrap)); ?>
		<?php if ($geobootstrap) { echo $this->Html->image('hot.gif'); } ?>
	<?php echo $this->Form->end( ); ?>
</div>

<?php $this->Html->scriptblock('var MATCH_URL = "'.$this->Html->url(array('controller' => 'matches', 'action' => 'update'), true).'";', array('block' => 'script')); ?>
<?php $this->Html->script('index.js', array('block' => 'scriptBottom')); ?>

