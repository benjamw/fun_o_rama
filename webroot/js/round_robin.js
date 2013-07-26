
jQuery('table.round_robin').tablesorter({ sortList:[[2,1],[3,0]] });

jQuery('td.name').on('click', function(evt) {
	jQuery(this).closest('table').find('tr.success').removeClass('success')
		.end( ).end( ).closest('tr').addClass('success');
});

