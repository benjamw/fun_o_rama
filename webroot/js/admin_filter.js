
var $compare = jQuery('#AdminFilterCompare');
var $value = jQuery('#AdminFilterValue');
var $select = jQuery('#AdminFilterSelect');

show_inputs( );

// ajax off and grab the select values if we need them
jQuery('#AdminFilterItem').change( function( ) {
	$select.html('<option>Loading...</option>').val('');
	show_inputs( );

	if ('Related' == jQuery(this).find('option:selected').parent( ).attr('label')) {
		jQuery.ajax({
			type: 'GET',
			url: FILTER_ROOT_URL+'/filter_select/'+jQuery('#AdminFilterItem').val( ),
			success: function(msg) {
				$select.html(msg);
			}
		});
	}
});


function show_inputs( ) {
	if (jQuery('#AdminFilterItem').find('option:selected').parent( ).is('select')) {
		$compare.hide( );
		$value.hide( );
		$select.hide( );
	}
	else if ('Related' == jQuery('#AdminFilterItem').find('option:selected').parent( ).attr('label')) {
		$compare.hide( );
		$value.hide( );
		$select.show( );
	}
	else {
		$select.hide( );
		$compare.show( );
		$value.show( );
	}
}

