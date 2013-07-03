
jQuery('.in_progress').on('click', 'button', function(evt) {
	var $this = $(this);
	var id = $this.attr('id').split('_');

	jQuery.ajax({
		url: TOURNAMENT_URL,
		type: 'POST',
		data: 'match='+ id[1] +'&winner='+ id[2],
		success: function(msg) {
			if ('OK' === msg) {
				$this.closest('.match').remove( );

				if ( ! $('.in_progress').find('.match').length) {
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