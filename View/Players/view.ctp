
<div class="players view">

	<h2><?php echo $player['Player']['name']; ?>'s Amazing Stuff!!</h2>

	<div class="well">
		<h3>Ranking</h3>
		Coming Soon
	</div>

	<?php if ( ! empty($player['Badge'])) { ?>
	<div class="well">
		<h3 class="badges" data-href="<?php echo $this->Html->url(array('admin' => true, 'prefix' => 'admin', 'controller' => 'players', 'action' => 'badges', $player['Player']['id'])); ?>">Badges</h3>

		<ul class="badges">
		<?php foreach ($player['Badge'] as $badge) { ?>
			<li><?php
				$popover = array(
					'data-animation' => 'true',
					'data-placement' => 'bottom',
					'data-trigger' => 'hover',
					'data-title' => $badge['name'],
					'data-content' => $badge['description'],
					'data-delay' => 250,
				);

				if ( ! empty($badge['icon']['main'])) {
					echo $this->Html->image($badge['icon']['main'], array_merge($popover, array('alt' => $badge['name'])));
				}
				else {

					echo '<span class="badge badge-inverse" '.implode_full($popover, ' ').'>'.$badge['name'].'</span>';
				}
			?></li>
		<?php } ?>
		</ul>

		<div style="clear:both;height:50px;">&nbsp;</div><!-- padding for the popovers -->
	</div>
	<?php } ?>

</div>

<?php $this->Html->scriptblock('
	jQuery("ul.badges li").find("img, span").popover( );
	jQuery("h3.badges").on("click", function( ) { window.location = jQuery(this).data("href"); });
', array('block' => 'scriptBottom')); ?>
