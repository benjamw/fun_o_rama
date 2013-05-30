
<div class="playerRankings view">

	<h2><?php echo __('Player Ranking'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($playerRanking['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerRanking['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Game Type'); ?></dt>
		<dd><?php echo $this->Html->link($playerRanking['GameType']['name'], array('controller' => 'game_types', 'action' => 'view', $playerRanking['GameType']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Mean'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['mean']); ?>&nbsp;</dd>

		<dt><?php echo __('Std Deviation'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['std_deviation']); ?>&nbsp;</dd>

		<dt><?php echo __('Games Played'); ?></dt>
		<dd><?php echo h($playerRanking['PlayerRanking']['games_played']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Player Ranking'), array('action' => 'edit', $playerRanking['PlayerRanking']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Player Ranking'), array('action' => 'delete', $playerRanking['PlayerRanking']['id']), array('class' => 'delete'), __('Are you sure you want to delete Player Ranking #%s?', $playerRanking['PlayerRanking']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Player Rankings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Player Ranking'), array('action' => 'add')); ?> </li>
	</ul>
</div>

