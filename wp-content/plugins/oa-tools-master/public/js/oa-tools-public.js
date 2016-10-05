(function( $ ) {
	'use strict';

	var $positions = $('.oaldr-positions');
	if ( $positions.length ) {
		$positions.mixItUp({
			selectors: {
				target: '.oaldr-position'
			},
			load: {
				sort: 'default:desc'
			}
		});
	}

})( jQuery );
