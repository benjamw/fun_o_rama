
<div class="playerStats view">

	<h2><?php echo __('Player Stat'); ?></h2>
	<dl>

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($playerStat['PlayerStat']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($playerStat['Player']['name'], array('controller' => 'players', 'action' => 'view', $playerStat['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Game'); ?></dt>
		<dd><?php echo $this->Html->link($playerStat['Game']['name'], array('controller' => 'games', 'action' => 'view', $playerStat['Game']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Wins'); ?></dt>
		<dd><?php echo h($playerStat['PlayerStat']['wins']); ?>&nbsp;</dd>

		<dt><?php echo __('Draws'); ?></dt>
		<dd><?php echo h($playerStat['PlayerStat']['draws']); ?>&nbsp;</dd>

		<dt><?php echo __('Losses'); ?></dt>
		<dd><?php echo h($playerStat['PlayerStat']['losses']); ?>&nbsp;</dd>

		<dt><?php echo __('Streak'); ?></dt>
		<dd><?php echo h($playerStat['PlayerStat']['streak']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Player Stat'), array('action' => 'edit', $playerStat['PlayerStat']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Player Stat'), array('action' => 'delete', $playerStat['PlayerStat']['id']), array('class' => 'delete'), __('Are you sure you want to delete Player Stat #%s?', $playerStat['PlayerStat']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Player Stats'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Player Stat'), array('action' => 'add')); ?> </li>
	</ul>
</div>

