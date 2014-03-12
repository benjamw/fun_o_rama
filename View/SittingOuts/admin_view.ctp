
<div class="sittingOuts view">

	<h3><?php echo __('Sitting Out'); ?></h3>
	<dl class="dl-horizontal">

		<dt><?php echo __('ID'); ?></dt>
		<dd><?php echo h($sittingOut['SittingOut']['id']); ?>&nbsp;</dd>

		<dt><?php echo __('Tournament'); ?></dt>
		<dd><?php echo $this->Html->link($sittingOut['Tournament']['created'], array('controller' => 'tournaments', 'action' => 'view', $sittingOut['Tournament']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Player'); ?></dt>
		<dd><?php echo $this->Html->link($sittingOut['Player']['name'], array('controller' => 'players', 'action' => 'view', $sittingOut['Player']['id'])); ?>&nbsp;</dd>

		<dt><?php echo __('Created'); ?></dt>
		<dd><?php echo h($sittingOut['SittingOut']['created']); ?>&nbsp;</dd>

	</dl>

</div>
<div class="actions">
	<ul class="nav nav-pills">
		<li><?php echo $this->Html->link(__('Edit Sitting Out'), array('controller' => 'sitting_outs', 'action' => 'edit', $sittingOut['SittingOut']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Sitting Out'), array('controller' => 'sitting_outs', 'action' => 'delete', $sittingOut['SittingOut']['id']), array('class' => 'delete'), __('Are you sure you want to delete Sitting Out #%s?', $sittingOut['SittingOut']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Sitting Outs'), array('controller' => 'sitting_outs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sitting Out'), array('controller' => 'sitting_outs', 'action' => 'add')); ?> </li>
	</ul>
</div>

