
<div class="games view">

	<h2><?php echo __('Game'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($game['Game']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Game Type'); ?></dt>
		<dd><?php echo $this->Html->link($game['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $game['GameType']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($game['Game']['name']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Game'), array('action' => 'edit', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game'), array('action' => 'delete', $game['Game']['id']), array('class' => 'delete'), __('Are you sure you want to delete Game #%s?', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?> </li>
	</ul>
</div>

<div class="related well">
	<h3><?php echo __('Related Matches'); ?></h3>

<?php if ( ! empty($game['Match'])) { ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo __('ID'); ?></th>
			<th>Team 1</th>
			<th>Team 2</th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Winning Team'); ?></th>
			<th><?php echo __('Sat Out'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>

	<?php foreach ($game['Match'] as $match) { ?>
		<tr class="table-hover">
			<td><?php echo $match['id']; ?></td>
			<td>
				<strong><?php echo $this->Html->link($match['Team'][0]['name'], array('controller' => 'teams', 'action' => 'view', $match['Team'][0]['id'])); ?></strong><br>
				<?php
					$players = array( );
					foreach ($match['Team'][0]['Player'] as $player) {
						$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
					}
					echo implode(', ', $players);
				?>&nbsp;
			</td>
			<td>
				<strong><?php echo $this->Html->link($match['Team'][1]['name'], array('controller' => 'teams', 'action' => 'view', $match['Team'][1]['id'])); ?></strong><br>
				<?php
					$players = array( );
					foreach ($match['Team'][1]['Player'] as $player) {
						$players[] = $this->Html->link($player['name'], array('controller' => 'players', 'action' => 'view', $player['id']));
					}
					echo implode(', ', $players);
				?>&nbsp;
			</td>
			<td><?php echo h($match['created']); ?>&nbsp;</td>
			<td><?php
				if (0 === $match['winning_team_id']) {
					echo 'Tie';
				}
				elseif ( ! $match['winning_team_id']) {
					echo 'Unfinished';
				}
				else {
					echo $this->Html->link($match['WinningTeam']['name'], array('controller' => 'teams', 'action' => 'view', $match['WinningTeam']['id']));
				}
			?>&nbsp;</td>
			<td><?php echo ( ! empty($match['SatOutPlayer']['name']) ? $this->Html->link($match['SatOutPlayer']['name'], array('controller' => 'players', 'action' => 'view', $match['SatOutPlayer']['id'])) : ''); ?></td>
			<td class="actions">
				<div class="btn-group">
					<?php echo $this->Html->link(__('View'), array('controller' => 'matches', 'action' => 'view', $match['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'matches', 'action' => 'edit', $match['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'matches', 'action' => 'delete', $match['id']), array('class' => 'btn btn-small btn-warning'), __('Are you sure you want to delete Match #%s?', $match['id'])); ?>
				</div>
			</td>
		</tr>
	<?php } ?>

	</table>
<?php } ?>

	<div class="actions">
		<ul class="nav nav-pills">
			<li><?php echo $this->Html->link(__('List Matches'), array('controller' => 'matches', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Match'), array('controller' => 'matches', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

