
var jqXHR = false;

jQuery('h2.name').find('span.clickable').on('click', function( ) {
	var $elem = $(this),
		team_num = $elem.closest('.well').attr('id').split('_'),
		team_id = jQuery('#team_'+ team_num[1] +' ul').attr('id').slice(5);

	jQuery.ajax({
		url: MATCH_UPDATE_URL,
		type: 'POST',
		data: 'rename='+team_id,
		success: function(msg) {
			$elem.text(msg);
		}
	});
}).css({'cursor': 'pointer', 'border-bottom': '1px dashed #08c'});

jQuery('#team_1 ul, #team_2 ul, #team_out ul, #the_rest ul').sortable({
	connectWith: '.swappable',
	placeholder: 'ui-state-highlight',
	tolerance: 'pointer',
	stop: function( ) {
		var team1 = jQuery('#team_1 ul').sortable('serialize', { key: 'team1[]' });
		team1 += '&team1_id='+jQuery('#team_1 ul').attr('id').slice(5);

		var team2 = jQuery('#team_2 ul').sortable('serialize', { key: 'team2[]' });
		team2 += '&team2_id='+jQuery('#team_2 ul').attr('id').slice(5);

		var team_out = jQuery('#team_out ul').sortable('serialize', { key: 'sat_out[]' });

		if (jqXHR) {
			jqXHR.abort( );
			jqXHR = false;
		}

		jqXHR = jQuery.ajax({
			url: MATCH_UPDATE_URL,
			type: 'POST',
			data: 'match_id='+pkid+'&'+team1+'&'+team2+'&'+team_out,
			success: function( ) {
				jqXHR = false;
			}
		});
	}
}).disableSelection( );

jQuery.fn.editable.defaults.mode = 'inline';
jQuery('h1 strong').editable({
	type: 'select',
	url: MATCH_UPDATE_URL,
	name: 'game_id',
	pk: pkid,
	source: games
}).css('cursor', 'pointer');

