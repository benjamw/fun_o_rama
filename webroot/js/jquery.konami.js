;(function($) {

	"use strict";

	$.fn.konami = function(callback, code) {
		code = code || "38,38,40,40,37,39,37,39,66,65";

		return this.each( function(idx, elem) {
			var kkeys = [ ];

			$(elem).on('keydown', function(e) {
				kkeys.push(e.which);

				while (kkeys.length > code.split(',').length) {
					kkeys.shift( );
				}

				if (-1 !== kkeys.toString( ).indexOf(code)) {
					callback(e);
				}
			});
		});
	}

}(jQuery));


jQuery(window).konami( function( ) {
	$('body').toggleClass('geobs');
});

