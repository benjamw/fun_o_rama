
jQuery('#win_loss').tablesorter({
	sortInitialOrder: 'desc'
});

jQuery('td.name').on('click', function(evt) {
	jQuery(this).closest('table').find('tr.success').removeClass('success')
		.end( ).end( ).closest('tr').addClass('success');
});

