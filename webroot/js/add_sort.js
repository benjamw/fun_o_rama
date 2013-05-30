var counters = {'sort' : 0};

jQuery(document).ready( function($) {

	$('div.select.add .add').live('click', function( ) {
		// get the counter name
		var cntr = 'sort';
		var myregexp = /cnt_(\w+)/;
		var match = myregexp.exec($(this).attr('class'));
		if (match != null) {
			cntr = match[1];
		}

		var $holder = $(this).parent( ).find('div.holder');

		// clone the clone div
		var $clone = $('div.clone', $holder).clone(true);
		$clone = '<div>'+$clone.html( ).replace(/NNN/g, counters[cntr])+'</div>';

		// stick the new clone into the wrapping div
		$holder.append($clone);

		// increment our counter
		++counters[cntr];
	}).css('cursor', 'pointer');

	$('div.select.add .delete').live('click', function( ) {
		$(this).parent( ).remove( );
	}).css('cursor', 'pointer');

});

