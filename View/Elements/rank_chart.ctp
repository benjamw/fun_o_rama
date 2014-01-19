<?php $this->Html->script('moment.min.js', array('block' => 'script')); ?>
<?php $this->Html->script('flot/jquery.flot.js', array('block' => 'script')); ?>
<?php $this->Html->script('flot/jquery.flot.time.js', array('block' => 'script')); ?>

<div class="chart" id="rank_<?php echo $ranking['id']; ?>"></div>

<?php

	$min = $max = 25;
	$data = array( );
	foreach ($ranking['RankHistory'] as $history) {
		$data[] = array(
			strtotime($history['created']) * 1000,
			$history['mean'],
		);

		if ($history['mean'] < $min) {
			$min = $history['mean'];
		}

		if ($history['mean'] > $max) {
			$max = $history['mean'];
		}
	}

	// round to nearest 5
	$disp_min = round(($min - (5 / 2)) / 5) * 5;
	while ($disp_min >= $min) {
		$disp_min -= 5;
	}

	$disp_max = round(($max + (5 / 2)) / 5) * 5;
	while ($disp_max <= $max) {
		$disp_max += 5;
	}

	// make sure it didn't round the wrong way

	$this->Html->scriptblock("
		(function ($) {

			\"use strict\";

			var data = ".json_encode($data).",
				plot = $.plot(
					$('#rank_".$ranking['id']."'),
					[
						{
							data: data,
							label: 'Rank'
						}
					],
					{
						series: {
							lines: { show: true },
							points: { show: true }
						},
						grid: {
							hoverable: true,
							clickable: true
						},
						xaxis: {
							mode: 'time',
							timezone: 'browser'
						},
						yaxis: {
							min: {$disp_min},
							max: {$disp_max}
						}
					}
				);

			$('<div id=\"tooltip_".$ranking['id']."\" class=\"chart_tooltip\"></div>').appendTo('body');

			$('#rank_".$ranking['id']."').bind('plothover', function (event, pos, item) {
				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$('#tooltip_".$ranking['id']."').html(item.series.label +' on '+ moment.unix(x / 1000).format('MMM D, YYYY h:mm a') +' = '+ y)
						.css({top: item.pageY - 25, left: item.pageX + 10})
						.fadeIn(200);
				}
				else {
					$('#tooltip_".$ranking['id']."').hide( );
				}
			});

		}(jQuery));
	", array('block' => 'scriptBottom'));

