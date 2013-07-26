<?php $this->Html->css('tablesorter.css', null, array('block' => 'css')); ?>
<?php $this->Html->script('jquery.tablesorter.js', array('block' => 'script')); ?>
<?php $this->Html->script('round_robin.js', array('block' => 'scriptBottom')); ?>

<div class="well" id="tourny_<?php echo $tourny['Tournament']['id']; ?>">
	<table class="table table-striped table-bordered table-condensed tablesorter round_robin">
		<thead>
			<tr>
				<th class="header">Team</th>
				<th class="header">Seed</th>
				<th class="header">Wins</th>
				<th class="header">Losses</th>
				<th class="header">Matches Remaining</th>
			</tr>
		</thead>
		<tbody>

		<?php foreach ($tourny['Results'] as $result) { ?>
			<tr class="table-hover">
<?php // TODO: replace this with a more detailed team name with hover states and all that ?>
				<td class="name"><?php echo $result['name']; ?></td>
				<td><?php echo $result['start_seed']; ?></td>
				<td><?php echo $result['wins']; ?></td>
				<td><?php echo $result['losses']; ?></td>
				<td><?php echo $result['remaining_matches']; ?></td>
			</tr>
		<?php } ?>

		</tbody>
	</table>
</div>

