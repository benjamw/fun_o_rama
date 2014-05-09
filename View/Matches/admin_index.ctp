
<div class="matches index">
	<h2><?php echo __('Matches'); ?> <?php echo $this->Html->link(__('New Match'), array('action' => 'add'), array('class' => 'btn btn-mini btn-info')); ?></h2>

	<div class="clearfix">
		<?php echo $this->element('admin_filter'); ?>
	</div>

	<?php echo $this->element('bootstrap_pagination'); ?>

	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('tournament_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('quality'); ?></th>
			<th>Team 1</th>
			<th>Team 2</th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('winning_team_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($matches as $match) { ?>
		<tr class="table-hover">
			<td><?php echo h($match['Match']['id']); ?>&nbsp;</td>
			<td><?php echo $this->Html->link($match['Tournament']['Game']['name'], array('controller' => 'games', 'action' => 'view', $match['Tournament']['Game']['id'])).' @ '.$this->Html->link($match['Tournament']['created'], array('controller' => 'tournaments', 'action' => 'view', $match['Tournament']['id'])); ?>&nbsp;</td>
			<td><?php echo h($match['Match']['name']); ?>&nbsp;</td>
			<td><?php echo h($match['Match']['quality']); ?>&nbsp;</td>
			<td>
				<strong><?php echo $this->Html->link($match['Team'][0]['name'] ?: 'Team 1', array('controller' => 'teams', 'action' => 'view', $match['Team'][0]['id'])); ?></strong><br>
				<?php
					$players = array( );
					foreach ($match['Team'][0]['Player'] as $player) {
						$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
					}
					echo implode(', ', $players);
				?>&nbsp;
			</td>
			<td>
				<strong><?php echo $this->Html->link(($match['Team'][1]['name'] ?: 'Team 2'), array('controller' => 'teams', 'action' => 'view', $match['Team'][1]['id'])); ?></strong><br>
				<?php
					$players = array( );
					foreach ($match['Team'][1]['Player'] as $player) {
						$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
					}
					echo implode(', ', $players);
				?>&nbsp;
			</td>
			<td><?php echo h($match['Match']['created']); ?>&nbsp;</td>
			<td><?php
				if (0 === (int) $match['Match']['winning_team_id']) {
					echo 'Tie';
				}
				elseif ( ! $match['Match']['winning_team_id']) {
					echo 'Unfinished';
				}
				else {
					if ( ! empty($match['WinningTeam']['name'])) {
						echo $match['WinningTeam']['name'].' &mdash;&nbsp;';
					}

					foreach ($match['Team'] as $n => $team) {
						if ((int) $team['id'] === (int) $match['Match']['winning_team_id']) {
							echo 'Team&nbsp;'.($n + 1);
							break;
						}
					}
				}
			?>&nbsp;</td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $match['Match']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $match['Match']['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Undo'), array('action' => 'undo', $match['Match']['id']), array('class' => 'btn btn-small btn-info'), __('Are you sure you want to undo Match #%s?', $match['Match']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $match['Match']['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Match #%s?', $match['Match']['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $this->element('bootstrap_pagination'); ?>
</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('New Match'), array('action' => 'add')); ?></li>
	</ul>
</div>

