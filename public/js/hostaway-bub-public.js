

// var elementHeights = $('.team-member-info').map(function() {
//     return $(this).height();
// }).get();

// // Math.max takes a variable number of arguments
// // `apply` is equivalent to passing each height as an argument
// var maxHeight = Math.max.apply(null, elementHeights);

// // Set each height to the max height
// $('.team-member-info').height(maxHeight);


(function( $ ) {
	'use strict';


	var dataContainer = jQuery('.listings-wrapper');

	if(dataContainer.length != 0) { // avoid running script on pages without team block
		var dataMixer = mixitup(dataContainer);

		$('.listings-filter li').each(function(){
			$(this).click(function(){
				$('.listings-filter li.active').removeClass('active');
				var selectedValue = $(this).attr('value');
				$(this).addClass('active');

				if(selectedValue == 'all') {
					dataMixer.filter(selectedValue);
				} else {
					dataMixer.filter('.'+selectedValue)
				}
			});
		});

		// console.log(dataMixer);
	}


})( jQuery );
