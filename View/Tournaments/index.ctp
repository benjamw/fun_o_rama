
<?php if ( ! $active && ! $completed) { ?>
	<h3>No Tournaments to show</h3>
<?php } ?>

<?php if ($active) { ?>
	<h3>Active Tournaments</h3>

	<?php foreach ($active as $tourny) { ?>
	<div class="well clearfix">
		<strong><?php echo Inflector::humanize($tourny['Tournament']['tournament_type']).' '.$tourny['Game']['name']; ?> Tournament</strong> started on <?php echo date('F j, Y @ h:ia', strtotime($tourny['Tournament']['created'])); ?>
<?php // TODO: show the teams and players here ?>
		<?php echo $this->element($tourny['Tournament']['tournament_type'], array('tourny' => $tourny)); ?>
	</div>
	<?php } ?>
<?php } ?>

<?php if ($completed) { ?>
	<h3>Completed Tournaments</h3>

	<?php foreach ($completed as $tourny) { ?>
	<div class="well clearfix">
		<strong><?php echo Inflector::humanize($tourny['Tournament']['tournament_type']).' '.$tourny['Game']['name']; ?> Tournament</strong> started on <?php echo date('F j, Y @ h:ia', strtotime($tourny['Tournament']['created'])); ?>
<?php // TODO: show the teams and players here ?>
		<?php echo $this->element($tourny['Tournament']['tournament_type'], array('tourny' => $tourny)); ?>
	</div>
	<?php } ?>
<?php } ?>

