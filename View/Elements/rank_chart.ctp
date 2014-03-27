<?php $this->Html->script('moment.min.js', array('block' => 'script')); ?>
<?php $this->Html->script('flot/jquery.flot.js', array('block' => 'script')); ?>
<?php $this->Html->script('flot/jquery.flot.time.js', array('block' => 'script')); ?>

<div class="chart" id="rank_<?php echo $ranking['id']; ?>"></div>

<?php

	$min = $max = 25;
	$data = array( );
	foreach ($ranking['RankHistory'] as $history) {
		$mean = (float) $history['mean'];

		$data[] = array(
			strtotime($history['created']) * 1000,
			$mean,
		);

		if ($mean < $min) {
			$min = $mean;
		}

		if ($mean > $max) {
			$max = $mean;
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

	$this->Html->scriptblock("
		(function ($) {

			\"use strict\";

			var data = ".json_encode($data).",
				dialog = function(title, contents, options, buttons) {
					if ('string' !== typeof title) {
						options = title;

						title = options.title;
						contents = options.contents;
						buttons = options.buttons;
					}

					title = String(title);
					contents = String(contents);
					options = options || { };
					buttons = buttons || [ ];

					options.modal = true;
					options.title = title;
					options.show = 'fade';
					options.hide = 'fade';
					options.minWidth = 600;
					options.buttons = buttons;
					options.close = function( ) {
						$(this).dialog('destroy');
					};
					options.open = function( ) {
						$('.ui-dialog-titlebar-close')
							.addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close')
							.append('<span class=\"ui-button-icon-primary ui-icon ui-icon-closethick\"></span><span class=\"ui-button-text\">close</span>');
					};

					$('<div>'+ contents +'</div>').dialog(options);

					$('.ui-widget-overlay').on('click', function ( ) {
						$('.ui-dialog-titlebar-close').trigger('click');
					});
				},
				pull_dialog = function(label, datapoint) {
					$.ajax({
						type: 'GET',
						url: ROOT_URL +'player_rankings/flot/',
						data: {
							name: label,
							game: {$ranking['GameType']['id']},
							time: datapoint[0],
							mean: datapoint[1]
						},
						dataType: 'html',
						success: function (data) {
							dialog(moment(datapoint[0]).format('ddd, MMM Do, YYYY @ H:mm a'), data);
						}
					});
				},
				yaxis_marking = { yaxis: { from: 25, to: 25 }, color: '#aaa' },
				weekendAreas = function(axes) {
					var i,
						markings = [ ],
						d = new Date(axes.xaxis.min);

					// go to the first Saturday

					d.setUTCDate(d.getUTCDate( ) - ((d.getUTCDay( ) + 1) % 7));
					d.setUTCSeconds(0);
					d.setUTCMinutes(0);
					d.setUTCHours(0);

					i = d.getTime( );

					// when we don't set yaxis, the rectangle automatically
					// extends to infinity upwards and downwards

					do {
						markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
						i += 7 * 24 * 60 * 60 * 1000;
					}
					while (i < axes.xaxis.max);

					// push this in last so it doesn't get covered
					markings.push(yaxis_marking);

					return markings;
				},
				plot = $.plot(
					$('#rank_{$ranking['id']}'),
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
							clickable: true,
							markings: weekendAreas
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

			$('<div id=\"tooltip_{$ranking['id']}\" class=\"chart_tooltip\"></div>').appendTo('body');

			$('#rank_{$ranking['id']}').bind('plothover', function (event, pos, item) {
				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$('#tooltip_{$ranking['id']}').html(item.series.label +' on '+ moment.unix(x / 1000).format('MMM D, YYYY h:mm a') +' = '+ y)
						.css({top: item.pageY - 25, left: item.pageX + 10})
						.fadeIn(200);
				}
				else {
					$('#tooltip_{$ranking['id']}').hide( );
				}
			});

			$('#rank_{$ranking['id']}').bind('plotclick', function (event, pos, item) {
				plot.unhighlight( );

				if (item) {
					plot.highlight(item.series, item.datapoint);

					// pop a small dialog box that shows all the players in that game,
					// their rankings after that game, and their ranking change (+- X.XX),
					// and the rest of the tournament data
					pull_dialog({$ranking['player_id']}, item.datapoint);
				}
			});

		}(jQuery));
	", array('block' => 'scriptBottom'));

