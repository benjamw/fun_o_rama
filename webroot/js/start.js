

jQuery('div.team').find('.name').find('span.clickable').on('click', function( ) {
	var team_id = $(this).closest('.well').attr('id').split('_');

	jQuery.ajax({
		url: TOURNAMENT_URL,
		type: 'POST',
		data: 'rename='+team_id[1],
		success: function(msg) {
			jQuery('.'+team_id[0]+'_'+team_id[1]).find('.name').find('span.clickable').text(msg);
		}
	});
}).css({'cursor': 'pointer', 'border-bottom': '1px dashed #08c'});

var jqXHR = false;
jQuery('ul.swappable').sortable({
	connectWith: '.swappable',
	placeholder: 'ui-state-highlight',
	tolerance: 'pointer',
	stop: function( ) {
		data = 'tournament_id='+pkid;

		jQuery('ul.swappable').each( function(idx, elem) {
			data = data+'&'+jQuery(elem).sortable('serialize', {
				key: jQuery(elem).closest('.well').attr('id')+'[]',
				expression: /^(.+)$/
			});
		});

		if (jqXHR) {
			jqXHR.abort( );
			jqXHR = false;
		}

		jqXHR = jQuery.ajax({
			url: TOURNAMENT_URL,
			type: 'POST',
			data: data,
			success: function( ) {
				jqXHR = false;
			}
		});
	}
}).disableSelection( );

jQuery.fn.editable.defaults.mode = 'inline';
jQuery('h3 strong').editable({
	type: 'select',
	url: TOURNAMENT_URL,
	name: 'game_id',
	pk: pkid,
	source: games
}).css('cursor', 'pointer');

