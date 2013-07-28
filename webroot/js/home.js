
jQuery('.in_progress').on('click', 'button', function(evt) {
	var $this = $(this),
		$tourny = $this.closest('.tourny'),
		id = $this.attr('id').split('_');

	jQuery.ajax({
		url: TOURNAMENT_URL,
		type: 'POST',
		data: 'match='+ id[1] +'&winner='+ id[2],
		success: function(msg) {
			if ('OK' === msg.slice(0, 2)) {
				if ('null' === id[2]) {
					$this.closest('.tourny').remove( );
				}
				else {
					$this.closest('.match').remove( );

					if ('' !== msg.slice(2)) {
						$tourny
							.find('.match').remove( ).end( )
							.append(msg.slice(2));
					}

					if ( ! $tourny.find('.match').length) {
						$tourny.remove( );
					}
				}

				if ( ! $('.in_progress').find('.tourny').length) {
					$('.in_progress').remove( );
				}
			}
			else {
				alert(msg);
			}
		}
	});

	return false; // stop everything
});

