
// The purpose of this script is to provide a standards-compliant way to open a new browser window without using the recently deprecated "target" attribute within anchor elements.
addOnLoad( function( ) {
	if ( ! document.getElementsByTagName) {
		return;
	}

	var anchors = document.getElementsByTagName('a');
	for (var i = 0; i < anchors.length; i++) {
		var anchor = anchors[i];
		if (anchor.getAttribute('href') && anchor.getAttribute('rel') == 'external') {
			anchor.target = '_blank';
		}
	}
});

